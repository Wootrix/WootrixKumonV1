//
//  AppDelegate.m
//  Wootrix
//
//  Created by Saurabh Verma on 11/12/14.
//  Copyright (c) 2014 Techahead. All rights reserved.
//

#import "AppDelegate.h"
#import "WXLoginViewController.h"
#import <Crashlytics/Crashlytics.h>
#import "WXLandingScreenViewController.h"
#import "WXHomeViewController.h"
#import "WXLoginViewController.h"
#import "UIAlertView+Blocks.h"
#import "WXIFrameVideoPlayerController.h"
#import "WXCurrentLocation.h"
#import "WXLoginViewController.h"
#import "Branch.h"
#import <CoreLocation/CoreLocation.h>
#import <FBSDKCoreKit/FBSDKCoreKit.h>
#import <TwitterKit/TwitterKit.h>
#import "MSAL.h"


#if defined(__IPHONE_10_0) && __IPHONE_OS_VERSION_MAX_ALLOWED >= __IPHONE_10_0
@import UserNotifications;
#endif
@import Firebase;
@import FirebaseInstanceID;
@import FirebaseMessaging;

#ifndef NSFoundationVersionNumber_iOS_9_x_Max
#define NSFoundationVersionNumber_iOS_9_x_Max 1299
#endif

#if defined(__IPHONE_10_0) && __IPHONE_OS_VERSION_MAX_ALLOWED >= __IPHONE_10_0
@interface AppDelegate () <UNUserNotificationCenterDelegate, FIRMessagingDelegate>
@end
#endif

@interface AppDelegate ()<CLLocationManagerDelegate,UIAlertViewDelegate>

@property (strong, nonatomic) CLLocationManager *locationManager;

@end

@implementation AppDelegate
@synthesize articleAlert;

typedef enum _NotificationType
{
    message = 0,
    article,
    closemagazine,
    advertisement
} NotificationType;
- (BOOL)application:(UIApplication *)application didFinishLaunchingWithOptions:(NSDictionary *)launchOptions
{
    // Override point for customization after application launch.
    //
    
    //#if defined (DEBUG) && (DEBUG==1)
    //
    //   [self writeLogsIntoFile];
    //
    //    NSString	*ourVersion = [[NSBundle mainBundle] objectForInfoDictionaryKey: @"CFBundleVersion"];
    //
    //    NSLog(@"********************************************");
    //    NSLog(@"  This is the Wootrix App with version %@", ourVersion);
    //    NSLog(@"********************************************");
    //
    //#endif
//    NSLog(@"%@", [UserDefaults objectForKey:kToken]);
//    NSLog(@"%@", [UserDefaults objectForKey:kIsLoggedin]);
//    NSLog(@"%@", [UserDefaults objectForKey:kUserImage]);
//    NSLog(@"%@", [UserDefaults objectForKey:kUserName]);
//    NSLog(@"%@", [UserDefaults objectForKey:kUserEmail]);
//    NSLog(@"%@", [UserDefaults objectForKey:kRequirePassword]);
//    NSLog(@"%@", [UserDefaults objectForKey:kRequireEmail]);
//
//    NSLog(@"%@", [UserDefaults objectForKey:kIsEmail]);
//    NSLog(@"%@", [UserDefaults objectForKey:kIsFacebook]);
//    NSLog(@"%@", [UserDefaults objectForKey:kIsTwitter]);
//    NSLog(@"%@", [UserDefaults objectForKey:kIsLinkedIn]);
    //    NSLog(@"%@", [UserDefaults objectForKey:kIsGoogle]);
    
    
//    NSLog(@"%@", [UserDefaults objectForKey:kArticleLanguages]);
//    NSLog(@"%@", [UserDefaults objectForKey:kSavedTopics]);
    
    

    
//    [UserDefaults setObject:@(2) forKey:kToken];
//    [UserDefaults setObject:@(1) forKey:kIsLoggedin];
//    [UserDefaults setObject:@"Orientador" forKey:kUserName];
//    [UserDefaults setObject:@"professor.teste06@unidadekumon.com.br" forKey:kUserEmail];
//    [UserDefaults setObject:@(0) forKey:kRequirePassword];
//
//    [UserDefaults setObject:@(0) forKey:kRequireEmail];
//    [UserDefaults setObject:@(1) forKey:kIsEmail];
//    [UserDefaults setObject:@(0) forKey:kIsFacebook];
//    [UserDefaults setObject:@(0) forKey:kIsTwitter];
//    [UserDefaults setObject:@(0) forKey:kIsLinkedIn];
//    [UserDefaults setObject:@(0) forKey:kIsGoogle];
//    [UserDefaults setObject:@"en" forKey:kAppLanguage];
//    [UserDefaults setObject:[NSDate date] forKey:@"lastLogged"];
//    [UserDefaults setObject:@[@"en", @"pt", @"es"] forKey:kArticleLanguages];
//    [UserDefaults setObject:@[@(24), @(25), @(26), @(27), @(28), @(29)] forKey:kSavedTopics];
//
//    [UserDefaults synchronize];
    
    [UserDefaults setObject:@"0.0" forKey:kLatitude];
    [UserDefaults setObject:@"0.0" forKey:kLongitude];
    
    [[NSNotificationCenter defaultCenter] addObserver:self
                                             selector:@selector(startLocationTimer)
                                                 name:UIApplicationDidBecomeActiveNotification object:nil];
    //[self startLocationTimer];
    
    [[NSUserDefaults standardUserDefaults]setBool:NO forKey:@"pushedFromBackground"];
    [Crashlytics startWithAPIKey:@"09e843d933d69d501612438002a6adb6077823d9"];
    if (isDeviceIPad)
    {
        _storyBoard = [UIStoryboard storyboardWithName:@"Mainipad" bundle:nil];
        
    }
    else
    {
        _storyBoard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
    }
    _navController = [_storyBoard instantiateViewControllerWithIdentifier:@"WXNavigationController"];
    
    self.window = [[UIWindow alloc] initWithFrame:[[UIScreen mainScreen] bounds]];
    // Override point for customization after application launch.
    self.window.backgroundColor = [UIColor whiteColor];
    [self.window makeKeyAndVisible];
    [self.window setRootViewController:_navController];
    
    if (![UserDefaults objectForKey:kAppLanguage])
    {
        
        NSString *language =[[UserDefaults objectForKey:@"AppleLanguages"] objectAtIndex:0];
        if ([language rangeOfString:[@"en" lowercaseString]].location != NSNotFound)
        {
            [UserDefaults setObject:@"en" forKey:kAppLanguage];
            [UserDefaults synchronize];
        }
        else if ([language rangeOfString:[@"es" lowercaseString]].location != NSNotFound)
        {
            [UserDefaults setObject:@"es" forKey:kAppLanguage];
            [UserDefaults synchronize];
        }
        else if ([language rangeOfString:[@"pt" lowercaseString]].location != NSNotFound)
        {
            [UserDefaults setObject:@"pt" forKey:kAppLanguage];
            [UserDefaults synchronize];
        }
        else
        {
            [UserDefaults setObject:@"en" forKey:kAppLanguage];
            [UserDefaults synchronize];
        }
    }
    
    if (![UserDefaults objectForKey:kArticleLanguages]) {
        NSMutableArray *array = [[NSMutableArray alloc] initWithArray:@[@"en"]];
        [UserDefaults setObject:array forKey:kArticleLanguages];
        [UserDefaults synchronize];
    }
    
    
    //    WXLoginViewController *loginViewController = [_storyBoard instantiateViewControllerWithIdentifier:@"WXLoginViewController"];
    
    //Setting Up Location manager
//    [self initializeLocationManager];
//    [self startLocationManager];
    
    
    if (floor(NSFoundationVersionNumber) <= NSFoundationVersionNumber_iOS_9_x_Max) {
        UIUserNotificationType allNotificationTypes =
        (UIUserNotificationTypeSound | UIUserNotificationTypeAlert | UIUserNotificationTypeBadge);
        UIUserNotificationSettings *settings =
        [UIUserNotificationSettings settingsForTypes:allNotificationTypes categories:nil];
        [[UIApplication sharedApplication] registerUserNotificationSettings:settings];
    } else {
        // iOS 10 or later
#if defined(__IPHONE_10_0) && __IPHONE_OS_VERSION_MAX_ALLOWED >= __IPHONE_10_0
        UNAuthorizationOptions authOptions =
        UNAuthorizationOptionAlert
        | UNAuthorizationOptionSound
        | UNAuthorizationOptionBadge;
        [[UNUserNotificationCenter currentNotificationCenter]
         requestAuthorizationWithOptions:authOptions
         completionHandler:^(BOOL granted, NSError * _Nullable error) {
         }
         ];
        
        // For iOS 10 display notification (sent via APNS)
        [[UNUserNotificationCenter currentNotificationCenter] setDelegate:self];
        // For iOS 10 data message (sent via FCM)
        [[FIRMessaging messaging] setRemoteMessageDelegate:self];
#endif
    }
    
    [[UIApplication sharedApplication] registerForRemoteNotifications];
    
    [FIRApp configure];
    
    
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(tokenRefreshNotification:) name:@"tokenRefreshNotification" object:nil];
    
    //branch deep link
    
    Branch *branch = [Branch getInstance];
    
    [branch initSessionWithLaunchOptions:launchOptions andRegisterDeepLinkHandler:^(NSDictionary *params, NSError *error) {
        if (!error && params) {
            // params are the deep linked params associated with the link that the user clicked -> was re-directed to this app
            // params will be empty if no data found
            // ... insert custom logic here ...
            NSLog(@"deep link params: %@", params.description);
            
            if ([params objectForKey:@"$magazine_code"] != nil)
                [[PropertyBag Instance] Add:[params objectForKey:@"$magazine_code"] forKey:@"magazine_code"];
            if ([params objectForKey:@"$article_id"] != nil )
                [[PropertyBag Instance] Add:[params objectForKey:@"$article_id"] forKey:@"article_id"];
            if ([params objectForKey:@"$magazine_id"] != nil)
                [[PropertyBag Instance] Add:[params objectForKey:@"$magazine_id"] forKey:@"magazine_id"];
            if ([params objectForKey:@"$ad_id"] != nil)
                [[PropertyBag Instance] Add:[params objectForKey:@"$ad_id"] forKey:@"ad_id"];
            if ([params objectForKey:@"$show_input_code_dlg"] != nil)
                [[PropertyBag Instance] Add:[params objectForKey:@"$show_input_code_dlg"] forKey:@"show_input_code_dlg"];
        }
    }];
    
    //facebook
    [[FBSDKApplicationDelegate sharedInstance] application:application
                             didFinishLaunchingWithOptions:launchOptions];
    
    [[Twitter sharedInstance] startWithConsumerKey:@"wuHhkpecoGtl4pIyyVtL7YMgX" consumerSecret:@"wmvUzUWWue264EMkcP6FjYubevLtcgPRry4VAiT8tKDR7rCIgj"];
    
    
    return YES;
}

- (void)writeLogsIntoFile
{
    NSString *docDirectory = [NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES) objectAtIndex:0];
    NSString *logPath = [docDirectory stringByAppendingPathComponent:@"console.txt"];
    freopen([logPath cStringUsingEncoding:NSASCIIStringEncoding], "a+", stderr);
}


- (void)tokenRefreshNotification:(NSNotification *)notification {
    // Note that this callback will be fired everytime a new token is generated, including the first
    // time. So if you need to retrieve the token as soon as it is available this is where that
    // should be done.
    
    
    NSString *refreshedToken = [[FIRInstanceID instanceID] token];
    if (refreshedToken != nil) {
        [[NSUserDefaults standardUserDefaults]setObject:refreshedToken forKey:kFirebaseId];
        [[NSUserDefaults standardUserDefaults]synchronize];
    }
    
    NSLog(@"InstanceID token: %@", refreshedToken);
    [self connectToFcm];
}

- (void)application:(UIApplication *)application didRegisterForRemoteNotificationsWithDeviceToken:(NSData *)deviceToken {
    NSLog(@"Did Register for Remote Notifications with Device Token (%@)", deviceToken);
    
    NSLog(@"register device token begin");
    
    [[FIRInstanceID instanceID]setAPNSToken:deviceToken type:FIRInstanceIDAPNSTokenTypeProd];
    //[[FIRInstanceID instanceID]setAPNSToken:deviceToken type:FIRInstanceIDAPNSTokenTypeSandbox];
    
    if ([[NSUserDefaults standardUserDefaults]objectForKey:kFirebaseId]==nil) {
        
        [[NSNotificationCenter defaultCenter]postNotificationName:@"tokenRefreshNotification"object:self];
    }
    NSString * mes = [[NSUserDefaults standardUserDefaults]objectForKey:kFirebaseId];
    NSLog(@"Message: %@",mes);
    
    NSLog(@"register device token end");
    
}

- (void)connectToFcm {
    
    NSLog(@"fcm begin");
    
    [[FIRMessaging messaging] disconnect];
    
    [[FIRMessaging messaging] connectWithCompletion:^(NSError * _Nullable error) {
        if (error != nil) {
            NSLog(@"Unable to connect to FCM. %@", error);
            
        } else {
            NSLog(@"Connected to FCM.");
        }
    }];
    NSLog(@"fcm end");
    
}
#pragma mark - Register For Remote Notification

- (void)application:(UIApplication *)application
didReceiveRemoteNotification:(NSDictionary *)userInfo
fetchCompletionHandler:
(void (^)(UIBackgroundFetchResult))completionHandler {
    // Let FCM know about the message for analytics etc.
    NSLog(@"%@", userInfo);
    [[Branch getInstance] handlePushNotification:userInfo];
    
    [self handlePushNotifications:userInfo];
    // handle your message.
}

//#if defined(__IPHONE_10_0) && __IPHONE_OS_VERSION_MAX_ALLOWED >= __IPHONE_10_0
- (void)userNotificationCenter:(UNUserNotificationCenter *)center
       willPresentNotification:(UNNotification *)notification
         withCompletionHandler:(void (^)(UNNotificationPresentationOptions))completionHandler {
    // Print message ID.
    NSDictionary *userInfo = notification.request.content.userInfo;
    NSLog(@"Message ID: %@", userInfo[@"gcm.message_id"]);
    
    // Print full message.
    NSLog(@"%@", userInfo);
    
    [self handlePushNotifications : userInfo];
}
-(void)userNotificationCenter:(UNUserNotificationCenter *)center didReceiveNotificationResponse:(UNNotificationResponse *)response withCompletionHandler:(void(^)())completionHandler{
    
    NSLog(@"Userinfo %@",response.notification.request.content.userInfo);
    [self handlePushNotifications : response.notification.request.content.userInfo];
    
}

- (void)applicationWillResignActive:(UIApplication *)application {
    // Sent when the application is about to move from active to inactive state. This can occur for certain types of temporary interruptions (such as an incoming phone call or SMS message) or when the user quits the application and it begins the transition to the background state.
    // Use this method to pause ongoing tasks, disable timers, and throttle down OpenGL ES frame rates. Games should use this method to pause the game.
}

- (void)applicationDidEnterBackground:(UIApplication *)application {
    // Use this method to release shared resources, save user data, invalidate timers, and store enough application state information to restore your application to its current state in case it is terminated later.
    // If your application supports background execution, this method is called instead of applicationWillTerminate: when the user quits.
    [[FIRMessaging messaging] disconnect];
    
}

- (void)applicationWillEnterForeground:(UIApplication *)application {
    // Called as part of the transition from the background to the inactive state; here you can undo many of the changes made on entering the background.
}

- (void)applicationDidBecomeActive:(UIApplication *)application {
    // Restart any tasks that were paused (or not yet started) while the application was inactive. If the application was previously in the background, optionally refresh the user interface.
    [self connectToFcm];
    [FBSDKAppEvents activateApp];
}

- (void)applicationWillTerminate:(UIApplication *)application {
    // Called when the application is about to terminate. Save data if appropriate. See also applicationDidEnterBackground:.
    // Saves changes in the application's managed object context before the application terminates.
    [[FIRMessaging messaging] disconnect];
    
    [self saveContext];
}


//- (BOOL)application:(UIApplication *)app openURL:(NSURL *)url options:(NSDictionary<UIApplicationOpenURLOptionsKey,id> *)options {
//    // handler for URI Schemes (depreciated in iOS 9.2+, but still used by some apps)
//    [[Branch getInstance] application:app openURL:url options:options];
//
//    [[FBSDKApplicationDelegate sharedInstance] application:app openURL:url options:options];
//
//    [GPPURLHandler handleURL:url sourceApplication:@"com.apple.SafariViewService" annotation:nil];
//
//    [[Twitter sharedInstance] application:app openURL:url options:options];
//
//    return YES;
//}

- (BOOL)application:(UIApplication *)application
            openURL:(NSURL *)url
  sourceApplication:(NSString *)sourceApplication
         annotation:(id)annotation
{
    [[FBSDKApplicationDelegate sharedInstance] application:application
                                                   openURL:url
                                         sourceApplication:sourceApplication
                                                annotation:annotation];
    
    //branch deep link
    [[Branch getInstance] handleDeepLink:url];
    
    
    [GPPURLHandler handleURL:url
           sourceApplication:sourceApplication
                  annotation:annotation];
    
     [MSALPublicClientApplication handleMSALResponse:url];
    
    
    [[Twitter sharedInstance] application:application openURL:url options:@{}];
    
    return (true);
    //return [FBSession.activeSession handleOpenURL:url];
}

// Respond to Universal Links
- (BOOL)application:(UIApplication *)application
continueUserActivity:(NSUserActivity *)userActivity
 restorationHandler:(void (^)(NSArray *restorableObjects))restorationHandler
{
    BOOL handledByBranch = [[Branch getInstance] continueUserActivity:userActivity];
    
    return handledByBranch;
}


-(void)handlePushNotifications : (NSDictionary *)userInfo
{
    // Let FCM know about the message for analytics etc.
    NSLog(@"%@", userInfo);
    
    if ([UIApplication sharedApplication].applicationState == UIApplicationStateActive) {
        
        
        //if ()
        NSArray *items = @[@"message", @"article", @"closemagazine",@"advertisement"];
        
        
        switch((int)[items indexOfObject:[userInfo objectForKey:@"messagetype"]])
        
        {
            case 0 :
            {
                
                NSString * message1 = [NSString stringWithFormat:@"%@",[[[userInfo objectForKey:@"aps"] objectForKey:@"alert"] valueForKey:@"body"]];
                NSString * title = [NSString stringWithFormat:@"%@",[[[userInfo objectForKey:@"aps"] objectForKey:@"alert"] valueForKey:@"title"]];
                UIAlertView * alert = [[UIAlertView alloc]initWithTitle:title message:message1 delegate:self cancelButtonTitle: @"Ok"otherButtonTitles:nil, nil];
                [alert show];
                break;
            }
                
                
            case 1 :{
                if (![UserDefaults boolForKey:kIsLoggedin])
                {
                    //                    [self alertcontrollerToShow:@"Wootrix" message: [NSString stringWithFormat:@"You need login to view this '%@' article",[userInfo objectForKey:@"title"]]];
                    [self alertcontrollerToShow:@"Wootrix" message: [NSString stringWithFormat:[WXHomeViewController languageSelectedStringForKey:@"You need login to view this Article"]]];
                    break;
                }
                else{
                    NSString* message1 = [NSString stringWithFormat:@"%@",[userInfo objectForKey:@"message"]];
                    
                    
                    [UIAlertView displayAlertWithTitle:@"wootrix"
                                               message:message1
                                       leftButtonTitle:@"Cancel"
                                      leftButtonAction:nil                                      rightButtonTitle:@"OK"
                                     rightButtonAction:^{
                                         if ([[_navController visibleViewController]isKindOfClass:[WXIFrameVideoPlayerController class]])
                                         {
                                             [self.navController dismissViewControllerAnimated:NO completion:nil];
                                         }
                                         
                                         [self openArticleThroughNotification:userInfo];
                                     }];
                    
                    break;
                    
                }
            }
            case 2 :{
                if (![UserDefaults boolForKey:kIsLoggedin])
                {
                    //                    [self alertcontrollerToShow:@"Wootrix" message: [NSString stringWithFormat:@"You need login to view this '%@' Closed Magazine",[userInfo objectForKey:@"title"]]];
                    [self alertcontrollerToShow:@"Wootrix" message: [NSString stringWithFormat:[WXHomeViewController languageSelectedStringForKey:@"You need login to view this Closed Magazine"]]];
                    break;
                }else{
                    NSString* message1 = [NSString stringWithFormat:@"%@",[userInfo objectForKey:@"message"]];
                    
                    [UIAlertView displayAlertWithTitle:@"wootrix"
                                               message:message1
                                       leftButtonTitle:@"Cancel"
                                      leftButtonAction:nil                                      rightButtonTitle:@"OK"
                                     rightButtonAction:^{
                                         if ([[_navController visibleViewController]isKindOfClass:[WXIFrameVideoPlayerController class]])
                                         {
                                             [self.navController dismissViewControllerAnimated:NO completion:nil];
                                         }
                                         
                                         [[NSNotificationCenter defaultCenter]postNotificationName:@"openCloseArticleThroughNotification" object:nil userInfo:userInfo];
                                     }];
                    
                    
                    break;
                }
            }
            case 3 :
            {
                if (![UserDefaults boolForKey:kIsLoggedin])
                {
                    [self alertcontrollerToShow:@"Wootrix" message: [NSString stringWithFormat:[WXHomeViewController languageSelectedStringForKey:@"You need login to view this Advertisement."]]];
                    break;
                }else{
                    NSString* message1 = [NSString stringWithFormat:@"%@",[userInfo objectForKey:@"message"]];
                    [UIAlertView displayAlertWithTitle:@"wootrix"
                                               message: message1
                                       leftButtonTitle:@"Cancel"
                                      leftButtonAction:nil                                      rightButtonTitle:@"OK"
                                     rightButtonAction:^{
                                         if ([[_navController visibleViewController]isKindOfClass:[WXIFrameVideoPlayerController class]])
                                         {
                                             [self.navController dismissViewControllerAnimated:NO completion:nil];
                                         }
                                         
                                         [self advertisementThroughNotification:userInfo];
                                     }];
                    
                    break;
                }
            }
        }
        
        
        
    }
    else{
        NSArray *items = @[@"message", @"article", @"closemagazine",@"advertisement"];
        switch((int)[items indexOfObject:[userInfo objectForKey:@"messagetype"]])
        {
            case 0 :
            {
                NSString * message1 = [NSString stringWithFormat:@"%@",[[[userInfo objectForKey:@"aps"] objectForKey:@"alert"] valueForKey:@"body"]];
                
                //NSString * message = [NSString stringWithFormat:@"%@",[[userInfo objectForKey:@"aps"] valueForKey:@"alert"]];
                NSString * title = [NSString stringWithFormat:@"%@",[[[userInfo objectForKey:@"aps"] objectForKey:@"alert"] valueForKey:@"title"]];
                UIAlertView * alert = [[UIAlertView alloc]initWithTitle:title message:message1 delegate:self cancelButtonTitle: @"Ok"otherButtonTitles:nil, nil];
                [alert show];
                break;
            }
            case 1 :
            {
                if (![UserDefaults boolForKey:kIsLoggedin])
                {
                    NSLog(@"current view controlleraaaa %@",_navController.visibleViewController);
                    //                    [self alertcontrollerToShow:@"Wootrix" message: [NSString stringWithFormat:@"You need login to view this '%@' article",[userInfo objectForKey:@"title"]]];
                    [self alertcontrollerToShow:@"Wootrix" message: [NSString stringWithFormat:[WXHomeViewController languageSelectedStringForKey:@"You need login to view this Article"]]];
                    break;
                }else
                {
                    if ([[_navController visibleViewController]isKindOfClass:[WXIFrameVideoPlayerController class]])
                    {
                        [self.navController dismissViewControllerAnimated:NO completion:nil];
                    }
                    
                    [self openArticleThroughNotification:userInfo];
                    break;
                }
            }
            case 2 :
            {
                if (![UserDefaults boolForKey:kIsLoggedin])
                {
                    //                    [self alertcontrollerToShow:@"Wootrix" message: [NSString stringWithFormat:@"You need login to view this '%@' Closed Magazine",[userInfo objectForKey:@"title"]]];
                    [self alertcontrollerToShow:@"Wootrix" message: [NSString stringWithFormat:[WXHomeViewController languageSelectedStringForKey:@"You need login to view this Closed Magazine"]]];
                    break;
                }else
                {
                    if ([[_navController visibleViewController]isKindOfClass:[WXIFrameVideoPlayerController class]])
                    {
                        [self.navController dismissViewControllerAnimated:NO completion:nil];
                    }
                    
                    [[NSUserDefaults standardUserDefaults]setBool:YES forKey:@"pushedFromBackground"];
                    [[NSUserDefaults standardUserDefaults]setObject:userInfo forKey:@"closeMagazineDetails"];
                    //                [[NSUserDefaults standardUserDefaults]synchronize];
                    //changes need to be made from here
                    NSLog(@"from background closed article completely opened closemagazine");
                    
                    [[NSNotificationCenter defaultCenter]postNotificationName:@"openCloseArticleThroughNotification" object:nil userInfo:userInfo];
                    break;
                }
                
            }
            case 3 :
                if (![UserDefaults boolForKey:kIsLoggedin])
                {
                    [self alertcontrollerToShow:@"Wootrix" message: [NSString stringWithFormat:[WXHomeViewController languageSelectedStringForKey:@"You need login to view this Advertisement."]]];
                    break;
                }else
                {
                    
                    [self advertisementThroughNotification:userInfo];
                    break;
                }
        }
        
    }
    
    // handle your message.
}

#pragma mark - Core Data stack

@synthesize managedObjectContext = _managedObjectContext;
@synthesize managedObjectModel = _managedObjectModel;
@synthesize persistentStoreCoordinator = _persistentStoreCoordinator;

- (NSURL *)applicationDocumentsDirectory {
    // The directory the application uses to store the Core Data store file. This code uses a directory named "com.techahead.Wootrix" in the application's documents directory.
    return [[[NSFileManager defaultManager] URLsForDirectory:NSDocumentDirectory inDomains:NSUserDomainMask] lastObject];
}

- (NSManagedObjectModel *)managedObjectModel {
    // The managed object model for the application. It is a fatal error for the application not to be able to find and load its model.
    if (_managedObjectModel != nil) {
        return _managedObjectModel;
    }
    NSURL *modelURL = [[NSBundle mainBundle] URLForResource:@"Wootrix" withExtension:@"momd"];
    _managedObjectModel = [[NSManagedObjectModel alloc] initWithContentsOfURL:modelURL];
    return _managedObjectModel;
}

- (NSPersistentStoreCoordinator *)persistentStoreCoordinator {
    // The persistent store coordinator for the application. This implementation creates and return a coordinator, having added the store for the application to it.
    if (_persistentStoreCoordinator != nil) {
        return _persistentStoreCoordinator;
    }
    
    // Create the coordinator and store
    
    _persistentStoreCoordinator = [[NSPersistentStoreCoordinator alloc] initWithManagedObjectModel:[self managedObjectModel]];
    NSURL *storeURL = [[self applicationDocumentsDirectory] URLByAppendingPathComponent:@"Wootrix.sqlite"];
    NSError *error = nil;
    NSString *failureReason = @"There was an error creating or loading the application's saved data.";
    if (![_persistentStoreCoordinator addPersistentStoreWithType:NSSQLiteStoreType configuration:nil URL:storeURL options:nil error:&error]) {
        // Report any error we got.
        NSMutableDictionary *dict = [NSMutableDictionary dictionary];
        dict[NSLocalizedDescriptionKey] = @"Failed to initialize the application's saved data";
        dict[NSLocalizedFailureReasonErrorKey] = failureReason;
        dict[NSUnderlyingErrorKey] = error;
        error = [NSError errorWithDomain:@"YOUR_ERROR_DOMAIN" code:9999 userInfo:dict];
        // Replace this with code to handle the error appropriately.
        // abort() causes the application to generate a crash log and terminate. You should not use this function in a shipping application, although it may be useful during development.
        NSLog(@"Unresolved error %@, %@", error, [error userInfo]);
        abort();
    }
    
    return _persistentStoreCoordinator;
}


- (NSManagedObjectContext *)managedObjectContext {
    // Returns the managed object context for the application (which is already bound to the persistent store coordinator for the application.)
    if (_managedObjectContext != nil) {
        return _managedObjectContext;
    }
    
    NSPersistentStoreCoordinator *coordinator = [self persistentStoreCoordinator];
    if (!coordinator) {
        return nil;
    }
    _managedObjectContext = [[NSManagedObjectContext alloc] init];
    [_managedObjectContext setPersistentStoreCoordinator:coordinator];
    return _managedObjectContext;
}

#pragma mark - Core Data Saving support

- (void)saveContext {
    NSManagedObjectContext *managedObjectContext = self.managedObjectContext;
    if (managedObjectContext != nil) {
        NSError *error = nil;
        if ([managedObjectContext hasChanges] && ![managedObjectContext save:&error]) {
            // Replace this implementation with code to handle the error appropriately.
            // abort() causes the application to generate a crash log and terminate. You should not use this function in a shipping application, although it may be useful during development.
            NSLog(@"Unresolved error %@, %@", error, [error userInfo]);
            abort();
        }
    }
}

#pragma mark - Location Manager

-(void)startLocationTimer
{
    dispatch_async(dispatch_get_main_queue(), ^{
        
//        self.timer_LocationUpdate = nil;
//        self.timer_LocationUpdate = [NSTimer scheduledTimerWithTimeInterval:900 target:self selector:@selector(startLocationManager) userInfo:nil repeats:YES];
    });
    
}
- (void)initializeLocationManager
{
//    _locationManager = [[CLLocationManager alloc] init];
//    [_locationManager setDelegate:self];
//    _locationManager.distanceFilter = kCLDistanceFilterNone;//kCLDistanceFilterNone;
//    [_locationManager setDesiredAccuracy:kCLLocationAccuracyBest];//kCLLocationAccuracyNearestTenMeters
}

- (void)startLocationManager
{
//    self.locationManager = [[CLLocationManager alloc]init];
//    [self.locationManager setDelegate:self];
//    self.locationManager.distanceFilter = kCLDistanceFilterNone;
//    [self.locationManager setDesiredAccuracy:kCLLocationAccuracyBest];
//    if ([self.locationManager respondsToSelector:@selector(requestWhenInUseAuthorization)]) {
//        [self.locationManager requestWhenInUseAuthorization];
//    }
//    [self.locationManager startUpdatingLocation];
}

- (void)stopLocationManager
{
//    [self.locationManager stopUpdatingLocation];
//    self.locationManager = nil;
//    [self.timer_LocationUpdate invalidate];
//    self.timer_LocationUpdate = nil;
}
- (void)locationManager:(CLLocationManager *)manager didUpdateLocations:(NSArray<CLLocation *> *)locations
{
    NSLog(@"LOCATION UPDATED+++++++++++++++++++++");
    
    CLLocation *location = [locations objectAtIndex:0];
    _latString = [NSString stringWithFormat:@"%.10f",[location coordinate].latitude];
    _longString = [NSString stringWithFormat:@"%.10f",[location coordinate].longitude];
    
    [UserDefaults setObject:_latString forKey:kLatitude];
    [UserDefaults setObject:_longString forKey:kLongitude];
    
    [self stopLocationManager];
    
    dispatch_async(dispatch_get_main_queue(), ^{
        
        if (self.timer_LocationUpdate) {
            [self.timer_LocationUpdate invalidate];
            self.timer_LocationUpdate = nil;
        }
    });
    //    if [UserDefaults objectForKey:kLatitude ]isEqualToString: @"0.0000000000" && [UserDefaults objectForKey:kLongitude ]isEqualToString:@"0.0000000000"+
    
    if ([UserDefaults boolForKey:kIsLoggedin]) {
        
        NSDictionary *dictSendParams = @{@"userId" : [UserDefaults valueForKey:kToken],
                                         @"latitude"            :[UserDefaults objectForKey:kLatitude],
                                         @"longitude"         :[UserDefaults objectForKey:kLongitude],
                                         };
        
        [self callLoginWebServiceToLoadFcmToken:dictSendParams];
    }
}

- (void)locationManager:(CLLocationManager *)manager didFailWithError:(NSError *)error
{
    
    _latString = @"0.0000000000";
    _longString = @"0.0000000000";
    
    [UserDefaults setObject:_latString forKey:kLatitude];
    [UserDefaults setObject:_longString forKey:kLongitude];
    
    dispatch_async(dispatch_get_main_queue(), ^{
        
//        [self.timer_LocationUpdate invalidate];
//        self.timer_LocationUpdate = nil;
//        self.timer_LocationUpdate = [NSTimer scheduledTimerWithTimeInterval:900 target:self selector:@selector(startLocationManager) userInfo:nil repeats:YES];
    });
    
    NSLog(@"FAILED LOCATION+++++++++++++++++++++ %ld",(long)[error code]);
    if ([error code]== kCLAuthorizationStatusRestricted && [UserDefaults boolForKey:kIsLoggedin])
    {
        
//        NSString *alertMessage = [NSString stringWithFormat:@"\"%@\" requires your current location. Please go to settings and enable location service for this app.",kAPPName];
//        UIAlertView *alert =  [[UIAlertView alloc] initWithTitle:kAPPName message:alertMessage delegate:self cancelButtonTitle:@"OK" otherButtonTitles:@"Settings", nil] ;
//        alert.tag = 1001;
//        [alert show];
        
    }
}

- (void)alertView:(UIAlertView *)alertView clickedButtonAtIndex:(NSInteger)buttonIndex
{
//    if (alertView.tag == 1001) {
//        if (buttonIndex == 1) {
//            [[UIApplication sharedApplication] openURL:[NSURL URLWithString:@"prefs:root=LOCATION_SERVICES"]];
//            //[self startLocationManager];
//#if defined(__IPHONE_10_0) && __IPHONE_OS_VERSION_MAX_ALLOWED >= __IPHONE_10_0
//            [[UIApplication sharedApplication] openURL:[NSURL URLWithString:@"App-prefs:root=LOCATION_SERVICES"]];
//#endif
//
//
//        }
//    }
}

- (void)locationUpdate
{
    if ([UserDefaults boolForKey:kIsLoggedin])
    {
//        NSString *alertMessage = [NSString stringWithFormat:@"\"%@\" requires your current location. Please go to settings and enable location service for this app.",kAPPName];
//        UIAlertView *alert =  [[UIAlertView alloc] initWithTitle:kAPPName message:alertMessage delegate:self cancelButtonTitle:@"OK" otherButtonTitles:@"Settings", nil] ;
//        alert.tag = 1001;
//        [alert show];
    }
}

- (void)openArticleThroughNotification: (NSDictionary *)userInfo {
    if(![[_navController visibleViewController]isKindOfClass:[WXHomeViewController class]]&&![[_navController visibleViewController]isKindOfClass:[UIAlertController class]])
    {
        NSInteger flag = 0;
        for (UIViewController *viewC in _navController.viewControllers)
        {
            if ([viewC isKindOfClass:[WXHomeViewController class]])
            {
                flag = 1;
                WXHomeViewController *homeViewC = (WXHomeViewController*)viewC;
                //                            [homeViewC setIsMagazine:YES];
                homeViewC.dicOpenArticle = [userInfo mutableCopy];
                if(homeViewC.btnBack.hidden ==NO){
                    homeViewC.btnBack.hidden = YES;
                    [homeViewC setIsMagazine:NO];
                    [homeViewC viewDidAppear:YES];
                }
                
                //                            [homeViewC setDictMagazine:[arrMagazines objectAtIndex:index]];
                //                            [UserDefaults setObject:[[arrMagazines objectAtIndex:index] objectForKey:@"mobileAppBarColorRGB"] forKey:kMagazineColor];
                //                            [UserDefaults synchronize];
                [_navController popToViewController:homeViewC animated:YES];
            }
        }
        if (flag == 0)
        {
            WXHomeViewController *homeViewController = [self.storyBoard instantiateViewControllerWithIdentifier:@"WXHomeViewController"];
            homeViewController.dicOpenArticle = [userInfo mutableCopy];
            if(homeViewController.btnBack.hidden ==NO){
                homeViewController.btnBack.hidden = YES;
                [homeViewController setIsMagazine:NO];
                [homeViewController viewDidAppear:YES];
            }
            //                        [homeViewController setIsMagazine:YES];
            //                        [homeViewController setDictMagazine:[arrMagazines objectAtIndex:index]];
            //                        [UserDefaults setObject:[[arrMagazines objectAtIndex:index] objectForKey:@"mobileAppBarColorRGB"] forKey:kMagazineColor];
            //                        [UserDefaults synchronize];
            [_navController pushViewController:homeViewController animated:YES];
        }
        
    }else{
        
        [[NSNotificationCenter defaultCenter]postNotificationName:@"openArticleNotification" object:nil userInfo:userInfo];
    }
    
    
}
- (void) advertisementThroughNotification: (NSDictionary *)userInfo {
    
    if([_navController.visibleViewController isKindOfClass:[WXOpenAdsViewController class]])
    {
        [[NSNotificationCenter defaultCenter]postNotificationName:@"addAdvertisment" object:nil userInfo:userInfo];
    }
    else
    {
        if(![[_navController visibleViewController]isKindOfClass:[WXHomeViewController class]]&&![[_navController visibleViewController]isKindOfClass:[UIAlertController class]])
        {
            NSInteger flag = 0;
            for (UIViewController *viewC in _navController.viewControllers)
            {
                if ([viewC isKindOfClass:[WXHomeViewController class]])
                {
                    flag = 1;
                    WXHomeViewController *homeViewC = (WXHomeViewController*)viewC;
                    //                            [homeViewC setIsMagazine:YES];
                    homeViewC.openAdvertisementURL = [userInfo objectForKey:@"url"];
                    if(homeViewC.btnBack.hidden ==NO){
                        homeViewC.btnBack.hidden = YES;
                        [homeViewC setIsMagazine:NO];
                        //                        [homeViewC viewDidAppear:YES];
                    }
                    
                    //                            [homeViewC setDictMagazine:[arrMagazines objectAtIndex:index]];
                    //                            [UserDefaults setObject:[[arrMagazines objectAtIndex:index] objectForKey:@"mobileAppBarColorRGB"] forKey:kMagazineColor];
                    //                            [UserDefaults synchronize];
                    [_navController popToViewController:homeViewC animated:YES];
                }
            }
            if (flag == 0)
            {
                WXHomeViewController *homeViewController = [self.storyBoard instantiateViewControllerWithIdentifier:@"WXHomeViewController"];
                homeViewController.openAdvertisementURL= [userInfo objectForKey:@"url"];
                if(homeViewController.btnBack.hidden ==NO){
                    homeViewController.btnBack.hidden = YES;
                    [homeViewController setIsMagazine:NO];
                    // [homeViewController viewDidAppear:YES];
                }
                //                        [homeViewController setIsMagazine:YES];
                //                        [homeViewController setDictMagazine:[arrMagazines objectAtIndex:index]];
                //                        [UserDefaults setObject:[[arrMagazines objectAtIndex:index] objectForKey:@"mobileAppBarColorRGB"] forKey:kMagazineColor];
                //                        [UserDefaults synchronize];
                [_navController pushViewController:homeViewController animated:YES];
            }
            
        }else{
            
            [[NSNotificationCenter defaultCenter]postNotificationName:@"openAdervtismentNotification" object:nil userInfo:userInfo];
        }
    }
}

- (void)alertcontrollerToShow:(NSString *)title message: (NSString *) message
{
    UIAlertController *alertController = [UIAlertController
                                          alertControllerWithTitle:title
                                          message:message
                                          preferredStyle:UIAlertControllerStyleAlert];
    
    UIAlertAction *cancelAction = [UIAlertAction
                                   actionWithTitle:NSLocalizedString(@"Ok", @"Cancel action")
                                   style:UIAlertActionStyleCancel
                                   handler:^(UIAlertAction *action)
                                   {
                                   }];
    
    //    UIAlertAction *okAction = [UIAlertAction
    //                               actionWithTitle:NSLocalizedString(@"Open", @"OK action")
    //                               style:UIAlertActionStyleDefault
    //                               handler:^(UIAlertAction *action)
    //                               {
    ////                                   [self advertisementThroughNotification:userInfo];
    //                               }];
    
    [alertController addAction:cancelAction];
    //    [alertController addAction:okAction];
    [_navController.visibleViewController presentViewController:alertController animated:YES completion:nil];
    
    
}

//test
//- (NSDictionary *)methodToLoadArticleForCloseMagazine
//{
//    NSDictionary *dict=@{@"allowComment":@"Y",
//                         @"allowShare":@"Y",
//                         @"articleDescHTML":@"PHA+RW0gdW0gbW9tZW50b2RlIGRpbmhlaXJvIGN1cnRvLCBhcyBwZXNzb2FzIHNlIHRvcm5hbSBjYWRhIHZleiBtYWlzIGNvbnNjaWVudGVzIGRvIHNldSB2YWxvci5FIHVtYSBkYXMgbWVsaG9yZXMgbWFuZWlyYXMgZGUgZW5mcmVudGFyIGEgY29uY29yciZlY2lyYztuY2lhLCBzZW0gc2FjcmlmaWNhciBvcyBwcmUmY2NlZGlsO29zLCAmZWFjdXRlOyBvZmVyZWNlciB1bSAmb2FjdXRlO",
//                         @"articleDescPlain":@"Em um momentode dinheiro curto, as pessoas se tornam cada vez mais conscientes do seu valor.E uma das melhores maneiras de enfrentar a concorr&ecirc;ncia, sem sacrificar os pre&ccedil;os, &eacute; oferecer um &oacute;timo servi&ccedil;o ao cliente &ndash;",
//                         @"articleId":@"2387",
//                         @"articleType":@"photo",
//                         @"articleVideoUrl":@"http://cipldev.com/wootrix/assets/Article/",
//                         @"commentsCount": @"1",
//                         @"coverPhotoUrl":@"http://cipldev.com/wootrix/assets/Article/2016-05-13amL_org.jpg",
//                         @"createdBy": @"0",
//                         @"createdDate":@"2016-05-13 12:32:52",
//                         @"createdSource":@"customer",
//                         @"detailScreen": @"Y",
//                         @"embedded_thumbnail":@"",
//                         @"embedded_video":@"",
//                         @"fullSoruce":@"http://www.blogdobanas.com.br/dicas-para-conquistar-lealdade-cliente/",
//                         @"landingScreenLogo":@"http://cipldev.com/wootrix/assets/Magazine_cover/",
//                         @"source":@"blogdobanas",
//                         @"title":@"DICAS PAR CONQUISTAR A LEALDADE DO CLIENTE"
//                         };
//
//    return dict;
//}
- (void)callLoginWebServiceToLoadFcmToken: (NSDictionary*)params
{
    if(![[NetworkManager manager] isConnectedToWiFi])
    {
        [[[UIAlertView alloc] initWithTitle:kAPPName message:[WXLoginViewController languageSelectedStringForKey:@"Network Not Available"] delegate:nil cancelButtonTitle:[WXLoginViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles: nil] show];
        return;
    }
    //[self showProgressHUD:[WXLoginViewController languageSelectedStringForKey:@"Loading..."]];
    WXCurrentLocation *loginrequest1= [[WXCurrentLocation alloc] init];
    [loginrequest1 setUserid:[params objectForKey:@"userId"]];
    [loginrequest1 setLatitude:[params objectForKey:@"latitude"]];
    [loginrequest1 setLongitude:[params objectForKey:@"longitude"]];
    [[NetworkManager manager] requestWithMethod:kHTTPMethodPOST1
                                      URLString:@"userlocation"
                                     parameters:loginrequest1
                                         sucess:^(id response)
     {
         // [self dismissProgressHUD];
         
         NSLog(@"response:%@",response);
         
         if ([[response objectForKey:kSuccess] boolValue] == YES)
         {
             if ([[response objectForKey:@"data"] count] > 0)
             {
                 
                 //           getUserSelectedTab
                 
             }
         }
         else
         {
             [[[UIAlertView alloc] initWithTitle:kAPPName message:[response objectForKey:kMessage] delegate:nil cancelButtonTitle:[WXLoginViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles: nil] show];
         }
         
         
     }failure:^(NSError *error)
     {
         NSLog(@"error:%@",error);
         //[self dismissProgressHUD];
     }];
    
}


@end
