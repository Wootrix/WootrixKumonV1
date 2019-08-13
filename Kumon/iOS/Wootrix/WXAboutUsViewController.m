//
//  WXAboutUsViewController.m
//  Wootrix
//
//  Created by Mayank Pahuja on 17/12/14.
//  Copyright (c) 2014 Techahead. All rights reserved.
//

#import "WXAboutUsViewController.h"

@interface WXAboutUsViewController ()

@end

@implementation WXAboutUsViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view.
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (void)viewDidAppear:(BOOL)animated
{
  
  [_lblNavBar setText:[WXAboutUsViewController languageSelectedStringForKey:@"About Us"]];
  
  
  NSString *urlAddress = @"http://wootrix.com/index.php/wootrix-about-us";
  NSURL *url = [NSURL URLWithString:urlAddress];
  NSURLRequest *requestObj = [NSURLRequest requestWithURL:url];
//  _webViewAboutUs.delegate = self;
  [_webViewAboutUs loadRequest:requestObj];
  _webViewAboutUs.scalesPageToFit = YES;
}

#pragma mark - button action
/**
 *  This method is called to set the custom font sizes and colors of all objects present on the view
 */
- (void)setFontsAndColors
{
  
}

#pragma mark - button action

/**
 *  Called on tapping the back button
 *
 *  @param sender object of UIButton
 */
- (IBAction)btnBackTapped:(UIButton*)sender
{
  [self.navigationController popViewControllerAnimated:YES];
}



@end
