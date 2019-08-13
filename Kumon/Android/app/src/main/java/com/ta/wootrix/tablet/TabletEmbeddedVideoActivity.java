package com.ta.wootrix.tablet;

import android.graphics.Bitmap;
import android.os.Bundle;
import android.view.KeyEvent;
import android.view.View;
import android.view.View.OnClickListener;
import android.webkit.WebChromeClient;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.FrameLayout;
import android.widget.ImageView;

import com.ta.wootrix.R;
import com.ta.wootrix.phone.BaseActivity;

public class TabletEmbeddedVideoActivity extends BaseActivity implements OnClickListener
{
	private WebView								webView;
	private FrameLayout							customViewContainer;
	private WebChromeClient.CustomViewCallback	customViewCallback;
	private View								mCustomView;
	private myWebChromeClient					mWebChromeClient;
	private myWebViewClient						mWebViewClient;
	private ImageView							mImgVwBack;
	private String								mURl, type;

	/**
	 * Called when the activity is first created.
	 */
	@Override
	public void onCreate(Bundle savedInstanceState)
	{
		super.onCreate(savedInstanceState);
		setContentView(R.layout.embedded_video_lyt);

		/* header values set data */
		mImgVwBack = (ImageView) findViewById(R.id.tab_aboutus_backs_imageVw);
		mImgVwBack.setVisibility(View.VISIBLE);
		mImgVwBack.setOnClickListener(this);

		type = getIntent().getStringExtra("type");
		mURl = getIntent().getStringExtra("advtURL");
		initViews();

	}

	private void initViews()
	{
		customViewContainer = (FrameLayout) findViewById(R.id.customViewContainer);
		webView = (WebView) findViewById(R.id.webView);

		mWebViewClient = new myWebViewClient();
		webView.setWebViewClient(mWebViewClient);

		mWebChromeClient = new myWebChromeClient();
		webView.setWebChromeClient(mWebChromeClient);
		webView.getSettings().setJavaScriptEnabled(true);
		webView.getSettings().setAppCacheEnabled(true);
		webView.getSettings().setBuiltInZoomControls(true);
		webView.getSettings().setSaveFormData(true);
		webView.loadUrl(mURl);
	}

	@Override
	public void onClick(View v)
	{
		switch ( v.getId() )
		{
			case R.id.tab_aboutus_backs_imageVw :
				finish();
				break;

			default :
				break;
		}
	}

	public boolean inCustomView()
	{
		return (mCustomView != null);
	}

	public void hideCustomView()
	{
		mWebChromeClient.onHideCustomView();
	}

	@Override
	protected void onPause()
	{
		super.onPause(); // To change body of overridden methods use File |
							// Settings | File Templates.
		webView.onPause();
	}

	@Override
	protected void onResume()
	{
		super.onResume(); // To change body of overridden methods use File |
							// Settings | File Templates.
		webView.onResume();
	}

	@Override
	protected void onStop()
	{
		super.onStop(); // To change body of overridden methods use File |
						// Settings | File Templates.
		if (inCustomView())
		{
			hideCustomView();
		}
	}

	@Override
	public boolean onKeyDown(int keyCode, KeyEvent event)
	{
		if (keyCode == KeyEvent.KEYCODE_BACK)
		{

			if (inCustomView())
			{
				hideCustomView();
				return true;
			}

			if ((mCustomView == null) && webView.canGoBack())
			{
				webView.goBack();
				return true;
			}
		}
		return super.onKeyDown(keyCode, event);
	}

	class myWebChromeClient extends WebChromeClient
	{
		private Bitmap	mDefaultVideoPoster;
		private View	mVideoProgressView;

		@Override
		public void onShowCustomView(View view, int requestedOrientation, CustomViewCallback callback)
		{
			onShowCustomView(view, callback); // To change body of overridden
												// methods use File | Settings |
												// File Templates.
		}

		@Override
		public void onShowCustomView(View view, CustomViewCallback callback)
		{

			// if a view already exists then immediately terminate the new one
			if (mCustomView != null)
			{
				callback.onCustomViewHidden();
				return;
			}
			mCustomView = view;
			webView.setVisibility(View.GONE);
			customViewContainer.setVisibility(View.VISIBLE);
			customViewContainer.addView(view);
			customViewCallback = callback;
		}

		@Override
		public View getVideoLoadingProgressView()
		{

			if (mVideoProgressView == null)
			{
				// LayoutInflater inflater =
				// LayoutInflater.from(MyActivity.this);
				// mVideoProgressView =
				// inflater.inflate(R.layout.video_progress,
				// null);
			}
			return mVideoProgressView;
		}

		@Override
		public void onHideCustomView()
		{
			super.onHideCustomView(); // To change body of overridden methods
										// use File | Settings | File Templates.
			if (mCustomView == null)
				return;

			webView.setVisibility(View.VISIBLE);
			customViewContainer.setVisibility(View.GONE);

			// Hide the custom view.
			mCustomView.setVisibility(View.GONE);

			// Remove the custom view from its container.
			customViewContainer.removeView(mCustomView);
			customViewCallback.onCustomViewHidden();

			mCustomView = null;
		}
	}

	class myWebViewClient extends WebViewClient
	{
		@Override
		public boolean shouldOverrideUrlLoading(WebView view, String url)
		{
			return super.shouldOverrideUrlLoading(view, url); // To change body
																// of overridden
																// methods use
																// File |
																// Settings |
																// File
																// Templates.
		}
	}

}
