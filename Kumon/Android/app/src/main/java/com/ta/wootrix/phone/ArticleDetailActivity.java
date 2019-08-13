package com.ta.wootrix.phone;

import android.annotation.SuppressLint;
import android.app.Activity;
import android.app.DownloadManager;
import android.app.ProgressDialog;
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
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import com.ta.wootrix.R;
import com.ta.wootrix.asynctask.BranchAsyncTask;
import com.ta.wootrix.modle.ArticleModle;
import com.ta.wootrix.utils.BranchDelegate;
import com.ta.wootrix.utils.Utility;

import java.io.UnsupportedEncodingException;

@SuppressLint("SetJavaScriptEnabled")
public class ArticleDetailActivity extends Activity implements OnClickListener, BranchDelegate {
    protected static final String TAG = "tag";
    private static final int REQ_COMMENT_ACTIVITY = 412;
    private TextView mTxtHeaderTitle;
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
    private String mMagaZineID;
    boolean status;
    private boolean isDeepLink;
    private ProgressDialog progressDialog;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.aboutus_screen);
        mArticleData = getIntent().getExtras().getParcelable("articleData");
        mMagaZineID = getIntent().getExtras().getString("magzineID");
        mArticlePos = getIntent().getExtras().getInt("position");
        isDeepLink = getIntent().getExtras().getBoolean("isDeepLink", false);
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
                Log.i("mslz", url);
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
                Utility.showErrorDialog(ArticleDetailActivity.this, getString(R.string.app_name), description, null);
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
                    request.setDestinationInExternalFilesDir(ArticleDetailActivity.this,
                            Environment.DIRECTORY_DOWNLOADS,".pdf");
                    DownloadManager dm = (DownloadManager) getSystemService(DOWNLOAD_SERVICE);
                    dm.enqueue(request);
                    Toast.makeText(getApplicationContext(), "Fazendo o download do arquivo...",
                            Toast.LENGTH_LONG).show();

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
        mTxtHeaderTitle = (TextView) findViewById(R.id.header_text_txtVw);
        mTxtHeaderTitle.setText(getString(R.string.header_article));
        mImgVwBackBtn = (ImageView) findViewById(R.id.header_back_btn_imgVw);
        mTxtVwComments = (TextView) findViewById(R.id.header_comment_txtVw);
        mImgVwShare = (ImageView) findViewById(R.id.header_share_imgVw);
        mWebView = (WebView) findViewById(R.id.about_us_webview);
        progressBar = (ProgressBar) findViewById(R.id.about_us_loading_progressBar);
        mTxtVwComments.setOnClickListener(this);
        mImgVwShare.setOnClickListener(this);
        mImgVwBackBtn.setOnClickListener(this);

        if (mArticleData.isCanShare())
            mImgVwShare.setVisibility(View.VISIBLE);
        if (mArticleData.isCanComment() && !isDeepLink) {
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
            case R.id.header_back_btn_imgVw:
                finish();
                break;

            case R.id.header_share_imgVw:
                // call for sharing the share dialog for handelling the shareing via
                // intent

                BranchAsyncTask branchAsyncTask = new BranchAsyncTask(this, mArticleData.getArticleID(), mMagaZineID == null ? "" : mMagaZineID);
                branchAsyncTask.execute();


                break;

            case R.id.header_comment_txtVw:
                Intent commentIntet = new Intent(this, CommentActivity.class);
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
                HomeActivity.getInstance().updateAricleDataComment(mArticlePos, launchFrom, count);
            }
    }

    @Override
    protected void onDestroy() {
        super.onDestroy();

    }

    @Override
    public void preCreate() {

        progressDialog = ProgressDialog.show(this, "Aguarde", "Processando data", true);
        progressDialog.setCancelable(false);

    }

    @Override
    public void postCreate(String result) {

        progressDialog.dismiss();

        try {
            // http://103.25.130.197/wootrix/index.php/wootrix-article-detail?articleId=20
            Intent shareIntent = new Intent(Intent.ACTION_SEND);
            shareIntent.setType("text/plain");
//            StringBuilder shareString = new StringBuilder(mArticleData.getArticleDescPlain());
//            if (mMagaZineID == null) {
//                if (mArticleData.isDetailLoadFromHtmlData())
//                    shareString.append("...  \n " + APIUtils.SHARING_BASE_URL).append("articleId=").append(mArticleData.getArticleID());
//                else {
//                    // shareString.append(mArticleData.getSource());
//                    shareString.append("... \n\n" + mArticleData.getFullSource());
//                }
//            } else {
//                if (mArticleData.getSource().equalsIgnoreCase("")) {
//                    shareString.append("...  \n " + APIUtils.SHARING_BASE_URL).append("source=").append(mArticleData.getCreatedSource()).append("&articleId=")
//                            .append(mArticleData.getArticleID()).append("&magazineId=").append(mMagaZineID);
//                } else {
//                    shareString.append("... \n\n" + mArticleData.getFullSource());// source
//                    // contain
//                    // full
//                    // side
//                    // url
//                }
//            }

//            shareString.append(result);

            shareIntent.putExtra(Intent.EXTRA_TEXT, result);
            startActivity(Intent.createChooser(shareIntent, "Please Choose"));
        } catch (Exception e) {
            e.printStackTrace();
            Utility.showToastMessage(this, getString(R.string.lbl_no_social_found));
        }

    }
}
