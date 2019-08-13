package com.ta.wootrix.tablet;

import android.app.Fragment;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RadioButton;
import android.widget.TextView;

import com.ta.wootrix.R;
import com.ta.wootrix.customDialog.CustomDialog;
import com.ta.wootrix.customDialog.CustomDialog.DIALOG_TYPE;
import com.ta.wootrix.customDialog.IActionOKCancel;
import com.ta.wootrix.phone.SplashActivity;
import com.ta.wootrix.utils.Constants;
import com.ta.wootrix.utils.Utility;

/**
 * Chane language fragment: This class is used for chagning app language
 * 
 * @author ashok
 */
public class ChangeLangFragment extends Fragment implements OnClickListener, IActionOKCancel
{

	private static final int	REQ_CHANGE_LANG	= 212;
	private RadioButton			mRadioEnglishLang;
	private RadioButton			mRadioSpanishLang;
	private RadioButton			mRadioPortuLang;
	private ImageView			mImgViewHeaderBack;
	private TextView			mTxtVwHeaderDone;
	private String				currentLanguage;
	private Context				mContext;

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
		View v = inflater.inflate(R.layout.change_lang_popup, container, false);
		initViews(v);
		return v;
	}

	private void initViews(View v)
	{

		mImgViewHeaderBack = (ImageView) v.findViewById(R.id.chng_language_popup_back_imgVw);
		mTxtVwHeaderDone = (TextView) v.findViewById(R.id.chng_language_popup_done_txt);
		mTxtVwHeaderDone.setVisibility(View.VISIBLE);

		mRadioEnglishLang = (RadioButton) v.findViewById(R.id.change_lang_english_radio);
		mRadioSpanishLang = (RadioButton) v.findViewById(R.id.change_lang_spanish_radio);
		mRadioPortuLang = (RadioButton) v.findViewById(R.id.change_lang_portuguese_radio);

		mTxtVwHeaderDone.setOnClickListener(this);
		mImgViewHeaderBack.setOnClickListener(this);
		((LinearLayout) v.findViewById(R.id.chng_language_popup_main_lnrlyt)).setOnClickListener(this);
		currentLanguage = Utility.getSharedPrefStringData(mContext, Constants.APP_LANGUAGE);
		if (currentLanguage.equalsIgnoreCase("en"))
			mRadioEnglishLang.setChecked(true);
		else if (currentLanguage.equalsIgnoreCase("es"))
			mRadioSpanishLang.setChecked(true);
		else if (currentLanguage.equalsIgnoreCase("pt"))
			mRadioPortuLang.setChecked(true);

	}

	@Override
	public void onClick(View v)
	{
		switch ( v.getId() )
		{
			case R.id.chng_language_popup_back_imgVw :
				getFragmentManager().popBackStack();
				break;
			case R.id.chng_language_popup_done_txt :
				if (mRadioEnglishLang.isChecked() || mRadioSpanishLang.isChecked() || mRadioPortuLang.isChecked())
				{
					// Ask user for confirming that he is disiring to change the app lanugage and
					// hasn't pressed the done button by mistake
					CustomDialog dialog = CustomDialog.getInstance(mContext, this, getString(R.string.change_language_warning_message), getString(R.string.app_name), DIALOG_TYPE.OK_CANCEL, -1,
							getString(R.string.lbl_yes), getString(R.string.lbl_no), REQ_CHANGE_LANG);
					dialog.show();

				}
				else
					Utility.showToastMessage(mContext, getString(R.string.select_language));

				break;

			default :
				break;
		}
	}

	@Override
	public void onActionOk(int requestCode)
	{

		// update the language in shared preferences and restart the app from starting
		if (mRadioEnglishLang.isChecked())
		{
			Utility.updateAppDefaultLanguage(mContext, "en");
			Utility.setSharedPrefStringData(mContext, Constants.APP_LANGUAGE, "en");
		}
		else if (mRadioSpanishLang.isChecked())
		{
			Utility.updateAppDefaultLanguage(mContext, "es");
			Utility.setSharedPrefStringData(mContext, Constants.APP_LANGUAGE, "es");
		}
		else if (mRadioPortuLang.isChecked())
		{
			Utility.updateAppDefaultLanguage(mContext, "pt");
			Utility.setSharedPrefStringData(mContext, Constants.APP_LANGUAGE, "pt");
		}
		// Utility.setSharedPrefStringData(getActivity(), Constants.USER_TOPICS, "");
		Intent restartAppIntent = new Intent(mContext, SplashActivity.class);
		restartAppIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
		startActivity(restartAppIntent);
		getActivity().finish();
	}

	@Override
	public void onActionCancel(int requestCode)
	{

	}

	@Override
	public void onActionNeutral(int requestCode)
	{

	}
}