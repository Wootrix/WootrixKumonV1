//
//  WXArticleDetailViewController.h
//  Wootrix
//
//  Created by Mayank Pahuja on 15/12/14.
//  Copyright (c) 2014 Techahead. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <Social/Social.h>
#import "WXCommentViewController.h"

typedef enum
{
  kSharingPlatformFacebook,
  kSharingPlatformTwitter
} kSharingPlatform;


@interface WXArticleDetailViewController : UIViewController<UIWebViewDelegate,UIActionSheetDelegate,CommentDelegate>
@property (strong, nonatomic) IBOutlet UILabel *lblCommentsCount;

@property (strong, nonatomic) IBOutlet UILabel *lblNavBar;
@property (nonatomic, strong) NSMutableDictionary *dictData;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewArticle;
@property (strong, nonatomic) IBOutlet UILabel *lblTitle;
@property (strong, nonatomic) IBOutlet UILabel *lblDate;
@property (strong, nonatomic) IBOutlet UILabel *lblWebURL;
@property (strong, nonatomic) IBOutlet UITextView *txtViewDesc;
@property (strong, nonatomic) UINavigationController *navControllerPopover;
@property (strong, nonatomic) UIPopoverController *popOverViewC;
@property (strong, nonatomic) IBOutlet UIButton *btnComment;
@property (strong, nonatomic) IBOutlet UIButton *btnShare;
@property (strong, nonatomic) IBOutlet UIWebView *webViewDesc;
@property (strong, nonatomic) NSString *type;
@property (strong, nonatomic) NSString *magazineId;
@property (weak, nonatomic) IBOutlet UIImageView *imgHeaderLogo;
@property (strong, nonatomic) UIImage *imgHeader;
@property (strong, nonatomic) IBOutlet UIView *viewNavBar;



/**
 *  called on Tapping the back button
 *
 *  @param sender object of UIButton
 */
- (IBAction)btnBackTapped:(UIButton*)sender;

/**
 *  called on Tapping the Comment button
 *
 *  @param sender object of UIButton
 */
- (IBAction)btnCommentTapped:(UIButton*)sender;

/**
 *  called on Tapping the Share button
 *
 *  @param sender object of UIButton
 */
- (IBAction)btnShareTapped:(UIButton*)sender;


@end


