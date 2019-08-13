package com.ta.wootrix.tablet;

import android.Manifest;
import android.annotation.TargetApi;
import android.app.Activity;
import android.app.Fragment;
import android.content.Context;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.graphics.Bitmap;
import android.os.Build;
import android.os.Bundle;
import android.os.Handler;
import android.support.v4.app.ActivityCompat;
import android.support.v4.content.ContextCompat;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.CheckBox;
import android.widget.CompoundButton;
import android.widget.CompoundButton.OnCheckedChangeListener;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.nostra13.universalimageloader.core.ImageLoader;
import com.nostra13.universalimageloader.core.assist.FailReason;
import com.nostra13.universalimageloader.core.assist.ImageLoadingListener;
import com.ta.wootrix.R;
import com.ta.wootrix.customDialog.CustomProgressDialog;
import com.ta.wootrix.customDialog.SetImageFromCamera;
import com.ta.wootrix.restapi.APIUtils;
import com.ta.wootrix.utils.Constants;
import com.ta.wootrix.utils.Utility;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.File;

public class MyAccountFragment extends Fragment implements OnClickListener
{
	protected Handler				mPhotoUpdateHandler		= new Handler();
	private ImageView				mImgVwMainProfileImg;
	private ImageView				mImgVwRoundProfileImg;
	private ImageView				mImgVwUpdateImage;
	private TextView				mTxtVwProfileName;
	private TextView				mTxtVwEmail;
	private CheckBox				mChkBoxShowPage;
	private TextView				mTxtVwChangeLanguage;
	// private TextView mTxtVwChangeEmail;
	private ImageView				mImgVwBackBtn;
	private ImageLoader				mImageLoader;
	private SetImageFromCamera		mImgFromCamera;
	private String					mImagePath;
	private CustomProgressDialog	mProgressDioalog;
	private Context					mContext;
	private static final int		PERMISSION_REQUEST_CODE	= 1;
	private View					v;

	@Override
	public void onAttach(Activity activity)
	{
		super.onAttach(activity);
		mContext = activity;
	}

	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState)
	{
		setRetainInstance(true);
		if (v == null)
		{
			v = inflater.inflate(R.layout.profile_popup, container, false);
			initView(v);
		}
		return v;
	}

	private void initView(View v)
	{
		mImgVwBackBtn = (ImageView) v.findViewById(R.id.profile_popup_back_imgVw);

		mImgVwMainProfileImg = (ImageView) v.findViewById(R.id.profile_screen_main_profile_img_imgVw);
		mImgVwRoundProfileImg = (ImageView) v.findViewById(R.id.profile_screen_rounded_profile_img_imgVw);
		mImgVwUpdateImage = (ImageView) v.findViewById(R.id.profile_screen_update_image_imgVw);

		mTxtVwProfileName = (TextView) v.findViewById(R.id.profile_screen_profilename_txtVw);
		mTxtVwEmail = (TextView) v.findViewById(R.id.profile_screen_email_txtVw);

		mChkBoxShowPage = (CheckBox) v.findViewById(R.id.profile_screen_showpage_toggle_btn);

		mTxtVwChangeLanguage = (TextView) v.findViewById(R.id.profile_screen_change_language_txtVw);

		/*
		 * mTxtVwChangeEmail = (TextView) v .findViewById(R.id.profile_screen_change_email_txtVw);
		 * mTxtVwChangeEmail.setOnClickListener(this);
		 */
		mImgVwUpdateImage.setOnClickListener(this);

		mTxtVwChangeLanguage.setOnClickListener(this);
		mImgVwBackBtn.setOnClickListener(this);
		mImgVwRoundProfileImg.setOnClickListener(this);
		((LinearLayout) v.findViewById(R.id.profile_popup_main_lnrlyt)).setOnClickListener(this);
		mChkBoxShowPage.setOnCheckedChangeListener(new OnCheckedChangeListener() {
			@Override
			public void onCheckedChanged(CompoundButton buttonView, boolean isChecked)
			{
				if (isChecked)
				{
					Utility.setSharedPrefBooleanData(mContext, Constants.USER_SHOW_PAGE, false);
					((FragCommuInterface) mContext).showHidePagingSlider(true);
				}
				else
				{
					Utility.setSharedPrefBooleanData(mContext, Constants.USER_SHOW_PAGE, true);
					((FragCommuInterface) mContext).showHidePagingSlider(false);
				}
			}
		});
		updateData();
	}

	private void updateData()
	{
		mTxtVwProfileName.setText(Utility.getSharedPrefStringData(mContext, Constants.USER_NAME));
		if (mImageLoader == null)
			mImageLoader = Utility.getImageLoader(mContext);
		String imageURL = Utility.getSharedPrefStringData(mContext, Constants.USER_IMAGE);
		mImageLoader.displayImage(imageURL, mImgVwMainProfileImg, Utility.getProfileBlurredPicDisplayOption(), new ImageLoadingListener() {
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
				if (loadedImage != null)
					mImgVwMainProfileImg.setImageBitmap(fastblur(loadedImage, 8));
			}

			@Override
			public void onLoadingCancelled(String imageUri, View view)
			{
			}
		});
		mImageLoader.displayImage(imageURL, mImgVwRoundProfileImg, Utility.getProfilePicDisplayOption());

		if (!Utility.getSharedPrefBooleanData(mContext, Constants.USER_SHOW_PAGE))
			mChkBoxShowPage.setChecked(true);
		else
			mChkBoxShowPage.setChecked(false);
	}

	@Override
	public void onResume()
	{
		 super.onResume();
		 mTxtVwEmail.setText(Utility.getSharedPrefStringData(mContext, Constants.USER_EMAIL));
		 /*
		 * if (Utility .getSharedPrefBooleanData(mContext, Constants.USER_REQ_EMAIL))
		 * mTxtVwChangeEmail.setText(getString(R.string.add_email)); else
		 * mTxtVwChangeEmail.setVisibility(View.GONE); if
		 * (Utility.getSharedPrefBooleanData(mContext, Constants.USER_REQ_PASS))
		 * mTxtVwChangePassword.setText(getString(R.string.add_pass)); else
		 * mTxtVwChangePassword.setText(getString(R.string.lbl_change_pass));
		 */
	}

	@Override
	public void onClick(View v)
	{
		switch ( v.getId() )
		{

			case R.id.profile_popup_back_imgVw :
				getFragmentManager().popBackStack();
				break;

			case R.id.profile_screen_rounded_profile_img_imgVw :
			case R.id.profile_screen_update_image_imgVw :

				int currentAPIVersion = Build.VERSION.SDK_INT;
				if (currentAPIVersion >= android.os.Build.VERSION_CODES.M) {
					boolean result = checkPermission();
					if (result) {
						if (mImgFromCamera == null)
							mImgFromCamera = new SetImageFromCamera(getActivity(), this);
						mImgFromCamera.showCameraDialog();
					}
				}
				else
				{
					if (mImgFromCamera == null)
						mImgFromCamera = new SetImageFromCamera(getActivity(), this);
					mImgFromCamera.showCameraDialog();
				}

				break;

			case R.id.profile_screen_change_language_txtVw :
				((TabletHomeActivity) mContext).switchFragments(new ChangeLangFragment());
				break;

			default :
				break;
		}
	}

	@TargetApi(Build.VERSION_CODES.JELLY_BEAN)
	public boolean checkPermission() {
		int currentAPIVersion = Build.VERSION.SDK_INT;
		if (currentAPIVersion >= android.os.Build.VERSION_CODES.M) {
			if (ContextCompat.checkSelfPermission(mContext, Manifest.permission.CAMERA) != PackageManager.PERMISSION_GRANTED
					|| ContextCompat.checkSelfPermission(mContext, Manifest.permission.WRITE_EXTERNAL_STORAGE) != PackageManager.PERMISSION_GRANTED) {

				if (ActivityCompat.shouldShowRequestPermissionRationale((Activity) mContext, Manifest.permission.CAMERA)
                        || ActivityCompat.shouldShowRequestPermissionRationale((Activity) mContext, Manifest.permission.WRITE_EXTERNAL_STORAGE)) {
					ActivityCompat.requestPermissions((Activity) mContext, new String[]{Manifest.permission.CAMERA, Manifest.permission.WRITE_EXTERNAL_STORAGE}, PERMISSION_REQUEST_CODE);
				} else {
					ActivityCompat.requestPermissions((Activity) mContext, new String[]{Manifest.permission.CAMERA, Manifest.permission.WRITE_EXTERNAL_STORAGE}, PERMISSION_REQUEST_CODE);
				}

				return false;

			} else {
				return true;
			}
		} else {
			return true;
		}
	}

	@Override
	public void onRequestPermissionsResult(int requestCode, String[] permissions, int[] grantResults)
	{
		super.onRequestPermissionsResult(requestCode, permissions, grantResults);
		switch ( requestCode )
		{
			case PERMISSION_REQUEST_CODE :
				if (grantResults.length > 0 && grantResults[0] == PackageManager.PERMISSION_GRANTED)
				{
					if (mImgFromCamera == null)
						mImgFromCamera = new SetImageFromCamera(getActivity(), this);
					mImgFromCamera.showCameraDialog();
				} else if (grantResults.length > 1 && grantResults[1] == PackageManager.PERMISSION_GRANTED)
                {
                    if (mImgFromCamera == null)
                        mImgFromCamera = new SetImageFromCamera(getActivity(), this);
                    mImgFromCamera.showCameraDialog();
                }
				else
				{
					Toast.makeText(getActivity(), "Permission Denied, You cannot access Camera or Files.", Toast.LENGTH_LONG).show();
				}
				break;
		}
	}

	public void setProfileImage(String mImagePath, Bitmap image)
	{
		this.mImagePath = mImagePath;
		updatePhotoOnServer();
	}

	private void updatePhotoOnServer()
	{
		if (mProgressDioalog != null)
			mProgressDioalog.dismissDialog();
		mProgressDioalog = new CustomProgressDialog(mContext, "");

		new Thread(new Runnable() {
			String response = "";

			@Override
			public void run()
			{
				JSONObject json = null;
				try
				{
					json = new JSONObject();
					json.put("token", Utility.getSharedPrefStringData(mContext, Constants.USER_TOKEN));
					json.put("appLanguage", Utility.getDrfaultLanguage());
				}
				catch (JSONException e)
				{
					e.printStackTrace();
				}

				Log.e("params for first", json.toString());
				String url = APIUtils.getBaseURL(APIUtils.UPDATE_PROFILE_PHOTO);
				response = Utility.httpPostRequestToServerWithImageData(url, json.toString(), mContext, new File(mImagePath));
				mPhotoUpdateHandler.post(new Runnable() {
					@Override
					public void run()
					{
						mProgressDioalog.dismissDialog();
						if (response != null && response.length() > 0)
						{
							JSONObject JsonObject;
							try
							{
								Log.e("msg", "is:" + response);
								JsonObject = new JSONObject(response);
								if (JsonObject.optBoolean("success"))
								{
									JSONObject mainObject = JsonObject.getJSONArray("data").getJSONObject(0);

									JSONObject userOb = mainObject.getJSONArray("user").getJSONObject(0);
									Utility.setSharedPrefStringData(mContext, Constants.USER_IMAGE, userOb.optString("photoUrl").toString());
									Utility.showToastMessage(mContext, JsonObject.optString("message"));
									((FragCommuInterface) mContext).onImageUpdate(userOb.optString("photoUrl").toString());
									updateData();
								}
								else
								{
									Utility.showMsgDialog(mContext, JsonObject.optString("message"));
								}

							}
							catch (JSONException e)
							{
								e.printStackTrace();
								mProgressDioalog.dismissDialog();
							}

						}

					}
				});

			}
		}).start();

	}

	// @Override
	// public void onActivityResult(int requestCode, int resultCode, Intent
	// data)
	// {
	// super.onActivityResult(requestCode, resultCode, data);
	// if (mImgFromCamera != null)
	// mImgFromCamera.onImgTknFrmCamOrGalleryResult(requestCode, resultCode,
	// data);
	// }

	@Override
	public void onActivityResult(int requestCode, int resultCode, Intent data)
	{
		super.onActivityResult(requestCode, resultCode, data);
		if (mImgFromCamera != null)
			mImgFromCamera.onImgTknFrmCamOrGalleryResult(requestCode, resultCode, data);
	}

	public Bitmap fastblur(Bitmap sentBitmap, int radius)
	{

		// Stack Blur v1.0 from
		// http://www.quasimondo.com/StackBlurForCanvas/StackBlurDemo.html
		//
		// Java Author: Mario Klingemann <mario at quasimondo.com>
		// http://incubator.quasimondo.com
		// created Feburary 29, 2004
		// Android port : Yahel Bouaziz <yahel at kayenko.com>
		// http://www.kayenko.com
		// ported april 5th, 2012

		// This is a compromise between Gaussian Blur and Box blur
		// It creates much better looking blurs than Box Blur, but is
		// 7x faster than my Gaussian Blur implementation.
		//
		// I called it Stack Blur because this describes best how this
		// filter works internally: it creates a kind of moving stack
		// of colors whilst scanning through the image. Thereby it
		// just has to add one new block of color to the right side
		// of the stack and remove the leftmost color. The remaining
		// colors on the topmost layer of the stack are either added on
		// or reduced by one, depending on if they are on the right or
		// on the left side of the stack.
		//
		// If you are using this algorithm in your code please add
		// the following line:
		//
		// Stack Blur Algorithm by Mario Klingemann <mario@quasimondo.com>

		Bitmap bitmap = sentBitmap.copy(sentBitmap.getConfig(), true);

		if (radius < 1)
		{
			return (null);
		}

		int w = bitmap.getWidth();
		int h = bitmap.getHeight();

		int[] pix = new int[w * h];
		Log.e("pix", w + " " + h + " " + pix.length);
		bitmap.getPixels(pix, 0, w, 0, 0, w, h);

		int wm = w - 1;
		int hm = h - 1;
		int wh = w * h;
		int div = radius + radius + 1;

		int r[] = new int[wh];
		int g[] = new int[wh];
		int b[] = new int[wh];
		int rsum, gsum, bsum, x, y, i, p, yp, yi, yw;
		int vmin[] = new int[Math.max(w, h)];

		int divsum = (div + 1) >> 1;
		divsum *= divsum;
		int dv[] = new int[256 * divsum];
		for (i = 0; i < 256 * divsum; i++)
		{
			dv[i] = (i / divsum);
		}

		yw = yi = 0;

		int[][] stack = new int[div][3];
		int stackpointer;
		int stackstart;
		int[] sir;
		int rbs;
		int r1 = radius + 1;
		int routsum, goutsum, boutsum;
		int rinsum, ginsum, binsum;

		for (y = 0; y < h; y++)
		{
			rinsum = ginsum = binsum = routsum = goutsum = boutsum = rsum = gsum = bsum = 0;
			for (i = -radius; i <= radius; i++)
			{
				p = pix[yi + Math.min(wm, Math.max(i, 0))];
				sir = stack[i + radius];
				sir[0] = (p & 0xff0000) >> 16;
				sir[1] = (p & 0x00ff00) >> 8;
				sir[2] = (p & 0x0000ff);
				rbs = r1 - Math.abs(i);
				rsum += sir[0] * rbs;
				gsum += sir[1] * rbs;
				bsum += sir[2] * rbs;
				if (i > 0)
				{
					rinsum += sir[0];
					ginsum += sir[1];
					binsum += sir[2];
				}
				else
				{
					routsum += sir[0];
					goutsum += sir[1];
					boutsum += sir[2];
				}
			}
			stackpointer = radius;

			for (x = 0; x < w; x++)
			{

				r[yi] = dv[rsum];
				g[yi] = dv[gsum];
				b[yi] = dv[bsum];

				rsum -= routsum;
				gsum -= goutsum;
				bsum -= boutsum;

				stackstart = stackpointer - radius + div;
				sir = stack[stackstart % div];

				routsum -= sir[0];
				goutsum -= sir[1];
				boutsum -= sir[2];

				if (y == 0)
				{
					vmin[x] = Math.min(x + radius + 1, wm);
				}
				p = pix[yw + vmin[x]];

				sir[0] = (p & 0xff0000) >> 16;
				sir[1] = (p & 0x00ff00) >> 8;
				sir[2] = (p & 0x0000ff);

				rinsum += sir[0];
				ginsum += sir[1];
				binsum += sir[2];

				rsum += rinsum;
				gsum += ginsum;
				bsum += binsum;

				stackpointer = (stackpointer + 1) % div;
				sir = stack[(stackpointer) % div];

				routsum += sir[0];
				goutsum += sir[1];
				boutsum += sir[2];

				rinsum -= sir[0];
				ginsum -= sir[1];
				binsum -= sir[2];

				yi++;
			}
			yw += w;
		}
		for (x = 0; x < w; x++)
		{
			rinsum = ginsum = binsum = routsum = goutsum = boutsum = rsum = gsum = bsum = 0;
			yp = -radius * w;
			for (i = -radius; i <= radius; i++)
			{
				yi = Math.max(0, yp) + x;

				sir = stack[i + radius];

				sir[0] = r[yi];
				sir[1] = g[yi];
				sir[2] = b[yi];

				rbs = r1 - Math.abs(i);

				rsum += r[yi] * rbs;
				gsum += g[yi] * rbs;
				bsum += b[yi] * rbs;

				if (i > 0)
				{
					rinsum += sir[0];
					ginsum += sir[1];
					binsum += sir[2];
				}
				else
				{
					routsum += sir[0];
					goutsum += sir[1];
					boutsum += sir[2];
				}

				if (i < hm)
				{
					yp += w;
				}
			}
			yi = x;
			stackpointer = radius;
			for (y = 0; y < h; y++)
			{
				// Preserve alpha channel: ( 0xff000000 & pix[yi] )
				pix[yi] = (0xff000000 & pix[yi]) | (dv[rsum] << 16) | (dv[gsum] << 8) | dv[bsum];

				rsum -= routsum;
				gsum -= goutsum;
				bsum -= boutsum;

				stackstart = stackpointer - radius + div;
				sir = stack[stackstart % div];

				routsum -= sir[0];
				goutsum -= sir[1];
				boutsum -= sir[2];

				if (x == 0)
				{
					vmin[y] = Math.min(y + r1, hm) * w;
				}
				p = x + vmin[y];

				sir[0] = r[p];
				sir[1] = g[p];
				sir[2] = b[p];

				rinsum += sir[0];
				ginsum += sir[1];
				binsum += sir[2];

				rsum += rinsum;
				gsum += ginsum;
				bsum += binsum;

				stackpointer = (stackpointer + 1) % div;
				sir = stack[stackpointer];

				routsum += sir[0];
				goutsum += sir[1];
				boutsum += sir[2];

				rinsum -= sir[0];
				ginsum -= sir[1];
				binsum -= sir[2];

				yi += w;
			}
		}

		Log.e("pix", w + " " + h + " " + pix.length);
		bitmap.setPixels(pix, 0, w, 0, 0, w, h);

		return (bitmap);
	}
}