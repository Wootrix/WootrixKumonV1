package com.ta.wootrix.phone;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.ImageView;
import android.widget.RadioButton;
import android.widget.TextView;

import com.ta.wootrix.R;
import com.ta.wootrix.customDialog.CustomDialog;
import com.ta.wootrix.customDialog.CustomDialog.DIALOG_TYPE;
import com.ta.wootrix.customDialog.IActionOKCancel;
import com.ta.wootrix.utils.Constants;
import com.ta.wootrix.utils.Utility;

public class ChangeLanguageActivity extends BaseActivity implements OnClickListener, IActionOKCancel
{
	private static final int	REQ_CHANGE_LANG	= 0;
	private RadioButton			mRadioEnglishLang;
	private RadioButton			mRadioSpanishLang;
	private RadioButton			mRadioPortuLang;
	private ImageView			mImgViewHeaderBack;
	private TextView			mTxtVwHeaderDone;
	private TextView			mTxtHeaderTitle;
	private String				currentLanguage;

	@Override
	protected void onCreate(Bundle savedInstanceState)
	{
		super.onCreate(savedInstanceState);
		setContentView(R.layout.change_language);
		initViews();
	}

	private void initViews()
	{
		mTxtHeaderTitle = (TextView) findViewById(R.id.header_text_txtVw);
		mTxtHeaderTitle.setText(getString(R.string.header_chng_lng));

		mImgViewHeaderBack = (ImageView) findViewById(R.id.header_back_btn_imgVw);
		mTxtVwHeaderDone = (TextView) findViewById(R.id.header_done_btn_txtVw);
		mTxtVwHeaderDone.setVisibility(View.VISIBLE);

		mRadioEnglishLang = (RadioButton) findViewById(R.id.change_lang_english_radio);
		mRadioSpanishLang = (RadioButton) findViewById(R.id.change_lang_spanish_radio);
		mRadioPortuLang = (RadioButton) findViewById(R.id.change_lang_portuguese_radio);

		mTxtVwHeaderDone.setOnClickListener(this);
		mImgViewHeaderBack.setOnClickListener(this);

		currentLanguage = Utility.getSharedPrefStringData(this, Constants.APP_LANGUAGE);
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
			case R.id.header_back_btn_imgVw :
				finish();
				break;
			case R.id.header_done_btn_txtVw :
				if (mRadioEnglishLang.isChecked() || mRadioSpanishLang.isChecked() || mRadioPortuLang.isChecked())
				{
					// Ask user for confirming that he is disiring to change the app lanugage and
					// hasn't pressed the done button by mistake
					CustomDialog dialog = CustomDialog.getInstance(this, this, getString(R.string.change_language_warning_message), getString(R.string.app_name), DIALOG_TYPE.OK_CANCEL, -1,
							getString(R.string.lbl_yes), getString(R.string.lbl_no), REQ_CHANGE_LANG);
					dialog.show();

				}
				else
					Utility.showToastMessage(this, getString(R.string.select_language));

				break;

			default :
				break;
		}
	}

	@Override
	public void onActionOk(int requestCode)
	{
		if (mRadioEnglishLang.isChecked())
		{
			Utility.updateAppDefaultLanguage(this, "en");
			Utility.setSharedPrefStringData(this, Constants.APP_LANGUAGE, "en");
		}
		else if (mRadioSpanishLang.isChecked())
		{
			Utility.updateAppDefaultLanguage(this, "es");
			Utility.setSharedPrefStringData(this, Constants.APP_LANGUAGE, "es");
		}
		else if (mRadioPortuLang.isChecked())
		{
			Utility.updateAppDefaultLanguage(this, "pt");
			Utility.setSharedPrefStringData(this, Constants.APP_LANGUAGE, "pt");
		}
		// Utility.setSharedPrefStringData(this, Constants.USER_TOPICS, "");
		if (HomeActivity.getInstance() != null)
			HomeActivity.getInstance().finish();
		if (SettingActivity.getInstance() != null)
			SettingActivity.getInstance().finish();
		if (MyAccountActivity.getInstance() != null)
			MyAccountActivity.getInstance().finish();
		Intent restartAppIntent = new Intent(ChangeLanguageActivity.this, SplashActivity.class);
		restartAppIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
		startActivity(restartAppIntent);
		finish();
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
