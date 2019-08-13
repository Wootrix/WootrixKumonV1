package com.ta.wootrix.tablet;

import android.app.Fragment;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ProgressBar;

import com.ta.wootrix.R;
import com.ta.wootrix.utils.Utility;

public class AboutUsFragment extends Fragment implements OnClickListener
{
	protected static final String	TAG	= "TAG";
	private ImageView				mImgVwBackBtn;
	private WebView					mWebView;
	private ProgressBar				progressBar;

	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState)
	{
		setRetainInstance(true);
		View v = inflater.inflate(R.layout.about_us_popup, container, false);
		initViews(v);
		return v;
	}

	private void initViews(View v)
	{

		((LinearLayout) v.findViewById(R.id.aboutus_popup_main_lnrlyt)).setOnClickListener(this);

		mImgVwBackBtn = (ImageView) v.findViewById(R.id.aboutus_popup_back_imgVw);
		mWebView = (WebView) v.findViewById(R.id.aboutus_popup_webview);
		progressBar = (ProgressBar) v.findViewById(R.id.aboutus_popup_progressBar);
		progressBar.setVisibility(View.VISIBLE);
		mImgVwBackBtn.setOnClickListener(this);

		WebSettings settings = mWebView.getSettings();
		settings.setBuiltInZoomControls(true);
		settings.setSupportZoom(true);
		settings.setJavaScriptEnabled(true);
		mWebView.setScrollBarStyle(WebView.SCROLLBARS_OUTSIDE_OVERLAY);

		mWebView.setWebViewClient(new WebViewClient() {
			public boolean shouldOverrideUrlLoading(WebView view, String url)
			{
				Log.i(TAG, "Processing webview url click...");
				view.loadUrl(url);
				return true;
			}

			public void onPageFinished(WebView view, String url)
			{
				Log.i(TAG, "Finished loading URL: " + url);
				progressBar.setVisibility(View.GONE);
			}

			@SuppressWarnings("deprecation")
			public void onReceivedError(WebView view, int errorCode, String description, String failingUrl)
			{
				Log.e(TAG, "Error: " + description);
				Utility.showErrorDialog(getActivity(), getString(R.string.app_name), description, null);
			}
		});
		// mWebView.loadUrl(APIUtils.ABOUT_US_URL);
		mWebView.loadUrl(getResources().getString(R.string.url_about_us));

	}

	@Override
	public void onClick(View v)
	{
		if (v == mImgVwBackBtn)
			getFragmentManager().popBackStack();
	}
}
