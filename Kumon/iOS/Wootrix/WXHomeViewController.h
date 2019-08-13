//
//  WXHomeViewController.h
//  Wootrix
//
//  Created by Mayank Pahuja on 15/12/14.
//  Copyright (c) 2014 Techahead. All rights reserved.
//

#import <UIKit/UIKit.h>

#import "AFKPageFlipper.h"
#import "AFKPageFlipperIphone.h"
#import "WXMyAccountViewController.h"

#import "WXOpenArticlesModel.h"
#import "WXSearchArticlesModel.h"
#import "WXGetArticlesModel.h"
#import "WXSearchMagazineModel.h"
#import "WXAdvertisementMagazineModel.h"
#import "WXAdsReportModel.h"
#import "WXOpenAdsViewController.h"

#import "WXIPhoneLayout1.h"
#import "WXIPhoneLayout2.h"
#import "WXIPadLayout1.h"
#import "WXIPadLayout2.h"
#import "WXIPadLayout1Portrait.h"
#import "WXIPadLayout2Portrait.h"
#import "WXIPadLayout3.h"
#import "WXIPadLayout4.h"
#import "WXIPadLayout3Portrait.h"
#import "WXIPadLayout4Portrait.h"
#import "WXMenuViewController.h"
#import <MediaPlayer/MediaPlayer.h>


/**
 WXHomeViewController is the Home screen for the application that contains all the articles in a page view format. the pages can be flipped to change to next page. On tapping an article a detail screen of that article comes up.
 */

@interface WXHomeViewController : UIViewController<AFKPageFlipperDataSource,AFKPageFlipperIphoneDataSource,MenuViewDelegate>
{
    NSMutableArray *arrayArticles;
    NSMutableArray *arrSearchArticles;
    NSInteger pageNumber;
    NSDateFormatter *formatterSource;
    NSDateFormatter *formatterFinal;
    NSDictionary *dicAdvertisement;
    
    NSInteger imageCount;
    
    NSString *layoutName;
    NSTimer *timerAdv;
    UIImageView *tempImgView;
    NSArray *arrAdvImages;
    BOOL loadMore;
    BOOL viewLoaded;              //check if the view has been loaded once.
    NSInteger articleLoadPage;
    NSInteger storedPageNumber;
}

@property (strong, nonatomic) IBOutlet UIButton *btnRefresh;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewProfile;
@property (strong, nonatomic) MPMoviePlayerViewController *player;
@property (strong, nonatomic) IBOutlet UITableView *tblViewSearchResults;
@property (strong, nonatomic) IBOutlet UITextField *txtFieldSearchArticles;
@property (nonatomic, strong) AFKPageFlipper *pageFlipper;
@property (nonatomic, strong) AFKPageFlipperIphone *pageFlipperIphone;
@property (strong, nonatomic) IBOutlet UIView *viewFlipperContainer;
@property (strong, nonatomic) IBOutlet UIButton *btnNextPage;
@property (strong, nonatomic) IBOutlet UIButton *btnPreviousPage;
@property (strong, nonatomic) IBOutlet UIScrollView *scrlViewPages;
@property (strong, nonatomic) IBOutlet UIButton *btnProfile;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewCustomerLogo;
@property (strong, nonatomic) UINavigationController *navControllerPopover;
@property (strong, nonatomic) UIPopoverController *popOverViewC;
@property (strong, nonatomic) IBOutlet UIView *viewPageBar;
@property (strong, nonatomic) IBOutlet UIView *viewNavBar;
@property (strong, nonatomic) UIView *viewAdvertisement;
@property (strong, nonatomic) UILabel *lblPoint;
@property (strong, nonatomic) IBOutlet UIButton *btnBack;
@property (strong, nonatomic) IBOutlet UIImageView *img_BackArrow;
@property (nonatomic, strong) UIButton *btnAdvImage;
@property (nonatomic, strong) UIButton *btnAdvVideo;
@property (nonatomic, strong) UIImageView *imgViewAdvertisement;
@property (nonatomic, strong) NSDictionary *dicOpenArticle;

@property(strong, nonatomic)  NSString*  openArticleId;
@property(strong, nonatomic)  NSString*  openAdvertisementURL;
@property (strong, nonatomic) NSDictionary* dictCloseArticle;

@property (assign, nonatomic) BOOL isMagazine;
@property (assign, nonatomic) BOOL isMagazinePhotoShown;
@property (assign, nonatomic) BOOL isCloseMagCover;
@property (assign, nonatomic)NSMutableArray *arrayArticles;
@property (assign, nonatomic) BOOL viewLoaded;
@property (nonatomic, strong) NSDictionary *dictMagazine;
@property (assign, nonatomic) BOOL shouldCoverImageAppear;
//@property (strong, nonatomic) UIView *viewMain;

/**
 *  call on tapping the search button
 *
 *  @param sender object of UIButton
 */
- (IBAction)btnSearchTapped:(UIButton*)sender;

/**
 *  called on Tapping the image on top right corner
 *
 *  @param sender object of UIButton
 */
- (IBAction)btnProfileTapped:(UIButton*)sender;

/**
 *  <#Description#>
 *
 *  @param sender <#sender description#>
 */
- (IBAction)btnPrevPageTapped:(UIButton *)sender;
/**
 *  <#Description#>
 *
 *  @param sender <#sender description#>
 */
- (IBAction)btnNextPageTapped:(UIButton *)sender;
- (IBAction)backButtonPressed:(UIButton *)sender;
- (IBAction)btnRefreshTapped:(id)sender;
- (IBAction)OnHome:(id)sender;

- (void)callAdvertisementOpenService;
- (void)callOpenArticlesWebService;

@end

