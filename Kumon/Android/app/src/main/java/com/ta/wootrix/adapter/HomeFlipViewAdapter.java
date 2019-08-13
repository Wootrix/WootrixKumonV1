package com.ta.wootrix.adapter;

import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.net.Uri;
import android.text.Html;
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
import com.ta.wootrix.modle.ArticleModle;
import com.ta.wootrix.modle.IModel;
import com.ta.wootrix.modle.MagazineModle;
import com.ta.wootrix.phone.AdvertisementViewActivity;
import com.ta.wootrix.phone.ArticleDetailActivity;
import com.ta.wootrix.phone.EmbeddedArticleDetailActivity;
import com.ta.wootrix.phone.EmbeddedVideoActivity;
import com.ta.wootrix.restapi.APIUtils;
import com.ta.wootrix.restapi.RestService;
import com.ta.wootrix.utils.AccessRegisterListener;
import com.ta.wootrix.utils.ColorUtils;
import com.ta.wootrix.utils.Constants;
import com.ta.wootrix.utils.GPSTracker;
import com.ta.wootrix.utils.Utility;

import java.util.ArrayList;
import java.util.HashMap;

public class HomeFlipViewAdapter extends BaseAdapter implements OnClickListener
{
	LayoutInflater						mInflater;
	ArticleModle						modle;
	ArrayList<AdvertisementModle>		mAdvt;
	private ArrayList<IModel>			mList;
	private Context						context;
	private int							pageNumber;
	private int							whileColor;
	private int							blackishColor;
	private ImageLoader					mImageLoader;
	private GPSTracker					mGpsTracker;
	private WootrixImageLoadingListener	wootrixILL;
	private int							defaultDrawable;
	private MagazineModle				magazineData;
    private AccessRegisterListener      accessRegisterListener;

	public HomeFlipViewAdapter(Context con, ArrayList<IModel> mArticleList, int pageNumber, ArrayList<AdvertisementModle> mAdvtData, GPSTracker mGpsTracker2, MagazineModle magazineData)
	{
		this.context = con;
		mInflater = (LayoutInflater) con.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
		this.mList = mArticleList;
		this.mAdvt = mAdvtData;
		this.pageNumber = pageNumber;
		mImageLoader = Utility.getImageLoader(con);
		whileColor = con.getResources().getColor(R.color.white);
		blackishColor = con.getResources().getColor(R.color.black_heading);
		defaultDrawable = R.drawable.article_layout2_placeholder;
		this.mGpsTracker = mGpsTracker2;
		this.magazineData = magazineData;
	}

    public HomeFlipViewAdapter(Context con, ArrayList<IModel> mArticleList, int pageNumber, ArrayList<AdvertisementModle> mAdvtData, GPSTracker mGpsTracker2, MagazineModle magazineData, AccessRegisterListener accessRegisterListener)
    {
        this.context = con;
        mInflater = (LayoutInflater) con.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        this.mList = mArticleList;
        this.mAdvt = mAdvtData;
        this.pageNumber = pageNumber;
        mImageLoader = Utility.getImageLoader(con);
        whileColor = con.getResources().getColor(R.color.white);
        blackishColor = con.getResources().getColor(R.color.black_heading);
        defaultDrawable = R.drawable.article_layout2_placeholder;
        this.mGpsTracker = mGpsTracker2;
        this.magazineData = magazineData;
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
	public long getItemId(int position)
	{
		return position;
	}

	@Override
	public View getView(int pageNumber, View convertView, ViewGroup parent)
	{
		final ViewHolder holder;
		View view = convertView;
		int lastPos = lastIndexofArticleInPage(pageNumber);
		int startPos = 0;
		if (wootrixILL == null)
			wootrixILL = new WootrixImageLoadingListener();
		if (view == null)
		{
			view = mInflater.inflate(R.layout.home_layout_row, null, false);
			holder = new ViewHolder();

			holder.m1stFrame = (FrameLayout) view.findViewById(R.id.home_layout_1st_frmLyt);
			holder.m2ndFrame = (FrameLayout) view.findViewById(R.id.home_layout_2nd_frmLyt);
			holder.m3rdFrame = (FrameLayout) view.findViewById(R.id.home_layout_3rd_frmLyt);
			holder.m5thFrame = (FrameLayout) view.findViewById(R.id.home_layout_5th_frmLyt);
			holder.m6thFrame = (FrameLayout) view.findViewById(R.id.home_layout_6th_frmLyt);
			holder.mAdvtFrame = (FrameLayout) view.findViewById(R.id.home_layout_advt_frmLyt);

			holder.m1stBG = (ImageView) view.findViewById(R.id.home_1st_block_bg_imgVW);
			holder.m2ndBG = (ImageView) view.findViewById(R.id.home_2nd_block_bg_imgVW);
			holder.m3rdBG = (ImageView) view.findViewById(R.id.home_3rd_block_bg_imgVW);
			holder.m5thBG = (ImageView) view.findViewById(R.id.home_5th_block_bg_imgVW);
			holder.m6thBG = (ImageView) view.findViewById(R.id.home_6th_block_bg_imgVW);
			holder.mAdvtFlipper = (ViewFlipper) view.findViewById(R.id.home_layout_row_viewFlipper);

			holder.m1stIMGPlcHldr = (ImageView) view.findViewById(R.id.home_1st_block_bg_placeholder_imgVW);
			holder.m2ndIMGPlcHldr = (ImageView) view.findViewById(R.id.home_2nd_block_bg_placeholder_imgVW);
			holder.m3rdIMGPlcHldr = (ImageView) view.findViewById(R.id.home_3rd_block_bg_placeholder_imgVW);
			holder.m5thIMGPlcHldr = (ImageView) view.findViewById(R.id.home_5th_block_bg_placeholder_imgVW);
			holder.m6thIMGPlcHldr = (ImageView) view.findViewById(R.id.home_6th_block_bg_placeholder_imgVW);

			holder.m1stVideoOvrLy = (ImageView) view.findViewById(R.id.home_1st_block_video_placeholder_imgVW);
			holder.m2ndVideoOvrLy = (ImageView) view.findViewById(R.id.home_2nd_block_video_placeholder_imgVW);
			holder.m3rdVideoOvrLy = (ImageView) view.findViewById(R.id.home_3rd_block_video_placeholder_imgVW);
			holder.mAdvtVdoOvrLy = (ImageView) view.findViewById(R.id.home_4th_block_video_placeholder_imgVW);
			holder.m5thVideoOvrLy = (ImageView) view.findViewById(R.id.home_5th_block_video_placeholder_imgVW);
			holder.m6thVideoOvrLy = (ImageView) view.findViewById(R.id.home_6th_block_video_placeholder_imgVW);

			holder.m1stTitle = (TextView) view.findViewById(R.id.home_1st_block_title_txtVw);
			holder.m2ndTitle = (TextView) view.findViewById(R.id.home_2nd_block_title_txtVw);
			holder.m3rdTitle = (TextView) view.findViewById(R.id.home_3rd_block_title_txtVw);
			holder.m5thTitle = (TextView) view.findViewById(R.id.home_5th_block_title_txtVw);
			holder.m6thTitle = (TextView) view.findViewById(R.id.home_6th_block_title_txtVw);

			holder.m1stURL = (TextView) view.findViewById(R.id.home_1st_block_url_txtVw);
			holder.m2ndURL = (TextView) view.findViewById(R.id.home_2nd_block_url_txtVw);
			holder.m3rdURL = (TextView) view.findViewById(R.id.home_3rd_block_url_txtVw);
			holder.m5thURL = (TextView) view.findViewById(R.id.home_5th_block_url_txtVw);
			holder.m6thURL = (TextView) view.findViewById(R.id.home_6th_block_url_txtVw);

			holder.m1stDesc = (TextView) view.findViewById(R.id.home_1st_block_desc_txtVw);
			holder.m2ndDesc = (TextView) view.findViewById(R.id.home_2nd_block_desc_txtVw);
			holder.m3rdDesc = (TextView) view.findViewById(R.id.home_3rd_block_desc_txtVw);
			holder.m5thDesc = (TextView) view.findViewById(R.id.home_5th_block_desc_txtVw);
			holder.m6thDesc = (TextView) view.findViewById(R.id.home_6th_block_desc_txtVw);
			holder.mAdvtFlipper.setInAnimation(AnimationUtils.loadAnimation(context, R.anim.fade_in));
			holder.mAdvtFlipper.setOutAnimation(AnimationUtils.loadAnimation(context, R.anim.fade_out));
			holder.mAdvtFlipper.getInAnimation().setAnimationListener(new Animation.AnimationListener() {
				public void onAnimationStart(Animation animation)
				{
				}

				public void onAnimationRepeat(Animation animation)
				{
				}

				public void onAnimationEnd(Animation animation)
				{
					try
					{
						ImageView vv = (ImageView) ((LinearLayout) holder.mAdvtFlipper.getCurrentView()).getChildAt(0);
						if (vv.getTag(R.string.ad_videourl) != null)
							holder.mAdvtVdoOvrLy.setVisibility(View.VISIBLE);
						else
							holder.mAdvtVdoOvrLy.setVisibility(View.GONE);
						holder.mAdvtFlipper.setFlipInterval((Integer) vv.getTag(
								R.string.ad_tim_in_sec)/*
														 * ((Integer ) vv.getTag ( R.string .
														 * ad_tim_in_sec )) < 2000 ? 5000 : (Integer
														 * ) vv.getTag ( R.string . ad_tim_in_sec )
														 */);
					}
					catch (Exception e)
					{
						e.printStackTrace();
					}
				}
			});

			view.setTag(holder);
		}
		else
		{
			holder = (ViewHolder) convertView.getTag();
		}
		// code for the viewflipper
		if (mAdvt != null && mAdvt.size() > 0)
		{
			holder.mAdvtFlipper.removeAllViews();
			for (int i = 0; i < mAdvt.size(); i++)
			{
				View child = mInflater.inflate(R.layout.advt_item, null);
				ImageView image = (ImageView) child.findViewById(R.id.advt_item_row_imgVw);

				if (mAdvt.get(i).getType().equalsIgnoreCase("embeded"))
				{
					mImageLoader.displayImage(mAdvt.get(i).getEmbededThumbUrl(), image, Utility.getArticleDisplayOption(), wootrixILL);
					image.setTag(R.string.ad_videourl, mAdvt.get(i).getVideoURL());
					String str = mAdvt.get(i).getEmbededVideoUrl();
					image.setTag(R.string.ad_embedded_url, mAdvt.get(i).getEmbededVideoUrl());
					image.setTag(R.string.ad_tim_in_sec, mAdvt.get(i).getTimeInSeconds() * 1000);
					image.setTag(R.string.ad_advtID, mAdvt.get(i).getAdvertiseID());
				}
				else if (mAdvt.get(i).getType().equalsIgnoreCase("video"))
				{

					mImageLoader.displayImage(mAdvt.get(i).getBannerURL(), image, Utility.getArticleDisplayOption(), wootrixILL);
					image.setTag(R.string.ad_videourl, mAdvt.get(i).getVideoURL());
					image.setTag(R.string.ad_link_url, mAdvt.get(i).getLikeToOpen());
					image.setTag(R.string.ad_tim_in_sec, mAdvt.get(i).getTimeInSeconds() * 1000);
					image.setTag(R.string.ad_advtID, mAdvt.get(i).getAdvertiseID());
				}
				else
				{
					mImageLoader.displayImage(mAdvt.get(i).getPortaitURL(), image, Utility.getArticleDisplayOption(), wootrixILL);
					image.setTag(R.string.ad_videourl, null);
					image.setTag(R.string.ad_link_url, mAdvt.get(i).getLikeToOpen());
					image.setTag(R.string.ad_tim_in_sec, mAdvt.get(i).getTimeInSeconds() * 1000);
					image.setTag(R.string.ad_advtID, mAdvt.get(i).getAdvertiseID());
				}

				holder.mAdvtFlipper.addView(child);

			}

			if (mAdvt.get(0).getType().equalsIgnoreCase("video"))
				holder.mAdvtVdoOvrLy.setVisibility(View.VISIBLE);
			else if (mAdvt.get(0).getType().equalsIgnoreCase("embeded"))
				holder.mAdvtVdoOvrLy.setVisibility(View.VISIBLE);
			else
				holder.mAdvtVdoOvrLy.setVisibility(View.GONE);

			holder.mAdvtFlipper.setFlipInterval(mAdvt.get(0).getTimeInSeconds() * 1000);
			holder.mAdvtFlipper.setAutoStart(true);
			holder.mAdvtFrame.setTag(holder.mAdvtFlipper);
			holder.mAdvtFrame.setOnClickListener(this);

		}
		else
		{
			holder.mAdvtFlipper.removeAllViews();
			View chiled = mInflater.inflate(R.layout.advt_item, null);
			holder.mAdvtVdoOvrLy.setVisibility(View.GONE);
			holder.mAdvtFlipper.addView(chiled);
			holder.mAdvtFrame.setOnClickListener(null);
		}

		if (pageNumber % 2 == 0)
		{
			startPos = lastPos - 3;
			if (startPos < 0)
				startPos = 0;
			holder.m2ndFrame.setVisibility(View.GONE);
		}
		else
		{
			startPos = lastPos - 4;
			if (startPos < 0)
				startPos = 0;
			holder.m2ndFrame.setVisibility(View.VISIBLE);
		}
		// code for first frame
		if (mList.size() > startPos && mList.get(startPos) != null)
		{
			ColorUtils.showViews(holder.m1stURL, holder.m1stTitle, holder.m1stDesc);
			modle = (ArticleModle) mList.get(startPos);
			if (modle.getArticleType().equalsIgnoreCase("text"))
			{
				holder.m1stBG.setImageResource(defaultDrawable);
				ColorUtils.hideViews(/* holder.m1stBG, */holder.m1stIMGPlcHldr, holder.m1stVideoOvrLy);
				ColorUtils.setColor(blackishColor, holder.m1stTitle, holder.m1stDesc);
			}
			else
			{
				ColorUtils.showViews(holder.m1stIMGPlcHldr);
				ColorUtils.setColor(whileColor, holder.m1stTitle, holder.m1stDesc);

				if (modle.getArticleType().equalsIgnoreCase("video"))
				{
					holder.m1stVideoOvrLy.setVisibility(View.VISIBLE);
					mImageLoader.displayImage(modle.getCoverPhotoUrl(), holder.m1stBG, Utility.getBigArticleDisplayOption(), wootrixILL);
				}
				else if (modle.getArticleType().equalsIgnoreCase("embedded"))
				{
					holder.m1stVideoOvrLy.setVisibility(View.VISIBLE);
					mImageLoader.displayImage(modle.getEmbeddedThumb(), holder.m1stBG, Utility.getBigArticleDisplayOption(), wootrixILL);
				}
				else
				{
					holder.m1stVideoOvrLy.setVisibility(View.INVISIBLE);
					mImageLoader.displayImage(modle.getCoverPhotoUrl(), holder.m1stBG, Utility.getBigArticleDisplayOption(), wootrixILL);
				}
				holder.m1stBG.setTag(pageNumber);
			}
			holder.m1stTitle.setText(modle.getTitle());
			if (modle.getSource() == null || modle.getSource().equalsIgnoreCase(""))
				holder.m1stURL.setVisibility(View.GONE);
			else
			{
				holder.m1stURL.setVisibility(View.VISIBLE);
				holder.m1stURL.setText(modle.getSource());
			}

			holder.m1stDesc.setText(Html.fromHtml(modle.getArticleDescPlain()));
			holder.m1stFrame.setTag(startPos);
			holder.m1stFrame.setOnClickListener(this);
		}
		else
		{
			holder.m1stBG.setImageResource(defaultDrawable);
			ColorUtils.hideViews(/* holder.m1stBG, */holder.m1stIMGPlcHldr, holder.m1stVideoOvrLy, holder.m1stURL, holder.m1stTitle, holder.m1stDesc);
			holder.m1stFrame.setOnClickListener(null);
		}
		startPos++;
		if (pageNumber % 2 != 0)
		{
			if (mList.size() > startPos && mList.get(startPos) != null)
			{
				ColorUtils.showViews(holder.m2ndURL, holder.m2ndTitle, holder.m2ndDesc);
				modle = (ArticleModle) mList.get(startPos);
				if (modle.getArticleType().equalsIgnoreCase("text"))
				{
					holder.m2ndBG.setImageResource(defaultDrawable);
					ColorUtils.hideViews(/* holder.m2ndBG, */holder.m2ndIMGPlcHldr, holder.m2ndVideoOvrLy);
					ColorUtils.setColor(blackishColor, holder.m2ndTitle, holder.m2ndDesc);
				}
				else
				{
					ColorUtils.showViews(holder.m2ndIMGPlcHldr);
					ColorUtils.setColor(whileColor, holder.m2ndTitle, holder.m2ndDesc);

					if (modle.getArticleType().equalsIgnoreCase("video"))
					{
						holder.m2ndVideoOvrLy.setVisibility(View.VISIBLE);
						mImageLoader.displayImage(modle.getCoverPhotoUrl(), holder.m2ndBG, Utility.getArticleDisplayOption(), wootrixILL);
					}
					else if (modle.getArticleType().equalsIgnoreCase("embedded"))
					{
						holder.m2ndVideoOvrLy.setVisibility(View.VISIBLE);
						mImageLoader.displayImage(modle.getEmbeddedThumb(), holder.m2ndBG, Utility.getArticleDisplayOption(), wootrixILL);
					}
					else
					{
						holder.m2ndVideoOvrLy.setVisibility(View.INVISIBLE);
						mImageLoader.displayImage(modle.getCoverPhotoUrl(), holder.m2ndBG, Utility.getArticleDisplayOption(), wootrixILL);
					}
				}
				holder.m2ndTitle.setText(modle.getTitle());
				if (modle.getSource() == null || modle.getSource().equalsIgnoreCase(""))
					holder.m2ndURL.setVisibility(View.GONE);
				else
				{
					holder.m2ndURL.setVisibility(View.VISIBLE);
					holder.m2ndURL.setText(modle.getSource());
				}
				// holder.m2ndURL.setText(modle.getSource());
				holder.m2ndDesc.setText(Html.fromHtml(modle.getArticleDescPlain()));
				holder.m2ndFrame.setTag(startPos);
				holder.m2ndFrame.setOnClickListener(this);
			}
			else
			{
				holder.m2ndBG.setImageResource(defaultDrawable);
				ColorUtils.hideViews(/* holder.m2ndBG, */holder.m2ndIMGPlcHldr, holder.m2ndVideoOvrLy, holder.m2ndURL, holder.m2ndTitle, holder.m2ndDesc);
				holder.m2ndFrame.setOnClickListener(null);
			}
			startPos++;
		}

		// code for third frame
		if (mList.size() > startPos && mList.get(startPos) != null)
		{
			ColorUtils.showViews(holder.m3rdURL, holder.m3rdTitle, holder.m3rdDesc);
			modle = (ArticleModle) mList.get(startPos);
			if (modle.getArticleType().equalsIgnoreCase("text"))
			{
				holder.m3rdBG.setImageResource(defaultDrawable);
				ColorUtils.hideViews(/* holder.m3rdBG, */holder.m3rdIMGPlcHldr, holder.m3rdVideoOvrLy);
				ColorUtils.setColor(blackishColor, holder.m3rdTitle, holder.m3rdDesc);
			}
			else
			{
				ColorUtils.showViews(holder.m3rdIMGPlcHldr);
				ColorUtils.setColor(whileColor, holder.m3rdTitle, holder.m3rdDesc);

				if (modle.getArticleType().equalsIgnoreCase("video"))
				{
					holder.m3rdVideoOvrLy.setVisibility(View.VISIBLE);
					mImageLoader.displayImage(modle.getCoverPhotoUrl(), holder.m3rdBG, Utility.getArticleDisplayOption(), wootrixILL);
				}
				else if (modle.getArticleType().equalsIgnoreCase("embedded"))
				{
					holder.m3rdVideoOvrLy.setVisibility(View.VISIBLE);
					mImageLoader.displayImage(modle.getEmbeddedThumb(), holder.m3rdBG, Utility.getArticleDisplayOption(), wootrixILL);
				}
				else
				{
					holder.m3rdVideoOvrLy.setVisibility(View.INVISIBLE);
					mImageLoader.displayImage(modle.getCoverPhotoUrl(), holder.m3rdBG, Utility.getArticleDisplayOption(), wootrixILL);
				}
			}
			holder.m3rdTitle.setText(modle.getTitle());

			if (modle.getSource() == null || modle.getSource().equalsIgnoreCase(""))
				holder.m3rdURL.setVisibility(View.GONE);
			else
			{
				holder.m3rdURL.setVisibility(View.VISIBLE);
				holder.m3rdURL.setText(modle.getSource());
			}

			holder.m3rdDesc.setText(Html.fromHtml(modle.getArticleDescPlain()));
			holder.m3rdFrame.setTag(startPos);
			holder.m3rdFrame.setOnClickListener(this);
		}
		else
		{
			holder.m3rdBG.setImageResource(defaultDrawable);
			ColorUtils.hideViews(/* holder.m3rdBG, */holder.m3rdIMGPlcHldr, holder.m3rdVideoOvrLy, holder.m3rdURL, holder.m3rdTitle, holder.m3rdDesc);
			holder.m3rdFrame.setOnClickListener(null);
		}
		startPos++;

		// code for 5th frame
		if (mList.size() > startPos && mList.get(startPos) != null)
		{
			ColorUtils.showViews(holder.m5thTitle, holder.m5thURL, holder.m5thDesc);
			modle = (ArticleModle) mList.get(startPos);
			if (modle.getArticleType().equalsIgnoreCase("text"))
			{
				holder.m5thBG.setImageResource(defaultDrawable);
				ColorUtils.hideViews(/* holder.m5thBG, */holder.m5thIMGPlcHldr, holder.m5thVideoOvrLy);
				ColorUtils.setColor(blackishColor, holder.m5thTitle, holder.m5thDesc);
			}
			else
			{
				ColorUtils.showViews(holder.m5thIMGPlcHldr);
				ColorUtils.setColor(whileColor, holder.m5thTitle, holder.m5thDesc);

				if (modle.getArticleType().equalsIgnoreCase("video"))
				{
					holder.m5thVideoOvrLy.setVisibility(View.VISIBLE);
					mImageLoader.displayImage(modle.getCoverPhotoUrl(), holder.m5thBG, Utility.getArticleDisplayOption(), wootrixILL);
				}
				else if (modle.getArticleType().equalsIgnoreCase("embedded"))
				{
					holder.m5thVideoOvrLy.setVisibility(View.VISIBLE);
					mImageLoader.displayImage(modle.getEmbeddedThumb(), holder.m5thBG, Utility.getArticleDisplayOption(), wootrixILL);
				}
				else
				{
					holder.m5thVideoOvrLy.setVisibility(View.INVISIBLE);
					mImageLoader.displayImage(modle.getCoverPhotoUrl(), holder.m5thBG, Utility.getArticleDisplayOption(), wootrixILL);
				}
			}
			holder.m5thTitle.setText(modle.getTitle());

			if (modle.getSource() == null || modle.getSource().equalsIgnoreCase(""))
				holder.m5thURL.setVisibility(View.GONE);
			else
			{
				holder.m5thURL.setVisibility(View.VISIBLE);
				holder.m5thURL.setText(modle.getSource());
			}

			holder.m5thDesc.setText(Html.fromHtml(modle.getArticleDescPlain()));
			holder.m5thFrame.setTag(startPos);
			holder.m5thFrame.setOnClickListener(this);
		}
		else
		{
			holder.m5thBG.setImageResource(defaultDrawable);
			ColorUtils.hideViews(/* holder.m5thBG, */holder.m5thIMGPlcHldr, holder.m5thVideoOvrLy, holder.m5thTitle, holder.m5thURL);
			holder.m5thFrame.setOnClickListener(null);
		}
		startPos++;

		// code for 6th frame
		if (mList.size() > startPos && mList.get(startPos) != null)
		{
			ColorUtils.showViews(holder.m6thTitle, holder.m6thURL, holder.m6thDesc);
			modle = (ArticleModle) mList.get(startPos);
			if (modle.getArticleType().equalsIgnoreCase("text"))
			{
				holder.m6thBG.setImageResource(defaultDrawable);
				ColorUtils.hideViews(/* holder.m6thBG, */holder.m6thIMGPlcHldr, holder.m6thVideoOvrLy);
				ColorUtils.setColor(blackishColor, holder.m6thTitle,  holder.m6thDesc);
			}
			else
			{
				ColorUtils.showViews(holder.m6thIMGPlcHldr);
				ColorUtils.setColor(whileColor, holder.m6thTitle, holder.m6thDesc);

				if (modle.getArticleType().equalsIgnoreCase("video"))
				{
					holder.m6thVideoOvrLy.setVisibility(View.VISIBLE);
					mImageLoader.displayImage(modle.getCoverPhotoUrl(), holder.m6thBG, Utility.getArticleDisplayOption(), wootrixILL);
				}
				else if (modle.getArticleType().equalsIgnoreCase("embedded"))
				{
					holder.m6thVideoOvrLy.setVisibility(View.VISIBLE);
					mImageLoader.displayImage(modle.getEmbeddedThumb(), holder.m6thBG, Utility.getArticleDisplayOption(), wootrixILL);
				}
				else
				{
					holder.m6thVideoOvrLy.setVisibility(View.INVISIBLE);
					mImageLoader.displayImage(modle.getCoverPhotoUrl(), holder.m6thBG, Utility.getArticleDisplayOption(), wootrixILL);
				}
			}
			holder.m6thTitle.setText(modle.getTitle());

			if (modle.getSource() == null || modle.getSource().equalsIgnoreCase(""))
				holder.m6thURL.setVisibility(View.GONE);
			else
			{
				holder.m6thURL.setVisibility(View.VISIBLE);
				holder.m6thURL.setText(modle.getSource());
			}
			holder.m6thDesc.setText(Html.fromHtml(modle.getArticleDescPlain()));
			holder.m6thFrame.setTag(startPos);
			holder.m6thFrame.setOnClickListener(this);
		}
		else
		{
			holder.m6thBG.setImageResource(defaultDrawable);
			ColorUtils.hideViews(/* holder.m6thBG, */holder.m6thIMGPlcHldr, holder.m6thVideoOvrLy, holder.m6thTitle, holder.m6thURL);
			holder.m6thFrame.setOnClickListener(null);
		}

		return view;
	}

	public void update(ArrayList<IModel> list)
	{
		mList = list;
		notifyDataSetChanged();
	}

	@Override
	public void onClick(View v)
	{
		switch ( v.getId())
		{
			case R.id.home_layout_1st_frmLyt :
			case R.id.home_layout_2nd_frmLyt :
			case R.id.home_layout_3rd_frmLyt :
			case R.id.home_layout_5th_frmLyt :
			case R.id.home_layout_6th_frmLyt :
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
						Intent myIntent = new Intent(context, EmbeddedArticleDetailActivity.class);
						myIntent.putExtra("articleData", mList.get(position));
						myIntent.putExtra("position", position);
						myIntent.putExtra("launchFrom", true);
						myIntent.putExtra("isMagzine", magazineData != null ? true : false);
						myIntent.putExtra("magzineID", magazineData != null ? magazineData.getMagazineId() : null);
						context.startActivity(myIntent);

                        if( accessRegisterListener != null ){
                            accessRegisterListener.sendAccessData(magazineData != null ? magazineData.getMagazineId() : null,
                                    ((ArticleModle) mList.get(position)).getArticleID(), "Smartphone");
                        }

					}
					else
					{
						Intent myIntent = new Intent(context, ArticleDetailActivity.class);
						myIntent.putExtra("articleData", mList.get(position));
						myIntent.putExtra("position", position);
						myIntent.putExtra("launchFrom", true);
						myIntent.putExtra("isMagzine", magazineData != null ? true : false);
						myIntent.putExtra("magzineID", magazineData != null ? magazineData.getMagazineId() : null);
						context.startActivity(myIntent);

                        if( accessRegisterListener != null ){
                            accessRegisterListener.sendAccessData(magazineData != null ? magazineData.getMagazineId() : null,
                                    ((ArticleModle) mList.get(position)).getArticleID(), "Smartphone");
                        }

					}
				}
				break;

			case R.id.home_layout_advt_frmLyt :
				ViewFlipper vf = (ViewFlipper) v.getTag();
				ImageView vv = (ImageView) ((LinearLayout) vf.getCurrentView()).getChildAt(0);
				// {
				// "token":"1",
				// "latitude":"42.2423",
				// "longitude":"-100.84",
				// "advertisementId":"2",
				// "device":"Android",
				// "appLanguage":"en"
				// }

				callAdvtService((String) vv.getTag(R.string.ad_advtID));
				String str = (String) vv.getTag(R.string.ad_videourl);
				if (str != null)
				{
					if (str.contains("iframe"))
					{
						/* embedded video activity is only for advertisment flow */
						Intent myIntent = new Intent(context, EmbeddedVideoActivity.class);
						String embeddedVideoUrl = (String) vv.getTag(R.string.ad_embedded_url);
						myIntent.putExtra("advtURL", embeddedVideoUrl);
						myIntent.putExtra("type", "embeded");
						context.startActivity(myIntent);

					}
					{
						try
						{
							Intent intent = new Intent(Intent.ACTION_VIEW);
							intent.setDataAndType(Uri.parse((String) vv.getTag(R.string.ad_videourl)), "video/mp4");
							context.startActivity(intent);
						}
						catch (Exception e)
						{
							e.printStackTrace();
						}
					}

				}
				else
				{
					try {

						String url = (String) vv.getTag(R.string.ad_link_url);

						if( url.contains("sharepoint") ){
                            Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse(url));
                            context.startActivity(browserIntent);
                        } else {
                            Intent myIntent = new Intent(context, AdvertisementViewActivity.class);
                            myIntent.putExtra("advtURL", url);
                            myIntent.putExtra("type", "link");
                            context.startActivity(myIntent);
                        }

					} catch (Exception e) {
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
				return 3;
			else if (pageNumber == 1)
				return 8;
			else if (pageNumber % 2 == 0)
			{
				return 4 + lastIndexofArticleInPage(pageNumber - 1);
			}
			else
			{
				return 5 + lastIndexofArticleInPage(pageNumber - 1);
			}
		}
		else
			return 0;
	}

	@Override
	public Object getItem(int position)
	{
		return null;
	}

	private static class ViewHolder
	{
		ImageView	m1stBG, m2ndBG, m3rdBG, m5thBG, m6thBG, m1stIMGPlcHldr, m2ndIMGPlcHldr, m3rdIMGPlcHldr, m5thIMGPlcHldr, m6thIMGPlcHldr, m1stVideoOvrLy, m2ndVideoOvrLy, m3rdVideoOvrLy,
				m5thVideoOvrLy, m6thVideoOvrLy, mAdvtVdoOvrLy;
		ViewFlipper	mAdvtFlipper;
		TextView	m1stTitle, m2ndTitle, m3rdTitle, m5thTitle, m6thTitle;
		TextView	m1stURL, m2ndURL, m3rdURL, m5thURL, m6thURL;
		TextView	m1stDesc, m2ndDesc, m3rdDesc, m5thDesc, m6thDesc;
		FrameLayout	m1stFrame, m2ndFrame, m3rdFrame, m5thFrame, m6thFrame, mAdvtFrame;
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
			// controller.refreshPage(controller.getSelectedItemPosition());
			// Log.e("onPageRefreshed", "PageRefreshed called");
			// }
		}

		@Override
		public void onLoadingCancelled(String imageUri, View view)
		{

		}
	}

	/*
	 * public void playWebVideo(final WebView mWebView) { WebSettings webViewSettings =
	 * mWebView.getSettings(); webViewSettings.setJavaScriptCanOpenWindowsAutomatically(true);
	 * webViewSettings.setJavaScriptEnabled(true); webViewSettings.setBuiltInZoomControls(false);
	 * mWebView.setInitialScale(150); mWebView.setX(-5); mWebView.setY(-5);
	 * mWebView.setClickable(false); mWebView.setLongClickable(false); mWebView.setFocusable(false);
	 * mWebView.setFocusableInTouchMode(false); mWebView.setOnTouchListener(new
	 * View.OnTouchListener() {
	 * @Override public boolean onTouch(View v, MotionEvent event) { View parent = (View)
	 * v.getParent(); parent.performClick(); Toast.makeText(context, " web view clicked",
	 * Toast.LENGTH_LONG) .show(); // mContent.setVisibility(View.GONE); switch (event.getAction())
	 * { case MotionEvent.ACTION_DOWN: case MotionEvent.ACTION_UP: if (!v.hasFocus()) {
	 * v.requestFocus(); callAdvtService((String) v.getTag(R.string.ad_advtID)); String webstr =
	 * (String) v.getTag(R.string.ad_videourl); Intent myIntent = new Intent(context,
	 * AdvertisementViewActivity.class); myIntent.putExtra("advtURL", webstr);
	 * myIntent.putExtra("type", "embeded"); context.startActivity(myIntent); } break; } return
	 * true; } }); final String mimeType = "text/html"; final String encoding = "UTF-8"; String
	 * htmlLink = (String) mWebView.getTag(R.string.ad_videourl); // String htmlLink = //
	 * "<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/k3OP1lzQbVE\" frameborder=\"0\" allowfullscreen></iframe>\"\n"
	 * ; mWebView.loadDataWithBaseURL("", htmlLink, mimeType, encoding, ""); }
	 */
}
