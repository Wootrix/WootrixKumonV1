//
//  WXMenuViewController.h
//  Wootrix
//
//  Created by Mayank Pahuja on 15/12/14.
//  Copyright (c) 2014 Techahead. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "WXGetTopicsModel.h"
#import "WXSubscribeMagazineModel.h"
#import "WXGetMagazinesModel.h"
//#import "WXHomeViewController.h"

@protocol MenuViewDelegate <NSObject>

- (void)logoutAction;
- (void)magazineTapped:(NSDictionary*)dictData;
- (void)openArticleTapped;
- (void)cancelTapped;
- (void)doneTapped;

@end

@interface WXMenuViewController : UIViewController
{
    NSMutableArray  *arrTopics;
    NSMutableArray  *arrTempSavedTopics,*arrTempSavedLanguages;
    NSArray         *arrLanguages;
    NSArray         *arrMagazines;
}
/**
 *  called on Tapping the cancel button
 *
 *  @param sender object of UIButton
 */
- (IBAction)btnCancelTapped:(UIButton*)sender;

/**
 *  called on Tapping the Done button
 *
 *  @param sender object of UIButton
 */
- (IBAction)btnDoneTapped:(UIButton*)sender;


/**
 *  called on Tapping the Logout button
 *
 *  @param sender object of UIButton
 */


- (IBAction)btnLogoutTapped:(UIButton*)sender;

- (IBAction)btnSettingsTapped:(UIButton *)sender;
@property (strong, nonatomic) IBOutlet UITableView *tblViewTopics;
@property (strong, nonatomic) IBOutlet UITableView *tblViewLanguages;
@property (strong, nonatomic) IBOutlet UIScrollView *scrlViewMagazines;
@property (strong, nonatomic) id<MenuViewDelegate> delegate;

@property (strong, nonatomic) IBOutlet UIButton *btnCancel;
@property (strong, nonatomic) IBOutlet UILabel *lblNavBar;
@property (strong, nonatomic) IBOutlet UILabel *lblOpenArticles;
@property (strong, nonatomic) IBOutlet UIButton *btnDone;
@property (strong, nonatomic) IBOutlet UILabel *lblTopics;
@property (strong, nonatomic) IBOutlet UILabel *lblArticleLanguages;
@property (strong, nonatomic) IBOutlet UILabel *lblMagazines;
@property (strong, nonatomic) IBOutlet UIButton *btnLogout;
@end
