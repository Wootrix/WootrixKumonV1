package com.ta.wootrix.phone;

import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.Color;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Build;
import android.os.Bundle;
import android.os.Handler;
import android.support.annotation.NonNull;
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
import android.widget.HorizontalScrollView;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

import com.google.android.gms.common.ConnectionResult;
import com.google.android.gms.common.api.GoogleApiClient;
import com.nostra13.universalimageloader.core.ImageLoader;
import com.ta.wootrix.R;
import com.ta.wootrix.adapter.HomeFlipViewAdapter;
import com.ta.wootrix.adapter.SearchArticleAdapter;
import com.ta.wootrix.asynctask.IAsyncCaller;
import com.ta.wootrix.asynctask.ServerIntractorAsync;
import com.ta.wootrix.firebase.ArticleDetailActivityTwo;
import com.ta.wootrix.firebase.StorePreference;
import com.ta.wootrix.modle.AdvertisementModle;
import com.ta.wootrix.modle.AdvtMainModle;
import com.ta.wootrix.modle.ArticleModle;
import com.ta.wootrix.modle.Error;
import com.ta.wootrix.modle.IModel;
import com.ta.wootrix.modle.MagazineModle;
import com.ta.wootrix.parser.AdvrtParser;
import com.ta.wootrix.parser.ArticesParser;
import com.ta.wootrix.parser.ArticleParser;
import com.ta.wootrix.parser.MessageParser;
import com.ta.wootrix.restapi.APIUtils;
import com.ta.wootrix.restapi.APIUtils.REQUEST_TYPE;
import com.ta.wootrix.tablet.TabletHomeActivity;
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

public class HomeActivity extends AppCompatActivity implements OnClickListener, IAsyncCaller, GoogleApiClient.OnConnectionFailedListener,
        AccessRegisterListener {
    private static final int REQ_CODE_SETTINGS = 42;
    private static final int SWIPE_MIN_DISTANCE = 120;
    private static final int SWIPE_MAX_OFF_PATH = 250;
    private static final int SWIPE_THRESHOLD_VELOCITY = 200;
    private static HomeActivity instance;
    protected AsyncTask<Void, Void, String> mSearchAsync;
    int width;
    int height;
    View.OnTouchListener gestureListener;
    boolean isOpened = false;
    boolean isKeyBoardOpen;
    ArrayList<TextView> mPagesList = new ArrayList<TextView>();
    boolean isClicked = false;
    int childViewWidth = 0;
    private FlipView flipView;
    private ImageView mImgVwHeaderTopBanner;
    private EditText mEdtTxtSearch;
    private ImageView mImgVwSearchIcon;
    private ImageView mImgVwProfileIcon;
    private ImageView mImgVwLeftPageSlide;

    private ImageView mImgVwRightPageSlide;
    private LinearLayout mLnrLytShowPaging;
    private LinearLayout mLnrLytPagingLyt;
    private HorizontalScrollView mHoriZontalScrollVw;
    private LayoutInflater mLytInflater;
    private MagazineModle magazineData;
    private int startPaging = 1;
    private ImageLoader mImageLoader;
    private LinearLayout mLnrLytHeader;
    private ArrayList<IModel> mArticleList = new ArrayList<IModel>();
    private ArrayList<IModel> mSearchResList = new ArrayList<IModel>();
    private HomeFlipViewAdapter mHomeFlipAdapter;
    private SearchArticleAdapter mSearchAdapter;
    private GPSTracker mGPSTracker;
    private boolean isRefreshed = false;
    private boolean isMagzinData = false;
    private boolean isOnFirstPage = true;
    private boolean openMagazine = false;
    private boolean isCoverHide = false;
    private boolean openPage = false;
    private ImageView ivCoverPage;
    private GestureDetector gestureDetector;
    private int mBlueColor;
    private int mBlakisColor;
    private ListView mListVwSearchResults;
    private boolean isHumanJumpToPosition;
    private ImageView mImgVwRefresh;
    private boolean isDataFechingAuto;
    private boolean isServiceAlreadyRunning;
    private ArrayList<AdvertisementModle> mAdvtData = new ArrayList<AdvertisementModle>();
    private int backPressed;
    private Handler handler = new Handler();

    private boolean showCoverPage = true;

    StorePreference myStorePreference;

    String receivedMessage = null, article_id, notiFurl, messageType;


    String allowShare = null, fullSource = null, allowComment = null, embedded_video = null, title = null,
            createdBy = null, articleDesc = null, articleVideoUrl = null, coverPhotoUrl = null, articleType = null, artArticle_id = null, close_subtype = null;

    public static HomeActivity getInstance() {
        return instance;
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {

        super.onCreate(savedInstanceState);
        setContentView(R.layout.home_screen);

        setListnerToRootView();
        mGPSTracker = new GPSTracker(this);
        instance = this;

        if (getIntent().getExtras() != null && getIntent().getExtras().containsKey("magazineData")) {
            magazineData = getIntent().getExtras().getParcelable("magazineData");
            isMagzinData = true;
        }

        if (getIntent().getExtras() != null && getIntent().getExtras().containsKey("showInputDialog")) {
            Intent intentSettings = new Intent(this, SettingActivity.class);
            intentSettings.putExtra("showInputDialog", true);

            if( getIntent().getExtras().containsKey("deepArticleId") ){
                intentSettings.putExtra("deepArticleId", getIntent().getStringExtra("deepArticleId"));
            }

            if( getIntent().getExtras().containsKey("deepMagazineId") ){
                intentSettings.putExtra("deepMagazineId", getIntent().getStringExtra("deepMagazineId"));
            }

            startActivityForResult(intentSettings, REQ_CODE_SETTINGS);
        }

        mLytInflater = getLayoutInflater();
        mBlueColor = getResources().getColor(R.color.color_blue);
        mBlakisColor = getResources().getColor(R.color.black_heading);
        if (mImageLoader == null)
            mImageLoader = Utility.getImageLoader(this);
        initViews();

        receivingIntent();


        if (messageType != null) {
            Utility.toastMessage(this, messageType);
        }

        getAdvertiseAsync();

    }


    private void receivingIntent() {

        Log.d("Home LOG", "Home LOG");
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

    // listener for listening the keyboard events and doing tasks on them
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
    protected void onDestroy() {
        super.onDestroy();
        if (mGPSTracker != null)
            mGPSTracker.stopUsingGPS();
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
        // <mobile/tablet/web>, topics<1,2,3>, articleLanguage<en, sp>,
        // <1,2,3,4>

        // params : platform<mobile/tablet/web>,
        // magazineId
        try {
            if (magazineData == null) {
                JSONObject json = new JSONObject();
                json.put("platform", "mobile");
                json.put("topics", Utility.getSharedPrefStringData(this, Constants.USER_TOPICS));
                json.put("articleLanguage", Utility.getSharedPrefStringData(this, Constants.ARTICLE_LANGUAGE));
                json.put("token", Utility.getSharedPrefStringData(this, Constants.USER_TOKEN));
                json.put("appLanguage", Utility.getDrfaultLanguage());
                json.put("layoutId", "");
                return json;

            } else {
                JSONObject json = new JSONObject();
                json.put("platform", "mobile");
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
        Log.d("HomeResume", "HomeResume");
        if (Utility.getSharedPrefBooleanData(this, Constants.USER_SHOW_PAGE))
            mLnrLytShowPaging.setVisibility(View.GONE);
        else
            mLnrLytShowPaging.setVisibility(View.VISIBLE);

        mImageLoader.displayImage(Utility.getSharedPrefStringData(this, Constants.USER_IMAGE), mImgVwProfileIcon, Utility.getProfilePicDisplayOption());

        calculateChildWidth();

    }

    @Override
    protected void onPause() {
        super.onPause();
        //  receivingIntent();
        Log.d("HomePause", "HomePause");
    }

    private void getArticlesAsync() {
        if (Utility.isNetworkAvailable(this)) {
            isServiceAlreadyRunning = true;

            new ServerIntractorAsync(this, getString(R.string.please_wait_lbl), startPaging == 1 ? true : false,
                    magazineData == null ? APIUtils.getBaseURL(APIUtils.GET_OPEN_ARTICLES) : APIUtils.getBaseURL(APIUtils.GET_MAGAZINE_ARTICLES), getJsonParams(), REQUEST_TYPE.POST, this,
                    new ArticesParser(magazineData == null ? true : false, 0)).execute();
        } else {
            Utility.showNetworkNotAvailToast(this);
        }
    }

    private JSONObject getJsonParams() {
        // <tablet, web, >, , topics<1,2,3>, <en, sp>
        try {
            JSONObject json = new JSONObject();
            json.put("deviceType", "mobile");
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

                    Log.e("click is working", "click is working");
                    isClicked = true;
                    int pos = (Integer) v.getTag();
                    isHumanJumpToPosition = true;
                    updateTextColor(pos);
                    flipView.flipTo(pos);
                    mHomeFlipAdapter.notifyDataSetChanged();

                    if (startPaging <= ((ArticleModle) mArticleList.get(0)).getTotalPages() && pos > getPageNumber() - 4 && !isServiceAlreadyRunning) {
                        isDataFechingAuto = true;
                        getArticlesAsync();
                    }
                    isClicked = false;
                }
            });
            mLnrLytPagingLyt.addView(v);
        }
        if (startPaging == 1)
            updateTextColor(0);
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

        flipView = (FlipView) findViewById(R.id.home_screen_flipview);
        flipView.setOverFlipMode(OverFlipMode.RUBBER_BAND);
        myStorePreference = new StorePreference(this);

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

                } else {
                    isOnFirstPage = false;
                }

                try {
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

        mEdtTxtSearch.setOnTouchListener(new OnTouchListener() {
            @Override
            public boolean onTouch(View v, MotionEvent event) {
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

                    if (Utility.isNetworkAvailable(HomeActivity.this)) {
                        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.HONEYCOMB) {
                            mSearchAsync = new SearchAsyncTask(s.toString().trim()).executeOnExecutor(AsyncTask.THREAD_POOL_EXECUTOR);
                        } else {
                            mSearchAsync = new SearchAsyncTask(s.toString().trim()).execute();

                        }
                    } else
                        Utility.showNetworkNotAvailToast(HomeActivity.this);

                } else {
                    mSearchResList.clear();
                    if (mSearchAdapter != null)
                        mSearchAdapter.notifyDataSetChanged();
                }
            }

            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {

            }

            @Override
            public void afterTextChanged(Editable s) {

            }
        });

        ivCoverPage = (ImageView) findViewById(R.id.home_screen_cover_page);

        updateHeader();

    }

    public void updateCover() {
        if (isMagzinData && mArticleList.size() > 0 && showCoverPage) {
            /* show magazine cover page */
            Display mDisplay = getWindowManager().getDefaultDisplay();
            width = mDisplay.getWidth();
            height = mDisplay.getHeight();

            String cover_url = ((ArticleModle) mArticleList.get(0)).getCoverImageMag() + getImageSize();
            Log.i("TAG", "cover image url: " + cover_url);
            if (cover_url != null && !cover_url.equalsIgnoreCase(""))
                mImageLoader.displayImage(cover_url, ivCoverPage, Utility.getMagazinesCoverDisplayOption());
            ivCoverPage.setVisibility(View.VISIBLE);
            showCoverPage = false;

			/* make visible to cover page */
            if (isCoverHide) {
                TranslateAnimation moveLeftFromRight = new TranslateAnimation((-1) * width, 0, 0, 0);
                moveLeftFromRight.setDuration(100);
                moveLeftFromRight.setFillAfter(true);
                ivCoverPage.startAnimation(moveLeftFromRight);
                isCoverHide = false;
            }
            if (startPaging == 1) {
                setSwipeOnImage();
            }
        } else {
            ivCoverPage.setVisibility(View.GONE);
        }
    }

    public String getImageSize() {
        String size = "";
        if (/* width > 400 && */width <= 600) {
            size = size + "480800.png";
        } else if (width > 600 && width <= 900) {
            size = size + "7201280.png";
        }
        if (width > 900) {
            size = size + "10801920.png";
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
        // iv.setVisibility(View.VISIBLE);
        if (isMagzinData) {
            ivCoverPage.setVisibility(View.VISIBLE);
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
                Utility.setSharedPrefBooleanData(HomeActivity.this, Constants.isMagzineAvailable, true);
                startActivity(new Intent(HomeActivity.this, LandingPageActivity.class));
                finish();
            }
        });
    }

    public void scrollScrollViewOnPageChange() {
        mHoriZontalScrollVw.scrollTo(childViewWidth * flipView.getCurrentPage(), 0);
    }

    private void setFlipView() {

        int pageNumber = getPageNumber();

        if (notiFurl != null) {
            if (messageType.equals("advertisement")) {
                if (mHomeFlipAdapter == null) {
                    mHomeFlipAdapter = new HomeFlipViewAdapter(this, mArticleList, pageNumber, mAdvtData, mGPSTracker, magazineData, this);
                    flipView.setAdapter(mHomeFlipAdapter);
                } else {
                    mHomeFlipAdapter.updateMagazineData(magazineData);
                    mHomeFlipAdapter.updatePageNumber(getPageNumber());
                    mHomeFlipAdapter.notifyDataSetChanged();
                }
                checkAdvertisementUrl(notiFurl);
            } else if (messageType.equals("article")) {
                if (mHomeFlipAdapter == null) {
                    mHomeFlipAdapter = new HomeFlipViewAdapter(this, mArticleList, pageNumber, mAdvtData, mGPSTracker, magazineData, this);
                    flipView.setAdapter(mHomeFlipAdapter);
                } else {
                    mHomeFlipAdapter.updateMagazineData(magazineData);
                    mHomeFlipAdapter.updatePageNumber(getPageNumber());
                    mHomeFlipAdapter.notifyDataSetChanged();
                }
                checkURLHaveOrNot(notiFurl, allowShare, fullSource, allowComment, embedded_video, title, createdBy, articleDesc, articleVideoUrl, coverPhotoUrl, articleType, artArticle_id);
            } else if (messageType.equals("closemagazine")) {
                if (close_subtype.equals("close_advertisement")) {
                    Log.d("With advertisement URL", messageType);
                    if (mHomeFlipAdapter == null) {
                        mHomeFlipAdapter = new HomeFlipViewAdapter(this, mArticleList, pageNumber, mAdvtData, mGPSTracker, magazineData, this);
                        flipView.setAdapter(mHomeFlipAdapter);
                    } else {
                        mHomeFlipAdapter.updateMagazineData(magazineData);
                        mHomeFlipAdapter.updatePageNumber(getPageNumber());
                        mHomeFlipAdapter.notifyDataSetChanged();
                    }
                    checkCloseMagAdvHaveOrNot(notiFurl);
                } else if (close_subtype.equals("close_article")) {
                    Log.d("With article URL", messageType);
                    if (mHomeFlipAdapter == null) {
                        mHomeFlipAdapter = new HomeFlipViewAdapter(this, mArticleList, pageNumber, mAdvtData, mGPSTracker, magazineData, this);
                        flipView.setAdapter(mHomeFlipAdapter);
                    } else {
                        mHomeFlipAdapter.updateMagazineData(magazineData);
                        mHomeFlipAdapter.updatePageNumber(getPageNumber());
                        mHomeFlipAdapter.notifyDataSetChanged();
                    }
                    checkCloseMagHaveOrNot(notiFurl, allowShare, fullSource, allowComment, embedded_video, title, createdBy, articleDesc, articleVideoUrl, coverPhotoUrl, articleType, artArticle_id);
                }
            }
        } else {
            Log.d("Without URL", "Without URL");
            if (mHomeFlipAdapter == null) {
                mHomeFlipAdapter = new HomeFlipViewAdapter(this, mArticleList, pageNumber, mAdvtData, mGPSTracker, magazineData, this);
                flipView.setAdapter(mHomeFlipAdapter);
            } else {
                mHomeFlipAdapter.updateMagazineData(magazineData);
                mHomeFlipAdapter.updatePageNumber(getPageNumber());
                mHomeFlipAdapter.notifyDataSetChanged();
            }
        }
        if (close_subtype == null) {
            if (!isRefreshed)
                updateCover();
            else
                isRefreshed = false;

            initalisePages();
            calculateChildWidth();
        } else if (close_subtype.equals("closemagazine")) {
            if (!isRefreshed)
                updateCover();
            else
                isRefreshed = false;

            initalisePages();
            calculateChildWidth();
        }
    }

    private void checkCloseMagHaveOrNot(String notiFurl, String allowShare, String fullSource, String allowComment, String embedded_video, String title, String createdBy, String articleDesc, String articleVideoUrl, String coverPhotoUrl, String articleType, String artArticle_id) {

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
                fullSource = null;
                this.startActivity(myIntent);
            } else if (articleType.equals("embedded")) {
                Intent myEmbedIntent = new Intent(getApplicationContext(), EmbeddedArticleDetailActivity.class);
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
                    Uri uri = Uri.parse(articleVideoUrl);
                    intent.setDataAndType(uri, "video/*");
                    this.startActivity(intent);
                } catch (Exception e) {
                    e.printStackTrace();
                }
            }
        }
    }

    private void checkCloseMagAdvHaveOrNot(String closefullSource) {
        Intent myIntent = new Intent(this, AdvertisementViewActivity.class);
        myIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
        myIntent.putExtra("advtURL", closefullSource);
        notiFurl = null;
        myIntent.putExtra("type", "link");
        startActivity(myIntent);
    }

    private void checkAdvertisementUrl(String advUrl) {

        Intent myIntent = new Intent(this, AdvertisementViewActivity.class);
        myIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
        myIntent.putExtra("advtURL", advUrl);
        Log.d("ADV URL", (String) advUrl);
        notiFurl = null;
        myIntent.putExtra("type", "link");
        this.startActivity(myIntent);
    }

    private void checkURLHaveOrNot(String urlString, String allowShare, String fullSource, String allowComment, String embedded_video, String title, String createdBy, String articleDesc, String articleVideoUrl, String coverPhotoUrl, String articleType, String artArticle_id) {

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
                Intent myEmbedIntent = new Intent(getApplicationContext(), EmbeddedArticleDetailActivity.class);
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

    private void calculateChildWidth() {
        if (childViewWidth == 0)
            mHoriZontalScrollVw.getViewTreeObserver().addOnGlobalLayoutListener(new ViewTreeObserver.OnGlobalLayoutListener() {

                @SuppressWarnings("deprecation")
                @Override
                public void onGlobalLayout() {
                    // Ensure you call it only once :
                    mHoriZontalScrollVw.getViewTreeObserver().removeGlobalOnLayoutListener(this);
                    final int count = ((LinearLayout) mHoriZontalScrollVw.getChildAt(0)).getChildCount();

                    for (int i = 0; i < count; ) {
                        Log.e("Calculated Child Width", childViewWidth + "");
                        LinearLayout ll = (LinearLayout) ((LinearLayout) mHoriZontalScrollVw.getChildAt(0)).getChildAt(0);
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
                startActivityForResult(new Intent(this, SettingActivity.class), REQ_CODE_SETTINGS);
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
            mAdvtData.addAll(((AdvtMainModle) object).getLyt1List());

        showCoverPage = true;
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
            isDataFechingAuto = false;
        }

    }

    public int getPageNumber() {
        int size = mArticleList.size();
        int pageNumber = ((int) (size / 9)) * 2;
        if (size % 9 > 0) {
            int temp = size % 9;
            if (temp > 4) {
                pageNumber = pageNumber + 2;
            } else
                pageNumber = pageNumber + 1;
        }
        return pageNumber;
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        if (resultCode == RESULT_OK && requestCode == REQ_CODE_SETTINGS) {
            if (data != null && data.getExtras().containsKey("magazineData")) {
                magazineData = data.getExtras().getParcelable("magazineData");
                isMagzinData = true;
            } else if (data != null && data.getExtras().containsKey("articleDta")) {
                magazineData = null;
                isMagzinData = false;
            }
            updateHeader();
            restartFlipperWithNewData();

            if( data != null && data.getExtras().containsKey("deepArticleId") ){
                getArticleAsync(magazineData.getMagazineId(), data.getStringExtra("deepArticleId"));
            }

        }
    }

    private void getArticleAsync(final String magazineId, String articleId) {

        if (Utility.isNetworkAvailable(this)) {

            IAsyncCaller articleDetailAsyncCaller = new IAsyncCaller() {

                @Override
                public void onComplete(ArrayList<IModel> object, String message, boolean status) {

                }

                @Override
                public void onComplete(IModel object, String message, boolean status) {

                    ArticleModle deepArticle = (ArticleModle) object;

                    if( deepArticle.getArticleID() != null ){
                        goToDeepArticleDetail(deepArticle, magazineId);
                        return;
                    }

                }

            };

            new ServerIntractorAsync(this, getString(R.string.please_wait_lbl), true,
                    APIUtils.getBaseURL(APIUtils.GET_ARTICLE_DETAIL), getArticleDetailParams(magazineId, articleId), APIUtils.REQUEST_TYPE.POST, articleDetailAsyncCaller,
                    new ArticleParser(true, 0)).execute();

        } else {
            Utility.showNetworkNotAvailToast(this);
            finish();
        }

    }

    private void goToDeepArticleDetail(ArticleModle deepArticle, String magazineId){

        Intent myIntent = new Intent(this, ArticleDetailActivity.class);
        myIntent.putExtra("articleData", deepArticle);
        myIntent.putExtra("isDeepLink", true);
        myIntent.putExtra("launchFrom", true);
        myIntent.putExtra("isMagzine", true);
        myIntent.putExtra("magzineID", magazineId);
        startActivity(myIntent);

        sendAccessData(magazineId, deepArticle.getArticleID(), "Smartphone");

    }

    private JSONObject getArticleDetailParams(String magazineId, String articleId) {

        try {
            JSONObject json = new JSONObject();
            json.put("magazineId", magazineId);
            json.put("articleId", articleId);
            return json;
        } catch (JSONException e) {
            e.printStackTrace();
        }

        return null;

    }

    private void restartFlipperWithNewData() {
        // isDataFechingAuto = false;
        startPaging = 1;
        mArticleList.clear();
        mPagesList.clear();
        mLnrLytPagingLyt.removeAllViews();
        mAdvtData.clear();
        if (mHomeFlipAdapter != null)
            mHomeFlipAdapter.notifyDataSetChanged();
        mLnrLytPagingLyt.removeAllViews();
        getAdvertiseAsync();
    }

    public void setSearchAdapter() {
        if (mSearchAdapter == null) {
            mSearchAdapter = new SearchArticleAdapter(this, mSearchResList, this);
            mSearchAdapter.updateIsMagazineData(magazineData);
            mListVwSearchResults.setAdapter(mSearchAdapter);
            mListVwSearchResults.setVisibility(View.VISIBLE);
        } else {
            mSearchAdapter.notifyDataSetChanged();
            mSearchAdapter.updateIsMagazineData(magazineData);
            mListVwSearchResults.setVisibility(View.VISIBLE);
        }

    }

    @Override
    public void onBackPressed() {

        if (isCoverHide && openMagazine) {
            isCoverHide = false;
            openMagazine = false;
            if (getString(R.string.device_type).equalsIgnoreCase("ph"))
                startActivity(new Intent(this, HomeActivity.class));
            else
                startActivity(new Intent(this, TabletHomeActivity.class));
            finish();
        } else {
            callBackPressed();
        }
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

    public void updateAricleDataCommentUsingId(int mArticleid, boolean launchFrom, String count) {
        try {
            if (launchFrom) {

                ((ArticleModle) mArticleList.get(0)).setCommentCount(count);
            } else {
                if (mSearchResList != null && mSearchResList.size() > mArticleid)
                    ((ArticleModle) mSearchResList.get(0)).setCommentCount(count);
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    @Override
    public void onConnectionFailed(@NonNull ConnectionResult connectionResult) {
        Toast.makeText(this, "Google Play Services Error: " + connectionResult.getErrorCode(),
                Toast.LENGTH_SHORT).show();
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
            json.put("latitude", String.valueOf(mGPSTracker.getLatitude()));
            json.put("longitude", String.valueOf(mGPSTracker.getLongitude()));
Log.d("mslz", json.toString());
            return json;
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return null;

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
                json.put("token", Utility.getSharedPrefStringData(HomeActivity.this, Constants.USER_TOKEN));
                json.put("appLanguage", Utility.getDrfaultLanguage());
                if (magazineData != null) {
                    json.put("magazineId", magazineData.getMagazineId());
                } else {
                    json.put("topics", Utility.getSharedPrefStringData(HomeActivity.this, Constants.USER_TOPICS));
                    json.put("articleLanguage", Utility.getSharedPrefStringData(HomeActivity.this, Constants.ARTICLE_LANGUAGE));
                }
                String response = Utility.httpPostRequestToServer(APIUtils.getBaseURL(magazineData == null ? APIUtils.SEARCH_OPEN_ARTICLES : APIUtils.SEARCH_MAGAZINES_ARTICLES), json,
                        HomeActivity.this);
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
                    TranslateAnimation moveLeftFromRight = new TranslateAnimation(0, (-1) * width, 0, 0);
                    moveLeftFromRight.setDuration(500);
                    moveLeftFromRight.setFillAfter(true);
                    ivCoverPage.startAnimation(moveLeftFromRight);

                    new Handler().postDelayed(new Runnable() {

                        @Override
                        public void run() {
                            ivCoverPage.setVisibility(View.GONE);
                            ivCoverPage.setOnTouchListener(null);
                            openPage = false;
                            isCoverHide = true;
                        }
                    }, 500);

                } else if (e2.getX() - e1.getX() > SWIPE_MIN_DISTANCE && Math.abs(velocityX) > SWIPE_THRESHOLD_VELOCITY) {
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