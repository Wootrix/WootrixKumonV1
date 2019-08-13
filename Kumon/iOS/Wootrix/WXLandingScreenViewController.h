//
//  WXLandingScreenViewController.h
//  Wootrix
//
//  Created by Mayank Pahuja on 29/12/14.
//  Copyright (c) 2014 Techahead. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "WXLandingRequestModel.h"
#import "WXMyAccountViewController.h"
#import "WXAddMagazineModel.h"
#import "WXOpenArticlesModel.h"
#import "WXMenuViewController.h"

@protocol WXLandingScreenViewControllerProtocol <NSObject>

/**
 *	Runs the |block| submitted on serial 'introQueue'.
 */
- (void)executeBlock:(dispatch_block_t)block;

@end

@interface WXLandingScreenViewController : UIViewController<MenuViewDelegate, UICollectionViewDelegate, UICollectionViewDataSource, UICollectionViewDelegateFlowLayout>
{
    NSMutableArray *arrMagazines;
@protected
    dispatch_queue_t landingQueue;
    void *landingQueueTag;
}
@property (strong, nonatomic) IBOutlet UIButton *btnProfile;

- (IBAction)btnViewProfileTapped:(UIButton *)sender;
@property (strong, nonatomic) IBOutlet UILabel *lblArticleTitle;
@property (strong, nonatomic) IBOutlet UILabel *lblArticleDate;
@property (strong, nonatomic) IBOutlet UILabel *lblWebsiteLink;
@property (strong, nonatomic) IBOutlet UITextView *txtViewArticleDetail;
@property (strong, nonatomic) IBOutlet UIScrollView *scrlViewMagazines;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewArticle;
@property (strong, nonatomic) UINavigationController *navControllerPopover;
@property (strong, nonatomic) IBOutlet UILabel *lblMagazine;
@property (strong, nonatomic) IBOutlet UIView *viewContainer;
@property (strong, nonatomic) IBOutlet NSLayoutConstraint *viewTextLeadingConstraint;
@property (strong, nonatomic) IBOutlet NSLayoutConstraint *viewTextTraillingCOnstraint;
@property (strong, nonatomic) IBOutlet NSLayoutConstraint *viewTextBottomConstraint;
@property (strong, nonatomic) IBOutlet NSLayoutConstraint *viewTextTopCOnstraint;
@property (strong, nonatomic) IBOutlet NSLayoutConstraint *imgViewHeightConstraint;
@property (strong, nonatomic) IBOutlet UIView *viewTextContainer;
@property (strong, nonatomic) IBOutlet NSLayoutConstraint *imgViewWidthCOnstraint;
@property (strong, nonatomic) IBOutlet UILabel *lblOpenArticle;
@property (strong, nonatomic) NSDictionary *dictData;
@property (strong, nonatomic) NSString* closedMagazineId;
@property (weak, nonatomic) IBOutlet UICollectionView *CollectionView;

@end
