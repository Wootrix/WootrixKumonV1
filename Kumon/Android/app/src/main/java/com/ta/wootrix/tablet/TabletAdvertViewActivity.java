package com.ta.wootrix.tablet;

import android.app.DownloadManager;
import android.net.Uri;
import android.os.Bundle;
import android.os.Environment;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.webkit.CookieManager;
import android.webkit.DownloadListener;
import android.webkit.URLUtil;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.Toast;

import com.ta.wootrix.R;
import com.ta.wootrix.phone.BaseActivity;
import com.ta.wootrix.utils.Utility;

public class TabletAdvertViewActivity extends BaseActivity implements OnClickListener
{
	protected static final String	TAG	= "TAG";
	private WebView					mWebView;
	private ProgressBar				progressBar;
	private ImageView				mImgVwBack;
	private String					mURL, type;

	@Override
	protected void onCreate(Bundle savedInstanceState)
	{
		super.onCreate(savedInstanceState);
		setContentView(R.layout.aboutus_screen);
		type = getIntent().getStringExtra("type");
		mURL = getIntent().getExtras().getString("advtURL");
		Log.d("mslz", mURL);

		initViews();

	}

	private void initViews()
	{
		mImgVwBack = (ImageView) findViewById(R.id.tab_aboutus_backs_imageVw);
		progressBar = (ProgressBar) findViewById(R.id.about_us_loading_progressBar);
		mImgVwBack.setVisibility(View.VISIBLE);
		mImgVwBack.setOnClickListener(this);

		mWebView = (WebView) findViewById(R.id.about_us_webview);
		customWebView();
		mWebView.loadUrl(mURL);
	}

	@Override
	public void onClick(View v)
	{
		switch ( v.getId() )
		{
			case R.id.tab_aboutus_backs_imageVw :
				onBackPressed();
				break;
			default :
				break;
		}
	}

	public void customWebView()
	{

		/* set web view */
		WebSettings settings = mWebView.getSettings();
		settings.setJavaScriptEnabled(true);
		settings.setBuiltInZoomControls(true);
		settings.setSupportZoom(true);
		mWebView.setScrollBarStyle(WebView.SCROLLBARS_OUTSIDE_OVERLAY);
		progressBar.setVisibility(View.VISIBLE);
		mWebView.setWebViewClient(new WebViewClient() {
			public boolean shouldOverrideUrlLoading(WebView view, String url)
			{
				Log.i(TAG, "Processing webview url click...");
				view.loadUrl(url);
				return true;
			}

			public void onPageFinished(WebView view, String url)
			{
				Log.i(TAG, "Finished Advertisement loading URL: " + url);
				progressBar.setVisibility(View.GONE);
			}

			@SuppressWarnings("deprecation")
			public void onReceivedError(WebView view, int errorCode, String description, String failingUrl)
			{
				Log.e(TAG, "Error: " + description);
				Utility.showErrorDialog(TabletAdvertViewActivity.this, getString(R.string.app_name), description, null);
			}
		});

		mWebView.setDownloadListener(new DownloadListener() {
			@Override
			public void onDownloadStart(String url, String userAgent, String contentDisposition, String mimetype, long contentLength) {

				mWebView.loadUrl(mURL);

				DownloadManager.Request request = new DownloadManager.Request(
						Uri.parse(url));

				request.setMimeType(mimetype);

				String cookies = CookieManager.getInstance().getCookie(url);

				request.addRequestHeader("cookie", cookies);
				request.addRequestHeader("User-Agent", userAgent);
				request.setDescription("Fazendo o download do arquivo...");

				request.setTitle(URLUtil.guessFileName(url, contentDisposition,
						mimetype));

				request.allowScanningByMediaScanner();

				request.setNotificationVisibility(DownloadManager.Request.VISIBILITY_VISIBLE_NOTIFY_COMPLETED);
				request.setDestinationInExternalFilesDir(TabletAdvertViewActivity.this,
						Environment.DIRECTORY_DOWNLOADS,".pdf");
				DownloadManager dm = (DownloadManager) getSystemService(DOWNLOAD_SERVICE);
				dm.enqueue(request);
				Toast.makeText(getApplicationContext(), "Fazendo o download do arquivo...",
						Toast.LENGTH_LONG).show();

			}
		});

	}

}