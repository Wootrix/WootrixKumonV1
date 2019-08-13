// package com.ta.wootrix.adapter;
//
// import java.util.HashMap;
// import java.util.Stack;
//
// import android.annotation.SuppressLint;
// import android.app.AlertDialog;
// import android.app.NotificationManager;
// import android.content.Context;
// import android.content.DialogInterface;
// import android.content.Intent;
// import android.content.res.AssetManager;
// import android.net.Uri;
// import android.os.Build;
// import android.os.Bundle;
// import android.support.v4.app.Fragment;
// import android.support.v4.app.FragmentActivity;
// import android.support.v4.app.FragmentManager;
// import android.support.v4.app.FragmentTabHost;
// import android.support.v4.app.FragmentTransaction;
// import android.view.MotionEvent;
// import android.view.View;
// import android.view.View.OnTouchListener;
// import android.widget.TabHost;
// import android.widget.TabHost.OnTabChangeListener;
// import android.widget.TabWidget;
//
// import com.google.android.gms.ads.AdRequest;
// import com.ta.wootrix.R;
// import com.ta.wootrix.utils.Constants;
// import com.ta.wootrix.utils.Utility;
//
/// **
// * @author ravindmaurya
// *
// *
// *
// */
// public class BaseTabActivity extends FragmentActivity implements OnTabChangeListener
// {
// /* Your Tab host */
// private FragmentTabHost mTabHost;
// public static BaseTabActivity mBaseTabActivity;
// /* A HashMap of stacks, where we use tab identifier as keys.. */
// public static HashMap<String, Stack<Fragment>> mStacks;
//
// public static String mCurrentTab;
// private HomeScreenFragment mHomeScreenFragment;
// private MovieScreenFragment mMovieScreenFragment;
// private QuizScreenFragment mQuizScreenFragment;
// private MyCinemaScreenFragment mCinemaScreenFragment;
// private static boolean activityVisible;
// //public InterstitialAd interstitialAd;
//
// public AdRequest adRequest;
// public static int tabId = 0;
//
// // flurry ads
//
// @Override
// public void onCreate(Bundle savedInstanceState)
// {
// // TODO Auto-generated method stub
// super.onCreate(savedInstanceState);
// setContentView(R.layout.bottom_tabs);
// mBaseTabActivity = this;
//
// mTabHost = (FragmentTabHost) findViewById(android.R.id.tabhost);
// if (savedInstanceState == null)
// {
// mHomeScreenFragment = new HomeScreenFragment();
// mMovieScreenFragment = new MovieScreenFragment();
// mQuizScreenFragment = new QuizScreenFragment();
// mCinemaScreenFragment = new MyCinemaScreenFragment();
// mStacks = new HashMap<String, Stack<Fragment>>();
//
// mStacks.put(Constants.HOMESCREENTAB, new Stack<Fragment>());
// mStacks.put(Constants.QUIZSCREENTAB, new Stack<Fragment>());
// mStacks.put(Constants.MOVIESCREENTAB, new Stack<Fragment>());
// mStacks.put(Constants.CINEMASCREENTAB, new Stack<Fragment>());
// mTabHost.setOnTabChangedListener(this);
//
// }
// else
// {
//
// mHomeScreenFragment = new HomeScreenFragment();
// mMovieScreenFragment = new MovieScreenFragment();
// mQuizScreenFragment = new QuizScreenFragment();
// mCinemaScreenFragment = new MyCinemaScreenFragment();
// mStacks = new HashMap<String, Stack<Fragment>>();
//
// mStacks.put(Constants.HOMESCREENTAB, new Stack<Fragment>());
// mStacks.put(Constants.QUIZSCREENTAB, new Stack<Fragment>());
// mStacks.put(Constants.MOVIESCREENTAB, new Stack<Fragment>());
// mStacks.put(Constants.CINEMASCREENTAB, new Stack<Fragment>());
// mTabHost.setOnTabChangedListener(this);
// mHomeScreenFragment = (HomeScreenFragment) getSupportFragmentManager().findFragmentByTag(
// Constants.HOMESCREENTAB);
// mMovieScreenFragment = (MovieScreenFragment) getSupportFragmentManager().findFragmentByTag(
// Constants.MOVIESCREENTAB);
// mQuizScreenFragment = (QuizScreenFragment) getSupportFragmentManager().findFragmentByTag(
// Constants.QUIZSCREENTAB);
// mCinemaScreenFragment = (MyCinemaScreenFragment) getSupportFragmentManager().findFragmentByTag(
// Constants.CINEMASCREENTAB);
//
//
// }
//
// mTabHost.setup(this, getSupportFragmentManager(), R.id.realtabcontent);
// initializeTabs();
//
// //getAppAdInitializer();
//
// }
//
// // for push
//
// public void setMessage(String[] strings)
// {
// setPushMessage(strings, mBaseTabActivity);
// clearNotificationStatus();
//
// }
//
// public void clearNotificationStatus()
// {
// try
// {
// NotificationManager nm = (NotificationManager) getSystemService(Context.NOTIFICATION_SERVICE);
// nm.cancelAll();
// }
// catch (Exception e)
// {
// }
// }
//
// void setPushMessage(String[] strings, Context context)
// {
// try
// {
// parseMessage(strings, context);
// }
// catch (Exception e)
// {
// e.printStackTrace();
// }
// }
//
// void parseMessage(String[] message, Context context)
// {
// if (message.length > 0)
// {
// createCustomOKPushDialog(message, context, context.getAssets());
// }
// }
//
// void createCustomOKPushDialog(final String[] message, final Context context, AssetManager
// mAssetManager)
// {
//
// showMsgDialog(message[0]);
//
// }
//
// void showMsgDialog(String msg)
// {
// new AlertDialog.Builder(this)
// // Utility.setSharedPrefStringData(this, "notification",
// // realMsg);
// .setMessage(msg)
// .setTitle(getResources().getString(R.string.lbl_update))
// .setIcon(R.drawable.appicon)
// .setCancelable(false)
// .setPositiveButton(this.getResources().getString(R.string.lbl_ok),
// new DialogInterface.OnClickListener()
// {
// public void onClick(DialogInterface dialog, int whichButton)
// {
//
// dialog.dismiss();
//
// Intent i = new Intent(Intent.ACTION_VIEW);
// i.setData(Uri.parse("https://play.google.com/store"));
// startActivity(i);
//
// finish();
//
// // pushFragments(Constants.NOTIFICATION, new
// // NotificationFragmentActivity(), true, true);
//
// }
// }).create().show();
//
// }
//
// public boolean isActivityVisible()
// {
// return activityVisible;
// }
//
// // private void getAppAdInitializer()
// // {
// //
// // interstitialAd = new InterstitialAd(this);
// // interstitialAd.setAdUnitId(Constants.AD_UNIT_ID_INT);
// // adRequest = new AdRequest.Builder()
// // /* .addTestDevice(androidId) */.build();
// //
// // interstitialAd.setAdListener(new AdListener()
// // {
// // @Override
// // public void onAdLoaded()
// // {
// //
// // interstitialAd.show();
// // }
// //
// // @Override
// // public void onAdFailedToLoad(int errorCode)
// // {
// // // TODO Auto-generated method stub
// // super.onAdFailedToLoad(errorCode);
// // }
// // });
// //
// // }
//
// @SuppressLint("NewApi")
// private void initializeTabs()
// {
// /* Setup your tab icons and content views.. Nothing special in this.. */
// // home Tab
//
// Bundle bundle = new Bundle();
// bundle.putString("key", Constants.HOMESCREENTAB);
// TabHost.TabSpec spec = mTabHost.newTabSpec(Constants.HOMESCREENTAB);
// spec.setContent(new TabHost.TabContentFactory()
// {
// public View createTabContent(String tag)
// {
// return findViewById(R.id.realtabcontent);
// }
// });
//
// /* mTabHost.addTab(mTabHost.newTabSpec("setting").setIndicator(""),
// SettingFragmentActivity.class, null);
//
// mTabHost.getTabWidget().getChildAt(0)
// .setBackgroundResource(R.drawable.tabbar_notification);*/
//
// //spec.setIndicator(getString(R.string.home_tab),
// getResources().getDrawable(R.drawable.hometab_selector));
// mTabHost.addTab(
// mTabHost.newTabSpec(getResources().getString(R.string.home_tab)).setIndicator(
// getResources().getString(R.string.home_tab)), HomeScreenFragment.class, bundle);
// mTabHost.getTabWidget().getChildAt(0).setBackground(getResources().getDrawable(R.drawable.hometab_selector));
//
// // movies Tab
// bundle = new Bundle();
// bundle.putString("key", Constants.MOVIESCREENTAB);
// spec = mTabHost.newTabSpec(Constants.MOVIESCREENTAB);
//
// spec.setContent(new TabHost.TabContentFactory()
// {
// public View createTabContent(String tag)
// {
// return findViewById(R.id.realtabcontent);
// }
// });
// /*spec.setIndicator(getString(R.string.movie_tab),
// getResources().getDrawable(R.drawable.movietab_selector));
// mTabHost.addTab(spec, MovieScreenFragment.class, bundle);*/
//
// mTabHost.addTab(
// mTabHost.newTabSpec(getResources().getString(R.string.movie_tab)).setIndicator(
// getResources().getString(R.string.movie_tab)), MovieScreenFragment.class, bundle);
// mTabHost.getTabWidget().getChildAt(1).setBackground(getResources().getDrawable(R.drawable.movietab_selector));
//
// // quiz Tab
// bundle = new Bundle();
// bundle.putString("key", Constants.QUIZSCREENTAB);
// spec = mTabHost.newTabSpec(Constants.QUIZSCREENTAB);
//
// spec.setContent(new TabHost.TabContentFactory()
// {
// public View createTabContent(String tag)
// {
// return findViewById(R.id.realtabcontent);
// }
// });
// /*spec.setIndicator(getString(R.string.quiz_tab),
// getResources().getDrawable(R.drawable.quiztab_selector));
// mTabHost.addTab(spec, QuizScreenFragment.class, bundle);*/
//
// mTabHost.addTab(
// mTabHost.newTabSpec(getResources().getString(R.string.quiz_tab)).setIndicator(
// getResources().getString(R.string.quiz_tab)), QuizScreenFragment.class, bundle);
// mTabHost.getTabWidget().getChildAt(2).setBackground(getResources().getDrawable(R.drawable.quiztab_selector));
//
// // movies Tab
// bundle = new Bundle();
// bundle.putString("key", Constants.CINEMASCREENTAB);
// spec = mTabHost.newTabSpec(Constants.CINEMASCREENTAB);
//
// spec.setContent(new TabHost.TabContentFactory()
// {
// public View createTabContent(String tag)
// {
// return findViewById(R.id.realtabcontent);
// }
// });
// // spec.setIndicator(getString(R.string.cinema_tab),
// getResources().getDrawable(R.drawable.cinematab_selector));
// // mTabHost.addTab(spec, MyCinemaScreenFragment.class, bundle);
//
// mTabHost.addTab(
// mTabHost.newTabSpec(getResources().getString(R.string.cinema_tab)).setIndicator(
// getResources().getString(R.string.cinema_tab)), MyCinemaScreenFragment.class, bundle);
// mTabHost.getTabWidget().getChildAt(3).setBackground(getResources().getDrawable(R.drawable.cinematab_selector));
//
// mTabHost.getTabWidget().setStripEnabled(false);
// mTabHost.getTabWidget().setDividerDrawable(null);
// mTabHost.getTabWidget().setFadingEdgeLength(0);
//
// if (Integer.parseInt(Build.VERSION.SDK) >= Build.VERSION_CODES.HONEYCOMB)
// {
// mTabHost.getTabWidget().setShowDividers(TabWidget.SHOW_DIVIDER_NONE);
// }
// if (getIntent().getBooleanExtra("toMovieScreen", false))
// {
// tabId = 1;
// }
// else
// if (getIntent().getBooleanExtra("toCinemaScreen", false))
// {
// tabId = 3;
// }
// else
// if (getIntent().getBooleanExtra("toQuizScreen", false))
// {
// tabId = 2;
// }
// else
// {
// tabId = 0;
// }
// mTabHost.setCurrentTab(tabId);
//
// mTabHost.getTabWidget().getChildAt(3).setOnTouchListener(new OnTouchListener()
// {
//
// @Override
// public boolean onTouch(View v, MotionEvent event)
// {
// int action = event.getAction();
//
// if (action == MotionEvent.ACTION_UP)
// {
// if (Utility.getSharedPrefBooleanData(BaseTabActivity.this, Constants.IS_GUEST))
// {
// Utility.showLoginPrompt(BaseTabActivity.this, getString(R.string.lbl_mycinema));
// return true;
// }
//
// /*String currentTabTag = (String)tabHost.getCurrentTabTag();
// String clickedTabTag = (String)v.getTag();
//
// if(clickedTabTag.equals("BAD TAG"))
// {
// return true; // Prevents from clicking
// }*/
//
// }
// return false;
// }
// });
//
// }
//
// public void onTabChange(String tabId)
// {
// mCurrentTab = tabId;
// setCurrentTab(1);
// }
//
// @Override
// protected void onPause()
// {
// super.onPause();
// setActivityVisible(false);
// if (mStacks == null || mStacks.size() == 0)
// finish();
// // SpeechTabFragment.isActivitypaused = true;
//
// }
//
// @Override
// protected void onResume()
// {
// // TODO Auto-generated method stub
// super.onResume();
// setActivityVisible(true);
//
// }
//
// public static void setActivityVisible(boolean activityVisible)
// {
// BaseTabActivity.activityVisible = activityVisible;
// }
//
// @Override
// protected void onActivityResult(int requestCode, int resultCode, Intent data)
// {
// if (mStacks.get(mCurrentTab).size() == 0)
// {
// return;
// }
//
// /* Now current fragment on screen gets onActivityResult callback.. */
// mStacks.get(mCurrentTab).lastElement().onActivityResult(requestCode, resultCode, data);
// }
//
// /*
// * Might be useful if we want to switch tab programmatically, from inside
// * any of the fragment.
// */
// public void setCurrentTab(int val)
// {
// mTabHost.setCurrentTab(val);
// }
//
// @Override
// public void onTabChanged(String tabId)
// {
//
// /* Set current tab.. */
// mCurrentTab = tabId;
// try
// {
// if (mStacks.get(tabId).size() == 0)
// {
// /*
// * First time this tab is selected. So add first fragment of
// * that tab. Dont need animation, so that argument is false. We
// * are adding a new fragment which is not present in stack. So
// * add to stack is true.
// */
// if (tabId.equals(Constants.HOMESCREENTAB))
// {
// pushFragments(tabId, mHomeScreenFragment, false, true);
// }
// else
// if (tabId.equals(Constants.MOVIESCREENTAB))
// {
// pushFragments(tabId, mMovieScreenFragment, false, true);
// }
// else
// if (tabId.equals(Constants.QUIZSCREENTAB))
// {
// pushFragments(tabId, mQuizScreenFragment, false, true);
// }
// else
// if (tabId.equals(Constants.CINEMASCREENTAB))
// {
// /*if (Utility.getSharedPrefBooleanData(this, Constants.IS_GUEST))
// {
// Utility.showLoginPrompt(this, getString(R.string.lbl_mycinema));
// }
// else
// {*/
// pushFragments(tabId, mCinemaScreenFragment, false, true);
// /*}*/
// }
// }
// else
// {
// /*
// * We are switching tabs, and target tab is already has atleast
// * one fragment. No need of animation, no need of stack pushing.
// * Just show the target fragment
// */
// pushFragments(tabId, mStacks.get(tabId).lastElement(), false, false);
// }
// }
// catch (Exception e)
// {
// e.printStackTrace();
// }
//
// }
//
// /*
// * To add fragment to a tab. tag -> Tab identifier fragment -> Fragment to
// * show, in tab identified by tag shouldAnimate -> should animate
// * transaction. false when we switch tabs, or adding first fragment to a tab
// * true when when we are pushing more fragment into navigation stack.
// * shouldAdd -> Should add to fragment navigation stack (mStacks.get(tag)).
// * false when we are switching tabs (except for the first time) true in all
// * other cases.
// */
// public void pushFragments(String tag, Fragment fragment, boolean shouldAnimate, boolean
// shouldAdd)
// {
// if (shouldAdd)
// mStacks.get(tag).push(fragment);
// FragmentManager manager = getSupportFragmentManager();
// FragmentTransaction ft = manager.beginTransaction();
// ft.addToBackStack(null);
// if (shouldAnimate)
// ft.setCustomAnimations(R.anim.slide_in_right, R.anim.slide_out_left);
// ft.replace(R.id.realtabcontent, fragment);
// ft.commit();
// }
//
// public Fragment popFragments()
// {
// try
// {
// /*
// * Select the second last fragment in current tab's stack.. which
// * will be shown after the fragment transaction given below
// */
// Fragment fragment = mStacks.get(mCurrentTab).elementAt(mStacks.get(mCurrentTab).size() - 2);
//
// /* pop current fragment from stack.. */
// mStacks.get(mCurrentTab).pop();
//
// /*
// * We have the target fragment in hand.. Just show it.. Show a
// * standard navigation animation
// */
// FragmentManager manager = getSupportFragmentManager();
// FragmentTransaction ft = manager.beginTransaction();
// ft.addToBackStack(null);
// ft.setCustomAnimations(R.anim.slide_in_left, R.anim.slide_out_right);
// ft.replace(R.id.realtabcontent, fragment);
// ft.commit();
// fragment.onResume();
// return fragment;
// }
// catch (Exception e)
// {
// e.printStackTrace();
// }
// return null;
// }
//
// @Override
// public void onBackPressed()
// {
// if (((com.ta.cinemaapp.fragment.BaseFragment)
// mStacks.get(mCurrentTab).lastElement()).onBackPressed() == false)
// {
// /*
// * top fragment in current tab doesn't handles back press, we can do
// * our thing, which is
// *
// * if current tab has only one fragment in stack, ie first fragment
// * is showing for this tab. finish the activity else pop to previous
// * fragment in stack for the same tab
// */
// // SpeechTabFragment.isActivitypaused = true;
//
// if (mStacks.get(mCurrentTab).size() == 1)
// {
//
// finish();
// }
// else
// {
// popFragments();
// }
//
// }
// else
// {
// }
// }
//
// }
