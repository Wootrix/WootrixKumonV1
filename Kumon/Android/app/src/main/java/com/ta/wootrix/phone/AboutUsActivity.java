package com.ta.wootrix.phone;

import android.annotation.SuppressLint;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.TextView;

import com.ta.wootrix.R;
import com.ta.wootrix.utils.Utility;

@SuppressLint("SetJavaScriptEnabled")
public class AboutUsActivity extends BaseActivity implements OnClickListener
{
	protected static final String	TAG	= "tag";
	private WebView					mWebView;
	private TextView				mTxtHeaderTitle;
	private ImageView				mImgVwBackBtn;
	private ProgressBar				progressBar;

	@Override
	protected void onCreate(Bundle savedInstanceState)
	{
		super.onCreate(savedInstanceState);
		setContentView(R.layout.aboutus_screen);
		initViews();
	}

	private void initViews()
	{
		mTxtHeaderTitle = (TextView) findViewById(R.id.header_text_txtVw);
		mTxtHeaderTitle.setText(getString(R.string.header_aboutus));
		mImgVwBackBtn = (ImageView) findViewById(R.id.header_back_btn_imgVw);
		mWebView = (WebView) findViewById(R.id.about_us_webview);
		progressBar = (ProgressBar) findViewById(R.id.about_us_loading_progressBar);
		progressBar.setVisibility(View.VISIBLE);
		mImgVwBackBtn.setOnClickListener(this);

		WebSettings settings = mWebView.getSettings();
		settings.setJavaScriptEnabled(true);
		settings.setBuiltInZoomControls(true);
		settings.setSupportZoom(true);
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
				Utility.showErrorDialog(AboutUsActivity.this, getString(R.string.app_name), description, null);
			}
		});
		// mWebView.loadUrl(APIUtils.ABOUT_US_URL);
		mWebView.loadUrl(getResources().getString(R.string.url_about_us));

	}

	@Override
	public void onClick(View v)
	{
		switch ( v.getId() )
		{
			case R.id.header_back_btn_imgVw :
				finish();
				break;

			default :
				break;
		}
	}
}
