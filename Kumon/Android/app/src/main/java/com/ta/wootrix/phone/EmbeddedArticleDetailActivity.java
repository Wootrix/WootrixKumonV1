package com.ta.wootrix.phone;

import android.annotation.SuppressLint;
import android.app.ProgressDialog;
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

import com.ta.wootrix.R;
import com.ta.wootrix.asynctask.BranchAsyncTask;
import com.ta.wootrix.modle.ArticleModle;
import com.ta.wootrix.utils.BranchDelegate;
import com.ta.wootrix.utils.Utility;

@SuppressLint("SetJavaScriptEnabled")
public class EmbeddedArticleDetailActivity extends BaseActivity implements OnClickListener, BranchDelegate
{
	protected static final String				TAG						= "tag";
	private static final int					REQ_COMMENT_ACTIVITY	= 412;
	private TextView							mTxtHeaderTitle;
	private ImageView							mImgVwBackBtn;
	private WebView								mWebView;
	private ImageView							mImgVwShare;
	private ArticleModle						mArticleData;
	private TextView							mTxtVwComments;
	private int									mArticlePos;
	private boolean								launchFrom;
	private boolean								isMagzine;
	private String								text;
	private String								mMagaZineID;
	private WebChromeClient.CustomViewCallback	customViewCallback;
	private View								mCustomView;
	private myWebChromeClient					mWebChromeClient;
	private myWebViewClient						mWebViewClient;
	private FrameLayout							customViewContainer;
	private ProgressDialog progressDialog;

	@Override
	protected void onCreate(Bundle savedInstanceState)
	{
		super.onCreate(savedInstanceState);
		setContentView(R.layout.embedded_video_lyt);
		mArticleData = getIntent().getExtras().getParcelable("articleData");
		mMagaZineID = getIntent().getExtras().getString("magzineID");
		mArticlePos = getIntent().getExtras().getInt("position");
		// launchFrom== true for articlse and false for searchResult
		launchFrom = getIntent().getBooleanExtra("launchFrom", false);
		isMagzine = getIntent().getBooleanExtra("isMagzine", false);

		initViews();
		setDefaultWebVideo();

	}

	private void initViews()
	{
		/* initialise the header part of activity */
		mTxtHeaderTitle = (TextView) findViewById(R.id.header_text_txtVw);
		mTxtHeaderTitle.setText(getString(R.string.header_article));
		mImgVwBackBtn = (ImageView) findViewById(R.id.header_back_btn_imgVw);
		mTxtVwComments = (TextView) findViewById(R.id.header_comment_txtVw);
		mImgVwShare = (ImageView) findViewById(R.id.header_share_imgVw);

		mTxtVwComments.setOnClickListener(this);
		mImgVwShare.setOnClickListener(this);
		mImgVwBackBtn.setOnClickListener(this);

		if (mArticleData.isCanShare())
			mImgVwShare.setVisibility(View.VISIBLE);
		if (mArticleData.isCanComment())
		{
			mTxtVwComments.setVisibility(View.VISIBLE);
			mTxtVwComments.setText(mArticleData.getCommentCount());
		}

	}

	public void setDefaultWebVideo()
	{

		mWebView = (WebView) findViewById(R.id.webView);
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
		String videoUrl = mArticleData.getEmbeddedVideoUrl();
		// "https://www.youtube.com/embed/IAS7ZVethOY"

		if (videoUrl != null)
		{
			mWebView.loadUrl(videoUrl);
		}
		else
		{
			Toast.makeText(this, "No video data to pley", Toast.LENGTH_LONG).show();
		}
	}

	/*
	 * @Override public boolean onKeyDown(int keyCode, KeyEvent event) { if (event.getAction() ==
	 * KeyEvent.ACTION_DOWN) { switch (keyCode) { case KeyEvent.KEYCODE_BACK: if
	 * (mWebView.canGoBack()) { mWebView.goBack(); } else { finish(); } return true; } } return
	 * super.onKeyDown(keyCode, event); }
	 */

	@Override
	public void onClick(View v)
	{
		switch ( v.getId() )
		{
			case R.id.header_back_btn_imgVw :
				finish();
				break;

			case R.id.header_share_imgVw :
				// call for sharing the share dialog for handelling the shareing via
				// intent

				BranchAsyncTask branchAsyncTask = new BranchAsyncTask(this, mArticleData.getArticleID(), mMagaZineID == null ? "" : mMagaZineID);
				branchAsyncTask.execute();


				break;

			case R.id.header_comment_txtVw :
				Intent commentIntet = new Intent(this, CommentActivity.class);
				commentIntet.putExtra("articleID", mArticleData.getArticleID());
				commentIntet.putExtra("isMagzine", isMagzine);
				startActivityForResult(commentIntet, REQ_COMMENT_ACTIVITY);
				break;

			default :
				break;
		}
	}

	@Override
	protected void onActivityResult(int requestCode, int resultCode, Intent data)
	{
		super.onActivityResult(requestCode, resultCode, data);
		if (requestCode == REQ_COMMENT_ACTIVITY)
			if (resultCode == RESULT_OK && data != null)
			{
				String count = data.getStringExtra("count");
				mTxtVwComments.setText(count);
				HomeActivity.getInstance().updateAricleDataComment(mArticlePos, launchFrom, count);
			}
	}

	/* web view setting for embedded video */
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
		mWebView.onPause();
	}

	@Override
	protected void onResume()
	{
		super.onResume(); // To change body of overridden methods use File |
							// Settings | File Templates.
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

	@Override
	public void preCreate() {

		progressDialog = ProgressDialog.show(this, "Aguarde", "Processando data", true);
		progressDialog.setCancelable(false);

	}

	@Override
	public void postCreate(String result) {

        progressDialog.dismiss();

		try
		{
			// http://103.25.130.197/wootrix/index.php/wootrix-article-detail?articleId=20
			Intent shareIntent = new Intent(Intent.ACTION_SEND);
			shareIntent.setType("text/plain");
//			StringBuilder shareString = new StringBuilder(mArticleData.getArticleDescPlain());
//			if (mArticleData.getArticleType().equalsIgnoreCase("embedded"))
//			{
//				shareString.append(mArticleData.getEmbeddedVideoUrl());
//			}
//			else if (mMagaZineID == null)
//			{
//				if (mArticleData.isDetailLoadFromHtmlData())
//					shareString.append("...  \n " + APIUtils.SHARING_BASE_URL).append("articleId=").append(mArticleData.getArticleID());
//				else
//					shareString.append(mArticleData.getSource());
//			}
//			else
//				shareString.append("...  \n " + APIUtils.SHARING_BASE_URL).append("articleId=").append(mArticleData.getArticleID()).append("&magazineId=").append(mMagaZineID);

			shareIntent.putExtra(Intent.EXTRA_TEXT, result);
			startActivity(Intent.createChooser(shareIntent, "Please Choose"));
		}
		catch (Exception e)
		{
			e.printStackTrace();
			Utility.showToastMessage(this, getString(R.string.lbl_no_social_found));
		}

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
