package com.ta.wootrix.phone;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Intent;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.widget.Toast;

import com.ta.wootrix.R;
import com.ta.wootrix.asynctask.IAsyncCaller;
import com.ta.wootrix.asynctask.ServerIntractorAsync;
import com.ta.wootrix.asynctask.UserHasMagazineAsyncTask;
import com.ta.wootrix.modle.ArticleModle;
import com.ta.wootrix.modle.CustomAdvModel;
import com.ta.wootrix.modle.Error;
import com.ta.wootrix.modle.IModel;
import com.ta.wootrix.modle.MagazineModle;
import com.ta.wootrix.parser.ArticleParser;
import com.ta.wootrix.parser.CustomAdvParser;
import com.ta.wootrix.parser.MagazineParser;
import com.ta.wootrix.parser.MessageParser;
import com.ta.wootrix.restapi.APIUtils;
import com.ta.wootrix.tablet.TabletAdvertViewActivity;
import com.ta.wootrix.tablet.TabletHomeActivity;
import com.ta.wootrix.utils.AccessRegisterListener;
import com.ta.wootrix.utils.Constants;
import com.ta.wootrix.utils.GPSTracker;
import com.ta.wootrix.utils.UserHasMagazineDelegate;
import com.ta.wootrix.utils.Utility;

import org.json.JSONException;
import org.json.JSONObject;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;

import io.branch.referral.Branch;
import io.branch.referral.BranchError;

public class DeepLinkActivity extends AppCompatActivity implements IAsyncCaller, AccessRegisterListener, UserHasMagazineDelegate {

    public static final int REQUEST_DEEP_LOGIN = 1264;

    private String deepMagazineId, deepArticleId, deepMagazineCode, deepAdId, deepShowInputDialog;
    private ProgressDialog deepProgressDialog;
    private ArticleModle deepArticle;
    private CustomAdvModel advModel;
    private GPSTracker mGPSTracker;
    private boolean sameRequest = false;

	@Override
	protected void onCreate(Bundle savedInstanceState) {

		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_deeplink);

        mGPSTracker = new GPSTracker(this);

        deepProgressDialog = new ProgressDialog(this);
        deepProgressDialog.setTitle(getString(R.string.wait));
        deepProgressDialog.setMessage(getString(R.string.processing_data));
        deepProgressDialog.setCancelable(false);

	}

    @Override
    protected void onStart() {

        super.onStart();

        if( !sameRequest ) {

            Branch branch = Branch.getInstance(getApplicationContext());
            branch.initSession(new Branch.BranchReferralInitListener() {
                @Override
                public void onInitFinished(JSONObject referringParams, BranchError error) {

                    if (error == null) {

                        try {

                            if (referringParams.has("$magazine_code")) {
                                deepMagazineCode = referringParams.getString("$magazine_code");
                            }

                            if (referringParams.has("$magazine_id")) {
                                deepMagazineId = referringParams.getString("$magazine_id");
                            }

                            if (referringParams.has("$article_id")) {
                                deepArticleId = referringParams.getString("$article_id");
                            }

                            if (referringParams.has("$ad_id")) {
                                deepAdId = referringParams.getString("$ad_id");
                            }

                            if (referringParams.has("$show_input_code_dlg")) {
                                deepShowInputDialog = referringParams.getString("$show_input_code_dlg");
                            }

                            if( deepMagazineCode == null && deepMagazineId == null && deepArticleId == null && deepAdId == null
                                    && deepShowInputDialog == null ){
                                Intent i = new Intent(DeepLinkActivity.this, SplashActivity.class);
                                startActivity(i);
                                finish();
                                return;
                            }

                            if (!Utility.getSharedPrefBooleanData(DeepLinkActivity.this, Constants.PREF_KEY_LOGIN_STATUS)) {

                                Intent intentLogin = new Intent(DeepLinkActivity.this, LoginActivity.class);
                                intentLogin.putExtra("deepMagazineCode", deepMagazineCode);
                                intentLogin.putExtra("deepMagazineId", deepMagazineId);
                                intentLogin.putExtra("deepArticleId", deepArticleId);
                                intentLogin.putExtra("deepAdId", deepAdId);
                                intentLogin.putExtra("deepShowInputDialog", deepShowInputDialog);
                                startActivityForResult(intentLogin, REQUEST_DEEP_LOGIN);

                                sameRequest = true;

                            } else {

                                if (deepMagazineCode != null) {
                                    callSubscribeMagazine();
                                } else if ( deepAdId != null ){
                                    getAdAsync();
                                } else if( deepShowInputDialog != null ){
                                    goToMagazineDialog();
                                } else {
                                    getArticleAsync();
                                }

                            }

                        } catch (JSONException e) {
                            e.printStackTrace();
                        }

                    } else {
                        Intent i = new Intent(DeepLinkActivity.this, SplashActivity.class);
                        startActivity(i);
                        finish();
                        Log.i("MyApp", error.getMessage());
                    }

                }
            }, this.getIntent().getData(), this);

        }

    }

    @Override
    protected void onNewIntent(Intent intent) {
        this.setIntent(intent);
    }

    private void callSubscribeMagazine() {

        if (Utility.isNetworkAvailable(this)) {

            new ServerIntractorAsync(this, getString(R.string.please_wait_lbl), true, APIUtils.getBaseURL(APIUtils.SUBSCRIBE_MAGAZINE), getSubscriptionParams(deepMagazineCode), APIUtils.REQUEST_TYPE.POST, this,
                    new MagazineParser()).execute();

        } else {
            Utility.showNetworkNotAvailToast(this);
        }

    }

    private JSONObject getSubscriptionParams(String in) {

        try {
            JSONObject json = new JSONObject();
            json.put("token", Utility.getSharedPrefStringData(this, Constants.USER_TOKEN));
            json.put("subscriptionPassword", in);
            json.put("appLanguage", Utility.getDrfaultLanguage());

            return json;
        } catch (JSONException e) {
            e.printStackTrace();
        }

        return null;

    }

    private void getArticleAsync() {

        if (Utility.isNetworkAvailable(this)) {

            IAsyncCaller articleDetailAsyncCaller = new IAsyncCaller() {

                @Override
                public void onComplete(ArrayList<IModel> object, String message, boolean status) {

                }

                @Override
                public void onComplete(IModel object, String message, boolean status) {

                    deepArticle = (ArticleModle) object;

                    if( deepArticle.getArticleID() != null ){

                        goToDeepArticleDetail();
                        deepProgressDialog.dismiss();

                        return;

                    }

                    deepMagazineCode = "";
                    deepMagazineId = "";
                    deepArticleId = "";
                    deepAdId = "";
                    deepShowInputDialog = "";

                    finish();

                }

            };

            new ServerIntractorAsync(this, getString(R.string.please_wait_lbl), true,
                    APIUtils.getBaseURL(APIUtils.GET_ARTICLE_DETAIL), getArticleDetailParams(), APIUtils.REQUEST_TYPE.POST, articleDetailAsyncCaller,
                    new ArticleParser(deepMagazineId != null ? true : false, 0)).execute();

        } else {
            Utility.showNetworkNotAvailToast(this);
            deepProgressDialog.dismiss();
            deepMagazineCode = "";
            deepMagazineId = "";
            deepArticleId = "";
            deepAdId = "";
            deepShowInputDialog = "";
            finish();
        }

    }

    private void getAdAsync() {

        if (Utility.isNetworkAvailable(this)) {

            IAsyncCaller adDetailAsyncCaller = new IAsyncCaller() {

                @Override
                public void onComplete(ArrayList<IModel> object, String message, boolean status) {

                    advModel = (CustomAdvModel) object.get(0);

                    if( advModel.getId() != null ){
                        goToDeepAd();
                        deepProgressDialog.dismiss();
                    }

                    deepMagazineCode = "";
                    deepMagazineId = "";
                    deepArticleId = "";
                    deepAdId = "";
                    deepShowInputDialog = "";

                    finish();

                }

                @Override
                public void onComplete(IModel object, String message, boolean status) {



                }

            };

            new ServerIntractorAsync(this, getString(R.string.please_wait_lbl), true,
                    APIUtils.getBaseURL(APIUtils.GET_AD_DETAIL), getAdDetailParams(), APIUtils.REQUEST_TYPE.POST, adDetailAsyncCaller,
                    new CustomAdvParser()).execute();

        } else {
            Utility.showNetworkNotAvailToast(this);
            deepProgressDialog.dismiss();
            deepMagazineCode = "";
            deepMagazineId = "";
            deepArticleId = "";
            deepAdId = "";
            deepShowInputDialog = "";
            finish();
        }

    }

    private void getMagazineData(String magazineId){

        if (Utility.isNetworkAvailable(this)) {

            IAsyncCaller magazineDetailAsyncCaller = new IAsyncCaller() {

                @Override
                public void onComplete(ArrayList<IModel> object, String message, boolean status) {

                    MagazineModle magazine = (MagazineModle) object.get(0);

                    Intent magazineIntent = new Intent(DeepLinkActivity.this, getString(R.string.device_type).equalsIgnoreCase("ph") ? HomeActivity.class : TabletHomeActivity.class);
                    magazineIntent.putExtra("magazineData", magazine);
                    startActivity(magazineIntent);

                    if( deepAdId != null ) {
                        getAdAsync();
                    }
                    else if( deepArticleId != null ){
                        getArticleAsync();
                    } else if( deepShowInputDialog != null ){
                        goToMagazineDialog();
                    } else {
                        deepMagazineCode = "";
                        deepMagazineId = "";
                        deepArticleId = "";
                        deepAdId = "";
                        deepShowInputDialog = "";
                    }

                    finish();

                }

                @Override
                public void onComplete(IModel object, String message, boolean status) {

                }

            };

            new ServerIntractorAsync(this, getString(R.string.please_wait_lbl), true,
                    APIUtils.getBaseURL(APIUtils.GET_MAGAZINE_DATA), getMagazineDetailParams(magazineId), APIUtils.REQUEST_TYPE.POST, magazineDetailAsyncCaller,
                    new MagazineParser()).execute();

        } else {
            Utility.showNetworkNotAvailToast(this);
            deepProgressDialog.dismiss();
            deepMagazineCode = "";
            deepMagazineId = "";
            deepArticleId = "";
            deepAdId = "";
            deepShowInputDialog = "";
            finish();
        }

    }

    private void goToDeepArticleDetail(){

        if( deepMagazineId != null ){

            String userId = Utility.getSharedPrefStringData(DeepLinkActivity.this, Constants.USER_TOKEN);

            UserHasMagazineAsyncTask async = new UserHasMagazineAsyncTask(this, userId, deepMagazineId);
            async.execute();

        } else {
            accessArticleDetail();

            deepMagazineCode = "";
            deepMagazineId = "";
            deepArticleId = "";
            deepAdId = "";
            deepShowInputDialog = "";
        }

    }

    private void goToDeepAd(){

        if (getString(R.string.device_type).equalsIgnoreCase("ph")) {

            Intent myIntent = new Intent(this, AdvertisementViewActivity.class);
            myIntent.putExtra("advtURL", advModel.getLink());
            myIntent.putExtra("type", "link");
            startActivity(myIntent);

        } else {

            Intent myIntent = new Intent(this, TabletAdvertViewActivity.class);
            myIntent.putExtra("advtURL", advModel.getLink());
            myIntent.putExtra("type", "link");
            startActivity(myIntent);

        }

        finish();

    }

    private void goToMagazineDialog(){

        Intent homeIntent = new Intent(this, getString(R.string.device_type).equalsIgnoreCase("ph") ? HomeActivity.class : TabletHomeActivity.class);
        homeIntent.putExtra("showInputDialog", true);

        if( deepArticleId != null && !deepArticleId.isEmpty() ){
            homeIntent.putExtra("deepArticleId", deepArticleId);
        }

        if( deepMagazineId != null && !deepMagazineId.isEmpty() ){
            homeIntent.putExtra("deepMagazineId", deepMagazineId);
        }

        startActivity(homeIntent);
        finish();

    }

    private JSONObject getArticleDetailParams() {

        try {
            JSONObject json = new JSONObject();
            json.put("magazineId", deepMagazineId);
            json.put("articleId", deepArticleId);
            return json;
        } catch (JSONException e) {
            e.printStackTrace();
        }

        return null;

    }

    private JSONObject getAdDetailParams() {

        try {
            JSONObject json = new JSONObject();
            json.put("adId", deepAdId);
            return json;
        } catch (JSONException e) {
            e.printStackTrace();
        }

        return null;

    }

    private JSONObject getMagazineDetailParams(String magazineId) {

        try {
            JSONObject json = new JSONObject();
            json.put("magazineId", magazineId);
            return json;
        } catch (JSONException e) {
            e.printStackTrace();
        }

        return null;

    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {

        super.onActivityResult(requestCode, resultCode, data);

        if (requestCode == REQUEST_DEEP_LOGIN) {

            if( resultCode == Activity.RESULT_OK) {

                if( deepMagazineCode != null ){
                    callSubscribeMagazine();
                } else if( deepAdId != null ) {
                    getAdAsync();
                } else if( deepShowInputDialog != null ){
                    goToMagazineDialog();
                } else {
                    getArticleAsync();
                }

            }

        }

    }

    @Override
    public void onComplete(IModel object, String message, boolean status) {

    }

    @Override
    public void onComplete(ArrayList<IModel> object, String message, boolean status) {

        if( status ){

            if( object.get(0) instanceof Error){
                Error error = (Error) object.get(0);

                if( error.getHasMagazine() ){
                    String magazineId = error.getMagazineId();
                    getMagazineData(magazineId);
                    return;
                } else {
                    Toast.makeText(this, error.getError(), Toast.LENGTH_LONG).show();
                    finish();
                    return;
                }

            }

            MagazineModle magazine = (MagazineModle) object.get(0);

            if( deepArticleId != null ){
                getArticleAsync();
            } else if( deepAdId != null ){
                getAdAsync();
            } else if( deepShowInputDialog != null ){
                goToMagazineDialog();
            } else {

                Intent magazineIntent = new Intent(this, getString(R.string.device_type).equalsIgnoreCase("ph") ? HomeActivity.class : TabletHomeActivity.class);
                magazineIntent.putExtra("magazineData", magazine);
                startActivity(magazineIntent);
                finish();

            }

        }

    }

    @Override
    public void sendAccessData(String magazineId, String articleId, String typeDevice) {

        if (Utility.isNetworkAvailable(this)) {

            IAsyncCaller accessAsyncCaller = new IAsyncCaller() {

                @Override
                public void onComplete(ArrayList<IModel> object, String message, boolean status) {

                }

                @Override
                public void onComplete(IModel object, String message, boolean status) {

                }

            };

            new ServerIntractorAsync(this, getString(R.string.please_wait_lbl), false,
                    APIUtils.getBaseURL(APIUtils.POST_MAGAZINE_ACCESS), getAccessJsonParams(magazineId, articleId, typeDevice), APIUtils.REQUEST_TYPE.POST, accessAsyncCaller,
                    new MessageParser()).execute();

        } else {
            Utility.showNetworkNotAvailToast(this);
        }

    }

    private JSONObject getAccessJsonParams(String magazineId, String articleId, String typeDevice){

        SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
        String currentDateandTime = sdf.format(new Date());

        try {
            JSONObject json = new JSONObject();
            json.put("id_magazine", magazineId);
            json.put("id_article", articleId);
            json.put("date_access", currentDateandTime);
            json.put("token", Utility.getSharedPrefStringData(this, Constants.USER_TOKEN));
            json.put("so_access", "Android");
            json.put("type_device_access", typeDevice);
            json.put("latitude", String.valueOf(mGPSTracker.getLatitude()));
            json.put("longitude", String.valueOf(mGPSTracker.getLongitude()));
            Log.d("mslz", json.toString());
            return json;
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return null;

    }

    @Override
    public void preHasMagazine() {

    }

    @Override
    public void postHasMagazine(Boolean result) {

        if( result ){
            accessArticleDetail();
        } else {
            goToMagazineDialog();

            deepMagazineCode = "";
            deepMagazineId = "";
            deepArticleId = "";
            deepAdId = "";
            deepShowInputDialog = "";
        }

    }

    private void accessArticleDetail(){

        Intent myIntent = new Intent(this, ArticleDetailActivity.class);
        myIntent.putExtra("articleData", deepArticle);
        myIntent.putExtra("isDeepLink", true);
        myIntent.putExtra("launchFrom", true);
        myIntent.putExtra("isMagzine", deepMagazineId != null ? true : false);
        myIntent.putExtra("magzineID", deepMagazineId != null ? deepMagazineId : null);
        startActivity(myIntent);

        sendAccessData(deepMagazineId != null ? deepMagazineId : null, deepArticleId, "Smartphone");

        finish();

    }

}
