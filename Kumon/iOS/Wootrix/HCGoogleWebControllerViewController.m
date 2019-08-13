//
//  HCGoogleWebControllerViewController.m
//  iHealthConnect
//
//  Created by Rahul Jain on 16/10/14.
//  Copyright (c) 2014 TechAhead. All rights reserved.
//

#import "HCGoogleWebControllerViewController.h"
//#import <GPPURLHandler.h>
#import <GooglePlus/GooglePlus.h>


@interface HCGoogleWebControllerViewController ()
@property (strong, nonatomic) IBOutlet UIWebView *webViewGoogle;

@end

@implementation HCGoogleWebControllerViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view from its nib.
    [self loadWebView];
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (void)loadWebView
{
    NSURLRequest *request = [[NSURLRequest alloc] initWithURL:self.url];
    self.webViewGoogle.delegate = self;
    [self.webViewGoogle loadRequest:request];
}


/*
 #pragma mark - Navigation
 
 // In a storyboard-based application, you will often want to do a little preparation before navigation
 - (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
 // Get the new view controller using [segue destinationViewController].
 // Pass the selected object to the new view controller.
 }
 */

- (BOOL)webView:(UIWebView *)webView
shouldStartLoadWithRequest:(NSURLRequest *)request
 navigationType:(UIWebViewNavigationType)navigationType
{
    //https://accounts.google.com/o/oauth2/approval
    NSLog(@" URL : %@",request.URL);
    
    if ([[[request URL] absoluteString] hasPrefix:@"com.pedro.wootrix:/oauth2callback"])
    {
        
        if ([[[request URL] absoluteString] hasPrefix:@"com.pedro.wootrix:/oauth2callback?error=access_denied&state"])
        {
            
        }
        else
        {
            
        }
        [GPPURLHandler handleURL:request.URL
               sourceApplication:@"com.apple.mobilesafari"
                      annotation:nil];
        [self dismissViewControllerAnimated:YES completion:nil];
        return NO;
    }
    
    
    return YES;
}

- (IBAction)tapCancel:(id)sender {
    [self dismissViewControllerAnimated:YES completion:nil];
}
@end
