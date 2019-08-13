package com.ta.wootrix.phone;

import android.content.Intent;
import android.content.pm.ActivityInfo;
import android.content.res.Configuration;
import android.os.Bundle;
import android.os.Handler;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.widget.RelativeLayout;

import com.ta.wootrix.R;
import com.ta.wootrix.firebase.StorePreference;
import com.ta.wootrix.restapi.APIUtils;
import com.ta.wootrix.tablet.TabletHomeActivity;
import com.ta.wootrix.utils.Constants;
import com.ta.wootrix.utils.Utility;

import org.json.JSONException;
import org.json.JSONObject;

public class SplashActivity extends AppCompatActivity {

    private Handler mHanlder;
    String receivedMessage = null, article_id = null, notiFurl = null, messageType = null;
    StorePreference myStorePreference;
    Utility utility;

    String allowShare = null, fullSource = null, allowComment = null, embedded_video = null, title = null,
            createdBy = null, articleDesc = null, articleVideoUrl = null, coverPhotoUrl = null,
            articleType = null, artArticle_id = null, close_subtype = null;

    @Override
    public void onCreate(Bundle savedInstanceState) {

        super.onCreate(savedInstanceState);

        if (getString(R.string.device_type).equalsIgnoreCase("ph")) {
            setRequestedOrientation(ActivityInfo.SCREEN_ORIENTATION_PORTRAIT);
        }

        setContentView(R.layout.splash_screen);

        myStorePreference = new StorePreference(this);
        utility = new Utility();

        receiveNotification();

        if (messageType != null) {
            Utility.toastMessage(this, messageType);
        }

        mHanlder = new Handler();

        String appLanguage = Utility.getSharedPrefStringData(this, Constants.APP_LANGUAGE);
        if (appLanguage.length() > 0)
            Utility.updateAppDefaultLanguage(this, appLanguage);

        if (Utility.isNetworkAvailable(this)) {

            if (!Utility.getSharedPrefBooleanData(SplashActivity.this, Constants.PREF_KEY_LOGIN_STATUS)) {

                mHanlder.postDelayed(new Runnable() {
                    @Override
                    public void run() {
                        //startActivity(new Intent(SplashActivity.this, LoginActivity.class));

                        Intent intentLogin = new Intent(SplashActivity.this, LoginActivity.class);
                        intentLogin.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
                        intentLogin.putExtra("message", receivedMessage);
                        intentLogin.putExtra("article_id", article_id);
                        intentLogin.putExtra("url", notiFurl);
                        intentLogin.putExtra("messagetype", messageType);
                        startActivity(intentLogin);
                        finish();
                    }
                }, 2000);

            } else {

                new Thread(new Runnable() {

                    String response = "";

                    @Override
                    public void run() {

                        JSONObject json = null;

                        try {
                            json = new JSONObject();
                            json.put("token", Utility.getSharedPrefStringData(SplashActivity.this, Constants.USER_TOKEN));
                            json.put("appLanguage", Utility.getDrfaultLanguage());
                        } catch (JSONException e) {
                            e.printStackTrace();
                        }

                        String url = APIUtils.getBaseURL(APIUtils.VERIFY_TOKEN);
                        response = Utility.httpPostRequestToServer(url, json.toString(), SplashActivity.this);

                        mHanlder.postDelayed(new Runnable() {
                            @Override
                            public void run() {

                                if (response != null && response.length() > 0) {

                                    JSONObject JsonObject;

                                    try {

                                        JsonObject = new JSONObject(response);

                                        if (JsonObject.optBoolean("success")) {

                                            if (Utility.getSharedPrefBooleanData(SplashActivity.this, Constants.isMagzineAvailable)) {

                                                Intent intentLanding = new Intent(SplashActivity.this, LandingPageActivity.class);
                                                intentLanding.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
                                                intentLanding.putExtra("message", receivedMessage);
                                                intentLanding.putExtra("article_id", article_id);
                                                intentLanding.putExtra("url", notiFurl);
                                                intentLanding.putExtra("messagetype", messageType);

                                                if (messageType != null) {

                                                    if (messageType.equals("article")) {

                                                        intentLanding.putExtra("allowShare", allowShare);
                                                        intentLanding.putExtra("fullSource", fullSource);
                                                        intentLanding.putExtra("allowComment", allowComment);
                                                        intentLanding.putExtra("embedded_video", embedded_video);
                                                        intentLanding.putExtra("title", title);
                                                        intentLanding.putExtra("createdBy", createdBy);
                                                        intentLanding.putExtra("articleDesc", articleDesc);
                                                        intentLanding.putExtra("articleVideoUrl", articleVideoUrl);
                                                        intentLanding.putExtra("coverPhotoUrl", coverPhotoUrl);
                                                        intentLanding.putExtra("articleType", articleType);
                                                        intentLanding.putExtra("article_id", artArticle_id);

                                                    } else if (messageType.equals("closemagazine")) {

                                                        intentLanding.putExtra("allowShare", allowShare);
                                                        intentLanding.putExtra("fullSource", fullSource);
                                                        intentLanding.putExtra("allowComment", allowComment);
                                                        intentLanding.putExtra("embedded_video", embedded_video);
                                                        intentLanding.putExtra("title", title);
                                                        intentLanding.putExtra("createdBy", createdBy);
                                                        intentLanding.putExtra("articleDesc", articleDesc);
                                                        intentLanding.putExtra("articleVideoUrl", articleVideoUrl);
                                                        intentLanding.putExtra("coverPhotoUrl", coverPhotoUrl);
                                                        intentLanding.putExtra("articleType", articleType);
                                                        intentLanding.putExtra("article_id", artArticle_id);

                                                        if (close_subtype != null) {
                                                            intentLanding.putExtra("close_subtype", close_subtype);
                                                        }

                                                    }

                                                }

                                                startActivity(intentLanding);

                                            } else if (getString(R.string.device_type).equalsIgnoreCase("ph")) {

                                                Intent intentHome = new Intent(SplashActivity.this, HomeActivity.class);
                                                intentHome.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
                                                intentHome.putExtra("message", receivedMessage);
                                                intentHome.putExtra("article_id", article_id);
                                                intentHome.putExtra("url", notiFurl);
                                                intentHome.putExtra("messagetype", messageType);

                                                if (messageType != null) {

                                                    if (messageType.equals("article")) {

                                                        intentHome.putExtra("allowShare", allowShare);
                                                        intentHome.putExtra("fullSource", fullSource);
                                                        intentHome.putExtra("allowComment", allowComment);
                                                        intentHome.putExtra("embedded_video", embedded_video);
                                                        intentHome.putExtra("title", title);
                                                        intentHome.putExtra("createdBy", createdBy);
                                                        intentHome.putExtra("articleDesc", articleDesc);
                                                        intentHome.putExtra("articleVideoUrl", articleVideoUrl);
                                                        intentHome.putExtra("coverPhotoUrl", coverPhotoUrl);
                                                        intentHome.putExtra("articleType", articleType);
                                                        intentHome.putExtra("article_id", artArticle_id);

                                                    }

                                                }

                                                startActivity(intentHome);

                                            } else {

                                                Intent intentTablet = new Intent(SplashActivity.this, TabletHomeActivity.class);
                                                intentTablet.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
                                                intentTablet.putExtra("message", receivedMessage);
                                                intentTablet.putExtra("article_id", article_id);
                                                intentTablet.putExtra("url", notiFurl);
                                                intentTablet.putExtra("messagetype", messageType);

                                                if (messageType != null) {

                                                    if (messageType.equals("article")) {

                                                        intentTablet.putExtra("allowShare", allowShare);
                                                        intentTablet.putExtra("fullSource", fullSource);
                                                        intentTablet.putExtra("allowComment", allowComment);
                                                        intentTablet.putExtra("embedded_video", embedded_video);
                                                        intentTablet.putExtra("title", title);
                                                        intentTablet.putExtra("createdBy", createdBy);
                                                        intentTablet.putExtra("articleDesc", articleDesc);
                                                        intentTablet.putExtra("articleVideoUrl", articleVideoUrl);
                                                        intentTablet.putExtra("coverPhotoUrl", coverPhotoUrl);
                                                        intentTablet.putExtra("articleType", articleType);
                                                        intentTablet.putExtra("article_id", artArticle_id);

                                                    }

                                                }

                                                startActivity(intentTablet);

                                            }

                                            finish();

                                        } else {

                                            Utility.clearUserData(SplashActivity.this);
//                                            Utility.showToastMessage(SplashActivity.this, JsonObject.optString("message"));

                                            Intent intentLogin = new Intent(SplashActivity.this, LoginActivity.class);
                                            intentLogin.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
                                            intentLogin.putExtra("message", receivedMessage);
                                            intentLogin.putExtra("article_id", article_id);
                                            intentLogin.putExtra("url", notiFurl);
                                            intentLogin.putExtra("messagetype", messageType);
                                            startActivity(intentLogin);

                                            finish();

                                        }

                                    } catch (JSONException e) {

                                        Intent intentJsonLogin = new Intent(SplashActivity.this, LoginActivity.class);
                                        intentJsonLogin.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
                                        intentJsonLogin.putExtra("message", receivedMessage);
                                        intentJsonLogin.putExtra("article_id", article_id);
                                        intentJsonLogin.putExtra("url", notiFurl);
                                        intentJsonLogin.putExtra("messagetype", messageType);
                                        startActivity(intentJsonLogin);
                                        finish();

                                    }

                                }

                            }

                        }, 2000);

                    }

                }).start();
            }

        } else {
            ((RelativeLayout) findViewById(R.id.splash_screen_lyt)).setBackgroundResource(R.drawable.no_network_screen);
        }

    }

    private void receiveNotification() {

        Intent in = getIntent();
        Log.d("Splash LOG", "Splash LOG");

        //  Bundle aBundle = in.getExtras();

        if (in != null) {
            receivedMessage = in.getStringExtra("contents");
            article_id = in.getStringExtra("article_id");
            notiFurl = in.getStringExtra("url");
            messageType = in.getStringExtra("messagetype");
            if (messageType != null) {
                if (messageType.equals("article")) {
                    allowShare = in.getStringExtra("allowShare");
                    fullSource = in.getStringExtra("fullSoruce");
                    allowComment = in.getStringExtra("allowComment");
                    embedded_video = in.getStringExtra("embedded_video");
                    title = in.getStringExtra("title");
                    createdBy = in.getStringExtra("createdBy");
                    articleDesc = in.getStringExtra("articleDesc");
                    articleVideoUrl = in.getStringExtra("articleVideoUrl");
                    coverPhotoUrl = in.getStringExtra("coverPhotoUrl");
                    articleType = in.getStringExtra("articleType");
                    artArticle_id = in.getStringExtra("article_id");
                } else if (messageType.equals("closemagazine")) {
                    allowShare = in.getStringExtra("allowShare");
                    fullSource = in.getStringExtra("fullSoruce");
                    allowComment = in.getStringExtra("allowComment");
                    embedded_video = in.getStringExtra("embedded_video");
                    title = in.getStringExtra("title");
                    createdBy = in.getStringExtra("createdBy");
                    articleDesc = in.getStringExtra("articleDesc");
                    articleVideoUrl = in.getStringExtra("articleVideoUrl");
                    coverPhotoUrl = in.getStringExtra("coverPhotoUrl");
                    articleType = in.getStringExtra("articleType");
                    artArticle_id = in.getStringExtra("article_id");
                    close_subtype = in.getStringExtra("close_subtype");
                }
            }
            if (receivedMessage != null) {
                Log.d("receivedMessage", receivedMessage);
            }
            if (article_id != null) {
                Log.d("article_id", article_id);
            }
            if (notiFurl != null) {
                Log.d("notiFurl", notiFurl);
            }
        }

    }

    @Override
    protected void onResume() {
        super.onResume();
        //  receiveNotification();
        Log.d("Splash Resume", "Splash Resume");
    }

    @Override
    protected void onPause() {
        super.onPause();
        //   receiveNotification();
        Log.d("Splash Pause", "Splash Pause");
    }


    @Override
    public void onConfigurationChanged(Configuration newConfig) {
        super.onConfigurationChanged(newConfig);

        // Checks the orientation of the screen
        // if (newConfig.orientation == Configuration.ORIENTATION_LANDSCAPE)
        // {
        // Toast.makeText(this, "landscape", Toast.LENGTH_SHORT).show();
        // setContentView(R.layout.splash_screen);
        // }
        // else
        // if (newConfig.orientation == Configuration.ORIENTATION_PORTRAIT)
        // {
        // Toast.makeText(this, "portrait", Toast.LENGTH_SHORT).show();
        // setContentView(R.layout.splash_screen);
        // }
        setContentView(R.layout.splash_screen);
        String appLanguage = Utility.getSharedPrefStringData(this, Constants.APP_LANGUAGE);
        if (appLanguage.length() > 0)
            Utility.updateAppDefaultLanguage(this, appLanguage);
        if (!Utility.isNetworkAvailable(this))
            ((RelativeLayout) findViewById(R.id.splash_screen_lyt)).setBackgroundResource(R.drawable.no_network_screen);
        Log.e("mslz", Utility.getDrfaultLanguage());
    }

}
