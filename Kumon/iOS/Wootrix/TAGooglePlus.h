//
//  TAGooglePlus.h
//  NewGooglePlus
//
//  Created by Rohit Singh on 12/05/14.
//  Copyright (c) 2014 TechAhead. All rights reserved.
//


/* Documentaion
 
 1)
 To enable the Google- API for your app, you need to create an APIs Console project, enable the Google- API then create and get a client ID.
 
 Go to follwing link to create App Client Id
 https://console.developers.google.com/
 
 
 2)
 
 Set Bundle identifier in the url type under info tab of the project.
 This bundle indentifier should be same as the bundle identifier that was used during client Id cretion.
 
 
 3)
 
 ----- Download the Google- iOS SDK, if you haven't already. The SDK includes:
 
 GooglePlus.framework
 GooglePlus.bundle resource bundle with assets for the Google- Sign-In button.
 GoogleOpenSource.framework which is a static framework that contains all of the files within the OpenSource directory.
 
 
 4) ***** Very Important
 
 -----Include the following frameworks in your Xcode project:
 
 AddressBook.framework
 AssetsLibrary.framework
 Foundation.framework
 CoreLocation.framework
 CoreMotion.framework
 CoreGraphics.framework
 CoreText.framework
 MediaPlayer.framework
 Security.framework
 SystemConfiguration.framework
 UIKit.framework
 
 
 
 
 
 
 5)
 ------ Drag and drop the following frameworks from the SDK into your XCode project:
 
 GooglePlus.framework
 GoogleOpenSource.framework
 
 
 6) import following header file in AppDelegate file
 
 """#import <GooglePlus/GooglePlus.h>"""
 
and Add following function in Appdelegate.m
 
 - (BOOL)application: (UIApplication *)application openURL: (NSURL *)url sourceApplication: (NSString *)sourceApplication annotation: (id)annotation
 {
 return [GPPURLHandler handleURL:url sourceApplication:sourceApplication annotation:annotation];
 }

 
 
 
 
 7) *****Very Important
 
 ----Add the ObjC linker flag to the app target's build settingss:
 
 Other Linker Flags: -ObjC
 
 8)
 
 In your app's Info tab, add a URL type and enter your bundle ID as the identifier and scheme:
 
 for demo purpose you can use following client ID ----
 139167343755-u7rp1tkjhiam9siup8n7pkn6fpii35l2.apps.googleusercontent.com
 
 Bundle Identifier----
 com.google.ta
 

 */

#import <Foundation/Foundation.h>
#import <GooglePlus/GooglePlus.h>
#import <GoogleOpenSource/GoogleOpenSource.h>

// Block for normal sharing
typedef void (^GooglePlusCompleteHandler)(BOOL,id);
static     GooglePlusCompleteHandler googlePlusCompleteHandler;

//Block for login

typedef void (^GooglePlusCompletionHandlerForLogin)(BOOL,GPPSignIn* signIn);
static GooglePlusCompletionHandlerForLogin googlePlusCompletionHandlerForLogin;

@interface TAGooglePlus : NSObject <GPPSignInDelegate,GPPShareDelegate,GPPNativeShareBuilder>
{
    BOOL isFirstCall;
}


+(TAGooglePlus *) goolePlus;
-(void)callShareLinkOnGooglePlus:(NSString *)pretext andlink:(NSURL *)link completionHandler:(GooglePlusCompleteHandler)completionHandler;

-(void)callShareImageOnGooglePlus:(NSString *)pretext andimagename:(NSString *)imagename isbundle:(BOOL)isBundle completionHandler:(GooglePlusCompleteHandler)completionHandler;
-(void)callShareVideoOnGooglePlus:(NSString *)pretext videoName:(NSString *)videoName extension:(NSString *)Extension completionHandler:(GooglePlusCompleteHandler)completionHandler;

-(void)callSignInGooglePlus:(NSString *)clientID completionHandler:(GooglePlusCompletionHandlerForLogin)completionHandlerForLogin;

-(void)callSignOut:(GooglePlusCompleteHandler)completionHandler;






@end
