//
//  WXLoginViewController.h
//  Wootrix
//
//  Created by Mayank Pahuja on 12/12/14.
//  Copyright (c) 2014 Techahead. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <Accounts/Accounts.h>
#import <Social/Social.h>
#import <GooglePlus/GooglePlus.h>

//#import "TAFacebookHelper.h"
#import "TALinkedInHelper.h"
#import "TAGooglePlus.h"
#import "TTTAttributedLabel.h"
#import "WXLoginModel.h"
#import "WXForgotPassModel.h"
//#import "FHSTwitterEngine.h"

typedef void (^GoogleCompletionHandler)(NSDictionary *response);
@interface WXLoginViewController : UIViewController<TTTAttributedLabelDelegate>
{
@protected
  dispatch_queue_t loginQueue;
  void *loginQueueTag;

}

@property (strong, nonatomic) IBOutlet UITextField *txtFieldEmail;
@property (strong, nonatomic) IBOutlet UITextField *txtFieldPassword;
@property (strong, nonatomic) IBOutlet UIButton *btnLogin;
@property (strong, nonatomic) IBOutlet UIButton *btnLoginCollaborator;
@property (strong, nonatomic) IBOutlet UILabel *lblLoginWith;
@property (strong, nonatomic) IBOutlet TTTAttributedLabel *lblDontHaveAnAccount;

@property (strong, nonatomic) WXLoginModel *loginModel;
@property (strong, nonatomic) NSString *msgString;

@property (nonatomic, retain)ACAccountStore *facebookACAccountStore;
@property (nonatomic, retain)ACAccountStore *twitterACAccountStore;
/**
 *  Called on Tapping the Login button
 *
 *  @param sender object of tapped button
 */
- (IBAction)btnLoginTapped:(UIButton*)sender;

/**
 *  Called on Tapping the Login button
 *
 *  @param sender object of tapped button
 */
- (IBAction)btnLoginCollabTapped:(UIButton*)sender;

/**
 *  Called on Tapping the Signup button
 *
 *  @param sender object of tapped button
 */
- (IBAction)btnSignupTapped:(UIButton*)sender;

/**
 *  Called on Tapping the Facebook button
 *
 *  @param sender object of tapped button
 */
- (IBAction)btnFacebookTapped:(UIButton*)sender;

/**
 *  Called on Tapping the Twitter button
 *
 *  @param sender object of tapped button
 */
- (IBAction)btnTwitterTapped:(UIButton*)sender;

/**
 *  Called on Tapping the LinkedIn button
 *
 *  @param sender object of tapped button
 */
- (IBAction)btnLinkedInTapped:(UIButton*)sender;

/**
 *  Called on Tapping the Gplus button
 *
 *  @param sender object of tapped button
 */
- (IBAction)btnGplusTapped:(UIButton*)sender;

/**
 *  Called on Tapping the ? button
 *
 *  @param sender object of tapped button
 */
- (IBAction)btnForgotPasswordTapped:(UIButton*)sender;

/**
 *  <#Description#>
 *
 *  @param completionHandler <#completionHandler description#>
 */

- (void)registerNewUserForGoogle:(GooglePlusCompleteHandler)completionHandler;
- (void)callLoginWebServiceToLoadFcmToken: (NSDictionary*)params;
@end
