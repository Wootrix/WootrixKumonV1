package com.ta.wootrix.tablet;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.Fragment;
import android.app.ProgressDialog;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ScrollView;
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
import com.ta.wootrix.modle.IModel;
import com.ta.wootrix.modle.TopicsModle;
import com.ta.wootrix.parser.MessageParser;
import com.ta.wootrix.phone.LoginActivity;
import com.ta.wootrix.restapi.APIUtils;
import com.ta.wootrix.restapi.APIUtils.REQUEST_TYPE;
import com.ta.wootrix.utils.Constants;
import com.ta.wootrix.utils.Utility;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

public class SettingFragment extends Fragment implements OnClickListener, IActionOKCancel, IAsyncCaller {
    private static final int REQ_CODE_LOGOUT = 202;
    String topicIDs, languageCodes;
    private ImageView mImgVwMyAccount;
//    private LinearLayout mLnrLytMagazines;
    private Button mBtnLogout;
    private TextView mAddAccount;
    private TextView mTxtVwHeaderCancel;
    private TextView mTxtVwHeaderDone;
    private ImageLoader mImageLoader;
    private LayoutInflater mLytInflater;
    private TopicsAdapter mTopicAdapters;
    private ArrayList<IModel> mTopicsList = new ArrayList<IModel>();
    private ArrayList<IModel> mMagazinesList = new ArrayList<IModel>();
    private boolean isSubscriptionServiceCalled;
    private boolean fetchingMagazines;
    private Activity mContext;
    private AlertDialog dialog;
    private ScrollView mScrollView;
    private ConnectionResult mConnectionResult;
    private GoogleApiClient mGoogleApiClient;
    private View v;
    private boolean fetchingTopics;
    private String mUserID;
    JSONObject aReceiptWiseObj;

    FirebaseAnalytics firebaseAnalytics;
    private Tracker mTracker;
    AnalyticsApplication application;
    String serverName = "";

    @Override
    public void onAttach(Activity activity) {
        super.onAttach(activity);
        mContext = activity;

    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        // setRetainInstance(true);
        if (v == null) {
            v = inflater.inflate(R.layout.setting_popup_scrn, container, false);
            initViews(v);
        }
        mUserID = Utility.getSharedPrefStringData(mContext, Constants.USER_TOKEN);
        return v;
    }

    private void initViews(View v) {
        mLytInflater = mContext.getLayoutInflater();
        mTxtVwHeaderCancel = (TextView) v.findViewById(R.id.setting_popup_cancel_txt);
        mTxtVwHeaderDone = (TextView) v.findViewById(R.id.setting_popup_done_txt);
        mTxtVwHeaderCancel.setVisibility(View.VISIBLE);
        mTxtVwHeaderDone.setVisibility(View.VISIBLE);

        mImgVwMyAccount = (ImageView) v.findViewById(R.id.setting_screen_myaccount_imgVw);

//        mLnrLytMagazines = (LinearLayout) v.findViewById(R.id.setting_screen_horizontal_lnrLyt);
        mBtnLogout = (Button) v.findViewById(R.id.setting_screen_logout_btn);

        application = (AnalyticsApplication) mContext.getApplication();
        mTracker = application.getDefaultTracker();
        firebaseAnalytics = FirebaseAnalytics.getInstance(mContext);
        /*
         * mAddAccount = (TextView) v .findViewById(R.id.setting_screen_add_account);
		 * mAddAccount.setOnClickListener(this);
		 */

        mBtnLogout.setOnClickListener(this);

        mTxtVwHeaderCancel.setOnClickListener(this);
        mTxtVwHeaderDone.setOnClickListener(this);
        mImgVwMyAccount.setOnClickListener(this);
        ((LinearLayout) v.findViewById(R.id.setting_popup_main_lnrlyt)).setOnClickListener(this);


    }

    @Override
    public void onClick(View v) {
        switch (v.getId()) {
            case R.id.setting_popup_cancel_txt:
                ((TabletHomeActivity) mContext).popFragmentAndHideLyt();
                break;
            case R.id.setting_popup_done_txt:
                validateFields();
                break;
            case R.id.setting_screen_logout_btn:
                // logout from the screen
                CustomDialog dialog = CustomDialog.getInstance(mContext, this, getString(R.string.wanna_logout), null, DIALOG_TYPE.OK_CANCEL, -1, getString(R.string.lbl_yes),
                        getString(R.string.lbl_no), REQ_CODE_LOGOUT);
                dialog.show();

                break;
            case R.id.setting_screen_myaccount_imgVw:
                ((TabletHomeActivity) mContext).switchFragments(new MyAccountFragment());
                break;

            default:
                break;
        }
    }

    private void validateFields() {
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

        if (languageCodes.length() == 0)
            Utility.showToastMessage(mContext, getString(R.string.please_choose_article_lang));
        else {
            saveTopicsAndLanguage(serverTopics, Serverlang);
        }

    }

    private void saveTopicsAndLanguage(String categories, String articleLanguage) {
        if (Utility.isNetworkAvailable(mContext)) {
            try {
                JSONObject json = new JSONObject();
                json.put("user_id", mUserID);
                json.put("category", categories);
                json.put("article_language", articleLanguage);
                json.put("web_language", Utility.getCustomizedAppLanguage());
                new ServerIntractorAsync(mContext, getString(R.string.please_wait_lbl), true, APIUtils.getBaseURL(APIUtils.SAVE_TOPICS_AND_ARTICLE_LANGUAGES), json, REQUEST_TYPE.POST, this,
                        new MessageParser()).execute();
            } catch (JSONException e) {
                e.printStackTrace();
            }
            mTracker.send(new HitBuilders.EventBuilder().setCategory("Topics").setAction(serverName).build());
            firebaseAnalytics.setUserProperty("interests", serverName);
        } else {
            Utility.showNetworkNotAvailToast(mContext);
        }
    }

    @Override
    public void onActionOk(int requestCode) {
        if (requestCode == REQ_CODE_LOGOUT) {
            Utility.clearUserData(mContext);

            Intent loginIntent = new Intent(mContext, LoginActivity.class);
            loginIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
            startActivity(loginIntent);
            mContext.finish();
        }
    }

    private void updateUI() {
        mConnectionResult = null;
        mGoogleApiClient.connect();
    }

    @Override
    public void onActionCancel(int requestCode) {

    }

    @Override
    public void onActionNeutral(int requestCode) {

    }

    @Override
    public void onComplete(IModel object, String message, boolean status) {

    }

    @Override
    public void onComplete(ArrayList<IModel> object, String message, boolean status) {

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
            dialog = new ProgressDialog(mContext);
            dialog.show();
            dialog.setMessage("Loading...");
            dialog.setCanceledOnTouchOutside(false);
            super.onPreExecute();
        }

        @Override
        protected String doInBackground(Void... params) {

            loadJsonData(userId, magazineId);

            String url = APIUtils.BASE_URL + APIUtils.CIPL_DELETEMAGAZINE_URL;
            response = Utility.httpPostRequestToServer(aReceiptWiseObj.toString(), url, mContext);
            return response;
        }

        protected void onPostExecute(String response) {
            super.onPostExecute(response);
            dialog.dismiss();
            try {
                JSONObject jsonObject = new JSONObject(response);

                String Name = jsonObject.getString("message");
                Toast.makeText(mContext, Name, Toast.LENGTH_SHORT).show();
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
