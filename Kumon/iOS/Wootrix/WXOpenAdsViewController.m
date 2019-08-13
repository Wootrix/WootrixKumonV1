//
//  WXOpenAdsViewController.m
//  Wootrix
//
//  Created by Mayank Pahuja on 18/02/15.
//  Copyright (c) 2015 Techahead. All rights reserved.
//

#import "WXOpenAdsViewController.h"

@interface WXOpenAdsViewController ()

@end

@implementation WXOpenAdsViewController

- (void)viewDidLoad
{
   // [[NSNotificationCenter defaultCenter]removeObserver:@"openAdvertisment"];
    [[NSNotificationCenter defaultCenter]removeObserver:self name:@"addAdvertisment" object:nil];
    [[NSNotificationCenter defaultCenter]addObserver:self selector:@selector(openAdvertisementInSameView:) name:@"addAdvertisment" object:nil];
    _imgHeaderLogo.text =[WXOpenAdsViewController languageSelectedStringForKey:@"Advertisement"];
    [super viewDidLoad];
    // Do any additional setup after loading the view.
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}


- (void)viewDidAppear:(BOOL)animated
{
    [self showProgressHUD:[WXOpenAdsViewController languageSelectedStringForKey:@"Loading..."]];
    self.view.userInteractionEnabled = NO;
    

//  NSString *urlAddress = [_dictData objectForKey:@"source"];
  NSURL *url = [NSURL URLWithString:_linkUrl];
  NSURLRequest *requestObj = [NSURLRequest requestWithURL:url];
  _webViewAds.delegate = self;
  [_webViewAds loadRequest:requestObj];
  _webViewAds.scalesPageToFit = YES;

}


/*
#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}
*/


#pragma mark - button action

- (IBAction)btnBackTapped:(id)sender
{
  [self.navigationController popViewControllerAnimated:YES];
}

- (void)openAdvertisementInSameView: (NSNotification *)notification
{
    [self showProgressHUD:[WXOpenAdsViewController languageSelectedStringForKey:@"Loading..."]];
    self.view.userInteractionEnabled = NO;
    NSURL *url = [NSURL URLWithString:[notification.userInfo objectForKey:@"url"]];
    NSURLRequest *requestObj = [NSURLRequest requestWithURL:url];
    _webViewAds.delegate = self;
    [_webViewAds loadRequest:requestObj];
    _webViewAds.scalesPageToFit = YES;
}

- (void)webView:(UIWebView *)webView didFailLoadWithError:(NSError *)error {
    
   
}


- (void)webViewDidFinishLoad:(UIWebView *)webView {
    
    [self dismissProgressHUD];
     self.view.userInteractionEnabled = YES;
    
    
}


@end
