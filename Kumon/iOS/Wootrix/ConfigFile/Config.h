//
//  Config.h
//  TechaHeadBase
//
//  Created by Girijesh Kumar on 18/12/14.
//  Copyright (c) 2014 Girijesh Kumar. All rights reserved.
//


#ifndef TechaHeadBase_Config_h
#endif
#define TechaHeadBase_Config_h

/**
 *	---------------------------------------------------------------------
 *		CocoaLumberjack Framework Log levels defined by Targets
 *	---------------------------------------------------------------------
 *	Log levels: off, error, warn, info, verbose
 *	If you set the log level to LOG_LEVEL_ERROR, then you will only see DDLogError statements.
 *	If you set the log level to LOG_LEVEL_WARN, then you will only see DDLogError and DDLogWarn statements.
 *	If you set the log level to LOG_LEVEL_INFO, you'll see Error, Warn and Info statements.
 *	If you set the log level to LOG_LEVEL_DEBUG, you'll see Error, Warn, Info and Debug statements.
 *	If you set the log level to LOG_LEVEL_VERBOSE, you'll see all DDLog statements.
 *	If you set the log level to LOG_LEVEL_OFF, you won't see any DDLog statements.
 */

#if DEBUG

static int ddLogLevel = LOG_LEVEL_VERBOSE;
#elif TEST
static int ddLogLevel = LOG_LEVEL_INFO;
#elif STAGE
static int ddLogLevel = LOG_LEVEL_WARN;
#else
static int ddLogLevel = LOG_LEVEL_ERROR;
#endif


#define SET_ERROR_COLOR() [[DDTTYLogger sharedInstance] setForegroundColor:[UIColor colorWithRed:(255/255.0) green:(0.0) blue:(0.0) alpha:1.0] backgroundColor:nil forFlag:LOG_FLAG_ERROR];
#define SET_WARN_COLOR() [[DDTTYLogger sharedInstance] setForegroundColor:[UIColor colorWithRed:(255/255.0) green:(0.0) blue:(255/255.0) alpha:1.0] backgroundColor:nil forFlag:LOG_FLAG_WARN];
#define SET_INFO_COLOR() [[DDTTYLogger sharedInstance] setForegroundColor:[UIColor colorWithRed:(0.0) green:(255/255.0) blue:(255/255.0) alpha:1.0] backgroundColor:nil forFlag:LOG_FLAG_INFO];
#define SET_VERBOSE_COLOR() [[DDTTYLogger sharedInstance] setForegroundColor:[UIColor colorWithRed:(96/255.0) green:(96/255.0) blue:(96/255.0) alpha:1.0] backgroundColor:nil forFlag:LOG_FLAG_VERBOSE];


/** --------------------------------------------------------
 *		Crashlytics API key defined by Targets.
 *	--------------------------------------------------------
 */
#if DEBUG
#define kCrashlyticsKey @""
#elif TEST
#define kCrashlyticsKey @""
#elif STAGE
#define kCrashlyticsKey @""
#else
#define kCrashlyticsKey @""
#endif

/** --------------------------------------------------------
 *		Font File Name
 *  --------------------------------------------------------
 */
#define ROBOTO_BLACK_FONT        @"Roboto-Black"

/** --------------------------------------------------------
 *		Commonly Used Macros
 *  --------------------------------------------------------
 */
#define IOS(x)   (([[[UIDevice currentDevice] systemVersion] floatValue]>=x)?YES:NO)

/*****************************************************************************/
/* Entry/exit trace macros                                                   */
/*****************************************************************************/
#define TRC_ENTRY()    DDLogVerbose(@"ENTRY: %s:%d:", __PRETTY_FUNCTION__,__LINE__);
#define TRC_EXIT()     DDLogVerbose(@"EXIT:  %s:%d:", __PRETTY_FUNCTION__,__LINE__);


/*****************************************************************************/
/* Local File Path Names                                                     */
/*****************************************************************************/

#define kClientSplashImageName      @"ClientSplash.png"


/*****************************************************************************/
/* UIColor Macro                                                             */
/*****************************************************************************/
#define RGBACOLOR(r,g,b,a)          [UIColor colorWithRed:r/256.f green:g/256.f blue:b/256.f alpha:a]


/*****************************************************************************/
/* Calculate Navigation and Status Bar Height                                */
/*****************************************************************************/
#define kNavigationBarHeight        self.navigationController.navigationBar.frame.size.height
#define kStatusBarHeight            [[UIApplication sharedApplication] statusBarFrame].size.height

/*****************************************************************************/
/* Scanner Y Position and Scanner Height                                     */
/*****************************************************************************/

#define kScannerYPosition           44.0f
#define kScannerHeight              235.0f

#define IS_IPHONE_4 [ [ UIScreen mainScreen ] bounds ].size.height == 480




#define isDeviceIPad (UI_USER_INTERFACE_IDIOM() == UIUserInterfaceIdiomPad)
#define UserDefaults                  [NSUserDefaults standardUserDefaults]
#define kAPPName                                      @"Wootrix"
#define kToken                                        @"token"
#define kEnglishSelected                              @"English"
#define kSpanishSelected                              @"Spanish"
#define kPortugeseSelected                            @"Portuguese"
#define kSavedTopics                                  @"savedTopics"
#define kHidePage                                     @"hidePage"
#define kAppLanguage                                  @"appLanguage"
#define kIsLoggedin                                   @"isLoggedIn"
#define kUserImage                                    @"userImage"
#define kArticleLanguages                             @"articleLanguages"
#define kUserName                                     @"userName"
#define kUserEmail                                    @"userEmail"
#define kRequirePassword                              @"requirePassword"
#define kRequireEmail                                 @"requireEmail"
#define kMagazineColor                                @"magazineColor"
#define kLatitude                                     @"latitude"
#define kLongitude                                     @"longitude"
#define kIsEmail                                    @"is_email"
#define kIsFacebook                                    @"is_facebook"
#define kIsTwitter                                    @"is_twitter"
#define kIsGoogle                                    @"is_google"
#define kIsLinkedIn                                    @"is_linkedin"
#define kFirebaseId                                    @"FirebaseId"

/******************************************************************************/
/* Web Service Keys                                                           */
/******************************************************************************/
#define kSuccess                      @"success"
#define kMessage                      @"message"
#define kData                         @"data"
