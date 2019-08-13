package com.ta.wootrix.utils;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;
import android.content.res.Configuration;
import android.graphics.Bitmap;
import android.graphics.Bitmap.Config;
import android.graphics.BitmapFactory;
import android.graphics.Matrix;
import android.media.ExifInterface;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.net.ParseException;
import android.util.Log;
import android.view.Gravity;
import android.view.View;
import android.view.animation.AccelerateInterpolator;
import android.view.animation.Animation;
import android.view.animation.TranslateAnimation;
import android.view.inputmethod.InputMethodManager;
import android.widget.EditText;
import android.widget.Toast;

import com.nostra13.universalimageloader.cache.disc.naming.Md5FileNameGenerator;
import com.nostra13.universalimageloader.core.DisplayImageOptions;
import com.nostra13.universalimageloader.core.ImageLoader;
import com.nostra13.universalimageloader.core.ImageLoaderConfiguration;
import com.nostra13.universalimageloader.core.assist.ImageScaleType;
import com.nostra13.universalimageloader.core.assist.QueueProcessingType;
import com.nostra13.universalimageloader.core.display.SimpleBitmapDisplayer;
import com.ta.wootrix.R;
import com.ta.wootrix.modle.LoginSignupModle;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.conn.ConnectTimeoutException;
import org.apache.http.entity.StringEntity;
import org.apache.http.entity.mime.HttpMultipartMode;
import org.apache.http.entity.mime.MultipartEntity;
import org.apache.http.entity.mime.content.FileBody;
import org.apache.http.entity.mime.content.StringBody;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicHeader;
import org.apache.http.params.BasicHttpParams;
import org.apache.http.params.HttpConnectionParams;
import org.apache.http.params.HttpParams;
import org.apache.http.protocol.HTTP;
import org.apache.http.util.EntityUtils;

import java.io.Closeable;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Locale;
import java.util.TimeZone;

public class Utility
{
	static final int					RC_REQUEST			= 10001;
	static final String					TAG					= "sampleBilling";
	public static int					TIMEOUT_CONNECTION	= 5000;				// 5sec
	public static int					TIMEOUT_SOCKET		= 30000;			// 30sec
	static int							posstion			= 0;
	static Context						mcontext;
	static DateFormat					utcFormat, pstFormat /* , format */;
	private static DisplayImageOptions	bigArticleOptions;

	// inFromLeftAnimation
	private static DisplayImageOptions	profilePicDisplayOption;
	private static DisplayImageOptions	profileburredOptions;
	private static DisplayImageOptions	topBannerOptions;
	private static DisplayImageOptions	articleOptions;
	private static DisplayImageOptions	magazineOptions;
	private static DisplayImageOptions	magazineCoverOptions;

	public static void updateAppDefaultLanguage(Context context, String language)
	{
		Configuration config = context.getResources().getConfiguration();
		Locale locale = new Locale(language);
		Locale.setDefault(locale);
		config.locale = locale;
		context.getResources().updateConfiguration(config, context.getResources().getDisplayMetrics());
	}

	// outToLeftAnimation
	public static Animation outToLeftAnimation()
	{
		Animation outtoLeft = new TranslateAnimation(Animation.RELATIVE_TO_PARENT, 0.0f, Animation.RELATIVE_TO_PARENT, -1.0f, Animation.RELATIVE_TO_PARENT, 0.0f, Animation.RELATIVE_TO_PARENT, 0.0f);
		outtoLeft.setDuration(500);
		outtoLeft.setInterpolator(new AccelerateInterpolator());
		return outtoLeft;
	}

	public static Animation inFromLeftAnimation()
	{
		Animation inFromLeft = new TranslateAnimation(Animation.RELATIVE_TO_PARENT, -1.0f, Animation.RELATIVE_TO_PARENT, 0.0f, Animation.RELATIVE_TO_PARENT, 0.0f, Animation.RELATIVE_TO_PARENT, 0.0f);
		inFromLeft.setDuration(500);
		inFromLeft.setInterpolator(new AccelerateInterpolator());
		return inFromLeft;
	}

	public static void showMsgDialog(Context context, String msg)
	{
		new AlertDialog.Builder(context, R.style.custom_dialog_theme)

				.setMessage(msg).setIcon(R.drawable.app_icon).setCancelable(false).setPositiveButton(context.getResources().getString(android.R.string.ok), new DialogInterface.OnClickListener() {
					public void onClick(DialogInterface dialog, int whichButton)
					{
						dialog.dismiss();

					}
				}).create().show();
	}

	public static void showActivityFinishDilaog(final Context context, String msg)
	{
		new AlertDialog.Builder(context, R.style.custom_dialog_theme).setMessage(msg).setIcon(R.drawable.app_icon).setCancelable(false).setTitle(context.getString(R.string.app_name))
				.setPositiveButton(context.getResources().getString(android.R.string.ok), new DialogInterface.OnClickListener() {
					public void onClick(DialogInterface dialog, int whichButton)
					{
						dialog.dismiss();
						((Activity) context).finish();
					}
				}).create().show();
	}

	public static String getDrfaultLanguage()
	{
		return Locale.getDefault().getLanguage();
	}

	public static String getCustomizedAppLanguage()
	{
		// english/spanish/portuguese
		String lang = Locale.getDefault().getLanguage();
		if (lang.equalsIgnoreCase("en"))
			return "english";
		else if (lang.equalsIgnoreCase("es"))
			return "spanish";
		else
			return "portuguese";

	}

	public static ImageLoader getImageLoader(Context context)
	{
		ImageLoader imageLoader = ImageLoader.getInstance();
		if (imageLoader.isInited())
			return imageLoader;
		else
		{
			ImageLoaderConfiguration config = new ImageLoaderConfiguration.Builder(context).threadPriority(Thread.NORM_PRIORITY - 2).denyCacheImageMultipleSizesInMemory()
					.discCacheFileNameGenerator(new Md5FileNameGenerator()).tasksProcessingOrder(QueueProcessingType.LIFO).writeDebugLogs()
					/*
					 * .defaultDisplayImageOptions( new DisplayImageOptions.Builder
					 * ().showStubImage(R.drawable.app_icon)
					 * .showImageForEmptyUri(R.drawable.app_icon) .showImageOnFail
					 * (R.drawable.app_icon).cacheInMemory(true). cacheOnDisc(true) .displayer(new
					 * SimpleBitmapDisplayer()).build()) // Remove for release app
					 */.build();
			// Initialize ImageLoader with configuration.
			imageLoader.init(config);
			return imageLoader;

		}
	}

	public final static Bitmap getUnRotatedImage(String imahePath, Bitmap rotattedBitmap)
	{
		int rotate = 0;
		try
		{
			File imageFile = new File(imahePath);
			ExifInterface exif = new ExifInterface(imageFile.getAbsolutePath());
			int orientation = exif.getAttributeInt(ExifInterface.TAG_ORIENTATION, ExifInterface.ORIENTATION_NORMAL);

			switch ( orientation )
			{
				case ExifInterface.ORIENTATION_ROTATE_270 :
					rotate = 270;
					break;
				case ExifInterface.ORIENTATION_ROTATE_180 :
					rotate = 180;
					break;
				case ExifInterface.ORIENTATION_ROTATE_90 :
					rotate = 90;
					break;
			}
		}
		catch (Exception e)
		{
			e.printStackTrace();
			return null;
		}
		if (rotate != 0)
		{
			Matrix matrix = new Matrix();
			matrix.postRotate(rotate);

			return Bitmap.createBitmap(rotattedBitmap, 0, 0, rotattedBitmap.getWidth(), rotattedBitmap.getHeight(), matrix, true);
		}
		else
			return rotattedBitmap;
	}

	public final static Bitmap getMyUnRotatedImage(String imahePath, Bitmap rotattedBitmap)
	{
		int rotate = 0;
		try
		{
			File imageFile = new File(imahePath);
			ExifInterface exif = new ExifInterface(imageFile.getAbsolutePath());
			int orientation = exif.getAttributeInt(ExifInterface.TAG_ORIENTATION, ExifInterface.ORIENTATION_NORMAL);

			switch ( orientation )
			{
				case ExifInterface.ORIENTATION_ROTATE_270 :
					rotate = 90;
					break;
				case ExifInterface.ORIENTATION_ROTATE_180 :
					rotate = 360;
					break;
				case ExifInterface.ORIENTATION_ROTATE_90 :
					rotate = 270;
					break;
				default :
					rotate = 180;
			}
		}
		catch (Exception e)
		{
			e.printStackTrace();
			return null;
		}
		if (rotate != 0)
		{
			Matrix matrix = new Matrix();
			matrix.postRotate(rotate);

			return Bitmap.createBitmap(rotattedBitmap, 0, 0, rotattedBitmap.getWidth(), rotattedBitmap.getHeight(), matrix, true);
		}
		else
			return rotattedBitmap;
	}

	public static DisplayImageOptions getBigArticleDisplayOption()
	{
		if (bigArticleOptions == null)
		{
			bigArticleOptions = new DisplayImageOptions.Builder().showStubImage(R.drawable.article_layout_placeholder)
					.showImageForEmptyUri(
							R.drawable.article_layout_placeholder)/*
																	 * . considerExifParams ( true )
																	 */
					.showImageOnFail(R.drawable.article_layout_placeholder).bitmapConfig(Config.RGB_565).cacheInMemory(true).cacheOnDisc(true).displayer(new SimpleBitmapDisplayer()).build();
		}
		return bigArticleOptions;

	}

	public final static DisplayImageOptions getProfilePicDisplayOption()
	{
		if (profilePicDisplayOption == null)
		{
			profilePicDisplayOption = new DisplayImageOptions.Builder().showStubImage(R.drawable.profile_image_placeholder)
					.showImageForEmptyUri(
							R.drawable.profile_image_placeholder)/*
																	 * . considerExifParams ( true )
																	 */
					.showImageOnFail(R.drawable.profile_image_placeholder).bitmapConfig(Config.RGB_565).cacheInMemory(true).cacheOnDisc(true).displayer(new SimpleBitmapDisplayer()).build();
		}
		return profilePicDisplayOption;

	}

	public final static DisplayImageOptions getProfileBlurredPicDisplayOption()
	{
		if (profileburredOptions == null)
		{
			profileburredOptions = new DisplayImageOptions.Builder().showStubImage(R.drawable.blur_bg)
					.showImageForEmptyUri(R.drawable.blur_bg)/*
																 * .considerExifParams (true)
																 */
					.showImageOnFail(R.drawable.blur_bg).bitmapConfig(Config.RGB_565).cacheInMemory(true).cacheOnDisc(true).displayer(new SimpleBitmapDisplayer()).build();
		}
		return profileburredOptions;

	}

	public final static DisplayImageOptions getTopBannerLogiDisplayOption()
	{
		if (topBannerOptions == null)
		{
			topBannerOptions = new DisplayImageOptions.Builder().showStubImage(R.drawable.wootrix_logo)
					.showImageForEmptyUri(
							R.drawable.wootrix_logo)/*
														 * . considerExifParams (true)
														 */
					.showImageOnFail(R.drawable.wootrix_logo).bitmapConfig(Config.RGB_565).cacheInMemory(true).cacheOnDisc(true).displayer(new SimpleBitmapDisplayer()).build();
		}
		return topBannerOptions;

	}

	public final static DisplayImageOptions getArticleDisplayOption()
	{
		if (articleOptions == null)
		{
			articleOptions = new DisplayImageOptions.Builder().showStubImage(R.drawable.article_layout2_placeholder)
					.showImageForEmptyUri(
							R.drawable.article_layout2_placeholder)/*
																	 * . considerExifParams (true)
																	 */
					.showImageOnFail(R.drawable.article_layout2_placeholder).bitmapConfig(Config.RGB_565).cacheInMemory(true).cacheOnDisc(true).imageScaleType(ImageScaleType.EXACTLY)
					.displayer(new SimpleBitmapDisplayer()).build();
		}
		return articleOptions;
	}

	public final static DisplayImageOptions getMagazinesDisplayOption()
	{
		if (magazineOptions == null)
		{
			magazineOptions = new DisplayImageOptions.Builder().showStubImage(R.drawable.place_holder_magazine)
					.showImageForEmptyUri(
							R.drawable.place_holder_magazine)/*
																 * . considerExifParams ( true )
																 */
					.showImageOnFail(R.drawable.place_holder_magazine).bitmapConfig(Config.RGB_565).cacheInMemory(true).cacheOnDisc(true).displayer(new SimpleBitmapDisplayer()).build();
		}
		return magazineOptions;
	}

	public final static DisplayImageOptions getMagazinesCoverDisplayOption()
	{
		if (magazineCoverOptions == null)
		{
			magazineCoverOptions = new DisplayImageOptions.Builder().showStubImage(R.drawable.cover_icon).showImageForEmptyUri(R.drawable.cover_icon).showImageOnFail(R.drawable.cover_icon)
					.imageScaleType(ImageScaleType.IN_SAMPLE_INT).bitmapConfig(Config.RGB_565).cacheInMemory(true).cacheOnDisc(true).displayer(new SimpleBitmapDisplayer()).build();
		}
		return magazineCoverOptions;
	}

	public static boolean getSharedPrefBooleanData(Context context, String key)
	{
		SharedPreferences userAcountPreference = context.getSharedPreferences(Constants.SHARED_PREF_NAME, Context.MODE_MULTI_PROCESS);
		boolean data = userAcountPreference.getBoolean(key, false);
		return data;
	}

	public static boolean getAppLevelPrefBooleanData(Context context, String key)
	{
		SharedPreferences userAcountPreference = context.getSharedPreferences(Constants.WHOLE_APP_PREFRENCES, Context.MODE_MULTI_PROCESS);
		boolean data = userAcountPreference.getBoolean(key, false);
		return data;
	}

	public static String getSharedPrefStringData(Context context, String key)
	{
		SharedPreferences userAcountPreference = context.getSharedPreferences(Constants.SHARED_PREF_NAME, Context.MODE_MULTI_PROCESS);
		String data = userAcountPreference.getString(key, "");
		return data;
	}

	public static long getSharedPrefLongData(Context context, String key)
	{
		SharedPreferences userAcountPreference = context.getSharedPreferences(Constants.SHARED_PREF_NAME, Context.MODE_MULTI_PROCESS);
		long data = userAcountPreference.getLong(key, -1);
		return data;
	}

	public static final void clearSharedPrefData(Context context, String key)
	{
		SharedPreferences appInstallInfoSharedPref = context.getSharedPreferences(Constants.SHARED_PREF_NAME, context.MODE_MULTI_PROCESS);
		Editor appInstallInfoEditor = appInstallInfoSharedPref.edit();
		appInstallInfoEditor.remove(key);
		appInstallInfoEditor.commit();
	}

	public static final void clearUserData(Context context)
	{
		SharedPreferences appInstallInfoSharedPref = context.getSharedPreferences(Constants.SHARED_PREF_NAME, context.MODE_MULTI_PROCESS);
		Editor appInstallInfoEditor = appInstallInfoSharedPref.edit();
		appInstallInfoEditor.remove(Constants.USER_TOKEN);
		appInstallInfoEditor.remove(Constants.USER_NAME);
		appInstallInfoEditor.remove(Constants.USER_IMAGE);
		appInstallInfoEditor.remove(Constants.USER_LOGIN_TYPE);
		appInstallInfoEditor.remove(Constants.USER_REQ_EMAIL);
		appInstallInfoEditor.remove(Constants.USER_REQ_PASS);
		appInstallInfoEditor.remove(Constants.USER_EMAIL);
		appInstallInfoEditor.remove(Constants.LOGIN_TYPE_VIA_FACEBOOK);
		appInstallInfoEditor.remove(Constants.PREF_KEY_LOGIN_STATUS);
		appInstallInfoEditor.remove(Constants.isMagzineAvailable);

		appInstallInfoEditor.commit();
	}

	public static final void saveUserData(Context context, LoginSignupModle info, int i)
	{
		SharedPreferences appInstallInfoSharedPref = context.getSharedPreferences(Constants.SHARED_PREF_NAME, context.MODE_MULTI_PROCESS);
		Editor appInstallInfoEditor = appInstallInfoSharedPref.edit();
		appInstallInfoEditor.putString(Constants.USER_TOKEN, info.getToken());
		appInstallInfoEditor.putString(Constants.USER_NAME, info.getUsername());
		appInstallInfoEditor.putString(Constants.USER_IMAGE, info.getPhotoUrl());
		appInstallInfoEditor.putString(Constants.USER_LOGIN_TYPE, i > 0 ? Constants.SOCIAL_LOGIN : Constants.MANUAL_LOGIN);
		appInstallInfoEditor.putBoolean(Constants.USER_REQ_EMAIL, info.isEmailRequired());
		appInstallInfoEditor.putBoolean(Constants.USER_REQ_PASS, info.isPasswordRequired());
		if (appInstallInfoSharedPref.getString(Constants.ARTICLE_LANGUAGE, "").length() == 0)
		{
			appInstallInfoEditor.putString(Constants.ARTICLE_LANGUAGE, Utility.getDrfaultLanguage());
		}
		if (appInstallInfoSharedPref.getString(Constants.APP_LANGUAGE, "").length() == 0)
		{
			appInstallInfoEditor.putString(Constants.APP_LANGUAGE, Utility.getDrfaultLanguage());
		}
		appInstallInfoEditor.putString(Constants.USER_EMAIL, info.getEmail());
		appInstallInfoEditor.putBoolean(Constants.PREF_KEY_LOGIN_STATUS, true);

		appInstallInfoEditor.putBoolean(Constants.IS_FB_CONNECTED, info.isFB());
		appInstallInfoEditor.putBoolean(Constants.IS_GP_CONNECTED, info.isGP());
		appInstallInfoEditor.putBoolean(Constants.IS_TW_CONNECTED, info.isTW());
		appInstallInfoEditor.putBoolean(Constants.IS_LN_CONNECTED, info.isLN());

		appInstallInfoEditor.commit();
	}

	public static final void updateDefaultUser(Context context)
	{
		SharedPreferences appInstallInfoSharedPref = context.getSharedPreferences(Constants.SHARED_PREF_NAME, context.MODE_MULTI_PROCESS);
		Editor appInstallInfoEditor = appInstallInfoSharedPref.edit();
		appInstallInfoEditor.putString("", "");

		appInstallInfoEditor.commit();
	}

	/**
	 * It gets integer type data stored in shared preferences.
	 *
	 * @param context
	 * @param key
	 * @return data including key and its value.
	 */
	public static int getSharedPrefIntegerData(Context context, String key)
	{
		SharedPreferences userAcountPreference = context.getSharedPreferences(Constants.SHARED_PREF_NAME, Context.MODE_MULTI_PROCESS);
		int data = userAcountPreference.getInt(key, -1);
		return data;
	}

	/**
	 * It stores integer type data in shared preferences.
	 *
	 * @param context
	 * @param key
	 *            -unique key value to be assigned.
	 * @param value
	 *            -required value.
	 */
	public static void setSharedPrefIntegerData(Context context, String key, int value)
	{
		SharedPreferences appInstallInfoSharedPref = context.getSharedPreferences(Constants.SHARED_PREF_NAME, context.MODE_MULTI_PROCESS);
		Editor appInstallInfoEditor = appInstallInfoSharedPref.edit();
		appInstallInfoEditor.putInt(key, value);
		appInstallInfoEditor.commit();
	}

	/**
	 * It stores string type data in shared preferences.
	 *
	 * @param context
	 * @param key
	 *            -unique key value to be assigned.
	 * @param value
	 *            -required value.
	 */
	public static void setSharedPrefStringData(Context context, String key, String value)
	{
		SharedPreferences appInstallInfoSharedPref = context.getSharedPreferences(Constants.SHARED_PREF_NAME, context.MODE_MULTI_PROCESS);
		Editor appInstallInfoEditor = appInstallInfoSharedPref.edit();
		appInstallInfoEditor.putString(key, value);
		appInstallInfoEditor.commit();
	}

	public static void setSharedPrefLongData(Context context, String key, long value)
	{
		SharedPreferences appInstallInfoSharedPref = context.getSharedPreferences(Constants.SHARED_PREF_NAME, context.MODE_MULTI_PROCESS);
		Editor appInstallInfoEditor = appInstallInfoSharedPref.edit();
		appInstallInfoEditor.putLong(key, value);
		appInstallInfoEditor.commit();
	}

	/**
	 * It stores boolean type data in shared preferences.
	 *
	 * @param context
	 * @param key
	 *            -unique key value to be assigned.
	 * @param value
	 *            -required value.
	 */
	public static void setSharedPrefBooleanData(Context context, String key, boolean value)
	{

		Log.i(Constants.SHARED_PREF_NAME, "Key: " + key + "Value: " + String.valueOf(value));
		SharedPreferences appInstallInfoSharedPref = context.getSharedPreferences(Constants.SHARED_PREF_NAME, context.MODE_MULTI_PROCESS);
		Editor appInstallInfoEditor = appInstallInfoSharedPref.edit();
		appInstallInfoEditor.putBoolean(key, value);
		appInstallInfoEditor.commit();
	}

	public static void setAppLevelPrefBooleanData(Context context, String key, boolean value)
	{

		Log.i(Constants.SHARED_PREF_NAME, "Key: " + key + "Value: " + String.valueOf(value));
		SharedPreferences appInstallInfoSharedPref = context.getSharedPreferences(Constants.WHOLE_APP_PREFRENCES, context.MODE_MULTI_PROCESS);
		Editor appInstallInfoEditor = appInstallInfoSharedPref.edit();
		appInstallInfoEditor.putBoolean(key, value);
		appInstallInfoEditor.commit();
	}

	public static boolean isNetworkAvailable(Context context)
	{
		ConnectivityManager connMgr = (ConnectivityManager) context.getSystemService(Context.CONNECTIVITY_SERVICE);
		if (connMgr.getNetworkInfo(ConnectivityManager.TYPE_WIFI).getState() == NetworkInfo.State.CONNECTED
				|| connMgr.getNetworkInfo(ConnectivityManager.TYPE_WIFI).getState() == NetworkInfo.State.CONNECTING)
		{
			return true;
		}
		else if (connMgr.getNetworkInfo(ConnectivityManager.TYPE_MOBILE).getState() == NetworkInfo.State.CONNECTED
				|| connMgr.getNetworkInfo(ConnectivityManager.TYPE_MOBILE).getState() == NetworkInfo.State.CONNECTING)
		{

			return true;
		}
		else
			return false;

	}

	public static Bitmap decodeFile(File f)
	{
		try
		{
			BitmapFactory.Options options = new BitmapFactory.Options();
			options.inPreferredConfig = Bitmap.Config.RGB_565;
			options.inJustDecodeBounds = true;
			BitmapFactory.decodeStream(new FileInputStream(f), null, options);

			final int REQUIRED_SIZE = 300;

			// Find the correct scale value. It should be the power of 2.
			int width_tmp = options.outWidth, height_tmp = options.outHeight;
			int scale = 1;
			while ( true )
			{
				if (width_tmp / 2 < REQUIRED_SIZE || height_tmp / 2 < REQUIRED_SIZE)
					break;
				width_tmp /= 2;
				height_tmp /= 2;
				scale *= 2;
			}

			// Decode with inSampleSize
			BitmapFactory.Options o2 = new BitmapFactory.Options();
			o2.inPreferredConfig = Bitmap.Config.ARGB_8888;
			o2.inSampleSize = scale;
			// o2.inPurgeable = true;

			return BitmapFactory.decodeStream(new FileInputStream(f), null, o2);
		}
		catch (FileNotFoundException e)
		{
			e.printStackTrace();
		}
		return null;
	}

	public static void showKeyboard(Context context)
	{
		InputMethodManager imm = (InputMethodManager) context.getSystemService(Context.INPUT_METHOD_SERVICE);
		if (imm != null)
		{
			imm.toggleSoftInput(InputMethodManager.SHOW_FORCED, InputMethodManager.HIDE_IMPLICIT_ONLY);
		}
	}

	public static void hideKeyboard(Context context)
	{
		InputMethodManager imm = (InputMethodManager) context.getSystemService(Activity.INPUT_METHOD_SERVICE);
		if (imm != null)
		{
			imm.toggleSoftInput(InputMethodManager.HIDE_IMPLICIT_ONLY, 0);
		}
	}

	public static String httpGetRequest(String jsonString, String URL)
	{
		HttpClient client = new DefaultHttpClient();
		HttpConnectionParams.setConnectionTimeout(client.getParams(), Constants.CONNECTION_TIME_OUT); // Timeout
		// Limit
		HttpResponse httpresponse;
		// String response = "";
		try
		{
			HttpGet post = new HttpGet(URL);
			Log.e("jsonRequest:", jsonString);
			Log.e("URL:", URL);
			StringEntity se = new StringEntity(jsonString);
			post.setHeader(HTTP.CONTENT_TYPE, "application/json;charset=utf-8");

			se.setContentEncoding(new BasicHeader(HTTP.CONTENT_TYPE, "application/json;charset=utf-8"));
			// String serviceURL = URL.substring(APIUtils.BASE_URL.length(),
			// URL.length());
			// String authenticationKey = AESAlgo.Encrypt(serviceURL + "_" +
			// getTimeInMillsInLong(), "Cacophony_0314");
			// Log.e("Serviceurl", getTimeInMillsInLong() + "\n" + serviceURL +
			// "auth key== " + authenticationKey);
			post.addHeader("Authorization", "Basic Q29wcGVyTW9iaWxlOmN1cGlk");
			// post.setEntity(se);
			// response = client.execute(post);
			httpresponse = client.execute(post);
			if (httpresponse != null /*
										 * && response.getStatusLine().getStatusCode() == 200
										 */)
			{
				String responseEntity = EntityUtils.toString(httpresponse.getEntity());
				responseEntity = responseEntity.trim();
				Log.w("server resp:", responseEntity);
				if (responseEntity != null && !responseEntity.equalsIgnoreCase(""))
					return responseEntity;
				else
					return "{ \"success\": false,\"message\": \"Response is not Coming from server. Please try again.\",\"Result\": []}";
			}
			else
				return "{ \"success\": false,\"message\": \"Response is not Coming from server. Please try again.\",\"Result\": []}";

		}
		catch (UnsupportedEncodingException e)
		{
			e.printStackTrace();
			return "{ \"success\": false,\"message\": \"Network Error Occured. Please try again.\",\"Result\": []}";
		}
		catch (ClientProtocolException e)
		{
			e.printStackTrace();
			return "{ \"success\": false,\"message\": \"Network Error Occured. Please try again.\",\"Result\": []}";
		}
		catch (ConnectTimeoutException e)
		{
			e.printStackTrace();
			return "{ \"success\": false,\"message\": \"Timeout Occured. Please try again.\",\"Result\": []}";
		}
		catch (IOException e)
		{
			e.printStackTrace();
			return "{ \"success\": false,\"message\": \"Network Error Occured. Please try again.\",\"Result\": []}";
		}
		catch (ParseException e)
		{
			e.printStackTrace();
			return "{ \"success\": false,\"message\": \"Network Error Occured. Please try again.\",\"Result\": []}";
		}
		finally
		{
			if (client != null)
			{
				client.getConnectionManager().shutdown();
			}
		}

	}

	public static String httpPostRequestToServer(String URL, Object paramsList, Context mContext)
	{
		HttpParams httpParameters = new BasicHttpParams();
		HttpConnectionParams.setConnectionTimeout(httpParameters, Constants.CONNECTION_TIME_OUT);

		DefaultHttpClient client = new DefaultHttpClient(httpParameters);

		HttpPost httpPost = new HttpPost(URL);
		// httpPost.addHeader(HTTP.CONTENT_TYPE,
		// "application/x-www-form-urlencoded");
		httpPost.addHeader(HTTP.CONTENT_TYPE, "application/json");
		httpPost.addHeader("Authorization", "Basic Q29wcGVyTW9iaWxlOmN1cGlk");
		Log.w("url", URL);
		Log.w("header== Authorization", " Basic Q29wcGVyTW9iaWxlOmN1cGlk");
		HttpResponse response;
		try
		{
			if (paramsList != null)
			{
				Log.w("params", paramsList.toString());
				HttpEntity entity = new StringEntity(paramsList.toString(), "UTF-8");
				httpPost.setEntity(entity);
			}
			response = client.execute(httpPost);

			if (response != null /*
									 * && response.getStatusLine().getStatusCode() == 200
									 */)
			{
				String responseEntity = EntityUtils.toString(response.getEntity());
				responseEntity = responseEntity.trim();
				Log.w("server resp:", responseEntity);
				if (responseEntity != null && !responseEntity.equalsIgnoreCase(""))
					return responseEntity;
				else
					return "{ \"success\": false,\"message\": \"" + mContext.getString(R.string.server_response_error) + "\",\"Result\": []}";
			}
			else
				return "{ \"success\": false,\"message\": \"" + mContext.getString(R.string.server_response_error) + "\",\"Result\": []}";

		}
		catch (UnsupportedEncodingException e)
		{
			e.printStackTrace();
			return "{ \"success\": false,\"message\": \"" + mContext.getString(R.string.server_ntwrk_error) + "\",\"Result\": []}";
		}
		catch (ClientProtocolException e)
		{
			e.printStackTrace();
			return "{ \"success\": false,\"message\": \"" + mContext.getString(R.string.server_ntwrk_error) + "\",\"Result\": []}";
		}
		catch (ConnectTimeoutException e)
		{
			e.printStackTrace();
			return "{ \"success\": false,\"message\": \"" + mContext.getString(R.string.server_timout_error) + "\",\"Result\": []}";
		}
		catch (IOException e)
		{
			e.printStackTrace();
			return "{ \"success\": false,\"message\": \"" + mContext.getString(R.string.server_ntwrk_error) + "\",\"Result\": []}";
		}
		catch (ParseException e)
		{
			e.printStackTrace();
			return "{ \"success\": false,\"message\": \"" + mContext.getString(R.string.server_ntwrk_error) + "\",\"Result\": []}";
		}
		catch (Exception e)
		{
			e.printStackTrace();
			return "{ \"success\": false,\"message\": \"" + mContext.getString(R.string.server_ntwrk_error) + "\",\"Result\": []}";
		}
		finally
		{
			if (client != null)
			{
				client.getConnectionManager().shutdown();
			}
		}

	}

	public static String httpPostRequestToServerWithImageData(String URL, Object paramsList, Context mContext, File imageFile)
	{
		HttpParams httpParameters = new BasicHttpParams();
		HttpConnectionParams.setConnectionTimeout(httpParameters, Constants.CONNECTION_TIME_OUT);

		DefaultHttpClient client = new DefaultHttpClient(httpParameters);

		HttpPost httpPost = new HttpPost(URL);
		// httpPost.addHeader(HTTP.CONTENT_TYPE,
		// "application/x-www-form-urlencoded");
		// httpPost.addHeader(HTTP.CONTENT_TYPE, "application/json");
		httpPost.addHeader("Authorization", "Basic Q29wcGVyTW9iaWxlOmN1cGlk");
		Log.w("url", URL);
		Log.w("header== Authorization", " Basic Q29wcGVyTW9iaWxlOmN1cGlk");
		HttpResponse response;
		try
		{
			if (paramsList != null)
			{
				Log.w("params", paramsList.toString());
				MultipartEntity reqEntity = new MultipartEntity(HttpMultipartMode.BROWSER_COMPATIBLE);
				reqEntity.addPart("json", new StringBody(paramsList.toString()));
				FileBody fileBody = new FileBody(imageFile);
				System.out.println(imageFile.length() / 1024);
				reqEntity.addPart("photo", fileBody);
				httpPost.setEntity(reqEntity);
			}
			response = client.execute(httpPost);

			if (response != null /*
									 * && response.getStatusLine().getStatusCode() == 200
									 */)
			{
				String responseEntity = EntityUtils.toString(response.getEntity());
				responseEntity = responseEntity.trim();
				Log.w("server resp:", responseEntity);
				if (responseEntity != null && !responseEntity.equalsIgnoreCase(""))
					return responseEntity;
				else
					return "{ \"success\": false,\"message\": \"" + mContext.getString(R.string.server_response_error) + "\",\"Result\": []}";
			}
			else
				return "{ \"success\": false,\"message\": \"" + mContext.getString(R.string.server_response_error) + "\",\"Result\": []}";

		}
		catch (UnsupportedEncodingException e)
		{
			e.printStackTrace();
			return "{ \"success\": false,\"message\": \"" + mContext.getString(R.string.server_ntwrk_error) + "\",\"Result\": []}";
		}
		catch (ClientProtocolException e)
		{
			e.printStackTrace();
			return "{ \"success\": false,\"message\": \"" + mContext.getString(R.string.server_ntwrk_error) + "\",\"Result\": []}";
		}
		catch (ConnectTimeoutException e)
		{
			e.printStackTrace();
			return "{ \"success\": false,\"message\": \"" + mContext.getString(R.string.server_timout_error) + "\",\"Result\": []}";
		}
		catch (IOException e)
		{
			e.printStackTrace();
			return "{ \"success\": false,\"message\": \"" + mContext.getString(R.string.server_ntwrk_error) + "\",\"Result\": []}";
		}
		catch (ParseException e)
		{
			e.printStackTrace();
			return "{ \"success\": false,\"message\": \"" + mContext.getString(R.string.server_ntwrk_error) + "\",\"Result\": []}";
		}
		catch (Exception e)
		{
			e.printStackTrace();
			return "{ \"success\": false,\"message\": \"" + mContext.getString(R.string.server_ntwrk_error) + "\",\"Result\": []}";
		}
		finally
		{
			if (client != null)
			{
				client.getConnectionManager().shutdown();
			}
		}

	}

	public static void showErrorDialog(Context context, String title, String msg, final View mEdTextToFocus)
	{
		// title = context.getResources().getString(R.string.label_message);
		new AlertDialog.Builder(context, R.style.custom_dialog_theme).setTitle(title).setMessage(msg).setIcon(R.drawable.app_icon).setCancelable(false)
				.setPositiveButton(context.getResources().getString(android.R.string.ok), new DialogInterface.OnClickListener() {
					public void onClick(DialogInterface dialog, int whichButton)
					{
						dialog.dismiss();

						if (mEdTextToFocus != null && mEdTextToFocus instanceof EditText)
						{
							mEdTextToFocus.requestFocus();
						}
						else
						{
							// do nothing
						}
					}
				}).create().show();
	}

	public static final boolean isEmailValid(CharSequence email)
	{
		if (email == null)
		{
			return false;
		}
		else
		{
			return android.util.Patterns.EMAIL_ADDRESS.matcher(email).matches();
		}
	}

	public static final void showNetworkNotAvailToast(Context context)
	{
		Toast.makeText(context, context.getResources().getString(R.string.network_not_avail), Toast.LENGTH_SHORT).show();

	}

	public static final void showToastMessage(Context context, String Message)
	{
		// Toast.makeText(context, Message, Toast.LENGTH_SHORT).show();

		Toast toast = Toast.makeText(context, Message, Toast.LENGTH_SHORT);
		toast.setGravity(Gravity.CENTER, 0, 0);
		toast.show();
	}

	public static final void toastMessage(Context context, String Message)
	{
		// Toast.makeText(context, Message, Toast.LENGTH_SHORT).show();
		Toast toast = Toast.makeText(context,"Notification type can't identify", Toast.LENGTH_SHORT);;
		if(Message.equals("message"))
			toast = Toast.makeText(context,context.getResources().getString(R.string.toast_message), Toast.LENGTH_SHORT);
		else if(Message.equals("advertisement"))
			toast = Toast.makeText(context,context.getResources().getString(R.string.toast_advertisement), Toast.LENGTH_SHORT);
		else if (Message.equals("article"))
			toast = Toast.makeText(context,context.getResources().getString(R.string.toast_article), Toast.LENGTH_SHORT);
		else if(Message.equals("closemagazine"))
			toast = Toast.makeText(context,context.getResources().getString(R.string.toast_close), Toast.LENGTH_SHORT);
		toast.setGravity(Gravity.BOTTOM, 0, 0);
		toast.show();
	}

	public static void closeSilently(Closeable c)
	{
		if (c == null)
			return;
		try
		{
			c.close();
		}
		catch (Throwable t)
		{
		}
	}

	public static final String getSystemDataTimeFromUTC(Context context, String utcDateTime)
	{

		if (utcDateTime.length() > 0)
		{

			if (utcFormat == null)
			{
				utcFormat = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
				utcFormat.setTimeZone(TimeZone.getTimeZone("UTC"));
			}
			Date date;
			try
			{
				date = utcFormat.parse(utcDateTime);
				if (pstFormat == null)
				{
					pstFormat = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
					pstFormat.setTimeZone(TimeZone.getDefault());
				}
				// System.out.println("pst formate" + pstFormat.format(date));
				Date convertedDate = pstFormat.parse(pstFormat.format(date));
				// System.out.println("diffetrence" +
				// (System.currentTimeMillis() - convertedDate.getTime())
				// / (1000 * 3600));
				/*
				 * long totalSeconds = (System.currentTimeMillis() - convertedDate.getTime()) /
				 * (1000); if (totalSeconds < 60) { if (totalSeconds < 1) { return
				 * context.getString(R.string.pre_ago) + " 0 " + context.getString(R.string.sec_ago)
				 * "0 sec ago"; } if (totalSeconds == 1) { return
				 * context.getString(R.string.pre_ago) + " 1 " + context.getString(R.string.sec_ago)
				 * "1 sec ago"; } else { return context.getString(R.string.pre_ago) + " " +
				 * totalSeconds + " " + context.getString(R.string.secs_ago)" secs ago"; } } else if
				 * (totalSeconds >= 60 && totalSeconds < 3600) { long min = totalSeconds / 60; if
				 * (min == 1) { return context.getString(R.string.pre_ago) + " 1 " +
				 * context.getString(R.string.min_ago)"1 min ago"; } else { return
				 * context.getString(R.string.pre_ago) + " " + min + " " +
				 * context.getString(R.string.mins_ago)" mins ago"; } } else if (totalSeconds >=
				 * 3660 && totalSeconds < 86400) { long hour = totalSeconds / 3600; if (hour == 1) {
				 * return context.getString(R.string.pre_ago) + " 1 " +
				 * context.getString(R.string.hr_ago)"1 hour ago"; } else { return
				 * context.getString(R.string.pre_ago) + " " + hour + " " +
				 * context.getString(R.string.hrs_ago) " hours ago"; } } else if (totalSeconds >=
				 * 86400 && totalSeconds <= 259200) { int secondsInAMinute = 60; int secondsInAnHour
				 * = 60 * secondsInAMinute; long secondsInADay = 24 * secondsInAnHour; long days =
				 * totalSeconds / secondsInADay; if (days == 1) { //return @"yesterday"; return
				 * context.getString(R.string.pre_ago) + " 1 " + context.getString(R.string.day_ago)
				 * "1 day ago"; } else { return context.getString(R.string.pre_ago) + " " + days +
				 * " " + context.getString(R.string.day_ago) " days ago"; } } else {
				 */

				// if (format == null)
				if (Utility.getDrfaultLanguage().equalsIgnoreCase("en"))
				{
					SimpleDateFormat format = new SimpleDateFormat("MM/dd/yy");
					return format.format(convertedDate);
				}
				else
				{
					SimpleDateFormat format = new SimpleDateFormat("dd/MM/yyyy"/* "MMMM dd, yyyy " */);
					return format.format(convertedDate);
				}
				// }
			}
			catch (Exception e)
			{

			}
		}
		return "";
	}


	// public static long getTimeInMillsInLong()
	// {
	// Calendar cal1 = Calendar.getInstance(TimeZone.getTimeZone("UTC"));
	// long timestamp = cal1.getTimeInMillis();
	// Log.e("timestamp...", timestamp + "");
	// return timestamp;
	//
	// }

	/*
	 * public static String changeSpecialCharectorIssue(String str){ String result=""; try { result
	 * = new String(str.getBytes("ISO-8859-1"), "UTF-8"); } catch (UnsupportedEncodingException e) {
	 * e.printStackTrace(); } return result; }
	 */
}
