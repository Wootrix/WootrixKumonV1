package com.ta.wootrix.firebase;

import android.annotation.SuppressLint;
import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.util.Base64;
import android.util.Log;
import android.view.KeyEvent;
import android.view.View;
import android.view.View.OnClickListener;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.TextView;

import com.ta.wootrix.R;
import com.ta.wootrix.modle.ArticleModle;
import com.ta.wootrix.phone.HomeActivity;
import com.ta.wootrix.restapi.APIUtils;
import com.ta.wootrix.utils.Utility;

import java.io.UnsupportedEncodingException;

@SuppressLint("SetJavaScriptEnabled")
public class ArticleDetailActivityTwo extends Activity implements OnClickListener {
    protected static final String TAG = "ArticleDetailActivityTwo";
    private static final int REQ_COMMENT_ACTIVITY = 412;
    private ImageView mTxtHeaderTitle;
    private ImageView mImgVwBackBtn;
    private WebView mWebView;
    private ImageView mImgVwShare;
    private ArticleModle mArticleData;
    private ProgressBar progressBar;
    private TextView mTxtVwComments;
    private int mArticlePos;
    private boolean launchFrom;
    private boolean isMagzine;
    private String text;
    boolean status;
    private String mMagaZineID;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.articlenoti);
        mArticleData = getIntent().getExtras().getParcelable("articleData");
        mMagaZineID = getIntent().getExtras().getString("magzineID");
        mArticlePos = getIntent().getExtras().getInt("position");
        // launchFrom== true for articlse and false for searchResult
        launchFrom = getIntent().getBooleanExtra("launchFrom", false);
        isMagzine = getIntent().getBooleanExtra("isMagzine", false);
        initViews();

        WebSettings settings = mWebView.getSettings();
        settings.setJavaScriptEnabled(true);
        settings.setBuiltInZoomControls(true);
        settings.setSupportZoom(true);
        mWebView.setScrollBarStyle(WebView.SCROLLBARS_OUTSIDE_OVERLAY);

        progressBar.setVisibility(View.VISIBLE);

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
            public void onReceivedError(WebView view, int errorCode, String description, String failingUrl) {
                Log.e(TAG, "Error: " + description);
                Utility.showErrorDialog(ArticleDetailActivityTwo.this, getString(R.string.app_name), description, null);
            }
        });
        if (mArticleData.getFullSource().contains(".pdf")) {
            if (mArticleData.getFullSource() != null && !mArticleData.getFullSource().equalsIgnoreCase(""))
                mWebView.loadUrl(mArticleData.getFullSource());
            else
                mWebView.loadUrl(mArticleData.getSource());
        } else {
            if (mArticleData.isDetailLoadFromHtmlData()) {
                // Receiving side
                byte[] data = Base64.decode(mArticleData.getArticleDescHtml(), Base64.DEFAULT);
                try {
                    text = new String(data, "UTF-8");
                    Log.i("TAG", "String: " + text);
                    mWebView.loadData(text, "text/html", "UTF-8");
                } catch (UnsupportedEncodingException e) {
                    e.printStackTrace();
                }

            } else {
            /*
             * now source is changed from source to soruce1 to show the url list of data
			 */
                // mWebView.loadUrl(mArticleData.getSource());
                if (mArticleData.getFullSource() != null && !mArticleData.getFullSource().equalsIgnoreCase(""))
                    mWebView.loadUrl(mArticleData.getFullSource());
                else
                    mWebView.loadUrl(mArticleData.getSource());
            }
        }
    }

    private void initViews() {
        mTxtHeaderTitle = (ImageView) findViewById(R.id.tab_aboutus_top_banner_imageVw);
        mImgVwBackBtn = (ImageView) findViewById(R.id.tab_aboutus_backs_imageVw);
        mTxtVwComments = (TextView) findViewById(R.id.tab_aboutus_header_comment_txtVw);
        mImgVwShare = (ImageView) findViewById(R.id.tab_aboutus_header_share_imgVw);
        mWebView = (WebView) findViewById(R.id.about_us_webview);
        progressBar = (ProgressBar) findViewById(R.id.about_us_loading_progressBar);
        mTxtVwComments.setOnClickListener(this);
        mImgVwShare.setOnClickListener(this);
        mImgVwBackBtn.setOnClickListener(this);

        if (mArticleData.isCanShare())
            mImgVwShare.setVisibility(View.VISIBLE);
        if (mArticleData.isCanComment()) {
            mTxtVwComments.setVisibility(View.VISIBLE);
            mTxtVwComments.setText(mArticleData.getCommentCount());
        }

    }

    @Override
    public boolean onKeyDown(int keyCode, KeyEvent event) {
        if (event.getAction() == KeyEvent.ACTION_DOWN) {
            switch (keyCode) {
                case KeyEvent.KEYCODE_BACK:
                    if (mWebView.canGoBack()) {
                        mWebView.goBack();
                    } else {
                        finish();
                    }
                    return true;
            }
        }
        return super.onKeyDown(keyCode, event);
    }

    @Override
    public void onClick(View v) {
        switch (v.getId()) {
            case R.id.tab_aboutus_backs_imageVw:
                finish();
                break;

            case R.id.tab_aboutus_header_share_imgVw:
                // call for sharing the share dialog for handelling the shareing via
                // intent
                try {
                    // http://103.25.130.197/wootrix/index.php/wootrix-article-detail?articleId=20
                    Intent shareIntent = new Intent(Intent.ACTION_SEND);
                    shareIntent.setType("text/plain");
                    StringBuilder shareString = new StringBuilder(mArticleData.getArticleDescPlain());
                    if (mMagaZineID == null) {
                        if (mArticleData.isDetailLoadFromHtmlData())
                            shareString.append("...  \n " + APIUtils.SHARING_BASE_URL).append("articleId=").append(mArticleData.getArticleID());
                        else {
                            // shareString.append(mArticleData.getSource());
                            shareString.append(mArticleData.getFullSource());
                        }
                    } else {
                        if (mArticleData.getSource().equalsIgnoreCase("")) {
                            shareString.append("...  \n " + APIUtils.SHARING_BASE_URL).append("source=").append(mArticleData.getCreatedSource()).append("&articleId=")
                                    .append(mArticleData.getArticleID()).append("&magazineId=").append(mMagaZineID);
                        } else {
                            shareString.append(mArticleData.getFullSource());
                            // source
                            // contain
                            // full
                            // side
                            // url
                        }
                    }

                    shareIntent.putExtra(Intent.EXTRA_TEXT, shareString.toString());
                    startActivity(Intent.createChooser(shareIntent, "Please Choose"));
                } catch (Exception e) {
                    e.printStackTrace();
                    Utility.showToastMessage(this, getString(R.string.lbl_no_social_found));
                }
                break;

            case R.id.tab_aboutus_header_comment_txtVw:
                Intent commentIntet = new Intent(this, CommentActivityTwo.class);
                commentIntet.putExtra("articleID", mArticleData.getArticleID());
                commentIntet.putExtra("isMagzine", isMagzine);
                startActivityForResult(commentIntet, REQ_COMMENT_ACTIVITY);
                break;

            default:
                break;
        }
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        if (requestCode == REQ_COMMENT_ACTIVITY)
            if (resultCode == RESULT_OK && data != null) {
                String count = data.getStringExtra("count");
                mTxtVwComments.setText(count);
                HomeActivity.getInstance().updateAricleDataCommentUsingId(Integer.parseInt(mArticleData.getArticleID()), launchFrom, count);
            }
    }
}
