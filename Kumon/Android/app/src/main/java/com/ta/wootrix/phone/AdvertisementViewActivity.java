package com.ta.wootrix.phone;

import android.app.DownloadManager;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.os.Environment;
import android.support.v4.app.NavUtils;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.webkit.CookieManager;
import android.webkit.DownloadListener;
import android.webkit.URLUtil;
import android.webkit.WebSettings;
import android.webkit.WebSettings.PluginState;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import com.ta.wootrix.R;
import com.ta.wootrix.utils.Utility;

public class AdvertisementViewActivity extends BaseActivity implements OnClickListener
{
	protected static final String	TAG	= "tag";
	private WebView					mWebView;
	private TextView				mTxtHeaderTitle;
	private ImageView				mImgVwBackBtn;
	private ProgressBar				progressBar;
	boolean status;
	private String					mURl, type;

	@Override
	protected void onCreate(Bundle savedInstanceState)
	{
		super.onCreate(savedInstanceState);
		setContentView(R.layout.aboutus_screen);
		type = getIntent().getStringExtra("type");
		mURl = getIntent().getStringExtra("advtURL");
		initViews();
	}

	private void initViews()
	{
		mTxtHeaderTitle = (TextView) findViewById(R.id.header_text_txtVw);
		mTxtHeaderTitle.setText(getString(R.string.lbl_advrtsmnt));
		mImgVwBackBtn = (ImageView) findViewById(R.id.header_back_btn_imgVw);
		mWebView = (WebView) findViewById(R.id.about_us_webview);
		progressBar = (ProgressBar) findViewById(R.id.about_us_loading_progressBar);
		mImgVwBackBtn.setOnClickListener(this);

		customWebView();
		mWebView.loadUrl(mURl);

	}

	@Override
	public void onClick(View v)
	{
		switch ( v.getId() )
		{
			case R.id.header_back_btn_imgVw :

			    //hack :(
                Intent i = new Intent(this, HomeActivity.class);
                NavUtils.navigateUpTo(this, i);
                startActivity(i);

				break;

			default :
				break;
		}
	}

    @Override
    public void onBackPressed() {
        //hack :(
        Intent i = new Intent(this, HomeActivity.class);
        NavUtils.navigateUpTo(this, i);
        startActivity(i);
    }

    public void customWebView()
	{
		progressBar.setVisibility(View.VISIBLE);
		WebSettings settings = mWebView.getSettings();
		settings.setPluginState(PluginState.ON);
		settings.setJavaScriptEnabled(true);
		settings.setBuiltInZoomControls(true);
		settings.setSupportZoom(true);
		mWebView.setScrollBarStyle(WebView.SCROLLBARS_OUTSIDE_OVERLAY);

		mWebView.setWebViewClient(new WebViewClient() {
			@Override
			public boolean shouldOverrideUrlLoading(WebView view, String url) {
				Log.i(TAG, "Processing webview url click...");
				view.loadUrl(url);
				status = false;
				return true;
			}

			@Override
			public void onPageFinished(WebView view, String url) {
				if (!status) {
					//Do something you want when finished loading
					Log.i(TAG, "Finished loading URL: " + url);
					progressBar.setVisibility(View.GONE);
				}
			}

			@SuppressWarnings("deprecation")
			public void onReceivedError(WebView view, int errorCode, String description, String failingUrl)
			{
				Log.e(TAG, "Error: " + description);
				Utility.showErrorDialog(AdvertisementViewActivity.this, getString(R.string.app_name), description, null);
			}
		});

		mWebView.setDownloadListener(new DownloadListener() {
			@Override
			public void onDownloadStart(String url, String userAgent, String contentDisposition, String mimetype, long contentLength) {

				mWebView.loadUrl(mURl);

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
				request.setDestinationInExternalFilesDir(AdvertisementViewActivity.this,
						Environment.DIRECTORY_DOWNLOADS,".pdf");
				DownloadManager dm = (DownloadManager) getSystemService(DOWNLOAD_SERVICE);
				dm.enqueue(request);
				Toast.makeText(getApplicationContext(), "Fazendo o download do arquivo...",
						Toast.LENGTH_LONG).show();

			}
		});
	}
}
