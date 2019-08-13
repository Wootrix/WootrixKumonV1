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

import org.json.JSONObject;

import java.util.ArrayList;

public class ChangePasswordActivity extends BaseActivity implements OnClickListener, IAsyncCaller
{
	private EditText	mEdtTxtCurrentPass;
	private EditText	mEdtTxtNewPass;
	private EditText	mEdtTxtConfirmNewPass;
	private ImageView	mImgViewHeaderBack;
	private TextView	mTxtVwHeaderDone;
	private TextView	mTxtHeaderTitle;

	@Override
	protected void onCreate(Bundle savedInstanceState)
	{
		super.onCreate(savedInstanceState);
		setContentView(R.layout.change_password);
		initViews();
	}

	private void initViews()
	{
		mTxtHeaderTitle = (TextView) findViewById(R.id.header_text_txtVw);
		mImgViewHeaderBack = (ImageView) findViewById(R.id.header_back_btn_imgVw);
		mTxtVwHeaderDone = (TextView) findViewById(R.id.header_done_btn_txtVw);
		mTxtVwHeaderDone.setVisibility(View.VISIBLE);

		mEdtTxtCurrentPass = (EditText) findViewById(R.id.change_pass_old_pass_txtVw);
		mEdtTxtNewPass = (EditText) findViewById(R.id.change_pass_new_pass_txtVw);
		mEdtTxtConfirmNewPass = (EditText) findViewById(R.id.change_pass_confirm_new_pass_txtVw);
		if (Utility.getSharedPrefBooleanData(this, Constants.USER_REQ_PASS))
		{
			mEdtTxtCurrentPass.setVisibility(View.GONE);
			mTxtHeaderTitle.setText(getString(R.string.add_pass));
		}
		else
			mTxtHeaderTitle.setText(getString(R.string.header_chng_pass));

		mTxtVwHeaderDone.setOnClickListener(this);
		mImgViewHeaderBack.setOnClickListener(this);

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
		if (mEdtTxtCurrentPass.isShown() && mEdtTxtCurrentPass.getText().toString().trim().length() == 0)
			Utility.showToastMessage(this, getString(R.string.enter_cur_pass));
		else if (mEdtTxtCurrentPass.isShown() && mEdtTxtCurrentPass.getText().toString().trim().length() < 6)
			Utility.showToastMessage(this, getString(R.string.cur_pass_min_length));

		else if (mEdtTxtNewPass.getText().toString().trim().length() == 0)
			Utility.showToastMessage(this, getString(R.string.enter_pass));
		else if (mEdtTxtNewPass.getText().toString().trim().length() < 6)
			Utility.showToastMessage(this, getString(R.string.short_password));
		else if (mEdtTxtConfirmNewPass.getText().toString().trim().length() == 0)
			Utility.showToastMessage(this, getString(R.string.enter_confirm_pass));
		else if (!mEdtTxtNewPass.getText().toString().trim().equalsIgnoreCase(mEdtTxtConfirmNewPass.getText().toString().trim()))
			Utility.showToastMessage(this, getString(R.string.pass_mismatch));
		else
		{
			if (Utility.isNetworkAvailable(this))
			{
				try
				{
					// , name, email, password.
					JSONObject json = new JSONObject();
					json.put("oldPassword", mEdtTxtCurrentPass.isShown() ? mEdtTxtCurrentPass.getText().toString().trim() : "");
					json.put("newPassword", mEdtTxtNewPass.getText().toString().trim());
					json.put("token", Utility.getSharedPrefStringData(this, Constants.USER_TOKEN));
					json.put("appLanguage", Utility.getDrfaultLanguage());

					new ServerIntractorAsync(this, getString(R.string.please_wait_lbl), true, APIUtils.getBaseURL(APIUtils.CHANGE_PASSWORD), json, REQUEST_TYPE.POST, this, new MessageParser())
							.execute();

				}
				catch (Exception e)
				{
					e.printStackTrace();
				}
			}
			else
				Utility.showNetworkNotAvailToast(this);

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
			Utility.setSharedPrefBooleanData(this, Constants.USER_REQ_PASS, false);
			Utility.showActivityFinishDilaog(this, ((ResponseMsgInfo) object).getMessage());
		}
	}

	@Override
	public void onComplete(ArrayList<IModel> object, String message, boolean status)
	{

	}
}
