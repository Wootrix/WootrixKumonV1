//
//  WXOpenAdsViewController.h
//  Wootrix
//
//  Created by Mayank Pahuja on 18/02/15.
//  Copyright (c) 2015 Techahead. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface WXOpenAdsViewController : UIViewController<UIWebViewDelegate>


@property (nonatomic, strong) IBOutlet UILabel *lblNavBar;
@property (nonatomic, strong) IBOutlet UIWebView *webViewAds;
@property (nonatomic, strong) NSString *linkUrl;
@property (weak, nonatomic) IBOutlet UILabel *imgHeaderLogo;


@end
