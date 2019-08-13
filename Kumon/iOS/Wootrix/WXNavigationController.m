//
//  WXNavigationController.m
//  Wootrix
//
//  Created by Factory Technologies on 04/04/17.
//  Copyright © 2017 Techahead. All rights reserved.
//

#import "WXNavigationController.h"
#import <SafariServices/SafariServices.h>
#import "WXMetricModel.h"
#import "WXArticleModel.h"
#import "AppDelegate.h"
#import "WXMenuViewController.h"
#import "WXSubscribeMagazineModel.h"
#import "WXChangePasswordViewController.h"
#import "WXHomeViewController.h"
#import "WXArticleDetailViewController.h"
#import "WXMagazineModel.h"
#import "WXAdModel.h"
#import "WXIFrameVideoPlayerController.h"
#import "WXUserArticle.h"


/*!
 root navigation screen
 */
@implementation WXNavigationController
{
    NSTimer *_timer;
    NSString *_tempMagazineId;
    NSString *_tempArticleId;
}


/*!
 Called after the controller's view is loaded into memory.
 */
- (void)viewDidLoad
{
    [super viewDidLoad];
    // Do any additional setup after loading the view.
    
    _tempMagazineId = @"";
    _tempArticleId = @"";
    _timer = [NSTimer scheduledTimerWithTimeInterval:5.0 target:self selector:@selector(OnTimer:) userInfo:nil repeats:true];
}


/*!
 Sent to the view controller when the app receives a memory warning.
 */
- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

#pragma mark - Timer

/*!
 called by the timer call
 */
-(void) OnTimer:(NSTimer *)timer
{
    NSString *articleId = (NSString *)[[PropertyBag Instance] Get:@"article_id"];
    NSString *magazineId = (NSString *)[[PropertyBag Instance] Get:@"magazine_id"];
    NSString *magazineCode = (NSString *)[[PropertyBag Instance] Get:@"magazine_code"];
    NSString *adId = (NSString *)[[PropertyBag Instance] Get:@"ad_id"];
    NSString *showInputDlg = (NSString *)[[PropertyBag Instance] Get:@"show_input_code_dlg"];
    
    [[PropertyBag Instance] Add:@"" forKey:@"ad_id"];
    
    
    if ([UserDefaults boolForKey:kIsLoggedin] && (magazineCode != nil && [magazineCode length] > 0))
        [self callAddMagazineWebServiceWithPassword:magazineCode :articleId :magazineId :adId];
    else if ([UserDefaults boolForKey:kIsLoggedin] && (articleId != nil && [articleId length] > 0))
    {
        [[PropertyBag Instance] Add:@"" forKey:@"article_id"];
        [[PropertyBag Instance] Add:@"" forKey:@"magazine_id"];
        
        if (magazineId.length == 0)
            [self NavigateToArticle:articleId :0];
        else
        {
            WXUserArticle *userArticle = [[WXUserArticle alloc]init];
            
            userArticle.userId = [UserDefaults stringForKey:kToken];
            userArticle.magazineId = magazineId;
            
            [[NetworkManager manager] requestWithMethod:kHTTPMethodPOST1
                                              URLString:@"getUserHasMagazine"
                                             parameters:userArticle
                                                 sucess:^(id response)
             {
                 
                 if ([[response objectForKeyNonNull:kSuccess] boolValue] == YES)
                 {
                     bool userHasMagazine = [[[response objectForKey:@"data"] objectForKey:@"userHasMagazine"] boolValue];
                     
                     if (userHasMagazine)
                         [self NavigateToArticle:articleId :magazineId];
                     else
                     {
                         _tempArticleId = articleId;
                         _tempMagazineId = magazineId;
                         [self ShowInputDlg];
                     }
                         
                 }
             }failure:^(NSError *error)
             {
                 NSLog(@"error %@", error);
             }];
        }
    }
    else if ([UserDefaults boolForKey:kIsLoggedin] && (adId != nil && [adId length] > 0))
        [self NavigateToAd:adId];
    else if ([UserDefaults boolForKey:kIsLoggedin] && (showInputDlg != nil && [showInputDlg length] > 0))
    {
        [[PropertyBag Instance] Add:@"" forKey:@"show_input_code_dlg"];
        [self ShowInputDlg];
    }
}

/*
 #pragma mark - Navigation
 
 // In a storyboard-based application, you will often want to do a little preparation before navigation
 - (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
 // Get the new view controller using [segue destinationViewController].
 // Pass the selected object to the new view controller.
 }
 */

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
    metric.id_magazine = [[article objectForKeyNonNull:@"magazineId"] length] > 0 ? [article objectForKeyNonNull:@"magazineId"] : @"0";
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

/*!
 navigates to the article
 */
-(void) NavigateToArticle:(NSString *)articleId :(NSString *)magazineId
{
    WXArticleModel *articleModel = [[WXArticleModel alloc]init];
    
    [[PropertyBag Instance] Add:@"" forKey:@"article_id"];
    [[PropertyBag Instance] Add:@"" forKey:@"magazine_id"];
    
    articleModel.magazineId = magazineId.length > 0 ? magazineId : @"";
    articleModel.articleId = articleId.length > 0 ? articleId : @"";
    
    
    if (articleId.length == 0)
        return;
    
    [[NetworkManager manager] requestWithMethod:kHTTPMethodPOST1
                                      URLString:@"getArticleDetail"
                                     parameters:articleModel
                                         sucess:^(id response)
     {
         
         if ([[response objectForKeyNonNull:kSuccess] boolValue] == YES)
         {
             if ([[response objectForKey:@"data"] objectForKey:@"openArticles"] == nil)
                 return;
             
             [self SendMetrics:[[response objectForKey:@"data"] objectForKey:@"openArticles"]];
             
             NSDictionary *dictData = [[response objectForKey:@"data"] objectForKey:@"openArticles"];
             
             if ([[dictData objectForKey:@"createdBy"] boolValue] == NO) // source url
             {
                 NSString *urlAddress = [[[response objectForKey:@"data"] objectForKey:@"openArticles"] objectForKey:@"fullSoruce"];
                 
                 if (urlAddress.length > 0)
                 {
                     SFSafariViewController *safariVc = [[SFSafariViewController alloc]initWithURL:[[NSURL alloc] initWithString:urlAddress]];
                     
                     [self.view.topViewController presentViewController:safariVc animated:YES completion:nil];
                 }
             }
             else if ([dictData objectForKey:@"articleDescHTML"] != nil)
             {
                 WXArticleDetailViewController *articleViewC = [self.storyboard instantiateViewControllerWithIdentifier:@"WXArticleDetailViewController"];
                 
                 [articleViewC setDictData:dictData];
                 [articleViewC setType:magazineId.length > 0 ? @"magazine" : @"open"];
                 [articleViewC setMagazineId:magazineId.length > 0 ? magazineId : @"-1"];
                 
                 
                 [self pushViewController:articleViewC animated:YES];
             }
             
             //
             //
             //
             //
             //
             //
             //
             //
         }
     }failure:^(NSError *error)
     {
         NSLog(@"error %@", error);
     }];
}




#pragma mark - Add Magazine



- (void)callAddMagazineWebServiceWithPassword:(NSString*)password :(NSString *)articleId :(NSString *)magazineId :(NSString *)adId
{
    [[PropertyBag Instance] Add:@"" forKey:@"magazine_code"];
    [[PropertyBag Instance] Add:@"" forKey:@"article_id"];
    [[PropertyBag Instance] Add:@"" forKey:@"magazine_id"];
    _tempMagazineId = @"";
    _tempArticleId = @"";
    
    
    if(![[NetworkManager manager] isConnectedToWiFi])
    {
        [[[UIAlertView alloc] initWithTitle:kAPPName
                                    message:[WXMenuViewController languageSelectedStringForKey:@"Network Not Available"]
                                   delegate:nil
                          cancelButtonTitle:[WXMenuViewController languageSelectedStringForKey:@"Ok"]
                          otherButtonTitles: nil] show];
        return;
    }
    
    [self.view.topViewController showProgressHUD:[WXChangePasswordViewController languageSelectedStringForKey:@"Subscribing Magazine..."]];
    //    [self.view ShowWaitingProgress];
    
    WXSubscribeMagazineModel *subscribeMagazine = [[WXSubscribeMagazineModel alloc] init];
    
    [subscribeMagazine setSubscriptionPassword:password];
    [subscribeMagazine setToken     :[UserDefaults objectForKey:kToken]];
    [subscribeMagazine setAppLanguage:[UserDefaults objectForKey:kAppLanguage]?[UserDefaults objectForKey:kAppLanguage]:@"en"];
    
    [[NetworkManager manager] requestWithMethod:kHTTPMethodPOST1
                                      URLString:@"subscribeMagazine"
                                     parameters:subscribeMagazine
                                         sucess:^(id response)
     {
         NSLog(@"res:%@",response);
         
         [self.view.topViewController dismissProgressHUD];
         
         if ([[response objectForKey:kSuccess] boolValue] == YES)
         {
             UIAlertController * alertcontroller = [UIAlertController alertControllerWithTitle:kAPPName
                                                                                       message:[WXChangePasswordViewController languageSelectedStringForKey:@"Magazine subscribed successfully."]
                                                                                preferredStyle:UIAlertControllerStyleAlert];
             
             UIAlertAction * ok = [UIAlertAction actionWithTitle:[WXMenuViewController languageSelectedStringForKey:@"Ok"]
                                                           style:UIAlertActionStyleDefault
                                                         handler:^(UIAlertAction * _Nonnull action) {
                                                             [self showMagazine:[[response objectForKey:@"data"][0] objectForKey:@"magazines" ][0]];
                                                             [self NavigateToArticle:articleId :magazineId];
                                                             [self NavigateToAd:adId];
                                                         }];
             [alertcontroller addAction:ok];
             
             [self.view.topViewController presentViewController:alertcontroller animated:NO completion:^{
                 
             }];
         }
         else
         {
             UIAlertController * alertcontroller = [UIAlertController alertControllerWithTitle:kAPPName
                                                                                       message:[NSString stringWithFormat:@"%@",[response objectForKey:kMessage]]
                                                                                preferredStyle:UIAlertControllerStyleAlert];
             
             UIAlertAction * ok = [UIAlertAction actionWithTitle:[WXMenuViewController languageSelectedStringForKey:@"Ok"]
                                                           style:UIAlertActionStyleDefault
                                                         handler:nil];
             
             if (![[response objectForKey:@"hasMagazine"] boolValue])
             {
                 [alertcontroller addAction:ok];
                 
                 [self.view.topViewController presentViewController:alertcontroller animated:NO completion:nil];
             }
             else
             {
                 WXMagazineModel *magazine = [[WXMagazineModel alloc]init];
                 
                 magazine.magazineId = [response objectForKey:@"magazineId"];
                 
                 [[NetworkManager manager] requestWithMethod:kHTTPMethodPOST1
                                                   URLString:@"getMagazineData"
                                                  parameters:magazine sucess:^(id response) {
                                                      
                                                      if ([[response objectForKey:kSuccess] boolValue] == YES)
                                                      {
                                                          [self showMagazine:[[response objectForKey:@"data"][0] objectForKey:@"magazines" ][0]];
                                                          [self NavigateToArticle:articleId :magazineId];
                                                          [self NavigateToAd:adId];
                                                      }
                                                      else
                                                      {
                                                          UIAlertController * alertcontroller = [UIAlertController alertControllerWithTitle:kAPPName
                                                                                                                                    message:[NSString stringWithFormat:@"%@",[response objectForKey:kMessage]]
                                                                                                                             preferredStyle:UIAlertControllerStyleAlert];
                                                          
                                                          UIAlertAction * ok = [UIAlertAction actionWithTitle:[WXMenuViewController languageSelectedStringForKey:@"Ok"]
                                                                                                        style:UIAlertActionStyleDefault
                                                                                                      handler:nil];
                                                          
                                                          [alertcontroller addAction:ok];
                                                          
                                                          [self.view.topViewController presentViewController:alertcontroller animated:NO completion:nil];
                                                      }
                                                      
                                                  } failure:^(NSError *error) {
                                                      [self.view.topViewController dismissProgressHUD];
                                                  }];
                 
             }
             
         }
         
         
         
     }failure:^(NSError *error)
     {
         [self.view.topViewController dismissProgressHUD];
     }];
    
}

- (void)showMagazine:(NSDictionary *)magazine
{
    
    //    if (isDeviceIPad)
    //    {
    //        [_delegate magazineTapped:[arrMagazines objectAtIndex:index]];
    //    }
    //    else
    //    {
    UIViewController *vc = self.visibleViewController;
    NSMutableArray *vcLists = [self.viewControllers mutableCopy];
    
    bool isInList = false;
    
    for (int i = 0; i < [self.viewControllers count]; i++)
    {
        UIViewController *viewC = vcLists[i];
        
        if ([viewC isKindOfClass:[WXHomeViewController class]])
        {
            [vcLists removeObject:viewC];
            isInList = true;
            break;
        }
    }
    
    WXHomeViewController *homeViewController = [self.storyboard instantiateViewControllerWithIdentifier:@"WXHomeViewController"];
    
    [homeViewController setIsMagazine:YES];
    [homeViewController setDictMagazine:magazine];
    [UserDefaults setObject:[magazine objectForKey:@"mobileAppBarColorRGB"] forKey:kMagazineColor];
    [UserDefaults synchronize];
    homeViewController.isMagazinePhotoShown = YES;
    
    [vcLists addObject:homeViewController];
    
    if (!isInList)
        [self pushViewController:homeViewController animated:YES];
    else if ([vc isKindOfClass:[WXHomeViewController class]])
        [self setViewControllers:vcLists];
    else if (isInList)
        [self popToViewController:vc animated:true];
    
    //    }
    
}

/*!
 navigates to the ad
 */
-(void) NavigateToAd:(NSString *) adId
{
    if (adId.length == 0)
        return;
    
    [[PropertyBag Instance] Add:@"" forKey:@"ad_id"];
    
    WXAdModel *ad = [[WXAdModel alloc] init];
    
    ad.adId = adId;
    
    [[NetworkManager manager] requestWithMethod:kHTTPMethodPOST1
                                      URLString:@"getAdvDetail"
                                     parameters:ad
                                         sucess:^(id response) {
                                             
                                             if ([[response objectForKey:kSuccess] boolValue] == YES)
                                             {
                                                 
                                                 if ([response objectForKey:@"data"] == nil || [[response objectForKey:@"data"] count] == 0)
                                                     return;
                                                 
                                                 NSString *link = [[response objectForKey:@"data"] objectForKey:@"link"];
                                                 int mediaType = [[[response objectForKey:@"data"] objectForKey:@"media_type"] intValue];
                                                 
                                                 if (link.length > 0)
                                                 {
                                                     if (mediaType == 1)
                                                     {
                                                         WXOpenAdsViewController *openAds = [self.storyboard instantiateViewControllerWithIdentifier:@"WXOpenAdsViewController"];
                                                         [openAds setLinkUrl:link];
                                                         
                                                         [self pushViewController:openAds animated:YES];
                                                     }
                                                     else
                                                     {
                                                         WXIFrameVideoPlayerController *iframe = [self.storyboard instantiateViewControllerWithIdentifier:@"WXIFrameVideoPlayerController"];
                                                         NSDictionary *dict = @{@"videoURL" : [[response objectForKey:@"data"] objectForKey:@"embed_video"],
                                                                                @"allowComment" : @(0),
                                                                                @"allowShare" : @(0)};
                                                         
                                                         iframe.iFrameVideoType = iFrameVideoTypeAdvertisement;
                                                         iframe.dictData = dict;
                                                         [iframe setType: @"open"];
                                                         [iframe setMagazineId:@"-1"];
                                                         iframe.Pushed = true;
                                                         //                                                     UINavigationController *nav = [[UINavigationController alloc] initWithRootViewController:iframe];
                                                         //                                                     [nav setNavigationBarHidden:YES];
                                                         
                                                         // [self presentViewController:nav animated:YES completion:nil];
                                                         [self pushViewController:iframe animated:true];
                                                     }
                                                 }
                                                 
                                                 
                                             }
                                             
                                         } failure:^(NSError *error) {
                                             
                                         }];
}


#pragma mark - show input code dlg

/*!
 shows input code dlg
 */
-(void) ShowInputDlg
{
    UIAlertView *alertView = [[UIAlertView alloc] initWithTitle:kAPPName
                                                        message:[WXMenuViewController languageSelectedStringForKey:@"Enter Subscription Password"]
                                                       delegate:self
                                              cancelButtonTitle:[WXMenuViewController languageSelectedStringForKey:@"Cancel"]
                                              otherButtonTitles:[WXMenuViewController languageSelectedStringForKey:@"Ok"],nil];
    
    [alertView setAlertViewStyle:UIAlertViewStylePlainTextInput];
    alertView.tag = 101;
    
    UITextField *textField = [alertView textFieldAtIndex:0];
    [textField setKeyboardType:UIKeyboardTypeEmailAddress];
    [textField setPlaceholder:[WXMenuViewController languageSelectedStringForKey:@"Password"]];
    textField.keyboardType = UIKeyboardTypeEmailAddress;
    [alertView show];
}


#pragma mark - alertview delegates

/*!
 called when the user clicks the ok button on the dlg
 */
- (void)alertView:(UIAlertView *)alertView didDismissWithButtonIndex:(NSInteger)buttonIndex
{
    UITextField *textField = [alertView textFieldAtIndex:0];
    
    if ([textField.text length] == 0)
        return;
    
    if (buttonIndex == 1)
        [self callAddMagazineWebServiceWithPassword:textField.text :_tempArticleId :_tempMagazineId :@""];
}

@end
