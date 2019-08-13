package com.ta.wootrix.tablet;

import android.app.DownloadManager;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.os.Environment;
import android.util.Base64;
import android.util.Log;
import android.view.KeyEvent;
import android.view.View;
import android.view.View.OnClickListener;
import android.webkit.CookieManager;
import android.webkit.DownloadListener;
import android.webkit.URLUtil;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import com.nostra13.universalimageloader.core.ImageLoader;
import com.ta.wootrix.R;
import com.ta.wootrix.modle.ArticleModle;
import com.ta.wootrix.phone.ArticleDetailActivity;
import com.ta.wootrix.phone.BaseActivity;
import com.ta.wootrix.restapi.APIUtils;
import com.ta.wootrix.utils.Utility;

import java.io.UnsupportedEncodingException;

public class TabletArticleDetailActivity extends BaseActivity implements OnClickListener
{
	protected static final String	TAG	= "TAG";
	private WebView					mWebView;
	private ImageView				mImgVwShare;
	private ImageView				mImgArticeLogo;
	private FrameLayout				mFrameLytFragmentLyt;
	private CommentsFragment		mFragmentComments;
	private ArticleModle			mArticleData;
	private ProgressBar				progressBar;
	private ImageView				mImgVwBack;
	private ImageLoader				mImgLoader;
	private String					mLogoURL;
	private TextView				mTxtComments;
	private int						mArticlePos;
	private boolean					launchFrom;
	private boolean					isMagzine;
	private String					mMagaZineID,url;

	@Override
	protected void onCreate(Bundle savedInstanceState)
	{
		super.onCreate(savedInstanceState);
		setContentView(R.layout.aboutus_screen);
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
				Log.i(TAG, "Finished loading URL: " + url);
				progressBar.setVisibility(View.GONE);
			}

			@SuppressWarnings("deprecation")
			public void onReceivedError(WebView view, int errorCode, String description, String failingUrl)
			{
				Log.e(TAG, "Error: " + description);
				Utility.showErrorDialog(TabletArticleDetailActivity.this, getString(R.string.app_name), description, null);
			}
		});

		mWebView.setDownloadListener(new DownloadListener() {
			@Override
			public void onDownloadStart(String url, String userAgent, String contentDisposition, String mimetype, long contentLength) {

				mWebView.loadUrl(mArticleData.getFullSource());

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
				request.setDestinationInExternalFilesDir(TabletArticleDetailActivity.this,
						Environment.DIRECTORY_DOWNLOADS,".pdf");
				DownloadManager dm = (DownloadManager) getSystemService(DOWNLOAD_SERVICE);
				dm.enqueue(request);
				Toast.makeText(getApplicationContext(), "Fazendo o download do arquivo...",
						Toast.LENGTH_LONG).show();

			}
		});

		if (mArticleData.isDetailLoadFromHtmlData())
		{
			// Receiving side
			byte[] data = Base64.decode(mArticleData.getArticleDescHtml(), Base64.DEFAULT);
			String text;
			try
			{
				text = new String(data, "UTF-8");
				mWebView.loadData(text, "text/html", "UTF-8");
			}
			catch (UnsupportedEncodingException e)
			{
				e.printStackTrace();
			}

		}
		else
		{
			// mWebView.loadUrl(mArticleData.getSource());
			if (mArticleData.getFullSource() != null && !mArticleData.getFullSource().equalsIgnoreCase(""))
				mWebView.loadUrl(mArticleData.getFullSource());
			else
				mWebView.loadUrl(mArticleData.getSource());
		}

	}

	@Override
	public boolean onKeyDown(int keyCode, KeyEvent event)
	{
		if (event.getAction() == KeyEvent.ACTION_DOWN)
		{
			switch ( keyCode )
			{
				case KeyEvent.KEYCODE_BACK :
					if (mWebView.canGoBack())
					{
						mWebView.goBack();
					}
					else if (mFrameLytFragmentLyt.isShown())
					{
						mFrameLytFragmentLyt.setVisibility(View.GONE);
					}
					else
					{
						finish();
					}
					return true;
			}

		}
		return super.onKeyDown(keyCode, event);
	}

	private void initViews()
	{
		mImgVwBack = (ImageView) findViewById(R.id.tab_aboutus_backs_imageVw);
		mTxtComments = (TextView) findViewById(R.id.tab_aboutus_header_comment_txtVw);
		mImgVwShare = (ImageView) findViewById(R.id.tab_aboutus_header_share_imgVw);
		mImgArticeLogo = (ImageView) findViewById(R.id.tab_aboutus_top_banner_imageVw);
		progressBar = (ProgressBar) findViewById(R.id.about_us_loading_progressBar);
		mImgVwBack.setVisibility(View.VISIBLE);
		mWebView = (WebView) findViewById(R.id.about_us_webview);
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

					if (mMagaZineID == null)
					{
						if (mArticleData.isDetailLoadFromHtmlData())
							shareString.append("... \n " + APIUtils.SHARING_BASE_URL).append("articleId=").append(mArticleData.getArticleID());
						else
						{
							// changes due to tester
							// shareString.append(mArticleData.getSource());//
							// source contain now only side name
							shareString.append(mArticleData.getFullSource());// source
																				// contain
																				// full
																				// side
																				// url
						}
					}
					else
					{
						if (mArticleData.getSource().equalsIgnoreCase(""))
						{
							shareString.append("... \n " + APIUtils.SHARING_BASE_URL).append("source=").append(mArticleData.getCreatedSource()).append("&articleId=")
									.append(mArticleData.getArticleID()).append("&magazineId=").append(mMagaZineID);
						}
						else
						{
							shareString.append(mArticleData.getFullSource());// source
																				// contain
																				// full
																				// side
																				// url
						}
					}

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
}