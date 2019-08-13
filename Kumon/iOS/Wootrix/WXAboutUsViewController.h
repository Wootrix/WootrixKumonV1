//
//  WXAboutUsViewController.h
//  Wootrix
//
//  Created by Mayank Pahuja on 17/12/14.
//  Copyright (c) 2014 Techahead. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface WXAboutUsViewController : UIViewController


/**
 *  Called on tapping the back button
 *
 *  @param sender object of UIButton
 */
- (IBAction)btnBackTapped:(UIButton*)sender;

@property (nonatomic, strong) IBOutlet UILabel *lblNavBar;
@property (nonatomic, strong) IBOutlet UIWebView *webViewAboutUs;


@end
