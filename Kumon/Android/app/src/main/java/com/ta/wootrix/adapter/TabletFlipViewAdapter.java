package com.ta.wootrix.adapter;

import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.net.Uri;
import android.os.Handler;
import android.text.Html;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.view.animation.Animation;
import android.view.animation.AnimationUtils;
import android.widget.BaseAdapter;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.ViewFlipper;

import com.nostra13.universalimageloader.core.ImageLoader;
import com.nostra13.universalimageloader.core.assist.FailReason;
import com.nostra13.universalimageloader.core.assist.ImageLoadingListener;
import com.ta.wootrix.R;
import com.ta.wootrix.modle.AdvertisementModle;
import com.ta.wootrix.modle.AdvtMainModle;
import com.ta.wootrix.modle.ArticleModle;
import com.ta.wootrix.modle.IModel;
import com.ta.wootrix.modle.MagazineModle;
import com.ta.wootrix.restapi.APIUtils;
import com.ta.wootrix.restapi.RestService;
import com.ta.wootrix.tablet.TabletAdvertViewActivity;
import com.ta.wootrix.tablet.TabletArticleDetailActivity;
import com.ta.wootrix.tablet.TabletEmbeddedArticleDetailActivity;
import com.ta.wootrix.tablet.TabletEmbeddedVideoActivity;
import com.ta.wootrix.utils.AccessRegisterListener;
import com.ta.wootrix.utils.ColorUtils;
import com.ta.wootrix.utils.Constants;
import com.ta.wootrix.utils.GPSTracker;
import com.ta.wootrix.utils.Utility;

import java.util.ArrayList;
import java.util.HashMap;

public class TabletFlipViewAdapter extends BaseAdapter implements OnClickListener
{
	LayoutInflater							mInflater;
	Handler									handler	= new Handler();
	View									v1, v2, v3, v4;
	private int								whileColor;
	private int								blackishColor;
	private ImageLoader						mImageLoader;
	private Context							context;
	private ArrayList<IModel>				mList;
	private AdvtMainModle					mAdvt;
	private int								pageNumber;
	private ArticleModle					modle;
	private int								bgColor;
	private String							logoURL;
	private GPSTracker						mGpsTracker;
	private ArrayList<AdvertisementModle>	mAdvtData;
	private int								screenOrientation;
	private int								defaultDrawable;
	private WootrixImageLoadingListener		wootrixILL;
	private MagazineModle					magazineData;
	String URLDtring = "http://www.fastcasual.com/articles/5-things-to-consider-before-measuring-customer-behavior-via-mobile/";
	private AccessRegisterListener      accessRegisterListener;

	public TabletFlipViewAdapter(Context con, ArrayList<IModel> mArticleList, int pageNumber, AdvtMainModle mAdvtData, String logoURL, GPSTracker mGpsTracker, MagazineModle magazineData)
	{
		this.context = con;
		mInflater = (LayoutInflater) con.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
		this.mList = mArticleList;
		if (mAdvtData == null)
			this.mAdvt = new AdvtMainModle();
		else
			this.mAdvt = mAdvtData;
		this.pageNumber = pageNumber;
		this.logoURL = logoURL;
		mImageLoader = Utility.getImageLoader(con);
		this.magazineData = magazineData;
		whileColor = con.getResources().getColor(R.color.white);
		blackishColor = con.getResources().getColor(R.color.black_heading);
		bgColor = con.getResources().getColor(R.color.banner_color);
		this.mGpsTracker = mGpsTracker;
		this.screenOrientation = con.getResources().getConfiguration().orientation;
		defaultDrawable = R.drawable.article_layout2_placeholder;
	}

	public TabletFlipViewAdapter(Context con, ArrayList<IModel> mArticleList, int pageNumber, AdvtMainModle mAdvtData, String logoURL, GPSTracker mGpsTracker, MagazineModle magazineData, AccessRegisterListener accessRegisterListener)
	{
		this.context = con;
		mInflater = (LayoutInflater) con.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
		this.mList = mArticleList;
		if (mAdvtData == null)
			this.mAdvt = new AdvtMainModle();
		else
			this.mAdvt = mAdvtData;
		this.pageNumber = pageNumber;
		this.logoURL = logoURL;
		mImageLoader = Utility.getImageLoader(con);
		this.magazineData = magazineData;
		whileColor = con.getResources().getColor(R.color.white);
		blackishColor = con.getResources().getColor(R.color.black_heading);
		bgColor = con.getResources().getColor(R.color.banner_color);
		this.mGpsTracker = mGpsTracker;
		this.screenOrientation = con.getResources().getConfiguration().orientation;
		defaultDrawable = R.drawable.article_layout2_placeholder;
        this.accessRegisterListener = accessRegisterListener;
	}

	public void updateMagazineData(MagazineModle data)
	{
		this.magazineData = data;
	}

	@Override
	public int getCount()
	{
		if (mList.size() > 0)
			return pageNumber;
		else
		{
			pageNumber = 0;
			return 0;
		}
	}

	@Override
	public Object getItem(int position)
	{
		return pageNumber;
	}

	@Override
	public long getItemId(int position)
	{
		return position;
	}

	@Override
	public int getItemViewType(int position)
	{
		return super.getItemViewType(position);
	}

	@Override
	public int getViewTypeCount()
	{
		return super.getViewTypeCount();
	}

	@Override
	public View getView(int pageNumber, View convertView, ViewGroup parent)
	{

		int lastPos = lastIndexofArticleInPage(pageNumber);
		int startPos = 0;
		if (wootrixILL == null)
			wootrixILL = new WootrixImageLoadingListener();
		if (pageNumber % 4 == 0)
		{
			final LayoutOneVwHolder mlayoutOneVwHolder;
			// code for first layout
			// View v1 = convertView;
			if (v1 == null || !(v1.getTag() instanceof LayoutOneVwHolder))
			{
				Log.e("View Created", "First View Created");
				mlayoutOneVwHolder = new LayoutOneVwHolder();
				v1 = mInflater.inflate(R.layout.home_layout_one, null, false);

				mlayoutOneVwHolder.m1stBG = (ImageView) v1.findViewById(R.id.home_tab_one_1st_block_bg_imgVW);
				mlayoutOneVwHolder.m2ndBG = (ImageView) v1.findViewById(R.id.home_tab_one_2nd_block_bg_imgVW);
				mlayoutOneVwHolder.m3rdBG = (ImageView) v1.findViewById(R.id.home_tab_one_3rd_block_bg_imgVW);
				mlayoutOneVwHolder.m1stBGPlcHolder = (ImageView) v1.findViewById(R.id.home_tab_one_1st_block_transparent_bg_imgVW);
				mlayoutOneVwHolder.m2ndBGPlcHolder = (ImageView) v1.findViewById(R.id.home_tab_one_2nd_block_transparent_bg_imgVW);
				mlayoutOneVwHolder.m3rdBGPlcHolder = (ImageView) v1.findViewById(R.id.home_tab_one_3rd_block_transparent_bg_imgVW);
				mlayoutOneVwHolder.m1stBGVideoOvrly = (ImageView) v1.findViewById(R.id.home_tab_one_1st_block_video_placeholder_imgVW);
				mlayoutOneVwHolder.m2ndBGVideoOvrly = (ImageView) v1.findViewById(R.id.home_tab_one_2nd_block_video_placeholder_imgVW);
				mlayoutOneVwHolder.m3rdBGVideoOvrly = (ImageView) v1.findViewById(R.id.home_tab_one_3rd_block_video_placeholder_imgVW);
				mlayoutOneVwHolder.mAdvtVideoOvrly = (ImageView) v1.findViewById(R.id.home_tab_one_videoOverly_imgVw);

				mlayoutOneVwHolder.mTxt1stTitle = (TextView) v1.findViewById(R.id.home_tab_one_1st_block_article_title_txtVw);
				mlayoutOneVwHolder.mTxt2ndTitle = (TextView) v1.findViewById(R.id.home_tab_one_2nd_block_article_title_txtVw);
				mlayoutOneVwHolder.mTxt3rdTitle = (TextView) v1.findViewById(R.id.home_tab_one_3rd_block_article_title_txtVw);
				mlayoutOneVwHolder.mTxt1stURL = (TextView) v1.findViewById(R.id.home_tab_one_1st_block_url_txtVw);
				mlayoutOneVwHolder.mTxt2ndURL = (TextView) v1.findViewById(R.id.home_tab_one_2nd_block_url_txtVw);
				mlayoutOneVwHolder.mTxt3rdURL = (TextView) v1.findViewById(R.id.home_tab_one_3rd_block_url_txtVw);

				mlayoutOneVwHolder.mTxt1stDesc = (TextView) v1.findViewById(R.id.home_tab_one_1st_block_description_txtVw);
				mlayoutOneVwHolder.mTxt2ndDesc = (TextView) v1.findViewById(R.id.home_tab_one_2nd_block_description_txtVw);
				mlayoutOneVwHolder.mTxt3rdDesc = (TextView) v1.findViewById(R.id.home_tab_one_3rd_block_description_txtVw);

				mlayoutOneVwHolder.advertiseFlipper = (ViewFlipper) v1.findViewById(R.id.home_tab_one_viewFlipper);

				mlayoutOneVwHolder.m1stLyt = (LinearLayout) v1.findViewById(R.id.home_tab_one_1st_block_txt_lnrLyt);
				mlayoutOneVwHolder.m2ndLyt = (LinearLayout) v1.findViewById(R.id.home_tab_one_2nd_block_lnrLyt);
				mlayoutOneVwHolder.m3rdLyt = (LinearLayout) v1.findViewById(R.id.home_tab_one_3rd_block_lnrLyt);
				mlayoutOneVwHolder.m1stBGLyt = (LinearLayout) v1.findViewById(R.id.home_tab_one_1st_block_main_LnrLyt);
				mlayoutOneVwHolder.m2ndBGLyt = (LinearLayout) v1.findViewById(R.id.home_tab_one_2nd_block_main_lntLyt);
				mlayoutOneVwHolder.m3rdBGLyt = (LinearLayout) v1.findViewById(R.id.home_tab_one_3rd_block_main_lntLyt);
				mlayoutOneVwHolder.mAdvtLyt = (FrameLayout) v1.findViewById(R.id.home_tab_one_advt_frmLyt);

				mlayoutOneVwHolder.advertiseFlipper.setInAnimation(AnimationUtils.loadAnimation(context, R.anim.fade_in));
				mlayoutOneVwHolder.advertiseFlipper.setOutAnimation(AnimationUtils.loadAnimation(context, R.anim.fade_out));
				mlayoutOneVwHolder.advertiseFlipper.getInAnimation().setAnimationListener(new Animation.AnimationListener() {
					public void onAnimationStart(Animation animation)
					{
					}

					public void onAnimationRepeat(Animation animation)
					{
					}

					public void onAnimationEnd(Animation animation)
					{
						ImageView vv = (ImageView) ((LinearLayout) mlayoutOneVwHolder.advertiseFlipper.getCurrentView()).getChildAt(0);
						if (vv.getTag(R.string.ad_videourl) != null)
							mlayoutOneVwHolder.mAdvtVideoOvrly.setVisibility(View.VISIBLE);
						else
							mlayoutOneVwHolder.mAdvtVideoOvrly.setVisibility(View.GONE);
						mlayoutOneVwHolder.advertiseFlipper.setFlipInterval((Integer) vv.getTag(
								R.string.ad_tim_in_sec)/*
														 * ( ( Integer ) vv . getTag ( R . string .
														 * ad_tim_in_sec ) ) < 2000 ? 5 * 1000 : (
														 * Integer ) vv . getTag ( R . string .
														 * ad_tim_in_sec )
														 */);
					}
				});

				ColorUtils.setDescHeights(mlayoutOneVwHolder.mTxt1stDesc, mlayoutOneVwHolder.mTxt2ndDesc, mlayoutOneVwHolder.mTxt3rdDesc);
				v1.setTag(mlayoutOneVwHolder);
			}
			else
			{
				mlayoutOneVwHolder = (LayoutOneVwHolder) v1.getTag();
			}
			mAdvtData = mAdvt.getLyt1List();

			// code for the viewflipper
			if (mAdvtData != null && mAdvtData.size() > 0)
			{
				mlayoutOneVwHolder.advertiseFlipper.removeAllViews();
				for (int i = 0; i < mAdvtData.size(); i++)
				{
					View child = mInflater.inflate(R.layout.advt_item, null);
					ImageView image = (ImageView) child.findViewById(R.id.advt_item_row_imgVw);

					if (mAdvtData.get(i).getType().equalsIgnoreCase("embeded"))
					{
						mImageLoader.displayImage(mAdvtData.get(i).getEmbededThumbUrl(), image, Utility.getArticleDisplayOption(), wootrixILL);
						image.setTag(R.string.ad_videourl, mAdvtData.get(i).getVideoURL());
						image.setTag(R.string.ad_embedded_url, mAdvtData.get(i).getEmbededVideoUrl());
					}
					else if (mAdvtData.get(i).getType().equalsIgnoreCase("video"))
					{
						mImageLoader.displayImage(mAdvtData.get(i).getBannerURL(), image, Utility.getArticleDisplayOption(), wootrixILL);
						image.setTag(R.string.ad_videourl, mAdvtData.get(i).getVideoURL());
					}
					else
					{
						mImageLoader.displayImage(screenOrientation == 1 ? mAdvtData.get(i).getPortaitURL() : mAdvtData.get(i).getLandscapeURL(), image, Utility.getArticleDisplayOption(), wootrixILL);

						image.setTag(R.string.ad_videourl, null);
					}
					image.setTag(R.string.ad_link_url, mAdvtData.get(i).getLikeToOpen());
					image.setTag(R.string.ad_tim_in_sec, mAdvtData.get(i).getTimeInSeconds() * 1000);
					image.setTag(R.string.ad_advtID, mAdvtData.get(i).getAdvertiseID());

					mlayoutOneVwHolder.advertiseFlipper.addView(child);
				}
				if (mAdvtData.get(0).getType().equalsIgnoreCase("embeded"))
					mlayoutOneVwHolder.mAdvtVideoOvrly.setVisibility(View.VISIBLE);
				else if (mAdvtData.get(0).getType().equalsIgnoreCase("video"))
					mlayoutOneVwHolder.mAdvtVideoOvrly.setVisibility(View.VISIBLE);
				else
					mlayoutOneVwHolder.mAdvtVideoOvrly.setVisibility(View.GONE);
				mlayoutOneVwHolder.advertiseFlipper.setFlipInterval(mAdvtData.get(0).getTimeInSeconds() * 1000);
				mlayoutOneVwHolder.advertiseFlipper.setAutoStart(true);
				mlayoutOneVwHolder.mAdvtLyt.setTag(mlayoutOneVwHolder.advertiseFlipper);
				mlayoutOneVwHolder.mAdvtLyt.setOnClickListener(this);

			}
			else
			{
				mlayoutOneVwHolder.mAdvtVideoOvrly.setVisibility(View.GONE);
				mlayoutOneVwHolder.advertiseFlipper.removeAllViews();
				View chiled = mInflater.inflate(R.layout.advt_item, null);
				mlayoutOneVwHolder.advertiseFlipper.addView(chiled);
				mlayoutOneVwHolder.mAdvtLyt.setOnClickListener(null);
			}

			startPos = lastPos - 2;
			if (startPos < 0)
				startPos = 0;
			if (mList.size() > startPos && mList.get(startPos) != null)
			{
				mlayoutOneVwHolder.m1stLyt.setBackgroundColor(whileColor);
				modle = (ArticleModle) mList.get(startPos);
				ColorUtils.showViews(mlayoutOneVwHolder.mTxt1stTitle, mlayoutOneVwHolder.mTxt1stURL, mlayoutOneVwHolder.mTxt1stDesc);
				if (modle.getArticleType().equalsIgnoreCase("text"))
				{
					mlayoutOneVwHolder.m1stBG.setImageResource(R.drawable.article_layout_placeholder);
					ColorUtils.hideViews(mlayoutOneVwHolder.m1stBGPlcHolder, mlayoutOneVwHolder.m1stBGVideoOvrly);
				}
				else
				{

					if (modle.getArticleType().equalsIgnoreCase("video"))
					{
						mlayoutOneVwHolder.m1stBGVideoOvrly.setVisibility(View.VISIBLE);
						mImageLoader.displayImage(modle.getCoverPhotoUrl(), mlayoutOneVwHolder.m1stBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
					else if (modle.getArticleType().equalsIgnoreCase("embedded"))
					{
						mlayoutOneVwHolder.m1stBGVideoOvrly.setVisibility(View.VISIBLE);
						mImageLoader.displayImage(modle.getEmbeddedThumb(), mlayoutOneVwHolder.m1stBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
					else
					{
						mlayoutOneVwHolder.m1stBGVideoOvrly.setVisibility(View.INVISIBLE);
						mImageLoader.displayImage(modle.getCoverPhotoUrl(), mlayoutOneVwHolder.m1stBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
				}
				mlayoutOneVwHolder.mTxt1stTitle.setText(modle.getTitle());

				if (modle.getSource() == null || modle.getSource().equalsIgnoreCase(""))
					mlayoutOneVwHolder.mTxt1stURL.setVisibility(View.GONE);
				else
				{
					mlayoutOneVwHolder.mTxt1stURL.setVisibility(View.VISIBLE);
					mlayoutOneVwHolder.mTxt1stURL.setText(modle.getSource());
				}

				mlayoutOneVwHolder.mTxt1stDesc.setText(Html.fromHtml(modle.getArticleDescPlain()));
				mlayoutOneVwHolder.m1stBGLyt.setTag(startPos);
				mlayoutOneVwHolder.m1stBGLyt.setOnClickListener(this);
			}
			else
			{
				mlayoutOneVwHolder.m1stBG.setImageResource(R.drawable.article_layout_placeholder);
				mlayoutOneVwHolder.m1stLyt.setBackgroundColor(bgColor);
				ColorUtils.hideViews(mlayoutOneVwHolder.m1stBGPlcHolder, mlayoutOneVwHolder.m1stBGVideoOvrly, mlayoutOneVwHolder.mTxt1stTitle, mlayoutOneVwHolder.mTxt1stURL, mlayoutOneVwHolder.mTxt1stDesc);
				mlayoutOneVwHolder.m1stBGLyt.setOnClickListener(null);
			}
			startPos++;
			if (mList.size() > startPos && mList.get(startPos) != null)
			{
				ColorUtils.showViews(mlayoutOneVwHolder.mTxt2ndTitle, mlayoutOneVwHolder.mTxt2ndURL, mlayoutOneVwHolder.mTxt2ndDesc);
				mlayoutOneVwHolder.m2ndLyt.setBackgroundColor(whileColor);
				modle = (ArticleModle) mList.get(startPos);
				if (modle.getArticleType().equalsIgnoreCase("text"))
				{
					mlayoutOneVwHolder.m2ndBG.setImageResource(defaultDrawable);
					ColorUtils.hideViews(mlayoutOneVwHolder.m2ndBGPlcHolder, mlayoutOneVwHolder.m2ndBGVideoOvrly);
				}
				else
				{

					if (modle.getArticleType().equalsIgnoreCase("video"))
					{
						mlayoutOneVwHolder.m2ndBGVideoOvrly.setVisibility(View.VISIBLE);
						mImageLoader.displayImage(modle.getCoverPhotoUrl(), mlayoutOneVwHolder.m2ndBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
					else if (modle.getArticleType().equalsIgnoreCase("embedded"))
					{
						mlayoutOneVwHolder.m2ndBGVideoOvrly.setVisibility(View.VISIBLE);
						mImageLoader.displayImage(modle.getEmbeddedThumb(), mlayoutOneVwHolder.m2ndBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
					else
					{
						mlayoutOneVwHolder.m2ndBGVideoOvrly.setVisibility(View.INVISIBLE);
						mImageLoader.displayImage(modle.getCoverPhotoUrl(), mlayoutOneVwHolder.m2ndBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
				}
				mlayoutOneVwHolder.mTxt2ndTitle.setText(modle.getTitle());

				if (modle.getSource() == null || modle.getSource().equalsIgnoreCase(""))
					mlayoutOneVwHolder.mTxt2ndURL.setVisibility(View.GONE);
				else
				{
					mlayoutOneVwHolder.mTxt2ndURL.setVisibility(View.VISIBLE);
					mlayoutOneVwHolder.mTxt2ndURL.setText(modle.getSource());
				}

				mlayoutOneVwHolder.mTxt2ndDesc.setText(Html.fromHtml(modle.getArticleDescPlain()));
				mlayoutOneVwHolder.m2ndBGLyt.setTag(startPos);
				mlayoutOneVwHolder.m2ndBGLyt.setOnClickListener(this);
			}
			else
			{
				mlayoutOneVwHolder.m2ndBG.setImageResource(defaultDrawable);
				mlayoutOneVwHolder.m2ndLyt.setBackgroundColor(bgColor);
				ColorUtils.hideViews(mlayoutOneVwHolder.m2ndBGPlcHolder, mlayoutOneVwHolder.m2ndBGVideoOvrly, mlayoutOneVwHolder.mTxt2ndTitle, mlayoutOneVwHolder.mTxt2ndURL, mlayoutOneVwHolder.mTxt2ndDesc);
				mlayoutOneVwHolder.m2ndBGLyt.setOnClickListener(null);
			}
			startPos++;
			if (mList.size() > startPos && mList.get(startPos) != null)
			{
				mlayoutOneVwHolder.m3rdLyt.setBackgroundColor(whileColor);
				modle = (ArticleModle) mList.get(startPos);
				ColorUtils.showViews(mlayoutOneVwHolder.mTxt3rdTitle, mlayoutOneVwHolder.mTxt3rdURL, mlayoutOneVwHolder.mTxt3rdDesc);
				if (modle.getArticleType().equalsIgnoreCase("text"))
				{
					mlayoutOneVwHolder.m3rdBG.setImageResource(defaultDrawable);
					ColorUtils.hideViews(mlayoutOneVwHolder.m3rdBGPlcHolder, mlayoutOneVwHolder.m3rdBGVideoOvrly);
				}
				else
				{

					if (modle.getArticleType().equalsIgnoreCase("video"))
					{
						mlayoutOneVwHolder.m3rdBGVideoOvrly.setVisibility(View.VISIBLE);
						mImageLoader.displayImage(modle.getCoverPhotoUrl(), mlayoutOneVwHolder.m3rdBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
					else if (modle.getArticleType().equalsIgnoreCase("embedded"))
					{
						mlayoutOneVwHolder.m3rdBGVideoOvrly.setVisibility(View.VISIBLE);
						mImageLoader.displayImage(modle.getEmbeddedThumb(), mlayoutOneVwHolder.m3rdBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
					else
					{
						mlayoutOneVwHolder.m3rdBGVideoOvrly.setVisibility(View.INVISIBLE);
						mImageLoader.displayImage(modle.getCoverPhotoUrl(), mlayoutOneVwHolder.m3rdBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
				}
				mlayoutOneVwHolder.mTxt3rdTitle.setText(modle.getTitle());

				if (modle.getSource() == null || modle.getSource().equalsIgnoreCase(""))
					mlayoutOneVwHolder.mTxt3rdURL.setVisibility(View.GONE);
				else
				{
					mlayoutOneVwHolder.mTxt3rdURL.setVisibility(View.VISIBLE);
					mlayoutOneVwHolder.mTxt3rdURL.setText(modle.getSource());
				}
				mlayoutOneVwHolder.mTxt3rdDesc.setText(Html.fromHtml(modle.getArticleDescPlain()));
				mlayoutOneVwHolder.m3rdBGLyt.setTag(startPos);
				mlayoutOneVwHolder.m3rdBGLyt.setOnClickListener(this);
			}
			else
			{
				mlayoutOneVwHolder.m3rdBG.setImageResource(defaultDrawable);
				mlayoutOneVwHolder.m3rdLyt.setBackgroundColor(bgColor);
				ColorUtils.hideViews(mlayoutOneVwHolder.m3rdBGPlcHolder, mlayoutOneVwHolder.m3rdBGVideoOvrly, mlayoutOneVwHolder.mTxt3rdTitle,
						mlayoutOneVwHolder.mTxt3rdURL, mlayoutOneVwHolder.mTxt3rdDesc);
				mlayoutOneVwHolder.m3rdBGLyt.setOnClickListener(null);
			}

			return v1;
		}
		else if (pageNumber % 4 == 1)
		{
			final LayoutTwoVwHolder mlayoutTwoVwHolder;
			// code for second layout
			// View v2 = convertView;
			if (v2 == null || !(v2.getTag() instanceof LayoutTwoVwHolder))
			{
				Log.e("View Created", "Sec View Created");
				mlayoutTwoVwHolder = new LayoutTwoVwHolder();
				v2 = mInflater.inflate(R.layout.home_layout_two, null, false);
				mlayoutTwoVwHolder.m1stBG = (ImageView) v2.findViewById(R.id.home_tab_two_1st_block_bg_imgVW);
				mlayoutTwoVwHolder.m1stBGPlcHolder = (ImageView) v2.findViewById(R.id.home_tab_two_1st_block_transparent_bg_imgVW);
				mlayoutTwoVwHolder.m1stBGVideoOvrly = (ImageView) v2.findViewById(R.id.home_tab_two_1st_block_video_placeholder_imgVW);
				mlayoutTwoVwHolder.mAdvtVideoOvrly = (ImageView) v2.findViewById(R.id.home_tab_two_videoOverly_imgVw);

				mlayoutTwoVwHolder.mTxt1stTitle = (TextView) v2.findViewById(R.id.home_tab_two_1st_block_article_title_txtVw);
				mlayoutTwoVwHolder.mTxt1stURL = (TextView) v2.findViewById(R.id.home_tab_two_1st_block_url_txtVw);

				mlayoutTwoVwHolder.mTxt1stDesc = (TextView) v2.findViewById(R.id.home_tab_two_1st_block_description_txtVw);

				mlayoutTwoVwHolder.advertiseFlipper = (ViewFlipper) v2.findViewById(R.id.home_tab_two_viewFlipper);

				mlayoutTwoVwHolder.m1stLyt = (LinearLayout) v2.findViewById(R.id.home_tab_two_1st_block_lnrLyt);
				mlayoutTwoVwHolder.m1stBGLyt = (LinearLayout) v2.findViewById(R.id.home_tab_two_1st_block_main_lnrLyt);
				mlayoutTwoVwHolder.mAdvtLyt = (FrameLayout) v2.findViewById(R.id.home_tab_two_advt_Frm_Lyt);

				mlayoutTwoVwHolder.advertiseFlipper.setInAnimation(AnimationUtils.loadAnimation(context, R.anim.fade_in));
				mlayoutTwoVwHolder.advertiseFlipper.setOutAnimation(AnimationUtils.loadAnimation(context, R.anim.fade_out));
				mlayoutTwoVwHolder.advertiseFlipper.getInAnimation().setAnimationListener(new Animation.AnimationListener() {
					public void onAnimationStart(Animation animation)
					{
					}

					public void onAnimationRepeat(Animation animation)
					{
					}

					public void onAnimationEnd(Animation animation)
					{
						ImageView vv = (ImageView) ((LinearLayout) mlayoutTwoVwHolder.advertiseFlipper.getCurrentView()).getChildAt(0);
						if (vv.getTag(R.string.ad_videourl) != null)
							mlayoutTwoVwHolder.mAdvtVideoOvrly.setVisibility(View.VISIBLE);
						else
							mlayoutTwoVwHolder.mAdvtVideoOvrly.setVisibility(View.GONE);
						mlayoutTwoVwHolder.advertiseFlipper.setFlipInterval((Integer) vv.getTag(
								R.string.ad_tim_in_sec)/*
														 * ( ( ( Integer ) vv . getTag ( R . string
														 * . ad_tim_in_sec ) ) < 2000 ? 5 * 1000 : (
														 * Integer ) vv . getTag ( R . string .
														 * ad_tim_in_sec ) )
														 */);
					}
				});

				ColorUtils.setDescHeights(mlayoutTwoVwHolder.mTxt1stDesc);
				v2.setTag(mlayoutTwoVwHolder);
			}
			else
			{
				mlayoutTwoVwHolder = (LayoutTwoVwHolder) v2.getTag();
			}
			// code for advertisements
			mAdvtData = mAdvt.getLyt2List();

			// code for the viewflipper
			if (mAdvtData != null && mAdvtData.size() > 0)
			{
				mlayoutTwoVwHolder.advertiseFlipper.removeAllViews();
				for (int i = 0; i < mAdvtData.size(); i++)
				{
					View child = mInflater.inflate(R.layout.advt_item, null);
					ImageView image = (ImageView) child.findViewById(R.id.advt_item_row_imgVw);
					if (mAdvtData.get(i).getType().equalsIgnoreCase("embeded"))
					{
						mImageLoader.displayImage(mAdvtData.get(i).getEmbededThumbUrl(), image, Utility.getArticleDisplayOption(), wootrixILL);
						image.setTag(R.string.ad_videourl, mAdvtData.get(i).getVideoURL());
						image.setTag(R.string.ad_embedded_url, mAdvtData.get(i).getEmbededVideoUrl());
					}
					else if (mAdvtData.get(i).getType().equalsIgnoreCase("video"))
					{
						mImageLoader.displayImage(mAdvtData.get(i).getBannerURL(), image, Utility.getArticleDisplayOption(), wootrixILL);
						image.setTag(R.string.ad_videourl, mAdvtData.get(i).getVideoURL());
					}

					else
					{
						mImageLoader.displayImage(screenOrientation == 1 ? mAdvtData.get(i).getPortaitURL() : mAdvtData.get(i).getLandscapeURL(), image, Utility.getArticleDisplayOption(), wootrixILL);
						image.setTag(R.string.ad_videourl, null);
					}
					image.setTag(R.string.ad_link_url, mAdvtData.get(i).getLikeToOpen());
					image.setTag(R.string.ad_tim_in_sec, mAdvtData.get(i).getTimeInSeconds() * 1000);
					image.setTag(R.string.ad_advtID, mAdvtData.get(i).getAdvertiseID());

					mlayoutTwoVwHolder.advertiseFlipper.addView(child);
				}
				if (mAdvtData.get(0).getType().equalsIgnoreCase("embeded"))
					mlayoutTwoVwHolder.mAdvtVideoOvrly.setVisibility(View.VISIBLE);
				else if (mAdvtData.get(0).getType().equalsIgnoreCase("video"))
					mlayoutTwoVwHolder.mAdvtVideoOvrly.setVisibility(View.VISIBLE);
				else
					mlayoutTwoVwHolder.mAdvtVideoOvrly.setVisibility(View.GONE);

				mlayoutTwoVwHolder.advertiseFlipper.setFlipInterval(mAdvtData.get(0).getTimeInSeconds() * 1000);
				mlayoutTwoVwHolder.advertiseFlipper.setAutoStart(true);
				mlayoutTwoVwHolder.mAdvtLyt.setTag(mlayoutTwoVwHolder.advertiseFlipper);
				mlayoutTwoVwHolder.mAdvtLyt.setOnClickListener(this);

			}
			else
			{
				mlayoutTwoVwHolder.advertiseFlipper.removeAllViews();
				View chiled = mInflater.inflate(R.layout.advt_item, null);
				mlayoutTwoVwHolder.advertiseFlipper.addView(chiled);
				mlayoutTwoVwHolder.mAdvtVideoOvrly.setVisibility(View.GONE);
				mlayoutTwoVwHolder.mAdvtLyt.setOnClickListener(null);
			}

			startPos = lastPos;
			if (startPos < 0)
				startPos = 0;
			if (mList.size() > startPos && mList.get(startPos) != null)
			{

				mlayoutTwoVwHolder.m1stLyt.setBackgroundColor(whileColor);
				modle = (ArticleModle) mList.get(startPos);

				ColorUtils.showViews(mlayoutTwoVwHolder.mTxt1stTitle, mlayoutTwoVwHolder.mTxt1stURL, mlayoutTwoVwHolder.mTxt1stDesc);
				if (modle.getArticleType().equalsIgnoreCase("text"))
				{
					mlayoutTwoVwHolder.m1stBG.setImageResource(defaultDrawable);
					ColorUtils.hideViews(mlayoutTwoVwHolder.m1stBGPlcHolder, mlayoutTwoVwHolder.m1stBGVideoOvrly);
				}
				else
				{

					if (modle.getArticleType().equalsIgnoreCase("video"))
					{
						mlayoutTwoVwHolder.m1stBGVideoOvrly.setVisibility(View.VISIBLE);
						mImageLoader.displayImage(modle.getCoverPhotoUrl(), mlayoutTwoVwHolder.m1stBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
					else if (modle.getArticleType().equalsIgnoreCase("embedded"))
					{
						mlayoutTwoVwHolder.m1stBGVideoOvrly.setVisibility(View.VISIBLE);
						mImageLoader.displayImage(modle.getEmbeddedThumb(), mlayoutTwoVwHolder.m1stBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
					else
					{
						mlayoutTwoVwHolder.m1stBGVideoOvrly.setVisibility(View.INVISIBLE);
						mImageLoader.displayImage(modle.getCoverPhotoUrl(), mlayoutTwoVwHolder.m1stBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
				}
				mlayoutTwoVwHolder.mTxt1stTitle.setText(modle.getTitle());

				if (modle.getSource() == null || modle.getSource().equalsIgnoreCase(""))
					mlayoutTwoVwHolder.mTxt1stURL.setVisibility(View.GONE);
				else
				{
					mlayoutTwoVwHolder.mTxt1stURL.setVisibility(View.VISIBLE);
					mlayoutTwoVwHolder.mTxt1stURL.setText(modle.getSource());
				}
				mlayoutTwoVwHolder.mTxt1stDesc.setText(Html.fromHtml(modle.getArticleDescPlain()));
				mlayoutTwoVwHolder.m1stBGLyt.setTag(startPos);
				mlayoutTwoVwHolder.m1stBGLyt.setOnClickListener(this);
			}
			else
			{
				mlayoutTwoVwHolder.m1stBG.setImageResource(defaultDrawable);
				mlayoutTwoVwHolder.m1stLyt.setBackgroundColor(bgColor);
				ColorUtils.hideViews(mlayoutTwoVwHolder.m1stBGPlcHolder, mlayoutTwoVwHolder.m1stBGVideoOvrly, mlayoutTwoVwHolder.mTxt1stTitle, mlayoutTwoVwHolder.mTxt1stURL, mlayoutTwoVwHolder.mTxt1stDesc);
				mlayoutTwoVwHolder.m1stBGLyt.setOnClickListener(null);
			}
			return v2;
		}
		else if (pageNumber % 4 == 2)
		{
			final LayoutThreeVwHolder mlayoutThreeVwHolder;
			// code for third layout
			// View v3 = convertView;
			if (v3 == null || !(v3.getTag() instanceof LayoutThreeVwHolder))
			{
				Log.e("View Created", "third View Created");
				mlayoutThreeVwHolder = new LayoutThreeVwHolder();
				v3 = mInflater.inflate(R.layout.home_layout_three, null, false);

				mlayoutThreeVwHolder.m1stBG = (ImageView) v3.findViewById(R.id.home_tab_three_1st_block_bg_imgVW);

				mlayoutThreeVwHolder.m2ndBG = (ImageView) v3.findViewById(R.id.home_tab_three_2nd_block_bg_imgVW);
				mlayoutThreeVwHolder.m3rdBG = (ImageView) v3.findViewById(R.id.home_tab_three_3rd_block_bg_imgVW);
				mlayoutThreeVwHolder.m4thBG = (ImageView) v3.findViewById(R.id.home_tab_three_4th_block_bg_imgVW);
				mlayoutThreeVwHolder.m5thBG = (ImageView) v3.findViewById(R.id.home_tab_three_5th_block_bg_imgVW);
				mlayoutThreeVwHolder.m1stBGPlcHoldr = (ImageView) v3.findViewById(R.id.home_tab_three_1st_block_transparent_bg_imgVW);

				mlayoutThreeVwHolder.m2ndBGPlcHoldr = (ImageView) v3.findViewById(R.id.home_tab_three_2nd_block_transparent_bg_imgVW);

				mlayoutThreeVwHolder.m3rdBGPlcHoldr = (ImageView) v3.findViewById(R.id.home_tab_three_3rd_block_transparent_bg_imgVW);
				mlayoutThreeVwHolder.m4thBGPlcHoldr = (ImageView) v3.findViewById(R.id.home_tab_three_4th_block_transparent_bg_imgVW);
				mlayoutThreeVwHolder.m5thBGPlcHoldr = (ImageView) v3.findViewById(R.id.home_tab_three_5th_block_transparent_bg_imgVW);
				mlayoutThreeVwHolder.m1stBGVdoOvrly = (ImageView) v3.findViewById(R.id.home_tab_three_1st_block_video_placeholder_imgVW);

				mlayoutThreeVwHolder.m2ndBGVdoOvrly = (ImageView) v3.findViewById(R.id.home_tab_three_2nd_block_video_placeholder_imgVW);

				mlayoutThreeVwHolder.m3rdBGVdoOvrly = (ImageView) v3.findViewById(R.id.home_tab_three_3rd_block_video_placeholder_imgVW);
				mlayoutThreeVwHolder.m4thBGVdoOvrly = (ImageView) v3.findViewById(R.id.home_tab_three_4th_block_video_placeholder_imgVW);
				mlayoutThreeVwHolder.m5thBGVdoOvrly = (ImageView) v3.findViewById(R.id.home_tab_three_5th_block_video_placeholder_imgVW);
				mlayoutThreeVwHolder.mAdvtVideoOvrly = (ImageView) v3.findViewById(R.id.home_tab_three_videoOverly_imgVw);

				mlayoutThreeVwHolder.mTxt1stTitle = (TextView) v3.findViewById(R.id.home_tab_three_1st_block_article_title_txtVw);
				mlayoutThreeVwHolder.mTxt2ndTitle = (TextView) v3.findViewById(R.id.home_tab_three_2nd_block_article_title_txtVw);
				mlayoutThreeVwHolder.mTxt3rdTitle = (TextView) v3.findViewById(R.id.home_tab_three_3rd_block_article_title_txtVw);
				mlayoutThreeVwHolder.mTxt4thTitle = (TextView) v3.findViewById(R.id.home_tab_three_4th_block_article_title_txtVw);
				mlayoutThreeVwHolder.mTxt5thTitle = (TextView) v3.findViewById(R.id.home_tab_three_5th_block_article_title_txtVw);

				mlayoutThreeVwHolder.mTxt1stURL = (TextView) v3.findViewById(R.id.home_tab_three_1st_block_url_txtVw);
				mlayoutThreeVwHolder.mTxt2ndURL = (TextView) v3.findViewById(R.id.home_tab_three_2nd_block_url_txtVw);
				mlayoutThreeVwHolder.mTxt3rdURL = (TextView) v3.findViewById(R.id.home_tab_three_3rd_block_url_txtVw);
				mlayoutThreeVwHolder.mTxt4thURL = (TextView) v3.findViewById(R.id.home_tab_three_4th_block_url_txtVw);
				mlayoutThreeVwHolder.mTxt5thURL = (TextView) v3.findViewById(R.id.home_tab_three_5th_block_url_txtVw);

				mlayoutThreeVwHolder.mTxt1stDesc = (TextView) v3.findViewById(R.id.home_tab_three_1st_block_description_txtVw);
				mlayoutThreeVwHolder.mTxt2ndDesc = (TextView) v3.findViewById(R.id.home_tab_three_2nd_block_description_txtVw);
				mlayoutThreeVwHolder.mTxt3rdDesc = (TextView) v3.findViewById(R.id.home_tab_three_3rd_block_description_txtVw);
				mlayoutThreeVwHolder.mTxt5ndDesc = (TextView) v3.findViewById(R.id.home_tab_three_5th_block_description_txtVw);

				mlayoutThreeVwHolder.advertiseFlipper = (ViewFlipper) v3.findViewById(R.id.home_tab_three_viewFlipper);

				mlayoutThreeVwHolder.m1LnrLyt = (LinearLayout) v3.findViewById(R.id.home_tab_three_1st_block_lnrLyt);
				mlayoutThreeVwHolder.m3LnrLyt = (LinearLayout) v3.findViewById(R.id.home_tab_three_3rd_block_lnrLyt);
				mlayoutThreeVwHolder.m5LnrLyt = (LinearLayout) v3.findViewById(R.id.home_tab_three_5th_block_lnrLyt);
				mlayoutThreeVwHolder.m1MainLnrLyt = (LinearLayout) v3.findViewById(R.id.home_tab_three_1st_block_main_lnrLyt);
				mlayoutThreeVwHolder.m3MainLnrLyt = (LinearLayout) v3.findViewById(R.id.home_tab_three_3rd_block_main_lnrLyt);
				mlayoutThreeVwHolder.m5MainLnrLyt = (LinearLayout) v3.findViewById(R.id.home_tab_three_5th_block_main_lnrLyt);

				mlayoutThreeVwHolder.mAdvtLyt = (FrameLayout) v3.findViewById(R.id.home_tab_three_advt_Lyt);
				mlayoutThreeVwHolder.m2FramLyt = (FrameLayout) v3.findViewById(R.id.home_tab_three_2nd_block_frame_lyt);
				mlayoutThreeVwHolder.m4FramLyt = (FrameLayout) v3.findViewById(R.id.home_tab_three_4th_block_frameLyt);

				mlayoutThreeVwHolder.advertiseFlipper.setInAnimation(AnimationUtils.loadAnimation(context, R.anim.fade_in));
				mlayoutThreeVwHolder.advertiseFlipper.setOutAnimation(AnimationUtils.loadAnimation(context, R.anim.fade_out));
				mlayoutThreeVwHolder.advertiseFlipper.getInAnimation().setAnimationListener(new Animation.AnimationListener() {
					public void onAnimationStart(Animation animation)
					{
					}

					public void onAnimationRepeat(Animation animation)
					{
					}

					public void onAnimationEnd(Animation animation)
					{
						ImageView vv = (ImageView) ((LinearLayout) mlayoutThreeVwHolder.advertiseFlipper.getCurrentView()).getChildAt(0);
						if (vv.getTag(R.string.ad_videourl) != null)
							mlayoutThreeVwHolder.mAdvtVideoOvrly.setVisibility(View.VISIBLE);
						else
							mlayoutThreeVwHolder.mAdvtVideoOvrly.setVisibility(View.GONE);
						mlayoutThreeVwHolder.advertiseFlipper.setFlipInterval((Integer) vv.getTag(
								R.string.ad_tim_in_sec)/*
														 * ( ( ( Integer ) vv . getTag ( R . string
														 * . ad_tim_in_sec ) ) < 2000 ? 5 * 1000 : (
														 * Integer ) vv . getTag ( R . string .
														 * ad_tim_in_sec ) )
														 */);
					}
				});

				ColorUtils.setDescHeights(mlayoutThreeVwHolder.mTxt1stDesc, mlayoutThreeVwHolder.mTxt2ndDesc, mlayoutThreeVwHolder.mTxt3rdDesc, mlayoutThreeVwHolder.mTxt5ndDesc);
				v3.setTag(mlayoutThreeVwHolder);
			}
			else
			{
				mlayoutThreeVwHolder = (LayoutThreeVwHolder) v3.getTag();
			}
			// code for advertisements
			mAdvtData = mAdvt.getLyt3List();

			// code for the viewflipper
			if (mAdvtData != null && mAdvtData.size() > 0)
			{
				mlayoutThreeVwHolder.advertiseFlipper.removeAllViews();
				for (int i = 0; i < mAdvtData.size(); i++)
				{
					View child = mInflater.inflate(R.layout.advt_item, null);
					ImageView image = (ImageView) child.findViewById(R.id.advt_item_row_imgVw);
					if (mAdvtData.get(i).getType().equalsIgnoreCase("embeded"))
					{
						mImageLoader.displayImage(mAdvtData.get(i).getEmbededThumbUrl(), image, Utility.getArticleDisplayOption(), wootrixILL);
						image.setTag(R.string.ad_videourl, mAdvtData.get(i).getVideoURL());
						image.setTag(R.string.ad_embedded_url, mAdvtData.get(i).getEmbededVideoUrl());
					}
					else if (mAdvtData.get(i).getType().equalsIgnoreCase("video"))
					{
						mImageLoader.displayImage(mAdvtData.get(i).getBannerURL(), image, Utility.getArticleDisplayOption(), wootrixILL);
						image.setTag(R.string.ad_videourl, mAdvtData.get(i).getVideoURL());
					}

					else
					{
						mImageLoader.displayImage(screenOrientation == 1 ? mAdvtData.get(i).getPortaitURL() : mAdvtData.get(i).getLandscapeURL(), image, Utility.getArticleDisplayOption(), wootrixILL);
						image.setTag(R.string.ad_videourl, null);
					}
					image.setTag(R.string.ad_link_url, mAdvtData.get(i).getLikeToOpen());
					image.setTag(R.string.ad_tim_in_sec, mAdvtData.get(i).getTimeInSeconds() * 1000);
					image.setTag(R.string.ad_advtID, mAdvtData.get(i).getAdvertiseID());

					mlayoutThreeVwHolder.advertiseFlipper.addView(child);
				}

				if (mAdvtData.get(0).getType().equalsIgnoreCase("embeded"))
					mlayoutThreeVwHolder.mAdvtVideoOvrly.setVisibility(View.VISIBLE);
				else if (mAdvtData.get(0).getType().equalsIgnoreCase("video"))
					mlayoutThreeVwHolder.mAdvtVideoOvrly.setVisibility(View.VISIBLE);
				else
					mlayoutThreeVwHolder.mAdvtVideoOvrly.setVisibility(View.GONE);
				mlayoutThreeVwHolder.advertiseFlipper.setFlipInterval(mAdvtData.get(0).getTimeInSeconds() * 1000);
				mlayoutThreeVwHolder.advertiseFlipper.setAutoStart(true);
				mlayoutThreeVwHolder.mAdvtLyt.setTag(mlayoutThreeVwHolder.advertiseFlipper);
				mlayoutThreeVwHolder.mAdvtLyt.setOnClickListener(this);

			}
			else
			{
				mlayoutThreeVwHolder.advertiseFlipper.removeAllViews();
				View chiled = mInflater.inflate(R.layout.advt_item, null);
				mlayoutThreeVwHolder.advertiseFlipper.addView(chiled);
				mlayoutThreeVwHolder.mAdvtVideoOvrly.setVisibility(View.GONE);
				mlayoutThreeVwHolder.mAdvtLyt.setOnClickListener(null);
			}

			startPos = lastPos - 4;
			if (startPos < 0)
				startPos = 0;
			// for first grid item
			if (mList.size() > startPos && mList.get(startPos) != null)
			{
				mlayoutThreeVwHolder.m1LnrLyt.setBackgroundColor(whileColor);
				modle = (ArticleModle) mList.get(startPos);

				ColorUtils.showViews(mlayoutThreeVwHolder.mTxt1stTitle, mlayoutThreeVwHolder.mTxt1stURL, mlayoutThreeVwHolder.mTxt1stDesc);
				if (modle.getArticleType().equalsIgnoreCase("text"))
				{
					mlayoutThreeVwHolder.m1stBG.setImageResource(defaultDrawable);
					ColorUtils.hideViews(mlayoutThreeVwHolder.m1stBGPlcHoldr, mlayoutThreeVwHolder.m1stBGVdoOvrly);
				}
				else
				{

					if (modle.getArticleType().equalsIgnoreCase("video"))
					{
						mlayoutThreeVwHolder.m1stBGVdoOvrly.setVisibility(View.VISIBLE);
						mImageLoader.displayImage(modle.getCoverPhotoUrl(), mlayoutThreeVwHolder.m1stBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
					else if (modle.getArticleType().equalsIgnoreCase("embedded"))
					{
						mlayoutThreeVwHolder.m1stBGVdoOvrly.setVisibility(View.VISIBLE);
						mImageLoader.displayImage(modle.getEmbeddedThumb(), mlayoutThreeVwHolder.m1stBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
					else
					{
						mlayoutThreeVwHolder.m1stBGVdoOvrly.setVisibility(View.INVISIBLE);
						mImageLoader.displayImage(modle.getCoverPhotoUrl(), mlayoutThreeVwHolder.m1stBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
				}
				mlayoutThreeVwHolder.mTxt1stTitle.setText(modle.getTitle());

				if (modle.getSource() == null || modle.getSource().equalsIgnoreCase(""))
					mlayoutThreeVwHolder.mTxt1stURL.setVisibility(View.GONE);
				else
				{
					mlayoutThreeVwHolder.mTxt1stURL.setVisibility(View.VISIBLE);
					mlayoutThreeVwHolder.mTxt1stURL.setText(modle.getSource());
				}

				mlayoutThreeVwHolder.mTxt1stDesc.setText(Html.fromHtml(modle.getArticleDescPlain()));
				mlayoutThreeVwHolder.m1MainLnrLyt.setTag(startPos);
				mlayoutThreeVwHolder.m1MainLnrLyt.setOnClickListener(this);
			}
			else
			{
				mlayoutThreeVwHolder.m1stBG.setImageResource(defaultDrawable);
				mlayoutThreeVwHolder.m1LnrLyt.setBackgroundColor(bgColor);
				ColorUtils.hideViews(mlayoutThreeVwHolder.m1stBGPlcHoldr, mlayoutThreeVwHolder.m1stBGVdoOvrly, mlayoutThreeVwHolder.mTxt1stTitle, mlayoutThreeVwHolder.mTxt1stURL, mlayoutThreeVwHolder.mTxt1stDesc);
				mlayoutThreeVwHolder.m1MainLnrLyt.setOnClickListener(null);
			}
			startPos++;
			// for second grid item
			if (mList.size() > startPos && mList.get(startPos) != null)
			{
				modle = (ArticleModle) mList.get(startPos);

				ColorUtils.showViews(mlayoutThreeVwHolder.mTxt2ndTitle, mlayoutThreeVwHolder.mTxt2ndDesc, mlayoutThreeVwHolder.mTxt2ndURL);
				if (modle.getArticleType().equalsIgnoreCase("text"))
				{
					mlayoutThreeVwHolder.m2ndBG.setImageResource(defaultDrawable);
					ColorUtils.hideViews(mlayoutThreeVwHolder.m2ndBGPlcHoldr, mlayoutThreeVwHolder.m2ndBGVdoOvrly);
					ColorUtils.setColor(blackishColor, mlayoutThreeVwHolder.mTxt2ndTitle, mlayoutThreeVwHolder.mTxt2ndDesc);
				}
				else
				{
					ColorUtils.showViews(mlayoutThreeVwHolder.m2ndBGPlcHoldr);
					ColorUtils.setColor(whileColor, mlayoutThreeVwHolder.mTxt2ndTitle, mlayoutThreeVwHolder.mTxt2ndDesc);

					if (modle.getArticleType().equalsIgnoreCase("video"))
					{
						mlayoutThreeVwHolder.m2ndBGVdoOvrly.setVisibility(View.VISIBLE);
						mImageLoader.displayImage(modle.getCoverPhotoUrl(), mlayoutThreeVwHolder.m2ndBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
					else if (modle.getArticleType().equalsIgnoreCase("embedded"))
					{
						mlayoutThreeVwHolder.m2ndBGVdoOvrly.setVisibility(View.VISIBLE);
						mImageLoader.displayImage(modle.getEmbeddedThumb(), mlayoutThreeVwHolder.m2ndBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
					else
					{
						mlayoutThreeVwHolder.m2ndBGVdoOvrly.setVisibility(View.INVISIBLE);
						mImageLoader.displayImage(modle.getCoverPhotoUrl(), mlayoutThreeVwHolder.m2ndBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
				}
				mlayoutThreeVwHolder.mTxt2ndTitle.setText(modle.getTitle());

				if (modle.getSource() == null || modle.getSource().equalsIgnoreCase(""))
					mlayoutThreeVwHolder.mTxt2ndURL.setVisibility(View.GONE);
				else
				{
					mlayoutThreeVwHolder.mTxt2ndURL.setVisibility(View.VISIBLE);
					mlayoutThreeVwHolder.mTxt2ndURL.setText(modle.getSource());
				}
				mlayoutThreeVwHolder.mTxt2ndDesc.setText(Html.fromHtml(modle.getArticleDescPlain()));
				mlayoutThreeVwHolder.m2FramLyt.setTag(startPos);
				mlayoutThreeVwHolder.m2FramLyt.setOnClickListener(this);
			}
			else
			{
				mlayoutThreeVwHolder.m2ndBG.setImageResource(defaultDrawable);
				ColorUtils.hideViews(mlayoutThreeVwHolder.m2ndBGPlcHoldr, mlayoutThreeVwHolder.m2ndBGVdoOvrly, mlayoutThreeVwHolder.mTxt2ndTitle,
						mlayoutThreeVwHolder.mTxt2ndDesc, mlayoutThreeVwHolder.mTxt2ndURL);
				mlayoutThreeVwHolder.m2FramLyt.setOnClickListener(null);
			}

			startPos++;

			// for third grid item
			if (mList.size() > startPos && mList.get(startPos) != null)
			{
				mlayoutThreeVwHolder.m3LnrLyt.setBackgroundColor(whileColor);
				modle = (ArticleModle) mList.get(startPos);
				ColorUtils.showViews(mlayoutThreeVwHolder.mTxt3rdTitle, mlayoutThreeVwHolder.mTxt3rdURL, mlayoutThreeVwHolder.mTxt3rdDesc);
				if (modle.getArticleType().equalsIgnoreCase("text"))
				{
					mlayoutThreeVwHolder.m3rdBG.setImageResource(defaultDrawable);
					ColorUtils.hideViews(mlayoutThreeVwHolder.m3rdBGPlcHoldr, mlayoutThreeVwHolder.m3rdBGVdoOvrly);
				}
				else
				{

					if (modle.getArticleType().equalsIgnoreCase("video"))
					{
						mlayoutThreeVwHolder.m3rdBGVdoOvrly.setVisibility(View.VISIBLE);
						mImageLoader.displayImage(modle.getCoverPhotoUrl(), mlayoutThreeVwHolder.m3rdBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
					else if (modle.getArticleType().equalsIgnoreCase("embedded"))
					{
						mlayoutThreeVwHolder.m3rdBGVdoOvrly.setVisibility(View.VISIBLE);
						mImageLoader.displayImage(modle.getEmbeddedThumb(), mlayoutThreeVwHolder.m3rdBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
					else
					{
						mlayoutThreeVwHolder.m3rdBGVdoOvrly.setVisibility(View.INVISIBLE);
						mImageLoader.displayImage(modle.getCoverPhotoUrl(), mlayoutThreeVwHolder.m3rdBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
				}
				mlayoutThreeVwHolder.mTxt3rdTitle.setText(modle.getTitle());

				if (modle.getSource() == null || modle.getSource().equalsIgnoreCase(""))
					mlayoutThreeVwHolder.mTxt3rdURL.setVisibility(View.GONE);
				else
				{
					mlayoutThreeVwHolder.mTxt3rdURL.setVisibility(View.VISIBLE);
					mlayoutThreeVwHolder.mTxt3rdURL.setText(modle.getSource());
				}
				mlayoutThreeVwHolder.mTxt3rdDesc.setText(Html.fromHtml(modle.getArticleDescPlain()));
				mlayoutThreeVwHolder.m3MainLnrLyt.setTag(startPos);
				mlayoutThreeVwHolder.m3MainLnrLyt.setOnClickListener(this);
			}
			else
			{
				mlayoutThreeVwHolder.m3rdBG.setImageResource(defaultDrawable);
				mlayoutThreeVwHolder.m3LnrLyt.setBackgroundColor(bgColor);
				ColorUtils.hideViews(mlayoutThreeVwHolder.m3rdBGPlcHoldr, mlayoutThreeVwHolder.m3rdBGVdoOvrly, mlayoutThreeVwHolder.mTxt3rdTitle, mlayoutThreeVwHolder.mTxt3rdURL);
				mlayoutThreeVwHolder.m3MainLnrLyt.setOnClickListener(null);
			}

			startPos++;

			// for fourth grid item
			if (mList.size() > startPos && mList.get(startPos) != null)
			{
				modle = (ArticleModle) mList.get(startPos);
				ColorUtils.showViews(mlayoutThreeVwHolder.mTxt4thTitle, mlayoutThreeVwHolder.mTxt4thURL);
				if (modle.getArticleType().equalsIgnoreCase("text"))
				{
					mlayoutThreeVwHolder.m4thBG.setImageResource(defaultDrawable);
					ColorUtils.hideViews(mlayoutThreeVwHolder.m4thBGPlcHoldr, mlayoutThreeVwHolder.m4thBGVdoOvrly);
					ColorUtils.setColor(blackishColor, mlayoutThreeVwHolder.mTxt4thTitle);
				}
				else
				{
					ColorUtils.showViews(mlayoutThreeVwHolder.m4thBGPlcHoldr);
					ColorUtils.setColor(whileColor, mlayoutThreeVwHolder.mTxt4thTitle);

					if (modle.getArticleType().equalsIgnoreCase("video"))
					{
						mlayoutThreeVwHolder.m4thBGVdoOvrly.setVisibility(View.VISIBLE);
						mImageLoader.displayImage(modle.getCoverPhotoUrl(), mlayoutThreeVwHolder.m4thBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
					else if (modle.getArticleType().equalsIgnoreCase("embedded"))
					{
						mlayoutThreeVwHolder.m4thBGVdoOvrly.setVisibility(View.VISIBLE);
						mImageLoader.displayImage(modle.getEmbeddedThumb(), mlayoutThreeVwHolder.m4thBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
					else
					{
						mlayoutThreeVwHolder.m4thBGVdoOvrly.setVisibility(View.INVISIBLE);
						mImageLoader.displayImage(modle.getCoverPhotoUrl(), mlayoutThreeVwHolder.m4thBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
				}
				mlayoutThreeVwHolder.mTxt4thTitle.setText(modle.getTitle());

				if (modle.getSource() == null || modle.getSource().equalsIgnoreCase(""))
					mlayoutThreeVwHolder.mTxt4thURL.setVisibility(View.GONE);
				else
				{
					mlayoutThreeVwHolder.mTxt4thURL.setVisibility(View.VISIBLE);
					mlayoutThreeVwHolder.mTxt4thURL.setText(modle.getSource());
				}
				mlayoutThreeVwHolder.m4FramLyt.setTag(startPos);
				mlayoutThreeVwHolder.m4FramLyt.setOnClickListener(this);
			}
			else
			{
				mlayoutThreeVwHolder.m4thBG.setImageResource(defaultDrawable);
				ColorUtils.hideViews(mlayoutThreeVwHolder.m4thBGPlcHoldr, mlayoutThreeVwHolder.m4thBGVdoOvrly, mlayoutThreeVwHolder.mTxt4thTitle,
						mlayoutThreeVwHolder.mTxt4thURL);
				mlayoutThreeVwHolder.m4FramLyt.setOnClickListener(null);
			}
			startPos++;
			// for fifth grid item
			if (mList.size() > startPos && mList.get(startPos) != null)
			{
				mlayoutThreeVwHolder.m5LnrLyt.setBackgroundColor(whileColor);
				modle = (ArticleModle) mList.get(startPos);
				ColorUtils.showViews(mlayoutThreeVwHolder.mTxt5thTitle, mlayoutThreeVwHolder.mTxt5thURL, mlayoutThreeVwHolder.mTxt5ndDesc);

				if (modle.getArticleType().equalsIgnoreCase("text"))
				{
					mlayoutThreeVwHolder.m5thBG.setImageResource(defaultDrawable);
					ColorUtils.hideViews(mlayoutThreeVwHolder.m5thBGPlcHoldr, mlayoutThreeVwHolder.m5thBGVdoOvrly);
				}
				else
				{

					if (modle.getArticleType().equalsIgnoreCase("video"))
					{
						mlayoutThreeVwHolder.m5thBGVdoOvrly.setVisibility(View.VISIBLE);
						mImageLoader.displayImage(modle.getCoverPhotoUrl(), mlayoutThreeVwHolder.m5thBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
					else if (modle.getArticleType().equalsIgnoreCase("embedded"))
					{
						mlayoutThreeVwHolder.m5thBGVdoOvrly.setVisibility(View.VISIBLE);
						mImageLoader.displayImage(modle.getEmbeddedThumb(), mlayoutThreeVwHolder.m5thBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
					else
					{
						mlayoutThreeVwHolder.m5thBGVdoOvrly.setVisibility(View.INVISIBLE);
						mImageLoader.displayImage(modle.getCoverPhotoUrl(), mlayoutThreeVwHolder.m5thBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
				}
				mlayoutThreeVwHolder.mTxt5thTitle.setText(modle.getTitle());
				if (modle.getSource() == null || modle.getSource().equalsIgnoreCase(""))
					mlayoutThreeVwHolder.mTxt5thURL.setVisibility(View.GONE);
				else
				{
					mlayoutThreeVwHolder.mTxt5thURL.setVisibility(View.VISIBLE);
					mlayoutThreeVwHolder.mTxt5thURL.setText(modle.getSource());
				}
				mlayoutThreeVwHolder.mTxt5ndDesc.setText(Html.fromHtml(modle.getArticleDescPlain()));
				mlayoutThreeVwHolder.m5MainLnrLyt.setTag(startPos);
				mlayoutThreeVwHolder.m5MainLnrLyt.setOnClickListener(this);
			}
			else
			{
				mlayoutThreeVwHolder.m5thBG.setImageResource(defaultDrawable);
				mlayoutThreeVwHolder.m5LnrLyt.setBackgroundColor(bgColor);
				ColorUtils.hideViews(mlayoutThreeVwHolder.m5thBGPlcHoldr, mlayoutThreeVwHolder.m5thBGVdoOvrly, mlayoutThreeVwHolder.mTxt5thTitle, mlayoutThreeVwHolder.mTxt5thURL, mlayoutThreeVwHolder.mTxt5ndDesc);
				mlayoutThreeVwHolder.m5MainLnrLyt.setOnClickListener(null);
			}

			return v3;
		}
		else
		{
			final LayoutfourVwHolder mlayoutFourVwHolder;
			// View v4 = convertView;
			if (v4 == null || !(v4.getTag() instanceof LayoutfourVwHolder))
			{
				Log.e("View Created", "four View Created");
				mlayoutFourVwHolder = new LayoutfourVwHolder();
				v4 = mInflater.inflate(R.layout.home_layout_four, null, false);
				mlayoutFourVwHolder.m1stBG = (ImageView) v4.findViewById(R.id.home_tab_four_1st_block_bg_imgVW);
				mlayoutFourVwHolder.m2ndBG = (ImageView) v4.findViewById(R.id.home_tab_four_2nd_block_bg_imgVW);
				mlayoutFourVwHolder.m3rdBG = (ImageView) v4.findViewById(R.id.home_tab_four_3rd_block_bg_imgVW);
				mlayoutFourVwHolder.m4thBG = (ImageView) v4.findViewById(R.id.home_tab_four_4th_block_bg_imgVW);
				mlayoutFourVwHolder.m5thBG = (ImageView) v4.findViewById(R.id.home_tab_four_5th_block_bg_imgVW);
				mlayoutFourVwHolder.m6thBG = (ImageView) v4.findViewById(R.id.home_tab_four_6th_block_bg_imgVW);
				mlayoutFourVwHolder.m1stBGPlcHoldr = (ImageView) v4.findViewById(R.id.home_tab_four_1st_block_transparent_bg_imgVW);
				mlayoutFourVwHolder.m2ndBGPlcHoldr = (ImageView) v4.findViewById(R.id.home_tab_four_2nd_block_transparent_bg_imgVW);
				mlayoutFourVwHolder.m3rdBGPlcHoldr = (ImageView) v4.findViewById(R.id.home_tab_four_3rd_block_transparent_bg_imgVW);
				mlayoutFourVwHolder.m4thBGPlcHoldr = (ImageView) v4.findViewById(R.id.home_tab_four_4th_block_transparent_bg_imgVW);
				mlayoutFourVwHolder.m5thBGPlcHoldr = (ImageView) v4.findViewById(R.id.home_tab_four_5th_block_transparent_bg_imgVW);
				mlayoutFourVwHolder.m6thBGPlcHoldr = (ImageView) v4.findViewById(R.id.home_tab_four_6th_block_transparent_bg_imgVW);

				mlayoutFourVwHolder.m1stBGVdoOvrly = (ImageView) v4.findViewById(R.id.home_tab_four_1st_block_video_placeholder_imgVW);

				mlayoutFourVwHolder.m2ndBGVdoOvrly = (ImageView) v4.findViewById(R.id.home_tab_four_2nd_block_video_placeholder_imgVW);
				mlayoutFourVwHolder.m3rdBGVdoOvrly = (ImageView) v4.findViewById(R.id.home_tab_four_3rd_block_video_placeholder_imgVW);
				mlayoutFourVwHolder.m4thBGVdoOvrly = (ImageView) v4.findViewById(R.id.home_tab_four_4th_block_video_placeholder_imgVW);
				mlayoutFourVwHolder.m5thBGVdoOvrly = (ImageView) v4.findViewById(R.id.home_tab_four_5th_block_video_placeholder_imgVW);
				mlayoutFourVwHolder.m6thBGVdoOvrly = (ImageView) v4.findViewById(R.id.home_tab_four_6th_block_video_placeholder_imgVW);
				mlayoutFourVwHolder.mAdvtVideoOvrly = (ImageView) v4.findViewById(R.id.home_tab_four_videoOverly_imgVw);

				mlayoutFourVwHolder.mTxt1stTitle = (TextView) v4.findViewById(R.id.home_tab_four_1st_block_article_title_txtVw);
				mlayoutFourVwHolder.mTxt2ndTitle = (TextView) v4.findViewById(R.id.home_tab_four_2nd_block_article_title_txtVw);
				mlayoutFourVwHolder.mTxt3rdTitle = (TextView) v4.findViewById(R.id.home_tab_four_3rd_block_article_title_txtVw);
				mlayoutFourVwHolder.mTxt4thTitle = (TextView) v4.findViewById(R.id.home_tab_four_4th_block_article_title_txtVw);
				mlayoutFourVwHolder.mTxt5thTitle = (TextView) v4.findViewById(R.id.home_tab_four_5th_block_article_title_txtVw);
				mlayoutFourVwHolder.mTxt6thTitle = (TextView) v4.findViewById(R.id.home_tab_four_6th_block_article_title_txtVw);
				mlayoutFourVwHolder.mTxt1stURL = (TextView) v4.findViewById(R.id.home_tab_four_1st_block_url_txtVw);
				mlayoutFourVwHolder.mTxt2ndURL = (TextView) v4.findViewById(R.id.home_tab_four_2nd_block_url_txtVw);
				mlayoutFourVwHolder.mTxt3rdURL = (TextView) v4.findViewById(R.id.home_tab_four_3rd_block_url_txtVw);
				mlayoutFourVwHolder.mTxt4thURL = (TextView) v4.findViewById(R.id.home_tab_four_4th_block_url_txtVw);
				mlayoutFourVwHolder.mTxt5thURL = (TextView) v4.findViewById(R.id.home_tab_four_5th_block_url_txtVw);
				mlayoutFourVwHolder.mTxt6thURL = (TextView) v4.findViewById(R.id.home_tab_four_6th_block_url_txtVw);
				mlayoutFourVwHolder.mTxt1stDesc = (TextView) v4.findViewById(R.id.home_tab_four_1st_block_description_txtVw);
				mlayoutFourVwHolder.mTxt3rdDesc = (TextView) v4.findViewById(R.id.home_tab_four_3rd_block_description_txtVw);
				mlayoutFourVwHolder.mTxt4thDesc = (TextView) v4.findViewById(R.id.home_tab_four_4th_block_description_txtVw);
				mlayoutFourVwHolder.mTxt5thDesc = (TextView) v4.findViewById(R.id.home_tab_four_5th_block_description_txtVw);
				mlayoutFourVwHolder.mTxt6thDesc = (TextView) v4.findViewById(R.id.home_tab_four_6th_block_description_txtVw);

				mlayoutFourVwHolder.advertiseFlipper = (ViewFlipper) v4.findViewById(R.id.home_tab_four_viewFlipper);

				mlayoutFourVwHolder.m1Frame = (FrameLayout) v4.findViewById(R.id.home_tab_four_1st_block_frmLyt);
				mlayoutFourVwHolder.m2Frame = (FrameLayout) v4.findViewById(R.id.home_tab_four_2nd_block_frmLyt);
				mlayoutFourVwHolder.mAdvtLyt = (FrameLayout) v4.findViewById(R.id.home_tab_four_advt_frmLyt);
				mlayoutFourVwHolder.m3LnrLyt = (LinearLayout) v4.findViewById(R.id.home_tab_four_3rd_block_lnrLyt);
				mlayoutFourVwHolder.m4LnrLyt = (LinearLayout) v4.findViewById(R.id.home_tab_four_4th_block_lnrLyt);
				mlayoutFourVwHolder.m5LnrLyt = (LinearLayout) v4.findViewById(R.id.home_tab_four_5th_block_lnrLyt);
				mlayoutFourVwHolder.m6LnrLyt = (LinearLayout) v4.findViewById(R.id.home_tab_four_6th_block_lnrLyt);

				mlayoutFourVwHolder.m3MainLnrLyt = (LinearLayout) v4.findViewById(R.id.home_tab_four_3rd_main_lnrLyt);
				mlayoutFourVwHolder.m4MainLnrLyt = (LinearLayout) v4.findViewById(R.id.home_tab_four_4th_main_lnrLyt);
				mlayoutFourVwHolder.m5MainLnrLyt = (LinearLayout) v4.findViewById(R.id.home_tab_four_5th_main_lnrLyt);
				mlayoutFourVwHolder.m6MainLnrLyt = (LinearLayout) v4.findViewById(R.id.home_tab_four_6th_main_lnrLyt);

				mlayoutFourVwHolder.advertiseFlipper.setInAnimation(AnimationUtils.loadAnimation(context, R.anim.fade_in));
				mlayoutFourVwHolder.advertiseFlipper.setOutAnimation(AnimationUtils.loadAnimation(context, R.anim.fade_out));
				mlayoutFourVwHolder.advertiseFlipper.getInAnimation().setAnimationListener(new Animation.AnimationListener() {
					public void onAnimationStart(Animation animation)
					{
					}

					public void onAnimationRepeat(Animation animation)
					{
					}

					public void onAnimationEnd(Animation animation)
					{
						ImageView vv = (ImageView) ((LinearLayout) mlayoutFourVwHolder.advertiseFlipper.getCurrentView()).getChildAt(0);
						if (vv.getTag(R.string.ad_videourl) != null)
							mlayoutFourVwHolder.mAdvtVideoOvrly.setVisibility(View.VISIBLE);
						else
							mlayoutFourVwHolder.mAdvtVideoOvrly.setVisibility(View.GONE);
						mlayoutFourVwHolder.advertiseFlipper.setFlipInterval((Integer) vv.getTag(
								R.string.ad_tim_in_sec)/*
														 * ( ( ( Integer ) vv . getTag ( R . string
														 * . ad_tim_in_sec ) ) < 2000 ? 5 * 1000 : (
														 * Integer ) vv . getTag ( R . string .
														 * ad_tim_in_sec ) )
														 */);
					}
				});

				ColorUtils.setDescHeights(mlayoutFourVwHolder.mTxt1stDesc, mlayoutFourVwHolder.mTxt3rdDesc, mlayoutFourVwHolder.mTxt4thDesc, mlayoutFourVwHolder.mTxt5thDesc,
						mlayoutFourVwHolder.mTxt6thDesc);
				v4.setTag(mlayoutFourVwHolder);
			}
			else
			{
				mlayoutFourVwHolder = (LayoutfourVwHolder) v4.getTag();
			}

			// code for advertisements
			mAdvtData = mAdvt.getLyt4List();

			// code for the viewflipper
			if (mAdvtData != null && mAdvtData.size() > 0)
			{
				mlayoutFourVwHolder.advertiseFlipper.removeAllViews();
				for (int i = 0; i < mAdvtData.size(); i++)
				{
					View child = mInflater.inflate(R.layout.advt_item, null);
					ImageView image = (ImageView) child.findViewById(R.id.advt_item_row_imgVw);
					if (mAdvtData.get(i).getType().equalsIgnoreCase("embeded"))
					{
						mImageLoader.displayImage(mAdvtData.get(i).getEmbededThumbUrl(), image, Utility.getArticleDisplayOption(), wootrixILL);
						image.setTag(R.string.ad_videourl, mAdvtData.get(i).getVideoURL());
						image.setTag(R.string.ad_embedded_url, mAdvtData.get(i).getEmbededVideoUrl());
					}
					else if (mAdvtData.get(i).getType().equalsIgnoreCase("video"))
					{
						mImageLoader.displayImage(mAdvtData.get(i).getBannerURL(), image, Utility.getArticleDisplayOption(), wootrixILL);
						image.setTag(R.string.ad_videourl, mAdvtData.get(i).getVideoURL());
					}

					else
					{
						mImageLoader.displayImage(screenOrientation == 1 ? mAdvtData.get(i).getPortaitURL() : mAdvtData.get(i).getLandscapeURL(), image, Utility.getArticleDisplayOption(), wootrixILL);
						image.setTag(R.string.ad_videourl, null);
					}
					image.setTag(R.string.ad_link_url, mAdvtData.get(i).getLikeToOpen());
					image.setTag(R.string.ad_tim_in_sec, mAdvtData.get(i).getTimeInSeconds() * 1000);
					image.setTag(R.string.ad_advtID, mAdvtData.get(i).getAdvertiseID());

					mlayoutFourVwHolder.advertiseFlipper.addView(child);
				}

				if (mAdvtData.get(0).getType().equalsIgnoreCase("embeded"))
					mlayoutFourVwHolder.mAdvtVideoOvrly.setVisibility(View.VISIBLE);
				else if (mAdvtData.get(0).getType().equalsIgnoreCase("video"))
					mlayoutFourVwHolder.mAdvtVideoOvrly.setVisibility(View.VISIBLE);
				else
					mlayoutFourVwHolder.mAdvtVideoOvrly.setVisibility(View.GONE);
				mlayoutFourVwHolder.advertiseFlipper.setFlipInterval(mAdvtData.get(0).getTimeInSeconds() * 1000);
				mlayoutFourVwHolder.advertiseFlipper.setAutoStart(true);
				mlayoutFourVwHolder.mAdvtLyt.setTag(mlayoutFourVwHolder.advertiseFlipper);
				mlayoutFourVwHolder.mAdvtLyt.setOnClickListener(this);

			}
			else
			{
				mlayoutFourVwHolder.advertiseFlipper.removeAllViews();
				View chiled = mInflater.inflate(R.layout.advt_item, null);
				mlayoutFourVwHolder.advertiseFlipper.addView(chiled);
				mlayoutFourVwHolder.mAdvtVideoOvrly.setVisibility(View.GONE);
				mlayoutFourVwHolder.mAdvtLyt.setOnClickListener(null);
			}

			startPos = lastPos - 5;
			if (startPos < 0)
				startPos = 0;
			// for first grid item
			if (mList.size() > startPos && mList.get(startPos) != null)
			{
				modle = (ArticleModle) mList.get(startPos);

				ColorUtils.showViews(mlayoutFourVwHolder.mTxt1stTitle, mlayoutFourVwHolder.mTxt1stDesc, mlayoutFourVwHolder.mTxt1stURL);
				if (modle.getArticleType().equalsIgnoreCase("text"))
				{
					mlayoutFourVwHolder.m1stBG.setImageResource(defaultDrawable);
					ColorUtils.hideViews(mlayoutFourVwHolder.m1stBGPlcHoldr, mlayoutFourVwHolder.m1stBGVdoOvrly);
					ColorUtils.setColor(blackishColor, mlayoutFourVwHolder.mTxt1stTitle, mlayoutFourVwHolder.mTxt1stDesc);
				}
				else
				{
					ColorUtils.showViews(mlayoutFourVwHolder.m1stBGPlcHoldr);
					ColorUtils.setColor(whileColor, mlayoutFourVwHolder.mTxt1stTitle, mlayoutFourVwHolder.mTxt1stDesc);

					if (modle.getArticleType().equalsIgnoreCase("video"))
					{
						mlayoutFourVwHolder.m1stBGVdoOvrly.setVisibility(View.VISIBLE);
						mImageLoader.displayImage(modle.getCoverPhotoUrl(), mlayoutFourVwHolder.m1stBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
					else if (modle.getArticleType().equalsIgnoreCase("embedded"))
					{
						mlayoutFourVwHolder.m1stBGVdoOvrly.setVisibility(View.VISIBLE);
						mImageLoader.displayImage(modle.getEmbeddedThumb(), mlayoutFourVwHolder.m1stBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
					else
					{
						mlayoutFourVwHolder.m1stBGVdoOvrly.setVisibility(View.INVISIBLE);
						mImageLoader.displayImage(modle.getCoverPhotoUrl(), mlayoutFourVwHolder.m1stBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
				}
				mlayoutFourVwHolder.mTxt1stTitle.setText(modle.getTitle());

				if (modle.getSource() == null || modle.getSource().equalsIgnoreCase(""))
					mlayoutFourVwHolder.mTxt1stURL.setVisibility(View.GONE);
				else
				{
					mlayoutFourVwHolder.mTxt1stURL.setVisibility(View.VISIBLE);
					mlayoutFourVwHolder.mTxt1stURL.setText(modle.getSource());
				}
				mlayoutFourVwHolder.mTxt1stDesc.setText(Html.fromHtml(modle.getArticleDescPlain()));
				mlayoutFourVwHolder.m1Frame.setTag(startPos);
				mlayoutFourVwHolder.m1Frame.setOnClickListener(this);
			}
			else
			{
				mlayoutFourVwHolder.m1stBG.setImageResource(defaultDrawable);
				ColorUtils.hideViews(mlayoutFourVwHolder.m1stBGPlcHoldr, mlayoutFourVwHolder.m1stBGVdoOvrly, mlayoutFourVwHolder.mTxt1stTitle,
						mlayoutFourVwHolder.mTxt1stDesc, mlayoutFourVwHolder.mTxt1stURL);
				mlayoutFourVwHolder.m1Frame.setOnClickListener(null);
			}

			startPos++;
			// for second grid item
			if (mList.size() > startPos && mList.get(startPos) != null)
			{
				modle = (ArticleModle) mList.get(startPos);

				ColorUtils.showViews(mlayoutFourVwHolder.mTxt2ndTitle, mlayoutFourVwHolder.mTxt2ndURL);
				if (modle.getArticleType().equalsIgnoreCase("text"))
				{
					mlayoutFourVwHolder.m2ndBG.setImageResource(defaultDrawable);
					ColorUtils.hideViews(mlayoutFourVwHolder.m2ndBGPlcHoldr, mlayoutFourVwHolder.m2ndBGVdoOvrly);
					ColorUtils.setColor(blackishColor, mlayoutFourVwHolder.mTxt2ndTitle);
				}
				else
				{
					ColorUtils.showViews(mlayoutFourVwHolder.m2ndBGPlcHoldr);
					ColorUtils.setColor(whileColor, mlayoutFourVwHolder.mTxt2ndTitle);

					if (modle.getArticleType().equalsIgnoreCase("video"))
					{
						mlayoutFourVwHolder.m2ndBGVdoOvrly.setVisibility(View.VISIBLE);
						mImageLoader.displayImage(modle.getCoverPhotoUrl(), mlayoutFourVwHolder.m2ndBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
					else if (modle.getArticleType().equalsIgnoreCase("embedded"))
					{
						mlayoutFourVwHolder.m2ndBGVdoOvrly.setVisibility(View.VISIBLE);
						mImageLoader.displayImage(modle.getEmbeddedThumb(), mlayoutFourVwHolder.m2ndBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
					else
					{
						mlayoutFourVwHolder.m2ndBGVdoOvrly.setVisibility(View.INVISIBLE);
						mImageLoader.displayImage(modle.getCoverPhotoUrl(), mlayoutFourVwHolder.m2ndBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
				}
				mlayoutFourVwHolder.mTxt2ndTitle.setText(modle.getTitle());

				if (modle.getSource() == null || modle.getSource().equalsIgnoreCase(""))
					mlayoutFourVwHolder.mTxt2ndURL.setVisibility(View.GONE);
				else
				{
					mlayoutFourVwHolder.mTxt2ndURL.setVisibility(View.VISIBLE);
					mlayoutFourVwHolder.mTxt2ndURL.setText(modle.getSource());
				}
				mlayoutFourVwHolder.m2Frame.setTag(startPos);
				mlayoutFourVwHolder.m2Frame.setOnClickListener(this);
			}
			else
			{
				mlayoutFourVwHolder.m2ndBG.setImageResource(defaultDrawable);
				ColorUtils.hideViews(mlayoutFourVwHolder.m2ndBGPlcHoldr, mlayoutFourVwHolder.m2ndBGVdoOvrly, mlayoutFourVwHolder.mTxt2ndTitle,
						mlayoutFourVwHolder.mTxt2ndURL);
				mlayoutFourVwHolder.m2Frame.setOnClickListener(null);
			}

			startPos++;

			// for third grid item
			if (mList.size() > startPos && mList.get(startPos) != null)
			{
				mlayoutFourVwHolder.m3LnrLyt.setBackgroundColor(whileColor);
				ColorUtils.showViews(mlayoutFourVwHolder.mTxt3rdTitle, mlayoutFourVwHolder.mTxt3rdURL, mlayoutFourVwHolder.mTxt3rdDesc);
				modle = (ArticleModle) mList.get(startPos);
				if (modle.getArticleType().equalsIgnoreCase("text"))
				{
					mlayoutFourVwHolder.m3rdBG.setImageResource(defaultDrawable);
					ColorUtils.hideViews(mlayoutFourVwHolder.m3rdBGPlcHoldr, mlayoutFourVwHolder.m3rdBGVdoOvrly);
				}
				else
				{

					if (modle.getArticleType().equalsIgnoreCase("video"))
					{
						mlayoutFourVwHolder.m3rdBGVdoOvrly.setVisibility(View.VISIBLE);
						mImageLoader.displayImage(modle.getCoverPhotoUrl(), mlayoutFourVwHolder.m3rdBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
					else if (modle.getArticleType().equalsIgnoreCase("embedded"))
					{
						mlayoutFourVwHolder.m3rdBGVdoOvrly.setVisibility(View.VISIBLE);
						mImageLoader.displayImage(modle.getEmbeddedThumb(), mlayoutFourVwHolder.m3rdBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
					else
					{
						mlayoutFourVwHolder.m3rdBGVdoOvrly.setVisibility(View.INVISIBLE);
						mImageLoader.displayImage(modle.getCoverPhotoUrl(), mlayoutFourVwHolder.m3rdBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
				}
				mlayoutFourVwHolder.mTxt3rdTitle.setText(modle.getTitle());

				if (modle.getSource() == null || modle.getSource().equalsIgnoreCase(""))
					mlayoutFourVwHolder.mTxt3rdURL.setVisibility(View.GONE);
				else
				{
					mlayoutFourVwHolder.mTxt3rdURL.setVisibility(View.VISIBLE);
					mlayoutFourVwHolder.mTxt3rdURL.setText(modle.getSource());
				}
				mlayoutFourVwHolder.mTxt3rdDesc.setText(Html.fromHtml(modle.getArticleDescPlain()));
				mlayoutFourVwHolder.m3MainLnrLyt.setTag(startPos);
				mlayoutFourVwHolder.m3MainLnrLyt.setOnClickListener(this);
			}
			else
			{
				mlayoutFourVwHolder.m3rdBG.setImageResource(defaultDrawable);
				ColorUtils.hideViews(mlayoutFourVwHolder.m3rdBGPlcHoldr, mlayoutFourVwHolder.m3rdBGVdoOvrly, mlayoutFourVwHolder.mTxt3rdTitle, mlayoutFourVwHolder.mTxt3rdURL, mlayoutFourVwHolder.mTxt3rdDesc);
				mlayoutFourVwHolder.m3MainLnrLyt.setOnClickListener(null);
				mlayoutFourVwHolder.m3LnrLyt.setBackgroundColor(bgColor);
			}
			startPos++;
			// for third fourth item
			if (mList.size() > startPos && mList.get(startPos) != null)
			{
				mlayoutFourVwHolder.m4LnrLyt.setBackgroundColor(whileColor);
				modle = (ArticleModle) mList.get(startPos);

				ColorUtils.showViews(mlayoutFourVwHolder.mTxt4thTitle, mlayoutFourVwHolder.mTxt4thURL, mlayoutFourVwHolder.mTxt4thDesc);
				if (modle.getArticleType().equalsIgnoreCase("text"))
				{
					mlayoutFourVwHolder.m4thBG.setImageResource(defaultDrawable);
					ColorUtils.hideViews(mlayoutFourVwHolder.m4thBGPlcHoldr, mlayoutFourVwHolder.m4thBGVdoOvrly);
				}
				else
				{

					if (modle.getArticleType().equalsIgnoreCase("video"))
					{
						mlayoutFourVwHolder.m4thBGVdoOvrly.setVisibility(View.VISIBLE);
						mImageLoader.displayImage(modle.getCoverPhotoUrl(), mlayoutFourVwHolder.m4thBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
					else if (modle.getArticleType().equalsIgnoreCase("embedded"))
					{
						mlayoutFourVwHolder.m4thBGVdoOvrly.setVisibility(View.VISIBLE);
						mImageLoader.displayImage(modle.getEmbeddedThumb(), mlayoutFourVwHolder.m4thBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
					else
					{
						mlayoutFourVwHolder.m4thBGVdoOvrly.setVisibility(View.INVISIBLE);
						mImageLoader.displayImage(modle.getCoverPhotoUrl(), mlayoutFourVwHolder.m4thBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
				}
				mlayoutFourVwHolder.mTxt4thTitle.setText(modle.getTitle());

				if (modle.getSource() == null || modle.getSource().equalsIgnoreCase(""))
					mlayoutFourVwHolder.mTxt4thURL.setVisibility(View.GONE);
				else
				{
					mlayoutFourVwHolder.mTxt4thURL.setVisibility(View.VISIBLE);
					mlayoutFourVwHolder.mTxt4thURL.setText(modle.getSource());
				}
				mlayoutFourVwHolder.mTxt4thDesc.setText(Html.fromHtml(modle.getArticleDescPlain()));
				mlayoutFourVwHolder.m4MainLnrLyt.setTag(startPos);
				mlayoutFourVwHolder.m4MainLnrLyt.setOnClickListener(this);
			}
			else
			{
				mlayoutFourVwHolder.m4thBG.setImageResource(defaultDrawable);
				ColorUtils.hideViews(mlayoutFourVwHolder.m4thBGPlcHoldr, mlayoutFourVwHolder.m4thBGVdoOvrly, mlayoutFourVwHolder.mTxt4thTitle, mlayoutFourVwHolder.mTxt4thURL, mlayoutFourVwHolder.mTxt4thDesc);
				mlayoutFourVwHolder.m4MainLnrLyt.setOnClickListener(null);
				mlayoutFourVwHolder.m4LnrLyt.setBackgroundColor(bgColor);
			}

			startPos++;
			// for fifth item
			if (mList.size() > startPos && mList.get(startPos) != null)
			{
				mlayoutFourVwHolder.m5LnrLyt.setBackgroundColor(whileColor);
				modle = (ArticleModle) mList.get(startPos);
				ColorUtils.showViews(mlayoutFourVwHolder.mTxt5thTitle, mlayoutFourVwHolder.mTxt5thURL, mlayoutFourVwHolder.mTxt5thDesc);

				if (modle.getArticleType().equalsIgnoreCase("text"))
				{
					mlayoutFourVwHolder.m5thBG.setImageResource(defaultDrawable);
					ColorUtils.hideViews(mlayoutFourVwHolder.m5thBGPlcHoldr, mlayoutFourVwHolder.m5thBGVdoOvrly);
				}
				else
				{

					if (modle.getArticleType().equalsIgnoreCase("video"))
					{
						mlayoutFourVwHolder.m5thBGVdoOvrly.setVisibility(View.VISIBLE);
						mImageLoader.displayImage(modle.getCoverPhotoUrl(), mlayoutFourVwHolder.m5thBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
					else if (modle.getArticleType().equalsIgnoreCase("embedded"))
					{
						mlayoutFourVwHolder.m5thBGVdoOvrly.setVisibility(View.VISIBLE);
						mImageLoader.displayImage(modle.getEmbeddedThumb(), mlayoutFourVwHolder.m5thBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
					else
					{
						mlayoutFourVwHolder.m5thBGVdoOvrly.setVisibility(View.INVISIBLE);
						mImageLoader.displayImage(modle.getCoverPhotoUrl(), mlayoutFourVwHolder.m5thBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
				}
				mlayoutFourVwHolder.mTxt5thTitle.setText(modle.getTitle());

				if (modle.getSource() == null || modle.getSource().equalsIgnoreCase(""))
					mlayoutFourVwHolder.mTxt5thURL.setVisibility(View.GONE);
				else
				{
					mlayoutFourVwHolder.mTxt5thURL.setVisibility(View.VISIBLE);
					mlayoutFourVwHolder.mTxt5thURL.setText(modle.getSource());
				}
				mlayoutFourVwHolder.mTxt5thDesc.setText(Html.fromHtml(modle.getArticleDescPlain()));
				mlayoutFourVwHolder.m5MainLnrLyt.setTag(startPos);
				mlayoutFourVwHolder.m5MainLnrLyt.setOnClickListener(this);
			}
			else
			{
				mlayoutFourVwHolder.m5thBG.setImageResource(defaultDrawable);
				ColorUtils.hideViews(mlayoutFourVwHolder.m5thBGPlcHoldr, mlayoutFourVwHolder.m5thBGVdoOvrly, mlayoutFourVwHolder.mTxt5thTitle, mlayoutFourVwHolder.mTxt5thURL, mlayoutFourVwHolder.mTxt5thDesc);
				mlayoutFourVwHolder.m5MainLnrLyt.setOnClickListener(null);
				mlayoutFourVwHolder.m5LnrLyt.setBackgroundColor(bgColor);
			}

			startPos++;
			// for sixth item
			if (mList.size() > startPos && mList.get(startPos) != null)
			{
				mlayoutFourVwHolder.m6LnrLyt.setBackgroundColor(whileColor);
				modle = (ArticleModle) mList.get(startPos);

				ColorUtils.showViews(mlayoutFourVwHolder.mTxt6thTitle, mlayoutFourVwHolder.mTxt6thURL, mlayoutFourVwHolder.mTxt6thDesc);
				if (modle.getArticleType().equalsIgnoreCase("text"))
				{
					mlayoutFourVwHolder.m6thBG.setImageResource(defaultDrawable);
					ColorUtils.hideViews(mlayoutFourVwHolder.m6thBGPlcHoldr, mlayoutFourVwHolder.m6thBGVdoOvrly);
				}
				else
				{

					if (modle.getArticleType().equalsIgnoreCase("video"))
					{
						mlayoutFourVwHolder.m6thBGVdoOvrly.setVisibility(View.VISIBLE);
						mImageLoader.displayImage(modle.getCoverPhotoUrl(), mlayoutFourVwHolder.m6thBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
					else if (modle.getArticleType().equalsIgnoreCase("embedded"))
					{
						mlayoutFourVwHolder.m6thBGVdoOvrly.setVisibility(View.VISIBLE);
						mImageLoader.displayImage(modle.getEmbeddedThumb(), mlayoutFourVwHolder.m6thBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
					else
					{
						mlayoutFourVwHolder.m6thBGVdoOvrly.setVisibility(View.INVISIBLE);
						mImageLoader.displayImage(modle.getCoverPhotoUrl(), mlayoutFourVwHolder.m6thBG, Utility.getBigArticleDisplayOption(), wootrixILL);
					}
				}
				mlayoutFourVwHolder.mTxt6thTitle.setText(modle.getTitle());

				if (modle.getSource() == null || modle.getSource().equalsIgnoreCase(""))
					mlayoutFourVwHolder.mTxt6thURL.setVisibility(View.GONE);
				else
				{
					mlayoutFourVwHolder.mTxt6thURL.setVisibility(View.VISIBLE);
					mlayoutFourVwHolder.mTxt6thURL.setText(modle.getSource());
				}
				mlayoutFourVwHolder.mTxt6thDesc.setText(Html.fromHtml(modle.getArticleDescPlain()));
				mlayoutFourVwHolder.m6MainLnrLyt.setTag(startPos);
				mlayoutFourVwHolder.m6MainLnrLyt.setOnClickListener(this);
			}
			else
			{
				mlayoutFourVwHolder.m6thBG.setImageResource(defaultDrawable);
				ColorUtils.hideViews(mlayoutFourVwHolder.m6thBGPlcHoldr, mlayoutFourVwHolder.m6thBGVdoOvrly, mlayoutFourVwHolder.mTxt6thTitle, mlayoutFourVwHolder.mTxt6thURL, mlayoutFourVwHolder.mTxt6thDesc);
				mlayoutFourVwHolder.m6MainLnrLyt.setOnClickListener(null);
				mlayoutFourVwHolder.m6LnrLyt.setBackgroundColor(bgColor);
			}

			return v4;
		}
	}

	public void update(ArrayList<IModel> list)
	{
		mList = list;
		notifyDataSetChanged();
	}



	@Override
	public void onClick(View v)
	{
		switch ( v.getId() )
		{
			case R.id.home_tab_one_1st_block_main_LnrLyt :
			case R.id.home_tab_one_2nd_block_main_lntLyt :
			case R.id.home_tab_one_3rd_block_main_lntLyt :
			case R.id.home_tab_two_1st_block_main_lnrLyt :
			case R.id.home_tab_three_1st_block_main_lnrLyt :
			case R.id.home_tab_three_2nd_block_frame_lyt :
			case R.id.home_tab_three_3rd_block_main_lnrLyt :
			case R.id.home_tab_three_4th_block_frameLyt :
			case R.id.home_tab_three_5th_block_main_lnrLyt :
			case R.id.home_tab_four_1st_block_frmLyt :
			case R.id.home_tab_four_2nd_block_frmLyt :
			case R.id.home_tab_four_3rd_main_lnrLyt :
			case R.id.home_tab_four_4th_main_lnrLyt :
			case R.id.home_tab_four_5th_main_lnrLyt :
			case R.id.home_tab_four_6th_main_lnrLyt :
				try
				{
					int position = (Integer) v.getTag();
					if (mList.size() > position)
					{
						if (((ArticleModle) mList.get(position)).getArticleType().equalsIgnoreCase("video"))
						{
							try
							{
								Intent intent = new Intent(android.content.Intent.ACTION_VIEW);
								Uri uri = Uri.parse(((ArticleModle) mList.get(position)).getVideoURL());
								intent.setDataAndType(uri, "video/*");
								context.startActivity(intent);
							}
							catch (Exception e)
							{
								e.printStackTrace();
							}
						}
						else if (((ArticleModle) mList.get(position)).getArticleType().equalsIgnoreCase("embedded"))
						{
							Intent myIntent = new Intent(context, TabletEmbeddedArticleDetailActivity.class);
							myIntent.putExtra("articleData", mList.get(position));
							myIntent.putExtra("logoURL", logoURL);
							myIntent.putExtra("position", position);
							myIntent.putExtra("launchFrom", false);
							myIntent.putExtra("isMagzine", magazineData != null ? true : false);
							myIntent.putExtra("magzineID", magazineData != null ? magazineData.getMagazineId() : null);
							context.startActivity(myIntent);

                            if( accessRegisterListener != null ){
                                accessRegisterListener.sendAccessData(magazineData != null ? magazineData.getMagazineId() : null,
                                        ((ArticleModle) mList.get(position)).getArticleID(), "Tablet");
                            }

						}
						else
						{
							Intent myIntent = new Intent(context, TabletArticleDetailActivity.class);
							myIntent.putExtra("articleData", mList.get(position));
							myIntent.putExtra("logoURL", logoURL);
							myIntent.putExtra("position", position);
							myIntent.putExtra("launchFrom", false);
							myIntent.putExtra("isMagzine", magazineData != null ? true : false);
							myIntent.putExtra("magzineID", magazineData != null ? magazineData.getMagazineId() : null);
							context.startActivity(myIntent);

                            if( accessRegisterListener != null ){
                                accessRegisterListener.sendAccessData(magazineData != null ? magazineData.getMagazineId() : null,
                                        ((ArticleModle) mList.get(position)).getArticleID(), "Tablet");
                            }

						}
					}
				}
				catch (Exception e)
				{
					e.printStackTrace();
				}
				break;

			case R.id.home_tab_one_advt_frmLyt :
			case R.id.home_tab_two_advt_Frm_Lyt :
			case R.id.home_tab_three_advt_Lyt :
			case R.id.home_tab_four_advt_frmLyt :
				ViewFlipper vf = (ViewFlipper) v.getTag();
				if (vf != null)
				{
					ImageView vv = (ImageView) ((LinearLayout) vf.getCurrentView()).getChildAt(0);
					callAdvtService((String) vv.getTag(R.string.ad_advtID));

					try
					{
						String str = (String) vv.getTag(R.string.ad_videourl);
						if (str != null)
						{
							if (str.contains("iframe"))
							{
								/*
								 * TabletEmbeddedVideoActivity only for artical embedded video play
								 */
								Intent myIntent = new Intent(context, TabletEmbeddedVideoActivity.class);
								String embeddedVideoUrl = (String) vv.getTag(R.string.ad_embedded_url);
								myIntent.putExtra("advtURL", embeddedVideoUrl);
								myIntent.putExtra("type", "embeded");
								context.startActivity(myIntent);

							}
							else
							{
								Intent intent = new Intent(Intent.ACTION_VIEW);
								intent.setDataAndType(Uri.parse((String) vv.getTag(R.string.ad_videourl)), "video/mp4");
								context.startActivity(intent);
							}
						}
						else
						{

							String url = (String) vv.getTag(R.string.ad_link_url);

							if( url.contains("sharepoint") ){
								Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse(url));
								context.startActivity(browserIntent);
							} else {

								Intent myIntent = new Intent(context, TabletAdvertViewActivity.class);
								myIntent.putExtra("advtURL", url);
								Log.d("ADV URL", (String) vv.getTag(R.string.ad_link_url));
								myIntent.putExtra("type", "link");
								context.startActivity(myIntent);

							}

						}
					}
					catch (Exception e)
					{
						e.printStackTrace();
					}
				}
				break;

			default :
				break;
		}
	}

	private void callAdvtService(String advtID)
	{
		Intent intent = new Intent(context, RestService.class);
		intent.putExtra(RestService.URL_KEY, APIUtils.REPORT_ADVERTISEMENT);
		intent.putExtra(RestService.DATA_MAP_KEY, getMyFeedParams(advtID));
		context.startService(intent);
	}

	private HashMap<String, Object> getMyFeedParams(String feedId)
	{
		HashMap<String, Object> myFeedParam = new HashMap<String, Object>();
		myFeedParam.put("advertisementId", feedId);
		myFeedParam.put("appLanguage", Utility.getDrfaultLanguage());
		myFeedParam.put("token", Utility.getSharedPrefStringData(context, Constants.USER_TOKEN));
		myFeedParam.put("device", "Android");
		myFeedParam.put("type", magazineData == null ? "open" : "magazine");
		myFeedParam.put("latitude", mGpsTracker.getLatitude());
		myFeedParam.put("longitude", mGpsTracker.getLongitude());

		return myFeedParam;

	}

	public void updatePageNumber(int i)
	{
		this.pageNumber = i;
	}

	private int lastIndexofArticleInPage(int pageNumber)
	{
		if (pageNumber >= 0)
		{
			if (pageNumber == 0)
				return 2;
			else if (pageNumber == 1)
				return 3;
			else if (pageNumber == 2)
				return 8;
			else if (pageNumber == 3)
				return 14;
			else if (pageNumber % 4 == 0)
			{
				return 3 + lastIndexofArticleInPage(pageNumber - 1);
			}
			else if (pageNumber % 4 == 1)
			{
				return 1 + lastIndexofArticleInPage(pageNumber - 1);
			}
			else if (pageNumber % 4 == 2)
			{
				return 5 + lastIndexofArticleInPage(pageNumber - 1);
			}
			else
			{
				return 6 + lastIndexofArticleInPage(pageNumber - 1);
			}
		}
		else
			return 0;
	}

	// static view holder class for holding layout one variables
	private static class LayoutOneVwHolder
	{
		ImageView		m1stBG, m2ndBG, m3rdBG, m1stBGPlcHolder, m2ndBGPlcHolder, m3rdBGPlcHolder, m1stBGVideoOvrly, m2ndBGVideoOvrly, m3rdBGVideoOvrly, mAdvtVideoOvrly;
		TextView		mTxt1stTitle, mTxt2ndTitle, mTxt3rdTitle;
		TextView		mTxt1stURL, mTxt2ndURL, mTxt3rdURL;
		TextView		mTxt1stDesc, mTxt2ndDesc, mTxt3rdDesc;
		ViewFlipper		advertiseFlipper;
		LinearLayout	m1stLyt, m2ndLyt, m3rdLyt, m1stBGLyt, m2ndBGLyt, m3rdBGLyt;
		FrameLayout		mAdvtLyt;
	}

	// static view holder class for holding layout two variables
	private static class LayoutTwoVwHolder
	{
		ImageView		m1stBG, m1stBGPlcHolder, m1stBGVideoOvrly, mAdvtVideoOvrly;
		TextView		mTxt1stTitle;
		TextView		mTxt1stURL;
		TextView		mTxt1stDesc;
		ViewFlipper		advertiseFlipper;
		LinearLayout	m1stLyt, m1stBGLyt;
		FrameLayout		mAdvtLyt;
	}

	// static view holder class for holding layout three variables
	private static class LayoutThreeVwHolder
	{
		ImageView		m1stBG, m2ndBG, m3rdBG, m4thBG, m5thBG, m1stBGPlcHoldr, m2ndBGPlcHoldr, m3rdBGPlcHoldr, m4thBGPlcHoldr, m5thBGPlcHoldr, m1stBGVdoOvrly, m2ndBGVdoOvrly, m3rdBGVdoOvrly,
				m4thBGVdoOvrly, m5thBGVdoOvrly, mAdvtVideoOvrly;
		TextView		mTxt1stTitle, mTxt2ndTitle, mTxt3rdTitle, mTxt4thTitle, mTxt5thTitle;
		TextView		mTxt1stURL, mTxt2ndURL, mTxt3rdURL, mTxt4thURL, mTxt5thURL;
		TextView		mTxt1stDesc, mTxt2ndDesc, mTxt3rdDesc, mTxt5ndDesc;
		ViewFlipper		advertiseFlipper;
		LinearLayout	m1LnrLyt, m3LnrLyt, m5LnrLyt, m1MainLnrLyt, m3MainLnrLyt, m5MainLnrLyt;
		FrameLayout		m2FramLyt, m4FramLyt, mAdvtLyt;
	}

	// static view holder class for holding layout four variables
	private static class LayoutfourVwHolder
	{
		ImageView		m1stBG, m2ndBG, m3rdBG, m4thBG, m5thBG, m6thBG, m1stBGPlcHoldr, m2ndBGPlcHoldr, m3rdBGPlcHoldr, m4thBGPlcHoldr, m5thBGPlcHoldr, m6thBGPlcHoldr, m1stBGVdoOvrly, m2ndBGVdoOvrly,
				m3rdBGVdoOvrly, m4thBGVdoOvrly, m5thBGVdoOvrly, m6thBGVdoOvrly, mAdvtVideoOvrly;
		TextView		mTxt1stTitle, mTxt2ndTitle, mTxt3rdTitle, mTxt4thTitle, mTxt5thTitle, mTxt6thTitle;
		TextView		mTxt1stURL, mTxt2ndURL, mTxt3rdURL, mTxt4thURL, mTxt5thURL, mTxt6thURL;
		TextView		mTxt1stDesc, mTxt3rdDesc, mTxt4thDesc, mTxt5thDesc, mTxt6thDesc;
		ViewFlipper		advertiseFlipper;

		LinearLayout	m3LnrLyt, m4LnrLyt, m5LnrLyt, m6LnrLyt, m3MainLnrLyt, m4MainLnrLyt, m5MainLnrLyt, m6MainLnrLyt;
		FrameLayout		mAdvtLyt, m1Frame, m2Frame;
	}

	private class WootrixImageLoadingListener implements ImageLoadingListener
	{

		@Override
		public void onLoadingStarted(String imageUri, View view)
		{

		}

		@Override
		public void onLoadingFailed(String imageUri, View view, FailReason failReason)
		{

		}

		@Override
		public void onLoadingComplete(String imageUri, View view, Bitmap loadedImage)
		{
			// if (controller != null)
			// {
			// handler.postDelayed(new Runnable()
			// {
			//
			// @Override
			// public void run()
			// {
			// controller.refreshPage(controller.getSelectedItemPosition());
			// }
			// }, 500);
			//
			// Log.e("onPageRefreshed", "PageRefreshed called" +
			// controller.getSelectedItemPosition());
			// }
		}

		@Override
		public void onLoadingCancelled(String imageUri, View view)
		{

		}
	}

	/*
	 * public void playWebVideo(WebView mContent) { WebSettings webViewSettings =
	 * mContent.getSettings(); webViewSettings.setJavaScriptCanOpenWindowsAutomatically(true);
	 * webViewSettings.setJavaScriptEnabled(true); webViewSettings.setBuiltInZoomControls(false);
	 * mContent.setInitialScale(150); mContent.setX(-5); mContent.setY(-5);
	 * mContent.setFocusable(false); mContent.setWebChromeClient(new WebChromeClient() { public void
	 * onProgressChanged(WebView view, int progress) { } }); final String mimeType = "text/html";
	 * final String encoding = "UTF-8"; String html =
	 * "<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/k3OP1lzQbVE\" frameborder=\"0\" allowfullscreen></iframe>\"\n"
	 * ; mContent.loadDataWithBaseURL("", html, mimeType, encoding, ""); }
	 */
}
