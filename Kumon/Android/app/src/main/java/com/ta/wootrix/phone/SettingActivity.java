package com.ta.wootrix.phone;

import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.v4.app.FragmentActivity;
import android.text.InputType;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.google.android.gms.analytics.HitBuilders;
import com.google.android.gms.analytics.Tracker;
import com.google.android.gms.common.ConnectionResult;
import com.google.android.gms.common.api.GoogleApiClient;
import com.google.firebase.analytics.FirebaseAnalytics;
import com.nostra13.universalimageloader.core.ImageLoader;
import com.ta.wootrix.R;
import com.ta.wootrix.adapter.TopicsAdapter;
import com.ta.wootrix.asynctask.IAsyncCaller;
import com.ta.wootrix.asynctask.ServerIntractorAsync;
import com.ta.wootrix.customDialog.CustomDialog;
import com.ta.wootrix.customDialog.CustomDialog.DIALOG_TYPE;
import com.ta.wootrix.customDialog.IActionOKCancel;
import com.ta.wootrix.firebase.AnalyticsApplication;
import com.ta.wootrix.firebase.StorePreference;
import com.ta.wootrix.modle.Error;
import com.ta.wootrix.modle.IModel;
import com.ta.wootrix.modle.ResponseMsgInfo;
import com.ta.wootrix.modle.TopicsModle;
import com.ta.wootrix.parser.DeleteMagazineParser;
import com.ta.wootrix.parser.MagazineParser;
import com.ta.wootrix.parser.MessageParser;
import com.ta.wootrix.parser.TopicsParser;
import com.ta.wootrix.restapi.APIUtils;
import com.ta.wootrix.restapi.APIUtils.REQUEST_TYPE;
import com.ta.wootrix.tablet.TabletHomeActivity;
import com.ta.wootrix.utils.Constants;
import com.ta.wootrix.utils.Utility;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

public class SettingActivity extends FragmentActivity implements OnClickListener, IAsyncCaller, IActionOKCancel, GoogleApiClient.OnConnectionFailedListener {
    private static final int REQ_CODE_LOGOUT = 202;
    private static SettingActivity instance;
    AnalyticsApplication application;
    String topicIDs, languageCodes;
    private ImageView mImgVwMyAccount;
//    private LinearLayout mLnrLytMagazines;
    private Button mBtnLogout, btn_home;
    private TextView mTxtVwHeaderCancel;
    private TextView mTxtVwHeaderDone;
    private TextView mTxtHeaderTitle;
//    private CheckBox mChkBoxEnglishLang;
//    private CheckBox mChkboxSpanishLang;
//    private CheckBox mChkboxPortuLang;
    private ImageLoader mImageLoader;
    private LayoutInflater mLytInflater;
    private TopicsAdapter mTopicAdapters;
    private ArrayList<IModel> mTopicsList = new ArrayList<IModel>();
    private ArrayList<IModel> mMagazinesList = new ArrayList<IModel>();
    private AlertDialog dialog;
    private boolean isSubscriptionServiceCalled;
    ;
    private boolean fetchingMagazines;
    private boolean fetchingTopics;
    private String mUserID;
    StorePreference myStorePreference;
    private Tracker mTracker;

    private ConnectionResult mConnectionResult;

    String serverName = "";

    private boolean showInputDialog = false;
    private String deepArticleId, deepMagazineId;

    FirebaseAnalytics firebaseAnalytics;
    JSONObject aReceiptWiseObj = null;


    public static SettingActivity getInstance() {
        return instance;
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.setting_screen);
        instance = this;
        mUserID = Utility.getSharedPrefStringData(this, Constants.USER_TOKEN);

        if (getIntent().getExtras() != null && getIntent().getExtras().containsKey("showInputDialog")) {
            showInputDialog = true;

            if( getIntent().getExtras().containsKey("deepArticleId") ){
                deepArticleId = getIntent().getStringExtra("deepArticleId");
            }

            if( getIntent().getExtras().containsKey("deepMagazineId") ){
                deepMagazineId = getIntent().getStringExtra("deepMagazineId");
            }

            showAddNewMagazineDialog();
        }

        initViews();

    }

    private void getTopicsAsync() {
        if (Utility.isNetworkAvailable(this)) {
            fetchingTopics = true;
            new ServerIntractorAsync(this, getString(R.string.please_wait_lbl), true, APIUtils.getBaseURL(APIUtils.GET_TOPICS), getTopicsJsonParams(), REQUEST_TYPE.POST, this, new TopicsParser(this))
                    .execute();
        } else {
            Utility.showNetworkNotAvailToast(this);
        }
    }

    private JSONObject getTopicsJsonParams() {
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

    private void callSubscribeMagazine(String in) {
        if (Utility.isNetworkAvailable(this)) {
            isSubscriptionServiceCalled = true;
            new ServerIntractorAsync(this, getString(R.string.please_wait_lbl), true, APIUtils.getBaseURL(APIUtils.SUBSCRIBE_MAGAZINE), getSubscriptionParams(in), REQUEST_TYPE.POST, this,
                    new MagazineParser()).execute();
        } else {
            Utility.showNetworkNotAvailToast(this);
        }
    }

    private JSONObject getSubscriptionParams(String in) {
        try {
            JSONObject json = new JSONObject();
            json.put("token", mUserID);
            json.put("subscriptionPassword", in);
            json.put("appLanguage", Utility.getDrfaultLanguage());

            return json;
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return null;
    }

    private void updateTopics() {
        if (mTopicAdapters == null) {
            mTopicAdapters = new TopicsAdapter(this, mTopicsList);
        } else
            mTopicAdapters.notifyDataSetChanged();
    }

    private void removeFromList(final int pos, final View v, final String magazineId) {

        AlertDialog.Builder alertDialogBuilder = new AlertDialog.Builder(SettingActivity.this);
        alertDialogBuilder.setTitle(R.string.app_name);
        alertDialogBuilder.setMessage("Do you want to delete this item ?").setCancelable(false).setPositiveButton("Yes", new DialogInterface.OnClickListener() {
            public void onClick(DialogInterface dialog, int id) {
                mMagazinesList.remove(pos);
                String userId = Utility.getSharedPrefStringData(getApplicationContext(), Constants.USER_TOKEN);
                deleteFromServer(userId, magazineId);
                // new DeleteMagazine(userId,magazineId).execute();
                //  updateMagazines();
            }
        }).setNegativeButton("No", new DialogInterface.OnClickListener() {
            public void onClick(DialogInterface dialog, int id) {
                dialog.cancel();
            }
        });

        AlertDialog alertDialog = alertDialogBuilder.create();
        alertDialog.show();
    }

    private void deleteFromServer(String userId, String magazineId) {
        if (Utility.isNetworkAvailable(SettingActivity.this)) {
            new ServerIntractorAsync(SettingActivity.this, getString(R.string.please_wait_lbl), true,
                    APIUtils.getBaseURL(APIUtils.CIPL_DELETEMAGAZINE_URL), getDeleteIds(userId, magazineId), REQUEST_TYPE.POST, this,
                    new DeleteMagazineParser()).execute();
        } else {
            Utility.showNetworkNotAvailToast(SettingActivity.this);
        }
    }

    private JSONObject getDeleteIds(String userId, String magazineId) {

        JSONObject json = new JSONObject();
        try {
            json.put("token", userId);
            json.put("magazine_id", magazineId);
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return json;
    }

    private void initViews() {
        mLytInflater = getLayoutInflater();
        mTxtHeaderTitle = (TextView) findViewById(R.id.header_text_txtVw);
        mTxtHeaderTitle.setText(getString(R.string.header_content));

        ((ImageView) findViewById(R.id.header_back_btn_imgVw)).setVisibility(View.INVISIBLE);
        mTxtVwHeaderCancel = (TextView) findViewById(R.id.header_cancel_btn_txtVw);
        mTxtVwHeaderDone = (TextView) findViewById(R.id.header_done_btn_txtVw);
        mTxtVwHeaderCancel.setVisibility(View.VISIBLE);
        mTxtVwHeaderDone.setVisibility(View.VISIBLE);

        application = (AnalyticsApplication) getApplication();
        mTracker = application.getDefaultTracker();
        myStorePreference = new StorePreference(this);

        firebaseAnalytics = FirebaseAnalytics.getInstance(this);

        mImgVwMyAccount = (ImageView) findViewById(R.id.setting_screen_myaccount_imgVw);

//        mChkBoxEnglishLang = (CheckBox) findViewById(R.id.setting_screen_english_chkbox);
//        mChkboxSpanishLang = (CheckBox) findViewById(R.id.setting_screen_spanish_chkbox);
//        mChkboxPortuLang = (CheckBox) findViewById(R.id.setting_screen_portuguese_chkbox);

        mBtnLogout = (Button) findViewById(R.id.setting_screen_logout_btn);
        btn_home = (Button) findViewById(R.id.btn_home);
//        ((TextView) findViewById(R.id.setting_screen_open_articles)).setOnClickListener(this);
        // ((TextView) findViewById(R.id.setting_screen_add_account)).setOnClickListener(this);

        mBtnLogout.setOnClickListener(this);
        btn_home.setOnClickListener(this);
        mTxtVwHeaderCancel.setOnClickListener(this);
        mTxtVwHeaderDone.setOnClickListener(this);
        mImgVwMyAccount.setOnClickListener(this);
        List<String> selctedLanguage = new ArrayList<String>(Arrays.asList(Utility.getSharedPrefStringData(this, Constants.ARTICLE_LANGUAGE).split(",")));
//        for (String s : selctedLanguage) {
//            if (s.trim().equals("en"))
//                mChkBoxEnglishLang.setChecked(true);
//            if (s.trim().equals("es"))
//                mChkboxSpanishLang.setChecked(true);
//            if (s.trim().equals("pt"))
//                mChkboxPortuLang.setChecked(true);
//        }
    }

    @Override
    public void onClick(View v) {
        switch (v.getId()) {

            case R.id.btn_home:
                if (getString(R.string.device_type).equalsIgnoreCase("ph"))
                    startActivity(new Intent(this, HomeActivity.class));
                else
                    startActivity(new Intent(this, TabletHomeActivity.class));
                finish();
                break;

			/*
             * case R.id.setting_screen_add_account: Intent addIntent = new Intent(this,
			 * AddAccountActivity.class); startActivity(addIntent); break;
			 */

            case R.id.header_cancel_btn_txtVw:
                finish();
                break;
            case R.id.header_done_btn_txtVw:
                try {
                    validateFields();
                } catch (JSONException e) {
                    e.printStackTrace();
                }
                break;
            case R.id.setting_screen_logout_btn:
                // logout from the screen
                CustomDialog dialog = CustomDialog.getInstance(this, this, getString(R.string.wanna_logout), null, DIALOG_TYPE.OK_CANCEL, -1, getString(R.string.lbl_yes), getString(R.string.lbl_no),
                        REQ_CODE_LOGOUT);
                dialog.show();

                break;
            case R.id.setting_screen_myaccount_imgVw:
                startActivity(new Intent(this, MyAccountActivity.class));
                break;
            default:
                break;
        }
    }

    private void validateFields() throws JSONException {
        topicIDs = "";
        languageCodes = "";
        String serverTopics = "", Serverlang = "";
        if (mTopicsList != null && mTopicsList.size() > 0) {
            for (int i = 0; i < mTopicsList.size(); i++) {
                if (((TopicsModle) mTopicsList.get(i)).isTopicSelected()) {
                    if (topicIDs.length() > 0) {
                        topicIDs = topicIDs + "," + ((TopicsModle) mTopicsList.get(i)).getTopicID();
                        serverTopics = serverTopics + "|" + ((TopicsModle) mTopicsList.get(i)).getTopicID();
                        serverName = serverName + ", " + ((TopicsModle) mTopicsList.get(i)).getTopicName();
                    } else {
                        topicIDs = ((TopicsModle) mTopicsList.get(i)).getTopicID();
                        serverTopics = ((TopicsModle) mTopicsList.get(i)).getTopicID();
                        serverName = ((TopicsModle) mTopicsList.get(i)).getTopicName();
                    }
                }
            }
        }
//        if (mChkBoxEnglishLang.isChecked()) {
//            languageCodes = "en";
//            Serverlang = "1";
//        }
//        if (mChkboxPortuLang.isChecked()) {
//            languageCodes = languageCodes + "," + "pt";
//            Serverlang = Serverlang + "," + "2";
//        }
//        if (mChkboxSpanishLang.isChecked()) {
//            languageCodes = languageCodes + "," + "es";
//            Serverlang = Serverlang + "," + "3";
//        }
        if (languageCodes.length() == 0)
            Utility.showToastMessage(this, getString(R.string.please_choose_article_lang));
        else {
            saveTopicsAndLanguage(serverTopics, Serverlang);
        }
    }

    private void saveTopicsAndLanguage(String categories, String articleLanguage) {
        if (Utility.isNetworkAvailable(this)) {
            // "category":"14|17|19","user_id":"1","web_language":"english/spanish/portuguese","article_language":"1/3/2"
            // article language , seprated
            // [11:22:49] Ashok Singhal (TechAhead): article_language me
            // 1(english),2(portuguese),3(spanish)
            // [17:38:15] Mayank Pahuja (TechAhead): category == topics
            try {
                JSONObject json = new JSONObject();
                json.put("user_id", mUserID);
                json.put("category", categories);
                json.put("article_language", articleLanguage);
                json.put("web_language", Utility.getCustomizedAppLanguage());
                json.put("appLanguage", Utility.getDrfaultLanguage());
                new ServerIntractorAsync(this, getString(R.string.please_wait_lbl), true, APIUtils.getBaseURL(APIUtils.SAVE_TOPICS_AND_ARTICLE_LANGUAGES), json, REQUEST_TYPE.POST, this,
                        new MessageParser()).execute();
            } catch (JSONException e) {
                e.printStackTrace();
            }
            mTracker.send(new HitBuilders.EventBuilder().setCategory("Topics").setAction(serverName).build());

            firebaseAnalytics.setUserProperty("interests", serverName);
        } else {
            Utility.showNetworkNotAvailToast(this);
        }
    }

    @Override
    public void onComplete(IModel object, String message, boolean status) {
        if (object instanceof Error) {
            Utility.showToastMessage(SettingActivity.this, ((Error) object).getError());
        } else if (object instanceof ResponseMsgInfo) {
            Utility.setSharedPrefStringData(this, Constants.USER_TOPICS, topicIDs);
            Utility.setSharedPrefStringData(this, Constants.ARTICLE_LANGUAGE, languageCodes);
            setResult(RESULT_OK);
            finish();
        }
    }

    @Override
    public void onComplete(ArrayList<IModel> object, String message, boolean status) {

        if (object.get(0) instanceof Error) {
            if (fetchingMagazines) {
                fetchingMagazines = false;
            } else if (fetchingTopics) {
                fetchingTopics = false;
            } else {
                if (isSubscriptionServiceCalled)
                    isSubscriptionServiceCalled = false;
                Utility.showToastMessage(this, ((Error) object.get(0)).getError());
            }
        } else if (object.get(0) instanceof TopicsModle) {

            fetchingTopics = false;
            mTopicsList.clear();
            mTopicsList.addAll((ArrayList<IModel>) object);
            updateTopics();
        }
    }

    private void showAddNewMagazineDialog() {
        AlertDialog.Builder builder = new AlertDialog.Builder(this, R.style.custom_dialog_theme);
        builder.setCancelable(false);
        builder.setTitle(getString(R.string.app_name));
        builder.setIcon(R.drawable.app_icon);
        builder.setMessage(getString(R.string.subscription_pass));
        final EditText input = new EditText(this);
        input.setHint(getString(R.string.subscription_pass_enter_pass));
        input.setInputType(InputType.TYPE_TEXT_VARIATION_PASSWORD);
        input.setSingleLine(true);
        input.setBackgroundColor(getResources().getColor(R.color.white));
        LinearLayout.LayoutParams lp = new LinearLayout.LayoutParams(LinearLayout.LayoutParams.MATCH_PARENT, LinearLayout.LayoutParams.WRAP_CONTENT);
        input.setLayoutParams(lp);
        builder.setView(input);
        builder.setPositiveButton(getString(R.string.lbl_ok), new DialogInterface.OnClickListener() {
            public void onClick(DialogInterface dialog, int id) {
                // do nothing as we are have overridden onclick on this button below to prevent auto
                // dismissing the dialog
            }
        });
        builder.setNegativeButton(getString(R.string.lbl_cancel), new DialogInterface.OnClickListener() {
            public void onClick(DialogInterface dialog, int id) {
                dialog.dismiss();
            }
        });
        // builder.create().show();
        dialog = builder.show();
        dialog.getButton(AlertDialog.BUTTON_POSITIVE).setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                String in = input.getText().toString().trim();
                if (in.length() == 0) {
                    Utility.showToastMessage(SettingActivity.this, getString(R.string.enter_pass));
                } else {
                    dialog.dismiss();
                    callSubscribeMagazine(in);
                }
            }
        });
    }

    @Override
    public void onActionOk(int requestCode) {
        if (requestCode == REQ_CODE_LOGOUT) {
            Utility.clearUserData(this);

            Intent loginIntent = new Intent(this, LoginActivity.class);
            loginIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
            startActivity(loginIntent);
            if (HomeActivity.getInstance() != null)
                HomeActivity.getInstance().finish();
            finish();
        }
    }

    @Override
    public void onActionCancel(int requestCode) {

    }

    @Override
    public void onActionNeutral(int requestCode) {

    }

    @Override
    public void onConnectionFailed(@NonNull ConnectionResult connectionResult) {

    }

    private class DeleteMagazine extends AsyncTask<Void, Void, String> {

        String userId, magazineId;

        public DeleteMagazine(String userId, String magazineId) {
            this.userId = userId;
            this.magazineId = magazineId;
        }

        String response;
        ProgressDialog dialog;

        @Override
        protected void onPreExecute() {
            dialog = new ProgressDialog(SettingActivity.this);
            dialog.show();
            dialog.setMessage("Loading...");
            dialog.setCanceledOnTouchOutside(false);
            super.onPreExecute();
        }

        @Override
        protected String doInBackground(Void... params) {

            loadJsonData(userId, magazineId);

            String url = APIUtils.BASE_URL + APIUtils.CIPL_DELETEMAGAZINE_URL;
            response = Utility.httpPostRequestToServer(aReceiptWiseObj.toString(), url, SettingActivity.this);
            return response;
        }

        protected void onPostExecute(String response) {
            super.onPostExecute(response);
            dialog.dismiss();
            try {
                JSONObject jsonObject = new JSONObject(response);

                String Name = jsonObject.getString("message");
                Toast.makeText(getApplicationContext(), Name, Toast.LENGTH_SHORT).show();
            } catch (Exception e) {
                e.printStackTrace();
            }
        }
    }

    private void loadJsonData(String userId, String magazineId) {

        try {
            aReceiptWiseObj = new JSONObject();
            aReceiptWiseObj.put("token", userId);
            aReceiptWiseObj.put("magazine_id", magazineId);

        } catch (JSONException e) {
            e.printStackTrace();
        }
    }
}