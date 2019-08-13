//
//  WXHomeViewController.m
//  Wootrix
//
//  Created by Mayank Pahuja on 15/12/14.
//  Copyright (c) 2014 Techahead. All rights reserved.
//

#import "WXHomeViewController.h"
#import "WXArticleDetailViewController.h"
#import "WXAdvertisementOpenRequestModel.h"
#import  <MediaPlayer/MediaPlayer.h>
#import <AVFoundation/AVFoundation.h>
#import "WXIFrameVideoPlayerController.h"
#import "WXLandingScreenViewController.h"
#import "NSString+XMLEntities.h"
#import "NSString+HTML.h"
#import <SafariServices/SafariServices.h>
#import "WXMetricModel.h"


#define kPageButtonOffset     20000
#define kArticleButtonOffset  30000
#define kAnimationDuration		0.5

#define kHeader1          16    //Title
#define kHeader2           12    //Description
#define kHeader3           11    //Date and web address
#define kContentMode      UIViewContentModeScaleAspectFit
typedef enum
{
    kLayoutTypeIPhone1 = 0,
    kLayoutTypeIPhone2,
    kLayoutTypeIPad1,
    kLayoutTypeIPad2,
    kLayoutTypeIPad3,
    kLayoutTypeIPad4,
    kLayoutTypeIPadLandscape1,
    kLayoutTypeIPadLandscape2,
    kLayoutTypeIPadLandscape3,
    kLayoutTypeIPadLandscape4
} kLayoutType;

@interface WXHomeViewController ()<UIGestureRecognizerDelegate,WXIFrameDelegate>
{
    UIView *viewLayout;
    UIImageView *imgCover;
    NSString *strCoverImageURL;
    BOOL isRefreshed;
    BOOL shouldCoverImageAppear;
    NSTimer *myTimer;
}

@property (nonatomic, weak) IBOutlet UIView *referencedView;

@end

@interface WXHomeViewController (WebServiceMethods)


- (void)callSearchArticlesServiceWithText:(NSString*)searchText;
- (void)callGetMagazinesService;
- (void)callSearchMagazineArticlesServiceWithText:(NSString*)searchText;
- (void)callAdvertisementOpenService;
- (void)callOpenArticlesWebService;
- (void)callAdvertisementMagazineService;
- (void)callAdvertisementTappedService;

@end

@implementation WXHomeViewController
@synthesize openArticleId,openAdvertisementURL,dicOpenArticle,dictCloseArticle,viewLoaded,shouldCoverImageAppear,isMagazinePhotoShown,isCloseMagCover;


#pragma mark - Util

/*!
 sends the metrics to the server
 */
-(void) SendMetrics:(NSDictionary *)article
{
    WXMetricModel *metric = [[WXMetricModel alloc] init];
    AppDelegate *delegate = (AppDelegate *)[[UIApplication sharedApplication] delegate];
    
    metric.token = [UserDefaults objectForKey:kToken];
    metric.type_device_access = isDeviceIPad ? @"Tablet" : @"Smartphone";
    metric.id_article = [article objectForKey:@"articleId"];
    metric.id_magazine = _isMagazine ? [_dictMagazine objectForKeyNonNull:@"magazineId"] : @"0";
    metric.so_access = @"iOS";
    metric.date_access = [self FormatDate:[NSDate date] with:@"yyyy-MM-dd HH:mm:ss"];
    metric.latitude = delegate.latString.length > 0 ? delegate.latString : @"";
    metric.longitude = delegate.longString.length > 0 ? delegate.longString : @"";
    
    [[NetworkManager manager] requestWithMethod:kHTTPMethodPOST1
                                      URLString:@"magazineAccess"
                                     parameters:metric
                                         sucess:^(id response)
     {
         
         if ([[response objectForKeyNonNull:kSuccess] boolValue] == YES)
         {
         }
     }failure:^(NSError *error)
     {
         NSLog(@"error %@", error);
     }];
}


/*!
 converts the date to the format to send
 */
-(NSString *) FormatDate:(NSDate *)date with:(NSString *)format
{
    NSDateFormatter *formatter = [[NSDateFormatter alloc]init];
    
    formatter.dateFormat = format;
    
    return ([formatter stringFromDate:date]);
}

#pragma mark - view life cycle

- (void)viewDidLoad
{
    [super viewDidLoad];
    // Do any additional setup after loading the view.
    
    [[NSNotificationCenter defaultCenter]removeObserver:self name:@"openArticleNotification" object:nil];
    
    [[NSNotificationCenter defaultCenter]addObserver:self selector:@selector(articleNotification:) name:@"openArticleNotification" object:nil];
    [[NSNotificationCenter defaultCenter]removeObserver:self name:@"closeArticleNotification" object:nil];
    [[NSNotificationCenter defaultCenter]addObserver:self selector:@selector(magazineNotificationToOpenArticle:) name:@"closeArticleNotification" object:nil];
    
    [[NSNotificationCenter defaultCenter]removeObserver:self name:@"openAdervtismentNotification" object:nil];
    [[NSNotificationCenter defaultCenter]addObserver:self selector:@selector(openAdUsingNotificationinHomePage:) name:@"openAdervtismentNotification" object:nil];

    //This variable prevents cover Image if any controller presents (video, embeded)
    shouldCoverImageAppear = YES;
    
    [self createMagzineCover];
    
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(orientationChanged:) name:UIDeviceOrientationDidChangeNotification object:nil];
    
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(hidePage:) name:@"ShowHidePage" object:nil];
    
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(changeLanguage:) name:@"ChangeLanguage" object:nil];
    
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(changePhoto) name:@"ChangePhoto" object:nil];
    
    if ([UserDefaults objectForKey:kFirebaseId] != nil &&![[UserDefaults objectForKey:kFirebaseId] isEqual:@"fqASoRlz1hQ:APA91bGb_exXTruv7AtArZA16sfdYcmpPzZJRqlDlKHtDYXn5qGEhYfscRAen7IgbWrD8Xvw7eKCoz3e8Pvl28BFq9jUzHsi849V4z6XMeAaLk7JJQ1funoJmc0PGNSj89fJo0PlktW1"]){
//    myTimer = [NSTimer scheduledTimerWithTimeInterval:2.0
//                                               target:self
//                                             selector:@selector(targetMethod:)
//                                             userInfo:nil
//                                              repeats:YES];
}


}

//-(void)targetMethod:(NSTimer*)timer
//{
//    [[NSNotificationCenter defaultCenter]postNotificationName:@"tokenRefreshNotification" object:nil];
//    
//    NSString *fcmDeviceToken;
//    NSString * str = [UserDefaults objectForKey:kFirebaseId];
//    NSLog(@"string%@",str);
//    if([UserDefaults objectForKey:kFirebaseId] != nil)
//    {
//        [myTimer invalidate];
//        myTimer = nil;
//    }
//    if ([UserDefaults objectForKey:kFirebaseId] != nil &&![[UserDefaults objectForKey:kFirebaseId] isEqual:@"fqASoRlz1hQ:APA91bGb_exXTruv7AtArZA16sfdYcmpPzZJRqlDlKHtDYXn5qGEhYfscRAen7IgbWrD8Xvw7eKCoz3e8Pvl28BFq9jUzHsi849V4z6XMeAaLk7JJQ1funoJmc0PGNSj89fJo0PlktW1"]){
//        
//        if ([UserDefaults boolForKey:kIsLoggedin])
//        {
//
//            [myTimer invalidate];
//            myTimer = nil;
//            
//        }
//    }
//}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (void)viewWillAppear:(BOOL)animated
{
    //This code prevents to excecute if any controller presents (video, embeded)
}

- (void)viewDidAppear:(BOOL)animated
{
    [super viewDidAppear:animated];
    //This code prevents to excecute if any controller presents (video, embeded)
    
    loadMore = NO;
    articleLoadPage = 1;
    
    if (!viewLoaded)
    {
        [self setFontsAndColors];
        
        [self setDateFormat];
        if (isDeviceIPad)
        {
            if (_pageFlipper)
            {
                _pageFlipper.dataSource = self;
            }
        }
        else
        {
            if (_pageFlipperIphone)
            {
                _pageFlipperIphone.dataSource = self;
            }
        }
    }
    
    if (isDeviceIPad)
    {
        [self orientationChanged:nil];
    }
    //[self btnRefreshTapped:nil];
}

-(void)viewWillDisappear:(BOOL)animated
{
    [super viewWillDisappear:animated];
    if (isDeviceIPad)
    {
        if (_pageFlipper && !viewLoaded)
        {
            _pageFlipper.dataSource = nil;
        }
    }
    else
    {
        if (_pageFlipperIphone && !viewLoaded)
        {
            _pageFlipperIphone.dataSource = nil;
        }
    }
    
    
}

- (void)dealloc
{
    
    [self stopTimer];
    [[NSNotificationCenter defaultCenter] removeObserver:self name:@"ShowHidePage" object:nil];
    [[NSNotificationCenter defaultCenter] removeObserver:self name:@"ChangeLanguage" object:nil];
    [[NSNotificationCenter defaultCenter] removeObserver:self name:@"ChangePhoto" object:nil];
    [[NSNotificationCenter defaultCenter] removeObserver:self name:UIDeviceOrientationDidChangeNotification object:nil];
}



- (void)viewDidDisappear:(BOOL)animated
{
    [self stopTimer];
}


#pragma mark - nofitication methods

- (void)changePhoto
{
    [_imgViewProfile setImageWithURL:[NSURL URLWithString:[NSString stringWithFormat:@"%@",[UserDefaults objectForKey:kUserImage]]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
    [_imgViewProfile.layer setCornerRadius:_imgViewProfile.frame.size.height/2];
    [_imgViewProfile setClipsToBounds:YES];
    
}
//- (void)viewDidLayoutSubviews
//{
//  static BOOL isLoaded = NO;
//  if (!isLoaded)
//  {
//    isLoaded = YES;
//
//  }
//
//}


#pragma mark - Other Methods
- (void)createMagzineCover
{
    imgCover = [[UIImageView alloc] initWithImage:[UIImage imageNamed:@"cover_icon"]];
    imgCover.backgroundColor = [UIColor whiteColor];
    imgCover.contentMode = UIViewContentModeScaleAspectFit;
    imgCover.clipsToBounds = YES;
    imgCover.frame = CGRectMake(0, -1024, [UIScreen mainScreen].bounds.size.width, [UIScreen mainScreen].bounds.size.height);
    AppDelegate *appDelegate = (AppDelegate *)[[UIApplication sharedApplication] delegate];
    imgCover.userInteractionEnabled = YES;
    
    [appDelegate.window.rootViewController.view addSubview:imgCover];
    
    
    UISwipeGestureRecognizer *swipe = [[UISwipeGestureRecognizer alloc] initWithTarget:self action:@selector(leftSwipeDetected:)];
    swipe.delegate = self;
    swipe.direction = UISwipeGestureRecognizerDirectionLeft;
    [imgCover addGestureRecognizer:swipe];
    
    
    UITapGestureRecognizer *pan = [[UITapGestureRecognizer alloc] initWithTarget:self action:@selector(tapDetected:)];
    pan.delegate = self;
    pan.numberOfTapsRequired = 1;
    pan.numberOfTouchesRequired = 1;
    pan.cancelsTouchesInView = NO;
    [imgCover addGestureRecognizer:pan];
}

-(void)flipEndedFromLeft:(AFKPageFlipper *)pageFlipper
{
    //  [self showCoverImageForURL:strCoverImageURL];
    NSString *imageURL = strCoverImageURL;
    if (!shouldCoverImageAppear) {
        shouldCoverImageAppear = YES;
        return;
    }
    
    
    if (!_isMagazine) {
        return;
    }
    
    
    [imgCover setImage:[UIImage imageNamed:@"cover_icon"]];
    
    NSString *imagePostFix = @"";
    if ([UIApplication sharedApplication].statusBarOrientation == UIInterfaceOrientationPortrait || [UIApplication sharedApplication].statusBarOrientation == UIInterfaceOrientationPortraitUpsideDown)
    {
        imgCover.frame = CGRectMake(0, 0, [UIScreen mainScreen].bounds.size.width, [UIScreen mainScreen].bounds.size.height);
        if (isDeviceIPad) {
            imagePostFix = @"1024768.png";
        }
        else {
            imagePostFix = @"320568.png";
        }
    }
    else
    {
        imgCover.frame = CGRectMake(0, 0, 1024, 768);
        if (isDeviceIPad) {
            imagePostFix = @"7681024.png";
        }
        else {
            imagePostFix = @"568320.png";
        }
    }
    
    imageURL = [imageURL stringByAppendingString:imagePostFix];
    
    if (imageURL.length > 0) {
        [imgCover setImageWithURL:[NSURL URLWithString:imageURL]];
    }
    
    //If cover image is already is already shown no animation required
    if (imgCover.xOrigin == 0) {
        return;
    }
    
    
    [UIView animateWithDuration:kAnimationDuration animations:^{
        imgCover.xOrigin = 0;
    }];
}

- (void)showCoverImageForURL:(NSString *)imageURL
{
    //This variable prevents cover Image if any controller presents (video, embeded)
    if (!shouldCoverImageAppear) {
        shouldCoverImageAppear = YES;
        return;
    }
    
    
    if (!_isMagazine) {
        return;
    }
    
    
    [imgCover setImage:[UIImage imageNamed:@"cover_icon"]];
    
    NSString *imagePostFix = @"";
    if ([UIApplication sharedApplication].statusBarOrientation == UIInterfaceOrientationPortrait || [UIApplication sharedApplication].statusBarOrientation == UIInterfaceOrientationPortraitUpsideDown)
    {
        imgCover.frame = CGRectMake(0, 0, [UIScreen mainScreen].bounds.size.width, [UIScreen mainScreen].bounds.size.height);
        if (isDeviceIPad) {
            imagePostFix = @"1024768.png";
        }
        else {
            imagePostFix = @"320568.png";
        }
    }
    else
    {
        imgCover.frame = CGRectMake(0, 0, 1024, 768);
        if (isDeviceIPad) {
            imagePostFix = @"7681024.png";
        }
        else {
            imagePostFix = @"568320.png";
        }
    }
    
    imageURL = [imageURL stringByAppendingString:imagePostFix];
    
    if (imageURL.length > 0) {
        [imgCover setImageWithURL:[NSURL URLWithString:imageURL]];
    }
    
    //If cover image is already is already shown no animation required
    if (imgCover.xOrigin == 0) {
        return;
    }
    
    imgCover.xOrigin = -1024;
    [UIView animateWithDuration:kAnimationDuration animations:^{
        imgCover.xOrigin = 0;
    }];
}

- (void)leftSwipeDetected:(UIGestureRecognizer *)recognizer
{
    [UIView animateWithDuration:kAnimationDuration animations:^{
        imgCover.xOrigin = -1024;
    }];
    
}

- (void)tapDetected:(UIGestureRecognizer *)recognizer
{
    [UIView animateWithDuration:kAnimationDuration animations:^{
        imgCover.xOrigin = -1024;
    }];
}

#pragma mark - Other Methods

/**
 *  This method is called to set the custom font sizes and colors of all objects present on the view
 */
- (void)setFontsAndColors
{
    
    //  NSDateFormatter *dateFormatter = [[NSDateFormatter alloc] init];
    //  [dateFormatter setDateFormat:@"yyyy-MM-dd HH:mm:ss"];
    //  NSDate *artcleDate = [dateFormatter dateFromString:[dictReceivedData objectForKeyNonNull:@"createdDate"]];
    //  [dateFormatter setDateFormat:@"MMM. dd, yyyy"];
    //  [_imgViewArticle setImageWithURL:[NSURL URLWithString:[dictReceivedData objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
    
    arrayArticles = nil;
    arrayArticles = [[NSMutableArray alloc] init];
    
    
    
    
    //  [_btnProfile.imageView setImageWithURL:[NSURL URLWithString:[NSString stringWithFormat:@"%@",[UserDefaults objectForKey:kUserImage]]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
    [_imgViewProfile setImageWithURL:[NSURL URLWithString:[NSString stringWithFormat:@"%@",[UserDefaults objectForKey:kUserImage]]] placeholderImage:[UIImage imageNamed:@"ArticleLayout2Placeholder"]];
    [_imgViewProfile.layer setCornerRadius:_imgViewProfile.frame.size.height/2];
    [_imgViewProfile setClipsToBounds:YES];
    
    [_btnRefresh.layer setBorderColor:[UIColor blackColor].CGColor];
    [_btnRefresh.layer setCornerRadius:_btnRefresh.frame.size.width/2];
    [_btnRefresh setClipsToBounds:YES];
    [_btnRefresh setBackgroundColor:[UIColor whiteColor]];
    //[_btnRefresh setImage:[UIImage imageNamed:@"refreshIcon"] forState:UIControlStateNormal];
    [_btnRefresh setImage:[UIImage imageNamed:@"refreshIcon_new"] forState:UIControlStateNormal];
    
    if (_isMagazine)
    {
        
        [_imgViewCustomerLogo setHidden:NO];
        
        if ([UserDefaults objectForKey:kMagazineColor] && [[UserDefaults objectForKey:kMagazineColor] length] > 0)
        {
            [_viewNavBar setBackgroundColor:[self colorWithHexString:[[UserDefaults objectForKey:kMagazineColor] stringByReplacingOccurrencesOfString:@"#" withString:@""]]];
        }
        [_txtFieldSearchArticles setPlaceholder:[WXHomeViewController languageSelectedStringForKey:@"Search Articles"]];
//        arrayArticles = nil;
//        arrayArticles = [[NSMutableArray alloc]init]
        [self callAdvertisementMagazineService];
        [self callGetMagazinesService];
    }
    else
    {
        [_imgViewCustomerLogo setHidden:YES];
//        [_btnBack setHidden: YES];
        [_img_BackArrow setHidden: YES];
        [_txtFieldSearchArticles setPlaceholder:[WXHomeViewController languageSelectedStringForKey:@"Search Articles"]];
        [_viewNavBar setBackgroundColor:[UIColor whiteColor]];
        [self callAdvertisementOpenService];
        [self callOpenArticlesWebService];
    }
    //  [_scrlViewPages setPagingEnabled:YES];
    
    
    if ([UserDefaults boolForKey:kHidePage])
    {
        [_viewPageBar setHidden:YES];
    }
    else
    {
        [_viewPageBar setHidden:NO];
    }
    
}

- (void)startTimer
{
    [timerAdv invalidate];
    timerAdv = nil;
    timerAdv = [NSTimer scheduledTimerWithTimeInterval:[[[arrAdvImages objectAtIndex:imageCount] objectForKeyNonNull:@"timeInSeconds"] integerValue] target:self selector:@selector(nextAdvertisement) userInfo:nil repeats:NO];
}

- (void)stopTimer
{
    if(timerAdv)
    {
        [timerAdv invalidate];
        timerAdv = nil;
    }
}

- (void)setDateFormat
{
    formatterSource = nil;
    formatterSource = [[NSDateFormatter alloc] init];
    [formatterSource setDateFormat:@"yyyy-MM-dd HH:mm:ss"];
    
    
    NSString *currentLanguage = [[NSUserDefaults standardUserDefaults] objectForKey:kAppLanguage];
    formatterFinal = nil;
    formatterFinal = [[NSDateFormatter alloc] init];
    //[formatterFinal setDateFormat:@"MMM. dd, yyyy"];
    if ([currentLanguage rangeOfString:@"en"].location != NSNotFound) {
        //For English
        [formatterFinal setDateFormat:@"MM/dd/yy"];
    }
    else{
        //For Portuguese and Spenish
        [formatterFinal setDateFormat:@"dd/MM/yyyy"];
    }
}


- (void)addPageFlipperToCurrentView
{
    if (!isDeviceIPad)
    {
        [self addPageFlipperIphoneToCurrentView];
        return;
    }
    if (_pageFlipper)
    {
        _pageFlipper.dataSource = nil;
        [_pageFlipper removeFromSuperview];
        _pageFlipper = nil;
    }
    
    
    _pageFlipper = [[AFKPageFlipper alloc] initWithFrame:CGRectMake(0, 0, CGRectGetWidth(_viewFlipperContainer.bounds), CGRectGetHeight(_viewFlipperContainer.bounds))];
    _pageFlipper.dataSource = self;
    _pageFlipper.autoresizingMask = UIViewAutoresizingFlexibleWidth | UIViewAutoresizingFlexibleHeight;
    
    [_viewFlipperContainer addSubview:_pageFlipper];
    
    [self.view sendSubviewToBack:_viewPageBar];
    [self.view bringSubviewToFront:_btnRefresh];
    if (articleLoadPage > 1)
    {
        
        //    [self performSelectorOnMainThread:@selector(changePage) withObject:nil waitUntilDone:NO];
        [self.view setUserInteractionEnabled:NO];
        [self performSelector:@selector(changePage) withObject:nil afterDelay:0.5];
    }
}

- (void)addPageFlipperIphoneToCurrentView
{
    if (_pageFlipperIphone)
    {
        _pageFlipperIphone.dataSource = nil;
        [_pageFlipperIphone removeFromSuperview];
        _pageFlipperIphone = nil;
    }
    
    
    _pageFlipperIphone = [[AFKPageFlipperIphone alloc] initWithFrame:CGRectMake(0, 0, CGRectGetWidth(_viewFlipperContainer.bounds), CGRectGetHeight(_viewFlipperContainer.bounds))];
    _pageFlipperIphone.dataSource = self;
    _pageFlipperIphone.autoresizingMask = UIViewAutoresizingFlexibleWidth | UIViewAutoresizingFlexibleHeight;
    
    [_viewFlipperContainer addSubview:_pageFlipperIphone];
    
    [self.view sendSubviewToBack:_viewPageBar];
    [self.view bringSubviewToFront:_btnRefresh];
    if (articleLoadPage > 1)
    {
        
        //    [self performSelectorOnMainThread:@selector(changePage) withObject:nil waitUntilDone:NO];
        [self.view setUserInteractionEnabled:NO];
        [self performSelector:@selector(changePage) withObject:nil afterDelay:0.5];
    }
}

- (void)changePage
{
    [self.view setUserInteractionEnabled:YES];
    if (isDeviceIPad)
    {
        [_pageFlipper setCurrentPage:storedPageNumber animated:NO];
    }
    else
    {
        [_pageFlipperIphone setCurrentPage:storedPageNumber animated:NO];
    }
    
}

- (void)setFlipperFrame
{
    if (isDeviceIPad)
    {
        [_pageFlipper setFrame:CGRectMake(0, 0, CGRectGetWidth(_viewFlipperContainer.bounds), CGRectGetHeight(_viewFlipperContainer.bounds))];
        
        [_pageFlipper setCurrentPage:(pageNumber-1) animated:NO];
    }
    else
    {
        [_pageFlipperIphone setFrame:CGRectMake(0, 0, CGRectGetWidth(_viewFlipperContainer.bounds), CGRectGetHeight(_viewFlipperContainer.bounds))];
        
        [_pageFlipperIphone setCurrentPage:(pageNumber-1) animated:NO];
    }
    
}

/**
 *  This method takes in the type of layout that has to be presented to the user and makes the suitable layout
 *
 *  @param layoutType defined as an enum is the type of layout that has to be presented.
 */
- (void)makeViewForLayout:(kLayoutType)layoutType onView:(UIView*)viewContainer
{
    switch (layoutType)
    {
        case kLayoutTypeIPhone1:
            //      [self makeLayoutTypeIphone1:viewContainer];
            break;
            
        case kLayoutTypeIPhone2:
            //      [self makeLayoutTypeIphone2:viewContainer];
            break;
            
            
        case kLayoutTypeIPad1:
            break;
        case kLayoutTypeIPad2:
            break;
            
        case kLayoutTypeIPad3:
            break;
            
        case kLayoutTypeIPad4:
            break;
            
        case kLayoutTypeIPadLandscape1:
            break;
        case kLayoutTypeIPadLandscape2:
            break;
        case kLayoutTypeIPadLandscape3:
            break;
        case kLayoutTypeIPadLandscape4:
            break;
            
            
        default:
            break;
    }
}

/**
 *  This method makes the scroll view to show all the available pages
 *
 *  @param pages The number of pages in the flipper
 */
- (void)makeBottomPageScroll:(NSInteger)pages
{
    
    [_scrlViewPages.subviews makeObjectsPerformSelector:@selector(removeFromSuperview)];
    
    NSInteger pageIndicatorWidth = 28;
    NSInteger pageIndicatorHeight = 28;
    
    for (int index = 0; index < pages; index ++)
    {
        UIImageView *imgViewPagenumberBG = [[UIImageView alloc] initWithFrame:CGRectMake(index*pageIndicatorWidth, 0, pageIndicatorWidth, pageIndicatorHeight)];
        [imgViewPagenumberBG setImage:[UIImage imageNamed:@"PageIndicator.png"]];
        UIButton *btnPagenumber = [UIButton buttonWithType:UIButtonTypeCustom];
        [btnPagenumber.titleLabel setFont:[UIFont fontWithName:@"Helvetica" size:9]];
        [btnPagenumber setTitleColor:[UIColor blackColor] forState:UIControlStateNormal];
        [btnPagenumber setTitleColor:[UIColor colorWithRed:0.0/255.0 green:161.0/255.0 blue:229.0/255.0 alpha:1] forState:UIControlStateSelected];
        [btnPagenumber addTarget:self action:@selector(btnPageTapped:) forControlEvents:UIControlEventTouchUpInside];
        [btnPagenumber setFrame:imgViewPagenumberBG.frame];
        [btnPagenumber setTitle:[NSString stringWithFormat:@"%i",index+1] forState:UIControlStateNormal];
        
        [_scrlViewPages addSubview:imgViewPagenumberBG];
        [_scrlViewPages addSubview:btnPagenumber];
        
        if (index == 0)
        {
            [btnPagenumber setSelected:YES];
        }
    }
    [_scrlViewPages setContentSize:CGSizeMake(pages*28, 28)];
    
}

/**
 *  This method plays the video of the given URL on MPMoviePlayer derived class WXVideoPlayer. It is called upen clicking any video on the layout.
 *
 *  @param videoURL URL of the video that has to be played
 */
- (void)playVideoWithURL:(NSURL*)videoURL
{
    
}

/**
 *  Opens Article on a Detailed Screen.
 *
 *  @param dictArticleDetails the details of the contents of Article
 */
- (void)openArticleDetailScreen:(id)dictArticleDetails
{
    
}


/**
 *  This method is called on searching through the search bar.
 *
 *  @param searchString is the part of string which contains the name of a related article.
 */
- (void)searchArticlesWithString:(NSString*)searchString
{
    
}

/**
 *  Chnages the number in bottom bar
 *
 *  @param pageNumber the number of page
 */
- (void)switchToPage:(NSInteger)page
{
    for (id tempSender in _scrlViewPages.subviews)
    {
        if ([tempSender isKindOfClass:[UIButton class]])
        {
            UIButton *btnSender = (UIButton*)tempSender;
            [btnSender setSelected:NO];
        }
        
    }
    for (id tempSender in _scrlViewPages.subviews)
    {
        if ([tempSender isKindOfClass:[UIButton class]])
        {
            UIButton *btnSender = (UIButton*)tempSender;
            if ([btnSender.titleLabel.text integerValue] == page)
            {
                [btnSender setSelected:YES];
                
            }
            
        }
        
    }
    
}

- (UIColor *) colorWithHexString: (NSString *) hex
{
    NSString *cString = [[hex stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]] uppercaseString];
    
    // String should be 6 or 8 characters
    if ([cString length] < 6) return [UIColor grayColor];
    
    // strip 0X if it appears
    if ([cString hasPrefix:@"0X"]) cString = [cString substringFromIndex:2];
    
    if ([cString length] != 6) return  [UIColor grayColor];
    
    // Separate into r, g, b substrings
    NSRange range;
    range.location = 0;
    range.length = 2;
    NSString *rString = [cString substringWithRange:range];
    
    range.location = 2;
    NSString *gString = [cString substringWithRange:range];
    
    range.location = 4;
    NSString *bString = [cString substringWithRange:range];
    
    // Scan values
    unsigned int r, g, b;
    [[NSScanner scannerWithString:rString] scanHexInt:&r];
    [[NSScanner scannerWithString:gString] scanHexInt:&g];
    [[NSScanner scannerWithString:bString] scanHexInt:&b];
    
    return [UIColor colorWithRed:((float) r / 255.0f)
                           green:((float) g / 255.0f)
                            blue:((float) b / 255.0f)
                           alpha:1.0f];
}


- (UIImage *)getThumbnailForVideoURL:(NSURL *)url
{
    AVURLAsset *asset = [[AVURLAsset alloc] initWithURL:url options:nil];
    AVAssetImageGenerator *generate = [[AVAssetImageGenerator alloc] initWithAsset:asset];
    generate.appliesPreferredTrackTransform = YES;
    NSError *err = NULL;
    CMTime time = CMTimeMake(1, 60);
    CGImageRef imgRef = [generate copyCGImageAtTime:time actualTime:NULL error:&err];
    UIImage *img = [[UIImage alloc] initWithCGImage:imgRef];
    return img;
}

- (void)changeColorToWhiteForAllViewsOf:(UIView *)view
{
    //Changing all labels color to white
    for (UIView *viewUpper in view.subviews)
    {
        for (UIView *viewInside in viewUpper.subviews)
        {
            //      if ([viewInside isKindOfClass:[UILabel class]])
            //      {
            //        [(UILabel *)viewInside setTextColor:[UIColor whiteColor]];
            //      }
            //       if ([viewInside isKindOfClass:[UIImageView class]])
            //      {
            ////        [(UITextView *)viewInside setTextColor:[UIColor whiteColor]];
            //        [(UIImageView*)viewInside setContentMode:UIViewContentModeScaleToFill];
            //
            //      }
            //      else if ([viewInside isKindOfClass:[UIButton class]])
            //      {
            //        [(UIButton *)viewInside setTitleColor:[UIColor whiteColor] forState:UIControlStateNormal];
            //      }
        }
    }
}

#pragma mark - custom views
/**
 *
 *
 *  @param viewContainer
 */

- (NSString *)stringByDecodingXMLEntities:(NSString*)text
{
    NSUInteger myLength = [text length];
    NSUInteger ampIndex = [text rangeOfString:@"&" options:NSLiteralSearch].location;
    
    // Short-circuit if there are no ampersands.
    if (ampIndex == NSNotFound) {
        return text;
    }
    // Make result string with some extra capacity.
    NSMutableString *result = [NSMutableString stringWithCapacity:(myLength * 1.25)];
    
    // First iteration doesn't need to scan to & since we did that already, but for code simplicity's sake we'll do it again with the scanner.
    NSScanner *scanner = [NSScanner scannerWithString:text];
    
    [scanner setCharactersToBeSkipped:nil];
    
    NSCharacterSet *boundaryCharacterSet = [NSCharacterSet characterSetWithCharactersInString:@" \t\n\r;"];
    
    do {
        // Scan up to the next entity or the end of the string.
        NSString *nonEntityString;
        if ([scanner scanUpToString:@"&" intoString:&nonEntityString]) {
            [result appendString:nonEntityString];
        }
        if ([scanner isAtEnd]) {
            goto finish;
        }
        // Scan either a HTML or numeric character entity reference.
        if ([scanner scanString:@"&amp;" intoString:NULL])
            [result appendString:@"&"];
        else if ([scanner scanString:@"&apos;" intoString:NULL])
            [result appendString:@"'"];
        else if ([scanner scanString:@"&quot;" intoString:NULL])
            [result appendString:@"\""];
        else if ([scanner scanString:@"&lt;" intoString:NULL])
            [result appendString:@"<"];
        else if ([scanner scanString:@"&gt;" intoString:NULL])
            [result appendString:@">"];
        else if ([scanner scanString:@"&#" intoString:NULL]) {
            BOOL gotNumber;
            unsigned charCode;
            NSString *xForHex = @"";
            
            // Is it hex or decimal?
            if ([scanner scanString:@"x" intoString:&xForHex]) {
                gotNumber = [scanner scanHexInt:&charCode];
            }
            else {
                gotNumber = [scanner scanInt:(int*)&charCode];
            }
            
            if (gotNumber) {
                [result appendFormat:@"%C", (unichar)charCode];
                
                [scanner scanString:@";" intoString:NULL];
            }
            else {
                NSString *unknownEntity = @"";
                
                [scanner scanUpToCharactersFromSet:boundaryCharacterSet intoString:&unknownEntity];
                
                
                [result appendFormat:@"&#%@%@", xForHex, unknownEntity];
                
                //[scanner scanUpToString:@";" intoString:&unknownEntity];
                //[result appendFormat:@"&#%@%@;", xForHex, unknownEntity];
                NSLog(@"Expected numeric character entity but got &#%@%@;", xForHex, unknownEntity);
                
            }
            
        }
        else {
            NSString *amp;
            
            [scanner scanString:@"&" intoString:&amp];  //an isolated & symbol
            [result appendString:amp];
            
            /*
             NSString *unknownEntity = @"";
             [scanner scanUpToString:@";" intoString:&unknownEntity];
             NSString *semicolon = @"";
             [scanner scanString:@";" intoString:&semicolon];
             [result appendFormat:@"%@%@", unknownEntity, semicolon];
             NSLog(@"Unsupported XML character entity %@%@", unknownEntity, semicolon);
             */
        }
        
    }
    while (![scanner isAtEnd]);
    
finish:
    return result;
}
- (void)makeLayoutTypeIphone1:(UIView*)viewContainer withArray:(NSArray*)arrayData onStartIndex:(NSInteger)startIndex
{
    layoutName = @"advertisement_layout1";
    WXIPhoneLayout1 *viewMain = [WXIPhoneLayout1 loadXIB:@"WXIPhoneLayout1" forClass:[WXIPhoneLayout1 class]];
    [viewMain setFrame:viewContainer.frame];
    [viewContainer addSubview:viewMain];
    
    
    if (arrayData.count >=1)
    {
        [viewMain.btnView1 setTag:startIndex + kArticleButtonOffset];
        [viewMain.btnView1 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        
        //    NSString *resStr = [NSString stringWithFormat:@"%@",[[arrayData objectAtIndex:0] objectForKeyNonNull:@"articleDescPlain"] ] ;
        //     NSString *resStr1 = [resStr stringByDecodingXMLEntities];
        //NSString *resStr2 = [resStr stringByDecodingHTMLEntities];
        
        
        [viewMain.txtViewDescriptionView1 setText:[[[arrayData objectAtIndex:0] objectForKeyNonNull:@"articleDescPlain"]stringByDecodingXMLEntities]];
        [viewMain.txtViewDescriptionView1 setFont:[UIFont systemFontOfSize:9]];
        [viewMain.txtViewDescriptionView1 setTextColor:[UIColor whiteColor]];
        
        [viewMain.lblTitleView1 setText:[[arrayData objectAtIndex:0] objectForKeyNonNull:@"title"]];
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData objectAtIndex:0] objectForKeyNonNull:@"createdDate"]];
        [viewMain.lblDateView1 setText:[formatterFinal stringFromDate:dateRec]];
        [viewMain.lblCaptionView1 setText:[[arrayData objectAtIndex:0] objectForKeyNonNull:@"title"]];
        viewMain.lblCaptionView1.hidden = YES;
        
        NSString *source = [[arrayData objectAtIndex:0] objectForKeyNonNull:@"source"];
        if (source.length > 0) {
            //source = [NSString stringWithFormat:@"Source:%@",source];
            [viewMain.lblWebUrlView1 setText:source];
        }
        else{
            viewMain.lblWebUrlView1.hidden = YES;
            //      UIImageView *imgGlobe = (UIImageView *)[viewMain.lblWebUrlView1.superview viewWithTag:10];
            //      imgGlobe.hidden = YES;
            viewMain.imgViewGlobeView1.hidden = YES;
        }
        
        [viewMain.imgViewBGView1 setImageWithURL:[NSURL URLWithString:[[arrayData objectAtIndex:0] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        [viewMain.imgViewBGView1 setContentMode:kContentMode];
        if (![[[arrayData objectAtIndex:0] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton1 setHidden:NO];
        }
        
        //Embeded
        if ([[[arrayData objectAtIndex:0] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgViewBGView1 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:0] objectForKeyNonNull:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
        
        
    }
    else
    {
        //[viewMain.view1 setHidden:YES];
        [viewMain.view1.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
        viewMain.imgViewBGView1.hidden = NO;
    }
    
    
    if (arrayData.count >= 2)
    {
        [viewMain.btnView2 setTag:startIndex + 1 + kArticleButtonOffset];
        
        [viewMain.btnView2 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        
        //[btnView2 setBackgroundColor:[UIColor orangeColor]];
        [viewMain.txtViewDescriptionView2 setText:[[[arrayData objectAtIndex:1] objectForKeyNonNull:@"articleDescPlain"]stringByDecodingXMLEntities]];
        [viewMain.txtViewDescriptionView2 setFont:[UIFont systemFontOfSize:9]];
        [viewMain.txtViewDescriptionView2 setTextColor:[UIColor whiteColor]];
        
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData objectAtIndex:1] objectForKeyNonNull:@"createdDate"]];
        [viewMain.lblTimeView2 setText:[formatterFinal stringFromDate:dateRec]];
        [viewMain.lblTitleView2 setText:[[arrayData objectAtIndex:1] objectForKeyNonNull:@"title"]];
        
        NSString *source = [[arrayData objectAtIndex:1] objectForKeyNonNull:@"source"];
        if (source.length > 0) {
            //source = [NSString stringWithFormat:@"Source:%@",source];
            [viewMain.lblWebUrlView2 setText:source];
        }
        else{
            viewMain.lblWebUrlView2.hidden = YES;
            //      UIImageView *imgGlobe = (UIImageView *)[viewMain.lblWebUrlView2.superview viewWithTag:10];
            //      imgGlobe.hidden = YES;
            viewMain.imgViewGlobeView2.hidden = YES;
        }
        
        [viewMain.imgViewBGView2 setImageWithURL:[NSURL URLWithString:[[arrayData objectAtIndex:1] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        if (![[[arrayData objectAtIndex:1] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton2 setHidden:NO];
        }
        
        //Embeded
        if ([[[arrayData objectAtIndex:1] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgViewBGView2 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:1] objectForKeyNonNull:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
        
    }
    else
    {
        //[viewMain.view2 setHidden:YES];
        [viewMain.view2.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
    }
    
    
    if (arrayData.count >=3)
    {
        [viewMain.btnView3 setTag:startIndex + 2 + kArticleButtonOffset];
        
        [viewMain.btnView3 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        //[btnView3 setBackgroundColor:[UIColor yellowColor]];
        [viewMain.lblTitleView3 setText:[[arrayData objectAtIndex:2] objectForKeyNonNull:@"title"]];
        [viewMain.txtViewDescriptionView3 setText:[[[arrayData objectAtIndex:2] objectForKeyNonNull:@"articleDescPlain"]stringByDecodingXMLEntities]];
        [viewMain.txtViewDescriptionView3 setFont:[UIFont systemFontOfSize:9]];
        [viewMain.txtViewDescriptionView3 setTextColor:[UIColor whiteColor]];
        
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData objectAtIndex:2] objectForKeyNonNull:@"createdDate"]];
        [viewMain.lblTimeView3 setText:[formatterFinal stringFromDate:dateRec]];
        [viewMain.imgViewBgView3 setContentMode:kContentMode];
        NSString *source = [[arrayData objectAtIndex:2] objectForKeyNonNull:@"source"];
        if (source.length > 0) {
            //source = [NSString stringWithFormat:@"Source:%@",source];
            [viewMain.lblUrlView3 setText:source];
        }
        else{
            viewMain.lblUrlView3.hidden = YES;
            //      UIImageView *imgGlobe = (UIImageView *)[viewMain.lblUrlView3.superview viewWithTag:10];
            //      imgGlobe.hidden = YES;
            viewMain.imgViewGlobeView3.hidden = YES;
        }
        
        [viewMain.imgViewBgView3 setImageWithURL:[NSURL URLWithString:[[arrayData objectAtIndex:2] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        if (![[[arrayData objectAtIndex:2] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton3 setHidden:NO];
        }
        
        //Embeded
        if ([[[arrayData objectAtIndex:2] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgViewBgView3 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:2] objectForKeyNonNull:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
        
    }
    else
    {
        //[viewMain.view3 setHidden:YES];
        [viewMain.view3.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
        viewMain.imgViewBgView3.hidden = NO;
    }
    
    
    if (arrayData.count >=4)
    {
        
        [viewMain.btnView4 setTag:startIndex + 3 + kArticleButtonOffset];
        
        
        [viewMain.btnView4 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        //[btnView4 setBackgroundColor:[UIColor blueColor]];
        
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData objectAtIndex:3] objectForKeyNonNull:@"createdDate"]];
        [viewMain.lblDateView4 setText:[formatterFinal stringFromDate:dateRec]];
        [viewMain.lblTitleView4 setText:[[arrayData objectAtIndex:3] objectForKeyNonNull:@"title"]];
        
        
        [viewMain.txtViewDescriptionView4 setText:[[[[arrayData objectAtIndex:3] objectForKeyNonNull:@"articleDescPlain"] stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]]stringByDecodingXMLEntities]];
        [viewMain.txtViewDescriptionView4 setFont:[UIFont systemFontOfSize:9]];
        [viewMain.txtViewDescriptionView4 setTextColor:[UIColor whiteColor]];
        
        [viewMain.imgViewBGView4 setContentMode:kContentMode];
        NSString *source = [[arrayData objectAtIndex:3] objectForKeyNonNull:@"source"];
        if (source.length > 0) {
            //source = [NSString stringWithFormat:@"Source:%@",source];
            [viewMain.lblURLView4 setText:source];
        }
        else{
            viewMain.lblURLView4.hidden = YES;
            //      UIImageView *imgGlobe = (UIImageView *)[viewMain.lblURLView4.superview viewWithTag:10];
            //      imgGlobe.hidden = YES;
            viewMain.imgViewGlobeView4.hidden = YES;
        }
        
        [viewMain.imgViewBGView4 setImageWithURL:[NSURL URLWithString:[[arrayData objectAtIndex:3] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        
        if (![[[arrayData objectAtIndex:3] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton4 setHidden:NO];
        }
        
        //Embeded
        if ([[[arrayData objectAtIndex:3] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgViewBGView4 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:3] objectForKeyNonNull:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
    }
    else
    {
        //[viewMain.view4 setHidden:YES];
        [viewMain.view4.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
        viewMain.imgViewBGView4.hidden = NO;
    }
    
    _imgViewAdvertisement = viewMain.imgViewAdvert;
    _btnAdvImage = viewMain.btnImage;
    _btnAdvVideo = viewMain.btnVideo;
    
    [_btnAdvImage addTarget:self action:@selector(btnAdvImageTapped) forControlEvents:UIControlEventTouchUpInside];
    [_btnAdvVideo addTarget:self action:@selector(btnAdvVideoTapped) forControlEvents:UIControlEventTouchUpInside];
    
    viewMain.txtViewDescriptionView2.textColor = [UIColor whiteColor];
    
    [viewMain.lblAdvertisement setText:[WXHomeViewController languageSelectedStringForKey:@"Advertisement"]];
    
    //Changing all labels color to white
    [self changeColorToWhiteForAllViewsOf:viewMain];
}

- (void)makeLayoutTypeIphone2:(UIView*)viewContainer withArray:(NSArray*)arrayData onStartIndex:(NSInteger)startIndex
{
    
    layoutName = @"advertisement_layout1";
    WXIPhoneLayout2 *viewMain = [WXIPhoneLayout2 loadXIB:@"WXIPhoneLayout2" forClass:[WXIPhoneLayout2 class]];
    
    [viewMain setFrame:viewContainer.frame];
    
    [viewContainer addSubview:viewMain];
    
    if (arrayData.count >= 1)
    {
        [viewMain.btnView1 setTag:startIndex + kArticleButtonOffset];
        
        [viewMain.btnView1 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        [viewMain.txtViewDescView1 setText:[[[arrayData objectAtIndex:0] objectForKeyNonNull:@"articleDescPlain"]stringByDecodingXMLEntities]];
        [viewMain.txtViewDescView1 setFont:[UIFont systemFontOfSize:9]];
        [viewMain.txtViewDescView1 setTextColor:[UIColor whiteColor]];
        
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData objectAtIndex:0] objectForKeyNonNull:@"createdDate"]];
        [viewMain.lblDateView1 setText:[formatterFinal stringFromDate:dateRec]];
        
        [viewMain.lblTitleView1 setText:[[arrayData objectAtIndex:0] objectForKeyNonNull:@"title"]];
        
        
        NSString *source = [[arrayData objectAtIndex:0] objectForKeyNonNull:@"source"];
        if (source.length > 0) {
            [viewMain.lblWebUrlView1 setText:source];
        }
        else{
            viewMain.lblWebUrlView1.hidden = YES;
            viewMain.imgViewGlobeView1.hidden = YES;
        }
        
        
        [viewMain.imgViewBgView1 setImageWithURL:[NSURL URLWithString:[[arrayData objectAtIndex:0] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        
        [viewMain.imgViewBgView1 setContentMode:kContentMode];
        if (![[[arrayData objectAtIndex:0] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton1 setHidden:NO];
        }
        
        //Embeded
        if ([[[arrayData objectAtIndex:0] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgViewBgView1 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:0] objectForKeyNonNull:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
    }
    else
    {
        //[viewMain.view1 setHidden:YES];
        [viewMain.view1.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
        viewMain.imgViewBgView1.hidden = NO;
    }
    if (arrayData.count >= 2)
    {
        [viewMain.btnView2 setTag:startIndex + 1 + kArticleButtonOffset];
        [viewMain.btnView2 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        [viewMain.txtViewDescriptionView2 setText:[[[arrayData objectAtIndex:1] objectForKeyNonNull:@"articleDescPlain"]stringByDecodingXMLEntities]];
        [viewMain.txtViewDescriptionView2 setFont:[UIFont systemFontOfSize:9]];
        [viewMain.txtViewDescriptionView2 setTextColor:[UIColor whiteColor]];
        
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData objectAtIndex:1] objectForKeyNonNull:@"createdDate"]];
        [viewMain.lblDateView2 setText:[formatterFinal stringFromDate:dateRec]];
        
        NSString *source = [[arrayData objectAtIndex:1] objectForKeyNonNull:@"source"];
        if (source.length > 0) {
            //source = [NSString stringWithFormat:@"Source:%@",source];
            [viewMain.lblEmailView2 setText:source];
        }
        else{
            viewMain.lblEmailView2.hidden = YES;
            //      UIImageView *imgGlobe = (UIImageView *)[viewMain.lblEmailView2.superview viewWithTag:10];
            //      imgGlobe.hidden = YES;
            viewMain.imgViewGlobeView2.hidden = YES;
        }
        
        [viewMain.imgViewBgView2 setImageWithURL:[NSURL URLWithString:[[arrayData objectAtIndex:1] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        
        
        [viewMain.lblTitleView2 setText:[[arrayData objectAtIndex:1] objectForKeyNonNull:@"title"]];
        if (![[[arrayData objectAtIndex:1] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton2 setHidden:NO];
        }
        
        //Embeded
        if ([[[arrayData objectAtIndex:1] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgViewBgView2 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:1] objectForKeyNonNull:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
        
    }
    else
    {
        //[viewMain.view2 setHidden:YES];
        [viewMain.view2.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
    }
    if (arrayData.count >= 3)
    {
        [viewMain.btnView3 setTag:startIndex + 2 + kArticleButtonOffset];
        
        [viewMain.btnView3 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        [viewMain.txtViewDescriptionView3 setText:[[[arrayData objectAtIndex:2] objectForKeyNonNull:@"articleDescPlain"]stringByDecodingXMLEntities]];
        [viewMain.txtViewDescriptionView3 setFont:[UIFont fontWithName:@"Helvetica" size:9]];
        [viewMain.txtViewDescriptionView3 setTextColor:[UIColor whiteColor]];
        
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData objectAtIndex:2] objectForKeyNonNull:@"createdDate"]];
        [viewMain.lblTimeView3 setText:[formatterFinal stringFromDate:dateRec]];
        [viewMain.lblTitleView3 setText:[[arrayData objectAtIndex:2] objectForKeyNonNull:@"title"]];
        
        NSString *source = [[arrayData objectAtIndex:2] objectForKeyNonNull:@"source"];
        if (source.length > 0) {
            //source = [NSString stringWithFormat:@"Source:%@",source];
            [viewMain.lblWebUrlView3 setText:source];
        }
        else{
            viewMain.lblWebUrlView3.hidden = YES;
            //      UIImageView *imgGlobe = (UIImageView *)[viewMain.lblWebUrlView3.superview viewWithTag:10];
            //      imgGlobe.hidden = YES;
            viewMain.imgViewGlobeView3.hidden = YES;
        }
        
        [viewMain.imgViewBgView3 setImageWithURL:[NSURL URLWithString:[[arrayData objectAtIndex:2] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        if (![[[arrayData objectAtIndex:2] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton3 setHidden:NO];
        }
        
        
        //Embeded
        if ([[[arrayData objectAtIndex:2] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgViewBgView3 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:2] objectForKeyNonNull:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
    }
    else
    {
        //[viewMain.view3 setHidden:YES];
        [viewMain.view3.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
    }
    if (arrayData.count >= 4)
    {
        [viewMain.btnView4 setTag:startIndex + 3 + kArticleButtonOffset];
        
        [viewMain.btnView4 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        [viewMain.lblTitleView4 setText:[[arrayData objectAtIndex:3] objectForKeyNonNull:@"title"]];
        
        [viewMain.txtViewDescriptionView4 setText:[[[arrayData objectAtIndex:3] objectForKeyNonNull:@"articleDescPlain"]stringByDecodingXMLEntities]];
        [viewMain.txtViewDescriptionView4 setFont:[UIFont systemFontOfSize:9]];
        [viewMain.txtViewDescriptionView4 setTextColor:[UIColor whiteColor]];
        
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData objectAtIndex:3] objectForKeyNonNull:@"createdDate"]];
        [viewMain.lblTimeView4 setText:[formatterFinal stringFromDate:dateRec]];
        [viewMain.imgViewBgView4 setContentMode:kContentMode];
        NSString *source = [[arrayData objectAtIndex:3] objectForKeyNonNull:@"source"];
        if (source.length > 0) {
            //source = [NSString stringWithFormat:@"Source:%@",source];
            [viewMain.lblUrlView4 setText:source];
        }
        else{
            viewMain.lblUrlView4.hidden = YES;
            //      UIImageView *imgGlobe = (UIImageView *)[viewMain.lblUrlView4.superview viewWithTag:10];
            //      imgGlobe.hidden = YES;
            viewMain.imgViewGlobeView4.hidden = YES;
        }
        
        [viewMain.imgViewBgView4 setImageWithURL:[NSURL URLWithString:[[arrayData objectAtIndex:3] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        if (![[[arrayData objectAtIndex:3] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton4 setHidden:NO];
        }
        
        //Embeded
        if ([[[arrayData objectAtIndex:3] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgViewBgView4 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:3] objectForKeyNonNull:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
    }
    else
    {
        //[viewMain.view4 setHidden:YES];
        [viewMain.view4.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
        viewMain.imgViewBgView4.hidden = NO;
    }
    if (arrayData.count >= 5)
    {
        [viewMain.btnView5 setTag:startIndex + 4 + kArticleButtonOffset];
        
        [viewMain.btnView5 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData objectAtIndex:4] objectForKeyNonNull:@"createdDate"]];
        [viewMain.lblDateView5 setText:[formatterFinal stringFromDate:dateRec]];
        
        [viewMain.txtViewDescView5 setText:[[[arrayData objectAtIndex:4] objectForKeyNonNull:@"articleDescPlain"]stringByDecodingXMLEntities]];
        [viewMain.txtViewDescView5 setFont:[UIFont systemFontOfSize:9]];
        [viewMain.txtViewDescView5 setTextColor:[UIColor whiteColor]];
        
        
        NSString *source = [[arrayData objectAtIndex:4] objectForKeyNonNull:@"source"];
        [viewMain.imgViewBGView5 setContentMode:kContentMode];
        if (source.length > 0) {
            //source = [NSString stringWithFormat:@"Source:%@",source];
            [viewMain.lblURLView5 setText:source];
        }
        else{
            viewMain.lblURLView5.hidden = YES;
            //      UIImageView *imgGlobe = (UIImageView *)[viewMain.lblURLView5.superview viewWithTag:10];
            //      imgGlobe.hidden = YES;
            viewMain.imgViewGlobeView5.hidden = YES;
        }
        
        [viewMain.lblTitleView5 setText:[[arrayData objectAtIndex:4] objectForKeyNonNull:@"title"]];
        [viewMain.imgViewBGView5 setImageWithURL:[NSURL URLWithString:[[arrayData objectAtIndex:4] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        if (![[[arrayData objectAtIndex:4] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton5 setHidden:NO];
        }
        
        //Embeded
        if ([[[arrayData objectAtIndex:4] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgViewBGView5 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:4] objectForKeyNonNull:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
    }
    else
    {
        //[viewMain.view5 setHidden:YES];
        [viewMain.view5.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
        viewMain.imgViewBGView5.hidden = NO;
    }
    _imgViewAdvertisement = viewMain.imgViewAdvert;
    _btnAdvImage = viewMain.btnImage;
    _btnAdvVideo = viewMain.btnVideo;
    
    [_btnAdvImage addTarget:self action:@selector(btnAdvImageTapped) forControlEvents:UIControlEventTouchUpInside];
    [_btnAdvVideo addTarget:self action:@selector(btnAdvVideoTapped) forControlEvents:UIControlEventTouchUpInside];
    
    [viewMain.lblAdvertisement setText:[WXHomeViewController languageSelectedStringForKey:@"Advertisement"]];
    
    //Changing all labels color to white
    [self changeColorToWhiteForAllViewsOf:viewMain];
}

- (void)makeLayoutTypeIpad1:(UIView*)viewContainer withArray:(NSArray*)arrayData onStartIndex:(NSInteger)startIndex
{
    
    layoutName = @"advertisement_layout1";
    WXIPadLayout1Portrait *viewMain = [WXIPadLayout1Portrait loadXIB:@"WXIPadLayout1Portrait" forClass:[WXIPadLayout1Portrait class]];
    
    [viewMain setFrame:viewContainer.frame];
    
    [viewContainer addSubview:viewMain];
    
    if (arrayData.count >=1)
    {
        
        [viewMain.btnView1 setTag:startIndex + kArticleButtonOffset];
        
        [viewMain.btnView1 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        
        [viewMain.txtViewDescView1 setText:[[[arrayData objectAtIndex:0] objectForKeyNonNull:@"articleDescPlain"]stringByDecodingXMLEntities]];
        [viewMain.txtViewDescView1 setFont:[UIFont systemFontOfSize:kHeader2]];
        
        [viewMain.lblTitleView1 setText:[[arrayData objectAtIndex:0] objectForKeyNonNull:@"title"]];
        [viewMain.lblTitleView1 setFont:[UIFont systemFontOfSize:kHeader1]];
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData objectAtIndex:0] objectForKeyNonNull:@"createdDate"]];
        [viewMain.lblDateView1 setText:[formatterFinal stringFromDate:dateRec]];
        [viewMain.lblDateView1 setFont:[UIFont systemFontOfSize:kHeader3]];
        
        [viewMain.imgView1 setContentMode:kContentMode];
        NSString *source = [[arrayData objectAtIndex:0] objectForKeyNonNull:@"source"];
        if (source.length > 0) {
            //source = [NSString stringWithFormat:@"Source:%@",source];
            [viewMain.lblUrlView1 setText:source];
            [viewMain.lblUrlView1 setFont:[UIFont systemFontOfSize:kHeader3]];
        }
        else{
            viewMain.lblUrlView1.hidden = YES;
            //      UIImageView *imgGlobe = (UIImageView *)[viewMain.lblUrlView1.superview viewWithTag:10];
            //      imgGlobe.hidden = YES;
            viewMain.imgViewGlobeView1.hidden = YES;
        }
        
        [viewMain.imgView1 setImageWithURL:[NSURL URLWithString:[[arrayData objectAtIndex:0] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        if ([[[arrayData objectAtIndex:0] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton1 setHidden:YES];
        }
        
        //Embeded
        if ([[[arrayData objectAtIndex:0] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgView1 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:0] objectForKeyNonNull:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
    }
    else
    {
        //[viewMain.view1 setHidden:YES];
        [viewMain.view1.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
        viewMain.imgView1.hidden = NO;
    }
    
    if (arrayData.count >=2)
    {
        [viewMain.btnView2 setTag:startIndex + kArticleButtonOffset + 1];
        
        [viewMain.btnView2 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        
        [viewMain.txtViewDescView2 setText:[[[arrayData objectAtIndex:1] objectForKeyNonNull:@"articleDescPlain"]stringByDecodingXMLEntities]];
        [viewMain.txtViewDescView2 setFont:[UIFont systemFontOfSize:kHeader2]];
        
        [viewMain.lblTitleView2 setText:[[arrayData objectAtIndex:1] objectForKeyNonNull:@"title"]];
        [viewMain.lblTitleView2 setFont:[UIFont systemFontOfSize:kHeader1]];
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData objectAtIndex:1] objectForKeyNonNull:@"createdDate"]];
        [viewMain.lblDateView2 setText:[formatterFinal stringFromDate:dateRec]];
        [viewMain.lblDateView2 setFont:[UIFont systemFontOfSize:kHeader3]];
        [viewMain.imgView2 setContentMode:kContentMode];
        NSString *source = [[arrayData objectAtIndex:1] objectForKeyNonNull:@"source"];
        if (source.length > 0) {
            //source = [NSString stringWithFormat:@"Source:%@",source];
            [viewMain.lblUrlView2 setText:source];
            [viewMain.lblUrlView2 setFont:[UIFont systemFontOfSize:kHeader3]];
        }
        else{
            viewMain.lblUrlView2.hidden = YES;
            //      UIImageView *imgGlobe = (UIImageView *)[viewMain.lblUrlView2.superview viewWithTag:10];
            //      imgGlobe.hidden = YES;
            viewMain.imgViewGlobeView2.hidden = YES;
        }
        
        [viewMain.imgView2 setImageWithURL:[NSURL URLWithString:[[arrayData objectAtIndex:1] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        if ([[[arrayData objectAtIndex:1] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton2 setHidden:YES];
        }
        
        //Embeded
        if ([[[arrayData objectAtIndex:1] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgView2 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:1] objectForKeyNonNull:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
        
    }
    else
    {
        //[viewMain.view2 setHidden:YES];
        [viewMain.view2.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
        viewMain.imgView2.hidden = NO;
    }
    
    if (arrayData.count >=3)
    {
        [viewMain.btnView3 setTag:startIndex + kArticleButtonOffset + 2];
        
        [viewMain.btnView3 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        
        [viewMain.txtViewDescView3 setText:[[[arrayData objectAtIndex:2] objectForKeyNonNull:@"articleDescPlain"]stringByDecodingXMLEntities]];
        [viewMain.txtViewDescView3 setFont:[UIFont systemFontOfSize:kHeader2]];
        
        [viewMain.lbltitleView3 setText:[[arrayData objectAtIndex:2] objectForKeyNonNull:@"title"]];
        [viewMain.lbltitleView3 setFont:[UIFont systemFontOfSize:kHeader1]];
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData objectAtIndex:2] objectForKeyNonNull:@"createdDate"]];
        [viewMain.lblDateView3 setText:[formatterFinal stringFromDate:dateRec]];
        [viewMain.lblDateView3 setFont:[UIFont systemFontOfSize:kHeader3]];
        
        [viewMain.imgView3 setContentMode:kContentMode];
        NSString *source = [[arrayData objectAtIndex:2] objectForKeyNonNull:@"source"];
        if (source.length > 0) {
            //source = [NSString stringWithFormat:@"Source:%@",source];
            [viewMain.lblUrlView3 setText:source];
            [viewMain.lblUrlView3 setFont:[UIFont systemFontOfSize:kHeader3]];
        }
        else{
            viewMain.lblUrlView3.hidden = YES;
            //      UIImageView *imgGlobe = (UIImageView *)[viewMain.lblUrlView3.superview viewWithTag:10];
            //      imgGlobe.hidden = YES;
            viewMain.imgViewGlobeView3.hidden = YES;
        }
        
        [viewMain.imgView3 setImageWithURL:[NSURL URLWithString:[[arrayData objectAtIndex:2] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        if ([[[arrayData objectAtIndex:2] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton3 setHidden:YES];
        }
        
        //Embeded
        if ([[[arrayData objectAtIndex:2] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgView3 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:2] objectForKeyNonNull:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
        
    }
    else
    {
        //[viewMain.view3 setHidden:YES];
        [viewMain.view3.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
        viewMain.imgView3.hidden = NO;
        
    }
    
    [viewMain.lblAdvertisement setText:[WXHomeViewController languageSelectedStringForKey:@"Advertisement"]];
    
    _imgViewAdvertisement = viewMain.imgViewAdvert;
    _btnAdvImage = viewMain.btnImage;
    _btnAdvVideo = viewMain.btnVideo;
    
    [_btnAdvImage addTarget:self action:@selector(btnAdvImageTapped) forControlEvents:UIControlEventTouchUpInside];
    [_btnAdvVideo addTarget:self action:@selector(btnAdvVideoTapped) forControlEvents:UIControlEventTouchUpInside];
    
    
    [self changeColorToWhiteForAllViewsOf:viewMain];
}

- (void)makeLayoutTypeIpad2:(UIView*)viewContainer withArray:(NSArray*)arrayData onStartIndex:(NSInteger)startIndex
{
    
    layoutName = @"advertisement_layout2";
    WXIPadLayout2Portrait *viewMain = [WXIPadLayout2Portrait loadXIB:@"WXIPadLayout2Portrait" forClass:[WXIPadLayout2Portrait class]];
    
    [viewMain setFrame:viewContainer.frame];
    
    [viewContainer addSubview:viewMain];
    if (arrayData.count >=1)
    {
        [viewMain.btnView1 setTag:startIndex + kArticleButtonOffset];
        [viewMain.btnView1 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        
        [viewMain.txtViewDescView1 setText:[[[arrayData firstObject] objectForKeyNonNull:@"articleDescPlain"]stringByDecodingXMLEntities]];
        [viewMain.txtViewDescView1 setFont:[UIFont systemFontOfSize:kHeader2]];
        
        [viewMain.lblTitleVeiw1 setText:[[arrayData firstObject] objectForKeyNonNull:@"title"]];
        [viewMain.lblTitleVeiw1 setFont:[UIFont systemFontOfSize:kHeader1]];
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData firstObject] objectForKeyNonNull:@"createdDate"]];
        [viewMain.lblDateView1 setText:[formatterFinal stringFromDate:dateRec]];
        [viewMain.lblDateView1 setFont:[UIFont systemFontOfSize:kHeader3]];
        [viewMain.imgView1 setContentMode:kContentMode];
        NSString *source = [[arrayData firstObject] objectForKeyNonNull:@"source"];
        if (source.length > 0) {
            //source = [NSString stringWithFormat:@"Source:%@",source];
            [viewMain.lblUrlView1 setText:source];
            [viewMain.lblUrlView1 setFont:[UIFont systemFontOfSize:kHeader3]];
        }
        else{
            viewMain.lblUrlView1.hidden = YES;
            //      UIImageView *imgGlobe = (UIImageView *)[viewMain.lblUrlView1.superview viewWithTag:10];
            //      imgGlobe.hidden = YES;
            viewMain.imgViewGlobeView1.hidden = YES;
        }
        
        [viewMain.imgView1 setImageWithURL:[NSURL URLWithString:[[arrayData firstObject] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        if ([[[arrayData objectAtIndex:0] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton1 setHidden:YES];
        }
        
        //Embeded
        if ([[[arrayData objectAtIndex:0] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgView1 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:0] objectForKeyNonNull:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
        
        //    [btnView1 setBackgroundColor:[UIColor blueColor]];
    }
    else
    {
        //[viewMain.view1 setHidden:YES];
        [viewMain.view1.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
        viewMain.imgView1.hidden = NO;
    }
    
    [viewMain.lblAdvertisement setText:[WXHomeViewController languageSelectedStringForKey:@"Advertisement"]];
    
    _imgViewAdvertisement = viewMain.imgViewAdvert;
    _btnAdvImage = viewMain.btnImage;
    _btnAdvVideo = viewMain.btnVideo;
    
    [_btnAdvImage addTarget:self action:@selector(btnAdvImageTapped) forControlEvents:UIControlEventTouchUpInside];
    [_btnAdvVideo addTarget:self action:@selector(btnAdvVideoTapped) forControlEvents:UIControlEventTouchUpInside];
    
    
    [self changeColorToWhiteForAllViewsOf:viewMain];
}

- (void)makeLayoutTypeIpad3:(UIView*)viewContainer withArray:(NSArray*)arrayData onStartIndex:(NSInteger)startIndex
{
    
    layoutName = @"advertisement_layout3";
    WXIPadLayout3Portrait *viewMain = [WXIPadLayout3Portrait loadXIB:@"WXIPadLayout3Portrait" forClass:[WXIPadLayout3Portrait class]];
    
    [viewMain setFrame:viewContainer.bounds];
    
    [viewContainer addSubview:viewMain];
    
    if (arrayData.count >=2)
    {
        [viewMain.btnView1 setTag:startIndex + kArticleButtonOffset + 1];
        
        [viewMain.btnView1 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        [viewMain.txtViewDescView1 setText:[[[arrayData objectAtIndex:1] objectForKeyNonNull:@"articleDescPlain"]stringByDecodingXMLEntities]];
        [viewMain.txtViewDescView1 setFont:[UIFont systemFontOfSize:kHeader2]];
        [viewMain.txtViewDescView1 setTextColor:[UIColor whiteColor]];
        
        [viewMain.lbltitleView1 setText:[[arrayData objectAtIndex:1] objectForKeyNonNull:@"title"]];
        [viewMain.lbltitleView1 setFont:[UIFont systemFontOfSize:kHeader1]];
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData objectAtIndex:1] objectForKeyNonNull:@"createdDate"]];
        [viewMain.lbldateView1 setText:[formatterFinal stringFromDate:dateRec]];
        [viewMain.lbldateView1 setFont:[UIFont systemFontOfSize:kHeader3]];
        
        [viewMain.imgView1 setContentMode:kContentMode];
        
        NSString *source = [[arrayData objectAtIndex:1] objectForKeyNonNull:@"source"];
        if (source.length > 0) {
            //source = [NSString stringWithFormat:@"Source:%@",source];
            [viewMain.lblUrlView1 setText:source];
            [viewMain.lblUrlView1 setFont:[UIFont systemFontOfSize:kHeader3]];
        }
        else{
            viewMain.lblUrlView1.hidden = YES;
            //      UIImageView *imgGlobe = (UIImageView *)[viewMain.lblUrlView1.superview viewWithTag:10];
            //      imgGlobe.hidden = YES;
            viewMain.imgViewGlobeView1.hidden = YES;
        }
        
        [viewMain.imgView1 setImageWithURL:[NSURL URLWithString:[[arrayData objectAtIndex:0] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        
        
        if ([[[arrayData objectAtIndex:1] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton1 setHidden:YES];
        }
        
        //Embeded
        if ([[[arrayData objectAtIndex:1] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgView1 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:1] objectForKeyNonNull:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
    }
    else
    {
        //[viewMain.view1 setHidden:YES];
        [viewMain.view1.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
        viewMain.imgView1.hidden = NO;
    }
    
    if (arrayData.count >=4)
    {
        [viewMain.btnView2 setTag:startIndex + kArticleButtonOffset + 3];
        
        [viewMain.btnView2 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        [viewMain.lblTitleView2 setText:[[arrayData objectAtIndex:3] objectForKeyNonNull:@"title"]];
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData objectAtIndex:3] objectForKeyNonNull:@"createdDate"]];
        [viewMain.lblTitleView2 setFont:[UIFont systemFontOfSize:kHeader1]];
        [viewMain.lbldateView2 setText:[formatterFinal stringFromDate:dateRec]];
        [viewMain.lbldateView2 setFont:[UIFont systemFontOfSize:kHeader3]];
        [viewMain.imgView2 setContentMode:kContentMode];
        NSString *source = [[arrayData objectAtIndex:3] objectForKeyNonNull:@"source"];
        if (source.length > 0) {
            //source = [NSString stringWithFormat:@"Source:%@",source];
            [viewMain.lblUrlView2 setText:source];
            [viewMain.lblUrlView2 setFont:[UIFont systemFontOfSize:kHeader3]];
        }
        else{
            viewMain.lblUrlView2.hidden = YES;
            //      UIImageView *imgGlobe = (UIImageView *)[viewMain.lblUrlView2.superview viewWithTag:10];
            //      imgGlobe.hidden = YES;
            viewMain.imgViewGlobeView2.hidden = YES;
        }
        
        
        [viewMain.imgView2 setImageWithURL:[NSURL URLWithString:[[arrayData objectAtIndex:3] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        if ([[[arrayData objectAtIndex:3] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton2 setHidden:YES];
        }
        
        //Embeded
        if ([[[arrayData objectAtIndex:3] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgView2 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:3] objectForKeyNonNull:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
        
    }
    else
    {
        //[viewMain.view2 setHidden:YES];
        [viewMain.view2.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
        viewMain.imgView2.hidden = NO;
    }
    
    
    if (arrayData.count >=1)
    {
        [viewMain.btnView3 setTag:startIndex + kArticleButtonOffset + 0];
        
        [viewMain.btnView3 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        
        [viewMain.txtViewdescView3 setText:[[[arrayData objectAtIndex:0] objectForKeyNonNull:@"articleDescPlain"]stringByDecodingXMLEntities]];
        [viewMain.txtViewdescView3 setFont:[UIFont systemFontOfSize:kHeader2]];
        [viewMain.lblTitleView3 setText:[[arrayData objectAtIndex:0] objectForKeyNonNull:@"title"]];
        [viewMain.lblTitleView3 setFont:[UIFont systemFontOfSize:kHeader1]];
        
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData objectAtIndex:0] objectForKeyNonNull:@"createdDate"]];
        [viewMain.lblDateView3 setText:[formatterFinal stringFromDate:dateRec]];
        [viewMain.lblDateView3 setFont:[UIFont systemFontOfSize:kHeader3]];
        [viewMain.imgView3 setContentMode:kContentMode];
        NSString *source = [[arrayData objectAtIndex:0] objectForKeyNonNull:@"source"];
        if (source.length > 0) {
            //source = [NSString stringWithFormat:@"Source:%@",source];
            [viewMain.lblUrlView3 setText:source];
            [viewMain.lblUrlView3 setFont:[UIFont systemFontOfSize:kHeader3]];
        }
        else{
            viewMain.lblUrlView3.hidden = YES;
            //      UIImageView *imgGlobe = (UIImageView *)[viewMain.lblUrlView3.superview viewWithTag:10];
            //      imgGlobe.hidden = YES;
            viewMain.imgViewGlobeView3.hidden = YES;
        }
        
        [viewMain.imgView3 setImageWithURL:[NSURL URLWithString:[[arrayData objectAtIndex:0] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        
        if ([[[arrayData objectAtIndex:0] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton3 setHidden:YES];
        }
        
        //Embeded
        if ([[[arrayData objectAtIndex:0] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgView3 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:0] objectForKey:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
    }
    else
    {
        //[viewMain.view3 setHidden:YES];
        [viewMain.view3.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
        viewMain.imgView3.hidden = NO;
    }
    
    if (arrayData.count >=3)
    {
        [viewMain.btnView4 setTag:startIndex + kArticleButtonOffset + 2];
        
        [viewMain.btnView4 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        
        [viewMain.txtViewDescView4 setText:[[[arrayData objectAtIndex:2] objectForKeyNonNull:@"articleDescPlain"]stringByDecodingXMLEntities]];
        [viewMain.txtViewDescView4 setFont:[UIFont systemFontOfSize:kHeader2]];
        [viewMain.lblTitleView4 setText:[[arrayData objectAtIndex:2] objectForKeyNonNull:@"title"]];
        [viewMain.lblTitleView4 setFont:[UIFont systemFontOfSize:kHeader1]];
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData objectAtIndex:2] objectForKeyNonNull:@"createdDate"]];
        [viewMain.lbldateView4 setText:[formatterFinal stringFromDate:dateRec]];
        [viewMain.lbldateView4 setFont:[UIFont systemFontOfSize:kHeader3]];
        [viewMain.imgView4 setContentMode:kContentMode];
        NSString *source = [[arrayData objectAtIndex:2] objectForKeyNonNull:@"source"];
        if (source.length > 0) {
            //source = [NSString stringWithFormat:@"Source:%@",source];
            [viewMain.lblurlView4 setText:source];
            [viewMain.lblurlView4 setFont:[UIFont systemFontOfSize:kHeader3]];
        }
        else{
            viewMain.lblurlView4.hidden = YES;
            //      UIImageView *imgGlobe = (UIImageView *)[viewMain.lblurlView4.superview viewWithTag:10];
            //      imgGlobe.hidden = YES;
            viewMain.imgViewGlobeView4.hidden = YES;
        }
        
        [viewMain.imgView4 setImageWithURL:[NSURL URLWithString:[[arrayData objectAtIndex:2] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        if ([[[arrayData objectAtIndex:2] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton4 setHidden:YES];
        }
        
        //Embeded
        if ([[[arrayData objectAtIndex:2] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgView4 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:2] objectForKeyNonNull:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
        
    }
    else
    {
        //[viewMain.view4 setHidden:YES];
        [viewMain.view4.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
        viewMain.imgView4.hidden = NO;
    }
    if (arrayData.count >=5)
    {
        [viewMain.btnView5 setTag:startIndex + kArticleButtonOffset + 4];
        
        [viewMain.btnView5 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        
        [viewMain.lblTitleView5 setText:[[arrayData objectAtIndex:4] objectForKeyNonNull:@"title"]];
        [viewMain.lblTitleView5 setFont:[UIFont systemFontOfSize:kHeader1]];
        
        [viewMain.tctViewDescView5 setText:[[[arrayData objectAtIndex:4] objectForKeyNonNull:@"articleDescPlain"]stringByDecodingXMLEntities]];
        [viewMain.tctViewDescView5 setFont:[UIFont systemFontOfSize:kHeader2]];
        
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData objectAtIndex:4] objectForKeyNonNull:@"createdDate"]];
        [viewMain.lbldateView5 setText:[formatterFinal stringFromDate:dateRec]];
        [viewMain.lbldateView5 setFont:[UIFont systemFontOfSize:kHeader3]];
        [viewMain.imgView5 setContentMode:kContentMode];
        NSString *source = [[arrayData objectAtIndex:4] objectForKeyNonNull:@"source"];
        if (source.length > 0) {
            //source = [NSString stringWithFormat:@"Source:%@",source];
            [viewMain.lblUrlView5 setText:source];
            [viewMain.lblUrlView5 setFont:[UIFont systemFontOfSize:kHeader3]];
        }
        else{
            viewMain.lblUrlView5.hidden = YES;
            //      UIImageView *imgGlobe = (UIImageView *)[viewMain.lblUrlView5.superview viewWithTag:10];
            //      imgGlobe.hidden = YES;
            viewMain.imgViewGlobeView5.hidden = YES;
        }
        
        [viewMain.imgView5 setImageWithURL:[NSURL URLWithString:[[arrayData objectAtIndex:4] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        if ([[[arrayData objectAtIndex:4] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton5 setHidden:YES];
        }
        
        //Embeded
        if ([[[arrayData objectAtIndex:4] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgView5 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:4] objectForKeyNonNull:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
    }
    else
    {
        //[viewMain.view5 setHidden:YES];
        [viewMain.view5.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
        viewMain.imgView5.hidden = NO;
    }
    
    [viewMain.lblAdvertisement setText:[WXHomeViewController languageSelectedStringForKey:@"Advertisement"]];
    
    _imgViewAdvertisement = viewMain.imgViewAdvert;
    _btnAdvImage = viewMain.btnImage;
    _btnAdvVideo = viewMain.btnVideo;
    
    [_btnAdvImage addTarget:self action:@selector(btnAdvImageTapped) forControlEvents:UIControlEventTouchUpInside];
    [_btnAdvVideo addTarget:self action:@selector(btnAdvVideoTapped) forControlEvents:UIControlEventTouchUpInside];
    
    [self changeColorToWhiteForAllViewsOf:viewMain];
}

- (void)makeLayoutTypeIpad4:(UIView*)viewContainer withArray:(NSArray*)arrayData onStartIndex:(NSInteger)startIndex
{
    
    layoutName = @"advertisement_layout4";
    WXIPadLayout4Portrait *viewMain = [WXIPadLayout4Portrait loadXIB:@"WXIPadLayout4Portrait" forClass:[WXIPadLayout4Portrait class]];
    
    [viewMain setFrame:viewContainer.frame];
    
    [viewContainer addSubview:viewMain];
    
    if (arrayData.count >=1)
    {
        [viewMain.btnView1 setTag:startIndex + kArticleButtonOffset];
        
        [viewMain.btnView1 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        
        [viewMain.txtViewdescView1 setText:[[[arrayData objectAtIndex:0] objectForKeyNonNull:@"articleDescPlain"]stringByDecodingXMLEntities]];
        [viewMain.txtViewdescView1 setTextColor:[UIColor whiteColor]];
        
        [viewMain.txtViewdescView1 setFont:[UIFont systemFontOfSize:kHeader2]];
        
        [viewMain.lblTitleView1 setText:[[arrayData objectAtIndex:0] objectForKeyNonNull:@"title"]];
        [viewMain.lblTitleView1 setFont:[UIFont systemFontOfSize:kHeader1]];
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData objectAtIndex:0] objectForKeyNonNull:@"createdDate"]];
        [viewMain.lblDateView1 setText:[formatterFinal stringFromDate:dateRec]];
        [viewMain.lblDateView1 setFont:[UIFont systemFontOfSize:kHeader3]];
        
        [viewMain.imgView1 setContentMode:kContentMode];
        
        NSString *source = [[arrayData objectAtIndex:0] objectForKeyNonNull:@"source"];
        if (source.length > 0) {
            //source = [NSString stringWithFormat:@"Source:%@",source];
            [viewMain.lblUrlView1 setText:source];
            [viewMain.lblUrlView1 setFont:[UIFont systemFontOfSize:kHeader3]];
        }
        else{
            viewMain.lblUrlView1.hidden = YES;
            //      UIImageView *imgGlobe = (UIImageView *)[viewMain.lblUrlView1.superview viewWithTag:10];
            //      imgGlobe.hidden = YES;
            viewMain.imgViewGlobeView1.hidden = YES;
            
        }
        
        [viewMain.imgView1 setImageWithURL:[NSURL URLWithString:[[arrayData objectAtIndex:0] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        
        if ([[[arrayData objectAtIndex:0] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton1 setHidden:YES];
        }
        
        //Embeded
        if ([[[arrayData objectAtIndex:0] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgView1 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:0] objectForKeyNonNull:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
    }
    else
    {
        //[viewMain.view1 setHidden:YES];
        [viewMain.view1.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
        viewMain.imgView1.hidden = NO;
        
    }
    if (arrayData.count >=2)
    {
        [viewMain.btnView2 setTag:startIndex + kArticleButtonOffset + 1];
        
        [viewMain.btnView2 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        
        [viewMain.lblTitleView2 setText:[[arrayData objectAtIndex:1] objectForKeyNonNull:@"title"]];
        [viewMain.lblTitleView2 setFont:[UIFont systemFontOfSize:kHeader1]];
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData objectAtIndex:1] objectForKeyNonNull:@"createdDate"]];
        [viewMain.lblDateView2 setText:[formatterFinal stringFromDate:dateRec]];
        [viewMain.lblDateView2 setFont:[UIFont systemFontOfSize:kHeader3]];
        
        [viewMain.imgView2 setContentMode:kContentMode];
        
        NSString *source = [[arrayData objectAtIndex:1] objectForKeyNonNull:@"source"];
        if (source.length > 0) {
            //source = [NSString stringWithFormat:@"Source:%@",source];
            [viewMain.lblUrlView2 setText:source];
            [viewMain.lblUrlView2 setFont:[UIFont systemFontOfSize:kHeader3]];
        }
        else{
            viewMain.lblUrlView2.hidden = YES;
            //      UIImageView *imgGlobe = (UIImageView *)[viewMain.lblUrlView2.superview viewWithTag:10];
            //      imgGlobe.hidden = YES;
            viewMain.imgViewGlobeView2.hidden = YES;
            
        }
        
        [viewMain.imgView2 setImageWithURL:[NSURL URLWithString:[[arrayData objectAtIndex:1] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        if ([[[arrayData objectAtIndex:1] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton2 setHidden:YES];
        }
        
        //Embeded
        if ([[[arrayData objectAtIndex:1] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgView2 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:1] objectForKeyNonNull:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
    }
    else
    {
        //[viewMain.view2 setHidden:YES];
        [viewMain.view2.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
        viewMain.imgView2.hidden = NO;
    }
    if (arrayData.count >=4)
    {
        [viewMain.btnView3 setTag:startIndex + kArticleButtonOffset + 3];
        
        [viewMain.btnView3 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        [viewMain.lblTitleView3 setText:[[arrayData objectAtIndex:3] objectForKeyNonNull:@"title"]];
        [viewMain.lblTitleView3 setFont:[UIFont systemFontOfSize:kHeader1]];
        [viewMain.txtViewdescView3 setText:[[[arrayData objectAtIndex:3] objectForKeyNonNull:@"articleDescPlain"]stringByDecodingXMLEntities]];
        [viewMain.txtViewdescView3 setFont:[UIFont systemFontOfSize:kHeader2]];
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData objectAtIndex:3] objectForKeyNonNull:@"createdDate"]];
        [viewMain.lblDateView3 setText:[formatterFinal stringFromDate:dateRec]];
        [viewMain.lblDateView3 setFont:[UIFont systemFontOfSize:kHeader3]];
        [viewMain.imgView3 setContentMode:kContentMode];
        NSString *source = [[arrayData objectAtIndex:3] objectForKeyNonNull:@"source"];
        if (source.length > 0) {
            //source = [NSString stringWithFormat:@"Source:%@",source];
            [viewMain.lblUrlView3 setText:source];
            [viewMain.lblUrlView3 setFont:[UIFont systemFontOfSize:kHeader3]];
        }
        else{
            viewMain.lblUrlView3.hidden = YES;
            //      UIImageView *imgGlobe = (UIImageView *)[viewMain.lblUrlView3.superview viewWithTag:10];
            //      imgGlobe.hidden = YES;
            viewMain.imgViewGlobeView3.hidden = YES;
        }
        
        [viewMain.imgView3 setImageWithURL:[NSURL URLWithString:[[arrayData objectAtIndex:3] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        if ([[[arrayData objectAtIndex:3] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton3 setHidden:YES];
        }
        
        //Embeded
        if ([[[arrayData objectAtIndex:3] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgView3 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:3] objectForKeyNonNull:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
    }
    else
    {
        //[viewMain.view3 setHidden:YES];
        [viewMain.view3.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
        viewMain.imgView3.hidden = NO;
    }
    if (arrayData.count >=3)
    {
        [viewMain.btnView4 setTag:startIndex + kArticleButtonOffset + 2];
        
        [viewMain.btnView4 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        
        [viewMain.txtViewdescView4 setText:[[[arrayData objectAtIndex:2] objectForKeyNonNull:@"articleDescPlain"]stringByDecodingXMLEntities]];
        [viewMain.txtViewdescView4 setFont:[UIFont systemFontOfSize:kHeader2]];
        [viewMain.lblTitleView4 setText:[[arrayData objectAtIndex:2] objectForKeyNonNull:@"title"]];
        [viewMain.lblTitleView4 setFont:[UIFont systemFontOfSize:kHeader1]];
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData objectAtIndex:2] objectForKeyNonNull:@"createdDate"]];
        [viewMain.lblDateView4 setText:[formatterFinal stringFromDate:dateRec]];
        [viewMain.lblDateView4 setFont:[UIFont systemFontOfSize:kHeader3]];
        [viewMain.imgView4 setContentMode:kContentMode];
        NSString *source = [[arrayData objectAtIndex:2] objectForKeyNonNull:@"source"];
        if (source.length > 0) {
            //source = [NSString stringWithFormat:@"Source:%@",source];
            [viewMain.lblUrlView4 setText:source];
            [viewMain.lblUrlView4 setFont:[UIFont systemFontOfSize:kHeader3]];
        }
        else{
            viewMain.lblUrlView4.hidden = YES;
            //      UIImageView *imgGlobe = (UIImageView *)[viewMain.lblUrlView4.superview viewWithTag:10];
            //      imgGlobe.hidden = YES;
            viewMain.imgViewGlobeView4.hidden = YES;
        }
        
        [viewMain.imgView4 setImageWithURL:[NSURL URLWithString:[[arrayData objectAtIndex:2] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        if ([[[arrayData objectAtIndex:2] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton4 setHidden:YES];
        }
        
        //Embeded
        if ([[[arrayData objectAtIndex:2] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgView4 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:2] objectForKeyNonNull:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
        
    }
    else
    {
        //[viewMain.view4 setHidden:YES];
        [viewMain.view4.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
        viewMain.imgView4.hidden = NO;
    }
    if (arrayData.count >=6)
    {
        [viewMain.btnView5 setTag:startIndex + kArticleButtonOffset + 5];
        
        [viewMain.btnView5 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        
        [viewMain.txtViewdescView5 setText:[[[arrayData objectAtIndex:5] objectForKeyNonNull:@"articleDescPlain"]stringByDecodingXMLEntities]];
        [viewMain.txtViewdescView5 setFont:[UIFont systemFontOfSize:kHeader2]];
        
        [viewMain.lblTitleView5 setText:[[arrayData objectAtIndex:5] objectForKeyNonNull:@"title"]];
        [viewMain.lblTitleView5 setFont:[UIFont systemFontOfSize:kHeader1]];
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData objectAtIndex:5] objectForKeyNonNull:@"createdDate"]];
        [viewMain.lblDateView5 setText:[formatterFinal stringFromDate:dateRec]];
        [viewMain.lblDateView5 setFont:[UIFont systemFontOfSize:kHeader3]];
        [viewMain.imgView5 setContentMode:kContentMode];
        NSString *source = [[arrayData objectAtIndex:5] objectForKeyNonNull:@"source"];
        if (source.length > 0) {
            //source = [NSString stringWithFormat:@"Source:%@",source];
            [viewMain.lblUrlView5 setText:source];
            [viewMain.lblUrlView5 setFont:[UIFont systemFontOfSize:kHeader3]];
        }
        else{
            viewMain.lblUrlView5.hidden = YES;
            //      UIImageView *imgGlobe = (UIImageView *)[viewMain.lblUrlView5.superview viewWithTag:10];
            //      imgGlobe.hidden = YES;
            viewMain.imgViewGlobeView5.hidden = YES;
        }
        
        [viewMain.imgView5 setImageWithURL:[NSURL URLWithString:[[arrayData objectAtIndex:5] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        if ([[[arrayData objectAtIndex:5] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton5 setHidden:YES];
        }
        
        //Embeded
        if ([[[arrayData objectAtIndex:5] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgView5 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:5] objectForKeyNonNull:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
        
    }
    else
    {
        //[viewMain.view5 setHidden:YES];
        [viewMain.view5.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
        viewMain.imgView5.hidden = NO;
    }
    if (arrayData.count >=5)
    {
        [viewMain.btnView6 setTag:startIndex + kArticleButtonOffset + 4];
        
        [viewMain.btnView6 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        
        [viewMain.txtViewdescView6 setText:[[[arrayData objectAtIndex:4] objectForKeyNonNull:@"articleDescPlain"]stringByDecodingXMLEntities]];
        [viewMain.txtViewdescView6 setFont:[UIFont systemFontOfSize:kHeader2]];
        
        [viewMain.lblTitleView6 setText:[[arrayData objectAtIndex:4] objectForKeyNonNull:@"title"]];
        [viewMain.lblTitleView6 setFont:[UIFont systemFontOfSize:kHeader1]];
        
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData objectAtIndex:4] objectForKeyNonNull:@"createdDate"]];
        [viewMain.lblDateView6 setText:[formatterFinal stringFromDate:dateRec]];
        [viewMain.lblDateView6 setFont:[UIFont systemFontOfSize:kHeader3]];
        [viewMain.imgView6 setContentMode:kContentMode];
        NSString *source = [[arrayData objectAtIndex:4] objectForKeyNonNull:@"source"];
        if (source.length > 0) {
            //source = [NSString stringWithFormat:@"Source:%@",source];
            [viewMain.lblUrlView6 setText:source];
            [viewMain.lblUrlView6 setFont:[UIFont systemFontOfSize:kHeader3]];
        }
        else{
            viewMain.lblUrlView6.hidden = YES;
            //      UIImageView *imgGlobe = (UIImageView *)[viewMain.lblUrlView6.superview viewWithTag:10];
            //      imgGlobe.hidden = YES;
            viewMain.imgViewGlobeView6.hidden = YES;
        }
        
        [viewMain.imgView6 setImageWithURL:[NSURL URLWithString:[[arrayData objectAtIndex:4] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        if ([[[arrayData objectAtIndex:4] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton6 setHidden:YES];
        }
        
        //Embeded
        if ([[[arrayData objectAtIndex:4] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgView5 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:4] objectForKeyNonNull:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
    }
    else
    {
        //[viewMain.view6 setHidden:YES];
        [viewMain.view6.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
        viewMain.imgView6.hidden = NO;
    }
    
    [viewMain.lblAdvertisement setText:[WXHomeViewController languageSelectedStringForKey:@"Advertisement"]];
    
    _imgViewAdvertisement = viewMain.imgViewAdvert;
    _btnAdvImage = viewMain.btnImage;
    _btnAdvVideo = viewMain.btnVideo;
    
    [_btnAdvImage addTarget:self action:@selector(btnAdvImageTapped) forControlEvents:UIControlEventTouchUpInside];
    [_btnAdvVideo addTarget:self action:@selector(btnAdvVideoTapped) forControlEvents:UIControlEventTouchUpInside];
    
    [self changeColorToWhiteForAllViewsOf:viewMain];
}

- (void)makeLayoutTypeIpadLandscape1:(UIView*)viewContainer withArray:(NSArray*)arrayData onStartIndex:(NSInteger)startIndex
{
    layoutName = @"advertisement_layout1";
    WXIPadLayout1 *viewMain = [WXIPadLayout1 loadXIB:@"WXIPadLayout1" forClass:[WXIPadLayout1 class]];
    
    [viewMain setFrame:viewContainer.frame];
    
    [viewContainer addSubview:viewMain];
    if (arrayData.count >=1)
    {
        [viewMain.btnView1 setTag:startIndex + kArticleButtonOffset];
        
        [viewMain.btnView1 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        [viewMain.txtViewDescView1 setText:[[[arrayData objectAtIndex:0] objectForKeyNonNull:@"articleDescPlain"]stringByDecodingXMLEntities]];
        [viewMain.txtViewDescView1 setFont:[UIFont systemFontOfSize:kHeader2]];
        
        
        [viewMain.lblTitleView1 setText:[[arrayData objectAtIndex:0] objectForKeyNonNull:@"title"]];
        [viewMain.lblTitleView1 setFont:[UIFont systemFontOfSize:kHeader1]];
        
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData objectAtIndex:0] objectForKeyNonNull:@"createdDate"]];
        
        [viewMain.lblDateView1 setText:[formatterFinal stringFromDate:dateRec]];
        [viewMain.lblDateView1 setFont:[UIFont systemFontOfSize:kHeader3]];
        [viewMain.imgView1 setContentMode:kContentMode];
        NSString *source = [[arrayData objectAtIndex:0] objectForKeyNonNull:@"source"];
        if (source.length > 0) {
            //source = [NSString stringWithFormat:@"Source:%@",source];
            [viewMain.lblUrlView1 setText:source];
            [viewMain.lblUrlView1 setFont:[UIFont systemFontOfSize:kHeader3]];
        }
        else{
            viewMain.lblUrlView1.hidden = YES;
            //      UIImageView *imgGlobe = (UIImageView *)[viewMain.lblUrlView1.superview viewWithTag:10];
            //      imgGlobe.hidden = YES;
            viewMain.imgViewGlobeView1.hidden = YES;
        }
        
        [viewMain.imgView1 setImageWithURL:[NSURL URLWithString:[[arrayData objectAtIndex:0] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        if ([[[arrayData objectAtIndex:0] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton1 setHidden:YES];
        }
        
        //Embeded
        if ([[[arrayData objectAtIndex:0] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgView1 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:0] objectForKeyNonNull:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
        
    }
    else
    {
        //[viewMain.view1 setHidden:YES];
        [viewMain.view1.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
        viewMain.imgView1.hidden = NO;
    }
    
    if (arrayData.count >=2)
    {
        [viewMain.btnView2 setTag:startIndex + kArticleButtonOffset + 1];
        
        [viewMain.btnView2 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        [viewMain.txtViewDescView2 setText:[[[arrayData objectAtIndex:1] objectForKeyNonNull:@"articleDescPlain"]stringByDecodingXMLEntities]];
        [viewMain.txtViewDescView2 setFont:[UIFont systemFontOfSize:kHeader2]];
        
        [viewMain.lblTitleView2 setText:[[arrayData objectAtIndex:1] objectForKeyNonNull:@"title"]];
        [viewMain.lblTitleView2 setFont:[UIFont systemFontOfSize:kHeader1]];
        
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData objectAtIndex:1] objectForKeyNonNull:@"createdDate"]];
        [viewMain.lblDateView2 setText:[formatterFinal stringFromDate:dateRec]];
        [viewMain.lblDateView2 setFont:[UIFont systemFontOfSize:kHeader3]];
        [viewMain.imgView2 setContentMode:kContentMode];
        NSString *source = [[arrayData objectAtIndex:1] objectForKeyNonNull:@"source"];
        if (source.length > 0) {
            //source = [NSString stringWithFormat:@"Source:%@",source];
            [viewMain.lblUrlView2 setText:source];
            [viewMain.lblUrlView2 setFont:[UIFont systemFontOfSize:kHeader3]];
        }
        else{
            viewMain.lblUrlView2.hidden = YES;
            //      UIImageView *imgGlobe = (UIImageView *)[viewMain.lblUrlView2.superview viewWithTag:10];
            //      imgGlobe.hidden = YES;
            viewMain.imgViewGlobeView2.hidden = YES;
        }
        
        [viewMain.imgView2 setImageWithURL:[NSURL URLWithString:[[arrayData objectAtIndex:1] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        if ([[[arrayData objectAtIndex:1] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton2 setHidden:YES];
        }
        
        //Embeded
        if ([[[arrayData objectAtIndex:1] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgView2 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:1] objectForKeyNonNull:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
        
    }
    else
    {
        //[viewMain.view2 setHidden:YES];
        [viewMain.view2.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
        viewMain.imgView2.hidden = NO;
    }
    
    if (arrayData.count >=3)
    {
        [viewMain.btnView3 setTag:startIndex + kArticleButtonOffset + 2];
        
        [viewMain.btnView3 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        
        [viewMain.txtViewDescView3 setText:[[[arrayData objectAtIndex:2] objectForKeyNonNull:@"articleDescPlain"]stringByDecodingXMLEntities]];
        [viewMain.txtViewDescView3 setFont:[UIFont systemFontOfSize:kHeader2]];
        
        [viewMain.lbltitleView3 setText:[[arrayData objectAtIndex:2] objectForKeyNonNull:@"title"]];
        [viewMain.lbltitleView3 setFont:[UIFont systemFontOfSize:kHeader1]];
        
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData objectAtIndex:2] objectForKeyNonNull:@"createdDate"]];
        [viewMain.lblDateView3 setText:[formatterFinal stringFromDate:dateRec]];
        [viewMain.lblDateView3 setFont:[UIFont systemFontOfSize:kHeader3]];
        [viewMain.imgView3 setContentMode:kContentMode];
        NSString *source = [[arrayData objectAtIndex:2] objectForKeyNonNull:@"source"];
        if (source.length > 0) {
            //source = [NSString stringWithFormat:@"Source:%@",source];
            [viewMain.lblUrlView3 setText:source];
            [viewMain.lblUrlView3 setFont:[UIFont systemFontOfSize:kHeader3]];
        }
        else{
            viewMain.lblUrlView3.hidden = YES;
            //      UIImageView *imgGlobe = (UIImageView *)[viewMain.lblUrlView3.superview viewWithTag:10];
            //      imgGlobe.hidden = YES;
            viewMain.imgViewGlobeView3.hidden = YES;
        }
        
        [viewMain.imgView3 setImageWithURL:[NSURL URLWithString:[[arrayData objectAtIndex:2] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        if ([[[arrayData objectAtIndex:2] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton3 setHidden:YES];
        }
        
        //Embeded
        if ([[[arrayData objectAtIndex:2] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgView3 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:2] objectForKeyNonNull:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
    }
    else
    {
        //[viewMain.view3 setHidden:YES];
        [viewMain.view3.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
        viewMain.imgView3.hidden = NO;
    }
    
    [viewMain.lblAdvertisement setText:[WXHomeViewController languageSelectedStringForKey:@"Advertisement"]];
    
    _imgViewAdvertisement = viewMain.imgViewAdvert;
    _btnAdvImage = viewMain.btnImage;
    _btnAdvVideo = viewMain.btnVideo;
    
    [_btnAdvImage addTarget:self action:@selector(btnAdvImageTapped) forControlEvents:UIControlEventTouchUpInside];
    [_btnAdvVideo addTarget:self action:@selector(btnAdvVideoTapped) forControlEvents:UIControlEventTouchUpInside];
    
    [self changeColorToWhiteForAllViewsOf:viewMain];
}

- (void)makeLayoutTypeIpadLandscape2:(UIView*)viewContainer withArray:(NSArray*)arrayData onStartIndex:(NSInteger)startIndex
{
    layoutName = @"advertisement_layout2";
    WXIPadLayout2 *viewMain = [WXIPadLayout2 loadXIB:@"WXIPadLayout2" forClass:[WXIPadLayout2 class]];
    
    [viewMain setFrame:viewContainer.frame];
    
    [viewContainer addSubview:viewMain];
    if (arrayData.count >=1)
    {
        [viewMain.btnView1 setTag:startIndex + kArticleButtonOffset];
        
        [viewMain.btnView1 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        
        [viewMain.txtViewDescView1 setText:[[[arrayData firstObject] objectForKeyNonNull:@"articleDescPlain"]stringByDecodingXMLEntities]];
        [viewMain.txtViewDescView1 setFont:[UIFont systemFontOfSize:kHeader2]];
        
        [viewMain.lblTitleVeiw1 setText:[[arrayData firstObject] objectForKeyNonNull:@"title"]];
        [viewMain.lblTitleVeiw1 setFont:[UIFont systemFontOfSize:kHeader1]];
        
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData firstObject] objectForKeyNonNull:@"createdDate"]];
        [viewMain.lblDateView1 setText:[formatterFinal stringFromDate:dateRec]];
        [viewMain.lblDateView1 setFont:[UIFont systemFontOfSize:kHeader3]];
        [viewMain.imgView1 setContentMode:kContentMode];
        NSString *source = [[arrayData firstObject] objectForKeyNonNull:@"source"];
        if (source.length > 0) {
            //source = [NSString stringWithFormat:@"Source:%@",source];
            [viewMain.lblUrlView1 setText:source];
            [viewMain.lblUrlView1 setFont:[UIFont systemFontOfSize:kHeader3]];
        }
        else{
            viewMain.lblUrlView1.hidden = YES;
            //      UIImageView *imgGlobe = (UIImageView *)[viewMain.lblUrlView1.superview viewWithTag:10];
            //      imgGlobe.hidden = YES;
            viewMain.imgViewGlobeView1.hidden = YES;
        }
        
        [viewMain.imgView1 setImageWithURL:[NSURL URLWithString:[[arrayData firstObject] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        if ([[[arrayData objectAtIndex:0] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton1 setHidden:YES];
        }
        
        //Embeded
        if ([[[arrayData objectAtIndex:0] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgView1 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:0] objectForKeyNonNull:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
    }
    else
    {
        //[viewMain.view1 setHidden:YES];
        [viewMain.view1.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
        viewMain.imgView1.hidden = NO;
        
    }
    
    [viewMain.lblAdvertisement setText:[WXHomeViewController languageSelectedStringForKey:@"Advertisement"]];
    
    _imgViewAdvertisement = viewMain.imgViewAdvert;
    _btnAdvImage = viewMain.btnImage;
    _btnAdvVideo = viewMain.btnVideo;
    
    [_btnAdvImage addTarget:self action:@selector(btnAdvImageTapped) forControlEvents:UIControlEventTouchUpInside];
    [_btnAdvVideo addTarget:self action:@selector(btnAdvVideoTapped) forControlEvents:UIControlEventTouchUpInside];
    
    [self changeColorToWhiteForAllViewsOf:viewMain];
    
}

- (void)makeLayoutTypeIpadLandscape3:(UIView*)viewContainer withArray:(NSArray*)arrayData onStartIndex:(NSInteger)startIndex
{
    
    layoutName = @"advertisement_layout3";
    WXIPadLayout3 *viewMain = [WXIPadLayout3 loadXIB:@"WXIPadLayout3" forClass:[WXIPadLayout3 class]];
    
    [viewMain setFrame:viewContainer.frame];
    
    [viewContainer addSubview:viewMain];
    
    if (arrayData.count >=2)
    {
        [viewMain.btnView1 setTag:startIndex + kArticleButtonOffset + 1];
        
        [viewMain.btnView1 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        
        [viewMain.txtViewDescView1 setText:[[[arrayData objectAtIndex:1] objectForKeyNonNull:@"articleDescPlain"]stringByDecodingXMLEntities]];
        [viewMain.txtViewDescView1 setFont:[UIFont systemFontOfSize:kHeader2]];
        [viewMain.txtViewDescView1 setTextColor:[UIColor whiteColor]];
        
        [viewMain.lbltitleView1 setText:[[arrayData objectAtIndex:1] objectForKeyNonNull:@"title"]];
        [viewMain.lbltitleView1 setFont:[UIFont systemFontOfSize:kHeader1]];
        
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData objectAtIndex:1] objectForKeyNonNull:@"createdDate"]];
        [viewMain.lbldateView1 setText:[formatterFinal stringFromDate:dateRec]];
        [viewMain.lbldateView1 setFont:[UIFont systemFontOfSize:kHeader3]];
        
        [viewMain.imgView1 setContentMode:kContentMode];
        
        
        NSString *source = [[arrayData objectAtIndex:1] objectForKeyNonNull:@"source"];
        if (source.length > 1) {
            //source = [NSString stringWithFormat:@"Source:%@",source];
            [viewMain.lblUrlView1 setText:source];
            [viewMain.lblUrlView1 setFont:[UIFont systemFontOfSize:kHeader3]];
        }
        else{
            viewMain.lblUrlView1.hidden = YES;
            //      UIImageView *imgGlobe = (UIImageView *)[viewMain.lblUrlView1.superview viewWithTag:10];
            //      imgGlobe.hidden = YES;
            viewMain.imgViewGlobeView1.hidden = YES;
        }
        
        [viewMain.imgView1 setImageWithURL:[NSURL URLWithString:[[arrayData objectAtIndex:0] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        
        if ([[[arrayData objectAtIndex:1] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton1 setHidden:YES];
        }
        
        //Embeded
        if ([[[arrayData objectAtIndex:1] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgView1 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:1] objectForKeyNonNull:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
    }
    else
    {
        //[viewMain.view1 setHidden:YES];
        [viewMain.view1.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
        viewMain.imgView1.hidden = NO;
    }
    
    if (arrayData.count >=4)
    {
        [viewMain.btnView2 setTag:startIndex + kArticleButtonOffset + 3];
        
        [viewMain.btnView2 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        
        [viewMain.lblTitleView2 setText:[[arrayData objectAtIndex:3] objectForKeyNonNull:@"title"]];
        [viewMain.lblTitleView2 setFont:[UIFont systemFontOfSize:kHeader1]];
        
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData objectAtIndex:3] objectForKeyNonNull:@"createdDate"]];
        [viewMain.lbldateView2 setText:[formatterFinal stringFromDate:dateRec]];
        [viewMain.lbldateView2 setFont:[UIFont systemFontOfSize:kHeader3]];
        [viewMain.imgView2 setContentMode:kContentMode];
        NSString *source = [[arrayData objectAtIndex:3] objectForKeyNonNull:@"source"];
        if (source.length > 0) {
            //source = [NSString stringWithFormat:@"Source:%@",source];
            [viewMain.lblUrlView2 setText:source];
            [viewMain.lblUrlView2 setFont:[UIFont systemFontOfSize:kHeader3]];
        }
        else{
            viewMain.lblUrlView2.hidden = YES;
            //      UIImageView *imgGlobe = (UIImageView *)[viewMain.lblUrlView2.superview viewWithTag:10];
            //      imgGlobe.hidden = YES;
            viewMain.imgViewGlobeView2.hidden = YES;
        }
        
        [viewMain.imgView2 setImageWithURL:[NSURL URLWithString:[[arrayData objectAtIndex:3] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        if ([[[arrayData objectAtIndex:3] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton2 setHidden:YES];
        }
        
        //Embeded
        if ([[[arrayData objectAtIndex:3] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgView2 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:3] objectForKeyNonNull:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
    }
    else
    {
        //[viewMain.view2 setHidden:YES];
        [viewMain.view2.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
        viewMain.imgView2.hidden = NO;
        
    }
    if (arrayData.count >=1)
    {
        [viewMain.btnView3 setTag:startIndex + kArticleButtonOffset + 0];
        
        [viewMain.btnView3 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        [viewMain.txtViewdescView3 setText:[[[arrayData objectAtIndex:0] objectForKeyNonNull:@"articleDescPlain"]stringByDecodingXMLEntities]];
        
        [viewMain.txtViewdescView3 setFont:[UIFont systemFontOfSize:kHeader2]];
        
        [viewMain.lblTitleView3 setText:[[arrayData objectAtIndex:0] objectForKeyNonNull:@"title"]];
        [viewMain.lblTitleView3 setFont:[UIFont systemFontOfSize:kHeader1]];
        
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData objectAtIndex:0] objectForKeyNonNull:@"createdDate"]];
        [viewMain.lblDateView3 setText:[formatterFinal stringFromDate:dateRec]];
        [viewMain.lblDateView3 setFont:[UIFont systemFontOfSize:kHeader3]];
        [viewMain.imgView3 setContentMode:kContentMode];
        NSString *source = [[arrayData objectAtIndex:0] objectForKeyNonNull:@"source"];
        if (source.length > 0) {
            //source = [NSString stringWithFormat:@"Source:%@",source];
            [viewMain.lblUrlView3 setText:source];
            [viewMain.lblUrlView3 setFont:[UIFont systemFontOfSize:kHeader3]];
        }
        else{
            viewMain.lblUrlView3.hidden = YES;
            //      UIImageView *imgGlobe = (UIImageView *)[viewMain.lblUrlView3.superview viewWithTag:10];
            //      imgGlobe.hidden = YES;
            viewMain.imgViewGlobeView3.hidden = YES;
        }
        
        [viewMain.imgView3 setImageWithURL:[NSURL URLWithString:[[arrayData objectAtIndex:0] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        if ([[[arrayData objectAtIndex:0] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton3 setHidden:YES];
        }
        
        //Embeded
        if ([[[arrayData objectAtIndex:0] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgView3 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:0] objectForKeyNonNull:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
    }
    else
    {
        //[viewMain.view3 setHidden:YES];
        [viewMain.view3.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
        viewMain.imgView3.hidden = NO;
        
    }
    if (arrayData.count >=3)
    {
        [viewMain.btnView4 setTag:startIndex + kArticleButtonOffset + 2];
        
        [viewMain.btnView4 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        
        [viewMain.txtViewDescView4 setText:[[[arrayData objectAtIndex:2] objectForKeyNonNull:@"articleDescPlain"]stringByDecodingXMLEntities]];
        [viewMain.txtViewDescView4 setFont:[UIFont systemFontOfSize:kHeader2]];
        
        [viewMain.lblTitleView4 setText:[[arrayData objectAtIndex:2] objectForKeyNonNull:@"title"]];
        [viewMain.lblTitleView4 setFont:[UIFont systemFontOfSize:kHeader1]];
        
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData objectAtIndex:2] objectForKeyNonNull:@"createdDate"]];
        [viewMain.lbldateView4 setText:[formatterFinal stringFromDate:dateRec]];
        [viewMain.lbldateView4 setFont:[UIFont systemFontOfSize:kHeader3]];
        [viewMain.imgView4 setContentMode:kContentMode];
        NSString *source = [[arrayData objectAtIndex:2] objectForKeyNonNull:@"source"];
        if (source.length > 0) {
            //source = [NSString stringWithFormat:@"Source:%@",source];
            [viewMain.lblurlView4 setText:source];
            [viewMain.lblurlView4 setFont:[UIFont systemFontOfSize:kHeader3]];
        }
        else{
            viewMain.lblurlView4.hidden = YES;
            //      UIImageView *imgGlobe = (UIImageView *)[viewMain.lblurlView4.superview viewWithTag:10];
            //      imgGlobe.hidden = YES;
            viewMain.imgViewGlobeView4.hidden = YES;
        }
        
        [viewMain.imgView4 setImageWithURL:[NSURL URLWithString:[[arrayData objectAtIndex:2] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        if ([[[arrayData objectAtIndex:2] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton4 setHidden:YES];
        }
        
        //Embeded
        if ([[[arrayData objectAtIndex:2] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgView4 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:2] objectForKeyNonNull:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
    }
    else
    {
        //[viewMain.view4 setHidden:YES];
        [viewMain.view4.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
        viewMain.imgView4.hidden = NO;
    }
    if (arrayData.count >=5)
    {
        [viewMain.btnView5 setTag:startIndex + kArticleButtonOffset + 4];
        
        [viewMain.btnView5 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        
        [viewMain.tctViewDescView5 setText:[[[arrayData objectAtIndex:4] objectForKeyNonNull:@"articleDescPlain"]stringByDecodingXMLEntities]];
        [viewMain.tctViewDescView5 setFont:[UIFont systemFontOfSize:kHeader2]];
        
        [viewMain.lblTitleView5 setText:[[arrayData objectAtIndex:4] objectForKeyNonNull:@"title"]];
        [viewMain.lblTitleView5 setFont:[UIFont systemFontOfSize:kHeader1]];
        
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData objectAtIndex:4] objectForKeyNonNull:@"createdDate"]];
        [viewMain.lbldateView5 setFont:[UIFont systemFontOfSize:kHeader3]];
        
        
        [viewMain.lbldateView5 setText:[formatterFinal stringFromDate:dateRec]];
        [viewMain.imgView5 setContentMode:kContentMode];
        NSString *source = [[arrayData objectAtIndex:4] objectForKeyNonNull:@"source"];
        if (source.length > 0) {
            //source = [NSString stringWithFormat:@"Source:%@",source];
            [viewMain.lblUrlView5 setText:source];
            [viewMain.lblUrlView5 setFont:[UIFont systemFontOfSize:kHeader3]];
        }
        else{
            viewMain.lblUrlView5.hidden = YES;
            //      UIImageView *imgGlobe = (UIImageView *)[viewMain.lblUrlView5.superview viewWithTag:10];
            //      imgGlobe.hidden = YES;
            viewMain.imgViewGlobeView5.hidden = YES;
        }
        
        [viewMain.imgView5 setImageWithURL:[NSURL URLWithString:[[arrayData objectAtIndex:4] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        if ([[[arrayData objectAtIndex:4] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton5 setHidden:YES];
        }
        
        //Embeded
        if ([[[arrayData objectAtIndex:4] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgView5 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:4] objectForKeyNonNull:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
    }
    else
    {
        //[viewMain.view5 setHidden:YES];
        [viewMain.view5.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
        viewMain.imgView5.hidden = NO;
        
    }
    
    [viewMain.lblAdvertisement setText:[WXHomeViewController languageSelectedStringForKey:@"Advertisement"]];
    
    _imgViewAdvertisement = viewMain.imgViewAdvert;
    _btnAdvImage = viewMain.btnImage;
    _btnAdvVideo = viewMain.btnVideo;
    
    [_btnAdvImage addTarget:self action:@selector(btnAdvImageTapped) forControlEvents:UIControlEventTouchUpInside];
    [_btnAdvVideo addTarget:self action:@selector(btnAdvVideoTapped) forControlEvents:UIControlEventTouchUpInside];
    
    [self changeColorToWhiteForAllViewsOf:viewMain];
}

- (void)makeLayoutTypeIpadLandscape4:(UIView*)viewContainer withArray:(NSArray*)arrayData onStartIndex:(NSInteger)startIndex
{
    
    layoutName = @"advertisement_layout4";
    WXIPadLayout4 *viewMain = [WXIPadLayout4 loadXIB:@"WXIPadLayout4" forClass:[WXIPadLayout4 class]];
    
    [viewMain setFrame:viewContainer.frame];
    
    [viewContainer addSubview:viewMain];
    
    if (arrayData.count >=1)
    {
        [viewMain.btnView1 setTag:startIndex + kArticleButtonOffset];
        
        [viewMain.btnView1 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        
        [viewMain.txtViewdescView1 setText:[[[arrayData objectAtIndex:0] objectForKeyNonNull:@"articleDescPlain"]stringByDecodingXMLEntities]];
        [viewMain.txtViewdescView1 setFont:[UIFont systemFontOfSize:kHeader2]];
        [viewMain.txtViewdescView1 setTextColor:[UIColor whiteColor]];
        
        [viewMain.lblTitleView1 setText:[[arrayData objectAtIndex:0] objectForKeyNonNull:@"title"]];
        [viewMain.lblTitleView1 setFont:[UIFont systemFontOfSize:kHeader1]];
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData objectAtIndex:0] objectForKeyNonNull:@"createdDate"]];
        [viewMain.lblDateView1 setText:[formatterFinal stringFromDate:dateRec]];
        [viewMain.lblDateView1 setFont:[UIFont systemFontOfSize:kHeader3]];
        
        NSString *source = [[arrayData objectAtIndex:0] objectForKeyNonNull:@"source"];
        
        [viewMain.imgView1 setContentMode:kContentMode];
        
        
        if (source.length > 0) {
            //source = [NSString stringWithFormat:@"Source:%@",source];
            [viewMain.lblUrlView1 setText:source];
            [viewMain.lblUrlView1 setFont:[UIFont systemFontOfSize:kHeader3]];
        }
        else{
            viewMain.lblUrlView1.hidden = YES;
            //      UIImageView *imgGlobe = (UIImageView *)[viewMain.lblUrlView1.superview viewWithTag:10];
            //      imgGlobe.hidden = YES;
            viewMain.imgViewGlobeView1.hidden = YES;
        }
        
        [viewMain.imgView1 setImageWithURL:[NSURL URLWithString:[[arrayData objectAtIndex:0] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        
        
        if ([[[arrayData objectAtIndex:0] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton1 setHidden:YES];
        }
        
        //Embeded
        if ([[[arrayData objectAtIndex:0] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgView1 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:0] objectForKeyNonNull:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
    }
    else
    {
        //[viewMain.view1 setHidden:YES];
        [viewMain.view1.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
        viewMain.imgView1.hidden = NO;
    }
    
    if (arrayData.count >=2)
    {
        [viewMain.btnView2 setTag:startIndex + kArticleButtonOffset + 1];
        
        [viewMain.btnView2 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        
        [viewMain.lblTitleView2 setText:[[arrayData objectAtIndex:1] objectForKeyNonNull:@"title"]];
        [viewMain.lblTitleView2 setFont:[UIFont systemFontOfSize:kHeader1]];
        
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData objectAtIndex:1] objectForKeyNonNull:@"createdDate"]];
        
        
        [viewMain.lblDateView2 setText:[formatterFinal stringFromDate:dateRec]];
        [viewMain.lblDateView2 setFont:[UIFont systemFontOfSize:kHeader3]];
        [viewMain.imgView2 setContentMode:kContentMode];
        NSString *source = [[arrayData objectAtIndex:1] objectForKeyNonNull:@"source"];
        if (source.length > 0) {
            //source = [NSString stringWithFormat:@"Source:%@",source];
            [viewMain.lblUrlView2 setText:source];
            [viewMain.lblUrlView2 setFont:[UIFont systemFontOfSize:kHeader3]];
        }
        else{
            viewMain.lblUrlView2.hidden = YES;
            //      UIImageView *imgGlobe = (UIImageView *)[viewMain.lblUrlView2.superview viewWithTag:10];
            //      imgGlobe.hidden = YES;
            viewMain.imgViewGlobeView2.hidden = YES;
        }
        
        [viewMain.imgView2 setImageWithURL:[NSURL URLWithString:[[arrayData objectAtIndex:1] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        if ([[[arrayData objectAtIndex:1] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton2 setHidden:YES];
        }
        
        //Embeded
        if ([[[arrayData objectAtIndex:1] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgView2 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:1] objectForKeyNonNull:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
    }
    else
    {
        //[viewMain.view2 setHidden:YES];
        [viewMain.view2.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
        viewMain.imgView2.hidden = NO;
    }
    
    if (arrayData.count >=3)
    {
        [viewMain.btnView3 setTag:startIndex + kArticleButtonOffset + 2];
        
        [viewMain.btnView3 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        
        [viewMain.txtViewdescView3 setText:[[[arrayData objectAtIndex:2] objectForKeyNonNull:@"articleDescPlain"]stringByDecodingXMLEntities]];
        [viewMain.txtViewdescView3 setFont:[UIFont systemFontOfSize:kHeader2]];
        
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData objectAtIndex:2] objectForKeyNonNull:@"createdDate"]];
        [viewMain.lblDateView3 setText:[formatterFinal stringFromDate:dateRec]];
        [viewMain.lblDateView3 setFont:[UIFont systemFontOfSize:kHeader3]];
        
        [viewMain.lblTitleView3 setText:[[arrayData objectAtIndex:2] objectForKeyNonNull:@"title"]];
        [viewMain.lblTitleView3 setFont:[UIFont systemFontOfSize:kHeader1]];
        [viewMain.imgView3 setContentMode:kContentMode];
        NSString *source = [[arrayData objectAtIndex:2] objectForKeyNonNull:@"source"];
        if (source.length > 0) {
            //source = [NSString stringWithFormat:@"Source:%@",source];
            [viewMain.lblUrlView3 setText:source];
            [viewMain.lblUrlView3 setFont:[UIFont systemFontOfSize:kHeader3]];
        }
        else{
            viewMain.lblUrlView3.hidden = YES;
            //      UIImageView *imgGlobe = (UIImageView *)[viewMain.lblUrlView3.superview viewWithTag:10];
            //      imgGlobe.hidden = YES;
            viewMain.imgViewGlobeView3.hidden = YES;
        }
        
        [viewMain.imgView3 setImageWithURL:[NSURL URLWithString:[[arrayData objectAtIndex:2] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        if ([[[arrayData objectAtIndex:2] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton3 setHidden:YES];
        }
        
        //Embeded
        if ([[[arrayData objectAtIndex:2] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgView3 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:2] objectForKeyNonNull:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
    }
    else
    {
        //[viewMain.view3 setHidden:YES];
        [viewMain.view3.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
        viewMain.imgView3.hidden = NO;
    }
    
    if (arrayData.count >=4)
    {
        [viewMain.btnView4 setTag:startIndex + kArticleButtonOffset + 3];
        
        [viewMain.btnView4 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        [viewMain.txtViewdescView4 setText:[[[arrayData objectAtIndex:3] objectForKeyNonNull:@"articleDescPlain"]stringByDecodingXMLEntities]];
        [viewMain.txtViewdescView4 setFont:[UIFont systemFontOfSize:kHeader2]];
        
        [viewMain.lblTitleView4 setText:[[arrayData objectAtIndex:3] objectForKeyNonNull:@"title"]];
        [viewMain.lblTitleView4 setFont:[UIFont systemFontOfSize:kHeader1]];
        
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData objectAtIndex:3] objectForKeyNonNull:@"createdDate"]];
        [viewMain.lblDateView4 setText:[formatterFinal stringFromDate:dateRec]];
        [viewMain.lblDateView4 setFont:[UIFont systemFontOfSize:kHeader3]];
        [viewMain.imgView4 setContentMode:kContentMode];
        NSString *source = [[arrayData objectAtIndex:3] objectForKeyNonNull:@"source"];
        if (source.length > 0) {
            //source = [NSString stringWithFormat:@"Source:%@",source];
            [viewMain.lblUrlView4 setText:source];
            [viewMain.lblUrlView4 setFont:[UIFont systemFontOfSize:kHeader3]];
        }
        else{
            viewMain.lblUrlView4.hidden = YES;
            //      UIImageView *imgGlobe = (UIImageView *)[viewMain.lblUrlView4.superview viewWithTag:10];
            //      imgGlobe.hidden = YES;
            viewMain.imgViewGlobeView4.hidden = YES;
        }
        
        [viewMain.imgView4 setImageWithURL:[NSURL URLWithString:[[arrayData objectAtIndex:3] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        if ([[[arrayData objectAtIndex:3] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton4 setHidden:YES];
        }
        
        //Embeded
        if ([[[arrayData objectAtIndex:3] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgView4 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:3] objectForKeyNonNull:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
    }
    else
    {
        //[viewMain.view4 setHidden:YES];
        [viewMain.view4.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
        viewMain.imgView4.hidden = NO;
        
    }
    if (arrayData.count >=5)
    {
        [viewMain.btnView5 setTag:startIndex + kArticleButtonOffset + 4];
        
        [viewMain.btnView5 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        
        [viewMain.txtViewdescView5 setText:[[[arrayData objectAtIndex:4] objectForKeyNonNull:@"articleDescPlain"]stringByDecodingXMLEntities]];
        [viewMain.txtViewdescView5 setFont:[UIFont systemFontOfSize:kHeader2]];
        
        [viewMain.lblTitleView5 setText:[[arrayData objectAtIndex:4] objectForKeyNonNull:@"title"]];
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData objectAtIndex:4] objectForKeyNonNull:@"createdDate"]];
        [viewMain.lblTitleView5 setFont:[UIFont systemFontOfSize:kHeader1]];
        
        [viewMain.lblDateView5 setText:[formatterFinal stringFromDate:dateRec]];
        [viewMain.lblDateView5 setFont:[UIFont systemFontOfSize:kHeader3]];
        [viewMain.imgView5 setContentMode:kContentMode];
        NSString *source = [[arrayData objectAtIndex:4] objectForKeyNonNull:@"source"];
        if (source.length > 0) {
            //source = [NSString stringWithFormat:@"Source:%@",source];
            [viewMain.lblUrlView5 setText:source];
            [viewMain.lblUrlView5 setFont:[UIFont systemFontOfSize:kHeader3]];
        }
        else{
            viewMain.lblUrlView5.hidden = YES;
            //      UIImageView *imgGlobe = (UIImageView *)[viewMain.lblUrlView5.superview viewWithTag:10];
            //      imgGlobe.hidden = YES;
            viewMain.imgViewGlobeView5.hidden = YES;
        }
        
        [viewMain.imgView5 setImageWithURL:[NSURL URLWithString:[[arrayData objectAtIndex:4] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        if ([[[arrayData objectAtIndex:4] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton5 setHidden:YES];
        }
        
        //Embeded
        if ([[[arrayData objectAtIndex:4] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgView5 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:4] objectForKeyNonNull:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
    }
    else
    {
        //[viewMain.view5 setHidden:YES];
        [viewMain.view5.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
        viewMain.imgView5.hidden = NO;
        
    }
    if (arrayData.count >=6)
    {
        [viewMain.btnView6 setTag:startIndex + kArticleButtonOffset + 5];
        
        [viewMain.btnView6 addTarget:self action:@selector(mainViewTapped:) forControlEvents:UIControlEventTouchUpInside];
        
        [viewMain.txtViewdescView6 setText:[[[arrayData objectAtIndex:5] objectForKeyNonNull:@"articleDescPlain"]stringByDecodingXMLEntities]];
        [viewMain.txtViewdescView6 setFont:[UIFont systemFontOfSize:kHeader2]];
        
        [viewMain.lblTitleView6 setText:[[arrayData objectAtIndex:5] objectForKeyNonNull:@"title"]];
        [viewMain.lblTitleView6 setFont:[UIFont systemFontOfSize:kHeader1]];
        
        NSDate *dateRec = [formatterSource dateFromString:[[arrayData objectAtIndex:5] objectForKeyNonNull:@"createdDate"]];
        [viewMain.lblDateView6 setText:[formatterFinal stringFromDate:dateRec]];
        [viewMain.lblDateView6 setFont:[UIFont systemFontOfSize:kHeader3]];
        [viewMain.imgView6 setContentMode:kContentMode];
        NSString *source = [[arrayData objectAtIndex:5] objectForKeyNonNull:@"source"];
        if (source.length > 0) {
            //source = [NSString stringWithFormat:@"Source:%@",source];
            [viewMain.lblUrlView6 setText:source];
            [viewMain.lblUrlView6 setFont:[UIFont systemFontOfSize:kHeader3]];
        }
        else{
            viewMain.lblUrlView6.hidden = YES;
            //      UIImageView *imgGlobe = (UIImageView *)[viewMain.lblUrlView6.superview viewWithTag:10];
            //      imgGlobe.hidden = YES;
            viewMain.imgViewGlobeView6.hidden = YES;
        }
        
        [viewMain.imgView6 setImageWithURL:[NSURL URLWithString:[[arrayData objectAtIndex:5] objectForKeyNonNull:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        if ([[[arrayData objectAtIndex:5] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
        {
            [viewMain.imgViewPlayButton6 setHidden:YES];
        }
        
        //Embeded
        if ([[[arrayData objectAtIndex:5] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
        {
            [viewMain.imgView6 setImageWithURL:[NSURL encodedURLWithString:[[arrayData objectAtIndex:5] objectForKeyNonNull:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
    }
    else
    {
        //[viewMain.view6 setHidden:YES];
        [viewMain.view6.subviews makeObjectsPerformSelector:@selector(setHidden:) withObject:@YES];
        viewMain.imgView6.hidden = NO;
    }
    
    [viewMain.lblAdvertisement setText:[WXHomeViewController languageSelectedStringForKey:@"Advertisement"]];
    
    _imgViewAdvertisement = viewMain.imgViewAdvert;
    _btnAdvImage = viewMain.btnImage;
    _btnAdvVideo = viewMain.btnVideo;
    
    [_btnAdvImage addTarget:self action:@selector(btnAdvImageTapped) forControlEvents:UIControlEventTouchUpInside];
    [_btnAdvVideo addTarget:self action:@selector(btnAdvVideoTapped) forControlEvents:UIControlEventTouchUpInside];
    
    [self changeColorToWhiteForAllViewsOf:viewMain];
}

#pragma mark - advertisement view

- (void)showAdvertisement
{
    
    if (![dicAdvertisement objectForKey:layoutName])
    {
        return;
    }
    imageCount = 0;
    if (dicAdvertisement)
    {
        arrAdvImages = nil;
        arrAdvImages = [[NSArray alloc] initWithArray:[dicAdvertisement objectForKeyNonNull:layoutName]];
        if ([UIApplication sharedApplication].statusBarOrientation == UIInterfaceOrientationPortrait)
        {
            [_imgViewAdvertisement setImageWithURL:[NSURL URLWithString:[[arrAdvImages objectAtIndex:imageCount] objectForKeyNonNull:@"portraitURL"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
        else
        {
            //[_imgViewAdvertisement setImageWithURL:[NSURL URLWithString:[[arrAdvImages objectAtIndex:imageCount] objectForKeyNonNull:@"landscapeURL"]]];
            [_imgViewAdvertisement setImageWithURL:[NSURL URLWithString:[[arrAdvImages objectAtIndex:imageCount] objectForKeyNonNull:@"landscapeURL"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
        
        if ([[[arrAdvImages objectAtIndex:imageCount] objectForKeyNonNull:@"type"] isEqualToString:@"standard"])
        {
            
            [_btnAdvVideo setHidden:YES];
            [_btnAdvImage setHidden:NO];
        }
        else
        {
            //      [_imgViewAdvertisement setImage:[self getThumbnailForVideoURL:[NSURL URLWithString:[[arrAdvImages objectAtIndex:imageCount] objectForKeyNonNull:@"videoURL"]]]];
            [_imgViewAdvertisement setImageWithURL:[NSURL URLWithString:[[arrAdvImages objectAtIndex:imageCount] objectForKeyNonNull:@"bannerURL"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
            [_btnAdvVideo setHidden:NO];
            [_btnAdvImage setHidden:YES];
        }
        
        [self startTimer];
    }
}

- (void)nextAdvertisement
{
    if (![dicAdvertisement objectForKey:layoutName])
    {
        return;
    }
    imageCount ++;
    
    if (imageCount < arrAdvImages.count)
    {
        if ([UIApplication sharedApplication].statusBarOrientation == UIInterfaceOrientationPortrait)
        {
            [_imgViewAdvertisement setImageWithURL:[NSURL URLWithString:[[arrAdvImages objectAtIndex:imageCount] objectForKeyNonNull:@"portraitURL"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
        else
        {
            [_imgViewAdvertisement setImageWithURL:[NSURL URLWithString:[[arrAdvImages objectAtIndex:imageCount] objectForKeyNonNull:@"landscapeURL"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
        if ([[[arrAdvImages objectAtIndex:imageCount] objectForKeyNonNull:@"type"] isEqualToString:@"standard"])
        {
            
            [_btnAdvVideo setHidden:YES];
            [_btnAdvImage setHidden:NO];
        }
        else
        {
            //      [_imgViewAdvertisement setImage:[self getThumbnailForVideoURL:[NSURL URLWithString:[[arrAdvImages objectAtIndex:imageCount] objectForKeyNonNull:@"videoURL"]]]];
            
            NSString *link = [[[dicAdvertisement objectForKeyNonNull:layoutName] objectAtIndex:imageCount] objectForKeyNonNull:@"videoURL"];
            if ([link rangeOfString:@"iframe"].location != NSNotFound)
            {
                NSURL *url = [NSURL encodedURLWithString:[[arrAdvImages objectAtIndex:imageCount] objectForKeyNonNull:@"videoThumbURL"]];
                [_imgViewAdvertisement setImageWithURL:url placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
            }
            else
            {
                [_imgViewAdvertisement setImageWithURL:[NSURL encodedURLWithString:[[arrAdvImages objectAtIndex:imageCount] objectForKeyNonNull:@"bannerURL"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
            }
            
            
            [_btnAdvVideo setHidden:NO];
            [_btnAdvImage setHidden:YES];
        }
        
        [self startTimer];
    }
    else
    {
        imageCount = 0;
        if ([UIApplication sharedApplication].statusBarOrientation == UIInterfaceOrientationPortrait)
        {
            [_imgViewAdvertisement setImageWithURL:[NSURL encodedURLWithString:[[arrAdvImages objectAtIndex:imageCount] objectForKeyNonNull:@"portraitURL"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
        else
        {
            [_imgViewAdvertisement setImageWithURL:[NSURL encodedURLWithString:[[arrAdvImages objectAtIndex:imageCount] objectForKeyNonNull:@"landscapeURL"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
        }
        if ([[[arrAdvImages objectAtIndex:imageCount] objectForKeyNonNull:@"type"] isEqualToString:@"standard"])
        {
            
            [_btnAdvVideo setHidden:YES];
            [_btnAdvImage setHidden:NO];
        }
        else
        {
            //      [_imgViewAdvertisement setImage:[self getThumbnailForVideoURL:[NSURL URLWithString:[[arrAdvImages objectAtIndex:imageCount] objectForKeyNonNull:@"videoURL"]]]];
            
            NSString *link = [[[dicAdvertisement objectForKeyNonNull:layoutName] objectAtIndex:imageCount] objectForKeyNonNull:@"videoURL"];
            if ([link rangeOfString:@"iframe"].location != NSNotFound)
            {
                [_imgViewAdvertisement setImageWithURL:[NSURL encodedURLWithString:[[arrAdvImages objectAtIndex:imageCount] objectForKeyNonNull:@"videoThumbURL"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
            }
            else
            {
                [_imgViewAdvertisement setImageWithURL:[NSURL encodedURLWithString:[[arrAdvImages objectAtIndex:imageCount] objectForKeyNonNull:@"bannerURL"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
            }
            
            [_btnAdvVideo setHidden:NO];
            [_btnAdvImage setHidden:YES];
        }
        
        [self startTimer];
    }
}

- (void)openAdvertisementImageLink
{
    NSString *link = @"";
    link = [[[dicAdvertisement objectForKey:layoutName] objectAtIndex:imageCount] objectForKeyNonNull:@"linkToOpen"];
    //  [[UIApplication sharedApplication]openURL:[NSURL URLWithString:link]];
    [self callAdvertisementTappedService];
    
    if (link.length > 0)
    {
        viewLoaded = YES;
        WXOpenAdsViewController *openAds = [self.storyboard instantiateViewControllerWithIdentifier:@"WXOpenAdsViewController"];
        [openAds setLinkUrl:link];
        
        [self.navigationController pushViewController:openAds animated:YES];
        
//        SFSafariViewController *safariVc = [[SFSafariViewController alloc]initWithURL:[[NSURL alloc] initWithString:link]];
//        
//        [self presentViewController:safariVc animated:YES completion:nil];
    }
}

- (void)openAdvertisementVideoLink
{
    _player = nil;
    NSString *link = [[[dicAdvertisement objectForKeyNonNull:layoutName] objectAtIndex:imageCount] objectForKeyNonNull:@"videoURL"];
    
    if ([link rangeOfString:@"iframe"].location != NSNotFound)
    {
        //This variable prevents cover Image if any controller presents (video, embeded)
        shouldCoverImageAppear = NO;
        
        WXIFrameVideoPlayerController *iframe = [self.storyboard instantiateViewControllerWithIdentifier:@"WXIFrameVideoPlayerController"];
        iframe.iFrameVideoType = iFrameVideoTypeAdvertisement;
        iframe.dictData = [[dicAdvertisement objectForKeyNonNull:layoutName] objectAtIndex:imageCount];
        [iframe setType:_isMagazine?@"magazine":@"open"];
        [iframe setMagazineId:_isMagazine?[_dictMagazine objectForKeyNonNull:@"magazineId"]:@"-1"];
        UINavigationController *nav = [[UINavigationController alloc] initWithRootViewController:iframe];
        [nav setNavigationBarHidden:YES];
        
        [self presentViewController:nav animated:YES completion:nil];
        
    }
    else
    {
        //This variable prevents cover Image if any controller presents (video, embeded)
        shouldCoverImageAppear = NO;
        
        _player = [[MPMoviePlayerViewController alloc]init];
        [self moviePlayerAddObserver];
        _player.moviePlayer.movieSourceType = MPMovieSourceTypeStreaming;
        _player.moviePlayer.contentURL = [NSURL URLWithString:link];
        _player.moviePlayer.shouldAutoplay = YES;
        //    [self presentMoviePlayerViewControllerAnimated:_player];
        [self presentViewController:_player animated:YES completion:nil];
    }
    
    [self callAdvertisementTappedService];
}

#pragma mark - page flipper methods

- (NSInteger)calculateNumberOfPagesInFlipper
{
    if (isDeviceIPad)
    {
        if (arrayArticles.count % 15 == 0)
        {
            return (arrayArticles.count/15)*4;
        }
        else
        {
            if (arrayArticles.count %15 < 4)
            {
                return (arrayArticles.count/15)*4 + 1;
            }
            else if(arrayArticles.count%15 < 5)
            {
                return (arrayArticles.count/15)*4 + 2;
            }
            else if(arrayArticles.count%15 < 10)
            {
                return (arrayArticles.count/15)*4 + 3;
            }
            else
            {
                return (arrayArticles.count/15)*4 + 4;
            }
        }
    }
    else
    {
        if (arrayArticles.count % 9 == 0)
        {
            return (arrayArticles.count/9)*2;
        }
        else
        {
            if (arrayArticles.count % 9 < 5)
            {
                return (arrayArticles.count/9)*2+1;
            }
            else
            {
                return (arrayArticles.count/9)*2+2;
            }
        }
    }
}

/**
 *  Called whenever page flip action occurs. Created by Teena Nath to resolve crash issue during page flip due to timer
 *
 *  @param action returns the page flip type
 */
-(void)pageFlipActionType:(FlipAction)action
{
    switch (action)
    {
        case FlipStarted:
        {
            [self stopTimer];
        }
            break;
        case FlipEnded:
        {
            [self startTimer];
            
            
        }
            break;
            
        default:
            break;
    }
}


#pragma mark - flipper ipad
- (NSInteger) numberOfPagesForPageFlipper:(AFKPageFlipper *)pageFlipper
{
    return [self calculateNumberOfPagesInFlipper];
}

- (UIView *) viewForPage:(NSInteger)page inFlipper:(AFKPageFlipper *) pageFlipper
{
    NSLog(@"page = %ld",(long)page);
    
    [self switchToPage:page];
    pageNumber = page;
    viewLayout = [[UIView alloc] init];
    @try
    {
        [self makeView:pageNumber];
        //[self performSelector:@selector(makeView:) withObject:nil afterDelay:0.1];
    }
    @catch (NSException *exception)
    {
        
    }
    @finally
    {
        
    }
    
    [self checkScrollButton];
    return viewLayout;
}


#pragma mark - flipper iphone

- (NSInteger) numberOfPagesForPageFlipperIphone:(AFKPageFlipperIphone *)pageFlipper
{
    return [self calculateNumberOfPagesInFlipper];
}

- (UIView *) viewForPage:(NSInteger)page inFlipperIphone:(AFKPageFlipperIphone *) pageFlipper
{
    [self switchToPage:page];
    pageNumber = page;
    viewLayout = [[UIView alloc] init];
    @try
    {
        [self makeView:pageNumber];
        //[self performSelector:@selector(makeView:) withObject:nil afterDelay:0.1];
    }
    @catch (NSException *exception)
    {
        
    }
    @finally
    {
        
    }
    
    [self checkScrollButton];
    return viewLayout;
}
- (void)makeView:(NSInteger)page
{
    
    if (arrayArticles.count == 0)
    {
        return;
    }
    [viewLayout.subviews makeObjectsPerformSelector:@selector(removeFromSuperview)];
    if (isDeviceIPad)
    {
        [viewLayout setFrame:_pageFlipper.bounds];
    }
    else
    {
        [viewLayout setFrame:_pageFlipperIphone.bounds];
    }
    
    NSInteger indexStart;
    NSInteger indexEnd;
    NSArray *tempArray;
    NSInteger divisor;
    if (isDeviceIPad)
    {
        divisor = (page-1)/4; // this calculates the number on which layout is
        if ([UIApplication sharedApplication].statusBarOrientation == UIInterfaceOrientationPortrait)
        {
            if (page % 4 == 1)
            {
                indexStart = divisor*15;
                indexEnd = divisor*15 + 2;
                tempArray = [arrayArticles subarrayWithRange:NSMakeRange(indexStart, arrayArticles.count<=indexEnd?arrayArticles.count-indexStart:3)];
                
                [self makeLayoutTypeIpad1:viewLayout withArray:tempArray onStartIndex:indexStart];
            }
            else if (page % 4 == 2)
            {
                indexStart = 3 + divisor*15;
                //        indexEnd = 4 + (page-1)*15 ;
                //        tempArray = [arrayArticles subarrayWithRange:NSMakeRange(indexStart, arrayArticles.count<=indexEnd?arrayArticles.count-indexStart:1)];
                tempArray = [NSArray arrayWithObjects:[arrayArticles objectAtIndex:indexStart], nil];
                [self makeLayoutTypeIpad2:viewLayout withArray:tempArray onStartIndex:indexStart];
            }
            else if (page % 4 == 3)
            {
                indexStart = 4 + divisor*15;
                indexEnd = 8 + divisor*15 ;
                tempArray = [arrayArticles subarrayWithRange:NSMakeRange(indexStart, arrayArticles.count<=indexEnd?arrayArticles.count-indexStart:5)];
                [self makeLayoutTypeIpad3:viewLayout withArray:tempArray onStartIndex:indexStart];
            }
            else
            {
                indexStart = 9 + divisor*15;
                indexEnd = 14 + divisor*15 ;
                tempArray = [arrayArticles subarrayWithRange:NSMakeRange(indexStart, arrayArticles.count<=indexEnd?arrayArticles.count-indexStart:6)];
                [self makeLayoutTypeIpad4:viewLayout withArray:tempArray onStartIndex:indexStart];
            }
        }
        else
        {
            if (page % 4 == 1)
            {
                indexStart = divisor*15;
                indexEnd = divisor*15 + 2;
                tempArray = [arrayArticles subarrayWithRange:NSMakeRange(indexStart, arrayArticles.count<=indexEnd?arrayArticles.count-indexStart:3)];
                
                [self makeLayoutTypeIpadLandscape1:viewLayout withArray:tempArray onStartIndex:indexStart];
            }
            else if (page % 4 == 2)
            {
                indexStart = 3 + divisor*15;
                //        indexEnd = 4 + (page-1)*15 ;
                //        tempArray = [arrayArticles subarrayWithRange:NSMakeRange(indexStart, arrayArticles.count<=indexEnd?arrayArticles.count-indexStart:1)];
                tempArray = [NSArray arrayWithObjects:[arrayArticles objectAtIndex:indexStart], nil];
                [self makeLayoutTypeIpadLandscape2:viewLayout withArray:tempArray onStartIndex:indexStart];
            }
            else if (page % 4 == 3)
            {
                indexStart = 4 + divisor*15;
                indexEnd = 8 + divisor*15 ;
                tempArray = [arrayArticles subarrayWithRange:NSMakeRange(indexStart, arrayArticles.count<=indexEnd?arrayArticles.count-indexStart:5)];
                [self makeLayoutTypeIpadLandscape3:viewLayout withArray:tempArray onStartIndex:indexStart];
            }
            else
            {
                indexStart = 9 + divisor*15;
                indexEnd = 14 + divisor*15 ;
                tempArray = [arrayArticles subarrayWithRange:NSMakeRange(indexStart, arrayArticles.count<=indexEnd?arrayArticles.count-indexStart:6)];
                [self makeLayoutTypeIpadLandscape4:viewLayout withArray:tempArray onStartIndex:indexStart];
            }
        }
        
        
    }
    else
    {
        divisor = (page-1)/2;
        if (page % 2 == 1)
        {
            indexStart = divisor*9;
            indexEnd = divisor*9 + 3;
            tempArray = [arrayArticles subarrayWithRange:NSMakeRange(indexStart, arrayArticles.count<=indexEnd?arrayArticles.count-indexStart:4)];
            
            [self makeLayoutTypeIphone1:viewLayout withArray:tempArray onStartIndex:indexStart];
        }
        else
        {
            indexStart = 4 + divisor*9;
            indexEnd = 8 + divisor*9 ;
            tempArray = [arrayArticles subarrayWithRange:NSMakeRange(indexStart, arrayArticles.count<=indexEnd?arrayArticles.count-indexStart:5)];
            [self makeLayoutTypeIphone2:viewLayout withArray:tempArray onStartIndex:indexStart];
        }
        
    }
    [self showAdvertisement];
    [viewLayout setBackgroundColor:[UIColor whiteColor]];
    
    
}



#pragma mark - UITableView

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    return arrSearchArticles.count;
}

- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath
{
    return 44;
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    UITableViewCell *cell = nil;
    
    cell = (UITableViewCell *)[[[NSBundle mainBundle] loadNibNamed:@"CellSearch" owner:self options:nil] objectAtIndex:0];
    
    UILabel *articleName = (UILabel*)[cell.contentView viewWithTag:60];
    
    [articleName setText:[[arrSearchArticles objectAtIndex:indexPath.row] objectForKeyNonNull:@"title"]];
    
    return cell;
}
- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
    [_txtFieldSearchArticles resignFirstResponder];
    if ([[[arrSearchArticles objectAtIndex:indexPath.row] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
    {
        viewLoaded = YES;
        loadMore = YES;
        
        NSDictionary *dictData = [arrSearchArticles objectAtIndex:indexPath.row];
        
        //send metric
        [self SendMetrics:dictData];
        
        if ([[dictData objectForKey:@"createdBy"] boolValue] == NO) // source url
        {
            NSString *urlAddress = [[arrSearchArticles objectAtIndex:indexPath.row] objectForKey:@"fullSoruce"];
            
            if ([urlAddress length] > 0)
            {
                SFSafariViewController *safariVc = [[SFSafariViewController alloc]initWithURL:[[NSURL alloc] initWithString:urlAddress]];
                
                [self presentViewController:safariVc animated:YES completion:nil];
            }
        }
        else if ([dictData objectForKey:@"articleDescHTML"] != nil)
        {
            WXArticleDetailViewController *articleViewC = [self.storyboard instantiateViewControllerWithIdentifier:@"WXArticleDetailViewController"];
            [articleViewC setDictData:[arrSearchArticles objectAtIndex:indexPath.row]];
            [articleViewC setType:_isMagazine?@"magazine":@"open"];
            [articleViewC setMagazineId:_isMagazine?[_dictMagazine objectForKeyNonNull:@"magazineId"]:@"-1"];
            [self.navigationController pushViewController:articleViewC animated:YES];
        }
    }
    else if ([[[arrSearchArticles objectAtIndex:indexPath.row] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
    {
        //This variable prevents cover Image if any controller presents (video, embeded)
        shouldCoverImageAppear = NO;
        
        
        
        
        WXIFrameVideoPlayerController *iframe = [self.storyboard instantiateViewControllerWithIdentifier:@"WXIFrameVideoPlayerController"];
        iframe.delegate = self;
        iframe.iFrameVideoType = iFrameVideoTypeArticle;
        iframe.dictData = [arrSearchArticles objectAtIndex:indexPath.row];
        [iframe setType:_isMagazine?@"magazine":@"open"];
        [iframe setMagazineId:_isMagazine?[_dictMagazine objectForKeyNonNull:@"magazineId"]:@"-1"];
        
        UINavigationController *nav = [[UINavigationController alloc] initWithRootViewController:iframe];
        [nav setNavigationBarHidden:YES];
        
        [self presentViewController:nav animated:YES completion:nil];
    }
    else
    {
        NSString *link = [[arrSearchArticles objectAtIndex:indexPath.row] objectForKeyNonNull:@"articleVideoUrl"];
        
        _player = nil;
        _player = [[MPMoviePlayerViewController alloc]init];
        [self moviePlayerAddObserver];
        _player.moviePlayer.movieSourceType = MPMovieSourceTypeStreaming;
        _player.moviePlayer.contentURL = [NSURL URLWithString:link];
        _player.moviePlayer.shouldAutoplay = YES;
        //    [self presentMoviePlayerViewControllerAnimated:_player];
        [self presentViewController:_player animated:YES completion:nil];
    }
    
}
#pragma mark - textfield

- (void)textFieldDidBeginEditing:(UITextField *)textField
{
    [_tblViewSearchResults setHidden:NO];
    [self.view bringSubviewToFront:_tblViewSearchResults];
}

- (void)textFieldDidEndEditing:(UITextField *)textField
{
    [_tblViewSearchResults setHidden:YES];
}

- (BOOL)textFieldShouldReturn:(UITextField *)textField
{
    [textField resignFirstResponder];
    return YES;
}

- (BOOL)textField:(UITextField *)textField shouldChangeCharactersInRange:(NSRange)range replacementString:(NSString *)string
{
    NSString* searchText = [textField.text stringByReplacingCharactersInRange:range withString:string];
    
    searchText = [searchText stringByAddingPercentEscapesUsingEncoding:NSUTF8StringEncoding];
    if (_isMagazine)
    {
        [self callSearchMagazineArticlesServiceWithText:searchText];
    }
    else
    {
        [self callSearchArticlesServiceWithText:searchText];
    }
    return YES;
}

#pragma mark - button action

- (IBAction)btnSearchTapped:(UIButton*)sender
{
    
}

- (IBAction)btnProfileTapped:(UIButton*)sender
{
    
    if (isDeviceIPad)
    {
        viewLoaded = NO;
        WXMenuViewController *menuViewC = [[WXMenuViewController alloc] initWithNibName:@"WXMenuViewIPad" bundle:nil];
        menuViewC.delegate = self;
        _navControllerPopover = nil;
        _navControllerPopover = [[UINavigationController alloc] initWithRootViewController:menuViewC];
        [_navControllerPopover setNavigationBarHidden:YES];
        
        _popOverViewC = [[UIPopoverController alloc] initWithContentViewController:_navControllerPopover];
        
        _popOverViewC.popoverContentSize = CGSizeMake(320, 568);
        [_lblPoint setFrame:CGRectMake(_btnProfile.center.x, _btnProfile.frame.origin.y+_btnProfile.frame.size.height, 1, 1)];
        
        
        [_popOverViewC presentPopoverFromRect:_btnProfile.frame inView:self.view permittedArrowDirections:UIPopoverArrowDirectionUp animated:YES];
    }
    else
    {
        WXMenuViewController *menuViewC = [self.storyboard instantiateViewControllerWithIdentifier:@"WXMenuViewController"];
        viewLoaded = NO;
        [self.navigationController pushViewController:menuViewC animated:YES];
    }
    
}

- (IBAction)btnPrevPageTapped:(UIButton *)sender
{
    
    if (_scrlViewPages.contentOffset.x > 28)
    {
        [_scrlViewPages setContentOffset:CGPointMake(_scrlViewPages.contentOffset.x-28, 0) animated:YES];
    }
}

- (IBAction)btnNextPageTapped:(UIButton *)sender
{
    NSInteger numberOfPages = [self calculateNumberOfPagesInFlipper];
    if (_scrlViewPages.contentOffset.x < 28*(numberOfPages-6))
    {
        [_scrlViewPages setContentOffset:CGPointMake(_scrlViewPages.contentOffset.x+28, 0) animated:YES];
    }
    
}

/**
 *  Selects the page number tapped from the page scroll
 *
 *  @param sender object of button
 */
- (void)btnPageTapped:(UIButton*)sender
{
    for (id tempSender in _scrlViewPages.subviews)
    {
        if ([tempSender isKindOfClass:[UIButton class]])
        {
            UIButton *btnSender = (UIButton*)tempSender;
            [btnSender setSelected:NO];
        }
        
    }
    [sender setSelected:YES];
    
    if (isDeviceIPad)
    {
        [_pageFlipper setCurrentPage:[sender.titleLabel.text intValue] animated:NO];
    }
    else
    {
        [_pageFlipperIphone setCurrentPage:[sender.titleLabel.text intValue] animated:NO];
    }
    
}


- (IBAction)OnHome:(id)sender
{
    [self.navigationController popViewControllerAnimated:true];
}

/**
 * Gets called on tapping an article from the flipper screen
 */
- (void)mainViewTapped:(UIButton *)sender
{
    //  CGRect rect = [sender convertRect:self.view.bounds toView:self.view];
    NSInteger index = sender.tag - kArticleButtonOffset;
    if(![arrayArticles count])
        return;
    
    if ([[[arrayArticles objectAtIndex:index] objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
    {
        viewLoaded = YES;
        loadMore = YES;
        
        NSDictionary *dictData = [arrayArticles objectAtIndex:index];
        
        //send metrics
        [self SendMetrics:dictData];
        
        if ([[dictData objectForKey:@"createdBy"] boolValue] == NO) // source url
        {
            NSString *urlAddress = [[arrayArticles objectAtIndex:index] objectForKey:@"fullSoruce"];
            
            if ([urlAddress length] > 0)
            {
//                SFSafariViewController *safariVc = [[SFSafariViewController alloc]initWithURL:[[NSURL alloc] initWithString:urlAddress]];
//                
//                [self presentViewController:safariVc animated:YES completion:nil];
                [[UIApplication sharedApplication] openURL:[NSURL URLWithString:urlAddress]];
            }
        }else if ([dictData objectForKey:@"articleDescHTML"] != nil)
        {
            WXArticleDetailViewController *articleViewC = [self.storyboard instantiateViewControllerWithIdentifier:@"WXArticleDetailViewController"];
            [articleViewC setDictData:[arrayArticles objectAtIndex:index]];
            [articleViewC setType:_isMagazine?@"magazine":@"open"];
            [articleViewC setMagazineId:_isMagazine?[_dictMagazine objectForKeyNonNull:@"magazineId"]:@"-1"];
            if (_imgViewCustomerLogo.image) {
                articleViewC.imgHeader = _imgViewCustomerLogo.image;
            }
            [self.navigationController pushViewController:articleViewC animated:YES];
        }
    }
    else if ([[[arrayArticles objectAtIndex:index] objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
    {
        //This variable prevents cover Image if any controller presents (video, embeded)
        shouldCoverImageAppear = NO;
        
        
        
        
        WXIFrameVideoPlayerController *iframe = [self.storyboard instantiateViewControllerWithIdentifier:@"WXIFrameVideoPlayerController"];
        iframe.delegate = self;
        iframe.iFrameVideoType = iFrameVideoTypeArticle;
        iframe.dictData = [arrayArticles objectAtIndex:index];
        [iframe setType:_isMagazine?@"magazine":@"open"];
        [iframe setMagazineId:_isMagazine?[_dictMagazine objectForKeyNonNull:@"magazineId"]:@"-1"];
        
        UINavigationController *nav = [[UINavigationController alloc] initWithRootViewController:iframe];
        [nav setNavigationBarHidden:YES];
        
        [self presentViewController:nav animated:YES completion:nil];
    }
    else {
        //This variable prevents cover Image if any controller presents (video, embeded)
        shouldCoverImageAppear = NO;
        
        NSString *link = [[arrayArticles objectAtIndex:index] objectForKeyNonNull:@"articleVideoUrl"];
        
        _player = nil;
        _player = [[MPMoviePlayerViewController alloc]init];
        [self moviePlayerAddObserver];
        _player.moviePlayer.movieSourceType = MPMovieSourceTypeStreaming;
        _player.moviePlayer.contentURL = [NSURL URLWithString:link];
        _player.moviePlayer.shouldAutoplay = YES;
        //    [self presentMoviePlayerViewControllerAnimated:_player];
        [self presentViewController:_player animated:YES completion:nil];
    }
}



- (void)btnAdvImageTapped
{
    [self openAdvertisementImageLink];
}
- (void)btnAdvVideoTapped
{
    [self openAdvertisementVideoLink];
}

- (IBAction)btnRefreshTapped:(id)sender
{
    isRefreshed = YES;
    
    articleLoadPage = 1;
    loadMore = NO;
    [arrayArticles removeAllObjects];
    arrayArticles = nil;
    arrayArticles = [[NSMutableArray alloc] init];
    if (_isMagazine)
    {
        [self callGetMagazinesService];
        [self callAdvertisementMagazineService];
    }
    else
    {
        [self callOpenArticlesWebService];
        [self callAdvertisementOpenService];
    }
}
/**
 *  This methods keeps the selected button in center of the screen and also call the load more service
 */
- (void)checkScrollButton
{
    NSInteger selectedButton = 0;
    for (id tempSender in _scrlViewPages.subviews)
    {
        if ([tempSender isKindOfClass:[UIButton class]])
        {
            UIButton *btnSender = (UIButton*)tempSender;
            if ([btnSender isSelected])
            {
                selectedButton = [btnSender.titleLabel.text integerValue];
                NSLog(@".center:%f",btnSender.center.x);
                NSLog(@".width:%f",_scrlViewPages.bounds.size.width/2);
                if (btnSender.center.x > _scrlViewPages.bounds.size.width/2)
                {
                    [_scrlViewPages setContentOffset:CGPointMake(btnSender.frame.origin.x+btnSender.frame.size.width/2-_scrlViewPages.bounds.size.width/2, _scrlViewPages.contentOffset.y)];
                }
                else
                {
                    [_scrlViewPages setContentOffset:CGPointMake(0, _scrlViewPages.contentOffset.y)];
                }
            }
        }
        
    }
    if (selectedButton > [self calculateNumberOfPagesInFlipper]-2)
    {
        if (loadMore)
        {
            
            articleLoadPage = articleLoadPage + 1;
            storedPageNumber = pageNumber;
            if (_isMagazine)
            {
                [self callGetMagazinesService];
            }
            else
            {
                [self callOpenArticlesWebService];
            }
            loadMore = NO;
        }
    }
}

#pragma mark - iframe delegates
- (void)openMessageFromEmbedwithType:(NSString*)type andData:(NSDictionary*)data
{
    WXCommentViewController *commentViewC = [self.storyboard instantiateViewControllerWithIdentifier:@"WXCommentViewController"];
    [commentViewC setType:type];
    [commentViewC setDictArticleData:data];
    [self.navigationController pushViewController:commentViewC animated:YES];
    
}

#pragma mark - menuview delegate

- (void)logoutAction
{
    [_popOverViewC dismissPopoverAnimated:YES];
    [UserDefaults setBool:NO forKey:kIsLoggedin];
    [self.navigationController popToRootViewControllerAnimated:YES];
}


- (void)magazineTapped:(NSDictionary *)dictData
{
    [_popOverViewC dismissPopoverAnimated:YES];
    self.isMagazine = YES;
    self.dictMagazine = dictData;
    
    [UserDefaults setObject:[dictData objectForKey:@"mobileAppBarColorRGB"] forKey:kMagazineColor];
    [UserDefaults synchronize];
    
    [self viewDidAppear:YES];
}

- (void)openArticleTapped
{
    [_popOverViewC dismissPopoverAnimated:YES];
    self.isMagazine = NO;
    [self viewDidAppear:YES];
}

- (void)cancelTapped
{
    [_popOverViewC dismissPopoverAnimated:YES];
}

- (void)doneTapped
{
    [self viewDidAppear:YES];
    [_popOverViewC dismissPopoverAnimated:YES];
}

#pragma mark - MoviePlayer Observer

- (void)moviePlayerAddObserver
{
    // Remove the movie player view controller from the "playback did finish" notification observers
    [[NSNotificationCenter defaultCenter] removeObserver:_player
                                                    name:MPMoviePlayerPlaybackDidFinishNotification
                                                  object:_player.moviePlayer];
    
    // Register this class as an observer instead
    [[NSNotificationCenter defaultCenter] addObserver:self
                                             selector:@selector(movieFinishedCallback:)
                                                 name:MPMoviePlayerPlaybackDidFinishNotification
                                               object:_player.moviePlayer];
}
- (void)movieFinishedCallback:(NSNotification*)aNotification
{
    // Remove this class from the observers
    [[NSNotificationCenter defaultCenter] removeObserver:self
                                                    name:MPMoviePlayerPlaybackDidFinishNotification
                                                  object:_player.moviePlayer];
    
    [self dismissViewControllerAnimated:YES completion:nil];
    
    // Obtain the reason why the movie playback finished
    /*NSNumber *finishReason = [[aNotification userInfo] objectForKey:MPMoviePlayerPlaybackDidFinishReasonUserInfoKey];
     
     // Dismiss the view controller ONLY when the reason is not "playback ended"
     if ([finishReason intValue] != MPMovieFinishReasonPlaybackEnded)
     {
     MPMoviePlayerController *moviePlayer = [aNotification object];
     
     // Remove this class from the observers
     [[NSNotificationCenter defaultCenter] removeObserver:self
     name:MPMoviePlayerPlaybackDidFinishNotification
     object:moviePlayer];
     
     // Dismiss the view controller
     [self dismissViewControllerAnimated:YES completion:nil];
     }*/
}

#pragma mark - orientation

- (void)orientationChanged:(NSNotification*)userInfo
{
    dispatch_after(dispatch_time(DISPATCH_TIME_NOW, (int64_t)(0.2 * NSEC_PER_SEC)), dispatch_get_main_queue(), ^{
        
        if (isDeviceIPad)
        {
            if ([UIApplication sharedApplication].statusBarOrientation == UIInterfaceOrientationPortrait || [UIApplication sharedApplication].statusBarOrientation == UIInterfaceOrientationPortraitUpsideDown)
            {
                CGRect frame = _scrlViewPages.frame;
                CGPoint center = _scrlViewPages.center;
                frame.size.width = 28*6;
                _scrlViewPages.frame = frame;
                _scrlViewPages.center = center;
                
                frame = _btnNextPage.frame;
                frame.origin.x = _scrlViewPages.frame.origin.x + _scrlViewPages.frame.size.width + 10;
                _btnNextPage.frame = frame;
                
                frame = _btnPreviousPage.frame;
                frame.origin.x = _scrlViewPages.frame.origin.x - 10 - _btnPreviousPage.frame.size.width;
                _btnPreviousPage.frame = frame;
                
                imgCover.height = 1024;
                imgCover.width = 1024;
            }
            else
            {
                CGRect frame = _scrlViewPages.frame;
                CGPoint center = _scrlViewPages.center;
                frame.size.width = 28*10;
                _scrlViewPages.frame = frame;
                _scrlViewPages.center = center;
                
                frame = _btnNextPage.frame;
                frame.origin.x = _scrlViewPages.frame.origin.x + _scrlViewPages.frame.size.width + 10;
                _btnNextPage.frame = frame;
                
                frame = _btnPreviousPage.frame;
                frame.origin.x = _scrlViewPages.frame.origin.x - 10 - _btnPreviousPage.frame.size.width;
                _btnPreviousPage.frame = frame;
                imgCover.height = 1024;
                imgCover.width = 1024;
            }
        }
        
        if (arrayArticles.count > 0)
        {
            [self makeView:pageNumber];
        }
        
        [_popOverViewC dismissPopoverAnimated:YES];
        
        
        //Cover image needs to be readjust in case of iPad as that will have orientation
        if (isDeviceIPad && imgCover.xOrigin == 0) {
            if (isMagazinePhotoShown == YES){
            [self showCoverImageForURL:strCoverImageURL];
            }
           
        }
        
    });
}


#pragma mark - NSNotification


- (void)articleNotification:(NSNotification*)notification
{
    dicOpenArticle = [notification.userInfo mutableCopy];
    if(_btnBack.hidden ==NO){
//        _btnBack.hidden = YES;
        [self setIsMagazine:NO];
        [_imgViewCustomerLogo setHidden:YES];
        [self viewDidAppear:YES];
    }else{
        [self btnRefreshTapped:nil];
    }
    
}

- (void)magazineNotificationToOpenArticle:(NSNotification*)notification
{
    //    openArticleId = [notification.userInfo objectForKey:@"article_id"];
    //    if(_btnBack.hidden ==NO){
    //        _btnBack.hidden = YES;
    //        [self setIsMagazine:YES];
    //        [self viewDidAppear:YES];
    //    }else{
    //        [self btnRefreshTapped:nil];
    //    }
    //to be used
    // dictCloseArticle = [notification.userInfo mutableCopy];
    //test
    //AppDelegate *appDelegate = (AppDelegate *)[[UIApplication sharedApplication] delegate];
    //dictCloseArticle = [appDelegate methodToLoadArticleForCloseMagazine];
    [self setIsMagazine:YES];
    if([[notification.userInfo valueForKey:@"messagetype_sub"]isEqualToString:@"closemagazine"]){
        isMagazinePhotoShown = YES;
        viewLoaded = NO;
    }else
    {
        isMagazinePhotoShown = NO;
        viewLoaded = NO;
    }
    //    if (viewLoaded == YES){
    //        viewLoaded = NO;
    //    }
    int index = [notification.object intValue];
    [self setDictMagazine:[[UserDefaults arrayForKey:@"listOfCloseMagazines"]objectAtIndex:index]];
    [UserDefaults setObject:[[[UserDefaults arrayForKey:@"listOfCloseMagazines"]objectAtIndex:index] objectForKey:@"mobileAppBarColorRGB"] forKey:kMagazineColor];
    [UserDefaults synchronize];
    self.dictCloseArticle = notification.userInfo;
    [self viewDidAppear:YES];
    
}

- (void)openArticleUsingNotification:(NSMutableDictionary *) articleDict
{
    //  CGRect rect = [sender convertRect:self.view.bounds toView:self.view];
    
    if ([[articleDict objectForKeyNonNull:@"articleType"] isEqualToString:@"photo"])
    {
        viewLoaded = YES;
        
        NSDictionary *dictData = articleDict;
        
        //send metrics
        [self SendMetrics:dictData];
        
        if ([[dictData objectForKey:@"createdBy"] boolValue] == NO) // source url
        {
            NSString *urlAddress = [articleDict objectForKey:@"fullSoruce"];
            
            if ([urlAddress length] > 0)
            {
                SFSafariViewController *safariVc = [[SFSafariViewController alloc]initWithURL:[[NSURL alloc] initWithString:urlAddress]];
                
                [self presentViewController:safariVc animated:YES completion:nil];
            }
        }
        else if ([dictData objectForKey:@"articleDescHTML"] != nil)
        {
            WXArticleDetailViewController *articleViewC = [self.storyboard instantiateViewControllerWithIdentifier:@"WXArticleDetailViewController"];
            [articleViewC setDictData:articleDict];
            [articleViewC setType:_isMagazine?@"magazine":@"open"];
            [articleViewC setMagazineId:_isMagazine?[_dictMagazine objectForKeyNonNull:@"magazineId"]:@"-1"];
            if (_imgViewCustomerLogo.image) {
                articleViewC.imgHeader = _imgViewCustomerLogo.image;
            }
            [self.navigationController pushViewController:articleViewC animated:YES];
        }
    }
    else if ([[articleDict objectForKeyNonNull:@"articleType"] isEqualToString:@"embedded"])
    {
        //This variable prevents cover Image if any controller presents (video, embeded)
        shouldCoverImageAppear = NO;
        
        
        
        
        WXIFrameVideoPlayerController *iframe = [self.storyboard instantiateViewControllerWithIdentifier:@"WXIFrameVideoPlayerController"];
        iframe.delegate = self;
        iframe.iFrameVideoType = iFrameVideoTypeArticle;
        iframe.dictData = articleDict;
        [iframe setType:_isMagazine?@"magazine":@"open"];
        [iframe setMagazineId:_isMagazine?[_dictMagazine objectForKeyNonNull:@"magazineId"]:@"-1"];
        
        UINavigationController *nav = [[UINavigationController alloc] initWithRootViewController:iframe];
        [nav setNavigationBarHidden:YES];
        
        [self presentViewController:nav animated:YES completion:nil];
    }
    else {
        //This variable prevents cover Image if any controller presents (video, embeded)
        shouldCoverImageAppear = NO;
        
        NSString *link = [articleDict objectForKeyNonNull:@"articleVideoUrl"];
        
        _player = nil;
        _player = [[MPMoviePlayerViewController alloc]init];
        [self moviePlayerAddObserver];
        _player.moviePlayer.movieSourceType = MPMovieSourceTypeStreaming;
        _player.moviePlayer.contentURL = [NSURL URLWithString:link];
        _player.moviePlayer.shouldAutoplay = YES;
        //    [self presentMoviePlayerViewControllerAnimated:_player];
        [self presentViewController:_player animated:YES completion:nil];
    }
    
    dicOpenArticle = nil;
    dictCloseArticle = nil;
}


- (void)closeArticleNotification:(NSNotification*)notification
{
    //    if (viewLoaded == YES){
    //        viewLoaded = NO;
    //    }
    if([[notification.userInfo valueForKey:@"messagetype_sub"]isEqualToString:@"closemagazine"]){
        isMagazinePhotoShown = YES;
        viewLoaded = NO;
    }else
    {
        isMagazinePhotoShown = NO;
        viewLoaded = NO;
    }
    [self btnRefreshTapped:nil];
    
}

- (void)openAdUsingNotification:(NSString *)link
{
    if (link.length > 0)
    {
        viewLoaded = YES;
        WXOpenAdsViewController *openAds = [self.storyboard instantiateViewControllerWithIdentifier:@"WXOpenAdsViewController"];
        [openAds setLinkUrl:link];
        
        [self.navigationController pushViewController:openAds animated:YES];
        self.openAdvertisementURL = nil;
    }
    
}



- (void) openAdUsingNotificationinHomePage:(NSNotification*)notification
{
    NSString *link= [notification.userInfo objectForKey:@"url"];
    
    if (link.length > 0)
    {
        if([[notification.userInfo valueForKey:@"messagetype_sub"]isEqualToString:@"closemagazine"]){
            isMagazinePhotoShown = YES;
            viewLoaded = NO;
        }else
        {
            isMagazinePhotoShown = NO;
            viewLoaded = NO;
        }
        
        WXOpenAdsViewController *openAds = [self.storyboard instantiateViewControllerWithIdentifier:@"WXOpenAdsViewController"];
        [openAds setLinkUrl:link];
        
        [self.navigationController pushViewController:openAds animated:YES];
    }
    dictCloseArticle = nil;
    
}

- (void) openCloseMagazineAd:(NSDictionary *)notification
{
    NSString *link= [notification objectForKey:@"url"];
    
    if (link.length > 0)
    {
        if([[notification valueForKey:@"messagetype_sub"]isEqualToString:@"closemagazine"]){
            isMagazinePhotoShown = YES;
            viewLoaded = NO;
        }else
        {
            isMagazinePhotoShown = NO;
            viewLoaded = NO;
        }
        
        WXOpenAdsViewController *openAds = [self.storyboard instantiateViewControllerWithIdentifier:@"WXOpenAdsViewController"];
        [openAds setLinkUrl:link];
        
        [self.navigationController pushViewController:openAds animated:YES];
    }
    dictCloseArticle = nil;
    
}



- (void)hidePage:(NSNotification*)userInfo
{
    if ([UserDefaults boolForKey:kHidePage])
    {
        [_viewPageBar setHidden:YES];
    }
    else
    {
        [_viewPageBar setHidden:NO];
    }
    
}

- (void)changeLanguage:(NSNotification*)userInfo
{
    if (_isMagazine)
    {
        [_imgViewCustomerLogo setHidden:NO];
        [_txtFieldSearchArticles setPlaceholder:[WXHomeViewController languageSelectedStringForKey:@"Search Articles"]];
    }
    else
    {
        [_imgViewCustomerLogo setHidden:YES];
        [_txtFieldSearchArticles setPlaceholder:[WXHomeViewController languageSelectedStringForKey:@"Search Articles"]];
    }
}
- (IBAction)backButtonPressed:(UIButton *)sender
{
    //    WXLandingScreenViewController *landingScreen = [self.storyboard instantiateViewControllerWithIdentifier:@"WXLandingScreenViewController"];
    //    [self.navigationController popToViewController:landingScreen animated:YES];
    _isMagazine = NO;
    
    [self reloadTheData];
}

- (void) reloadTheData
{
    loadMore = NO;
    articleLoadPage = 1;
    
    //    if (!viewLoaded)
    //    {
    [self setFontsAndColors];
//    shouldCoverImageAppear = NO;
    
    [self setDateFormat];
    if (isDeviceIPad)
    {
        if (_pageFlipper)
        {
            _pageFlipper.dataSource = self;
        }
    }
    else
    {
        if (_pageFlipperIphone)
        {
            _pageFlipperIphone.dataSource = self;
        }
    }
    //    }
    
}


@end


@implementation WXHomeViewController (WebServiceMethods)

/**
 *  Called on opening the home screen. This method calls the get aricles service to get all the articles from
 *
 
 */
- (void)callOpenArticlesWebService
{
    if(![[NetworkManager manager] isConnectedToWiFi])
    {
        [[[UIAlertView alloc] initWithTitle:kAPPName message:[WXHomeViewController languageSelectedStringForKey:@"Network Not Available"] delegate:nil cancelButtonTitle:[WXHomeViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles: nil] show];
        return;
    }
    [self showProgressHUD:[WXHomeViewController languageSelectedStringForKey:@"Loading..."]];
    self.view.userInteractionEnabled = NO;
    // hardcoded for testing
    
    NSString *articleLanguages = @"";
    if ([UserDefaults objectForKey:kArticleLanguages])
    {
        for (int i = 0; i<[[UserDefaults objectForKey:kArticleLanguages] count]; i++)
        {
            if (i == 0)
            {
                articleLanguages = [NSString stringWithFormat:@"%@",[[UserDefaults objectForKey:kArticleLanguages] firstObject]];
            }
            else
            {
                articleLanguages = [NSString stringWithFormat:@"%@,%@",articleLanguages,[[UserDefaults objectForKey:kArticleLanguages] objectAtIndex:i]];
            }
        }
    }
    
    NSString *savedTopics = @"";
    if ([UserDefaults objectForKey:kSavedTopics])
    {
        for (int i = 0; i<[[UserDefaults objectForKey:kSavedTopics] count]; i++)
        {
            if (i == 0)
            {
                savedTopics = [NSString stringWithFormat:@"%@",[[UserDefaults objectForKey:kSavedTopics] firstObject]];
            }
            else
            {
                savedTopics = [NSString stringWithFormat:@"%@,%@",savedTopics,[[UserDefaults objectForKey:kSavedTopics] objectAtIndex:i]];
            }
        }
    }
//    NSString *fcmDeviceToken;
//    if ([UserDefaults objectForKey:kFirebaseId] == nil){
//        fcmDeviceToken = @"fqASoRlz1hQ:APA91bGb_exXTruv7AtArZA16sfdYcmpPzZJRqlDlKHtDYXn5qGEhYfscRAen7IgbWrD8Xvw7eKCoz3e8Pvl28BFq9jUzHsi849V4z6XMeAaLk7JJQ1funoJmc0PGNSj89fJo0PlktW1";
//    }else{
//        fcmDeviceToken = [UserDefaults objectForKey:kFirebaseId];
//        [myTimer invalidate];
//        myTimer = nil;
//    }

    
    WXOpenArticlesModel *openArticles = [[WXOpenArticlesModel alloc] init];
    [openArticles setDeviceType           :isDeviceIPad?@"tablet":@"mobile"];
    [openArticles setPageNumber           :[NSString stringWithFormat:@"%i",(int)articleLoadPage]];
    [openArticles setTopics               :savedTopics];
    [openArticles setArticleLanguage      :articleLanguages];
//    [openArticles setDeviceId             :fcmDeviceToken];
//    [openArticles setDevice               :@"iPhone"];

    [openArticles setToken                :[UserDefaults objectForKey:kToken]]; //6 Ashok
    [openArticles setAppLanguage          :[UserDefaults objectForKey:kAppLanguage]?[UserDefaults objectForKey:kAppLanguage]:@"en"];
    
    [[NetworkManager manager] requestWithMethod:kHTTPMethodPOST1
                                      URLString:@"getOpenArticles"
                                     parameters:openArticles
                                         sucess:^(id response)
     {
         
         if ([[response objectForKeyNonNull:kSuccess] boolValue] == YES)
         {
             
             if ([[[[response objectForKeyNonNull:kData] firstObject] objectForKeyNonNull:@"openArticles"] count] > 0)
             {
                 loadMore = YES;
                 
                 [arrayArticles addObjectsFromArray:[[[response objectForKeyNonNull:kData] firstObject] objectForKeyNonNull:@"openArticles"]];
             }
             else
             {
                 loadMore = NO;
                 //         [[[UIAlertView alloc] initWithTitle:kAPPName message:@"No articles open" delegate:nil cancelButtonTitle:[WXHomeViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles:nil] show];
                 
             }
             
             //        add dummy data for testing
             
             //       for (int i = 0; i<20; i++)
             //       {
             //         [arrayArticles addObjectsFromArray:[[[response objectForKeyNonNull:kData] firstObject] objectForKeyNonNull:@"openArticles"]];
             //       }
             
             [self makeBottomPageScroll:[self calculateNumberOfPagesInFlipper]];
             [self performSelectorOnMainThread:@selector(addPageFlipperToCurrentView) withObject:nil waitUntilDone:NO];
             

             
             
             if(dicOpenArticle != nil)
             {
                     [self openArticleUsingNotification:[dicOpenArticle mutableCopy]];
             }
             if (openAdvertisementURL != nil)
             {
                 [self openAdUsingNotification:openAdvertisementURL];
             }

             
         }
         else
         {
             [[[UIAlertView alloc] initWithTitle:kAPPName message:[response objectForKeyNonNull:kMessage] delegate:nil cancelButtonTitle:[WXHomeViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles:nil] show];
         }
         [self dismissProgressHUD];
         self.view.userInteractionEnabled = YES;
         
     }failure:^(NSError *error)
     {
         [self dismissProgressHUD];
         self.view.userInteractionEnabled = YES;
     }];
    
    
}

- (void)callSearchArticlesServiceWithText:(NSString*)searchText
{
    //  [self showProgressHUD:@"Loading Articles"];
    
    //  searchText = [searchText stringByReplacingOccurrencesOfString:@" " withString:@"%20"];
    //  searchText = (NSString *)CFBridgingRelease(CFURLCreateStringByAddingPercentEscapes(
    //                                                                                     NULL,
    //                                                                                     (CFStringRef)searchText,
    //                                                                                     NULL,
    //                                                                                     (CFStringRef)@"!*'();:@&=+$,/?%#[]",
    //                                                                                     kCFStringEncodingUTF8 ));
    searchText = [searchText stringByReplacingPercentEscapesUsingEncoding:NSUTF8StringEncoding];
    searchText = [searchText stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceCharacterSet]];
    NSString *articleLanguages = @"";
    if ([UserDefaults objectForKey:kArticleLanguages])
    {
        for (int i = 0; i<[[UserDefaults objectForKey:kArticleLanguages] count]; i++)
        {
            if (i == 0)
            {
                articleLanguages = [NSString stringWithFormat:@"%@",[[UserDefaults objectForKey:kArticleLanguages] firstObject]];
            }
            else
            {
                articleLanguages = [NSString stringWithFormat:@"%@,%@",articleLanguages,[[UserDefaults objectForKey:kArticleLanguages] objectAtIndex:i]];
            }
        }
    }
    
    NSString *savedTopics = @"";
    if ([UserDefaults objectForKey:kSavedTopics])
    {
        for (int i = 0; i<[[UserDefaults objectForKey:kSavedTopics] count]; i++)
        {
            if (i == 0)
            {
                savedTopics = [NSString stringWithFormat:@"%@",[[UserDefaults objectForKey:kSavedTopics] firstObject]];
            }
            else
            {
                savedTopics = [NSString stringWithFormat:@"%@,%@",savedTopics,[[UserDefaults objectForKey:kSavedTopics] objectAtIndex:i]];
            }
        }
    }
    WXSearchArticlesModel *searchArticles = [[WXSearchArticlesModel alloc] init];
    [searchArticles setTopics               :savedTopics];
    [searchArticles setArticleLanguage      :articleLanguages];
    [searchArticles setToken                :[UserDefaults objectForKey:kToken]]; //6 Ashok
    [searchArticles setSearchKeyword        :searchText];
    [searchArticles setApplanguage          :[UserDefaults objectForKey:kAppLanguage]?[UserDefaults objectForKey:kAppLanguage]:@"en"];
    
    [[NetworkManager manager] requestWithMethod:kHTTPMethodPOST1
                                      URLString:@"searchOpen"
                                     parameters:searchArticles
                                         sucess:^(id response)
     {
         
         if ([[response objectForKeyNonNull:kSuccess] boolValue] == YES)
         {
             arrSearchArticles = [[NSMutableArray alloc] initWithArray:[[[response objectForKeyNonNull:kData] firstObject] objectForKeyNonNull:@"openArticles"]];
             [_tblViewSearchResults reloadData];
         }
     }failure:^(NSError *error)
     {
         [self dismissProgressHUD];
     }];
    
}

- (void)callGetMagazinesService
{
    if(![[NetworkManager manager] isConnectedToWiFi])
    {
        [[[UIAlertView alloc] initWithTitle:kAPPName message:[WXHomeViewController languageSelectedStringForKey:@"Network Not Available"] delegate:nil cancelButtonTitle:[WXHomeViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles: nil] show];
        return;
    }
    NSLog(@"callGetMagazinesService");
    [self showProgressHUD:[WXHomeViewController languageSelectedStringForKey:@"Loading..."]];
    self.view.userInteractionEnabled = NO;
    
    NSString *articleLanguages = @"";
    if ([UserDefaults objectForKey:kArticleLanguages])
    {
        for (int i = 0; i<[[UserDefaults objectForKey:kArticleLanguages] count]; i++)
        {
            if (i == 0)
            {
                articleLanguages = [NSString stringWithFormat:@"%@",[[UserDefaults objectForKey:kArticleLanguages] firstObject]];
            }
            else
            {
                articleLanguages = [NSString stringWithFormat:@"%@,%@",articleLanguages,[[UserDefaults objectForKey:kArticleLanguages] objectAtIndex:i]];
            }
        }
    }
    
    NSString *savedTopics = @"";
    if ([UserDefaults objectForKey:kSavedTopics])
    {
        for (int i = 0; i<[[UserDefaults objectForKey:kSavedTopics] count]; i++)
        {
            if (i == 0)
            {
                savedTopics = [NSString stringWithFormat:@"%@",[[UserDefaults objectForKey:kSavedTopics] firstObject]];
            }
            else
            {
                savedTopics = [NSString stringWithFormat:@"%@,%@",savedTopics,[[UserDefaults objectForKey:kSavedTopics] objectAtIndex:i]];
            }
        }
    }
    
    
    WXGetArticlesModel *magazineArticles = [[WXGetArticlesModel alloc] init];
    [magazineArticles setDeviceType           :isDeviceIPad?@"tablet":@"mobile"];
    [magazineArticles setPageNumber           :[NSString stringWithFormat:@"%i",(int)articleLoadPage]];
    [magazineArticles setTopics               :savedTopics];
    [magazineArticles setArticleLanguage      :articleLanguages];
    [magazineArticles setToken                :[UserDefaults objectForKey:kToken]];
    [magazineArticles setMagazineId           :[_dictMagazine objectForKeyNonNull:@"magazineId"]];
    [magazineArticles setAppLanguage          :[UserDefaults objectForKey:kAppLanguage]?[UserDefaults objectForKey:kAppLanguage]:@"en"];
    //6 Ashok
    
    [[NetworkManager manager] requestWithMethod:kHTTPMethodPOST1
                                      URLString:@"getMagazineArticles"
                                     parameters:magazineArticles
                                         sucess:^(id response)
     {
         
         if ([[response objectForKeyNonNull:kSuccess] boolValue] == YES)
         {
             //       if ([[[[response objectForKeyNonNull:kData] firstObject] objectForKeyNonNull:@"magazineArticles"] count] > 0)
             //       {
             //			 if ([[ response objectForKey:kData] firstObject]) {
             //				 <#statements#>
             //			 }
             loadMore = YES;
             if([[[[ response objectForKey:kData] firstObject] objectForKey:@"totalPages"] integerValue] <= 0)
             {
                 loadMore = NO;
             }
             
             if ([[response objectForKeyNonNull:kData] count] > 0) {
                 strCoverImageURL = [[[response objectForKeyNonNull:kData] firstObject] objectForKeyNonNull:@"cover_image"];
                 if (articleLoadPage == 1 && !isRefreshed)
                 {
                     if (isMagazinePhotoShown == YES)
                     [self showCoverImageForURL:strCoverImageURL];
                 }
                 else {
                     isRefreshed = NO;
                 }
             }
             if (arrayArticles.count > 0)
             {
//                 NSMutableArray *unique = [NSMutableArray array];
                 
                 for (id obj in [[[response objectForKeyNonNull:kData] firstObject] objectForKeyNonNull:@"magazineArticles"]) {
                     if (![arrayArticles containsObject:obj]) {
                         [arrayArticles addObject:obj];
                     }
                 }
             }
             else
             {
                 [arrayArticles addObjectsFromArray:[[[response objectForKeyNonNull:kData] firstObject] objectForKeyNonNull:@"magazineArticles"]];
             }
             

             //       }
             if ([[[[response objectForKeyNonNull:kData] firstObject] objectForKeyNonNull:@"magazineArticles"] count] == 0)
             {
                 loadMore = NO;
                 //         [[[UIAlertView alloc] initWithTitle:kAPPName message:@"No article present in this Magazine now." delegate:nil cancelButtonTitle:[WXHomeViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles:nil] show];
                 
             }
             [_imgViewCustomerLogo setImageWithURL:[NSURL URLWithString:[_dictMagazine objectForKeyNonNull:@"customerLogoUrl"]]];
//             [_btnBack setHidden: NO];
             [_img_BackArrow setHidden: NO];
             
             
             //        add dummy data for testing
             
             //       for (int i = 0; i<20; i++)
             //       {
             //         [arrayArticles addObjectsFromArray:[[[response objectForKeyNonNull:kData] firstObject] objectForKeyNonNull:@"magazineArticles"]];
             //       }
             
             [self makeBottomPageScroll:[self calculateNumberOfPagesInFlipper]];
             [self performSelectorOnMainThread:@selector(addPageFlipperToCurrentView) withObject:nil waitUntilDone:YES];
             //test
             if(dictCloseArticle != nil)
             {
                 if([[dictCloseArticle objectForKey:@"messagetype_sub"]isEqualToString:@"close_article"])
                 {
                 [self openArticleUsingNotification:[dictCloseArticle mutableCopy]];
                 }
                 else if([[dictCloseArticle objectForKey:@"messagetype_sub"]isEqualToString:@"close_advertisement"]) {
                     [self openCloseMagazineAd:[dictCloseArticle mutableCopy]];
                 }
             }
             
         }
         else
         {
             [[[UIAlertView alloc] initWithTitle:kAPPName message:[response objectForKeyNonNull:kMessage] delegate:nil cancelButtonTitle:[WXHomeViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles:nil] show];
         }
         [self dismissProgressHUD];
         self.view.userInteractionEnabled = YES;
         
     }failure:^(NSError *error)
     {
         [self dismissProgressHUD];
         self.view.userInteractionEnabled = YES;
     }];
    
    
    
}

- (void)callSearchMagazineArticlesServiceWithText:(NSString*)searchText
{
    //  [self showProgressHUD:@"Loading Articles"];
    
    NSString *articleLanguages = @"";
    if ([UserDefaults objectForKey:kArticleLanguages])
    {
        for (int i = 0; i<[[UserDefaults objectForKey:kArticleLanguages] count]; i++)
        {
            if (i == 0)
            {
                articleLanguages = [NSString stringWithFormat:@"%@",[[UserDefaults objectForKey:kArticleLanguages] firstObject]];
            }
            else
            {
                articleLanguages = [NSString stringWithFormat:@"%@,%@",articleLanguages,[[UserDefaults objectForKey:kArticleLanguages] objectAtIndex:i]];
            }
        }
    }
    
    //  searchText = [searchText stringByReplacingOccurrencesOfString:@" " withString:@"%20"];
    //  searchText = (NSString *)CFBridgingRelease(CFURLCreateStringByAddingPercentEscapes(
    //                                                                                     NULL,
    //                                                                                     (CFStringRef)searchText,
    //                                                                                     NULL,
    //                                                                                     (CFStringRef)@"!*'();:@&=+$,/?%#[]",
    //                                                                                     kCFStringEncodingUTF8 ));
    searchText = [searchText stringByReplacingPercentEscapesUsingEncoding:NSUTF8StringEncoding];
    searchText = [searchText stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceCharacterSet]];
    
    WXSearchMagazineModel *searchArticles = [[WXSearchMagazineModel alloc] init];
    [searchArticles setMagazineId           :[_dictMagazine objectForKeyNonNull:@"magazineId"]];
    [searchArticles setArticleLanguage      :articleLanguages];
    [searchArticles setToken                :[UserDefaults objectForKey:kToken]]; //6 Ashok
    [searchArticles setSearchKeyword        :searchText];
    [searchArticles setApplanguage          :[UserDefaults objectForKey:kAppLanguage]?[UserDefaults objectForKey:kAppLanguage]:@"en"];
    
    
    [[NetworkManager manager] requestWithMethod:kHTTPMethodPOST1
                                      URLString:@"searchMagazine"
                                     parameters:searchArticles
                                         sucess:^(id response)
     {
         
         if ([[response objectForKeyNonNull:kSuccess] boolValue] == YES)
         {
             arrSearchArticles = [[NSMutableArray alloc] initWithArray:[[[response objectForKeyNonNull:kData] firstObject] objectForKeyNonNull:@"openArticles"]];
             [_tblViewSearchResults reloadData];
         }
     }failure:^(NSError *error)
     {
         [self dismissProgressHUD];
     }];
    
}

- (void)callAdvertisementOpenService
{
    if(![[NetworkManager manager] isConnectedToWiFi])
    {
        [[[UIAlertView alloc] initWithTitle:kAPPName message:[WXHomeViewController languageSelectedStringForKey:@"Network Not Available"] delegate:nil cancelButtonTitle:[WXHomeViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles: nil] show];
        return;
    }
    //  [self showProgressHUD:@"Loading Advertisement"];
    
    NSString *savedTopics = @"";
    if ([UserDefaults objectForKey:kSavedTopics])
    {
        for (int i = 0; i<[[UserDefaults objectForKey:kSavedTopics] count]; i++)
        {
            if (i == 0)
            {
                savedTopics = [NSString stringWithFormat:@"%@",[[UserDefaults objectForKey:kSavedTopics] firstObject]];
            }
            else
            {
                savedTopics = [NSString stringWithFormat:@"%@,%@",savedTopics,[[UserDefaults objectForKey:kSavedTopics] objectAtIndex:i]];
            }
        }
    }
    
    WXAdvertisementOpenRequestModel *advertisementOpen = [[WXAdvertisementOpenRequestModel alloc] init];
    [advertisementOpen setPlatform:isDeviceIPad?@"tablet":@"mobile"];
    [advertisementOpen setTopics:savedTopics];
    [advertisementOpen setArticleLanguage:@"en"]; //6 Ashok
    [advertisementOpen setLayoutId:@""];
    [advertisementOpen setApplanguage:[UserDefaults objectForKey:kAppLanguage]?[UserDefaults objectForKey:kAppLanguage]:@"en"];
    
    [[NetworkManager manager] requestWithMethod:kHTTPMethodPOST1
                                      URLString:@"advertisementOpen"
                                     parameters:advertisementOpen
                                         sucess:^(id response)
     {
         dicAdvertisement = nil;
         dicAdvertisement = [[NSDictionary alloc] init];
         if ([[response objectForKeyNonNull:kSuccess] boolValue] == YES)
         {
             if ([[response objectForKeyNonNull:kData] count] > 0)
             {
                 
                 dicAdvertisement = [response objectForKeyNonNull:kData];
                 //[self addPageFlipperToCurrentView];
             }
         }
         
     }failure:^(NSError *error)
     {
         dicAdvertisement = nil;
         dicAdvertisement = [[NSDictionary alloc] init];
     }];
    
}

- (void)callAdvertisementMagazineService
{
    if(![[NetworkManager manager] isConnectedToWiFi])
    {
        [[[UIAlertView alloc] initWithTitle:kAPPName message:[WXHomeViewController languageSelectedStringForKey:@"Network Not Available"] delegate:nil cancelButtonTitle:[WXHomeViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles: nil] show];
        return;
    }
    //  [self showProgressHUD:@"Loading Advertisement"];
    
    NSString *savedTopics = @"";
    if ([UserDefaults objectForKey:kSavedTopics])
    {
        for (int i = 0; i<[[UserDefaults objectForKey:kSavedTopics] count]; i++)
        {
            if (i == 0)
            {
                savedTopics = [NSString stringWithFormat:@"%@",[[UserDefaults objectForKey:kSavedTopics] firstObject]];
            }
            else
            {
                savedTopics = [NSString stringWithFormat:@"%@,%@",savedTopics,[[UserDefaults objectForKey:kSavedTopics] objectAtIndex:i]];
            }
        }
    }
    
    WXAdvertisementMagazineModel *advertisementMagazine = [[WXAdvertisementMagazineModel alloc] init];
    [advertisementMagazine setPlatform:isDeviceIPad?@"mobile":@"mobile"];
    [advertisementMagazine setMagazineId:[_dictMagazine objectForKeyNonNull:@"magazineId"]];
    [advertisementMagazine setArticleLanguage:@"en"]; //6 Ashok
    [advertisementMagazine setLayoutId:@""];
    [advertisementMagazine setApplanguage:[UserDefaults objectForKey:kAppLanguage]?[UserDefaults objectForKey:kAppLanguage]:@"en"];
    
    [[NetworkManager manager] requestWithMethod:kHTTPMethodPOST1
                                      URLString:@"advertisementMagazine"
                                     parameters:advertisementMagazine
                                         sucess:^(id response)
     {
         dicAdvertisement = nil;
         dicAdvertisement = [[NSDictionary alloc] init];
         if ([[response objectForKeyNonNull:kSuccess] boolValue] == YES)
         {
             if ([[response objectForKeyNonNull:kData] count] > 0)
             {
                 dicAdvertisement = [response objectForKeyNonNull:kData];
                 NSLog(@"advertisement:%@",dicAdvertisement);
                 //[self addPageFlipperToCurrentView];
             }
         }
         
     }failure:^(NSError *error)
     {
         dicAdvertisement = nil;
         dicAdvertisement = [[NSDictionary alloc] init];
         
     }];
    
}

- (void)callAdvertisementTappedService
{
    if(![[NetworkManager manager] isConnectedToWiFi])
    {
        [[[UIAlertView alloc] initWithTitle:kAPPName message:[WXHomeViewController languageSelectedStringForKey:@"Network Not Available"] delegate:nil cancelButtonTitle:[WXHomeViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles: nil] show];
        return;
    }
    
    AppDelegate *delegate = (AppDelegate *)[[UIApplication sharedApplication] delegate];
    
    WXAdsReportModel *adsReport = [[WXAdsReportModel alloc] init];
    [adsReport setDevice:@"iPhone"];
    [adsReport setType:_isMagazine?@"magazine":@"open"];
    [adsReport setLatitude:delegate.latString.length > 0 ? delegate.latString : @""]; //6 Ashok
    [adsReport setLongitude:delegate.longString.length > 0 ? delegate.longString : @""];
    [adsReport setToken:[UserDefaults objectForKey:kToken]];
    [adsReport setApplanguage:[UserDefaults objectForKey:kAppLanguage]?[UserDefaults objectForKey:kAppLanguage]:@"en"];
    [adsReport setAdvertisementId:[[arrAdvImages objectAtIndex:imageCount] objectForKeyNonNull:@"advertisementId"]];
    
    [[NetworkManager manager] requestWithMethod:kHTTPMethodPOST1
                                      URLString:@"adsReport"
                                     parameters:adsReport
                                         sucess:^(id response)
     {
         
         if ([[response objectForKeyNonNull:kSuccess] boolValue] == YES)
         {
             if ([[response objectForKeyNonNull:kData] count] > 0)
             {
                 NSLog(@"re:%@",response);
             }
         }
         
     }failure:^(NSError *error)
     {
         
     }];
    
}



@end
