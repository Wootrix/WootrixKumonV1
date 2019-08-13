//
//  WXCommentViewController.h
//  Wootrix
//
//  Created by Mayank Pahuja on 15/12/14.
//  Copyright (c) 2014 Techahead. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "WXGetCommentsModel.h"
#import "WXPostCommentsModel.h"

@protocol CommentDelegate <NSObject>

- (void)getCommentNumber:(NSInteger)comments;

@end

@interface WXCommentViewController : UIViewController
{
  NSMutableArray *arrComments;
}

/**
 *  called on Tapping the back button
 *
 *  @param sender object of UIButton
 */
- (IBAction)btnBackTapped:(UIButton*)sender;

/**
 *  called on Tapping the Send button
 *
 *  @param sender object of UIButton
 */
- (IBAction)btnSendTapped:(UIButton*)sender;
@property (strong, nonatomic) IBOutlet UILabel *lblNavBar;

@property (strong, nonatomic) IBOutlet UIImageView    *imgViewTextBg;
@property (strong, nonatomic) IBOutlet UIButton       *btnSend;
@property (strong, nonatomic) IBOutlet UITextField    *txtFieldComment;
@property (strong, nonatomic) IBOutlet UIView         *viewSeperator;
@property (strong, nonatomic) IBOutlet UIView         *viewCommentBar;
@property (strong, nonatomic) IBOutlet UITableView    *tblViewComments;
@property (strong, nonatomic) NSString *type;
@property (strong, nonatomic) id <CommentDelegate>delegate;

@property (strong, nonatomic) NSDictionary *dictArticleData;
@property (weak, nonatomic) IBOutlet NSLayoutConstraint *commentBarBottomConstrint;


@end
