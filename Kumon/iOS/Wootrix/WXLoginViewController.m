//
//  WXLoginViewController.m
//  Wootrix
//
//  Created by Mayank Pahuja on 12/12/14.
//  Copyright (c) 2014 Techahead. All rights reserved.
//

#import "WXLoginViewController.h"
#import "WXLoginRequest.h"
#import "WXLoginModel.h"
#import "TAGooglePlus.h"
#import "HCGoogleWebControllerViewController.h"
#import "WXSignupViewController.h"
#import "WXLandingScreenViewController.h"
#import "WXGetUserSelected.h"
#import <FBSDKCoreKit/FBSDKCoreKit.h>
#import <FBSDKLoginKit/FBSDKLoginKit.h>
#import <TwitterKit/TwitterKit.h>
#import "MSAL.h"


#define kFaceBookAppID  @"906837972662201"

//#define kFaceBookAppID  @"1687834118193776"
//#define kGoogleClientId @"410271304403-i4e4mo8kj94mh9rg8trc6n6a61h58rda.apps.googleusercontent.com"
//#define kGoogleClientId @"181469443324-eql5au35e3uku3f2kuqa8jdr3kinpcis.apps.googleusercontent.com"
#define kGoogleClientId @"568008585838-59em7i5n95vvcgir6h8eamns3d3b8fnt.apps.googleusercontent.com"

@interface WXLoginViewController ()
{
    NSTimer *myTimer;
    MSALPublicClientApplication *_application;
}

@end

@interface WXLoginViewController (WebserviceMethods)


- (void)callLoginWebserviceWithparams:(NSDictionary*)params;
- (void)callForgotPasswordServiceWithEmail:(NSString*)email;
- (void)callGetUserSelectedWebserviceWithparams;

@end

@implementation WXLoginViewController

- (void)viewDidLoad
{
    [super viewDidLoad];
    
    NSLog(@"login - view didload begin");
    
    NSDate *loggedDate = [UserDefaults objectForKey:@"lastLogged"];
    
    NSCalendar *calendar = [[NSCalendar alloc] initWithCalendarIdentifier:NSCalendarIdentifierGregorian];
    NSDateComponents *date = [calendar components:NSCalendarUnitDay | NSCalendarUnitMonth | NSCalendarUnitYear fromDate:loggedDate];
    NSDateComponents *current = [calendar components:NSCalendarUnitDay | NSCalendarUnitMonth | NSCalendarUnitYear fromDate:[NSDate date]];
    
    if (loggedDate == nil || current.month > date.month || current.year > date.year)
    {
        [UserDefaults setBool:NO forKey:kIsLoggedin];
        [UserDefaults synchronize];
        [UserDefaults removeObjectForKey:@"lastLogged"];
    }
    
    if ([UserDefaults boolForKey:kIsLoggedin])
    {
        
        WXLandingScreenViewController *landingScreen = [self.storyboard instantiateViewControllerWithIdentifier:@"WXLandingScreenViewController"];
        [self.navigationController pushViewController:landingScreen animated:NO];
    }
    
    loginQueue = dispatch_queue_create(class_getName([WXLoginViewController class]), NULL);
    loginQueueTag = &loginQueue;
    dispatch_queue_set_specific(loginQueue, loginQueueTag, loginQueueTag, NULL);
    // Do any additional setup after loading the view.
    
    NSLog(@"login - view didload end");
    
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (void)viewDidAppear:(BOOL)animated
{
    NSLog(@"login - viewwill appear");
    [[NSNotificationCenter defaultCenter]postNotificationName:@"tokenRefreshNotification"object:self];
    
    [self setFontsAndColors];
}


#pragma mark - web services

/**
 *  This service is called after tapping the login button
 *
 *  @param dictLoginData contains all login information
 */
- (void)callServiceLoginWithParams:(NSDictionary*)dictLogin
{
    
}

/**
 *  This service is called on tapping the forgot password button
 *
 *  @param dictForgotPassword contains the information required to get a new password
 */
- (void)callServiceForgotPassword:(NSDictionary*)dictForgotPassword
{
    
}

#pragma mark - other methods
/**
 *  This method is called to set the custom font sizes and colors of all objects present on the view
 */
- (void)setFontsAndColors
{
    NSLog(@"setFontsAndColors begin");
    
    [_lblLoginWith setText:[WXLoginViewController languageSelectedStringForKey:@"Log in with"]];
    
    [_txtFieldEmail setText:@""];
    [_txtFieldPassword setText:@""];
    
    NSDictionary *linkAttributes = @{@(kCTUnderlineStyleSingle):(id)kCTUnderlineStyleAttributeName};
    NSLog(@"linkAttributes begin");
    _lblDontHaveAnAccount.linkAttributes = linkAttributes;
    _lblDontHaveAnAccount.delegate = self;
    _lblDontHaveAnAccount.numberOfLines = 0;
    NSLog(@"linkAttributes end");
    
    NSDictionary *attributesFontColor = @{NSFontAttributeName: [UIFont fontWithName:@"HelveticaNeue" size:14],NSForegroundColorAttributeName:[UIColor whiteColor]};
    NSDictionary *attributesBold = @{NSFontAttributeName: [UIFont fontWithName:@"HelveticaNeue-Bold" size:14],NSForegroundColorAttributeName:[UIColor whiteColor]};
    
    
    NSString *message = [NSString stringWithFormat:@"%@",[WXLoginViewController languageSelectedStringForKey:@"Don't have an account yet? Sign up here"]];
    
    NSMutableAttributedString *attributedMessage = [[NSMutableAttributedString alloc] initWithString:message attributes:nil];
    [attributedMessage addAttributes:attributesFontColor range:[message rangeOfString:message]];
    [attributedMessage addAttributes:attributesBold range:[message rangeOfString:[WXLoginViewController languageSelectedStringForKey:@"Sign up"]]];
    NSLog(@"Sign up");
    [attributedMessage addAttributes:attributesBold range:[message rangeOfString:[WXLoginViewController languageSelectedStringForKey:@"Cadastre-se"]]];
    NSLog(@"Cadastre-se");
    [attributedMessage addAttributes:attributesBold range:[message rangeOfString:[WXLoginViewController languageSelectedStringForKey:@"Regístrate"]]];
    NSLog(@"Cadastre-se");
    NSLog(@"attributed string over");
    
    myTimer = [NSTimer scheduledTimerWithTimeInterval:2.0
                                               target:self
                                             selector:@selector(targetMethod:)
                                             userInfo:nil
                                              repeats:YES];
    _lblDontHaveAnAccount .text = attributedMessage;
    [_lblDontHaveAnAccount setTextAlignment:NSTextAlignmentCenter];
    
    NSLog(@"linkAttributes begin1");
    NSDictionary *courseDict = @{@"event": @"1"};
    [_lblDontHaveAnAccount addLinkToTransitInformation:courseDict withRange:[message rangeOfString:@"Sign up"]];
    [_lblDontHaveAnAccount addLinkToTransitInformation:courseDict withRange:[message rangeOfString:@"Cadastre-se"]];
    [_lblDontHaveAnAccount addLinkToTransitInformation:courseDict withRange:[message rangeOfString:@"Regístrate"]];
    NSLog(@"linkAttributes end1");
    [_btnLogin setTitle:[WXLoginViewController languageSelectedStringForKey:@"Log in"] forState:UIControlStateNormal];
    [_btnLoginCollaborator setTitle:[WXLoginViewController languageSelectedStringForKey:@"Log in Col"] forState:UIControlStateNormal];
    
    NSMutableAttributedString *attriutedPasswordPlaceholder = [[NSMutableAttributedString alloc] initWithString:[WXLoginViewController languageSelectedStringForKey:@"Password"] attributes:@{NSForegroundColorAttributeName:[UIColor whiteColor]}];
    [_txtFieldPassword setAttributedPlaceholder:attriutedPasswordPlaceholder];
    
    
    NSMutableAttributedString *attriutedEmailPlaceholder = [[NSMutableAttributedString alloc] initWithString:[WXLoginViewController languageSelectedStringForKey:@"Email"] attributes:@{NSForegroundColorAttributeName:[UIColor whiteColor]}];
    [_txtFieldEmail setAttributedPlaceholder:attriutedEmailPlaceholder];
    
    NSLog(@"setFontsAndColors end");
    
    //  [_lblDontHaveAnAccount ];
}

-(void)targetMethod:(NSTimer*)timer
{
    [[NSNotificationCenter defaultCenter]postNotificationName:@"tokenRefreshNotification" object:nil];
    
    NSString *fcmDeviceToken;
    [self showProgressHUD:[WXLoginViewController languageSelectedStringForKey:@"Loading..."]];
    [self.view setUserInteractionEnabled:NO];
    NSString * str = [UserDefaults objectForKey:kFirebaseId];
    NSLog(@"string%@",str);
    if([UserDefaults objectForKey:kFirebaseId] != nil)
    {
        [self dismissProgressHUD];
        [self.view setUserInteractionEnabled:YES];
        [myTimer invalidate];
        myTimer = nil;
    }
    if ([UserDefaults objectForKey:kFirebaseId] != nil &&![[UserDefaults objectForKey:kFirebaseId] isEqual:@"fqASoRlz1hQ:APA91bGb_exXTruv7AtArZA16sfdYcmpPzZJRqlDlKHtDYXn5qGEhYfscRAen7IgbWrD8Xvw7eKCoz3e8Pvl28BFq9jUzHsi849V4z6XMeAaLk7JJQ1funoJmc0PGNSj89fJo0PlktW1"]){
        
        if ([UserDefaults boolForKey:kIsLoggedin])
        {
            [self dismissProgressHUD];
            [self.view setUserInteractionEnabled:YES];
            //    NSDictionary *dictSendParams = @{@"email" :[UserDefaults objectForKey:@"UserName"],
            //                                     @"password"           :[UserDefaults objectForKey:@"Password"],
            //                                     @"device"             :@"iPhone",
            //                                     @"osversion"          :@"8.1",
            //                                     @"socialaccounttype"  :@"",
            //                                     @"socialaccountid"    :@"",
            //                                     @"socialaccounttoken" :@"",
            //                                     @"applanguage"        :@"en",
            //                                     @"name"               :@"",
            //                                     @"photourl"           :@"",
            //                                     @"latitude"            :[UserDefaults objectForKey:kLatitude],
            //                                     @"longitude"         :[UserDefaults objectForKey:kLongitude],
            //                                     @"deviceId"          :fcmDeviceToken
            //                                     };
            //[self callLoginWebServiceToLoadFcmToken:dictSendParams];
            [myTimer invalidate];
            myTimer = nil;
            
        }
    }
}
/**
 *  Takes in parameters for Login and returns YES or NO based on success.
 *
 *  @param params takes dictionary/array as per request. Login can be through email,facebook,twitter,linkedIn or Googleplus
 *
 *  @return YES/NO - based on success and failure
 */
- (BOOL)loginWithParameters:(id)params
{
    return YES;
}


/**
 *  This method checks if the entered values in the text fields are valid or not.
 *
 *  @return a bool value based on validity
 */

- (BOOL)hasValidValues
{
    _msgString = nil;
    
    if(_txtFieldPassword.text.length == 0 || _txtFieldEmail.text.length == 0)
    {
        _msgString = @"Don't forget to fill all the required fields";
        return NO;
    }
    else if (![WXLoginViewController NSStringIsValidEmail:_txtFieldEmail.text])
    {
        _msgString = [WXLoginViewController languageSelectedStringForKey:@"Invalid email address"];
        return NO;
    }
    else if(_txtFieldPassword.text.length < 5 )
    {
        _msgString = [WXLoginViewController languageSelectedStringForKey:@"Password should be atleast 6 character long."];
        return NO;
    }
    
    return YES;
}
#pragma mark - validatation


/**
 *  checks if the string passed is a valid email id or not
 *
 *  @param checkString string containing an email id
 *
 *  @return returns YES if the string passed is a valid email id sting
 */

+(BOOL) NSStringIsValidEmail:(NSString *)checkString
{
    BOOL stricterFilter = YES;
    NSString *stricterFilterString = @"[A-Z0-9a-z\\._%+-]+@([A-Za-z0-9-]+\\.)+[A-Za-z]{2,4}";
    NSString *laxString = @".+@([A-Za-z0-9]+\\.)+[A-Za-z]{2}[A-Za-z]*";
    NSString *emailRegex = stricterFilter ? stricterFilterString : laxString;
    NSPredicate *emailTest = [NSPredicate predicateWithFormat:@"SELF MATCHES %@", emailRegex];
    return [emailTest evaluateWithObject:checkString];
}

#pragma mark - textfield delegates

- (void)textFieldDidBeginEditing:(UITextField *)textField
{
    
}
- (void)textFieldDidEndEditing:(UITextField *)textField
{
    [textField resignFirstResponder];
}
- (BOOL)textFieldShouldReturn:(UITextField *)textField
{
    [textField resignFirstResponder];
    return YES;
}


#pragma mark - TTTdelegates

- (void)attributedLabel:(TTTAttributedLabel *)label didSelectLinkWithTextCheckingResult:(NSTextCheckingResult *)result
{
    WXSignupViewController *signUpViewC = [self.storyboard instantiateViewControllerWithIdentifier:@"WXSignupViewController"];
    [self.navigationController pushViewController:signUpViewC animated:YES];
}

#pragma mark - google
- (void)registerNewUserForGoogle:(GoogleCompletionHandler)completionHandler
{
    //[Utils startActivityIndicatorInView:SharedAppDelegate.window withMessage:kLoading];
    NSString *fcmDeviceToken;
    
    if ([UserDefaults objectForKey:kFirebaseId] == nil){
        fcmDeviceToken = @"fqASoRlz1hQ:APA91bGb_exXTruv7AtArZA16sfdYcmpPzZJRqlDlKHtDYXn5qGEhYfscRAen7IgbWrD8Xvw7eKCoz3e8Pvl28BFq9jUzHsi849V4z6XMeAaLk7JJQ1funoJmc0PGNSj89fJo0PlktW1";
    }else{
        fcmDeviceToken = [UserDefaults objectForKey:kFirebaseId];
        [myTimer invalidate];
        myTimer = nil;
    }
    
    //[[NSNotificationCenter defaultCenter] addObserver:self
    //                                             selector:@selector(callGoogleLoginPromptController:)
    //                                                 name:kApplicationOpenGoogleAuthNotification
    //                                               object:nil];
    
    
    // [[GPPSignIn sharedInstance] trySilentAuthentication];
    
    [[TAGooglePlus goolePlus] callSignInGooglePlus:kGoogleClientId completionHandler:^(BOOL isSignIn, GPPSignIn *signIn)
     {
         
         if(isSignIn)
         {
             GTLPlusPerson *person = [GPPSignIn sharedInstance].googlePlusUser;
             [self dismissProgressHUD];
             [self.view setUserInteractionEnabled:YES];
             
             
             NSDictionary *dictSendParams = @{@"email"              :signIn.userEmail,
                                              @"password"           :@"",
                                              @"device"             :@"iPhone",
                                              @"osversion"          :[[UIDevice currentDevice] systemVersion],
                                              @"socialaccounttype"  :@"gPlus",
                                              @"socialaccountid"    :[signIn userID],
                                              @"socialaccounttoken" :@"",
                                              @"applanguage"        :@"en",
                                              @"name"               :person.name.familyName,
                                              @"photourl"           :person.image.url
                                              ,@"latitude"            :[UserDefaults objectForKey:kLatitude],
                                              @"longitude"         :[UserDefaults objectForKey:kLongitude],
                                              @"deviceId"          :fcmDeviceToken};
             
             
             [self callLoginWebserviceWithparams:dictSendParams];
             
             //       NSDictionary *dictProfile = [[NSDictionary alloc] initWithObjectsAndKeys:NULLVALUE(person.aboutMe),kAboutMe,NULLVALUE(person.birthday),kBirthday,NULLVALUE(person.currentLocation),kLocation,NULLVALUE(person.name.givenName),kFirstName,NULLVALUE(person.name.familyName),kLastName,NULLVALUE(person.gender),kGender,NULLVALUE(person.image.url),kPicture,NULLVALUE(person.cover.coverPhoto.url),kCoverPhoto,NULLVALUE(signIn.userEmail),kEmail,[signIn userID],kUser_id, nil];
             
             //       completionHandler(dictProfile);
             
         }
         else
         {
             [[GPPSignIn sharedInstance] signOut];
         }
     }];
}

- (void)callGoogleLoginPromptController: (NSNotification *)notif
{
    
    
    HCGoogleWebControllerViewController *loginGoogle = [[HCGoogleWebControllerViewController alloc] init];
    loginGoogle.url = [NSURL URLWithString:[NSString stringWithFormat:@"%@",notif.object]];
    
    [self.navigationController presentViewController:loginGoogle animated:YES completion:nil];
    
}



#pragma mark - facebook/twitter

/**
 *  This methos is used to check if user is logged in on settings menu facebook
 *
 *  @param block
 */
- (void)isFacebookConfigured:(void(^)(ACAccount *account))block
{
    self.facebookACAccountStore = [[ACAccountStore alloc] init];
    NSArray *permissionsArray = [[NSArray alloc] initWithObjects:
                                 // @"read_stream",
                                 @"user_birthday",
                                 @"email",
                                 nil];
    
    ACAccountType *facebookTypeAccount = [self.facebookACAccountStore accountTypeWithAccountTypeIdentifier:ACAccountTypeIdentifierFacebook];
    
    [self.facebookACAccountStore requestAccessToAccountsWithType:facebookTypeAccount
                                                         options:@{ACFacebookAppIdKey: kFaceBookAppID, ACFacebookPermissionsKey:permissionsArray,ACFacebookAudienceKey : ACFacebookAudienceEveryone}
                                                      completion:^(BOOL granted, NSError *error)
     {
         
         dispatch_async(dispatch_get_main_queue(), ^
                        {
                            if(granted)
                            {
                                NSArray *accounts = [self.facebookACAccountStore accountsWithAccountType:facebookTypeAccount];
                                if(accounts.count==0)
                                {
                                    block(nil);
                                }
                                else
                                {
                                    ACAccount *faceBookAccount=[accounts objectAtIndex:0];
                                    block(faceBookAccount);
                                }
                            }
                            else
                            {
                                block(nil);
                            }
                            
                        });
     }];
}

/**
 *  Called to login with facebook. Checks if the user is logged in the settings menu then it logs him in otherwise uses facebook SDK for logging in
 *
 *  @param block completeHandler Returns nsdictionary of desired values
 */
- (void)loginWithFacebookWithCompletion:(void(^)(NSDictionary *response,BOOL isCancelled))block
{
    //    [self isFacebookConfigured:^(ACAccount *account)
    //     {
    //         if (account)
    //         {
    //             ACAccount *faceBookAccount = account;
    //
    //
    //             NSDictionary *params = @{@"fields": @"id,email,picture.width(640),gender,work,first_name,last_name,location"};
    //             NSURL *meurl = [NSURL URLWithString:@"https://graph.facebook.com/me"];
    //
    //             SLRequest *merequest = [SLRequest requestForServiceType:SLServiceTypeFacebook
    //                                                       requestMethod:SLRequestMethodGET
    //                                                                 URL:meurl
    //                                                          parameters:params];
    //             merequest.account = faceBookAccount;
    //
    //             [merequest performRequestWithHandler:^(NSData *responseData, NSHTTPURLResponse *urlResponse, NSError *error)
    //              {
    //                  NSError* e;
    //                  NSDictionary* json = [NSJSONSerialization JSONObjectWithData:responseData
    //                                                                       options:kNilOptions
    //                                                                         error:&e];
    //                  block(json,NO);
    //              }];
    //         }
    //         else
    //         {
    //
    //             NSArray *permissionsArray = [[NSArray alloc] initWithObjects:
    //                                          @"read_stream",
    //                                          @"user_birthday",
    //                                          @"email",
    //                                          nil];
    //             NSString *params = @"id,email,picture.width(640),gender,work,first_name,last_name,location";
    //             [TAFacebookHelper fetchPersonalInfoWithParams:params withPermissions:permissionsArray completionHandler:^(id response, NSError *e) {
    //
    //                 if (response)
    //                 {
    //                     block(response, NO);
    //                 }
    //                 else
    //                 {
    //                     if ([[e localizedFailureReason] isEqualToString:@"com.facebook.sdk:UserLoginCancelled"])
    //                     {
    //                         block(nil,YES);
    //                     }
    //                     else
    //                     {
    //                         block(response, NO);
    //                     }
    //                 }
    //             }];
    //         }
    //     }];
}

/**
 *  Called to fetch data from twitter account in settings menu
 *
 *  @param completeHandler Returns nsdictionary of desired values
 */
-(void)loginWithTwitter:(void(^)(NSDictionary *response))completeHandler
{
    
    self.twitterACAccountStore = [[ACAccountStore alloc] init];
    
    ACAccountType *twitterTypeAccount = [self.twitterACAccountStore accountTypeWithAccountTypeIdentifier:ACAccountTypeIdentifierTwitter];
    
    [self.twitterACAccountStore requestAccessToAccountsWithType:twitterTypeAccount
                                                        options:nil
                                                     completion:^(BOOL granted, NSError *error)
     {
         if(granted)
         {
             NSArray *accounts = [self.twitterACAccountStore accountsWithAccountType:twitterTypeAccount];
             if(accounts.count==0)
             {
                 dispatch_async(dispatch_get_main_queue(), ^
                                {
                                    completeHandler(nil);
                                });
                 
                 return ;
             }
             ACAccount *twitterAccount=[accounts objectAtIndex:0];
             
             //NSURL *meurl = [NSURL URLWithString:@"http://api.twitter.com/1.1/users/show.json"];
             NSURL *meurl = [NSURL URLWithString:@"https://api.twitter.com/1/account/verify_credentials.json"];
             
             //NSDictionary *params = [NSDictionary dictionaryWithObjectsAndKeys:@"",@"screen_name",nil];
             
             SLRequest *merequest = [SLRequest requestForServiceType:SLServiceTypeTwitter
                                                       requestMethod:SLRequestMethodGET
                                                                 URL:meurl
                                                          parameters:nil];
             
             merequest.account = twitterAccount;
             
             [merequest performRequestWithHandler:^(NSData *responseData, NSHTTPURLResponse *urlResponse, NSError *error)
              {
                  //NSString *meDataString = [[NSString alloc] initWithData:responseData encoding:NSUTF8StringEncoding];
                  
                  //NSLog(@"%@", meDataString);
                  dispatch_async(dispatch_get_main_queue(), ^
                                 {
                                     if(error)
                                     {
                                         completeHandler(nil);
                                         
                                     }
                                     else
                                     {
                                         NSDictionary *jsonDict=[NSJSONSerialization JSONObjectWithData:responseData options:0 error:nil];
                                         completeHandler(jsonDict);
                                         
                                     }
                                     
                                 });
              }];
         }else{
             // ouch
             dispatch_async(dispatch_get_main_queue(), ^{
                 completeHandler(nil);
                 
             });
         }
     }];
    
}


#pragma mark - button actions

#define CLIENT_ID_ADVISOR @"35532026-3245-4de4-9a43-255a3b2632e9"
#define AUTHORITY_ADVISOR @"https://login.microsoftonline.com/c27f427d-7da5-4e40-97dc-8fde809e0c29"
#define CLIENT_ID_COLABORATOR @"5904648b-0ceb-46cd-ba1f-5ee6d91bf5be"
#define AUTHORITY_COLABORATOR @"https://login.microsoftonline.com/65b7f243-3785-438b-a6f5-ebecebfae111"


#define GRAPH_URI @"https://graph.microsoft.com/v1.0/me/"
#define SCOPES  @[@"https://graph.microsoft.com/user.read"]


/**
 *  Called on Tapping the Login button
 *
 *  @param sender object of tapped button
 */
- (IBAction)btnLoginCollabTapped:(UIButton*)sender
{
    NSString *fcmDeviceToken;
    
    if ([UserDefaults objectForKey:kFirebaseId] == nil)
        fcmDeviceToken = @"fqASoRlz1hQ:APA91bGb_exXTruv7AtArZA16sfdYcmpPzZJRqlDlKHtDYXn5qGEhYfscRAen7IgbWrD8Xvw7eKCoz3e8Pvl28BFq9jUzHsi849V4z6XMeAaLk7JJQ1funoJmc0PGNSj89fJo0PlktW1";
    else
    {
        fcmDeviceToken = [UserDefaults objectForKey:kFirebaseId];
        [myTimer invalidate];
        myTimer = nil;
    }
    
    //[self showProgressHUD:[WXLoginViewController languageSelectedStringForKey:@"Loading..."]];
    
    NSError *error = nil;
    _application = [[MSALPublicClientApplication alloc] initWithClientId:CLIENT_ID_COLABORATOR authority:AUTHORITY_COLABORATOR error:&error];
    
    [_application acquireTokenForScopes:SCOPES
                        completionBlock:^(MSALResult *result, NSError *error)
     {
         //[self dismissProgressHUD];
         
         if (!error)
         {
             // You'll want to get the user identifier to retrieve and reuse the user
             // for later acquireToken calls
             //             NSString *userIdentifier = result.user.userIdentifier;
             //
             //             NSString *accessToken = result.accessToken;
             dispatch_async(dispatch_get_main_queue(), ^{
                 NSDictionary *dictSendParams = @{@"email" :result.account.username,
                                                  @"password"           :@"",
                                                  @"device"             :@"iPhone",
                                                  @"osversion"          :@"8.1",
                                                  @"socialaccounttype"  :@"",
                                                  @"socialaccountid"    :@"",
                                                  @"socialaccounttoken" :@"",
                                                  @"applanguage"        :@"en",
                                                  @"name"               :@"",
                                                  @"photourl"           :@"",
                                                  @"latitude"            :[UserDefaults objectForKey:kLatitude],
                                                  @"longitude"         :[UserDefaults objectForKey:kLongitude],
                                                  @"deviceId"          :fcmDeviceToken
                                                  };
                 
                 [self callLoginWebserviceWithparams:dictSendParams];
             });
         }
         else
         {
             // Check the error
             dispatch_async(dispatch_get_main_queue(), ^{
                 UIAlertController * alertcontroller = [UIAlertController alertControllerWithTitle:kAPPName message:_msgString preferredStyle:UIAlertControllerStyleAlert];
                 UIAlertAction *ok = [UIAlertAction actionWithTitle:[WXLoginViewController languageSelectedStringForKey:@"Ok"] style:UIAlertActionStyleDefault handler:nil];
                 [alertcontroller addAction:ok];
                 [self presentViewController:alertcontroller animated:NO completion:nil];
             });
         }
     }];
    
    
    //        [UserDefaults setObject:_txtFieldEmail.text forKey:@"UserName"];
    //        [UserDefaults setObject:_txtFieldPassword.text forKey:@"Password"];
    //        [UserDefaults synchronize];
}


- (IBAction)btnLoginTapped:(UIButton*)sender
{
    NSString *fcmDeviceToken;
    
    if ([UserDefaults objectForKey:kFirebaseId] == nil)
        fcmDeviceToken = @"fqASoRlz1hQ:APA91bGb_exXTruv7AtArZA16sfdYcmpPzZJRqlDlKHtDYXn5qGEhYfscRAen7IgbWrD8Xvw7eKCoz3e8Pvl28BFq9jUzHsi849V4z6XMeAaLk7JJQ1funoJmc0PGNSj89fJo0PlktW1";
    else
    {
        fcmDeviceToken = [UserDefaults objectForKey:kFirebaseId];
        [myTimer invalidate];
        myTimer = nil;
    }
    
    //[self showProgressHUD:[WXLoginViewController languageSelectedStringForKey:@"Loading..."]];
    
    NSError *error = nil;
    _application = [[MSALPublicClientApplication alloc] initWithClientId:CLIENT_ID_ADVISOR authority:AUTHORITY_ADVISOR error:&error];
    
    [_application acquireTokenForScopes:SCOPES
                        completionBlock:^(MSALResult *result, NSError *error)
     {
         //[self dismissProgressHUD];
         
         if (!error)
         {
             // You'll want to get the user identifier to retrieve and reuse the user
             // for later acquireToken calls
             //             NSString *userIdentifier = result.user.userIdentifier;
             //
             //             NSString *accessToken = result.accessToken;
             dispatch_async(dispatch_get_main_queue(), ^{
                 NSDictionary *dictSendParams = @{@"email" :result.account.username,
                                                  @"password"           :@"",
                                                  @"device"             :@"iPhone",
                                                  @"osversion"          :@"8.1",
                                                  @"socialaccounttype"  :@"",
                                                  @"socialaccountid"    :@"",
                                                  @"socialaccounttoken" :@"",
                                                  @"applanguage"        :@"en",
                                                  @"name"               :@"",
                                                  @"photourl"           :@"",
                                                  @"latitude"            :[UserDefaults objectForKey:kLatitude],
                                                  @"longitude"         :[UserDefaults objectForKey:kLongitude],
                                                  @"deviceId"          :fcmDeviceToken
                                                  };
                 
                 [self callLoginWebserviceWithparams:dictSendParams];
             });
         }
         else
         {
             // Check the error
             dispatch_async(dispatch_get_main_queue(), ^{
                 UIAlertController * alertcontroller = [UIAlertController alertControllerWithTitle:kAPPName message:_msgString preferredStyle:UIAlertControllerStyleAlert];
                 UIAlertAction *ok = [UIAlertAction actionWithTitle:[WXLoginViewController languageSelectedStringForKey:@"Ok"] style:UIAlertActionStyleDefault handler:nil];
                 [alertcontroller addAction:ok];
                 [self presentViewController:alertcontroller animated:NO completion:nil];
             });
         }
     }];
    
    
    //        [UserDefaults setObject:_txtFieldEmail.text forKey:@"UserName"];
    //        [UserDefaults setObject:_txtFieldPassword.text forKey:@"Password"];
    //        [UserDefaults synchronize];
    
    
    
    
    
    
}

- (IBAction)btnSignupTapped:(UIButton*)sender
{
    
}

- (IBAction)btnFacebookTapped:(UIButton*)sender
{
    //  [self executeBlock:^{
    NSString *fcmDeviceToken;
    if ([UserDefaults objectForKey:kFirebaseId] == nil){
        fcmDeviceToken = @"fqASoRlz1hQ:APA91bGb_exXTruv7AtArZA16sfdYcmpPzZJRqlDlKHtDYXn5qGEhYfscRAen7IgbWrD8Xvw7eKCoz3e8Pvl28BFq9jUzHsi849V4z6XMeAaLk7JJQ1funoJmc0PGNSj89fJo0PlktW1";
    }else{
        fcmDeviceToken = [UserDefaults objectForKey:kFirebaseId];
        [myTimer invalidate];
        myTimer = nil;
    }
    
    //[self showProgressHUD:[WXLoginViewController languageSelectedStringForKey:@"Loading..."]];
    //[self.view setUserInteractionEnabled:NO];
    //    [self loginWithFacebookWithCompletion:^(NSDictionary *response, BOOL isCancelled)
    //     {
    //         [self dismissProgressHUD];
    //          [self.view setUserInteractionEnabled:YES];
    //         if (!isCancelled)
    //         {
    //
    //             NSLog(@"response:%@",response);
    //
    //             NSDictionary *dictSendParams = @{@"email"              :[response objectForKey:@"email"],
    //                                              @"password"           :@"",
    //                                              @"device"             :@"iPhone",
    //                                              @"osversion"          :[[UIDevice currentDevice] systemVersion],
    //                                              @"socialaccounttype"  :@"fb",
    //                                              @"socialaccountid"    :[response objectForKey:@"id"],
    //                                              @"socialaccounttoken" :@"",
    //                                              @"applanguage"        :@"en",
    //                                              @"name"               :[response objectForKey:@"first_name"],
    //                                              @"photourl"           :[[[response objectForKey:@"picture"]
    //                                                                       objectForKey:@"data"] objectForKey:@"url"],
    //                                              @"latitude"            :[UserDefaults objectForKey:kLatitude],
    //                                              @"longitude"         :[UserDefaults objectForKey:kLongitude],
    //                                              @"deviceId"          :fcmDeviceToken
    //                                              };
    //
    //
    //             [self callLoginWebserviceWithparams:dictSendParams];
    //         }
    //     }];
    //  }];
    
    FBSDKLoginManager *login = [[FBSDKLoginManager alloc] init];
    
    [login logOut];
    
    [login logInWithReadPermissions: @[@"email", @"public_profile"] fromViewController:self handler:^(FBSDKLoginManagerLoginResult *result, NSError *error)
     {
         //         [self.view ShowWaitingProgress];
         
         if (error)
         {
             //             [self.view DismissWaitingProgress];
             NSLog(@"Process error:%@, %@", error.localizedDescription, error.localizedFailureReason);
             [self.view ShowToast:@"Não foi possível autenticar-se com o Facebook."];
         }
         else if (result.isCancelled)
         {
             //             [self.view DismissWaitingProgress];
             NSLog(@"Cancelled");
             NSLog(@"%@", result);
         }
         else
         {
             [[[FBSDKGraphRequest alloc] initWithGraphPath:@"me" parameters:@{@"fields": @"id, name, email, picture"}]
              startWithCompletionHandler:^(FBSDKGraphRequestConnection *connection, id result, NSError *error)
              {
                  if (error)
                  {
                      [self.view DismissWaitingProgress];
                      NSLog(@"Process error:%@, %@", error.localizedDescription, error.localizedFailureReason);
                      [self.view ShowToast:@"Não foi possível autenticar-se com o Facebook."];
                  }
                  else
                  {
                      NSLog(@"%@", result);
                      
                      NSDictionary *dictSendParams = @{@"email"              :[result objectForKey:@"email"] == nil ? @"" : [result objectForKey:@"email"],
                                                       @"password"           :@"",
                                                       @"device"             :@"iPhone",
                                                       @"osversion"          :[[UIDevice currentDevice] systemVersion],
                                                       @"socialaccounttype"  :@"fb",
                                                       @"socialaccountid"    :[result objectForKey:@"id"],
                                                       @"socialaccounttoken" :@"",
                                                       @"applanguage"        :@"en",
                                                       @"name"               :[result objectForKey:@"name"],
                                                       @"photourl"           :[[[result objectForKey:@"picture"]
                                                                                objectForKey:@"data"] objectForKey:@"url"],
                                                       @"latitude"            :[UserDefaults objectForKey:kLatitude],
                                                       @"longitude"         :[UserDefaults objectForKey:kLongitude],
                                                       @"deviceId"          :fcmDeviceToken
                                                       };
                      
                      [self callLoginWebserviceWithparams:dictSendParams];
                  }
              }];
             
         }
     }];
}

- (IBAction)btnTwitterTapped:(UIButton*)sender
{
    NSString *fcmDeviceToken;
    if ([UserDefaults objectForKey:kFirebaseId] == nil){
        fcmDeviceToken = @"fqASoRlz1hQ:APA91bGb_exXTruv7AtArZA16sfdYcmpPzZJRqlDlKHtDYXn5qGEhYfscRAen7IgbWrD8Xvw7eKCoz3e8Pvl28BFq9jUzHsi849V4z6XMeAaLk7JJQ1funoJmc0PGNSj89fJo0PlktW1";
    }else{
        fcmDeviceToken = [UserDefaults objectForKey:kFirebaseId];
        [myTimer invalidate];
        myTimer = nil;
    }
    
    //    [self showProgressHUD:[WXLoginViewController languageSelectedStringForKey:@"Loading..."]];
    [self.view setUserInteractionEnabled:NO];
    
    [[Twitter sharedInstance] logInWithCompletion:^(TWTRSession *session, NSError *error)
     {
         //        [self dismissProgressHUD];
         [self.view setUserInteractionEnabled:YES];
         
         if (session)
         {
             TWTRAPIClient *client = [TWTRAPIClient clientWithCurrentUser];
             
             [client loadUserWithID:session.userID completion:^(TWTRUser *user, NSError *err)
              {
                  if (!err)
                  {
                      NSDictionary *dictSendParams = @{@"email"              :user.formattedScreenName,
                                                       @"password"           :@"",
                                                       @"device"             :@"iPhone",
                                                       @"osversion"          :[[UIDevice currentDevice] systemVersion],
                                                       @"socialaccounttype"  :@"tw",
                                                       @"socialaccountid"    :user.userID,
                                                       @"socialaccounttoken" :@"",
                                                       @"applanguage"        :@"en",
                                                       @"name"               :user.screenName,
                                                       @"photourl"           :user.profileImageURL,
                                                       @"latitude"           :[UserDefaults objectForKey:kLatitude],
                                                       @"longitude"          :[UserDefaults objectForKey:kLongitude],
                                                       @"deviceId"           :fcmDeviceToken
                                                       };
                      [self callLoginWebserviceWithparams:dictSendParams];
                  }
                  else
                  {
                      [[[UIAlertView alloc] initWithTitle:kAPPName message:[WXLoginViewController languageSelectedStringForKey:@"Twitter is not configured your device settings. Please go to device settings and configure twitter account first."] delegate:nil cancelButtonTitle:[WXLoginViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles:nil] show];
                  }
              }];
             
             
         } else {
             NSLog(@"error: %@", [error localizedDescription]);
         }
     }];
    
    
    
    //    [self loginWithTwitter:^(NSDictionary *response)
    //     {
    //
    //         [self dismissProgressHUD];
    //          [self.view setUserInteractionEnabled:YES];
    //         NSLog(@"response:%@",response);
    //
    //         if (response)
    //         {
    //             NSDictionary *dictSendParams = @{@"email"              :[response objectForKey:@"screen_name"],
    //                                              @"password"           :@"",
    //                                              @"device"             :@"iPhone",
    //                                              @"osversion"          :[[UIDevice currentDevice] systemVersion],
    //                                              @"socialaccounttype"  :@"tw",
    //                                              @"socialaccountid"    :[response objectForKey:@"id_str"],
    //                                              @"socialaccounttoken" :@"",
    //                                              @"applanguage"        :@"en",
    //                                              @"name"               :[response objectForKey:@"name"],
    //                                              @"photourl"           :[response objectForKey:@"profile_image_url"],
    //                                              @"latitude"           :[UserDefaults objectForKey:kLatitude],
    //                                              @"longitude"          :[UserDefaults objectForKey:kLongitude],
    //                                              @"deviceId"           :fcmDeviceToken
    //                                              };
    //             [self callLoginWebserviceWithparams:dictSendParams];
    //         }
    //         else
    //         {
    //             [[[UIAlertView alloc] initWithTitle:kAPPName message:[WXLoginViewController languageSelectedStringForKey:@"Twitter is not configured your device settings. Please go to device settings and configure twitter account first."] delegate:nil cancelButtonTitle:[WXLoginViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles:nil] show];
    //         }
    //     }];
}

- (IBAction)btnLinkedInTapped:(UIButton*)sender
{
    NSString *fcmDeviceToken;
    if ([UserDefaults objectForKey:kFirebaseId] == nil){
        fcmDeviceToken = @"fqASoRlz1hQ:APA91bGb_exXTruv7AtArZA16sfdYcmpPzZJRqlDlKHtDYXn5qGEhYfscRAen7IgbWrD8Xvw7eKCoz3e8Pvl28BFq9jUzHsi849V4z6XMeAaLk7JJQ1funoJmc0PGNSj89fJo0PlktW1";
    }else{
        fcmDeviceToken = [UserDefaults objectForKey:kFirebaseId];
        [myTimer invalidate];
        myTimer = nil;
    }
    
    NSString *fields = @"id,first-name,last-name,industry,picture-urls::(original),location:(name),positions:(company:(name),title),specialties,languages,email-address,last-modified-timestamp,proposal-comments,associations,interests,publications,patents,skills,certifications,educations,courses,volunteer,three-current-positions,three-past-positions,num-recommenders,recommendations-received,following,job-bookmarks,suggestions,date-of-birth,member-url-resources,related-profile-views,honors-awards";
    
    [[TALinkedInHelper sharedInstance] getLinkedInUserInfoForFields:fields completion:^(id response, NSError *error)
     {
         
         NSLog(@"res:%@",response);
         if (response)
         {
             
             NSString *picURL = @"";
             NSArray *arrPics = [[response objectForKey:@"pictureUrls"] objectForKey:@"values"];
             if ([arrPics isKindOfClass:[NSArray class]] && arrPics.count > 0) {
                 picURL = arrPics[0];
             }
             
             NSDictionary *dictSendParams = @{@"email"              :[response objectForKey:@"emailAddress"],
                                              @"password"           :@"",
                                              @"device"             :@"iPhone",
                                              @"osversion"          :[[UIDevice currentDevice] systemVersion],
                                              @"socialaccounttype"  :@"in",
                                              @"socialaccountid"    :[response objectForKey:@"id"],
                                              @"socialaccounttoken" :@"",
                                              @"applanguage"        :@"en",
                                              @"name"               :[response objectForKey:@"firstName"],
                                              @"photourl"           :picURL,
                                              @"latitude" : [UserDefaults objectForKey:kLatitude],
                                              @"longitude" : [UserDefaults objectForKey:kLongitude] ,
                                              @"deviceId" : fcmDeviceToken
                                              
                                              };
             [self callLoginWebserviceWithparams:dictSendParams];
         }
         
     }];
}

- (IBAction)btnGplusTapped:(UIButton*)sender
{
    [self registerNewUserForGoogle:^(BOOL isRegister, id responseReg)
     {
         
     }];
}

- (IBAction)btnForgotPasswordTapped:(UIButton*)sender
{
    [self.view endEditing:YES];
    UIAlertView *alertView = [[UIAlertView alloc] initWithTitle:kAPPName message:[WXLoginViewController languageSelectedStringForKey:@"Please enter your email"] delegate:self cancelButtonTitle:[WXLoginViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles:[WXLoginViewController languageSelectedStringForKey:@"Cancel"],nil];
    [alertView setAlertViewStyle:UIAlertViewStylePlainTextInput];
    alertView.tag = 101;
    UITextField *textField = [alertView textFieldAtIndex:0];
    [textField setKeyboardType:UIKeyboardTypeEmailAddress];
    textField.keyboardType = UIKeyboardTypeEmailAddress;
    [alertView show];
}



#pragma mark - PROTECTED METHODS

- (void)executeBlock:(dispatch_block_t)block
{
    // By design this method should not be invoked from the introQueue.
    //
    NSAssert(!dispatch_get_specific(loginQueueTag), @"Invoked on incorrect queue");
    
    dispatch_async(loginQueue, ^{ @autoreleasepool {
        
        block();
        
    }});
}

#pragma mark - alertview delegates

- (void)alertView:(UIAlertView *)alertView didDismissWithButtonIndex:(NSInteger)buttonIndex
{
    if (alertView.tag == 101)
    {
        UITextField *textField = [alertView textFieldAtIndex:0];
        
        if (buttonIndex == 0)
        {
            if (textField.text.length == 0)
            {
                UIAlertController *alertController = [UIAlertController alertControllerWithTitle:kAPPName message:@"Enter an email id" preferredStyle:UIAlertControllerStyleAlert];
                UIAlertAction *ok = [UIAlertAction actionWithTitle:[WXLoginViewController languageSelectedStringForKey:@"Ok"] style:UIAlertActionStyleDefault handler:nil];
                
                [alertController addAction:ok];
                [self presentViewController:alertController animated:NO completion:nil];
            }
            else if (![WXLoginViewController NSStringIsValidEmail:textField.text])
            {
                UIAlertController *alertController = [UIAlertController alertControllerWithTitle:kAPPName message:[WXLoginViewController languageSelectedStringForKey:@"Invalid email address"] preferredStyle:UIAlertControllerStyleAlert];
                
                UIAlertAction* ok = [UIAlertAction actionWithTitle:[WXLoginViewController languageSelectedStringForKey:@"Ok"] style:UIAlertActionStyleDefault handler:nil];
                [alertController addAction:ok];
                
                [self presentViewController:alertController animated:NO completion:nil];
                //                [[[UIAlertView alloc] initWithTitle:kAPPName message:[WXLoginViewController languageSelectedStringForKey:@"Invalid email address"] delegate:nil cancelButtonTitle:[WXLoginViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles:nil] show];
                //                [textField resignFirstResponder];
            }
            else
            {
                [self callForgotPasswordServiceWithEmail:textField.text];
            }
        }
        
    }
}


@end

#pragma mark - Web Service Category Methods

@implementation WXLoginViewController (WebserviceMethods)

- (void)callLoginWebserviceWithparams:(NSDictionary*)params
{
    
    if(![[NetworkManager manager] isConnectedToWiFi])
    {
        [[[UIAlertView alloc] initWithTitle:kAPPName message:[WXLoginViewController languageSelectedStringForKey:@"Network Not Available"] delegate:nil cancelButtonTitle:[WXLoginViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles: nil] show];
        return;
    }
    [self showProgressHUD:[WXLoginViewController languageSelectedStringForKey:@"Loading..."]];
    [self.view setUserInteractionEnabled:NO];
    WXLoginRequest *loginrequest= [[WXLoginRequest alloc] init];
    [loginrequest setEmail              :[params objectForKey:@"email"]];
    [loginrequest setPassword           :[params objectForKey:@"password"]];
    [loginrequest setDevice             :[params objectForKey:@"device"]];
    [loginrequest setOsVersion          :[params objectForKey:@"osversion"]];
    [loginrequest setSocialAccountType  :[params objectForKey:@"socialaccounttype"]];
    [loginrequest setSocialAccountId    :[params objectForKey:@"socialaccountid"]];
    [loginrequest setSocialAccountToken :[params objectForKey:@"socialaccounttoken"]];
    [loginrequest setAppLanguage        :[UserDefaults objectForKey:kAppLanguage]?[UserDefaults objectForKey:kAppLanguage]:@"en"];
    [loginrequest setName               :[params objectForKey:@"name"]];
    [loginrequest setPhotoUrl           :[params objectForKey:@"photourl"]];
    [loginrequest setLatitude            :[params objectForKey:@"latitude"]];
    [loginrequest setLongitude         :[params objectForKey:@"longitude"]];
    [loginrequest setDeviceId        :[params objectForKey:@"deviceId"]];
    [[NetworkManager manager] requestWithMethod:kHTTPMethodPOST1
                                      URLString:@"login"
                                     parameters:loginrequest
                                         sucess:^(id response)
     {
         [self dismissProgressHUD];
         [self.view setUserInteractionEnabled:YES];
         
         NSLog(@"response:%@",response);
         
         if ([[response objectForKey:kSuccess] boolValue] == YES)
         {
             if ([[response objectForKey:@"data"] count] > 0)
             {
                 _loginModel = [[WXLoginModel alloc] initWithDictionary:[[response objectForKey:@"data"] objectAtIndex:0] error:nil];
                 
                 [UserDefaults setObject:_loginModel.token forKey:kToken];
                 [UserDefaults setBool:YES forKey:kIsLoggedin];
                 [UserDefaults setObject:[[[[response objectForKey:@"data"] firstObject] objectForKey:@"user"] objectForKey:@"photoUrl"] forKey:kUserImage];
                 [UserDefaults setObject:[[[[response objectForKey:@"data"] firstObject] objectForKey:@"user"] objectForKey:@"name"] forKey:kUserName];
                 [UserDefaults setObject:[[[[response objectForKey:@"data"] firstObject] objectForKey:@"user"] objectForKey:@"email"] forKey:kUserEmail];
                 [UserDefaults setBool:[[[[response objectForKey:@"data"] firstObject] objectForKey:kRequirePassword] boolValue] forKey:kRequirePassword];
                 [UserDefaults setBool:[[[[response objectForKey:@"data"] firstObject] objectForKey:kRequireEmail] boolValue] forKey:kRequireEmail];
                 
                 [UserDefaults setBool:[[[[response objectForKey:@"data"] firstObject] objectForKey:kIsEmail] boolValue] forKey:kIsEmail];
                 [UserDefaults setBool:[[[[response objectForKey:@"data"] firstObject] objectForKey:kIsFacebook] boolValue] forKey:kIsFacebook];
                 [UserDefaults setBool:[[[[response objectForKey:@"data"] firstObject] objectForKey:kIsTwitter] boolValue] forKey:kIsTwitter];
                 [UserDefaults setBool:[[[[response objectForKey:@"data"] firstObject] objectForKey:kIsLinkedIn] boolValue] forKey:kIsLinkedIn];
                 [UserDefaults setBool:[[[[response objectForKey:@"data"] firstObject] objectForKey:kIsGoogle] boolValue] forKey:kIsGoogle];
                 
                 
                 [UserDefaults synchronize];
                 
                 //  [self callLoginWebServiceToLoadFcmToken:params];
                 [self callGetUserSelectedWebserviceWithparams];
                 //           getUserSelectedTab
                 
             }
         }
         else
         {
             UIAlertController * alertcontroller = [UIAlertController alertControllerWithTitle:kAPPName message:[NSString stringWithFormat:@"%@",[response objectForKey:kMessage]]  preferredStyle:UIAlertControllerStyleAlert];
             UIAlertAction * ok = [UIAlertAction actionWithTitle:[WXLoginViewController languageSelectedStringForKey:@"Ok"] style:UIAlertActionStyleDefault handler:nil];
             [alertcontroller addAction:ok];
             [self presentViewController:alertcontroller animated:NO completion:nil];
             //             [[[UIAlertView alloc] initWithTitle:kAPPName message:[response objectForKey:kMessage] delegate:nil cancelButtonTitle:[WXLoginViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles: nil] show];
         }
         
         
     }failure:^(NSError *error)
     {
         NSLog(@"error:%@",error);
         [self dismissProgressHUD];
         [self.view setUserInteractionEnabled:YES];
     }];
    //  }
}

//- (void)callLoginWebServiceToLoadFcmToken: (NSDictionary*)params
//{
//    if(![[NetworkManager manager] isConnectedToWiFi])
//    {
//        [[[UIAlertView alloc] initWithTitle:kAPPName message:[WXLoginViewController languageSelectedStringForKey:@"Network Not Available"] delegate:nil cancelButtonTitle:[WXLoginViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles: nil] show];
//        return;
//    }
//    [self showProgressHUD:[WXLoginViewController languageSelectedStringForKey:@"Loading..."]];
//    WXLoginRequest *loginrequest= [[WXLoginRequest alloc] init];
//    [loginrequest setEmail              :[params objectForKey:@"email"]];
//    [loginrequest setPassword           :[params objectForKey:@"password"]];
//    [loginrequest setLatitude            :[params objectForKey:@"latitude"]];
//    [loginrequest setLongitude         :[params objectForKey:@"longitude"]];
//    [loginrequest setDeviceId        :[params objectForKey:@"deviceId"]];
//    [[NetworkManager manager] requestWithMethod:kHTTPMethodPOST1
//                                      URLString:@"Devlogin"
//                                     parameters:loginrequest
//                                         sucess:^(id response)
//     {
//         // [self dismissProgressHUD];
//         
//         NSLog(@"response:%@",response);
//         
//         if ([[response objectForKey:kSuccess] boolValue] == YES)
//         {
//             if ([[response objectForKey:@"data"] count] > 0)
//             {
//                 
//                 //           getUserSelectedTab
//                 
//             }
//         }
//         else
//         {
//             [[[UIAlertView alloc] initWithTitle:kAPPName message:[response objectForKey:kMessage] delegate:nil cancelButtonTitle:[WXLoginViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles: nil] show];
//         }
//         
//         
//     }failure:^(NSError *error)
//     {
//         NSLog(@"error:%@",error);
//         [self dismissProgressHUD];
//     }];
//
//}

- (void)callGetUserSelectedWebserviceWithparams
{
    
    if(![[NetworkManager manager] isConnectedToWiFi])
    {
        [[[UIAlertView alloc] initWithTitle:kAPPName message:[WXLoginViewController languageSelectedStringForKey:@"Network Not Available"] delegate:nil cancelButtonTitle:[WXLoginViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles: nil] show];
        return;
    }
    WXGetUserSelected *getUserSelected = [[WXGetUserSelected alloc] init];
    [getUserSelected setUser_id:[UserDefaults objectForKey:kToken]];
    [[NetworkManager manager] requestWithMethod:kHTTPMethodPOST1
                                      URLString:@"getUserSelectedTab"
                                     parameters:getUserSelected
                                         sucess:^(id response)
     {
         [self dismissProgressHUD];
         [self.view setUserInteractionEnabled:YES];
         NSLog(@"response:%@",response);
         
         if ([[response objectForKey:kSuccess] boolValue] == YES)
         {
             
             [UserDefaults setObject:[self convertToLanguagePrefix:[[[response objectForKey:kData] objectForKeyNonNull:@"article_language"] componentsSeparatedByString:@","]] forKey:kArticleLanguages];
             [UserDefaults setObject:[[[response objectForKey:kData] objectForKeyNonNull:@"category"] componentsSeparatedByString:@"|"] forKey:kSavedTopics];
             
             [UserDefaults synchronize];
             
             [self setLanguage:[[response objectForKey:kData] objectForKeyNonNull:@"website_language"]];
             
             [UserDefaults setObject:[NSDate date] forKey:@"lastLogged"];
             
             WXLandingScreenViewController *landingScreen = [self.storyboard instantiateViewControllerWithIdentifier:@"WXLandingScreenViewController"];
             [self.navigationController pushViewController:landingScreen animated:YES];
             
         }
         else
         {
             
             [[[UIAlertView alloc] initWithTitle:kAPPName message:[NSString stringWithFormat:@"%@",[response objectForKey:kMessage]] delegate:nil cancelButtonTitle:[WXLoginViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles: nil] show];
         }
         
         
     }failure:^(NSError *error)
     {
         NSLog(@"error:%@",error);
         [self dismissProgressHUD];
         [self.view setUserInteractionEnabled:YES];
     }];
}

-(void)callForgotPasswordServiceWithEmail:(NSString*)email
{
    if(![[NetworkManager manager] isConnectedToWiFi])
    {
        [[[UIAlertView alloc] initWithTitle:kAPPName message:[WXLoginViewController languageSelectedStringForKey:@"Network Not Available"] delegate:nil cancelButtonTitle:[WXLoginViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles: nil] show];
        return;
    }
    [self showProgressHUD:[WXLoginViewController languageSelectedStringForKey:@"Loading..."]];
    [self.view setUserInteractionEnabled:NO];
    //  if([[NetworkManager manager] isConnectedToWiFi])
    //  {
    WXForgotPassModel *forgotPassword= [[WXForgotPassModel alloc] init];
    [forgotPassword setEmail        :email];
    [forgotPassword setAppLanguage  :[UserDefaults objectForKey:kAppLanguage]?[UserDefaults objectForKey:kAppLanguage]:@"en"];
    
    [[NetworkManager manager] requestWithMethod:kHTTPMethodPOST1
                                      URLString:@"forgotPassword"
                                     parameters:forgotPassword
                                         sucess:^(id response)
     {
         [self dismissProgressHUD];
         [self.view setUserInteractionEnabled:YES];
         NSLog(@"res:%@",response);
         if ([[response objectForKey:kSuccess] boolValue] == YES)
         {
             UIAlertController * alertcontroller = [UIAlertController alertControllerWithTitle:kAPPName message:[NSString stringWithFormat:@"%@",[response objectForKey:kMessage]]  preferredStyle:UIAlertControllerStyleAlert];
             UIAlertAction * ok = [UIAlertAction actionWithTitle:[WXLoginViewController languageSelectedStringForKey:@"Ok"] style:UIAlertActionStyleDefault handler:nil];
             [alertcontroller addAction:ok];
             [self presentViewController:alertcontroller animated:NO completion:nil];
             
             //             [[[UIAlertView alloc] initWithTitle:kAPPName message:[NSString stringWithFormat:@"%@",[response objectForKey:kMessage]] delegate:nil cancelButtonTitle:[WXLoginViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles: nil] show];
         }
         else
         {
             UIAlertController * alertcontroller = [UIAlertController alertControllerWithTitle:kAPPName message:[NSString stringWithFormat:@"%@",[response objectForKey:kMessage]]  preferredStyle:UIAlertControllerStyleAlert];
             UIAlertAction * ok = [UIAlertAction actionWithTitle:[WXLoginViewController languageSelectedStringForKey:@"Ok"] style:UIAlertActionStyleDefault handler:nil];
             [alertcontroller addAction:ok];
             [self presentViewController:alertcontroller animated:NO completion:nil];
         }
         
     }failure:^(NSError *error)
     {
         NSLog(@"error:%@",error);
         [self dismissProgressHUD];
         [self.view setUserInteractionEnabled:YES];
     }];
    //  }
}

- (NSArray*)convertToLanguagePrefix:(NSArray*)array
{
    NSMutableArray *arrTemp = [[NSMutableArray alloc] init];
    for (NSInteger index = 0; index < array.count; index++)
    {
        switch ([[array objectAtIndex:index] integerValue])
        {
            case 1:
                if (![arrTemp containsObject:@"en"])
                {
                    [arrTemp addObject:@"en"];
                }
                
                break;
            case 2:
                if (![arrTemp containsObject:@"pt"])
                {
                    [arrTemp addObject:@"pt"];
                }
                break;
            case 3:
                if (![arrTemp containsObject:@"es"])
                {
                    [arrTemp addObject:@"es"];
                }
                break;
                
            default:
                break;
        }
    }
    return arrTemp;
}

- (void)setLanguage:(NSString*)prefix
{
    if ([prefix rangeOfString:@"english"].location != NSNotFound)
    {
        [UserDefaults setObject:@"en" forKey:kAppLanguage];
        [UserDefaults synchronize];
    }
    else if ([prefix rangeOfString:@"spanish"].location != NSNotFound)
    {
        [UserDefaults setObject:@"es" forKey:kAppLanguage];
        [UserDefaults synchronize];
    }
    else if ([prefix rangeOfString:@"portuguese"].location != NSNotFound)
    {
        [UserDefaults setObject:@"pt" forKey:kAppLanguage];
        [UserDefaults synchronize];
    }
    
}

@end


