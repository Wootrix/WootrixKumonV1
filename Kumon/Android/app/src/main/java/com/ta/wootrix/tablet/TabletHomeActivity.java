package com.ta.wootrix.tablet;

import android.app.AlertDialog;
import android.app.Fragment;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.res.Configuration;
import android.graphics.Color;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Build;
import android.os.Bundle;
import android.os.Handler;
import android.support.v7.app.AppCompatActivity;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.Log;
import android.view.Display;
import android.view.GestureDetector;
import android.view.GestureDetector.SimpleOnGestureListener;
import android.view.LayoutInflater;
import android.view.MotionEvent;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.View.OnTouchListener;
import android.view.ViewTreeObserver;
import android.view.ViewTreeObserver.OnGlobalLayoutListener;
import android.view.animation.TranslateAnimation;
import android.widget.EditText;
import android.widget.FrameLayout;
import android.widget.FrameLayout.LayoutParams;
import android.widget.HorizontalScrollView;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.TextView;

import com.google.android.gms.analytics.Tracker;
import com.nostra13.universalimageloader.core.ImageLoader;
import com.ta.wootrix.R;
import com.ta.wootrix.adapter.SearchArticleAdapter;
import com.ta.wootrix.adapter.TabletFlipViewAdapter;
import com.ta.wootrix.asynctask.IAsyncCaller;
import com.ta.wootrix.asynctask.ServerIntractorAsync;
import com.ta.wootrix.firebase.AnalyticsApplication;
import com.ta.wootrix.firebase.ArticleDetailActivityTwo;
import com.ta.wootrix.firebase.StorePreference;
import com.ta.wootrix.modle.AdvtMainModle;
import com.ta.wootrix.modle.ArticleModle;
import com.ta.wootrix.modle.Error;
import com.ta.wootrix.modle.IModel;
import com.ta.wootrix.modle.MagazineModle;
import com.ta.wootrix.parser.AdvrtParser;
import com.ta.wootrix.parser.ArticesParser;
import com.ta.wootrix.parser.MessageParser;
import com.ta.wootrix.phone.HomeActivity;
import com.ta.wootrix.phone.LandingPageActivity;
import com.ta.wootrix.phone.SettingActivity;
import com.ta.wootrix.restapi.APIUtils;
import com.ta.wootrix.restapi.APIUtils.REQUEST_TYPE;
import com.ta.wootrix.socialntwrkings.OnIntentResult;
import com.ta.wootrix.utils.AccessRegisterListener;
import com.ta.wootrix.utils.Constants;
import com.ta.wootrix.utils.GPSTracker;
import com.ta.wootrix.utils.Utility;

import org.json.JSONException;
import org.json.JSONObject;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;

import se.emilsjolander.flipview.FlipView;
import se.emilsjolander.flipview.FlipView.OnFlipListener;
import se.emilsjolander.flipview.FlipView.OnOverFlipListener;
import se.emilsjolander.flipview.OverFlipMode;

public class TabletHomeActivity extends AppCompatActivity implements OnClickListener, IAsyncCaller, FragCommuInterface, AccessRegisterListener {
    private static final int REQ_CODE_SETTINGS = 42;
    private static final int SWIPE_MIN_DISTANCE = 120;
    private static final int SWIPE_MAX_OFF_PATH = 250;
    private static final int SWIPE_THRESHOLD_VELOCITY = 200;
    private static final int CONTENT_VIEW_ID = 18949;
    public static int pageNo = 0;
    // public static boolean isOrientationChanged = false;
    public static boolean isCoverVisible;
    private static TabletHomeActivity instance;
    public OnIntentResult mUpdateResult;
    protected AsyncTask<Void, Void, String> mSearchAsync;
    int width;
    int height;
    View.OnTouchListener gestureListener;
    int viewState = 0;
    boolean isOpened = false;
    boolean isKeyBoardOpen;
    ArrayList<TextView> mPagesList = new ArrayList<TextView>();
    boolean isDataFechingAuto = false;
    int childViewWidth;
    private FlipView flipView;
    private ImageView mImgVwHeaderTopBanner;
    private EditText mEdtTxtSearch;
    private ImageView mImgVwSearchIcon;
    private ImageView mImgVwProfileIcon;
    private ImageView mImgVwLeftPageSlide;
    private ImageView mImgVwRightPageSlide;

    public static boolean openMagazine = false;
    private LinearLayout mLnrLytShowPaging;
    private LinearLayout mLnrLytPagingLyt;
    private HorizontalScrollView mHoriZontalScrollVw;
    private LayoutInflater mLytInflater;
    private MagazineModle magazineData;
    private int startPaging = 1;
    private ImageLoader mImageLoader;
    private LinearLayout mLnrLytHeader;
    private ArticleModle articleData;
    private ArrayList<IModel> mArticleList = new ArrayList<IModel>();
    private ArrayList<IModel> mSearchResList = new ArrayList<IModel>();
    private TabletFlipViewAdapter mTabletFlipAdapter;
    private SearchArticleAdapter mSearchAdapter;
    private GPSTracker mGpsTracker;
    private boolean isCoverHide = false;
    private boolean isRefreshed = false;
    private boolean isLandscape = false;
    private boolean openPage = false;
    private boolean isMagzinData = false;

    AnalyticsApplication application;
    Tracker mTracker;


    // --0 for normal flipview
    // --1 for search screen
    // --2 for fragment view
    private boolean isOnFirstPage = false;
    private ImageView ivCoverPage;
    private GestureDetector gestureDetector;
    private SocialCallback callback;
    private int mBlueColor;
    private int mBlakisColor;
    private ListView mListVwSearchResults;
    private FrameLayout mFragmentFramLyt;
    private int currentPosition;
    private boolean isHumanJumpToPosition = false;
    private ImageView mImgVwRefresh;
    private boolean isServiceAlreadyRunning;
    private AdvtMainModle mAdvtData;
    private Handler handler = new Handler();
    private int backPressed;
    private FrameLayout myFrame;

    String receivedMessage = null, article_id, notiFurl, messageType;
    StorePreference myStorePreference;

    String allowShare = null, fullSource = null, allowComment = null, embedded_video = null, title = null,
            createdBy = null, articleDesc = null, articleVideoUrl = null, coverPhotoUrl = null, articleType = null, artArticle_id = null, close_subtype = null;

    public static TabletHomeActivity getInstance() {
        return instance;
    }

    @Override
    public void onConfigurationChanged(Configuration newConfig) {
        super.onConfigurationChanged(newConfig);
        // Checks the orientation of the screen
        if (newConfig.orientation == Configuration.ORIENTATION_LANDSCAPE || newConfig.orientation == Configuration.ORIENTATION_PORTRAIT) {
            if (newConfig.orientation == Configuration.ORIENTATION_LANDSCAPE)
                isLandscape = true;
            else
                isLandscape = false;

			/*
             * if (!isCoverVisible) isOrientationChanged = true;
			 */

            mFragmentFramLyt.removeAllViews();
            String edttxt = mEdtTxtSearch.getText().toString().trim();
            setContentView(R.layout.home_screen);
            setListnerToRootView();

            initViews();

            mTabletFlipAdapter = null;
            if (mArticleList.size() > 0) {
                mPagesList.clear();
                mLnrLytPagingLyt.removeAllViews();
                setFlipView();
                if (myFrame != null)
                    mFragmentFramLyt.addView(myFrame);
                if (currentPosition > 0) {
                    flipView.flipTo(currentPosition);
                    updateTextColor(currentPosition);
                    scrollScrollViewOnPageChange(currentPosition);
                }
                if (viewState == 0) {
                    if (isKeyBoardOpen)
                        Utility.hideKeyboard(this);
                    mImgVwSearchIcon.setImageResource(R.drawable.icon_search);
                    mEdtTxtSearch.setText("");
                    mSearchResList.clear();
                    if (mSearchAdapter != null)
                        mSearchAdapter.notifyDataSetChanged();
                    mListVwSearchResults.setVisibility(View.GONE);
                    flipView.setVisibility(View.VISIBLE);
                    mLnrLytShowPaging.setVisibility(View.VISIBLE);

                } else if (viewState == 1) {
                    if (mFragmentFramLyt.isShown())
                        mFragmentFramLyt.setVisibility(View.GONE);
                    mEdtTxtSearch.setText(edttxt);
                    mEdtTxtSearch.requestFocus();
                    mEdtTxtSearch.setFocusable(true);
                    mLnrLytShowPaging.setVisibility(View.GONE);
                    flipView.setVisibility(View.GONE);
                    mListVwSearchResults.setVisibility(View.VISIBLE);
                    mImgVwSearchIcon.setImageResource(R.drawable.icon_search_cross);
                } else if (viewState == 2) {
                    mFragmentFramLyt.setVisibility(View.VISIBLE);
                }
            }
        }

    }

    public void setListnerToRootView() {
        final View activityRootView = getWindow().getDecorView().findViewById(android.R.id.content);
        activityRootView.getViewTreeObserver().addOnGlobalLayoutListener(new OnGlobalLayoutListener() {
            @Override
            public void onGlobalLayout() {

                int heightDiff = activityRootView.getRootView().getHeight() - activityRootView.getHeight();
                if (heightDiff > 100) { // 99% of the time the height
                    // diff will be due to a
                    // keyboard.
                    // Toast.makeText(getApplicationContext(),
                    // "Gotcha!!! softKeyboardup",
                    // 0).show();
                    isKeyBoardOpen = true;
                    if (isOpened == false) {
                        // Do two things, make the view top visible and
                        // the editText smaller
                    }
                    isOpened = true;
                } else if (isOpened == true) {
                    isKeyBoardOpen = false;
                    // Toast.makeText(getApplicationContext(),
                    // "softkeyborad Down!!!", 0).show();
                    isOpened = false;
                    try {
                        viewState = 0;
                        mImgVwSearchIcon.setImageResource(R.drawable.icon_search);
                        mEdtTxtSearch.setText("");
                        mSearchResList.clear();
                        if (mSearchAdapter != null)
                            mSearchAdapter.notifyDataSetChanged();
                        mListVwSearchResults.setVisibility(View.GONE);
                        flipView.setVisibility(View.VISIBLE);
                        mLnrLytShowPaging.setVisibility(View.VISIBLE);
                    } catch (Exception e) {
                    }
                }
            }
        });
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.home_screen);
        isCoverVisible = true;
        // Toast.makeText(this, " on create called", Toast.LENGTH_LONG).show();
        instance = this;
        setListnerToRootView();
        mGpsTracker = new GPSTracker(this);
        if (getIntent().getExtras() != null && getIntent().getExtras().containsKey("magazineData")) {
            magazineData = getIntent().getExtras().getParcelable("magazineData");
            isMagzinData = true;
        }

        if (getIntent().getExtras() != null && getIntent().getExtras().containsKey("showInputDialog")) {
            Intent intentSettings = new Intent(this, SettingActivity.class);
            intentSettings.putExtra("showInputDialog", true);
            startActivityForResult(intentSettings, REQ_CODE_SETTINGS);
        }


        // else
        // if (getIntent().getExtras() != null &&
        // getIntent().getExtras().containsKey("articleData"))
        // articleData = getIntent().getExtras().getParcelable("articleData");
        mLytInflater = getLayoutInflater();
        mBlueColor = getResources().getColor(R.color.color_blue);
        mBlakisColor = getResources().getColor(R.color.black_heading);
        initViews();

        receivingIntent();
        if (messageType != null) {
            Utility.toastMessage(this, messageType);
        }
        getAdvertiseAsync();

    }

    private void receivingIntent() {
        Log.d("TABHome LOG", "TABHome LOG");

        Intent intent = getIntent();
        if (intent != null) {
            receivedMessage = intent.getStringExtra("message");
            notiFurl = intent.getStringExtra("url");
            if (notiFurl == null) {
                notiFurl = intent.getStringExtra("fullSource");
            }
            messageType = intent.getStringExtra("messagetype");
            article_id = intent.getStringExtra("article_id");
            if (messageType != null) {
                if (messageType.equals("message")) {
                    if (receivedMessage != null && receivedMessage != "") {
                        openAlertDialog(receivedMessage);
                        Log.d("Login Intent", receivedMessage);
                    }
                } else if (messageType.equals("article")) {
                    if (notiFurl != null && notiFurl != "") {
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
                    }
                } else if (messageType.equals("closemagazine")) {
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


    public void openAlertDialog(String receivedMessage) {

        new AlertDialog.Builder(this, R.style.custom_dialog_theme).setMessage(receivedMessage).setIcon(R.drawable.app_icon).setCancelable(false).setTitle(this.getString(R.string.app_name))
                .setPositiveButton(this.getResources().getString(android.R.string.ok), new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int whichButton) {
                        dialog.dismiss();
                    }
                }).create().show();
    }


    @Override
    protected void onDestroy() {
        super.onDestroy();
        if (mGpsTracker != null)
            mGpsTracker.stopUsingGPS();
    }

    private void getAdvertiseAsync() {
        if (Utility.isNetworkAvailable(this)) {
            isServiceAlreadyRunning = true;

            new ServerIntractorAsync(this, getString(R.string.please_wait_lbl), startPaging == 1 ? true : false,
                    magazineData == null ? APIUtils.getBaseURL(APIUtils.GET_OPEN_ARTICLES_ADVERTISEMENT) : APIUtils.getBaseURL(APIUtils.GET_MAGAZINES_ADVERTISEMENT), getAdvtJsonParams(),
                    REQUEST_TYPE.POST, this, new AdvrtParser()).execute();
        } else {
            Utility.showNetworkNotAvailToast(this);
        }
    }

    private JSONObject getAdvtJsonParams() {
        try {
            if (magazineData == null) {
                JSONObject json = new JSONObject();
                json.put("platform", "tablet");
                json.put("topics", Utility.getSharedPrefStringData(this, Constants.USER_TOPICS));
                json.put("articleLanguage", Utility.getSharedPrefStringData(this, Constants.ARTICLE_LANGUAGE));
                json.put("token", Utility.getSharedPrefStringData(this, Constants.USER_TOKEN));
                json.put("appLanguage", Utility.getDrfaultLanguage());
                return json;

            } else {
                JSONObject json = new JSONObject();
                json.put("platform", "tablet");
                json.put("token", Utility.getSharedPrefStringData(this, Constants.USER_TOKEN));
                json.put("appLanguage", Utility.getDrfaultLanguage());
                json.put("magazineId", magazineData.getMagazineId());
                return json;
            }
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return null;
    }

    @Override
    protected void onResume() {
        super.onResume();
        //  receivingIntent();
        Log.d("onResume", "onResume");
        if (Utility.getSharedPrefBooleanData(this, Constants.USER_SHOW_PAGE))
            mLnrLytShowPaging.setVisibility(View.GONE);
        else
            mLnrLytShowPaging.setVisibility(View.VISIBLE);
        calulateChildWidth();

    }

    @Override
    protected void onPause() {
        super.onPause();
        //  receivingIntent();
        Log.d("onPause", "onPause");
    }

    private void getArticlesAsync() {
        if (Utility.isNetworkAvailable(this)) {
            isServiceAlreadyRunning = true;
            new ServerIntractorAsync(this, getString(R.string.please_wait_lbl), startPaging == 1 ? true : false,
                    magazineData == null ? APIUtils.getBaseURL(APIUtils.GET_OPEN_ARTICLES) : APIUtils.getBaseURL(APIUtils.GET_MAGAZINE_ARTICLES), getJsonParams(), REQUEST_TYPE.POST, this,
                    new ArticesParser(magazineData == null ? true : false, 1)).execute();
        } else {
            Utility.showNetworkNotAvailToast(this);
        }
    }

    private JSONObject getJsonParams() {
        // <tablet, web, >, , topics<1,2,3>, <en, sp>
        try {
            JSONObject json = new JSONObject();
            json.put("deviceType", "tablet");
            json.put("pageNumber", startPaging);
            json.put("topics", Utility.getSharedPrefStringData(this, Constants.USER_TOPICS));
            json.put("articleLanguage", Utility.getSharedPrefStringData(this, Constants.ARTICLE_LANGUAGE));
            json.put("token", Utility.getSharedPrefStringData(this, Constants.USER_TOKEN));
            json.put("appLanguage", Utility.getDrfaultLanguage());
            if (magazineData != null)
                json.put("magazineId", magazineData.getMagazineId());

            return json;
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return null;
    }

    private void initalisePages() {
        // mPagesList.clear();
        // mLnrLytPagingLyt.removeAllViews();
        for (int i = mPagesList.size(); i < getPageNumber(); i++) {
            View v = mLytInflater.inflate(R.layout.page_number_row, null);
            TextView txtVw = (TextView) v.findViewById(R.id.page_number_row_page_txt);
            mPagesList.add(txtVw);
            txtVw.setText(i + 1 + "");
            txtVw.setTag(i);
            if (i == 0 && startPaging == 1)
                txtVw.setTextColor(mBlueColor);
            else
                txtVw.setTextColor(mBlakisColor);
            txtVw.setOnClickListener(new OnClickListener() {
                @Override
                public void onClick(View v) {
                    isHumanJumpToPosition = true;
                    int pos = (Integer) v.getTag();
                    updateTextColor(pos);
                    currentPosition = pos;

                    flipView.flipTo(pos);
                    mTabletFlipAdapter.notifyDataSetChanged();
                    if (startPaging <= ((ArticleModle) mArticleList.get(0)).getTotalPages() && pos > getPageNumber() - 4 && !isServiceAlreadyRunning) {
                        isDataFechingAuto = true;
                        getArticlesAsync();
                    }
                }
            });
            mLnrLytPagingLyt.addView(v);
        }
    }

    protected void updateTextColor(int pos) {
        for (int i = 0; i < mPagesList.size(); i++) {
            if (i == pos)
                mPagesList.get(i).setTextColor(getResources().getColor(R.color.color_blue));
            else
                mPagesList.get(i).setTextColor(getResources().getColor(R.color.black_heading));
        }
    }

    private void initViews() {
        mImgVwRefresh = (ImageView) findViewById(R.id.home_screen_refresh_imgVw_imgVw);
        mFragmentFramLyt = (FrameLayout) findViewById(R.id.home_screen_popups_frame_lyt);
        mImgVwHeaderTopBanner = (ImageView) findViewById(R.id.home_screen_top_banner_imageVw);
        mLnrLytHeader = (LinearLayout) findViewById(R.id.home_screen_top_banner_bg_lnrLyt);
        mEdtTxtSearch = (EditText) findViewById(R.id.home_screen_search_edtTxt);
        mImgVwSearchIcon = (ImageView) findViewById(R.id.home_screen_search_imgVw);

        mImgVwProfileIcon = (ImageView) findViewById(R.id.home_screen_profile_image_imgVw);
        mImgVwLeftPageSlide = (ImageView) findViewById(R.id.home_screen_left_paging_imgVw);
        mImgVwRightPageSlide = (ImageView) findViewById(R.id.home_screen_right_paging_imgVw);
        mLnrLytShowPaging = (LinearLayout) findViewById(R.id.home_screen_page_lnrLyt);
        mLnrLytPagingLyt = (LinearLayout) findViewById(R.id.home_screen_pager_slider_lnrLyt);
        mHoriZontalScrollVw = (HorizontalScrollView) findViewById(R.id.home_screen_pager_slider_scrollVw);
        mListVwSearchResults = (ListView) findViewById(R.id.home_screen_articles_listview);


        myStorePreference = new StorePreference(this);

        application = (AnalyticsApplication) getApplication();
        mTracker = application.getDefaultTracker();

        flipView = (FlipView) findViewById(R.id.home_screen_flipview);
        flipView.setOverFlipMode(OverFlipMode.RUBBER_BAND);
        // flipView.setEmptyView(findViewById(R.id.empty_view));
        mImgVwRefresh.setOnClickListener(this);

        flipView.setOnOverFlipListener(new OnOverFlipListener() {
            @Override
            public void onOverFlip(FlipView v, OverFlipMode mode, boolean overFlippingPrevious, float overFlipDistance, float flipDistancePerPage, int position) {

                if (overFlippingPrevious && isOnFirstPage) {

                    if (openPage) {
                        showCoverImage();
                        openPage = false;
                    } else {
                        openPage = true;
                    }
                    System.out.println("Position :- " + overFlippingPrevious + " , " + position);

                } /*
                     * else { isOnFirstPage = true; }
					 */

            }
        });

        flipView.setOnFlipListener(new OnFlipListener() {
            @Override
            public void onFlippedToPage(FlipView v, int position, long id) {
                if (position == 0) {
                    isOnFirstPage = true;
                    pageNo = 0;
                } else {
                    isOnFirstPage = false;
                    pageNo = position;
                }

                try {
                    currentPosition = position;
                    updateTextColor(position);
                    if (isHumanJumpToPosition)
                        isHumanJumpToPosition = false;
                    else
                        scrollScrollViewOnPageChange();
                } catch (Exception e) {
                    e.printStackTrace();
                }
                if (startPaging <= ((ArticleModle) mArticleList.get(0)).getTotalPages() && position > getPageNumber() - 4 && !isServiceAlreadyRunning) {
                    isDataFechingAuto = true;
                    getArticlesAsync();
                }
            }
        });
        mImgVwLeftPageSlide.setOnClickListener(this);
        mImgVwRightPageSlide.setOnClickListener(this);
        mImgVwProfileIcon.setOnClickListener(this);
        mImgVwSearchIcon.setOnClickListener(this);
        mFragmentFramLyt.setOnClickListener(this);
        if (mImageLoader == null)
            mImageLoader = Utility.getImageLoader(this);

        mImageLoader.displayImage(Utility.getSharedPrefStringData(this, Constants.USER_IMAGE), mImgVwProfileIcon, Utility.getProfilePicDisplayOption());
        mEdtTxtSearch.setOnTouchListener(new OnTouchListener() {
            @Override
            public boolean onTouch(View v, MotionEvent event) {

                if (mFragmentFramLyt.isShown())
                    mFragmentFramLyt.setVisibility(View.GONE);
                viewState = 1;
                v.requestFocus();
                v.setFocusable(true);
                mLnrLytShowPaging.setVisibility(View.GONE);
                flipView.setVisibility(View.GONE);
                mListVwSearchResults.setVisibility(View.VISIBLE);
                mImgVwSearchIcon.setImageResource(R.drawable.icon_search_cross);
                return false;
            }
        });

        mEdtTxtSearch.addTextChangedListener(new TextWatcher() {
            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
                if (s.toString().trim().length() >= 3) {
                    if (Utility.isNetworkAvailable(TabletHomeActivity.this)) {
                        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.HONEYCOMB) {
                            mSearchAsync = new SearchAsyncTask(s.toString().trim()).executeOnExecutor(AsyncTask.THREAD_POOL_EXECUTOR);
                        } else {
                            mSearchAsync = new SearchAsyncTask(s.toString().trim()).execute();

                        }
                    } else
                        Utility.showNetworkNotAvailToast(TabletHomeActivity.this);

                } else {
                    mSearchResList.clear();
                    if (mSearchAdapter != null)
                        mSearchAdapter.notifyDataSetChanged();
                }
            }

            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
                // TODO Auto-generated method stub

            }

            @Override
            public void afterTextChanged(Editable s) {
                // TODO Auto-generated method stub

            }
        });
        ivCoverPage = (ImageView) findViewById(R.id.home_screen_cover_page);
        updateHeader();

    }

    public void updateCover() {

        Display mDisplay = getWindowManager().getDefaultDisplay();
        width = mDisplay.getWidth();
        height = mDisplay.getHeight();

        if (isMagzinData && mArticleList.size() > 0) {

			/* load cover image on image view */
            String cover_url = ((ArticleModle) mArticleList.get(0)).getCoverImageMag() + getImageSize();
            Log.i("TAG", "cover image url: " + cover_url);
            if (cover_url != null && !cover_url.equalsIgnoreCase(""))
                mImageLoader.displayImage(cover_url, ivCoverPage, Utility.getMagazinesCoverDisplayOption());

            if (startPaging == 1 || !isCoverHide)
                setSwipeOnImage();

            if (/* !isOrientationChanged && */isCoverVisible) {
                // isOrientationChanged=false;
                isOnFirstPage = true;

                ivCoverPage.setVisibility(View.VISIBLE);
                isCoverVisible = true;

				/* make visible to cover page */
                if (isCoverHide) {
                    TranslateAnimation moveLeftFromRight = new TranslateAnimation((-1) * width, 0, 0, 0);
                    moveLeftFromRight.setDuration(100);
                    moveLeftFromRight.setFillAfter(true);
                    ivCoverPage.startAnimation(moveLeftFromRight);
                    isCoverHide = false;
                }
            }

        } else {
            ivCoverPage.setVisibility(View.GONE);
        }

    }

    public String getImageSize() {

        String size = "";

		/* for tablet */
        if (width < 1100 && isLandscape) {

            size = size + "1024600.png";
        } else if (width > 1100 && isLandscape) {
            size = size + "1280800.png";
        }
        if (width < 700 && !isLandscape) {
            size = size + "6001024.png";
        } else if (width > 700 && !isLandscape) {
            size = size + "8001280.png";
        }

        return size;
        // return "320568.png";

    }

    public void setSwipeOnImage() {
        // Gesture detection
        gestureDetector = new GestureDetector(new MyGestureDetector());
        gestureListener = new View.OnTouchListener() {
            public boolean onTouch(View v, MotionEvent event) {
                if (gestureDetector.onTouchEvent(event)) {
                    return true;
                }
                return false;
            }
        };

        ivCoverPage.setOnTouchListener(gestureListener);
    }

    public void showCoverImage() {
        if (isMagzinData) {
            ivCoverPage.setVisibility(View.VISIBLE);
            isCoverVisible = true;
            isCoverHide = false;
            ivCoverPage.setOnTouchListener(gestureListener);
            TranslateAnimation moveLeftFromRight = new TranslateAnimation((-1) * width, 0, 0, 0);
            moveLeftFromRight.setDuration(500);
            moveLeftFromRight.setFillAfter(true);
            ivCoverPage.startAnimation(moveLeftFromRight);
        }

    }

    private void updateHeader() {
        if (magazineData != null) {
            mImgVwHeaderTopBanner.setVisibility(View.VISIBLE);
            openMagazine = true;
            mImageLoader.displayImage(magazineData.getCustomerLogoUrl(), mImgVwHeaderTopBanner, Utility.getTopBannerLogiDisplayOption());
            try {
                mLnrLytHeader.setBackgroundColor(Color.parseColor(magazineData.getMobileAppBarColorRGB()));
            } catch (Exception e) {
            }
        } else {
            mLnrLytHeader.setBackgroundColor(getResources().getColor(R.color.banner_color));
            mImgVwHeaderTopBanner.setImageResource(R.drawable.wootrix_logo);
            mImgVwHeaderTopBanner.setVisibility(View.INVISIBLE);
        }

        mImgVwHeaderTopBanner.setOnClickListener(new OnClickListener() {
            @Override
            public void onClick(View v) {
                Utility.setSharedPrefBooleanData(TabletHomeActivity.this, Constants.isMagzineAvailable, true);
                startActivity(new Intent(TabletHomeActivity.this, LandingPageActivity.class));
                finish();
            }
        });
    }

    public void scrollScrollViewOnPageChange() {
        mHoriZontalScrollVw.scrollTo(childViewWidth * flipView.getCurrentPage(), 0);
    }

    public void scrollScrollViewOnPageChange(int postition) {
        mHoriZontalScrollVw.scrollTo(childViewWidth * postition, 0);
    }

    private void setFlipView() {
        int pageNumber = getPageNumber();

        if (notiFurl != null) {
            if (messageType.equals("advertisement")) {
                if (mTabletFlipAdapter == null) {
                    mTabletFlipAdapter = new TabletFlipViewAdapter(this, mArticleList, pageNumber, mAdvtData, magazineData != null ? magazineData.getCustomerLogoUrl() : null, mGpsTracker, magazineData, this);
                    flipView.setAdapter(mTabletFlipAdapter);
                } else {
                    mTabletFlipAdapter.updateMagazineData(magazineData);
                    mTabletFlipAdapter.updatePageNumber(getPageNumber());
                    mTabletFlipAdapter.notifyDataSetChanged();
                }
                checkAdvertisementUrl(notiFurl);
            } else if (messageType.equals("article")) {

                if (mTabletFlipAdapter == null) {
                    mTabletFlipAdapter = new TabletFlipViewAdapter(this, mArticleList, pageNumber, mAdvtData, magazineData != null ? magazineData.getCustomerLogoUrl() : null, mGpsTracker, magazineData, this);
                    flipView.setAdapter(mTabletFlipAdapter);
                } else {
                    mTabletFlipAdapter.updateMagazineData(magazineData);
                    mTabletFlipAdapter.updatePageNumber(getPageNumber());
                    mTabletFlipAdapter.notifyDataSetChanged();
                }

                checkURLHaveOrNot(notiFurl, allowShare, fullSource, allowComment, embedded_video, title, createdBy, articleDesc, articleVideoUrl, coverPhotoUrl, articleType, artArticle_id);

            } else if (messageType.equals("closemagazine")) {
                if (close_subtype.equals("close_advertisement")) {
                    Log.d("With advertisement URL", messageType);
                    if (mTabletFlipAdapter == null) {
                        mTabletFlipAdapter = new TabletFlipViewAdapter(this, mArticleList, pageNumber, mAdvtData, magazineData != null ? magazineData.getCustomerLogoUrl() : null, mGpsTracker, magazineData, this);
                        flipView.setAdapter(mTabletFlipAdapter);
                    } else {
                        mTabletFlipAdapter.updateMagazineData(magazineData);
                        mTabletFlipAdapter.updatePageNumber(getPageNumber());
                        mTabletFlipAdapter.notifyDataSetChanged();
                    }
                    checkCloseMagAdvHaveOrNot(notiFurl);
                } else if (close_subtype.equals("close_article")) {
                    Log.d("With article URL", messageType);
                    if (mTabletFlipAdapter == null) {
                        mTabletFlipAdapter = new TabletFlipViewAdapter(this, mArticleList, pageNumber, mAdvtData, magazineData != null ? magazineData.getCustomerLogoUrl() : null, mGpsTracker, magazineData, this);
                        flipView.setAdapter(mTabletFlipAdapter);
                    } else {
                        mTabletFlipAdapter.updateMagazineData(magazineData);
                        mTabletFlipAdapter.updatePageNumber(getPageNumber());
                        mTabletFlipAdapter.notifyDataSetChanged();
                    }
                    checkCloseMagHaveOrNot(notiFurl, allowShare, fullSource, allowComment, embedded_video, title, createdBy, articleDesc, articleVideoUrl, coverPhotoUrl, articleType, artArticle_id);
                }
            }

        } else {
            if (mTabletFlipAdapter == null) {
                mTabletFlipAdapter = new TabletFlipViewAdapter(this, mArticleList, pageNumber, mAdvtData, magazineData != null ? magazineData.getCustomerLogoUrl() : null, mGpsTracker, magazineData, this);
                flipView.setAdapter(mTabletFlipAdapter);
            } else {
                mTabletFlipAdapter.updateMagazineData(magazineData);
                mTabletFlipAdapter.updatePageNumber(getPageNumber());
                mTabletFlipAdapter.notifyDataSetChanged();
            }
        }
        if (close_subtype == null) {
            if (!isRefreshed)
                updateCover();
            else
                isRefreshed = false;

            initalisePages();
            calulateChildWidth();
        } else if (close_subtype.equals("closemagazine")) {
            if (!isRefreshed)
                updateCover();
            else
                isRefreshed = false;

            initalisePages();
            calulateChildWidth();
        }
    }

    private void checkCloseMagHaveOrNot(String furl, String allowShare, String fullSource, String allowComment, String embedded_video, String title, String createdBy, String articleDesc, String articleVideoUrl, String coverPhotoUrl, String articleType, String notiFurl) {
        if (messageType != null) {
            ArticleModle articleModle = new ArticleModle();
            articleModle.setCanShare(allowShare.equals("Y") ? true : false);
            articleModle.setFullSource(fullSource);
            articleModle.setCanComment(allowComment.equals("Y") ? true : false);
            articleModle.setTitle(title);
            articleModle.setArticleDescPlain(articleDesc);
            articleModle.setVideoURL(articleVideoUrl);
            articleModle.setCoverPhotoUrl(coverPhotoUrl);
            articleModle.setArticleType(articleType);
            articleModle.setArticleID(artArticle_id);

            if (this.articleType.equals("photo")) {
                Intent myIntent = new Intent(this, ArticleDetailActivityTwo.class);
                myIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
                myIntent.putExtra("articleData", articleModle);
                myIntent.putExtra("launchFrom", true);
                myIntent.putExtra("isMagzine", magazineData != null ? true : false);
                myIntent.putExtra("magzineID", magazineData != null ? magazineData.getMagazineId() : null);
                fullSource = null;
                this.startActivity(myIntent);
            } else if (articleType.equals("embedded")) {
                Intent myEmbedIntent = new Intent(getApplicationContext(), TabletEmbeddedArticleDetailActivity.class);
                myEmbedIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
                myEmbedIntent.putExtra("articleData", articleModle);
                myEmbedIntent.putExtra("logoURL", fullSource);
                myEmbedIntent.putExtra("launchFrom", false);
                myEmbedIntent.putExtra("isMagzine", magazineData != null ? true : false);
                myEmbedIntent.putExtra("magzineID", magazineData != null ? magazineData.getMagazineId() : null);
                fullSource = null;
                this.startActivity(myEmbedIntent);
            } else if (articleType.equals("video")) {
                try {
                    Intent intent = new Intent(android.content.Intent.ACTION_VIEW);
                    intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
                    Uri uri = Uri.parse(this.articleVideoUrl);
                    intent.setDataAndType(uri, "video/*");
                    this.startActivity(intent);
                } catch (Exception e) {
                    e.printStackTrace();
                }
            }

        }
    }

    private void checkCloseMagAdvHaveOrNot(String closefullSource) {
        Intent myIntent = new Intent(this, TabletAdvertViewActivity.class);
        myIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
        myIntent.putExtra("advtURL", closefullSource);
        notiFurl = null;
        myIntent.putExtra("type", "link");
        startActivity(myIntent);
    }

    private void checkAdvertisementUrl(String advUrl) {
        Intent myIntent = new Intent(this, TabletAdvertViewActivity.class);
        myIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
        myIntent.putExtra("advtURL", advUrl);
        notiFurl = null;
        Log.d("ADV URL", (String) advUrl);
        myIntent.putExtra("type", "link");
        this.startActivity(myIntent);
    }

    private void checkURLHaveOrNot(String URLDtring, String allowShare, String fullSource, String allowComment, String embedded_video, String title, String createdBy, String articleDesc, String articleVideoUrl, String coverPhotoUrl, String articleType, String artArticle_id) {

        if (messageType != null) {
            ArticleModle articleModle = new ArticleModle();
            articleModle.setCanShare(allowShare.equals("Y") ? true : false);
            articleModle.setFullSource(fullSource);
            articleModle.setCanComment(allowComment.equals("Y") ? true : false);
            articleModle.setTitle(title);
            articleModle.setArticleDescPlain(articleDesc);
            articleModle.setVideoURL(articleVideoUrl);
            articleModle.setCoverPhotoUrl(coverPhotoUrl);
            articleModle.setArticleType(articleType);
            articleModle.setArticleID(artArticle_id);

            if (articleType.equals("photo")) {
                Intent myIntent = new Intent(this, ArticleDetailActivityTwo.class);
                myIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
                myIntent.putExtra("articleData", articleModle);
                myIntent.putExtra("launchFrom", true);
                myIntent.putExtra("isMagzine", magazineData != null ? true : false);
                myIntent.putExtra("magzineID", magazineData != null ? magazineData.getMagazineId() : null);
                notiFurl = null;
                this.startActivity(myIntent);
            } else if (articleType.equals("embedded")) {
                Intent myEmbedIntent = new Intent(getApplicationContext(), TabletEmbeddedArticleDetailActivity.class);
                myEmbedIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
                myEmbedIntent.putExtra("articleData", articleModle);
                myEmbedIntent.putExtra("logoURL", fullSource);
                myEmbedIntent.putExtra("launchFrom", false);
                myEmbedIntent.putExtra("isMagzine", magazineData != null ? true : false);
                myEmbedIntent.putExtra("magzineID", magazineData != null ? magazineData.getMagazineId() : null);
                notiFurl = null;
                this.startActivity(myEmbedIntent);
            } else if (articleType.equals("video")) {
                try {
                    Intent intent = new Intent(android.content.Intent.ACTION_VIEW);
                    intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
                    Uri uri = Uri.parse(articleVideoUrl);
                    intent.setDataAndType(uri, "video/*");
                    this.startActivity(intent);
                } catch (Exception e) {
                    e.printStackTrace();
                }
            }

        }


    }

    private void calulateChildWidth() {
        if (childViewWidth == 0)
            mHoriZontalScrollVw.getViewTreeObserver().addOnGlobalLayoutListener(new ViewTreeObserver.OnGlobalLayoutListener() {

                @SuppressWarnings("deprecation")
                @Override
                public void onGlobalLayout() {
                    // Ensure you call it only once :
                    mHoriZontalScrollVw.getViewTreeObserver().removeGlobalOnLayoutListener(this);
                    final int count = ((LinearLayout) mHoriZontalScrollVw.getChildAt(0)).getChildCount();

                    for (int i = 0; i < count; i++) {
                        LinearLayout ll = (LinearLayout) ((LinearLayout) mHoriZontalScrollVw.getChildAt(0)).getChildAt(i);
                        final View child = (ll.getChildAt(0));
                        childViewWidth = child.getWidth();
                        break;
                    }
                }
            });
    }

    @Override
    public void onClick(View v) {
        switch (v.getId()) {
            case R.id.home_screen_refresh_imgVw_imgVw:
                restartFlipperWithNewData();
                isRefreshed = true;
                break;
            case R.id.home_screen_profile_image_imgVw:
                if (mFragmentFramLyt.isShown()) {
                    viewState = 0;
                    mFragmentFramLyt.setVisibility(View.INVISIBLE);
                } else {
                    viewState = 2;
                    mFragmentFramLyt.setVisibility(View.VISIBLE);
                    if (getFragmentManager().getBackStackEntryCount() == 0)
                        switchFragments(new SettingFragment());
                }
                break;
            case R.id.home_screen_popups_frame_lyt:
                viewState = 0;
                mFragmentFramLyt.setVisibility(View.INVISIBLE);
                break;

            case R.id.home_screen_search_imgVw:
                if (isKeyBoardOpen)
                    Utility.hideKeyboard(this);
                mImgVwSearchIcon.setImageResource(R.drawable.icon_search);
                mEdtTxtSearch.setText("");
                mSearchResList.clear();
                if (mSearchAdapter != null)
                    mSearchAdapter.notifyDataSetChanged();
                mListVwSearchResults.setVisibility(View.GONE);
                flipView.setVisibility(View.VISIBLE);
                mLnrLytShowPaging.setVisibility(View.VISIBLE);

                break;
            case R.id.home_screen_left_paging_imgVw:
                mHoriZontalScrollVw.smoothScrollTo(mHoriZontalScrollVw.getScrollX() - childViewWidth, 0);
                break;
            case R.id.home_screen_right_paging_imgVw:
                mHoriZontalScrollVw.smoothScrollTo(mHoriZontalScrollVw.getScrollX() + childViewWidth, 0);

                break;

            default:
                break;
        }
    }

    @Override
    public void onComplete(IModel object, String message, boolean status) {
        if (object != null && object instanceof AdvtMainModle)
            mAdvtData = ((AdvtMainModle) object);
        getArticlesAsync();

    }

    @Override
    public void onComplete(ArrayList<IModel> object, String message, boolean status) {
        isServiceAlreadyRunning = false;
        if (object.get(0) instanceof Error) {
            if (startPaging == 1)
                Utility.showToastMessage(this, ((Error) object.get(0)).getError());
        } else {
            if (startPaging == 1) {
                if (isDataFechingAuto) {
                    startPaging--;
                    isDataFechingAuto = false;
                } else {

                    mArticleList.clear();
                    mArticleList.addAll(object);
                }
            } else {
                mArticleList.addAll(object);
                isDataFechingAuto = false;
            }
            setFlipView();
            startPaging++;
        }
    }

    public int getPageNumber() {
        int size = mArticleList.size();
        int pageNumber = ((int) (size / 15)) * 4;
        if (size % 15 > 0) {
            int temp = size % 15;
            if (temp > 9)
                pageNumber = pageNumber + 4;
            else if (temp > 4)
                pageNumber = pageNumber + 3;
            else if (temp > 3)
                pageNumber = pageNumber + 2;
            else
                pageNumber = pageNumber + 1;
        }
        return pageNumber;
    }

    public void setSearchAdapter() {
        if (mSearchAdapter == null) {
            mSearchAdapter = new SearchArticleAdapter(this, mSearchResList, this);
            mSearchAdapter.updateIsMagazineData(magazineData);
            mListVwSearchResults.setAdapter(mSearchAdapter);
            mListVwSearchResults.setVisibility(View.VISIBLE);
        } else {
            mSearchAdapter.updateIsMagazineData(magazineData);
            mSearchAdapter.notifyDataSetChanged();
            mListVwSearchResults.setVisibility(View.VISIBLE);
        }
    }

    public void switchFragments(Fragment changeLangFragment) {
        if (myFrame == null) {
            myFrame = new FrameLayout(this);
            myFrame.setId(CONTENT_VIEW_ID);
            myFrame.setLayoutParams(new FrameLayout.LayoutParams(LayoutParams.FILL_PARENT, LayoutParams.FILL_PARENT));
            mFragmentFramLyt.addView(myFrame);
        }

        getFragmentManager().beginTransaction().replace(CONTENT_VIEW_ID, changeLangFragment).addToBackStack(changeLangFragment.getClass().toString()).commit();
    }

    public void popFragmentAndHideLyt() {
        getFragmentManager().popBackStack();
        mFragmentFramLyt.setVisibility(View.GONE);
    }

    private void restartFlipperWithNewData() {
        startPaging = 1;
        mArticleList.clear();
        mAdvtData = null;
        mPagesList.clear();
        mLnrLytPagingLyt.removeAllViews();
        if (mTabletFlipAdapter != null) {
            mTabletFlipAdapter.notifyDataSetChanged();
        }
        mTabletFlipAdapter = null;
        mLnrLytPagingLyt.removeAllViews();
        getAdvertiseAsync();
    }

    @Override
    public void showHidePagingSlider(boolean showPage) {
        if (!showPage)
            mLnrLytShowPaging.setVisibility(View.GONE);
        else
            mLnrLytShowPaging.setVisibility(View.VISIBLE);
    }

    @Override
    public void launchMagOrOPenAricleData(IModel data, boolean isOpenArticle) {
        mFragmentFramLyt.setVisibility(View.INVISIBLE);
        if (isOpenArticle) {
            magazineData = null;
            isMagzinData = false;
        } else if (data != null) {
            magazineData = (MagazineModle) data;
            isMagzinData = true;
        }
        updateHeader();
        restartFlipperWithNewData();
    }

    @Override
    public void onImageUpdate(String imagepath) {
        mImageLoader.displayImage(imagepath, mImgVwProfileIcon, Utility.getProfilePicDisplayOption());
    }

    @Override
    public void onBackPressed() {
        if (mFragmentFramLyt.isShown() && getFragmentManager().getBackStackEntryCount() > 0) {
            if (getFragmentManager().getBackStackEntryCount() == 1)
                mFragmentFramLyt.setVisibility(View.GONE);
            super.onBackPressed();
        } else if (isCoverVisible && openMagazine) {
            isCoverVisible = false;
            openMagazine = false;
            if (getString(R.string.device_type).equalsIgnoreCase("ph"))
                startActivity(new Intent(this, HomeActivity.class));
            else
                startActivity(new Intent(this, TabletHomeActivity.class));
            finish();
        } else
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

    /**
     * @param mArticlePos : position of artcle in list
     * @param launchFrom  : -- true when called from article view, false when called from the searchlist
     * @param count       -- updated commentcount
     */
    public void updateAricleDataComment(int mArticlePos, boolean launchFrom, String count) {
        try {
            if (launchFrom) {
                if (mArticleList != null && mArticleList.size() > mArticlePos)
                    ((ArticleModle) mArticleList.get(mArticlePos)).setCommentCount(count);
            } else {
                if (mSearchResList != null && mSearchResList.size() > mArticlePos)
                    ((ArticleModle) mSearchResList.get(mArticlePos)).setCommentCount(count);
            }
        } catch (Exception e) {
            e.printStackTrace();
        }

    }

    @Override
    public void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        Log.i("resultCode", String.valueOf(resultCode));
        // code for handelling the result received when loggingin via G+
        if (requestCode == 9000 && resultCode == -1) {
            // g plus disconnect and reconnect
            Log.e("mslz", "inside on activity result");
            mUpdateResult.updateUI(requestCode, resultCode, data);

        } else if (mUpdateResult != null) {
            mUpdateResult.updateUI(requestCode, resultCode, data);
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
                    if (object instanceof Error) {
//                        Utility.showToastMessage(LoginActivity.this, ((Error) object).getError());
                    } else {
//                        Utility.setSharedPrefStringData(LoginActivity.this, Constants.ARTICLE_LANGUAGE, ((TopicsAndLanguage) object).getArticle_language());
//                        Utility.setSharedPrefStringData(LoginActivity.this, Constants.USER_TOPICS, ((TopicsAndLanguage) object).getCategory());
//                        Utility.setSharedPrefStringData(LoginActivity.this, Constants.APP_LANGUAGE, ((TopicsAndLanguage) object).getAppLanguage());
//                        getLandingPageDataAsync();
                    }
                }

            };

            new ServerIntractorAsync(this, getString(R.string.please_wait_lbl), false,
                    APIUtils.getBaseURL(APIUtils.POST_MAGAZINE_ACCESS), getAccessJsonParams(magazineId, articleId, typeDevice), REQUEST_TYPE.POST, accessAsyncCaller,
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
            json.put("latitude", String.valueOf(mGpsTracker.getLatitude()));
            json.put("longitude", String.valueOf(mGpsTracker.getLongitude()));
            Log.d("mslz", json.toString());
            return json;
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return null;

    }

    public interface SocialCallback {
        public void onDone(JSONObject obj);
    }

    private class SearchAsyncTask extends AsyncTask<Void, Void, String> {
        ArrayList<IModel> tempArrayList = new ArrayList<IModel>();
        String seachSring = "";

        public SearchAsyncTask(String trim) {
            seachSring = trim;
        }

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            Log.e("asd as", "on pre");
        }

        @Override
        protected String doInBackground(Void... params) {
            // , topics<1,2,3>, articleLanguage<en, sp>
            try {
                JSONObject json = new JSONObject();
                json.put("searchKeyword", seachSring);
                // searchKeyword,
                json.put("token", Utility.getSharedPrefStringData(TabletHomeActivity.this, Constants.USER_TOKEN));
                json.put("appLanguage", Utility.getDrfaultLanguage());
                if (magazineData != null) {
                    json.put("magazineId", magazineData.getMagazineId());
                } else {
                    json.put("topics", Utility.getSharedPrefStringData(TabletHomeActivity.this, Constants.USER_TOPICS));
                    json.put("articleLanguage", Utility.getSharedPrefStringData(TabletHomeActivity.this, Constants.ARTICLE_LANGUAGE));
                }
                String response = Utility.httpPostRequestToServer(APIUtils.getBaseURL(magazineData == null ? APIUtils.SEARCH_OPEN_ARTICLES : APIUtils.SEARCH_MAGAZINES_ARTICLES), json,
                        TabletHomeActivity.this);
                tempArrayList.addAll(new ArticesParser(/*
                                                         * magazineData == null ?
														 */true/* : false */, 2).parse(response));
            } catch (JSONException e) {
                e.printStackTrace();
            }
            return null;
        }

        @Override
        protected void onPostExecute(String result) {
            super.onPostExecute(result);
            if (tempArrayList.size() > 0 && tempArrayList.get(0) instanceof Error) {
                mSearchResList.clear();
                if (mSearchAdapter != null)
                    mSearchAdapter.notifyDataSetChanged();
            } else {
                mSearchResList.clear();
                if (mSearchAdapter != null)
                    mSearchAdapter.notifyDataSetChanged();
                mSearchResList.addAll(tempArrayList);
                setSearchAdapter();
            }
        }
    }

    /* Guesture listner class */
    class MyGestureDetector extends SimpleOnGestureListener {

        @Override
        public boolean onFling(MotionEvent e1, MotionEvent e2, float velocityX, float velocityY) {

            System.out.println("in gesture recognizer !!!");

            try {
                if (Math.abs(e1.getY() - e2.getY()) > SWIPE_MAX_OFF_PATH)
                    return false;
                // right to left swipe
                if (e1.getX() - e2.getX() > SWIPE_MIN_DISTANCE && Math.abs(velocityX) > SWIPE_THRESHOLD_VELOCITY) {
                    /*
                     * Toast.makeText(HomeActivity.this, "Left Swipe", Toast.LENGTH_SHORT).show();
					 */
                    TranslateAnimation moveLeftFromRight = new TranslateAnimation(0, (-1) * width, 0, 0);
                    moveLeftFromRight.setDuration(500);
                    moveLeftFromRight.setFillAfter(true);
                    ivCoverPage.startAnimation(moveLeftFromRight);

                    new Handler().postDelayed(new Runnable() {

                        @Override
                        public void run() {
                            // TODO Auto-generated method stub
                            ivCoverPage.setVisibility(View.GONE);
                            ivCoverPage.setOnTouchListener(null);
                            openPage = false;
                            isCoverHide = true;
                            isCoverVisible = false;
                        }
                    }, 500);

                } else if (e2.getX() - e1.getX() > SWIPE_MIN_DISTANCE && Math.abs(velocityX) > SWIPE_THRESHOLD_VELOCITY) {
                    /*
                     * Toast.makeText(HomeActivity.this, "Right Swipe", Toast.LENGTH_SHORT).show();
					 */
                }
            } catch (Exception e) {
                // nothing
            }
            return false;
        }

        @Override
        public boolean onDown(MotionEvent e) {
            return true;
        }

    }
}
