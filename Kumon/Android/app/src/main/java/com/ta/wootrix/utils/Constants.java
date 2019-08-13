package com.ta.wootrix.utils;

import android.net.Uri;

public class Constants
{

	public static final String	PLAYSTORE_LINK						= "market://details?id=";

	public final static String	LOGIN_TYPE_VIA_FACEBOOK				= "facebook_login_type";
	/* Strings for taking images from camera/gallery */
	public static final int		REQUEST_CODE_IMAGE_CAPTURED			= 1;
	public static final int		REQUEST_CODE_IMAGE_GALLERY			= 0;
	public static final int		REQUEST_CODE_VIDEO_GALLERY			= 3;
	public static final int		REQUEST_CODE_VIDEO_CAPTURED			= 4;
	public static final String	SHARED_PREF_NAME					= "fitness_shared_pref";
	public static final int		CONNECTION_TIME_OUT					= 20000;
	// my shared preferences
	public static final String	FB_ACCESS_TOKEN						= "facebook_token_key";
	public static final String	FB_EXPIRY_DATE						= "fb_exp_date";
	public static final String	WHOLE_APP_PREFRENCES				= "whole_app_prefrences";
	public static final String	IS_FB_CONNECTED						= "fbconnected";
	public static final String	IS_TW_CONNECTED						= "twconnected";
	public static final String	IS_GP_CONNECTED						= "gpconnected";
	public static final String	IS_LN_CONNECTED						= "lnconnected";
	// sharedpref for user
	public static String		USER_TOKEN							= "user_token";
	public static String		USER_NAME							= "user_name";
	public static String		USER_EMAIL							= "user_email";
	public static String		USER_REQ_EMAIL						= "user_req_email";
	public static String		USER_REQ_PASS						= "user_req_pass";
	public static String		USER_IMAGE							= "user_photourl";
	public static String		USER_SHOW_PAGE						= "user_showpage";
	public static String		USER_LOGIN_TYPE						= "user_login_type";
	public static String		MANUAL_LOGIN						= "manual_login";

	/* shared pref */
	public static String		SOCIAL_LOGIN						= "social_login";
	public static String		ARTICLE_LANGUAGE					= "article_language";
	public static String		APP_LANGUAGE						= "app_language";
	public static String		USER_TOPICS							= "user_topics";
	public static String		isMagzineAvailable					= "magazinesAvailablity";
	public static Uri			URI_VIDEO_CAPTURED					= null;
	public static Uri			URI_IMAGE_CAPTURED					= null;
	public static String		SHARED_PREF_FACEBOOK_LOGIN_STATUS	= "facebook_login_status";
	public static String		PREF_KEY_LOGIN_STATUS				= "login_status";

}
