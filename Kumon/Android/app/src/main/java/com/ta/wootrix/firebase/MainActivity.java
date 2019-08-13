package com.ta.wootrix.firebase;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.ImageView;
import android.widget.ProgressBar;

import com.ta.wootrix.R;
import com.ta.wootrix.phone.HomeActivity;
import com.ta.wootrix.tablet.TabletHomeActivity;
import com.ta.wootrix.utils.Utility;

import static com.nostra13.universalimageloader.core.ImageLoader.TAG;

public class MainActivity extends Activity implements View.OnClickListener {

    WebView webView;
    ProgressBar progressBar;
    String mURL = null;
    boolean status;
    ImageView banner_imageVw, back_imageVw;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_webmain);

        Intent intent = getIntent();

        if (intent != null) {
            mURL = intent.getStringExtra("murl");
        }

        webView = (WebView) findViewById(R.id.webview);
        progressBar = (ProgressBar) findViewById(R.id.progressBar);
        banner_imageVw = (ImageView) findViewById(R.id.banner_imageVw);
        back_imageVw = (ImageView) findViewById(R.id.backs_imageVw);

        back_imageVw.setOnClickListener(this);

        customWebView();
        webView.loadUrl(mURL);
    }

    private void customWebView() {
        progressBar.setVisibility(View.VISIBLE);
        WebSettings settings = webView.getSettings();
        settings.setPluginState(WebSettings.PluginState.ON);
        settings.setJavaScriptEnabled(true);
        settings.setBuiltInZoomControls(true);
        settings.setSupportZoom(true);
        webView.setScrollBarStyle(WebView.SCROLLBARS_OUTSIDE_OVERLAY);

        webView.setWebViewClient(new WebViewClient() {
            public boolean shouldOverrideUrlLoading(WebView view, String url) {
                Log.i("Wootrix", "Processing webview url click...");
                view.loadUrl(url);
                status = false;
                return true;
            }

            public void onPageFinished(WebView view, String url) {
                if (!status) {
                    //Do something you want when finished loading
                    Log.i(TAG, "Finished loading URL: " + url);
                    progressBar.setVisibility(View.GONE);
                }
            }

            @SuppressWarnings("deprecation")
            public void onReceivedError(WebView view, int errorCode, String description, String failingUrl) {
                Log.e("Checking", "Error: " + description);
                Utility.showErrorDialog(MainActivity.this, getString(R.string.app_name), description, null);
            }
        });
    }

    @Override
    public void onBackPressed() {
        super.onBackPressed();
        Intent homeIntent = new Intent(this, getString(R.string.device_type).equalsIgnoreCase("ph") ? HomeActivity.class : TabletHomeActivity.class);
        homeIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
        startActivity(homeIntent);
        finish();
    }

    @Override
    public void onClick(View v) {
        switch (v.getId()) {
            case R.id.backs_imageVw:
                Intent homeIntent = new Intent(this, getString(R.string.device_type).equalsIgnoreCase("ph") ? HomeActivity.class : TabletHomeActivity.class);
                homeIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                startActivity(homeIntent);
                finish();
                break;
        }
    }
}
