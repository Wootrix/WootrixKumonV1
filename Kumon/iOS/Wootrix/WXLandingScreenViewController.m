//
//  WXLandingScreenViewController.m
//  Wootrix
//
//  Created by Mayank Pahuja on 29/12/14.
//  Copyright (c) 2014 Techahead. All rights reserved.
//

#import "WXLandingScreenViewController.h"
#import "WXHomeViewController.h"
#import "NSString+XMLEntities.h"
#import "NSString+HTML.h"
#import "WXDeleteMagazine.h"
#define kButtonTagOffset 10000



#define ITEM_HEIGHT 280.0
#define ITEM_WIDTH 190.0
#define TOTAL_PADDING 5.0

@interface WXLandingScreenViewController ()
{
    WXDeleteMagazine *deleteMagazines;
    int buttonId;
    NSTimer *myTimer;
    CGSize _screenSize;
    CGFloat _itemWidth;
    CGFloat _itemHeight;

}
@property (strong, nonatomic) IBOutlet UIImageView *imgWebUrlIcon;

@end

@interface WXLandingScreenViewController (WebserviceMethods)


- (void)callLandingScreenWebservice;
- (void)callOpenArticlesWebService;
- (void)callDeleteMagazinesWebService;

@end


@implementation WXLandingScreenViewController
@synthesize closedMagazineId;

#pragma mark - view life cycle
- (void)viewDidLoad
{
    [super viewDidLoad];
    NSLog(@"from background closed article completely opened");
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(orientationChangedLanding:) name:UIDeviceOrientationDidChangeNotification object:nil];
    [[NSNotificationCenter defaultCenter]removeObserver:self name:@"openCloseArticleThroughNotification" object:nil];
    [[NSNotificationCenter defaultCenter]addObserver:self selector:@selector(openClosedMagazine:) name:@"openCloseArticleThroughNotification" object:nil];
    //    landingQueue = dispatch_queue_create(class_getName([WXLandingScreenViewController class]), NULL);
    //		landingQueueTag = &landingQueue;
    //		dispatch_queue_set_specific(landingQueue, landingQueueTag, landingQueueTag, NULL);
    
    // Do any additional setup after loading the view.
    if ([UserDefaults objectForKey:kFirebaseId] != nil &&![[UserDefaults objectForKey:kFirebaseId] isEqual:@"fqASoRlz1hQ:APA91bGb_exXTruv7AtArZA16sfdYcmpPzZJRqlDlKHtDYXn5qGEhYfscRAen7IgbWrD8Xvw7eKCoz3e8Pvl28BFq9jUzHsi849V4z6XMeAaLk7JJQ1funoJmc0PGNSj89fJo0PlktW1"]){
        myTimer = [NSTimer scheduledTimerWithTimeInterval:2.0
                                                   target:self
                                                 selector:@selector(targetMethod:)
                                                 userInfo:nil
                                                  repeats:YES];
    }
    
    _screenSize = [UIScreen mainScreen].bounds.size;
    _itemWidth = (_screenSize.width - (TOTAL_PADDING *  (isDeviceIPad ? 5 : 3))) / (isDeviceIPad ? 4 : 2);
    _itemHeight = (ITEM_HEIGHT * _itemWidth) / ITEM_WIDTH;
}

-(void)targetMethod:(NSTimer*)timer
{
    [[NSNotificationCenter defaultCenter]postNotificationName:@"tokenRefreshNotification" object:nil];
    
    NSString *fcmDeviceToken;
    NSString * str = [UserDefaults objectForKey:kFirebaseId];
    NSLog(@"string%@",str);
    if([UserDefaults objectForKey:kFirebaseId] != nil)
    {
        [myTimer invalidate];
        myTimer = nil;
    }
    if ([UserDefaults objectForKey:kFirebaseId] != nil &&![[UserDefaults objectForKey:kFirebaseId] isEqual:@"fqASoRlz1hQ:APA91bGb_exXTruv7AtArZA16sfdYcmpPzZJRqlDlKHtDYXn5qGEhYfscRAen7IgbWrD8Xvw7eKCoz3e8Pvl28BFq9jUzHsi849V4z6XMeAaLk7JJQ1funoJmc0PGNSj89fJo0PlktW1"]){
        
        if ([UserDefaults boolForKey:kIsLoggedin])
        {
            
            [myTimer invalidate];
            myTimer = nil;
            
        }
    }
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (void)viewDidAppear:(BOOL)animated
{
    [self setFontsAndColors];
    
}


- (void)orientationChangedLanding:(NSNotification*)userInfo
{
    dispatch_after(dispatch_time(DISPATCH_TIME_NOW, (int64_t)(0.2 * NSEC_PER_SEC)), dispatch_get_main_queue(), ^{
        
        if (isDeviceIPad)
        {
            if ([UIApplication sharedApplication].statusBarOrientation == UIInterfaceOrientationPortrait || [UIApplication sharedApplication].statusBarOrientation == UIInterfaceOrientationPortraitUpsideDown)
            {
                _viewTextLeadingConstraint.constant = 0;
                _viewTextTopCOnstraint.constant = 0;
                _imgViewHeightConstraint.constant = 384;
                _imgViewWidthCOnstraint.constant = 768;
                
                
                [_viewTextContainer layoutIfNeeded];
                [_imgViewArticle layoutIfNeeded];
            }
            else
            {
                _viewTextLeadingConstraint.constant = 500;
                _viewTextTopCOnstraint.constant = -400;
                [_viewTextContainer layoutIfNeeded];
                
                _imgViewHeightConstraint.constant = 400;
                _imgViewWidthCOnstraint.constant = 500;
                [_imgViewArticle layoutIfNeeded];
                
            }
            
            if ([[_dictData objectForKey:@"embedded_thumbnail"] length] > 0)
            {
                UIImageView *imgViewPlay = (UIImageView*)[self.view viewWithTag:11001];
                [imgViewPlay removeFromSuperview];
                imgViewPlay = nil;
                UIImageView *imgViewPlayIcon = [[UIImageView alloc] initWithFrame:_imgViewArticle.frame];
                [imgViewPlayIcon setImage:[UIImage imageNamed:@"icon_play"]];
                [imgViewPlayIcon setContentMode:UIViewContentModeCenter];
                [imgViewPlayIcon setUserInteractionEnabled:NO];
                [imgViewPlayIcon setBackgroundColor:[UIColor colorWithRed:0 green:0 blue:0 alpha:0.5]];
                [imgViewPlayIcon setTag:11001];
                [self.view addSubview:imgViewPlayIcon];
            }
        }
    });
}

#pragma mark - setup view

/**
 *  This method is called to set the custom font sizes and colors of all objects present on the view
 */
- (void)setFontsAndColors
{
    dispatch_after(dispatch_time(DISPATCH_TIME_NOW, (int64_t)(0.2 * NSEC_PER_SEC)), dispatch_get_main_queue(), ^{
        
        if (isDeviceIPad)
        {
            if ([UIApplication sharedApplication].statusBarOrientation == UIInterfaceOrientationPortrait || [UIApplication sharedApplication].statusBarOrientation == UIInterfaceOrientationPortraitUpsideDown)
            {
                _viewTextLeadingConstraint.constant = 0;
                _viewTextTopCOnstraint.constant = 0;
                _imgViewHeightConstraint.constant = 384;
                _imgViewWidthCOnstraint.constant = 768;
                
                [_viewTextContainer layoutIfNeeded];
                [_imgViewArticle layoutIfNeeded];
            }
            else
            {
                _viewTextLeadingConstraint.constant = 500;
                _viewTextTopCOnstraint.constant = -400;
                [_viewTextContainer layoutIfNeeded];
                
                _imgViewHeightConstraint.constant = 400;
                _imgViewWidthCOnstraint.constant = 500;
                [_imgViewArticle layoutIfNeeded];
                
            }
        }
    });
    
    
    
    [_lblMagazine setText:[WXLandingScreenViewController languageSelectedStringForKey:@"Magazines"]];
    
    [_lblOpenArticle setText:[WXLandingScreenViewController languageSelectedStringForKey:@"Open Article"]];
    

    [self callLandingScreenWebservice];
    
}

/**
 *  Makes the scrollview of magazinr based on number of magazines in the container
 */
- (void)makeMagazineView
{
    
    for (UIView *view in _scrlViewMagazines.subviews) {
        [view removeFromSuperview];
    }
    [_scrlViewMagazines setContentSize:CGSizeMake(0, _scrlViewMagazines.frame.size.height)];
    
    CGFloat heightMagazine = _scrlViewMagazines.frame.size.height - _scrlViewMagazines.frame.size.height/10;
    //  CGFloat widthMagazine = (_scrlViewMagazines.frame.size.width - _scrlViewMagazines.frame.size.width/5)/3;
    CGFloat widthMagazine = heightMagazine*3/4;
    CGFloat xMargin = _scrlViewMagazines.frame.size.width/20;
    CGFloat yMargin = _scrlViewMagazines.frame.size.height/20;
    
    //  UIButton *btnMagazine = [UIButton buttonWithType:UIButtonTypeCustom];
    //  [btnMagazine setFrame:CGRectMake(0+ xMargin , yMargin, widthMagazine, heightMagazine)];
    //
    //  [btnMagazine setImage:[UIImage imageNamed:@"PlaceHolderMagazine.png"] forState:UIControlStateNormal];
    //  [btnMagazine addTarget:self action:@selector(btnAddMagazineTapped:) forControlEvents:UIControlEventTouchUpInside];
    //
    //  [_scrlViewMagazines addSubview:btnMagazine];
    
    for (int index = 0; index < [arrMagazines count]; index++)
    {
        UIView *magazineView = [[UIView alloc] initWithFrame:CGRectMake((widthMagazine*index)+ xMargin*(index+1), yMargin, widthMagazine, heightMagazine)];
        
        UIButton *btnMagazine = [UIButton buttonWithType:UIButtonTypeCustom];
        [btnMagazine setFrame:CGRectMake(0,0, widthMagazine, heightMagazine)];
        
        [btnMagazine setImage:[UIImage imageNamed:@"PlaceHolderMagazine"] forState:UIControlStateNormal];
        [btnMagazine setTag:index + kButtonTagOffset];
        //    [btnMagazine.imageView setImageWithURL:[NSURL URLWithString:[[arrMagazines objectAtIndex:index] objectForKey:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"PlaceHolderMagazine"]];
        [btnMagazine addTarget:self action:@selector(btnMagazineTapped:) forControlEvents:UIControlEventTouchUpInside];
        [magazineView addSubview:btnMagazine];
        
        UIImageView *imgViewMagazine = [[UIImageView alloc] initWithFrame:btnMagazine.frame];
        [imgViewMagazine setUserInteractionEnabled:NO];
        [imgViewMagazine setImageWithURL:[NSURL URLWithString:[[arrMagazines objectAtIndex:index] objectForKey:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"PlaceHolderMagazine"]];
        [_imgViewArticle setClipsToBounds:YES];
        [imgViewMagazine setTag:index + kButtonTagOffset];
        [magazineView addSubview:imgViewMagazine];
        _scrlViewMagazines.contentSize = CGSizeMake(_scrlViewMagazines.contentSize.width+widthMagazine+xMargin,_scrlViewMagazines.frame.size.height);
        
        UIButton *btnCloseMagazine = [UIButton buttonWithType:UIButtonTypeCustom];
        //    [btnMagazine setBackgroundImage:[UIImage imageNamed:@"MagazineSettingMenu"] forState:UIControlStateNormal];
        [btnCloseMagazine setTag:index + kButtonTagOffset];
        [btnCloseMagazine setImage:[UIImage imageNamed:@"close"] forState:UIControlStateNormal];
        
        [btnCloseMagazine addTarget:self action:@selector(btnDeleteCloseArticlesTapped:) forControlEvents:UIControlEventTouchUpInside];
        
        [btnCloseMagazine setFrame:CGRectMake(widthMagazine-20, 0, 20, 20)];
        
        [magazineView addSubview:btnCloseMagazine];
        magazineView.tag = index;
        [_scrlViewMagazines addSubview:magazineView];
        
    }
    if([[NSUserDefaults standardUserDefaults]boolForKey:@"pushedFromBackground"] == YES)
    {
        BOOL checkForMagazine  = NO;
        if (arrMagazines.count > 0){
            NSLog(@"Close Magazine Id %@", closedMagazineId);
            closedMagazineId = [[[NSUserDefaults standardUserDefaults]objectForKey:@"closeMagazineDetails"]objectForKey:@"article_id"];
    for (int index = 0; index < [arrMagazines count]; index++){
        if([[[arrMagazines objectAtIndex:index] objectForKey:@"magazineId"] isEqualToString: closedMagazineId] ){
            [self openMagazineUsingNotification:index articleDetailDictionary:[[NSUserDefaults standardUserDefaults]objectForKey:@"closeMagazineDetails"]];
            checkForMagazine  = YES;
            
        }
    }
            closedMagazineId = nil;
            [[NSUserDefaults standardUserDefaults]removeObjectForKey:@"closeMagazineDetails"];
            [[NSUserDefaults standardUserDefaults]synchronize];
            [[NSUserDefaults standardUserDefaults]setBool:NO forKey:@"pushedFromBackground"];
            [[NSUserDefaults standardUserDefaults]synchronize];
        
        if (checkForMagazine == NO)
        {
            closedMagazineId = nil;
            [[NSUserDefaults standardUserDefaults]removeObjectForKey:@"closeMagazineDetails"];
            [[NSUserDefaults standardUserDefaults]synchronize];
            UIAlertController *alertController = [UIAlertController
                                                  alertControllerWithTitle:@"Wootrix"
                                                  message:@"You don't have subscription for this magazine"
                                                  preferredStyle:UIAlertControllerStyleAlert];
            
            UIAlertAction *cancelAction = [UIAlertAction
                                           actionWithTitle:NSLocalizedString(@"Ok", @"Cancel action")
                                           style:UIAlertActionStyleCancel
                                           handler:^(UIAlertAction *action)
                                           {
                                           }];
            
            //    UIAlertAction *okAction = [UIAlertAction
            //                               actionWithTitle:NSLocalizedString(@"Open", @"OK action")
            //                               style:UIAlertActionStyleDefault
            //                               handler:^(UIAlertAction *action)
            //                               {
            ////                                   [self advertisementThroughNotification:userInfo];
            //                               }];
            
            [alertController addAction:cancelAction];
            //    [alertController addAction:okAction];
            [self.navigationController.visibleViewController presentViewController:alertController animated:YES completion:nil];
            
        }

    }
    }
    
}

- (void)btnDeleteCloseArticlesTapped:(UIButton*)sender
{
     buttonId  = (int)sender.tag - kButtonTagOffset;
    
    UIAlertController * alert=   [UIAlertController
                                  alertControllerWithTitle:[WXLandingScreenViewController languageSelectedStringForKey:@"Alert"]
                                  message:[WXLandingScreenViewController languageSelectedStringForKey:@"Do you want to delete the magazine"]
                                  preferredStyle:UIAlertControllerStyleAlert];
    
    UIAlertAction* ok = [UIAlertAction
                         actionWithTitle:[WXLandingScreenViewController languageSelectedStringForKey:@"Ok"]
                         style:UIAlertActionStyleDefault
                         handler:^(UIAlertAction * action)
                         {
                             for (int i=0;i<[arrMagazines count]; i++) {
                                 NSDictionary  *dict = [arrMagazines objectAtIndex:i];
                                 if (i == buttonId) {
                                     deleteMagazines = [[WXDeleteMagazine alloc] init];
                                     [deleteMagazines setToken        :[UserDefaults objectForKey:kToken]];
                                     [deleteMagazines setMagazine_id:[dict objectForKey:@"magazineId"]];
                                     [self callDeleteMagazinesWebService];
                                 }
                                 
                             }
                         }];
    UIAlertAction* cancel = [UIAlertAction
                             actionWithTitle:[WXLandingScreenViewController languageSelectedStringForKey:@"Cancel"]
                             style:UIAlertActionStyleDefault
                             handler:^(UIAlertAction * action)
                             {
                                 [alert dismissViewControllerAnimated:YES completion:nil];
                                 
                             }];
    
    [alert addAction:ok];
    [alert addAction:cancel];
    
    [self presentViewController:alert animated:YES completion:nil];
}

- (void) deleteMagazine: (int) buttonId1
{
    for (int i=0;i<[arrMagazines count]; i++) {
        NSString *item = [arrMagazines objectAtIndex:i];
        if (i == buttonId1) {
            [arrMagazines removeObject:item];
            for ( UIView *view in _scrlViewMagazines.subviews) {
                
                int viewRemove = (int)view.tag;
                if (viewRemove  == buttonId1) {
                    [view removeFromSuperview];
                    [self makeMagazineView];
                    [self.scrlViewMagazines setNeedsDisplay];
                }
            }
        }
        
    }
    
}

- (void)setUpView:(NSDictionary*)dictReceivedData
{
    _dictData = [NSDictionary dictionaryWithDictionary:dictReceivedData];
    [_lblArticleTitle       setText:[dictReceivedData objectForKey:@"title"]];
    
    
    NSDateFormatter *dateFormatter = [[NSDateFormatter alloc] init];
    [dateFormatter setDateFormat:@"yyyy-MM-dd HH:mm:ss"];
    NSDate *artcleDate = [dateFormatter dateFromString:[dictReceivedData objectForKey:@"createdDate"]];
    //[dateFormatter setDateFormat:@"MMM. dd, yyyy"];
    NSString *currentLanguage = [[NSUserDefaults standardUserDefaults] objectForKey:kAppLanguage];
    if ([currentLanguage rangeOfString:@"en"].location != NSNotFound) {
        //For English
        [dateFormatter setDateFormat:@"MM/dd/yy"];
    }
    else{
        //For Portuguese and Spenish
        [dateFormatter setDateFormat:@"dd/MM/yyyy"];
    }
    [_lblArticleDate        setText:[dateFormatter stringFromDate:artcleDate]];
    
    [_lblWebsiteLink        setText:[dictReceivedData objectForKey:@"source"]];
    if ([[dictReceivedData objectForKey:@"source"] length] == 0)
    {
        [_imgWebUrlIcon setHidden:YES];
    }
    
    [_txtViewArticleDetail  setText:[[dictReceivedData objectForKey:@"articleDescPlain"]stringByDecodingXMLEntities]];
    [_txtViewArticleDetail setFont:[UIFont systemFontOfSize:12]];
    UITapGestureRecognizer *tapLink = [[UITapGestureRecognizer alloc] initWithTarget:self action:@selector(linkTapped)];
    [_lblWebsiteLink addGestureRecognizer:tapLink];
    
    if ([[dictReceivedData objectForKey:@"embedded_thumbnail"] length] > 0)
    {
        [_imgViewArticle setImageWithURL:[NSURL URLWithString:[dictReceivedData objectForKey:@"embedded_thumbnail"]] placeholderImage:[UIImage imageNamed:@"PlaceHolderMagazine"]];
        UIImageView *imgViewPlayIcon = [[UIImageView alloc] initWithFrame:_imgViewArticle.frame];
        [imgViewPlayIcon setImage:[UIImage imageNamed:@"icon_play"]];
        [imgViewPlayIcon setContentMode:UIViewContentModeCenter];
        [imgViewPlayIcon setUserInteractionEnabled:NO];
        [imgViewPlayIcon setBackgroundColor:[UIColor colorWithRed:0 green:0 blue:0 alpha:0.5]];
        [imgViewPlayIcon setTag:11001];
        [self.view addSubview:imgViewPlayIcon];
    }
    else
    {
        [_imgViewArticle setImageWithURL:[NSURL URLWithString:[dictReceivedData objectForKey:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"PlaceHolderMagazine"]];
    }
    //  [_imgViewArticle setImageWithURL:[NSURL URLWithString:[dictReceivedData objectForKey:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
    
    //  UIButton *btnOpenArticle = [UIButton buttonWithType:UIButtonTypeCustom];
    //  [btnOpenArticle addTarget:self action:@selector(btnOpenArticleTapped:) forControlEvents:UIControlEventTouchUpInside];
    //  [btnOpenArticle setFrame:CGRectMake(_imgViewArticle.frame.origin.x, _imgViewArticle.frame.origin.y, _imgViewArticle.frame.size.width, _txtViewArticleDetail.frame.origin.y+_txtViewArticleDetail.frame.size.height)];
    //  [self.view addSubview:btnOpenArticle];
    
}

#pragma mark - button action

- (void)btnMagazineTapped:(UIButton*)sender
{
    NSInteger index = sender.tag-kButtonTagOffset;
    NSInteger flag = 0;
    
    for (UIViewController *viewC in self.navigationController.viewControllers)
    {
        if ([viewC isKindOfClass:[WXHomeViewController class]])
        {
            flag = 1;
            WXHomeViewController *homeViewC = (WXHomeViewController*)viewC;
            [homeViewC setIsMagazine:YES];
            [homeViewC setDictMagazine:[arrMagazines objectAtIndex:index]];
            
            [UserDefaults setObject:[[arrMagazines objectAtIndex:index] objectForKey:@"mobileAppBarColorRGB"] forKey:kMagazineColor];
            [UserDefaults synchronize];
            [self.navigationController popToViewController:homeViewC animated:YES];
        }
    }
    if (flag == 0)
    {
        WXHomeViewController *homeViewController = [self.storyboard instantiateViewControllerWithIdentifier:@"WXHomeViewController"];
        [homeViewController setIsMagazine:YES];
        [homeViewController setDictMagazine:[arrMagazines objectAtIndex:index]];
        [UserDefaults setObject:[[arrMagazines objectAtIndex:index] objectForKey:@"mobileAppBarColorRGB"] forKey:kMagazineColor];
        homeViewController.isMagazinePhotoShown = YES;
        [UserDefaults synchronize];
        [self.navigationController pushViewController:homeViewController animated:YES];
    }
    
}
- (void)openMagazineUsingNotification:(NSInteger)magazineIndex articleDetailDictionary: (NSDictionary *)userinfo
{
    
    NSInteger index = magazineIndex;
    NSInteger flag = 0;
    //test
    //AppDelegate *appDelegate = (AppDelegate *)[[UIApplication sharedApplication] delegate];
   
    if (![[self.navigationController visibleViewController]isKindOfClass:[WXHomeViewController class]]&&![[self.navigationController visibleViewController]isKindOfClass:[UIAlertController class]]){
    for (UIViewController *viewC in self.navigationController.viewControllers)
    {
        if ([viewC isKindOfClass:[WXHomeViewController class]])
        {
            flag = 1;
            NSLog(@"landing page open magazine using notification called flag 1");
            WXHomeViewController *homeViewC = (WXHomeViewController*)viewC;
            [homeViewC setIsMagazine:YES];
            if([[userinfo valueForKey:@"messagetype_sub"]isEqualToString:@"closemagazine"]){
                homeViewC.isMagazinePhotoShown = YES;
                homeViewC.isCloseMagCover = true;
                homeViewC.viewLoaded = NO;
            }else
            {
                homeViewC.isMagazinePhotoShown = NO;
                homeViewC.isCloseMagCover = false;
                homeViewC.viewLoaded = NO;
                
            }
            [homeViewC setDictMagazine:[arrMagazines objectAtIndex:index]];
                        [UserDefaults setObject:[[arrMagazines objectAtIndex:index] objectForKey:@"mobileAppBarColorRGB"] forKey:kMagazineColor];
            [UserDefaults synchronize];
            //test
            [homeViewC setDictCloseArticle:userinfo];
            [homeViewC.view sendSubviewToBack:homeViewC.viewPageBar];
            [homeViewC.view bringSubviewToFront:homeViewC.btnRefresh];
            [homeViewC.arrayArticles removeAllObjects];
            [homeViewC viewDidAppear:YES];
            NSLog(@"closed magazine article details %@",userinfo);
            [self.navigationController popToViewController:homeViewC animated:YES];
        }
    }
    if (flag == 0)
    { NSLog(@"landing page open magazine using notification called flag 0");
        WXHomeViewController *homeViewController = [self.storyboard instantiateViewControllerWithIdentifier:@"WXHomeViewController"];
        [homeViewController setIsMagazine:YES];
        if([[userinfo valueForKey:@"messagetype_sub"]isEqualToString:@"closemagazine"]){
            homeViewController.isMagazinePhotoShown = YES;
            homeViewController.viewLoaded = NO;
        }else
        {
            homeViewController.isMagazinePhotoShown = NO;
            homeViewController.viewLoaded = NO;
            
        }
        [homeViewController setDictMagazine:[arrMagazines objectAtIndex:index]];
         NSLog(@"values of closed magazine%@",homeViewController.dictMagazine);
        [UserDefaults setObject:[[arrMagazines objectAtIndex:index] objectForKey:@"mobileAppBarColorRGB"] forKey:kMagazineColor];
        [UserDefaults synchronize];
        //test
        [homeViewController setDictCloseArticle:userinfo];
        [homeViewController.view sendSubviewToBack:homeViewController.viewPageBar];
        [homeViewController.view bringSubviewToFront:homeViewController.btnRefresh];
        [homeViewController.arrayArticles removeAllObjects];
        [homeViewController viewDidAppear:YES];
        [self.navigationController pushViewController:homeViewController animated:YES];
    }
    }else{
        [[NSNotificationCenter defaultCenter]postNotificationName:@"closeArticleNotification" object:[NSNumber numberWithInt:magazineIndex] userInfo:userinfo];
    }
    
}


- (void)btnAddMagazineTapped:(UIButton*)sender
{
    //  if (IOS(8))
    //  {
    //
    //  }
    //  else
    //  {
    UIAlertView *alertView = [[UIAlertView alloc] initWithTitle:kAPPName message:@"Enter magazine password" delegate:self cancelButtonTitle:[WXLandingScreenViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles:[WXLandingScreenViewController languageSelectedStringForKey:@"Cancel"],nil];
    [alertView setAlertViewStyle:UIAlertViewStylePlainTextInput];
    alertView.tag = 101;
    UITextField *textField = [alertView textFieldAtIndex:0];
    [textField setKeyboardType:UIKeyboardTypeEmailAddress];
    textField.keyboardType = UIKeyboardTypeEmailAddress;
    [alertView show];
    //  }
}

- (IBAction)btnViewProfileTapped:(UIButton *)sender
{
    if (isDeviceIPad)
    {
        WXMenuViewController *menuViewC = [[WXMenuViewController alloc] initWithNibName:@"WXMenuViewIPad" bundle:nil];
        menuViewC.delegate = self;
        _navControllerPopover = nil;
        
        _navControllerPopover = [[UINavigationController alloc] initWithRootViewController:menuViewC];
        [_navControllerPopover setNavigationBarHidden:YES];
        
        UIPopoverController *popOverViewC = [[UIPopoverController alloc] initWithContentViewController:_navControllerPopover];
        popOverViewC.popoverContentSize = CGSizeMake(320, 568);
        [popOverViewC presentPopoverFromRect:_btnProfile.frame inView:self.view permittedArrowDirections:UIPopoverArrowDirectionAny animated:YES];
    }
    else
    {
        WXMenuViewController *menuViewC = [self.storyboard instantiateViewControllerWithIdentifier:@"WXMenuViewController"];
        [self.navigationController pushViewController:menuViewC animated:YES];
    }
    
}

/**
 *  Opens the tapped url on web view
 */
- (void)linkTapped
{
    
}

/**
 *  <#Description#>
 *
 *  @param sender <#sender description#>
 */
- (IBAction)btnOpenArticleTapped:(UIButton*)sender
{
    //  [self callOpenArticlesWebService];
    WXHomeViewController *homeViewController = [self.storyboard instantiateViewControllerWithIdentifier:@"WXHomeViewController"];
    [homeViewController setIsMagazine:NO];
    [self.navigationController pushViewController:homeViewController animated:YES];
}

#pragma mark - menuview delegate

- (void)logoutAction
{
    [self.navigationController popToRootViewControllerAnimated:self];
}

#pragma mark - PROTECTED METHODS

- (void)executeBlock:(dispatch_block_t)block
{
    // By design this method should not be invoked from the introQueue.
    //
    NSAssert(!dispatch_get_specific(landingQueueTag), @"Invoked on incorrect queue");
    
    dispatch_async(landingQueue, ^{ @autoreleasepool {
        
        block();
        
    }});
}

#pragma mark - alertview delegates

- (void)alertView:(UIAlertView *)alertView didDismissWithButtonIndex:(NSInteger)buttonIndex
{
    if (alertView.tag == 101)
    {
        UITextField *textField = [alertView textFieldAtIndex:0];
        if (buttonIndex == 0)
        {
            
        }
    }
    else
    {
        [self callLandingScreenWebservice];
    }
    
}


#pragma mark - UICollectionView Delegate

/*!
 Tells the delegate that the item at the specified index path was selected.
 */
- (void)collectionView:(UICollectionView *)collectionView didSelectItemAtIndexPath:(NSIndexPath *)indexPath
{
    NSInteger index = indexPath.row;
    NSInteger flag = 0;
    
    for (UIViewController *viewC in self.navigationController.viewControllers)
    {
        if ([viewC isKindOfClass:[WXHomeViewController class]])
        {
            flag = 1;
            WXHomeViewController *homeViewC = (WXHomeViewController*)viewC;
            [homeViewC setIsMagazine:YES];
            [homeViewC setDictMagazine:[arrMagazines objectAtIndex:index]];
            
            [UserDefaults setObject:[[arrMagazines objectAtIndex:index] objectForKey:@"mobileAppBarColorRGB"] forKey:kMagazineColor];
            [UserDefaults synchronize];
            [self.navigationController popToViewController:homeViewC animated:YES];
        }
    }
    if (flag == 0)
    {
        WXHomeViewController *homeViewController = [self.storyboard instantiateViewControllerWithIdentifier:@"WXHomeViewController"];
        [homeViewController setIsMagazine:YES];
        [homeViewController setDictMagazine:[arrMagazines objectAtIndex:index]];
        [UserDefaults setObject:[[arrMagazines objectAtIndex:index] objectForKey:@"mobileAppBarColorRGB"] forKey:kMagazineColor];
        homeViewController.isMagazinePhotoShown = YES;
        [UserDefaults synchronize];
        [self.navigationController pushViewController:homeViewController animated:YES];
    }
}


#pragma mark - UICollectionViewDataSource

/*!
 Asks your data source object for the number of items in the specified section.
 */
- (NSInteger)collectionView:(UICollectionView *)collectionView numberOfItemsInSection:(NSInteger)section
{
    return ([arrMagazines count]);
}

/*!
 The cell that is returned must be retrieved from a call to -dequeueReusableCellWithReuseIdentifier:forIndexPath:
 */
- (UICollectionViewCell *)collectionView:(UICollectionView *)collectionView cellForItemAtIndexPath:(NSIndexPath *)indexPath
{
    static NSString *cellId = @"Cell";
    UICollectionViewCell *cell = [collectionView dequeueReusableCellWithReuseIdentifier:cellId forIndexPath:indexPath];
    
    UIImageView *img = [cell viewWithTag:1];
    UILabel *lbl = [cell viewWithTag:2];
    
    [img setImageWithURL:[NSURL URLWithString:[[arrMagazines objectAtIndex:indexPath.row] objectForKey:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"PlaceHolderMagazine"]];
    lbl.text = [[arrMagazines objectAtIndex:indexPath.row] objectForKey:@"magazineName"];
    
    return (cell);
}

/*!
 Asks your data source object to provide a supplementary view to display in the collection view.
 */
- (UICollectionReusableView *)collectionView:(UICollectionView *)theCollectionView viewForSupplementaryElementOfKind:(NSString *)kind atIndexPath:(NSIndexPath *)theIndexPath
{
    
    UICollectionReusableView *theView;
    
    if(kind == UICollectionElementKindSectionHeader)
    {
        theView = [theCollectionView dequeueReusableSupplementaryViewOfKind:UICollectionElementKindSectionHeader
                                                        withReuseIdentifier:@"HeaderView"
                                                               forIndexPath:theIndexPath];
        
        UILabel *lbl = [theView viewWithTag:1];
        
        lbl.text = [WXLandingScreenViewController languageSelectedStringForKey:@"Magazines"];
       
    }
    
    return theView;
}

#pragma mark - UICollectionView Flow delegate

/*!
 Asks the delegate for the size of the specified item’s cell
 */
- (CGSize)collectionView:(UICollectionView *)collectionView
                  layout:(UICollectionViewLayout*)collectionViewLayout
  sizeForItemAtIndexPath:(NSIndexPath *)indexPath
{
    
    return (CGSizeMake(_itemWidth, _itemHeight/* + size.height*/));
}



@end


#pragma mark - WebService

@implementation WXLandingScreenViewController (WebserviceMethods)

/**
 *  This service is called to populate the View of Landing screen.
 */
- (void)callLandingScreenWebservice
{
    if(![[NetworkManager manager] isConnectedToWiFi])
    {
        [[[UIAlertView alloc] initWithTitle:kAPPName message:[WXLandingScreenViewController languageSelectedStringForKey:@"Network Not Available"] delegate:self cancelButtonTitle:[WXLandingScreenViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles: nil] show];
        return;
    }
    [self showProgressHUD:[WXLandingScreenViewController languageSelectedStringForKey:@"Loading..."]];
     [self.view setUserInteractionEnabled:NO];
    WXLandingRequestModel *landingRequest = [[WXLandingRequestModel alloc] init];
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
    NSString *fcmDeviceToken;
    if ([UserDefaults objectForKey:kFirebaseId] == nil){
        fcmDeviceToken = @"fqASoRlz1hQ:APA91bGb_exXTruv7AtArZA16sfdYcmpPzZJRqlDlKHtDYXn5qGEhYfscRAen7IgbWrD8Xvw7eKCoz3e8Pvl28BFq9jUzHsi849V4z6XMeAaLk7JJQ1funoJmc0PGNSj89fJo0PlktW1";
    }else{
        fcmDeviceToken = [UserDefaults objectForKey:kFirebaseId];
        [myTimer invalidate];
        myTimer = nil;
    }

    [landingRequest setArticleLanguage    :articleLanguages];
    [landingRequest setToken              :[UserDefaults objectForKey:kToken]];
    [landingRequest setApplanguage        :[UserDefaults objectForKey:kAppLanguage]?[UserDefaults objectForKey:kAppLanguage]:@"en"];
    [landingRequest setDeviceId           :fcmDeviceToken];
    [landingRequest setDevice                 :@"iPhone"];
    [[NetworkManager manager] requestWithMethod:kHTTPMethodPOST1
                                      URLString:@"LandingScreen"
                                     parameters:landingRequest
                                         sucess:^(id response)
     {
         NSLog(@"res:%@",response);
         if ([[response objectForKey:kSuccess] boolValue] == YES)
         {
             arrMagazines = nil;
             if ([[response objectForKey:@"data"] isKindOfClass:[NSDictionary class]])
             {
                 arrMagazines = [[NSMutableArray alloc] initWithArray:[[response objectForKey:@"data"] objectForKey:@"magazines"]];
             }
             else
             {
                 arrMagazines = [[NSMutableArray alloc] initWithArray:[[[response objectForKey:@"data"] firstObject] objectForKey:@"magazines"]];
             }
             
             //todo: remove
//             for (int i = 0; i < 10; i++)
//                 [arrMagazines addObjectsFromArray:arrMagazines];
             
             if(arrMagazines.count > 0){
             [[NSUserDefaults standardUserDefaults] removeObjectForKey:@"listOfCloseMagazines"];
             [[NSUserDefaults standardUserDefaults]setObject:arrMagazines forKey:@"listOfCloseMagazines"];
             [[NSUserDefaults standardUserDefaults]synchronize];
             }else{
                [[NSUserDefaults standardUserDefaults] removeObjectForKey:@"listOfCloseMagazines"];
                [[NSUserDefaults standardUserDefaults]synchronize];
             }
             //add dummy data
             
             //       for (int i = 0 ; i  <  10; i++)
             //       {
             //         [arrMagazines addObjectsFromArray:[[[response objectForKey:@"data"] firstObject] objectForKey:@"magazines"]];
             //       }
             NSLog(@"pushedFromBackground %d",[[NSUserDefaults standardUserDefaults]boolForKey:@"pushedFromBackground"]);
             if ([[NSUserDefaults standardUserDefaults]boolForKey:@"pushedFromBackground"]== YES) {
//                 WXHomeViewController *homeViewController = [self.storyboard instantiateViewControllerWithIdentifier:@"WXHomeViewController"];
//                 [homeViewController setIsMagazine:YES];
//                 [self.navigationController pushViewController:homeViewController animated:YES];
                 NSLog(@"make magazine view Called");
                 [self makeMagazineView];
                 if (![[response objectForKey:@"data"] isKindOfClass:[NSDictionary class]])
                 {
                     [self setUpView:[[[[response objectForKey:@"data"] firstObject] objectForKey:@"openArticle"] firstObject]];
                 }

                 [[NSUserDefaults standardUserDefaults]setBool:NO forKey:@"pushedFromBackground"];
                 [[NSUserDefaults standardUserDefaults]synchronize];
             }else{
                 if (arrMagazines.count == 0)
                 {
                     WXHomeViewController *homeViewController = [self.storyboard instantiateViewControllerWithIdentifier:@"WXHomeViewController"];
                     [homeViewController setIsMagazine:NO];
                     [self.navigationController pushViewController:homeViewController animated:YES];
                 }
                 [self makeMagazineView];
                 if (![[response objectForKey:@"data"] isKindOfClass:[NSDictionary class]])
                 {
                     [self setUpView:[[[[response objectForKey:@"data"] firstObject] objectForKey:@"openArticle"] firstObject]];
                 }

             }
             
             [self.CollectionView reloadData];
         }
         else
         {
             [[[UIAlertView alloc] initWithTitle:kAPPName message:[response objectForKey:kMessage] delegate:nil cancelButtonTitle:[WXLandingScreenViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles:nil] show];
         }
         [self dismissProgressHUD];
          [self.view setUserInteractionEnabled:YES];
         
     }failure:^(NSError *error)
     {
         [self dismissProgressHUD];
          [self.view setUserInteractionEnabled:YES];
     }];
}

- (void)callDeleteMagazinesWebService
{
    if(![[NetworkManager manager] isConnectedToWiFi])
    {
        [[[UIAlertView alloc] initWithTitle:kAPPName message:[WXMenuViewController languageSelectedStringForKey:@"Network Not Available"] delegate:nil cancelButtonTitle:[WXMenuViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles: nil] show];
        return;
    }
    [self showProgressHUD:@"Deleting Magazine"];
     [self.view setUserInteractionEnabled:NO];
    
    [[NetworkManager manager] requestWithMethod:kHTTPMethodPOST1
                                      URLString:@"magzinedelete"
                                     parameters:deleteMagazines
                                         sucess:^(id response)
     {
         NSLog(@"res:%@",response);
         if ([[response objectForKey:kSuccess] boolValue] == YES)
         {
             [self deleteMagazine:buttonId];
             [[[UIAlertView alloc] initWithTitle:[WXMenuViewController languageSelectedStringForKey:@"Alert"] message:[WXMenuViewController languageSelectedStringForKey:@"Magazine Deleted Successfully"]delegate:nil cancelButtonTitle:[WXMenuViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles:nil] show];
         }
         else
         {
             [[[UIAlertView alloc] initWithTitle:kAPPName message:[response objectForKey:kMessage] delegate:nil cancelButtonTitle:[WXMenuViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles:nil] show];
         }
         [self dismissProgressHUD];
          [self.view setUserInteractionEnabled:YES];
         
     }failure:^(NSError *error)
     {
         [self dismissProgressHUD];
          [self.view setUserInteractionEnabled:YES];
         
     }];
    
}

- (void) openClosedMagazine: (NSNotification *) notification
{
    //[self callLandingScreenWebservice];
    //orginal
    //NSString*  magazineId = [notification.userInfo objectForKey:@"article_id"];
    BOOL checkForMagazine  = NO;
    NSLog(@"closed article completely opened 101");
    NSString*  magazineId =  [notification.userInfo objectForKey:@"article_id"];
    arrMagazines = [[NSUserDefaults standardUserDefaults]objectForKey:@"listOfCloseMagazines"];
        if (arrMagazines.count > 0) {
        for (int index = 0; index < [arrMagazines count]; index++){
            if([[[arrMagazines objectAtIndex:index] objectForKey:@"magazineId"] isEqualToString: magazineId] )
            {
                [self openMagazineUsingNotification:index articleDetailDictionary:notification.userInfo];
                checkForMagazine = YES;
            }
        }
            if (checkForMagazine == NO)
          {
                UIAlertController *alertController = [UIAlertController
                                                      alertControllerWithTitle:@"Wootrix"
                                                      message:@"You don't have subscription for this magazine"
                                                      preferredStyle:UIAlertControllerStyleAlert];
                
                UIAlertAction *cancelAction = [UIAlertAction
                                               actionWithTitle:NSLocalizedString(@"Ok", @"Cancel action")
                                               style:UIAlertActionStyleCancel
                                               handler:^(UIAlertAction *action)
                                               {
                                               }];
                
                [alertController addAction:cancelAction];
                //    [alertController addAction:okAction];
                [self.navigationController.visibleViewController presentViewController:alertController animated:YES completion:nil];
                
            }

}
}
//- (void)articleNotification: (NSNotification*)notification
//{
//    WXHomeViewController *homeViewController = [self.storyboard instantiateViewControllerWithIdentifier:@"WXHomeViewController"];
//    [homeViewController setIsMagazine:NO];
//    [self.navigationController pushViewController:homeViewController animated:YES];
//}



@end
