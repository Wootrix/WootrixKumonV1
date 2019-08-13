package com.ta.wootrix.tablet;

import android.app.Fragment;
import android.content.Context;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
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

public class ChangePassFragment extends Fragment implements OnClickListener, IAsyncCaller
{

	private EditText	mEdtTxtCurrentPass;
	private EditText	mEdtTxtNewPass;
	private EditText	mEdtTxtConfirmNewPass;
	private ImageView	mImgViewHeaderBack;
	private TextView	mTxtVwHeaderDone;
	private TextView	mTxtHeaderTitle;
	private Context		mContext;

	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState)
	{
		setRetainInstance(true);
		View v = inflater.inflate(R.layout.chng_pass_popup, container, false);
		initViews(v);
		return v;
	}

	@Override
	public void onCreate(Bundle savedInstanceState)
	{
		super.onCreate(savedInstanceState);
		mContext = getActivity();
	}

	private void initViews(View v)
	{
		mTxtHeaderTitle = (TextView) v.findViewById(R.id.chng_pass_popup_header_title_imgVw);
		mImgViewHeaderBack = (ImageView) v.findViewById(R.id.chng_pass_popup_back_imgVw);
		mTxtVwHeaderDone = (TextView) v.findViewById(R.id.change_pass_popup_done_txt);
		mTxtVwHeaderDone.setVisibility(View.VISIBLE);

		mEdtTxtCurrentPass = (EditText) v.findViewById(R.id.change_pass_old_pass_txtVw);
		mEdtTxtNewPass = (EditText) v.findViewById(R.id.change_pass_new_pass_txtVw);
		mEdtTxtConfirmNewPass = (EditText) v.findViewById(R.id.change_pass_confirm_new_pass_txtVw);
		/*
		 * if (Utility.getSharedPrefBooleanData(mContext, Constants.USER_REQ_PASS)) {
		 * mEdtTxtCurrentPass.setVisibility(View.GONE);
		 * mTxtHeaderTitle.setText(getString(R.string.add_pass)); } else
		 * mTxtHeaderTitle.setText(getString(R.string.header_chng_pass));
		 */
		mTxtHeaderTitle.setText(getString(R.string.header_chng_pass));

		mTxtVwHeaderDone.setOnClickListener(this);
		mImgViewHeaderBack.setOnClickListener(this);
		((LinearLayout) v.findViewById(R.id.chng_email_popup_main_lnrlyt)).setOnClickListener(this);

	}

	@Override
	public void onClick(View v)
	{
		switch ( v.getId() )
		{
			case R.id.chng_pass_popup_back_imgVw :
				getFragmentManager().popBackStack();
				break;
			case R.id.change_pass_popup_done_txt :
				validateFields();
				break;

			default :
				break;
		}
	}

	private void validateFields()
	{
		if (mEdtTxtCurrentPass.isShown() && mEdtTxtCurrentPass.getText().toString().trim().length() == 0)
			Utility.showToastMessage(mContext, getString(R.string.enter_cur_pass));
		else if (mEdtTxtCurrentPass.isShown() && mEdtTxtCurrentPass.getText().toString().trim().length() < 6)
			Utility.showToastMessage(mContext, getString(R.string.cur_pass_min_length));

		else if (mEdtTxtNewPass.getText().toString().trim().length() == 0)
			Utility.showToastMessage(mContext, getString(R.string.enter_pass));
		else if (mEdtTxtNewPass.getText().toString().trim().length() < 6)
			Utility.showToastMessage(mContext, getString(R.string.short_password));
		else if (mEdtTxtConfirmNewPass.getText().toString().trim().length() == 0)
			Utility.showToastMessage(mContext, getString(R.string.enter_confirm_pass));
		else if (!mEdtTxtNewPass.getText().toString().trim().equalsIgnoreCase(mEdtTxtConfirmNewPass.getText().toString().trim()))
			Utility.showToastMessage(mContext, getString(R.string.pass_mismatch));
		else
		{
			if (Utility.isNetworkAvailable(mContext))
			{
				try
				{
					// , name, email, password.
					JSONObject json = new JSONObject();
					json.put("oldPassword", mEdtTxtCurrentPass.isShown() ? mEdtTxtCurrentPass.getText().toString().trim() : "");
					json.put("newPassword", mEdtTxtNewPass.getText().toString().trim());
					json.put("token", Utility.getSharedPrefStringData(mContext, Constants.USER_TOKEN));
					json.put("appLanguage", Utility.getDrfaultLanguage());

					new ServerIntractorAsync(mContext, getString(R.string.please_wait_lbl), true, APIUtils.getBaseURL(APIUtils.CHANGE_PASSWORD), json, REQUEST_TYPE.POST, this, new MessageParser())
							.execute();

				}
				catch (Exception e)
				{
					e.printStackTrace();
				}
			}
			else
				Utility.showNetworkNotAvailToast(mContext);

		}
	}

	@Override
	public void onComplete(IModel object, String message, boolean status)
	{
		if (object instanceof Error)
		{
			Utility.showToastMessage(mContext, ((Error) object).getError());
		}
		else if (object instanceof ResponseMsgInfo)
		{
			Utility.setSharedPrefBooleanData(mContext, Constants.USER_REQ_PASS, false);
			Utility.showToastMessage(mContext, ((ResponseMsgInfo) object).getMessage());
			getFragmentManager().popBackStack();
		}
	}

	@Override
	public void onComplete(ArrayList<IModel> object, String message, boolean status)
	{

	}
}