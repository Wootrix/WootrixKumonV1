//
//  WXMyAccountViewController.h
//  Wootrix
//
//  Created by Mayank Pahuja on 15/12/14.
//  Copyright (c) 2014 Techahead. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "WXChangeLanguageViewController.h"
#import <AssetsLibrary/AssetsLibrary.h>
#import "WXChangePasswordViewController.h"
#import "WXAboutUsViewController.h"

@protocol WXMyAccountDelegate <NSObject>



@end

@interface WXMyAccountViewController : UIViewController<UIImagePickerControllerDelegate,UINavigationControllerDelegate,UIActionSheetDelegate>
{
  BOOL isUploadingImage;
  NSArray *arrayMyAccount;
}

@property (strong, nonatomic) IBOutlet UILabel *lblNavBar;
@property (nonatomic, strong) IBOutlet UISwitch *switchShowPage;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewCover;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewProfile;
@property (nonatomic, strong) UIImagePickerController *imagePicker;
@property (strong, nonatomic) IBOutlet UILabel *lblUsername;

@property (strong, nonatomic) IBOutlet UILabel *lblUseremail;

- (IBAction)btnSwitchShowPage:(id)sender;

/**
 *  Called on tapping the back button
 *
 *  @param sender object of UIButton
 */
- (IBAction)btnBackTapped:(UIButton*)sender;


/**
 *  Called on tapping the change profile picture camera icon
 *
 *  @param sender object of UIButton
 */
- (IBAction)btnChangeImageTapped:(UIButton*)sender;


/**
 *  Called on tapping the "Change your login credentials button"
 *
 *  @param sender object of UIButton
 */
- (IBAction)btnChangeLoginCredentialsTapped:(UIButton*)sender;


@property (strong, nonatomic) IBOutlet UITableView *tblViewMyAccount;

@end
