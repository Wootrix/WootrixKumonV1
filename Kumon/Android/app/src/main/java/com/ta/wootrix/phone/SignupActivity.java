package com.ta.wootrix.phone;

import android.content.Intent;
import android.content.pm.ActivityInfo;
import android.content.res.Configuration;
import android.os.Bundle;
import android.text.Spannable;
import android.text.SpannableString;
import android.text.TextPaint;
import android.text.method.LinkMovementMethod;
import android.text.style.ClickableSpan;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.TextView.BufferType;
import android.widget.Toast;

import com.google.android.gms.analytics.HitBuilders;
import com.google.android.gms.analytics.Tracker;
import com.ta.wootrix.R;
import com.ta.wootrix.asynctask.IAsyncCaller;
import com.ta.wootrix.asynctask.ServerIntractorAsync;
import com.ta.wootrix.firebase.AnalyticsApplication;
import com.ta.wootrix.modle.Error;
import com.ta.wootrix.modle.IModel;
import com.ta.wootrix.modle.ResponseMsgInfo;
import com.ta.wootrix.parser.MessageParser;
import com.ta.wootrix.restapi.APIUtils;
import com.ta.wootrix.restapi.APIUtils.REQUEST_TYPE;
import com.ta.wootrix.utils.Utility;

import org.json.JSONObject;

import java.util.ArrayList;

public class SignupActivity extends BaseActivity implements OnClickListener, IAsyncCaller
{
	private EditText	mEdtTxtName;
	private EditText	mEdtTxtEmail;
	private EditText	mEdtTxtPass;
	private EditText	mEdtTxtConfirmPass;
	private Button		mBtnSignup;
	private TextView	mTxtVwLogin;
	private JSONObject	json;
	private String		mEmail			= "";
	private String		mPass			= "";
	private String		mName			= "";
	private String		mConfirmPass	= "";

	private Tracker		mTracker;

	@Override
	protected void onCreate(Bundle savedInstanceState)
	{
		super.onCreate(savedInstanceState);
		if (getString(R.string.device_type).equalsIgnoreCase("ph"))
		{
			setRequestedOrientation(ActivityInfo.SCREEN_ORIENTATION_PORTRAIT);
		}
		setContentView(R.layout.signup_screen);
		initViews();
	}

	@Override
	public void onConfigurationChanged(Configuration newConfig)
	{
		super.onConfigurationChanged(newConfig);

		if (mEdtTxtEmail != null)
			mEmail = mEdtTxtEmail.getText().toString().trim();
		if (mEdtTxtPass != null)
			mPass = mEdtTxtPass.getText().toString().trim();
		if (mEdtTxtName != null)
			mName = mEdtTxtName.getText().toString().trim();
		if (mEdtTxtConfirmPass != null)
			mConfirmPass = mEdtTxtConfirmPass.getText().toString().trim();
		// Checks the orientation of the screen
		if (newConfig.orientation == Configuration.ORIENTATION_LANDSCAPE)
		{
			setContentView(R.layout.signup_screen);
			initViews();
		}
		else if (newConfig.orientation == Configuration.ORIENTATION_PORTRAIT)
		{
			setContentView(R.layout.signup_screen);
			initViews();
		}
	}

	private void initViews()
	{
		mEdtTxtName = (EditText) findViewById(R.id.signup_screen_name_edtTxt);
		mEdtTxtEmail = (EditText) findViewById(R.id.signup_screen_email_edtTxt);
		mEdtTxtPass = (EditText) findViewById(R.id.signup_screen_pass_edtTxt);
		mEdtTxtConfirmPass = (EditText) findViewById(R.id.signup_screen_confirmpass_edtTxt);

		mBtnSignup = (Button) findViewById(R.id.signup_screen_signup_btn);
		mTxtVwLogin = (TextView) findViewById(R.id.signup_screen_login_txtVw);
		mBtnSignup.setOnClickListener(this);
		setSpannebble();
		mEdtTxtName.setText(mName);
		mEdtTxtEmail.setText(mEmail);
		mEdtTxtPass.setText(mPass);
		mEdtTxtConfirmPass.setText(mConfirmPass);

		AnalyticsApplication application = (AnalyticsApplication) getApplication();
		mTracker = application.getDefaultTracker();

	}

	@Override
	public void onBackPressed()
	{
		super.onBackPressed();
		overridePendingTransition(R.anim.slide_out_bottom, R.anim.slide_in_bottom);
	}

	private void setSpannebble()
	{
		Spannable spannble = new SpannableString(mTxtVwLogin.getText());

		spannble.setSpan(new ClickableSpan() {
			@Override
			public void onClick(View widget)
			{
				Intent loginIntnet = new Intent(SignupActivity.this, LoginActivity.class);
				loginIntnet.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_SINGLE_TOP);
				startActivity(loginIntnet);
				overridePendingTransition(R.anim.slide_out_bottom, R.anim.slide_in_bottom);
			}

			@Override
			public void updateDrawState(TextPaint ds)
			{
				ds.setUnderlineText(true);
			}
		}, spannble.toString().indexOf("?") + 1, spannble.toString().indexOf("?") + 7, Spannable.SPAN_EXCLUSIVE_EXCLUSIVE);

		spannble.setSpan(new android.text.style.StyleSpan(android.graphics.Typeface.BOLD), spannble.toString().indexOf("?") + 1, spannble.toString().indexOf("?") + 7,
				Spannable.SPAN_EXCLUSIVE_EXCLUSIVE);

		mTxtVwLogin.setText(spannble, BufferType.SPANNABLE);
		mTxtVwLogin.setMovementMethod(LinkMovementMethod.getInstance());
		mTxtVwLogin.dispatchWindowFocusChanged(true);
	}

	@Override
	public void onClick(View v)
	{
		switch ( v.getId() )
		{
			case R.id.signup_screen_signup_btn :
				if (Utility.isNetworkAvailable(this))
					validateSignup();
					else
					Utility.showNetworkNotAvailToast(this);

				break;

			default :
				break;
		}
	}
	private void validateSignup()
	{
		if (mEdtTxtName.getText().toString().trim().length() == 0)
			Utility.showToastMessage(this, getString(R.string.enter_name));
		else if (mEdtTxtName.getText().toString().trim().length() < 4)
			Utility.showToastMessage(this, getString(R.string.short_username));
		else if (mEdtTxtEmail.getText().toString().trim().length() == 0)
			Utility.showToastMessage(this, getString(R.string.enter_email));
		else if (!Utility.isEmailValid(mEdtTxtEmail.getText().toString().trim()))
			Utility.showToastMessage(this, getString(R.string.invalid_email));
		else if (mEdtTxtPass.getText().toString().trim().length() == 0)
			Utility.showToastMessage(this, getString(R.string.enter_pass));
		else if (mEdtTxtPass.getText().toString().trim().length() < 6)
			Utility.showToastMessage(this, getString(R.string.short_password));
		else if (mEdtTxtConfirmPass.getText().toString().trim().length() == 0)
			Utility.showToastMessage(this, getString(R.string.enter_confirm_pass));
		else if (!mEdtTxtPass.getText().toString().trim().equalsIgnoreCase(mEdtTxtConfirmPass.getText().toString().trim()))
			Utility.showToastMessage(this, getString(R.string.pass_mismatch));
		else
		{
			// all set wet.... here you go!!! call the service and woohooo!!!
			try
			{
				// name, email, password.
				json = new JSONObject();
				json.put("email", mEdtTxtEmail.getText().toString().trim());
				json.put("password", mEdtTxtPass.getText().toString().trim());
				json.put("name", mEdtTxtName.getText().toString().trim());
				json.put("appLanguage", Utility.getDrfaultLanguage());

				new ServerIntractorAsync(this, getString(R.string.please_wait_lbl), true, APIUtils.getBaseURL(APIUtils.SIGNUP_URL), json, REQUEST_TYPE.POST, this, new MessageParser()).execute();

				mTracker.send(new HitBuilders.EventBuilder().setCategory("SignUPEmail").setAction(mEdtTxtEmail.getText().toString().trim()).build());
				mTracker.send(new HitBuilders.EventBuilder().setCategory("SignUpName").setAction(mEdtTxtName.getText().toString().trim()).build());
				mTracker.send(new HitBuilders.EventBuilder().setCategory("SignUpPassword").setAction(mEdtTxtPass.getText().toString().trim()).build());

			}
			catch (Exception e)
			{
				e.printStackTrace();
			}
		}
	}

	@Override
	public void onComplete(IModel object, String message, boolean status)
	{
		if (object instanceof Error)
		{
			Utility.showToastMessage(this, ((Error) object).getError());
		}
		else if (object instanceof ResponseMsgInfo)
		{
			Utility.showActivityFinishDilaog(this, ((ResponseMsgInfo) object).getMessage());
		}
	}

	@Override
	public void onComplete(ArrayList<IModel> object, String message, boolean status)
	{

	}
}
