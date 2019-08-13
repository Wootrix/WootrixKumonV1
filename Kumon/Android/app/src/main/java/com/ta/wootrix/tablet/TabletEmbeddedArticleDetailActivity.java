package com.ta.wootrix.tablet;

import android.content.Intent;
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
import android.widget.TextView;
import android.widget.Toast;

import com.nostra13.universalimageloader.core.ImageLoader;
import com.ta.wootrix.R;
import com.ta.wootrix.modle.ArticleModle;
import com.ta.wootrix.phone.BaseActivity;
import com.ta.wootrix.restapi.APIUtils;
import com.ta.wootrix.utils.Utility;

public class TabletEmbeddedArticleDetailActivity extends BaseActivity implements OnClickListener
{
	protected static final String				TAG	= "TAG";
	private ImageView							mImgVwShare;
	private ImageView							mImgArticeLogo;
	private FrameLayout							mFrameLytFragmentLyt;
	private CommentsFragment					mFragmentComments;
	private ArticleModle						mArticleData;
	private ImageView							mImgVwBack;
	private ImageLoader							mImgLoader;
	private String								mLogoURL;
	private TextView							mTxtComments;
	private int									mArticlePos;
	private boolean								launchFrom;
	private boolean								isMagzine;
	private String								mMagaZineID;
	private WebView								webView;
	private FrameLayout							customViewContainer;
	private WebChromeClient.CustomViewCallback	customViewCallback;
	private View								mCustomView;
	private myWebChromeClient					mWebChromeClient;
	private myWebViewClient						mWebViewClient;

	@Override
	protected void onCreate(Bundle savedInstanceState)
	{
		super.onCreate(savedInstanceState);
		setContentView(R.layout.embedded_video_lyt);
		mArticleData = getIntent().getExtras().getParcelable("articleData");
		mLogoURL = getIntent().getExtras().getString("logoURL");
		mArticlePos = getIntent().getExtras().getInt("position");
		mMagaZineID = getIntent().getExtras().getString("magzineID");
		// launchFrom== true for articlse and false for searchResult
		launchFrom = getIntent().getBooleanExtra("launchFrom", false);
		isMagzine = getIntent().getBooleanExtra("isMagzine", false);

		initViews();

		if (mLogoURL != null)
		{
			mImgLoader = Utility.getImageLoader(this);
			mImgLoader.displayImage(mLogoURL, mImgArticeLogo, Utility.getTopBannerLogiDisplayOption());
		}

		setEmbeddedWebVideo();

		/*
		 * if (mArticleData.isDetailLoadFromHtmlData()) { // Receiving side byte[] data =
		 * Base64.decode(mArticleData.getArticleDescHtml(), Base64.DEFAULT); String text; try { text
		 * = new String(data, "UTF-8"); mWebView.loadData(text, "text/html", "UTF-8"); } catch
		 * (UnsupportedEncodingException e) { e.printStackTrace(); } } else {
		 * //mWebView.loadUrl(mArticleData.getSource());
		 * mWebView.loadUrl(mArticleData.getFullSource()); }
		 */

	}

	/*
	 * @Override public boolean onKeyDown(int keyCode, KeyEvent event) { if (event.getAction() ==
	 * KeyEvent.ACTION_DOWN) { switch (keyCode) { case KeyEvent.KEYCODE_BACK: if
	 * (mWebView.canGoBack()) { mWebView.goBack(); } else if (mFrameLytFragmentLyt.isShown()) {
	 * mFrameLytFragmentLyt.setVisibility(View.GONE); } else { finish(); } return true; } } return
	 * super.onKeyDown(keyCode, event); }
	 */

	private void initViews()
	{
		mImgVwBack = (ImageView) findViewById(R.id.tab_aboutus_backs_imageVw);
		mTxtComments = (TextView) findViewById(R.id.tab_aboutus_header_comment_txtVw);
		mImgVwShare = (ImageView) findViewById(R.id.tab_aboutus_header_share_imgVw);
		mImgArticeLogo = (ImageView) findViewById(R.id.tab_aboutus_top_banner_imageVw);
		mImgVwBack.setVisibility(View.VISIBLE);
		mFrameLytFragmentLyt = (FrameLayout) findViewById(R.id.about_usfragment_fram_layout);
		mTxtComments.setOnClickListener(this);
		mImgVwShare.setOnClickListener(this);
		mFrameLytFragmentLyt.setOnClickListener(this);
		mImgVwBack.setOnClickListener(this);
		if (mArticleData.isCanShare())
			mImgVwShare.setVisibility(View.VISIBLE);
		if (mArticleData.isCanComment())
		{
			mTxtComments.setVisibility(View.VISIBLE);
			mTxtComments.setText(mArticleData.getCommentCount());
		}

	}

	public void setEmbeddedWebVideo()
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

		// String videoUrl =
		// "https://player.vimeo.com/video/134635726?title=0&byline=0&badge=0";
		String videoUrl = mArticleData.getEmbeddedVideoUrl();
		if (videoUrl != null)
		{
			webView.loadUrl(videoUrl);
		}
		else
		{
			Toast.makeText(this, "No video data to pley", Toast.LENGTH_LONG).show();
		}

	}

	@Override
	public void onClick(View v)
	{
		switch ( v.getId() )
		{
			case R.id.tab_aboutus_backs_imageVw :
				finish();
				break;
			case R.id.tab_aboutus_header_share_imgVw :
				// call for sharing the share dialog for handelling the sharing via
				// intent
				try
				{
					Intent shareIntent = new Intent(Intent.ACTION_SEND);
					shareIntent.setType("text/plain");
					StringBuilder shareString = new StringBuilder(mArticleData.getArticleDescPlain());

					if (mArticleData.getArticleType().equalsIgnoreCase("embedded"))
					{
						shareString.append(mArticleData.getEmbeddedVideoUrl());
					}
					else if (mMagaZineID == null)
					{
						if (mArticleData.isDetailLoadFromHtmlData())
							shareString.append("... \n " + APIUtils.SHARING_BASE_URL).append("articleId=").append(mArticleData.getArticleID());
						else
							shareString.append(mArticleData.getSource());
					}
					else
						shareString.append("... \n " + APIUtils.SHARING_BASE_URL).append("articleId=").append(mArticleData.getArticleID()).append("&magazineId=").append(mMagaZineID);

					shareIntent.putExtra(Intent.EXTRA_TEXT, shareString.toString());
					startActivity(Intent.createChooser(shareIntent, "Please Choose"));
				}
				catch (Exception e)
				{
					e.printStackTrace();
					Utility.showToastMessage(this, getString(R.string.lbl_no_social_found));
				}
				break;

			case R.id.tab_aboutus_header_comment_txtVw :
				if (getFragmentManager().getBackStackEntryCount() > 0 && mFrameLytFragmentLyt.isShown())
				{
					mFrameLytFragmentLyt.setVisibility(View.INVISIBLE);
				}
				else
				{
					mFrameLytFragmentLyt.setVisibility(View.VISIBLE);
					if (getFragmentManager().getBackStackEntryCount() == 0)
					{
						mFragmentComments = new CommentsFragment();
						Bundle mBundle = new Bundle();
						mBundle.putString("mArticleID", mArticleData.getArticleID());
						mBundle.putBoolean("isMagzine", isMagzine);
						mFragmentComments.setArguments(mBundle);
						getFragmentManager().beginTransaction().replace(R.id.about_usfragment_fram_layout, mFragmentComments).addToBackStack(mFragmentComments.getClass().toString())
								.commitAllowingStateLoss();
					}
				}
				break;
			case R.id.about_usfragment_fram_layout :
				mFrameLytFragmentLyt.setVisibility(View.INVISIBLE);
				break;

			default :
				break;
		}
	}

	public void updateCommentCount(int i)
	{
		mTxtComments.setText(mArticleData.getCommentCount());
		TabletHomeActivity.getInstance().updateAricleDataComment(mArticlePos, launchFrom, mArticleData.getCommentCount());
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