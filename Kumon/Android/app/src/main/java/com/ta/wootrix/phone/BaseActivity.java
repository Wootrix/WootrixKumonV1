package com.ta.wootrix.phone;

import android.app.Activity;
import android.content.res.Configuration;
import android.os.Bundle;

import com.ta.wootrix.utils.Constants;
import com.ta.wootrix.utils.Utility;

public class BaseActivity extends Activity
{
	@Override
	protected void onCreate(Bundle savedInstanceState)
	{
		super.onCreate(savedInstanceState);
		String appLanguage = Utility.getSharedPrefStringData(this, Constants.APP_LANGUAGE);
		if (appLanguage.length() > 0)
			Utility.updateAppDefaultLanguage(this, appLanguage);
	}

	@Override
	public void onConfigurationChanged(Configuration newConfig)
	{
		super.onConfigurationChanged(newConfig);
		String appLanguage = Utility.getSharedPrefStringData(this, Constants.APP_LANGUAGE);
		if (appLanguage.length() > 0)
			Utility.updateAppDefaultLanguage(this, appLanguage);
	}
}
