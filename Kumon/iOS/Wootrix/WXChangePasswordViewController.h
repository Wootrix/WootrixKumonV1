//
//  WXChangePasswordViewController.h
//  Wootrix
//
//  Created by Mayank Pahuja on 18/12/14.
//  Copyright (c) 2014 Techahead. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface WXChangePasswordViewController : UIViewController

@property (nonatomic, strong) NSString *msgString;

@property (nonatomic, strong) IBOutlet UITextField *txtFieldOldPassword;
@property (nonatomic, strong) IBOutlet UITextField *txtFieldNewPassword;
@property (nonatomic, strong) IBOutlet UITextField *txtFieldConfirmPassword;
@property (strong, nonatomic) IBOutlet UILabel *lblNavBar;

/**
 *  Called on tapping the back button
 *
 *  @param sender object of UIButton
 */
- (IBAction)btnBackTapped:(UIButton*)sender;

/**
 *  Called on tapping the Done button
 *
 *  @param sender object of UIButton
 */
- (IBAction)btnDoneTapped:(UIButton*)sender;
@end
