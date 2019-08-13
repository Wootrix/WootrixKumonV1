package com.ta.wootrix.phone;

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
import android.widget.ProgressBar;
import android.widget.TextView;

import com.ta.wootrix.R;

public class EmbeddedVideoActivity extends BaseActivity implements OnClickListener
{
	protected static final String				TAG	= "tag";
	private WebView								mWebView;
	private TextView							mTxtHeaderTitle;
	private ImageView							mImgVwBackBtn;
	private ProgressBar							progressBar;
	private String								mURl, type;
	private WebChromeClient.CustomViewCallback	customViewCallback;
	private View								mCustomView;
	private myWebChromeClient					mWebChromeClient;
	private myWebViewClient						mWebViewClient;
	private FrameLayout							customViewContainer;

	@Override
	protected void onCreate(Bundle savedInstanceState)
	{
		super.onCreate(savedInstanceState);
		setContentView(R.layout.embedded_video_lyt);
		type = getIntent().getStringExtra("type");
		mURl = getIntent().getStringExtra("advtURL");
		initViews();
	}

	private void initViews()
	{

		mTxtHeaderTitle = (TextView) findViewById(R.id.header_text_txtVw);
		mTxtHeaderTitle.setText(getString(R.string.lbl_advrtsmnt));
		mImgVwBackBtn = (ImageView) findViewById(R.id.header_back_btn_imgVw);
		mImgVwBackBtn.setOnClickListener(this);

		customViewContainer = (FrameLayout) findViewById(R.id.customViewContainer);
		mWebView = (WebView) findViewById(R.id.webView);

		mWebViewClient = new myWebViewClient();
		mWebView.setWebViewClient(mWebViewClient);

		mWebChromeClient = new myWebChromeClient();
		mWebView.setWebChromeClient(mWebChromeClient);
		mWebView.getSettings().setJavaScriptEnabled(true);
		mWebView.getSettings().setAppCacheEnabled(true);
		mWebView.getSettings().setBuiltInZoomControls(true);
		mWebView.getSettings().setSaveFormData(true);
		mWebView.loadUrl(mURl);

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

	public boolean inCustomView()
	{
		return (mCustomView != null);
	}

	public void hideCustomView()
	{
		if (mWebChromeClient != null)
			mWebChromeClient.onHideCustomView();
	}

	@Override
	protected void onPause()
	{
		super.onPause(); // To change body of overridden methods use File |
							// Settings | File Templates.
		if (mWebView != null)
			mWebView.onPause();
	}

	@Override
	protected void onResume()
	{
		super.onResume(); // To change body of overridden methods use File |

		if (mWebView != null)// Settings | File Templates.
			mWebView.onResume();
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

			if ((mCustomView == null) && mWebView.canGoBack())
			{
				mWebView.goBack();
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
			mWebView.setVisibility(View.GONE);
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

			mWebView.setVisibility(View.VISIBLE);
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

			view.loadUrl(url);
			return false;
			// return super.shouldOverrideUrlLoading(view, url); // To change
			// body
			// of overridden
			// methods use
			// File |
			// Settings |
			// File
			// Templates.
		}
	}

}
