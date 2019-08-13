//
//  WXSignupViewController.h
//  Wootrix
//
//  Created by Mayank Pahuja on 12/12/14.
//  Copyright (c) 2014 Techahead. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "WXSignupRequest.h"
#import "TTTAttributedLabel.h"

@interface WXSignupViewController : UIViewController<TTTAttributedLabelDelegate>
{
@protected
  dispatch_queue_t introQueue;
  void *introQueueTag;
}

/**
 *  Called on Tapping the Login button
 *
 *  @param sender object of tapped button
 */
- (IBAction)btnLoginTapped:(UIButton*)sender;

/**
 *  Called on Tapping the Signup button
 *
 *  @param sender object of tapped button
 */
- (IBAction)btnSignupTapped:(UIButton*)sender;


@property (strong, nonatomic) IBOutlet UITextField *txtFieldName;
@property (strong, nonatomic) IBOutlet UITextField *txtFieldEmail;
@property (strong, nonatomic) IBOutlet UITextField *txtFieldPassword;
@property (strong, nonatomic) IBOutlet UITextField *txtFieldConfirmPassword;
@property (strong, nonatomic) IBOutlet UIButton *btnSignup;

@property (strong, nonatomic) NSString *msgString;
@property (strong, nonatomic) IBOutlet TTTAttributedLabel *lblAlredyHave;

@end
