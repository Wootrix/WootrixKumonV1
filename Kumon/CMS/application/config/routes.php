<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/
$checkHeaderValues = getallheaders();


//$version  = $check_version['BundleVersion']; 
//$version = '1.0';
$route['default_controller'] = "website/website_login/webLogin";
$route['404_override'] = '';
$route['welcome_message'] = 'web/index/welcome';

//************routes for Superadmin and admin panel*****************************//

$route['selectlanguage'] = "web/superadmin/select_language";
$route['subadmin'] = "web/superadmin/subadmin_dashbord";
$route['login_check'] = "web/superadmin/get_admin_login";
$route['superadmin'] = "web/superadmin/superadmin_dashbord";
$route['admin'] = "web/superadmin/sub_admin_list";
$route['admindetails'] = "web/superadmin/admin_information";
$route['addsubadmin'] = "web/superadmin/add_sub_admin";
$route['editsubadmin'] = "web/superadmin/edit_sub_admin";
$route['admin_forgot_password'] = "web/superadmin/admin_forgot_password";

$route['admineditprofile'] = "web/superadmin/admin_edit_profile";
$route['adminchangePassword'] = "web/superadmin/admin_change_Password";

$route['admindelete'] = "web/superadmin/admin_delete";
$route['logout'] = "web/superadmin/logout";

/*CATAGORY*/
$route['catagory'] = "web/catagory/catagory_list";
$route['addcatagory'] = "web/catagory/add_new_catagory";
$route['deletecatagory'] = "web/catagory/delete_catagory"; 
$route['languagecatagory'] = "web/catagory/language_base_catagory_list";

/* CUSTOMER*/
$route['customers'] = "web/customer/customer_list";
$route['addcustomer'] = "web/customer/add_customer";
$route['customerdetails'] = "web/customer/customer_details";
$route['customerdelete'] = "web/customer/customer_delete";
$route['editcustomer'] = "web/customer/edit_customer";
$route['customermagazine'] = "web/customer/customer_magazine_list";



/*Magazine*/
$route['magazinelist'] = "web/magazine/magazine_list";
$route['addmagazine'] = "web/magazine/add_magazine";
$route['suggestionlist'] = "web/magazine/customer_suggestion";
$route['magazinearticlelist'] = "web/magazine/magazine_article_list";
$route['deletemagzineads'] = "web/magazine/delete_magzine_ads";
$route['reviewmagazinearticle'] = "web/magazine/review_magazine_article";
$route['approvemagazarticle'] = "web/magazine/approve_magaz_article";
$route['rejectmagazinearticle'] = "web/magazine/reject_magazine_article";
$route['deletemagazine'] = "web/magazine/delete_magazine";
$route['magazinepublishedarticlelist'] = "web/magazine/magazine_published_article_list";
$route['magazinedeletedarticlelist'] = "web/magazine/magazine_deleted_article_list";
$route['magazinereviewarticlelist'] = "web/magazine/magazine_review_article_list";

$route['editMagazinearticle'] = "web/magazine/edit_customer_magazine_article";
$route['edit_magazine'] = "web/magazine/edit_magazine";



/*ARTICLE*/
$route['addarticle'] = "web/article/add_new_article";
$route['openarticlelist'] = "web/article/open_article";
$route['getnewarticlelist'] = "web/article/get_new_article_list";
$route['getdeletedarticlelist'] = "web/article/get_deleted_article_list";
$route['getpublishedarticlelist'] = "web/article/get_published_article_list";
$route['getdraftarticlelist'] = "web/article/get_draft_article_list";
$route['editopenarticle'] = "web/article/edit_open_article";
$route['deleteopenarticle'] = "web/article/delete_open_article";
$route['restoreopenarticle'] = "web/article/restore_open_article";
$route['permanentdeleteopenarticle'] = "web/article/permanent_delete_openarticle";
$route['publisharticle'] = "web/article/publish_article";
$route['publishopenarticle'] = "web/article/publish_open_article";
$route['getCategoryFromLanguage']='web/article/selectCategoryFromLanguage';




/*ADVERTISE*/ 
$route['addadvertise'] = "web/advertise/add_advertise";
$route['advertiselisting'] = "web/advertise/advertise_listing";
$route['publishadvertise'] = "web/advertise/publish_advertise";
$route['advertisedelete'] = "web/advertise/advertise_delete";
$route['editadvertise'] = "web/advertise/edit_advertise";
$route['viewadvertise'] = "web/advertise/view_advertise";
$route['publishadvertiselisting'] = "web/advertise/published_advertise_listing";
$route['draftadvertiselisting'] = "web/advertise/draft_advertise_listing";
$route['newadvertiselisting'] = "web/advertise/new_advertise_listing";
$route['deletedadvertiselisting'] = "web/advertise/deleted_advertise_listing";
$route['reviewadvertiselisting'] = "web/advertise/review_advertise_listing";
$route['advertiserestore'] = "web/advertise/advertise_restore";
$route['advertise_perma_delete'] = "web/advertise/advertise_perma_delete";



$route['approvedeclinearticle'] = "web/advertise/approve_decline_article";
$route['approve_magazi_advertise'] = "web/advertise/approve_magazi_advertise";
$route['decline_magazi_advertise'] = "web/advertise/decline_magazi_advertise";





/*HISTORY*/
$route['history'] = "web/history/delete_history";
$route['magizneartcle'] = "web/history/deleted_magizne_artcle";


/**************-CUSTOMER MODULE ROUTES -********************/

$route['customerdashbord'] = "customer/customer_index/customer_dashbord";
$route['selectcustomerlanguage'] = "customer/customer_index/select_customer_language";

$route['customer_login'] = "customer/customer_index/customer_login";
$route['customer_logout'] = "customer/customer_index/customer_logout";

$route['customereditprofile'] = "customer/customer_index/customer_edit_profile";
$route['customerchangePassword'] = "customer/customer_index/customer_change_Password";
$route['customerforgotpassword'] = "customer/customer_index/customer_forgot_password";


/*CUSTOMER MAGAZINE*/
$route['customer_magazinelist'] = "customer/customer_magazine/customer_magazinelist";
$route['customer_articlelist'] = "customer/customer_magazine/customer_articlelist";
$route['customer_publisharticle'] = "customer/customer_magazine/customer_magazine_published_article_list";
$route['customer_deletearticle'] = "customer/customer_magazine/customer_magazine_deleted_article_list";
$route['customer_reviewarticle'] = "customer/customer_magazine/customer_magazine_review_article_list";
$route['sendforreview'] = "customer/customer_magazine/send_for_review";
$route['restorearticle'] = "customer/customer_magazine/restore_article";
$route['shiftdeletearticle'] = "customer/customer_magazine/shiftdelete_article";
$route['customer_draftarticle'] = "customer/customer_magazine/customer_draftarticle";
$route['rejectionReport'] = "customer/customer_magazine/rejection_report_view";
$route['addcustomerarticle'] = "customer/customer_magazine/add_customer_article";
$route['editcustomerarticle'] = "customer/customer_magazine/edit_customer_article";
$route['customermagazinecode'] = "customer/customer_magazine/customer_magazine_code";


// *** new pages anuj for phase 2 work
$route['addCustomerArticle']="customer/customer_magazine/addCustomerArticle";
$route['editCustomerArticle']="customer/customer_magazine/editCustomerArticle";
$route['CustomerArticlelist']="customer/customer_magazine/CustomerArticlelist";


/*CUSTOMER ADS*/

$route['customeradvertiselisting'] = "customer/customer_ads/customer_advertise_listing";
$route['addcustomeradvertise'] = "customer/customer_ads/add_customer_advertise";
$route['editcustomeradvertise'] = "customer/customer_ads/edit_customer_advertise";
$route['publishcustomeradvertise'] = "customer/customer_ads/publish_customer_advertise";
$route['customeradvertisedelete'] = "customer/customer_ads/customer_advertise_delete";
$route['customerAdsShiftdelete'] = "customer/customer_ads/customer_ads_shiftdelete";
$route['restoreCustomerads'] = "customer/customer_ads/restore_customer_ads";
$route['sendAdsforreview'] = "customer/customer_ads/send_ads_forreview";  //view_custo_advertise
$route['view_custo_advertise'] = "customer/customer_ads/view_custo_advertise";



$route['customerPublishedarticle'] = "customer/customer_ads/customer_published_article_list";
$route['customerDraftedarticle'] = "customer/customer_ads/customer_drafted_article_list";
$route['customerDeletedarticle'] = "customer/customer_ads/customer_deleted_article_list";
$route['customerReviewarticle'] = "customer/customer_ads/customer_review_article_list";


/**************-WEBSITE MODULE ROUTES -********************/

$route['wootrix-signup'] = "website/website_login/webLogin";
//$route['wootrix-landing-screen'] = "website/website_login/getDashboardLandingPage";
$route['wootrix-landing-screen'] = "website/article_detail/getListOfSelectedMagazines";
$route['wootrix-user-registration'] = "website/website_login/userRegistration";
$route['wootrix-user-logout'] = "website/website_login/logOutUser";
$route['wootrix-user-login'] = "website/website_login/userLogin";
$route['wootrix-article-detail'] = "website/article_detail/articleDetailList";
$route['wootrix-search-magzine'] = "website/article_detail/searchMagzineList";
$route['wootrix-list-magazines'] = "website/article_detail/getListOfSelectedMagazines";
$route['wootrix-article-list-layout'] = "website/article_detail/articleLayoutsList";
$route['wootrix-save-search-criteria'] = "website/website_login/saveSearchCriteria";

$route['wootrix-about-us/en'] = "website/website_login/aboutUsPage";
$route['wootrix-about-us/es'] = "website/website_login/aboutUsPage";
$route['wootrix-about-us/pt'] = "website/website_login/aboutUsPage";

$route['wootrix-subscribe-password'] = "website/website_login/getSubscribeMagazinePassword";
$route['upload-img']="website/website_login/uploadUserImage";

$route['wootrix-mag-article-detail'] = "website/article_detail/magazineArticlesDetailList";

$route['wootrix-contact-us'] = "website/website_login/contactUsPage";

$route['wootrix-privacy-policy/en'] = "website/website_login/privacyPage";
$route['wootrix-privacy-policy/pt'] = "website/website_login/privacyPage";
$route['wootrix-privacy-policy/es'] = "website/website_login/privacyPage";

$route['wootrix-terms-services'] = "website/website_login/servicesPage";
$route['wootrix-articles'] = "website/website_login/articleListLayout";
$route['saveLatLong'] = "website/website_login/saveLatLong";




//*******routes for parsing websites*********//

$route['achr'] = "web/website_parse/AchrNews";
$route['AmericanMachinist'] = "web/website_parse/AmericanMachinist";
$route['ApplianceDesign']   = "web/website_parse/ApplianceDesign";
$route['AdhesivesMag']      = "web/website_parse/AdhesivesMag";
$route['Automation']        = "web/website_parse/Automation";
$route['AssemblyMag']       = "web/website_parse/AssemblyMag";
$route['BulkSolid']         = "web/website_parse/BulkSolidHandling";
$route['CanedianMetal']     = "web/website_parse/CanedianMetal";
$route['ChemicalProcessing'] = "web/website_parse/ChemicalProcessing";
$route['ConnectorSuplier']   = "web/website_parse/ConnectorSuplier";
$route['CseMag']            = "web/website_parse/CseMag";
$route['ControlEng']        = "web/website_parse/ControlEng";
$route['CteMag']            = "web/website_parse/CteMag";
$route['DesignNews']        = "web/website_parse/DesignNews";
$route['DrivesNControls']   = "web/website_parse/DrivesNControls";
$route['EeTimes']           = "web/website_parse/EeTimes";
$route['EhsToday']          = "web/website_parse/EhsToday";
$route['Ecmag']             = "web/website_parse/Ecmag";
$route['ElectronicSpecifier'] = "web/website_parse/ElectronicSpecifier";
$route['ElectronicDesign']    = "web/website_parse/ElectronicDesign";
$route['ElectronicsWeekly']   = "web/website_parse/ElectronicsWeekly";
$route['EngineerLive']        = "web/website_parse/EngineerLive";
$route['EngineeringTv']       = "web/website_parse/EngineeringTv";
$route['FlexPackmag']         = "web/website_parse/FlexPackmag";
$route['FlowControlNetwork']  = "web/website_parse/FlowControlNetwork";
$route['FoodandBeverAgePackaging'] = "web/website_parse/FoodandBeverAgePackaging";
$route['HPAC']                     = "web/website_parse/HPAC";
$route['LabelandnArrowWeb']        = "web/website_parse/LabelandnArrowWeb";
$route['MachineDesign']            = "web/website_parse/MachineDesign";
$route['Mecatronicaatual']         = "web/website_parse/Mecatronicaatual";
$route['MetalFormingMagazine']     = "web/website_parse/MetalFormingMagazine";
$route['MhlNews']                  = "web/website_parse/MhlNews";
$route['MhwMagzine']               = "web/website_parse/MhwMagzine";
$route['TechnologyReview']         = "web/website_parse/TechnologyReview";
$route['Mmdonline']                = "web/website_parse/Mmdonline";
$route['MoldMakingTechnology']     = "web/website_parse/MoldMakingTechnology";
$route['NASA']                     = "web/website_parse/NASA";
$route['NREL']                     = "web/website_parse/NREL";
$route['PaceToday']                = "web/website_parse/PaceToday";
$route['PackagingDigest']          = "web/website_parse/PackagingDigest";
$route['PemMag']                   = "web/website_parse/PemMag";
$route['PlumbingAndHvac']          = "web/website_parse/PlumbingAndHvac";
$route['PowderBulkSolids']         = "web/website_parse/PowderBulkSolids";
$route['ProcessingMagzine']        = "web/website_parse/ProcessingMagzine";
$route['QualityMag']               = "web/website_parse/QualityMag";
$route['RenewGrid']                = "web/website_parse/RenewGrid";
$route['RewMag']                   = "web/website_parse/RewMag";
$route['RenewableEnergyWorld']     = "web/website_parse/RenewableEnergyWorld";
$route['SupplyChainBrain']         = "web/website_parse/SupplyChainBrain";
$route['TestAndMeasurement']       = "web/website_parse/TestAndMeasurement";
$route['blog']                     = "web/website_parse/blog";
$route['WeldingAndGasesToday']     = "web/website_parse/WeldingAndGasesToday";
$route['WeldingDesign']            = "web/website_parse/WeldingDesign";
$route['WindPower']                = "web/website_parse/WindPower";
$route['EDN']                      = "web/website_parse/EDN";
$route['AutomationIsa']            = "web/website_parse/AutomationIsa";
$route['LinearMotionTips']         = "web/website_parse/LinearMotionTips";
$route['MotionControlTips']        = "web/website_parse/MotionControlTips";
$route['Packword']                 = "web/website_parse/Packword";
$route['ScienceDaily']             = "web/website_parse/ScienceDaily";
$route['SolarNovas']               = "web/website_parse/SolarNovas";
$route['SupplyChain247']           = "web/website_parse/SupplyChain247";
$route['TheEngineer']              = "web/website_parse/TheEngineer";
$route['ControlGlobal']            = "web/website_parse/ControlGlobal";
$route['SpectrumIEEE']             = "web/website_parse/SpectrumIEEE";
$route['ReliablePlant']            = "web/website_parse/ReliablePlant";
$route['GlobalEnergyWorld']        = "web/website_parse/GlobalEnergyWorld";
$route['GreenTechMedia']           = "web/website_parse/GreenTechMedia";
$route['ElectronicProducts']       = "web/website_parse/ElectronicProducts";
$route['AutomationWorld']          = "web/website_parse/AutomationWorld";
$route['FasterAndFixing']          = "web/website_parse/FasterAndFixing";
$route['bigbasket']                = "web/website_parse/bigbasket";
$route['bigbasket2']               = "web/website_parse/bigbasket2";
/**************-END-********************/















//******* routes for webservices *********//

/* USER AND NOTES  */
$route['user_registration']="webservices_$version/user_service/user_registration";

$route['login'] = "webservices/wootrix_login/wootrixLogin";
$route['userlocation'] = "webservices/wootrix_login/wootrixLocation";
$route['signup'] = "webservices/wootrix_login/signupInApp";
$route['forgotPassword'] = "webservices/wootrix_login/forgotPassword";
$route['LandingScreen'] = "webservices/landing_screen/landingScreenData";
$route['getTopics'] = "webservices/landing_screen/getWootrixTopics";
$route['getOpenArticles'] = "webservices/landing_screen/getOpenArticles";
$route['getMagazineArticles'] = "webservices/magzine_articles/magzineArticles";
$route['getMagazines'] = "webservices/magzine_articles/getMagzines";
$route['getComments'] = "webservices/article_comments/getArticleComments";
$route['postComment'] = "webservices/article_comments/postComment";
$route['subscribeMagazine'] = "webservices/magzine_articles/getSubscribeMagzine";
$route['changePhoto'] = "webservices/wootrix_login/changeUserPhoto";
$route['changeEmail'] = "webservices/wootrix_login/changeUserEmail";
$route['changePassword'] = "webservices/wootrix_login/changeUserPassword";

$route['searchOpen'] = "webservices/search_open_articles/searchOpenArticles";
$route['searchMagazine'] = "webservices/search_open_articles/searchMagazineData";
$route['advertisementOpen'] = "webservices/search_advertise_open/searchAdvertiseOpen";
$route['advertisementMagazine'] = "webservices/search_advertise_open/searchAdvertisementMagazine";
$route['adsReport'] = "webservices/wootrix_login/adsReportOnClick";
$route['verifyToken'] = "webservices/wootrix_login/verifyTokenValue";
$route['insertUserSelectedTab'] = "webservices/wootrix_login/insertUserSelectedTab";
$route['getUserSelectedTab'] = "webservices/wootrix_login/getUserSelectedTab";
$route['add_more_accounts'] = "webservices/wootrix_login/addAccount";
$route['merge_accounts'] = "webservices/wootrix_login/mergeAccounts";
$route['get_account_details'] = "webservices/wootrix_login/getAccounts";

//Magazine Access
$route['magazineAccess'] = "webservices/magzine_articles/magazineAccess";

//New Routes from Colan
/*Push Notifications*/
$route['notifications'] = "web/notifications";
$route['notify_ajax'] = "web/notify_ajax";
$route['customer_notify_ajax'] = "customer/notify_ajax";
$route['customer_notifications'] = "customer/notifications";
/*Magazine Delete*/
$route['magzinedelete'] = "webservices/magzine_articles/magzinedelete";
/*Reset Password*/
$route['resetpassword/(:num)'] = "website/website_login/resetpassword/$1";
$route['resetpost'] = "website/website_login/resetpost";

/* Moon */

$route['public_article_report'] = "web/superadmin/public_article_report";
$route['closed_article_report'] = "web/superadmin/closed_article_report";
$route['report_permission'] = "web/superadmin/report_permission";
$route['editmetrics'] = "web/superadmin/edit_metrics";
$route["get_report_permission_data"] = "web/superadmin/get_report_permission_data";
$route['language_filter'] = "web/superadmin/language_filter";

$route['use_report'] = "customer/customer_index/use_report";
$route['customer_magazine_filter'] = "customer/customer_index/get_customer_magazine_filter";
$route['magazine_content_filter'] = "customer/customer_index/get_magazine_content_filter";
$route['magazine_users_filter'] = "customer/customer_index/get_magazine_users_filter";
$route['use_report_data_1'] = "customer/customer_index/get_use_report_data_1";
$route['use_report_data_2'] = "customer/customer_index/get_use_report_data_2";
$route['use_report_data_3'] = "customer/customer_index/get_use_report_data_3";
$route['use_report_data_4'] = "customer/customer_index/get_use_report_data_4";
$route['use_report_data_5'] = "customer/customer_index/get_use_report_data_5";

$route['customer_users_filter'] = "web/customer/get_customer_users_filter";
$route['get_customer_filter'] = "web/customer/get_customer_filter";

$route['admin_report_data_1'] = "web/customer/get_use_report_data_1";
$route['admin_report_data_2'] = "web/customer/get_use_report_data_2";
$route['admin_report_data_3'] = "web/customer/get_use_report_data_3";
$route['admin_report_data_4'] = "web/customer/get_use_report_data_4";
$route['admin_report_data_5'] = "web/customer/get_use_report_data_5";
$route['admin_report_data_6'] = "web/customer/get_use_report_data_6";

$route['category_article_filter'] = "web/catagory/category_article_filter";
$route['open_magazine_filter'] = "web/catagory/get_open_magazine_filter";

$route['closed_magazine_filter'] = "web/magazine/get_closed_magazine_filter";
$route['magazine_articles'] = "web/magazine/get_magazine_articles_filter";
$route['registerAccess'] = "website/website_login/register_access";

$route['getArticleDetail'] = "webservices/landing_screen/getArticleDetail";
$route['redirectAccess'] = "website/website_login/redirectAccess";
$route['getAdvDetail'] = "webservices/landing_screen/getAdvDetail";
$route['getMagazineData'] = "webservices/magzine_articles/getMagazineData";
$route['deepLink'] = "web/superadmin/listDeepLink";
$route['fbCallback'] = "website/website_login/facebook_callback";
$route['gCallback'] = "website/website_login/google_callback";
$route['sendforpublish'] = "customer/customer_magazine/send_for_publish";

$route['magazineCodes'] = "customer/customer_index/magazineCodes";
$route['magazine_code_list'] = "customer/customer_index/magazine_code_list";
$route['edit_magazine_code'] = "customer/customer_index/edit_magazine_code";
$route['getBranchDetails'] = "webservices/landing_screen/getBranchDetails";
$route['getUserHasMagazine'] = "webservices/landing_screen/getUserHasMagazine";

$route['getSignageContent'] = "website/website_login/getSignageContent";
$route['getSignageText'] = "website/website_login/getSignageText";
$route['getSignageQrCode'] = "website/website_login/getSignageQrCode";

$route['tvCampaigns'] = "customer/customer_index/tvCampaigns";
$route['customerCampaign'] = "customer/customer_index/customer_campaign";
$route['getMagazineContent'] = "customer/customer_index/get_magazine_content";
$route['getMagazineArticleContent'] = "customer/customer_index/get_magazine_article_content";
$route['campaignLayoutList'] = "web/superadmin/campaign_layout_list";
$route['campaignLayoutDetail'] = "web/superadmin/campaign_layout_detail";
$route['getTextDevice'] = "web/superadmin/get_text_device";
$route['campaignGrocustomerdashbordup'] = "customer/customer_index/campaign_group";

$route['kumonlogin'] = "webservices/kumon/login";
$route['articleLocation'] = "webservices/kumon/generateArticleLocation";
$route['testKumon'] = "webservices/kumon/testKumon";

$route['groups'] = "web/kumon/group_list";
$route['newGroup'] = "web/kumon/new_group";
$route['kumonRegistration'] = "website/kumon/register";
$route['editGroup'] = "web/kumon/edit_group";
$route['deleteGroup'] = "web/kumon/delete_group";

$route['changeArticleOrder'] = "web/magazine/change_order";
$route['newFilter'] = "web/notifications/new_filter";
$route['customerNewFilter'] = "customer/notifications/new_filter";

/* End of file routes.php */




