package com.ta.wootrix.phone;

import android.Manifest;
import android.annotation.TargetApi;
import android.app.Activity;
import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.pm.ActivityInfo;
import android.content.pm.PackageManager;
import android.content.res.Configuration;
import android.os.Build;
import android.os.Bundle;
import android.support.v4.app.ActivityCompat;
import android.support.v4.app.FragmentActivity;
import android.support.v4.content.ContextCompat;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.Toast;

import com.google.android.gms.analytics.HitBuilders;
import com.google.android.gms.analytics.Tracker;
import com.google.firebase.analytics.FirebaseAnalytics;
import com.google.firebase.iid.FirebaseInstanceId;
import com.google.firebase.messaging.FirebaseMessaging;
import com.microsoft.identity.client.PublicClientApplication;
import com.ta.wootrix.R;
import com.ta.wootrix.asynctask.IAsyncCaller;
import com.ta.wootrix.asynctask.ServerIntractorAsync;
import com.ta.wootrix.firebase.AnalyticsApplication;
import com.ta.wootrix.firebase.StorePreference;
import com.ta.wootrix.modle.Error;
import com.ta.wootrix.modle.IModel;
import com.ta.wootrix.modle.LandingPageModle;
import com.ta.wootrix.modle.LoginSignupModle;
import com.ta.wootrix.modle.ResponseMsgInfo;
import com.ta.wootrix.modle.TopicsAndLanguage;
import com.ta.wootrix.parser.LandingPageParser;
import com.ta.wootrix.parser.LoginSignupParser;
import com.ta.wootrix.parser.TopicsLaguageParser;
import com.ta.wootrix.restapi.APIUtils;
import com.ta.wootrix.restapi.APIUtils.REQUEST_TYPE;
import com.ta.wootrix.socialntwrkings.OnIntentResult;
import com.ta.wootrix.tablet.TabletHomeActivity;
import com.ta.wootrix.utils.Constants;
import com.ta.wootrix.utils.GPSTracker;
import com.ta.wootrix.utils.Utility;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

/**
 * @author ashok
 */
public class LoginActivity extends FragmentActivity implements OnClickListener, IAsyncCaller {

    public final static String CLIENT_ID = "35532026-3245-4de4-9a43-255a3b2632e9";
    public final static String CLIENT_ID_COLABORADOR = "5904648b-0ceb-46cd-ba1f-5ee6d91bf5be";
    final static String SCOPES[] = {"https://graph.microsoft.com/User.Read"};
    public final static String AUTHORITY_URL = "https://login.microsoftonline.com/c27f427d-7da5-4e40-97dc-8fde809e0c29";
    public final static String AUTHORITY_URL_COLABORADOR = "https://login.microsoftonline.com/65b7f243-3785-438b-a6f5-ebecebfae111";

    public static LoginActivity mLoginActivity;
    public OnIntentResult mUpdateResult;
    protected LandingPageModle mLandingPageData;
    JSONObject json;
    AnalyticsApplication application;
    GPSTracker gpsTracker;
    private Button mBtnLogin, btnLoginColaborador;
    private String mEmail = "";
    private JSONObject mJsonObject;
    StorePreference myStorePreference;

    private String deepMagazineId, deepArticleId, deepMagazineCode, deepAdId, deepShowInputDialog;

    Context context;

    private Tracker mTracker;
    SettingActivity settingActivity;
    FirebaseAnalytics firebaseAnalytics;
    Utility utility;
    private static final int RC_SIGN_IN = 007;

    String refreshedToken = "", latitude = "0.0", longitude = "0.0";
    private static final int PERMISSION_REQUEST_CODE = 1;

    String receivedMessage = null, article_id = null, notiFurl = null, messageType = null;
    String loginMessage = "You need login to view this";

    private PublicClientApplication pca;

    public static LoginActivity getInstance() {
        return mLoginActivity;
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        mLoginActivity = this;
        if (getString(R.string.device_type).equalsIgnoreCase("ph")) {
            setRequestedOrientation(ActivityInfo.SCREEN_ORIENTATION_PORTRAIT);
        }
        setContentView(R.layout.login_screen);

        initViews();

        receivedIntent();

        if (messageType != null) {
            utility.toastMessage(this, messageType);
        }

        int currentAPIVersion = Build.VERSION.SDK_INT;
        if (currentAPIVersion >= android.os.Build.VERSION_CODES.M) {
            checkPermission();
            if (!gpsTracker.canGetLocation()) {
                gpsTracker.showSettingsAlert();
            } else {
                latitude = String.valueOf(gpsTracker.getLatitude());
                longitude = String.valueOf(gpsTracker.getLongitude());
            }
        } else {
            if (!gpsTracker.canGetLocation()) {
                gpsTracker.showSettingsAlert();
            } else {
                latitude = String.valueOf(gpsTracker.getLatitude());
                longitude = String.valueOf(gpsTracker.getLongitude());
            }
        }
        if (gpsTracker.canGetLocation()) {
            latitude = String.valueOf(gpsTracker.getLatitude());
            longitude = String.valueOf(gpsTracker.getLongitude());
        }
    }

    private void receivedIntent() {

        Intent intent = getIntent();

        Log.d("Login LOG", "Login LOG");

        if (intent != null) {

            deepMagazineCode = intent.getStringExtra("deepMagazineCode");
            deepMagazineId = intent.getStringExtra("deepMagazineId");
            deepArticleId = intent.getStringExtra("deepArticleId");
            deepAdId = intent.getStringExtra("deepAdId");
            deepShowInputDialog = intent.getStringExtra("deepShowInputDialog");

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
                    openAlertDialogCat(loginMessage, "Advertisement");
                } else if (messageType.equals("article")) {
                    openAlertDialogCat(loginMessage, "Article");
                } else if (messageType.equals("closemagazine")) {
                    openAlertDialogCat(loginMessage, "Closed Magazine");
                }
            }

        }
    }

    @TargetApi(Build.VERSION_CODES.JELLY_BEAN)
    public boolean checkPermission() {
        int currentAPIVersion = Build.VERSION.SDK_INT;
        if (currentAPIVersion >= android.os.Build.VERSION_CODES.M) {
            if (ContextCompat.checkSelfPermission(LoginActivity.this, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
                if (ActivityCompat.shouldShowRequestPermissionRationale(LoginActivity.this, Manifest.permission.ACCESS_FINE_LOCATION)) {
                    ActivityCompat.requestPermissions(LoginActivity.this, new String[]{Manifest.permission.ACCESS_FINE_LOCATION}, PERMISSION_REQUEST_CODE);
                } else {
                    ActivityCompat.requestPermissions(LoginActivity.this, new String[]{Manifest.permission.ACCESS_FINE_LOCATION}, PERMISSION_REQUEST_CODE);
                }
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    public void openAlertDialog(final String receivedMessage) {

        new AlertDialog.Builder(this, R.style.custom_dialog_theme).setMessage(receivedMessage).setIcon(R.drawable.app_icon).setCancelable(false).setTitle(this.getString(R.string.app_name))
                .setPositiveButton(this.getResources().getString(android.R.string.ok), new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int whichButton) {
                        dialog.dismiss();
                    }
                }).create().show();
    }

    @Override
    protected void onResume() {
        super.onResume();
        //   receivedIntent();
        Log.d("Login Resume", "Login Resume");
    }

    @Override
    protected void onPause() {
        super.onPause();
        //  receivedIntent();
        Log.d("Login Pause", "Login Pause");
    }

    public void openAlertDialogCat(String message, String receivedMessage) {

        new AlertDialog.Builder(this, R.style.custom_dialog_theme).setMessage(message + " " + receivedMessage + ".").setIcon(R.drawable.app_icon).setCancelable(false).setTitle(this.getString(R.string.app_name))
                .setPositiveButton(this.getResources().getString(android.R.string.ok), new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int whichButton) {
                        dialog.dismiss();
                    }
                }).create().show();
    }


    @Override
    public void onRequestPermissionsResult(int requestCode, String permissions[], int[] grantResults) {
        switch (requestCode) {
            case PERMISSION_REQUEST_CODE:
                if (grantResults.length > 0 && grantResults[0] == PackageManager.PERMISSION_GRANTED) {

                    if (!gpsTracker.canGetLocation()) {
                        gpsTracker.showSettingsAlert();
                    } else {
                        latitude = String.valueOf(gpsTracker.getLatitude());
                        longitude = String.valueOf(gpsTracker.getLongitude());
                    }

                } else {

                    Toast.makeText(this, "Permission Denied, You cannot access location data.", Toast.LENGTH_LONG).show();
                }
                break;
        }
    }

    @Override
    public void onConfigurationChanged(Configuration newConfig) {
        super.onConfigurationChanged(newConfig);

        if (newConfig.orientation == Configuration.ORIENTATION_LANDSCAPE) {
            setContentView(R.layout.login_screen);
            initViews();
        } else if (newConfig.orientation == Configuration.ORIENTATION_PORTRAIT) {
            setContentView(R.layout.login_screen);
            initViews();
        }
    }

    private void initViews() {
        context = this;

        mBtnLogin = (Button) findViewById(R.id.btnLoginOrientador);
        btnLoginColaborador = (Button) findViewById(R.id.btnLoginColaborador);

        firebaseAnalytics = FirebaseAnalytics.getInstance(this);
        firebaseAnalytics.setAnalyticsCollectionEnabled(true);
        firebaseAnalytics.setMinimumSessionDuration(30000);

        utility = new Utility();

        myStorePreference = new StorePreference(this);
        mBtnLogin.setOnClickListener(this);
        btnLoginColaborador.setOnClickListener(this);

        gpsTracker = new GPSTracker(this);
        settingActivity = new SettingActivity();
        myStorePreference = new StorePreference(this);
        application = (AnalyticsApplication) getApplication();
        mTracker = application.getDefaultTracker();
        getDeviceToken();
    }

    private void getDeviceToken() {

        FirebaseMessaging.getInstance().subscribeToTopic("deviceID");
        FirebaseInstanceId.getInstance().getToken();

        refreshedToken = FirebaseInstanceId.getInstance().getToken();
        System.out.println("DEVICETOKENID    " + refreshedToken);
    }

    @Override
    public void onClick(View v) {
        switch (v.getId()) {
            case R.id.btnLoginOrientador:
                callLoginAzureAd(false);
                break;

            case R.id.btnLoginColaborador:
                callLoginAzureAd(true);
                break;
            default:
                break;
        }
    }

    private void callLoginAzureAd(boolean colaborador) {

        String clientId = colaborador ? CLIENT_ID_COLABORADOR : CLIENT_ID;
        String authorityUrl = colaborador ? AUTHORITY_URL_COLABORADOR : AUTHORITY_URL;

        LoginSignupModle login = new LoginSignupModle();

        login.setEmail("professor.teste06@unidadekumon.com.br");
        login.setUsername("professor.teste06@unidadekumon.com.br");
        login.setToken("2");
        Utility.saveUserData(this, login, 0);
        Utility.setSharedPrefBooleanData(this, Constants.isMagzineAvailable, true);
        startActivity(new Intent(this, LandingPageActivity.class));

//        pca = new PublicClientApplication(
//                this.getApplicationContext(),
//                clientId,
//                authorityUrl);
//
//        pca.acquireToken(this, SCOPES, new AuthenticationCallback() {
//            @Override
//            public void onSuccess(AuthenticationResult authenticationResult) {
//
//                Log.d("mslz", "Successfully authenticated");
//
//                gpsTracker = new GPSTracker(LoginActivity.this);
//
//                if (refreshedToken == null || refreshedToken.length() == 0 || refreshedToken == "") {
//                    getDeviceToken();
//                }
//
//                latitude = String.valueOf(gpsTracker.getLatitude());
//                longitude = String.valueOf(gpsTracker.getLongitude());
//
//                validateLogin(authenticationResult.getUser().getDisplayableId(), refreshedToken, latitude, longitude);
//
//            }
//
//            @Override
//            public void onError(MsalException exception) {
//
//                Log.d("mslz", "Authentication failed: " + exception.toString());
//
//                if (exception instanceof MsalClientException) {
//                    exception.printStackTrace();
//                } else if (exception instanceof MsalServiceException) {
//                    exception.printStackTrace();
//                }
//
//                Toast.makeText(LoginActivity.this, "Falha na autenticação", Toast.LENGTH_LONG).show();
//
//            }
//
//            @Override
//            public void onCancel() {
//                Log.d("mslz", "User cancelled login.");
//            }
//        });

    }

    /**
     * manual login method -- firstly verifying all the filled details and then call the service
     *
     * @param refreshedToken
     * @param latitude
     * @param longitude
     */
    private void validateLogin(String email, String refreshedToken, String latitude, String longitude) {

        try {
            json = new JSONObject();
            json.put("email", email);
            json.put("device", "Android");
            json.put("osVersion", getAndroidVersion());
            json.put("photoUrl", "");
            json.put("name", "");
            json.put("appLanguage", Utility.getDrfaultLanguage());
            json.put("latitude", latitude);
            json.put("longitude", longitude);
            json.put("deviceId", refreshedToken);
        } catch (JSONException e) {
            e.printStackTrace();
        }

        executeAsync(json);

        Bundle params = new Bundle();
        params.putString(FirebaseAnalytics.Param.ITEM_NAME, email);
        params.putString(FirebaseAnalytics.Param.ACHIEVEMENT_ID, getAndroidVersion());
        params.putString(FirebaseAnalytics.Param.CHARACTER, String.valueOf(Utility.getDrfaultLanguage()));
        params.putString(FirebaseAnalytics.Param.COUPON, android.os.Build.MODEL);
        params.putString(FirebaseAnalytics.Param.CONTENT_TYPE, Build.MANUFACTURER);
        params.putString(FirebaseAnalytics.Param.LOCATION, gpsTracker.getLocationAddress());
        firebaseAnalytics.logEvent("Login", params);

        firebaseAnalytics.setUserProperty("name", email);
        firebaseAnalytics.setUserProperty("email", email);
        firebaseAnalytics.setUserProperty("location", gpsTracker.getLocationAddress());

        mTracker.send(new HitBuilders.EventBuilder().setCategory("Login Email").setAction(email).build());
        mTracker.send(new HitBuilders.EventBuilder().setCategory("OS Version").setAction(getAndroidVersion()).build());
        mTracker.send(new HitBuilders.EventBuilder().setCategory("App Language").setAction(String.valueOf(Utility.getDrfaultLanguage())).build());
        mTracker.send(new HitBuilders.EventBuilder().setCategory("Configuration").setAction(android.os.Build.MODEL).build());
        mTracker.send(new HitBuilders.EventBuilder().setCategory("Brand").setAction(Build.MANUFACTURER).build());
        mTracker.send(new HitBuilders.EventBuilder().setCategory("Location").setAction(gpsTracker.getLocationAddress()).build());

    }

    public String getAndroidVersion() {
        String release = Build.VERSION.RELEASE;
        int sdkVersion = Build.VERSION.SDK_INT;

        Log.e("build version", sdkVersion + " (" + release + ")");
        return "Andriod " + release + Build.VERSION.SDK + " (" + sdkVersion + ")";
    }

    private void executeAsync(JSONObject json) {

        if (Utility.isNetworkAvailable(this)) {
            this.mJsonObject = json;
            new ServerIntractorAsync(this, getString(R.string.please_wait_lbl), true, APIUtils.getBaseURL(APIUtils.LOGIN_URL), json, REQUEST_TYPE.POST, this, new LoginSignupParser()).execute();
        } else {
            Utility.showNetworkNotAvailToast(this);
        }

    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        Log.i("resultCode", String.valueOf(resultCode));
        // code for handelling the result received when loggingin via G+
        if (requestCode == 9000 && resultCode == -1) {
            // g plus disconnect and reconnect
            Log.e("inside", "inside on activity result");
            mUpdateResult.updateUI(requestCode, resultCode, data);

        } else if (mUpdateResult != null) {
            mUpdateResult.updateUI(requestCode, resultCode, data);
        } else {
            pca.handleInteractiveRequestRedirect(requestCode, resultCode, data);
        }

    }

    @Override
    public void onComplete(IModel object, String message, boolean status) {
        if (object instanceof Error) {
            Utility.showToastMessage(this, ((Error) object).getError());
        } else if (object instanceof LoginSignupModle) {
            // do save the data and open the landing screen data;
            Utility.saveUserData(this, ((LoginSignupModle) object), mJsonObject.optString("socialAccountType").length());
            getTopicsDetail();
        } else if (object instanceof ResponseMsgInfo) {
            Utility.showToastMessage(this, ((ResponseMsgInfo) object).getMessage());
        } else if (object instanceof LandingPageModle) {

            mLandingPageData = (LandingPageModle) object;

            if (mLandingPageData.getMagazineList().size() > 0) {

                if (deepArticleId != null || deepMagazineCode != null || deepAdId != null || deepShowInputDialog != null) {
                    setResult(Activity.RESULT_OK);
                } else {
                    Utility.setSharedPrefBooleanData(this, Constants.isMagzineAvailable, true);
                    startActivity(new Intent(this, LandingPageActivity.class));
                }

            } else if (getString(R.string.device_type).equalsIgnoreCase("ph")) {

                if (deepArticleId != null || deepMagazineCode != null || deepAdId != null || deepShowInputDialog != null) {
                    setResult(Activity.RESULT_OK);
                } else {
                    Intent i = new Intent(this, HomeActivity.class);
                    startActivity(i);
                }

            } else {
                startActivity(new Intent(this, TabletHomeActivity.class));
            }

            finish();

        }

    }

    private void getTopicsDetail() {
        if (Utility.isNetworkAvailable(this)) {
            try {
                JSONObject json = new JSONObject();
                json.put("user_id", Utility.getSharedPrefStringData(this, Constants.USER_TOKEN));
                json.put("appLanguage", Utility.getDrfaultLanguage());
                new ServerIntractorAsync(this, getString(R.string.please_wait_lbl), true, APIUtils.getBaseURL(APIUtils.FETCH_TOPICS_AND_ARTICLE_LANGUAGES), json, REQUEST_TYPE.POST,
                        new IAsyncCaller() {
                            @Override
                            public void onComplete(ArrayList<IModel> object, String message, boolean status) {
                            }

                            @Override
                            public void onComplete(IModel object, String message, boolean status) {
                                if (object instanceof Error) {
                                    Utility.showToastMessage(LoginActivity.this, ((Error) object).getError());
                                } else {
                                    Utility.setSharedPrefStringData(LoginActivity.this, Constants.ARTICLE_LANGUAGE, ((TopicsAndLanguage) object).getArticle_language());
                                    Utility.setSharedPrefStringData(LoginActivity.this, Constants.USER_TOPICS, ((TopicsAndLanguage) object).getCategory());
                                    Utility.setSharedPrefStringData(LoginActivity.this, Constants.APP_LANGUAGE, ((TopicsAndLanguage) object).getAppLanguage());
                                    getLandingPageDataAsync();

                                }
                            }
                        }, new TopicsLaguageParser()).execute();
            } catch (JSONException e) {
                e.printStackTrace();
            }
        } else {
            Utility.showNetworkNotAvailToast(this);
        }
    }

    @Override
    public void onComplete(ArrayList<IModel> object, String message, boolean status) {

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

}
