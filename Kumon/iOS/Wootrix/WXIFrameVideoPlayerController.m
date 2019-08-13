//
//  WXIFrameVideoPlayerController.m
//  Wootrix
//
//  Created by Teena Nath Paul on 24/07/15.
//  Copyright (c) 2015 Techahead. All rights reserved.
//

#import "WXIFrameVideoPlayerController.h"
#import "WXCommentViewController.h"
#import "WXArticleDetailViewController.h"
#import <Social/Social.h>
#import "WXMenuViewController.h"
#import "WXShareDetailModel.h"

@interface WXIFrameVideoPlayerController ()<CommentDelegate>

@property (weak, nonatomic) IBOutlet UIWebView *webView;
@property (strong, nonatomic) IBOutlet UILabel *lblCommentsCount;
@property (weak, nonatomic) IBOutlet UIButton *btnComment;
@property (weak, nonatomic) IBOutlet UIButton *btnShare;
@property (weak, nonatomic) IBOutlet UILabel *lblNavBar;
@property (weak, nonatomic) IBOutlet UIView *viewNavBar;

@property (strong, nonatomic) UINavigationController *navControllerPopover;
@property (strong, nonatomic) UIPopoverController *popOverViewC;
@end

@interface WXIFrameVideoPlayerController (CommentService)

- (void)callServiceGetComments;

@end

@implementation WXIFrameVideoPlayerController

@synthesize  Pushed = _pushed;

- (void)viewDidLoad
{
    [super viewDidLoad];
    // Do any additional setup after loading the view.
    //<iframe width="560" height="315" src="https://www.youtube.com/embed/E6SdDdTWVSQ" frameborder="0" allowfullscreen></iframe>
    if(self.iFrameVideoType  == iFrameVideoTypeAdvertisement)
    {
        self.strIFrameURL = [self.dictData objectForKey:@"videoURL"];
        _lblCommentsCount.hidden = YES;
        _btnComment.hidden = YES;
        _btnShare.hidden = YES;
    }
    else
    {
        self.strIFrameURL = [self.dictData objectForKey:@"embedded_video"];
    }
    
    _webView.scalesPageToFit = YES;
    [self playEmbedYouTubeVideo];
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}


- (void)playEmbedYouTubeVideo
{
    NSString* embedHTML = @"\
    <html><head>\
    <style type=\"text/css\">\
    body {\
    background-color: transparent;\
    color: white;\
    }\
    </style>\
    </head><body style=\"margin:0\">\
    <center>\
    %@\
    </center>\
    </body></html>";
    
    NSString* html = [NSString stringWithFormat:embedHTML, _strIFrameURL];
    [_webView loadHTMLString:html baseURL:[[NSBundle mainBundle] resourceURL]];
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


- (IBAction)tapBackButton:(UIButton *)sender
{
    if (_pushed)
        [self.navigationController popViewControllerAnimated:true];
    else
        [self dismissViewControllerAnimated:YES completion:nil];
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
        [_popOverViewC presentPopoverFromRect:sender.frame inView:self.view permittedArrowDirections:UIPopoverArrowDirectionAny animated:YES];
    }
    else
    {
        WXCommentViewController *commentViewC = [self.storyboard instantiateViewControllerWithIdentifier:@"WXCommentViewController"];
        [commentViewC setType:_type];
        [commentViewC setDictArticleData:_dictData];
        [self.navigationController pushViewController:commentViewC animated:YES];
        //		[self dismissViewControllerAnimated:YES completion:nil];
        //		[_delegate openMessageFromEmbedwithType:_type andData:_dictData];
    }
    
}

- (IBAction)btnShareTapped:(UIButton*)sender
{
    //  UIActionSheet *aSheet = [[UIActionSheet alloc] initWithTitle:kAPPName delegate:self cancelButtonTitle:[WXArticleDetailViewController languageSelectedStringForKey:@"Cancel"] destructiveButtonTitle:@"Facebook" otherButtonTitles:@"Twitter",nil];
    //  [aSheet showInView:self.view];
    
    [self.view.topViewController showProgressHUD:@"..."];
    
    NSArray *arrTemp = [[NSString stringWithFormat:@"%@",[_dictData objectForKey:@"articleDescPlain"]] componentsSeparatedByString:@"\n"];
    NSString *firstPara = [NSString stringWithFormat:@"%@:\n%@",[_dictData objectForKey:@"title"],[arrTemp firstObject]];
    
    NSString *strToShare = [NSString stringWithFormat:@"%@",[WXArticleDetailViewController languageSelectedStringForKey:@"Like me you can find relevant content on www.wootrix.com"]];
    strToShare = [NSString stringWithFormat:@"%@\n%@",firstPara,strToShare];
    
    
    NSURL *urlToShare = [NSURL encodedURLWithString:[NSString stringWithFormat:@"%@",[_dictData objectForKey:@"embedded_video_link"]]];
    UIImage *imgToShare = [UIImage imageWithData:[NSData dataWithContentsOfURL:[NSURL encodedURLWithString:[_dictData objectForKey:@"embedded_thumbnail"]]]];
    NSArray *objectsToShare = @[];
    if (imgToShare) {
        objectsToShare = @[strToShare,imgToShare,urlToShare];
    }
    else {
        objectsToShare = @[strToShare,urlToShare];
    }
    
    
   // UIActivityViewController *activityVC = [[UIActivityViewController alloc] initWithActivityItems:objectsToShare applicationActivities:nil];
    
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
    shareContent.magazineId = [_magazineId length] == 0 || [_magazineId intValue] == -1 ? @"" : _magazineId;
    
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
    if([SLComposeViewController isAvailableForServiceType:SLServiceTypeFacebook])
    {
        SLComposeViewController *controller = [SLComposeViewController composeViewControllerForServiceType:SLServiceTypeFacebook];
        [controller setInitialText:@"First post from my iPhone app"];
        [controller addURL:[NSURL URLWithString:@"http://www.appcoda.com"]];
        [controller addImage:[UIImage imageNamed:@"socialsharing-facebook-image.jpg"]];
        [self presentViewController:controller animated:YES completion:Nil];
    }
}
- (void)postToTwitter:(id)sender
{
    if ([SLComposeViewController isAvailableForServiceType:SLServiceTypeTwitter])
    {
        SLComposeViewController *tweetSheet = [SLComposeViewController
                                               composeViewControllerForServiceType:SLServiceTypeTwitter];
        [tweetSheet setInitialText:@"Great fun to learn iOS programming at appcoda.com!"];
        [self presentViewController:tweetSheet animated:YES completion:nil];
    }
}

- (void)getCommentNumber:(NSInteger)comments
{
    [_lblCommentsCount setText:[NSString stringWithFormat:@"%li",(long)comments]];
}



@end

@implementation WXIFrameVideoPlayerController (CommentService)


- (void)callServiceGetComments
{
    WXGetCommentsModel *getComments = [[WXGetCommentsModel alloc] init];
    [getComments setArticleId           :[_dictData objectForKey:@"articleId"]];
    [getComments setToken               :[UserDefaults objectForKey:kToken]]; //6 Ashok
    [getComments setAppLanguage         :[UserDefaults objectForKey:kAppLanguage]?[UserDefaults objectForKey:kAppLanguage]:@"en"];
    [getComments setType                : _type];
    
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
