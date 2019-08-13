package com.ta.wootrix.tablet;

import android.app.Activity;
import android.app.Fragment;
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

import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

public class ChangeEmailFragment extends Fragment implements OnClickListener, IAsyncCaller
{

	private EditText	mEdtTxtCurrentEmail;
	private EditText	mEdtTxtNewEmail;
	private EditText	mEdtTxtConfirmNewEmail;
	private ImageView	mImgViewHeaderBack;
	private TextView	mTxtVwHeaderDone;
	private TextView	mTxtHeaderTitle;
	private Activity	mContext;

	@Override
	public void onCreate(Bundle savedInstanceState)
	{
		super.onCreate(savedInstanceState);
		mContext = getActivity();
	}

	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState)
	{
		setRetainInstance(true);
		View v = inflater.inflate(R.layout.chng_email_popup, container, false);
		initViews(v);
		return v;
	}

	private void initViews(View v)
	{
		mTxtHeaderTitle = (TextView) v.findViewById(R.id.change_email_popup_header_txt);

		mImgViewHeaderBack = (ImageView) v.findViewById(R.id.chng_email_popup_back_imgVw);
		mTxtVwHeaderDone = (TextView) v.findViewById(R.id.change_email_popup_done_txt);
		mTxtVwHeaderDone.setVisibility(View.VISIBLE);

		mEdtTxtCurrentEmail = (EditText) v.findViewById(R.id.change_email_cureent_email_txtVw);
		mEdtTxtNewEmail = (EditText) v.findViewById(R.id.change_email_new_email_txtVw);
		mEdtTxtConfirmNewEmail = (EditText) v.findViewById(R.id.change_email_confirming_email_txtVw);

		mTxtVwHeaderDone.setOnClickListener(this);
		mImgViewHeaderBack.setOnClickListener(this);
		((LinearLayout) v.findViewById(R.id.chng_email_popup_main_lnrlyt)).setOnClickListener(this);

		if (Utility.getSharedPrefBooleanData(getActivity(), Constants.USER_REQ_EMAIL))
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
			case R.id.chng_email_popup_back_imgVw :
				getFragmentManager().popBackStack();
				break;
			case R.id.change_email_popup_done_txt :
				validateFields();
				break;

			default :
				break;
		}
	}

	private void validateFields()
	{
		if (mEdtTxtNewEmail.getText().toString().trim().length() == 0)
			Utility.showToastMessage(mContext, getString(R.string.enter_new_email));
		else if (!Utility.isEmailValid(mEdtTxtNewEmail.getText().toString().trim()))
			Utility.showToastMessage(mContext, getString(R.string.invalid_email));
		else if (mEdtTxtConfirmNewEmail.getText().toString().trim().length() == 0)
			Utility.showToastMessage(mContext, getString(R.string.enter_new_confrm_email));
		else if (!mEdtTxtNewEmail.getText().toString().trim().equalsIgnoreCase(mEdtTxtConfirmNewEmail.getText().toString().trim()))
			Utility.showToastMessage(mContext, getString(R.string.new_and_confrm_email_mismatch));
		else
		{
			if (Utility.isNetworkAvailable(mContext))
			{
				try
				{
					JSONObject json = new JSONObject();
					json.put("oldEmail", mEdtTxtCurrentEmail.isShown() ? mEdtTxtCurrentEmail.getText() : "");
					json.put("newEmail", mEdtTxtConfirmNewEmail.getText().toString().trim());
					json.put("token", Utility.getSharedPrefStringData(mContext, Constants.USER_TOKEN));
					json.put("appLanguage", Utility.getDrfaultLanguage());
					new ServerIntractorAsync(mContext, getString(R.string.please_wait_lbl), true, APIUtils.getBaseURL(APIUtils.CHANGE_EMAIL), json, REQUEST_TYPE.POST, this, new MessageParser())
							.execute();
				}
				catch (JSONException e)
				{
					e.printStackTrace();
				}
			}
			else
			{
				Utility.showNetworkNotAvailToast(mContext);
			}
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
			Utility.setSharedPrefBooleanData(mContext, Constants.USER_REQ_EMAIL, false);
			Utility.setSharedPrefStringData(mContext, Constants.USER_EMAIL, mEdtTxtNewEmail.getText().toString().trim());
			Utility.showToastMessage(mContext, ((ResponseMsgInfo) object).getMessage());
			getFragmentManager().popBackStack();
		}
	}

	@Override
	public void onComplete(ArrayList<IModel> object, String message, boolean status)
	{

	}
}