package com.ta.wootrix.phone;

import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;

import com.ta.wootrix.R;
import com.ta.wootrix.asynctask.IAsyncCaller;
import com.ta.wootrix.asynctask.ServerIntractorAsync;
import com.ta.wootrix.modle.Error;
import com.ta.wootrix.modle.IModel;
import com.ta.wootrix.modle.ResponseMsgInfo;
import com.ta.wootrix.parser.MessageParser;
import com.ta.wootrix.restapi.APIUtils;
import com.ta.wootrix.restapi.APIUtils.REQUEST_TYPE;
import com.ta.wootrix.utils.Constants;
import com.ta.wootrix.utils.Utility;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

public class ChangeEmailActivity extends BaseActivity implements OnClickListener, IAsyncCaller
{
	private EditText	mEdtTxtCurrentEmail;
	private EditText	mEdtTxtNewEmail;
	private EditText	mEdtTxtConfirmNewEmail;
	private ImageView	mImgViewHeaderBack;
	private TextView	mTxtVwHeaderDone;
	private TextView	mTxtHeaderTitle;

	@Override
	protected void onCreate(Bundle savedInstanceState)
	{
		super.onCreate(savedInstanceState);
		setContentView(R.layout.change_email);
		initViews();
	}

	private void initViews()
	{

		mTxtHeaderTitle = (TextView) findViewById(R.id.header_text_txtVw);

		mImgViewHeaderBack = (ImageView) findViewById(R.id.header_back_btn_imgVw);
		mTxtVwHeaderDone = (TextView) findViewById(R.id.header_done_btn_txtVw);
		mTxtVwHeaderDone.setVisibility(View.VISIBLE);

		mEdtTxtCurrentEmail = (EditText) findViewById(R.id.change_email_cureent_email_txtVw);
		mEdtTxtNewEmail = (EditText) findViewById(R.id.change_email_new_email_txtVw);
		mEdtTxtConfirmNewEmail = (EditText) findViewById(R.id.change_email_confirming_email_txtVw);

		mTxtVwHeaderDone.setOnClickListener(this);
		mImgViewHeaderBack.setOnClickListener(this);
		if (Utility.getSharedPrefBooleanData(this, Constants.USER_REQ_EMAIL))
		{
			mTxtHeaderTitle.setText(getString(R.string.add_email));
			mEdtTxtCurrentEmail.setVisibility(View.GONE);
		}
		else
			mTxtHeaderTitle.setText(getString(R.string.header_chng_email));

	}

	@Override
	public void onClick(View v)
	{
		switch ( v.getId() )
		{
			case R.id.header_back_btn_imgVw :
				finish();
				break;
			case R.id.header_done_btn_txtVw :
				validateFields();
				break;

			default :
				break;
		}
	}

	private void validateFields()
	{
		if (mEdtTxtNewEmail.getText().toString().trim().length() == 0)
			Utility.showToastMessage(this, getString(R.string.enter_new_email));
		else if (!Utility.isEmailValid(mEdtTxtNewEmail.getText().toString().trim()))
			Utility.showToastMessage(this, getString(R.string.invalid_email));
		else if (mEdtTxtConfirmNewEmail.getText().toString().trim().length() == 0)
			Utility.showToastMessage(this, getString(R.string.enter_new_confrm_email));
		else if (!mEdtTxtNewEmail.getText().toString().trim().equalsIgnoreCase(mEdtTxtConfirmNewEmail.getText().toString().trim()))
			Utility.showToastMessage(this, getString(R.string.new_and_confrm_email_mismatch));
		else
		{
			if (Utility.isNetworkAvailable(this))
			{
				try
				{
					JSONObject json = new JSONObject();
					json.put("oldEmail", mEdtTxtCurrentEmail.isShown() ? mEdtTxtCurrentEmail.getText() : "");
					json.put("newEmail", mEdtTxtConfirmNewEmail.getText().toString().trim());
					json.put("token", Utility.getSharedPrefStringData(this, Constants.USER_TOKEN));
					json.put("appLanguage", Utility.getDrfaultLanguage());
					new ServerIntractorAsync(this, getString(R.string.please_wait_lbl), true, APIUtils.getBaseURL(APIUtils.CHANGE_EMAIL), json, REQUEST_TYPE.POST, this, new MessageParser()).execute();
				}
				catch (JSONException e)
				{
					e.printStackTrace();
				}
			}
			else
			{
				Utility.showNetworkNotAvailToast(this);
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
			Utility.setSharedPrefBooleanData(this, Constants.USER_REQ_EMAIL, false);
			Utility.setSharedPrefStringData(this, Constants.USER_EMAIL, mEdtTxtNewEmail.getText().toString().trim());
			Utility.showActivityFinishDilaog(this, ((ResponseMsgInfo) object).getMessage());
		}
	}

	@Override
	public void onComplete(ArrayList<IModel> object, String message, boolean status)
	{

	}
}