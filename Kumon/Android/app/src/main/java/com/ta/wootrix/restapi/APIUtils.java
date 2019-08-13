package com.ta.wootrix.restapi;

/**
 * APIUtils provide baseurl method and declares all webservice name/url
 */
public class APIUtils {
    //Production URL
//    public static final String BASE_URL = "http://wootrix.com/index.php/";
    public static final String BASE_URL = "https://linhadireta.unidadekumon.com.br/index.php/";

    //Testing URL
    //public static final String	BASE_URL							= "http://cipldev.com/wootrix/index.php/";

    //Server Issue URL
    //public static final String	BASE_URL							= "http://demo.mycipl.in/wootrix/index.php/";

    //http://cipldev.com/wootrix/index.php

    // testing base url
    // public static final String BASE_URL = "http://103.25.130.197/wootrix/index.php/";
    // public static final String BASE_URL = "http://103.25.130.197/wootrix_phase_2/index.php/";

    // test url
    // public static final String BASE_URL = "http://103.25.130.197/wootrix_phase_2/index.php/";

    // live server url
    // public static final String BASE_URL = "http://54.187.9.155/index.php/";
    public static final String SIGNUP_URL = "signup";
    // public static final String BASE_URL = "http://103.25.130.197/wootrix_dev/index.php/";

    // new techahead testing url
    // public static final String BASE_URL = "http://103.25.130.197/wootrix_test/index.php/";
    public static final String LOGIN_URL = "login";
    public static final String GET_ADDED_ACCOUNT = "get_account_details";
    public static final String ADD_MORE_ACCOUNT = "add_more_accounts";
    public static final String MERGE_ACCOUNT = "merge_accounts";
    public static final String FORGOT_PASSWORD = "forgotPassword";
    public static final String GET_LANDING_PAGE_DATA = "LandingScreen";
    public static final String GET_MAGAZINE = "getMagazines";
    public static final String GET_TOPICS = "getTopics";
    public static final String GET_OPEN_ARTICLES = "getOpenArticles";
    public static final String GET_ARTICLE_DETAIL = "getArticleDetail";
    public static final String GET_AD_DETAIL = "getAdvDetail";
    public static final String GET_MAGAZINE_DATA = "getMagazineData";
    public static final String GET_MAGAZINE_ARTICLES = "getMagazineArticles";
    public static final String GET_COMMENTS = "getComments";
    public static final String CHANGE_PASSWORD = "changePassword";
    public static final String CHANGE_EMAIL = "changeEmail";
    public static final String UPDATE_PROFILE_PHOTO = "changePhoto";
    public static final String GET_OPEN_ARTICLES_ADVERTISEMENT = "advertisementOpen";
    public static final String GET_MAGAZINES_ADVERTISEMENT = "advertisementMagazine";
    public static final String SEARCH_OPEN_ARTICLES = "searchOpen";
    public static final String SEARCH_MAGAZINES_ARTICLES = "searchMagazine";
    public static final String POST_COMMENT = "postComment";
    public static final String SUBSCRIBE_MAGAZINE = "subscribeMagazine";
    public static final String VERIFY_TOKEN = "verifyToken";
    public static final String REPORT_ADVERTISEMENT = "adsReport";
    // public static final String ABOUT_US_URL = BASE_URL + "wootrix-about-us";
    public static final String SHARING_BASE_URL = BASE_URL + "wootrix-article-detail?";
    public static final String SAVE_TOPICS_AND_ARTICLE_LANGUAGES = "insertUserSelectedTab";
    public static final String FETCH_TOPICS_AND_ARTICLE_LANGUAGES = "getUserSelectedTab";
    public static final String POST_MAGAZINE_ACCESS = "magazineAccess";


    //SendTokenID
    public static final String CIPL_DELETEMAGAZINE_URL = "magzinedelete";


    public static String getBaseURL(String api) {
        return new StringBuilder().append(BASE_URL).append(api).toString();
    }

    public enum REQUEST_TYPE {
        GET, POST, MULTIPART_GET, MULTIPART_POST
    }
}