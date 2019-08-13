package com.ta.wootrix.phone;

import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.pm.ActivityInfo;
import android.content.res.Configuration;
import android.net.Uri;
import android.os.Bundle;
import android.os.Handler;
import android.support.annotation.NonNull;
import android.support.annotation.VisibleForTesting;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.DisplayMetrics;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.ImageView;
import android.widget.Toast;

import com.google.android.gms.common.ConnectionResult;
import com.google.android.gms.common.api.GoogleApiClient;
import com.nostra13.universalimageloader.core.ImageLoader;
import com.ta.wootrix.R;
import com.ta.wootrix.adapter.MagazineAdapter;
import com.ta.wootrix.asynctask.IAsyncCaller;
import com.ta.wootrix.asynctask.ServerIntractorAsync;
import com.ta.wootrix.firebase.StorePreference;
import com.ta.wootrix.modle.ArticleModle;
import com.ta.wootrix.modle.Error;
import com.ta.wootrix.modle.IModel;
import com.ta.wootrix.modle.LandingPageModle;
import com.ta.wootrix.modle.MagazineModle;
import com.ta.wootrix.parser.LandingPageParser;
import com.ta.wootrix.restapi.APIUtils;
import com.ta.wootrix.restapi.APIUtils.REQUEST_TYPE;
import com.ta.wootrix.tablet.TabletHomeActivity;
import com.ta.wootrix.utils.Constants;
import com.ta.wootrix.utils.GridSpacingItemDecoration;
import com.ta.wootrix.utils.ItemClickDelegate;
import com.ta.wootrix.utils.Utility;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

public class LandingPageActivity extends AppCompatActivity implements OnClickListener, IAsyncCaller, GoogleApiClient.OnConnectionFailedListener,
        ItemClickDelegate {
    Utility utility;
    String receivedMessage = null, article_id, notiFurl, messageType;
    StorePreference myStorePreference;
    GoogleApiClient mGoogleApiClient;
    String allowShare = null, fullSource = null, allowComment = null, embedded_video = null, title = null,
            createdBy = null, articleDesc = null, articleVideoUrl = null, coverPhotoUrl = null, articleType = null, artArticle_id = null, close_subtype = null;
    private ImageView mImgVwProfileIcon;
//    private ImageView mImgVwOpenArticleImageView;
//    private TextView mTxtVwArticleName;
//    private TextView mTxtVwArticleTitle;
//    private TextView mTxtVwArticleDate;
//    private TextView mTxtVwArticleWebURL;
//    private TextView mTxtVwArticleDesc;
//    private LinearLayout mLnrLytArticles;
    private LayoutInflater mLytInflater;
    private ImageLoader mImageLoader;
    private ArticleModle mOpenArticleData;
    private ArrayList<MagazineModle> mMagazineList;
    private int backPressed;
    private Handler handler = new Handler();
    private RecyclerView recyclerView;
    private MagazineAdapter adapter;
//    private ImageView mImgVwVideoOverlayImageView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {

        super.onCreate(savedInstanceState);

        if (getString(R.string.device_type).equalsIgnoreCase("ph")) {
            setRequestedOrientation(ActivityInfo.SCREEN_ORIENTATION_PORTRAIT);
        }

        setContentView(R.layout.landing_screen);

        initViews();

        receivedIntent();

        if (messageType != null) {
            utility.toastMessage(this, messageType);
        }

        validateAppCode();

        getLandingPageDataAsync();


    }


    @VisibleForTesting
    public Uri buildDeepLink(@NonNull Uri deepLink, int minVersion, boolean isAd) {
        // Get the unique appcode for this app.
        String appCode = getString(R.string.app_code);

        // Get this app's package name.
        String packageName = getApplicationContext().getPackageName();

        // Build the link with all required parameters
        Uri.Builder builder = new Uri.Builder()
                .scheme("https")
                .authority(appCode + ".app.goo.gl")
                .path("/")
                .appendQueryParameter("link", deepLink.toString())
                .appendQueryParameter("apn", packageName);

        // If the deep link is used in an advertisement, this value must be set to 1.
        if (isAd) {
            builder.appendQueryParameter("ad", "1");
        }

        // Minimum version is optional.
        if (minVersion > 0) {
            builder.appendQueryParameter("amv", Integer.toString(minVersion));
        }

        // Return the completed deep link.
        return builder.build();
    }

    private void validateAppCode() {
        String appCode = getString(R.string.app_code);
        if (appCode.contains("YOUR_APP_CODE")) {
            new android.support.v7.app.AlertDialog.Builder(this)
                    .setTitle("Invalid Configuration")
                    .setMessage("Please set your app code in res/values/strings.xml")
                    .setPositiveButton(android.R.string.ok, null)
                    .create().show();
        }
    }

    private void receivedIntent() {

        Intent intent = getIntent();

        Log.d("Landing LOG", "Landing LOG");

        if (intent != null) {
            receivedMessage = intent.getStringExtra("message");
            notiFurl = intent.getStringExtra("url");
            messageType = intent.getStringExtra("messagetype");
            article_id = intent.getStringExtra("article_id");
            if (messageType != null) {
                if (messageType.equals("message")) {
                    if (receivedMessage != null && receivedMessage != "") {
                        openAlertDialog(receivedMessage);
                        Log.d("Login Intent", receivedMessage);
                    }
                } else if (messageType.equals("advertisement")) {
                    if (notiFurl != null && notiFurl != "") {
                        loadAdvertisementPage(notiFurl);
                    }
                } else if (messageType.equals("article")) {
                    allowShare = intent.getStringExtra("allowShare");
                    fullSource = intent.getStringExtra("fullSource");
                    allowComment = intent.getStringExtra("allowComment");
                    embedded_video = intent.getStringExtra("embedded_video");
                    title = intent.getStringExtra("title");
                    createdBy = intent.getStringExtra("createdBy");
                    articleDesc = intent.getStringExtra("articleDesc");
                    articleVideoUrl = intent.getStringExtra("articleVideoUrl");
                    coverPhotoUrl = intent.getStringExtra("coverPhotoUrl");
                    articleType = intent.getStringExtra("articleType");
                    artArticle_id = intent.getStringExtra("article_id");

                    loadArticlePage(notiFurl, allowShare, fullSource, allowComment, embedded_video, title, createdBy, articleDesc, articleVideoUrl, coverPhotoUrl, articleType, artArticle_id);

                } else if (messageType.equals("closemagazine")) {

                    article_id = intent.getStringExtra("article_id");
                    allowShare = intent.getStringExtra("allowShare");
                    fullSource = intent.getStringExtra("fullSource");
                    allowComment = intent.getStringExtra("allowComment");
                    embedded_video = intent.getStringExtra("embedded_video");
                    title = intent.getStringExtra("title");
                    createdBy = intent.getStringExtra("createdBy");
                    articleDesc = intent.getStringExtra("articleDesc");
                    articleVideoUrl = intent.getStringExtra("articleVideoUrl");
                    coverPhotoUrl = intent.getStringExtra("coverPhotoUrl");
                    articleType = intent.getStringExtra("articleType");
                    artArticle_id = intent.getStringExtra("article_id");
                    close_subtype = intent.getStringExtra("close_subtype");
                }
            }

        }
    }

    @Override
    protected void onResume() {
        super.onResume();
        //  receivedIntent();
        Log.d("Landing Resume", "landing Resume");
    }

    @Override
    protected void onPause() {
        super.onPause();
        //receivedIntent();
        Log.d("Landing Pause", "landing pause");
    }


    private void loadArticlePage(String notiFurl, String allowShare, String fullSource, String allowComment, String embedded_video, String title, String createdBy, String articleDesc, String articleVideoUrl, String coverPhotoUrl, String articleType, String artArticle_id) {
        Intent homeIntent = new Intent(this, getString(R.string.device_type).equalsIgnoreCase("ph") ? HomeActivity.class : TabletHomeActivity.class);
        homeIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
        homeIntent.putExtra("url", notiFurl);
        homeIntent.putExtra("messagetype", messageType);
        if (messageType != null) {
            homeIntent.putExtra("allowShare", allowShare);
            homeIntent.putExtra("fullSource", fullSource);
            homeIntent.putExtra("allowComment", allowComment);
            homeIntent.putExtra("embedded_video", embedded_video);
            homeIntent.putExtra("title", title);
            homeIntent.putExtra("createdBy", createdBy);
            homeIntent.putExtra("articleDesc", articleDesc);
            homeIntent.putExtra("articleVideoUrl", articleVideoUrl);
            homeIntent.putExtra("coverPhotoUrl", coverPhotoUrl);
            homeIntent.putExtra("articleType", articleType);
            homeIntent.putExtra("article_id", artArticle_id);
        }
        startActivity(homeIntent);
        finish();
    }

    private void loadAdvertisementPage(String notiFurl) {

        Intent homeIntent = new Intent(this, getString(R.string.device_type).equalsIgnoreCase("ph") ? HomeActivity.class : TabletHomeActivity.class);
        homeIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
        homeIntent.putExtra("url", notiFurl);
        homeIntent.putExtra("messagetype", messageType);
        homeIntent.putExtra("message", receivedMessage);
        startActivity(homeIntent);
        finish();

    }


    public void openAlertDialog(String receivedMessage) {

        new AlertDialog.Builder(this, R.style.custom_dialog_theme).setMessage(receivedMessage).setIcon(R.drawable.app_icon).setCancelable(false).setTitle(this.getString(R.string.app_name))
                .setPositiveButton(this.getResources().getString(android.R.string.ok), new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int whichButton) {
                        dialog.dismiss();
                    }
                }).create().show();
    }

    private void getLandingPageDataAsync() {
        if (Utility.isNetworkAvailable(this)) {
            new ServerIntractorAsync(this, getString(R.string.please_wait_lbl), true, APIUtils.getBaseURL(APIUtils.GET_LANDING_PAGE_DATA), getJsonParams(), REQUEST_TYPE.POST, this,
                    new LandingPageParser()).execute();
        } else {
            Utility.showNetworkNotAvailToast(this);
        }
    }

    private JSONObject getJsonParams() {
        try {
            JSONObject json = new JSONObject();
            json.put("token", Utility.getSharedPrefStringData(this, Constants.USER_TOKEN));
            json.put("appLanguage", Utility.getDrfaultLanguage());
            json.put("articleLanguage", Utility.getSharedPrefStringData(this, Constants.ARTICLE_LANGUAGE));

            return json;
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return null;
    }

    @Override
    public void onConfigurationChanged(Configuration newConfig) {
        super.onConfigurationChanged(newConfig);

        // Checks the orientation of the screen
        if (newConfig.orientation == Configuration.ORIENTATION_LANDSCAPE || newConfig.orientation == Configuration.ORIENTATION_PORTRAIT) {
            setContentView(R.layout.landing_screen);
            initViews();
            updateMagaziesAndArticleView();
        }
    }

    private void updateMagaziesAndArticleView() {
        if (mImageLoader == null)
//            mImageLoader = Utility.getImageLoader(this);
//        if (mOpenArticleData != null) {
//            mTxtVwArticleTitle.setText(mOpenArticleData.getTitle());
//            if (mOpenArticleData.getSource() != null && !mOpenArticleData.getSource().equalsIgnoreCase(""))
//                mTxtVwArticleWebURL.setText(mOpenArticleData.getSource());
//            else
//                mTxtVwArticleWebURL.setVisibility(View.GONE);
//
//            mTxtVwArticleDate.setText(Utility.getSystemDataTimeFromUTC(this, mOpenArticleData.getCreateDate()));
//
//            mTxtVwArticleDesc.setText(Html.fromHtml(mOpenArticleData.getArticleDescPlain()));
//
//            ColorUtils.setDescHeights(mTxtVwArticleDesc);
//
//            mImageLoader.displayImage(Utility.getSharedPrefStringData(this, Constants.USER_IMAGE), mImgVwProfileIcon, Utility.getProfilePicDisplayOption());
//
////            if (mOpenArticleData.getEmbeddedThumb() != null && !mOpenArticleData.getEmbeddedThumb().equalsIgnoreCase("")) {
////                mImageLoader.displayImage(mOpenArticleData.getEmbeddedThumb(), mImgVwOpenArticleImageView, Utility.getBigArticleDisplayOption());
////            } else {
////                mImageLoader.displayImage(mOpenArticleData.getCoverPhotoUrl(), mImgVwOpenArticleImageView, Utility.getBigArticleDisplayOption());
////            }
//            if (mOpenArticleData.getArticleType().equalsIgnoreCase("video"))
//                mImgVwVideoOverlayImageView.setVisibility(View.VISIBLE);
//        }
        if (mMagazineList != null && mMagazineList.size() > 0) {

            adapter = new MagazineAdapter(this, this, mMagazineList);
            recyclerView.setAdapter(adapter);

//            for (int i = 0; i < mMagazineList.size(); i++) {
//
//                View v = mLytInflater.inflate(R.layout.magazine_item, null);
//                ImageView image = (ImageView) v.findViewById(R.id.magazine_item_imageVw);
//                ((TextView) v.findViewById(R.id.magazine_item_addnew_txtVw)).setVisibility(View.GONE);
//                image.setTag(i);
//                mImageLoader.displayImage(mMagazineList.get(i).getCoverPhotoUrl(), image, Utility.getMagazinesDisplayOption());
//                image.setOnClickListener(new OnClickListener() {
//                    @Override
//                    public void onClick(View v) {
//                        int pos = (Integer) v.getTag();
//                        Intent magazineIntent = new Intent(LandingPageActivity.this, getString(R.string.device_type).equalsIgnoreCase("ph") ? HomeActivity.class : TabletHomeActivity.class);
//                        magazineIntent.putExtra("magazineData", (MagazineModle) mMagazineList.get(pos));
//                        startActivity(magazineIntent);
//                        finish();
//                    }
//                });
//
//                mLnrLytArticles.addView(v);
//
//            }

            if (article_id != null && messageType.equals("closemagazine")) {
                boolean isMagazineAvail = false;

                for (int j = 0; j < mMagazineList.size(); j++) {
                    if (mMagazineList.get(j).getMagazineId().equals(article_id)) {
                        isMagazineAvail = true;
                        Intent magazineIntent = new Intent(LandingPageActivity.this, getString(R.string.device_type).equalsIgnoreCase("ph") ? HomeActivity.class : TabletHomeActivity.class);
                        magazineIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
                        magazineIntent.putExtra("magazineData", (MagazineModle) mMagazineList.get(j));
                        if (close_subtype.equals("close_advertisement")) {
                            Log.d("Close Advertisement", "close_adv");
                            magazineIntent.putExtra("fullSource", fullSource);
                            magazineIntent.putExtra("messagetype", messageType);
                            magazineIntent.putExtra("article_id", artArticle_id);
                            magazineIntent.putExtra("close_subtype", close_subtype);
                        } else if (close_subtype.equals("close_article")) {
                            magazineIntent.putExtra("allowShare", allowShare);
                            magazineIntent.putExtra("fullSource", fullSource);
                            magazineIntent.putExtra("allowComment", allowComment);
                            magazineIntent.putExtra("close_subtype", close_subtype);
                            magazineIntent.putExtra("embedded_video", embedded_video);
                            magazineIntent.putExtra("title", title);
                            magazineIntent.putExtra("articleDesc", articleDesc);
                            magazineIntent.putExtra("articleVideoUrl", articleVideoUrl);
                            magazineIntent.putExtra("coverPhotoUrl", coverPhotoUrl);
                            magazineIntent.putExtra("articleType", articleType);
                            magazineIntent.putExtra("article_id", artArticle_id);
                            magazineIntent.putExtra("artArticle_id", artArticle_id);
                            magazineIntent.putExtra("close_subtype", close_subtype);
                            magazineIntent.putExtra("messagetype", messageType);
                        }
                        Log.d("ARTICLEIDDDDDD", article_id);
                        startActivity(magazineIntent);
                        finish();
                    }
                }

                if (!isMagazineAvail) {
                    String magazineAvail = "You are not subscribe this magazine !";
                    openAlertMagazine(magazineAvail);
                }
            }

        }
    }

    private void openAlertMagazine(String magazineAvail) {

        new AlertDialog.Builder(this, R.style.custom_dialog_theme).setMessage(magazineAvail).setIcon(R.drawable.app_icon).setCancelable(false).setTitle(this.getString(R.string.app_name))
                .setPositiveButton(this.getResources().getString(android.R.string.ok), new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int whichButton) {
                        dialog.dismiss();
                    }
                }).create().show();
    }

    private void initViews() {

        recyclerView = (RecyclerView) findViewById(R.id.recyclerView);

        recyclerView.setNestedScrollingEnabled(false);
        recyclerView.setHasFixedSize(true);
        recyclerView.addItemDecoration(new GridSpacingItemDecoration(this));
        RecyclerView.LayoutManager mLayoutManager = new GridLayoutManager(this, calculateNoOfColumns());
//        RecyclerView.LayoutManager mLayoutManager = new LinearLayoutManager(this);
        recyclerView.setLayoutManager(mLayoutManager);

        mLytInflater = getLayoutInflater();
        mImgVwProfileIcon = (ImageView) findViewById(R.id.landing_screen_profile_image_imgVw);
//        mImgVwOpenArticleImageView = (ImageView) findViewById(R.id.landing_screen_openarticle_image_imgVw);
//        mImgVwVideoOverlayImageView = (ImageView) findViewById(R.id.landing_screen_video_overlay_imgVw);
//        mTxtVwArticleName = (TextView) findViewById(R.id.landing_screen_openarticle_name_txtVw);
//        mTxtVwArticleTitle = (TextView) findViewById(R.id.landing_screen_article_title_txtVw);
//        mTxtVwArticleDate = (TextView) findViewById(R.id.landing_screen_article_time_txtVw);
//        mTxtVwArticleWebURL = (TextView) findViewById(R.id.landing_screen_article_url_txtVw);
//        mTxtVwArticleDesc = (TextView) findViewById(R.id.landing_screen_article_description_txtVw);

//        mLnrLytArticles = (LinearLayout) findViewById(R.id.landing_screen_horizontal_lnrlyt);

        utility = new Utility();

//        ((LinearLayout) findViewById(R.id.landing_screen_open_article_lnrlyt)).setOnClickListener(this);
        mImgVwProfileIcon.setOnClickListener(this);
//        mImgVwOpenArticleImageView.setOnClickListener(this);
//        mTxtVwArticleName.setOnClickListener(this);
//        mTxtVwArticleTitle.setOnClickListener(this);
//        mTxtVwArticleDate.setOnClickListener(this);
//        mTxtVwArticleWebURL.setOnClickListener(this);
//        mTxtVwArticleDesc.setOnClickListener(this);
        myStorePreference = new StorePreference(this);
//        mImgVwVideoOverlayImageView.setOnClickListener(this);
        // if (!getString(R.string.device_type).equalsIgnoreCase("ph"))
        // {
        mImgVwProfileIcon.setVisibility(View.GONE);
        // }
    }

    public int calculateNoOfColumns() {

        DisplayMetrics displayMetrics = getResources().getDisplayMetrics();
        float dpWidth = displayMetrics.widthPixels / displayMetrics.density;
        int noOfColumns = (int) (dpWidth / 180);

        if( noOfColumns <= 1 ){
            noOfColumns = 2;
        }

        return noOfColumns;

    }

    @Override
    public void onClick(View v) {
        switch (v.getId()) {
            case R.id.landing_screen_profile_image_imgVw:
                startActivity(new Intent(LandingPageActivity.this, SettingActivity.class));
                break;
            default:
                break;
        }
    }

    @Override
    public void onComplete(IModel object, String message, boolean status) {
        if (object instanceof Error) {
            Utility.showToastMessage(this, ((Error) object).getError());
        } else if (object instanceof LandingPageModle) {
            mOpenArticleData = ((LandingPageModle) object).getOpenArticle();
            mMagazineList = ((LandingPageModle) object).getMagazineList();
            updateMagaziesAndArticleView();
        }
    }

    @Override
    public void onComplete(ArrayList<IModel> object, String message, boolean status) {
    }

    @Override
    public void onBackPressed() {
        callBackPressed();
    }

    private void callBackPressed() {
        if (backPressed == 1) {
            finish();
        } else {
            Utility.showToastMessage(this, getString(R.string.lbl_press_back_again));
            backPressed = 1;
        }
        handler.postDelayed(new Runnable() {
            @Override
            public void run() {
                backPressed = 0;
            }
        }, 2000);
    }

    @Override
    public void onConnectionFailed(@NonNull ConnectionResult connectionResult) {
        Toast.makeText(this, "Google Play Services Error: " + connectionResult.getErrorCode(),
                Toast.LENGTH_SHORT).show();
    }

    @Override
    public void onItemClick(String id, View v) {

        MagazineModle magazine = adapter.getById(id);

        Intent magazineIntent = new Intent(LandingPageActivity.this, getString(R.string.device_type).equalsIgnoreCase("ph") ? HomeActivity.class : TabletHomeActivity.class);
        magazineIntent.putExtra("magazineData", magazine);
        startActivity(magazineIntent);
        finish();

    }
}