


//
//  WXArticleDetailViewController.m
//  Wootrix
//
//  Created by Mayank Pahuja on 15/12/14.
//  Copyright (c) 2014 Techahead. All rights reserved.
//

#import "WXArticleDetailViewController.h"
#import "NSString+XMLEntities.h"
#import "NSString+HTML.h"
#import "WXShareDetailModel.h"
#import "WXMenuViewController.h"


@interface WXArticleDetailViewController ()

@end

@interface WXArticleDetailViewController (CommentService)

- (void)callServiceGetComments;

@end

@implementation WXArticleDetailViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    
    // Do any additional setup after loading the view.
    if (_imgHeader && [_type isEqualToString:@"magazine"]) {
        _imgHeaderLogo.image = _imgHeader;
        _imgHeaderLogo.contentMode = UIViewContentModeScaleAspectFit;
    }
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (void)viewDidAppear:(BOOL)animated
{
    [self setFontsAndColors];
    [self callServiceGetComments];
    
    if ([[_dictData objectForKey:@"createdBy"] boolValue] == NO) // source url
    {
        [self showProgressHUD:[WXArticleDetailViewController languageSelectedStringForKey:@"Loading..."]];
        [self.view setUserInteractionEnabled:NO];
        NSString *urlAddress = [_dictData objectForKey:@"fullSoruce"];
        NSURL *url = [NSURL URLWithString:urlAddress];
        NSURLRequest *requestObj = [NSURLRequest requestWithURL:url];
        _webViewDesc.delegate = self;
        [_webViewDesc loadRequest:requestObj];
    }
    else if ([_dictData objectForKey:@"articleDescHTML"])
    {
        //    [_dictData objectForKey:@"articleDescHTML"];
        NSData *decodedData = [[NSData alloc] initWithBase64EncodedString:[_dictData objectForKey:@"articleDescHTML"] options:0];
        
        NSString *decodedString = [[NSString alloc] initWithData:decodedData encoding:NSUTF8StringEncoding];
        
        NSString *htmlText = [NSString stringWithFormat:@"<!doctype html><html><head><meta charset=\"utf-8\"><meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\" /></head><body>%@</body></html>",decodedString];
        
        
        
        [_webViewDesc loadHTMLString:htmlText baseURL:nil];
        NSLog(@"Decode String Value: %@", htmlText);
    }
    
    _webViewDesc.scalesPageToFit = YES;
    
    //  [_lblTitle setText:[_dictData objectForKey:@"title"]];
    //  [_lblDate setText:[_dictData objectForKey:@"createdDate"]];
    //  [_lblWebURL setText:[_dictData objectForKey:@"source"]];
    //  [_txtViewDesc setText:[_dictData objectForKey:@"articleDescPlain"]];
}


- (void)webViewDidFinishLoad:(UIWebView *)webView
{
    [self dismissProgressHUD];
    [self.view setUserInteractionEnabled:YES];
}
- (void)webView:(UIWebView *)webView didFailLoadWithError:(NSError *)error
{
    [self dismissProgressHUD];
    [self.view setUserInteractionEnabled:YES];
}
#pragma mark - web service methods

/** This service is called to fetch article details of the given article id
 *
 *
 *  @param dictGetArticleDetails dictionary to fetch article details of particular id
 */
- (void)callServiceGetArticleDetailsWithDictionary:(NSDictionary*)dictGetArticleDetails
{
    
}


#pragma mark - other methods

/**
 *  This method is called to set the custom font sizes and colors of all objects present on the view
 */
- (void)setFontsAndColors
{
    [_lblNavBar setText:[WXArticleDetailViewController languageSelectedStringForKey:@"Article"]];
    
    if ([_type isEqualToString:@"magazine"])
    {
        if ([UserDefaults objectForKey:kMagazineColor] && [[UserDefaults objectForKey:kMagazineColor] length] > 0)
        {
            [_viewNavBar setBackgroundColor:[self colorWithHexString:[[UserDefaults objectForKey:kMagazineColor] stringByReplacingOccurrencesOfString:@"#" withString:@""]]];
        }
    }
    
    
    if ([[_dictData objectForKey:@"allowComment"] boolValue] == NO)
    {
        [_btnComment setHidden:YES];
        [_lblCommentsCount setHidden:YES];
    }
    if ([[_dictData objectForKey:@"allowShare"] boolValue] == NO)
    {
        [_btnShare setHidden:YES];
    }
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
 *  Tap on image to call this function. This opens image in full screen mode
 *
 *  @param image Takes in UIImage as parameter.
 */
- (void)openImageInFullScreen:(UIImage*)image
{
    
}


/**
 *  This method is called on tapping the share button
 *
 *  @param platform takes in enum sharingPlatform and specifies on which paltform sharing has to be done.
 *  @param params   describes the parameters of the article to be shared
 */
- (void)shareOn:(kSharingPlatform)platform withParameters:(id)params
{
    
}
#pragma mark -color

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

#pragma mark - button action
- (IBAction)btnBackTapped:(UIButton*)sender
{
    [self.navigationController popViewControllerAnimated:YES];
}
- (IBAction)btnCommentTapped:(UIButton*)sender
{
    if (isDeviceIPad)
    {
        WXCommentViewController *commentViewC = [[WXCommentViewController alloc] initWithNibName:@"WXCommentView" bundle:nil];
        [commentViewC setType:_type];
        [commentViewC setDictArticleData:_dictData];
        [commentViewC setDelegate:self];
        _navControllerPopover = nil;
        
        _navControllerPopover = [[UINavigationController alloc] initWithRootViewController:commentViewC];
        [_navControllerPopover setNavigationBarHidden:YES];
        
        _popOverViewC = [[UIPopoverController alloc] initWithContentViewController:_navControllerPopover];
        _popOverViewC.popoverContentSize = CGSizeMake(320, 568);
        [_popOverViewC presentPopoverFromRect:_btnComment.frame inView:self.view permittedArrowDirections:UIPopoverArrowDirectionAny animated:YES];
    }
    else
    {
        WXCommentViewController *commentViewC = [self.storyboard instantiateViewControllerWithIdentifier:@"WXCommentViewController"];
        [commentViewC setType:_type];
        [commentViewC setDictArticleData:_dictData];
        [self.navigationController pushViewController:commentViewC animated:YES];
    }
    
}

#pragma mark - utils

- (BOOL) stringIsEmpty:(NSString *) aString {
    
    if ((NSNull *) aString == [NSNull null])
    {
        return YES;
    }
    
    if (aString == nil)
    {
        return YES;
    }
    else if ([aString length] == 0)
    {
        return YES;
    }
    else
    {
        aString = [aString stringByTrimmingCharactersInSet: [NSCharacterSet whitespaceAndNewlineCharacterSet]];
        if ([aString length] == 0)
        {
            return YES;
        }
    }
    
    return NO;
}
- (IBAction)btnShareTapped:(UIButton*)sender
{
    //  UIActionSheet *aSheet = [[UIActionSheet alloc] initWithTitle:kAPPName delegate:self cancelButtonTitle:[WXArticleDetailViewController languageSelectedStringForKey:@"Cancel"] destructiveButtonTitle:@"Facebook" otherButtonTitles:@"Twitter",nil];
    //  [aSheet showInView:self.view];
    
    [self.view.topViewController showProgressHUD:@"..."];
    
    NSArray *arrTemp = [[NSString stringWithFormat:@"%@",[_dictData objectForKey:@"articleDescPlain"]] componentsSeparatedByString:@"\n"];
    NSString *firstPara = [NSString stringWithFormat:@"%@:\n%@",[_dictData objectForKey:@"title"],[arrTemp firstObject]];
    
    NSString *strForuse = [_dictData objectForKey:@"fullSoruce"];
    
    if ([self stringIsEmpty:strForuse])
    {
        strForuse = @"";
    }
    
    NSString *strToShare = [NSString stringWithFormat:@"%@\n %@",strForuse,[WXArticleDetailViewController languageSelectedStringForKey:@"Like me you can find relevant content on www.wootrix.com"]];
    
    strToShare = [strToShare stringByDecodingXMLEntities];
    
    
    NSURL *urlToShare = [NSURL URLWithString:[NSString stringWithFormat:@"%@",strForuse]];
    NSArray *objectsToShare;
    UIImage *imgToShare = [UIImage imageWithData:[NSData dataWithContentsOfURL:[NSURL URLWithString:[[_dictData objectForKey:@"coverPhotoUrl"] addURL]]]];
    
    if ([self stringIsEmpty:strForuse])
    {
        if ([_type isEqualToString:@"magazine"])
        {
            
            if ([_dictData objectForKey:@"createdSource"])
            {
                urlToShare = [NSURL URLWithString:[NSString stringWithFormat:@"%@/wootrix-article-detail?articleId=%@&magazineId=%@&source=%@",kBASEURL,[_dictData objectForKey:@"articleId"],_magazineId,[_dictData objectForKey:@"createdSource"]]];
            }
            else
            {
                urlToShare = [NSURL URLWithString:[NSString stringWithFormat:@"%@/wootrix-article-detail?articleId=%@&magazineId=%@&source=",kBASEURL,[_dictData objectForKey:@"articleId"],_magazineId]];
            }
            
        }
        else
        {
            urlToShare = [NSURL URLWithString:[NSString stringWithFormat:@"%@/wootrix-article-detail?articleId=%@",kBASEURL,[_dictData objectForKey:@"articleId"]]];
        }
        if (imgToShare)
        {
            strToShare = [NSString stringWithFormat:@"%@\n%@",firstPara,strToShare];
            strToShare = [strToShare stringByDecodingXMLEntities];
            objectsToShare = @[strToShare,imgToShare != nil ? imgToShare : @""/*,urlToShare*/];
        }
        else
        {
            NSURL *url = urlToShare;
            objectsToShare = @[strToShare,imgToShare != nil ? imgToShare : @""/*,url*/];
        }
        
    }
    else
    {
        urlToShare = [NSURL URLWithString:[NSString stringWithFormat:@"%@",strForuse]];
        objectsToShare = @[strToShare/*,urlToShare*/];
    }
    //NSURL *shareUrl = [NSURL URLWithString:@"http://www.google.com"];
    NSLog(@"Objects to share is %@",objectsToShare);
    
    
    NSArray *excludeActivities = @[UIActivityTypeAirDrop,
                                   UIActivityTypePrint,
                                   UIActivityTypeAssignToContact,
                                   UIActivityTypeSaveToCameraRoll,
                                   //UIActivityTypeMail,
                                   UIActivityTypePostToFlickr,
                                   UIActivityTypePostToVimeo];
    
    
    
    
    
    
    WXShareDetailModel *shareContent = [[WXShareDetailModel alloc] init];
    AppDelegate *delegate = (AppDelegate *)[[UIApplication sharedApplication] delegate];
    
    shareContent.articleId = [_dictData objectForKey:@"articleId"];
    shareContent.magazineId = [_magazineId length] == 0  || [_magazineId intValue] == -1 ? @"" : _magazineId;
    
    [[NetworkManager manager] requestWithMethod:kHTTPMethodPOST1
                                      URLString:@"getBranchDetails"
                                     parameters:shareContent
                                         sucess:^(id response)
     {
         
         [self.view.topViewController dismissProgressHUD];
         
         if ([[response objectForKeyNonNull:kSuccess] boolValue] == YES)
         {
             NSMutableArray *temp = [[NSMutableArray alloc] init];
             
             [temp addObject:[NSURL URLWithString:[[response objectForKey:@"data"] objectForKey:@"branchLink"]]];
             
             UIActivityViewController *activityVC = [[UIActivityViewController alloc] initWithActivityItems:temp applicationActivities:nil];
             
             activityVC.excludedActivityTypes = excludeActivities;
             
             
             if (UI_USER_INTERFACE_IDIOM() == UIUserInterfaceIdiomPhone)
             {
                 
             }
             //if iPad
             else
             {
                 if ([[[UIDevice currentDevice] systemVersion] floatValue] >= 8.0)
                 {
                     activityVC.popoverPresentationController.sourceView = _btnComment;
                 }
                 
             }
             
             
             //AppDelegate *apDelegate = (AppDelegate*)[[UIApplication sharedApplication] delegate];
             [self presentViewController:activityVC animated:YES completion:nil];
             
         }
         else
         {
             UIAlertController * alertcontroller = [UIAlertController alertControllerWithTitle:kAPPName
                                                                                       message:[NSString stringWithFormat:@"%@",[[response objectForKey:@"data"] objectForKey:kMessage]]
                                                                                preferredStyle:UIAlertControllerStyleAlert];
             
             UIAlertAction * ok = [UIAlertAction actionWithTitle:[WXMenuViewController languageSelectedStringForKey:@"Ok"]
                                                           style:UIAlertActionStyleDefault
                                                         handler:nil];
             
             [alertcontroller addAction:ok];
             
             [self.view.topViewController presentViewController:alertcontroller animated:NO completion:nil];
         }
     }failure:^(NSError *error)
     {
         NSLog(@"error %@", error);
         [self.view.topViewController dismissProgressHUD];
         
     }];
}
-(void)postToFacebook:(id)sender
{
    if([SLComposeViewController isAvailableForServiceType:SLServiceTypeFacebook]) {
        
        SLComposeViewController *controller = [SLComposeViewController composeViewControllerForServiceType:SLServiceTypeFacebook];
        
        [controller setInitialText:@"First post from my iPhone app"];
        [controller addURL:[NSURL URLWithString:@"http://www.appcoda.com"]];
        [controller addImage:[UIImage imageNamed:@"socialsharing-facebook-image.jpg"]];
        
        [self presentViewController:controller animated:YES completion:Nil];
        
    }
}
- (void)postToTwitter:(id)sender {
    if ([SLComposeViewController isAvailableForServiceType:SLServiceTypeTwitter])
    {
        SLComposeViewController *tweetSheet = [SLComposeViewController
                                               composeViewControllerForServiceType:SLServiceTypeTwitter];
        [tweetSheet setInitialText:@"Great fun to learn iOS programming at appcoda.com!"];
        [self presentViewController:tweetSheet animated:YES completion:nil];
    }
}

#pragma mark - comment delegate

- (void)getCommentNumber:(NSInteger)comments
{
    [_lblCommentsCount setText:[NSString stringWithFormat:@"%li",(long)comments]];
}

@end

@implementation WXArticleDetailViewController (CommentService)

- (void)callServiceGetComments
{
    WXGetCommentsModel *getComments = [[WXGetCommentsModel alloc] init];
    [getComments setArticleId           :[_dictData objectForKey:@"articleId"]];
    [getComments setToken               :[UserDefaults objectForKey:kToken]]; //6 Ashok
    [getComments setAppLanguage         :[UserDefaults objectForKey:kAppLanguage]?[UserDefaults objectForKey:kAppLanguage]:@"en"];
    [getComments setType                :_type];
    
    [[NetworkManager manager] requestWithMethod:kHTTPMethodPOST1
                                      URLString:@"getComments"
                                     parameters:getComments
                                         sucess:^(id response)
     {
         
         if ([[response objectForKey:kSuccess] boolValue] == YES)
         {
             [_lblCommentsCount setText:[NSString stringWithFormat:@"%d",[[[[response objectForKey:kData] firstObject] objectForKey:@"comments"] count]]];
             
         }
         else
         {
             
         }
         
         
     }failure:^(NSError *error)
     {
         
     }];
    
}



@end
