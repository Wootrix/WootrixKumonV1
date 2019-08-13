package com.ta.wootrix.firebase;

import android.app.Application;

import com.google.android.gms.analytics.GoogleAnalytics;
import com.google.android.gms.analytics.Tracker;
import com.ta.wootrix.R;

import io.branch.referral.Branch;

/**
 * Created by CIPL0424 on 8/8/2016.
 */
public class AnalyticsApplication extends Application
{
	private Tracker mTracker;

	@Override
	public void onCreate() {
		super.onCreate();
		Branch.getAutoInstance(this);
	}

	synchronized public Tracker getDefaultTracker()
	{
		if (mTracker == null)
		{
			GoogleAnalytics analytics = GoogleAnalytics.getInstance(this);
			mTracker = analytics.newTracker(R.xml.app_tracker);
		}
		return mTracker;
	}
}