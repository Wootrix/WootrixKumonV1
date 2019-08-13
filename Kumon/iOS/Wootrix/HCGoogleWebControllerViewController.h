//
//  HCGoogleWebControllerViewController.h
//  iHealthConnect
//
//  Created by Rahul Jain on 16/10/14.
//  Copyright (c) 2014 TechAhead. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface HCGoogleWebControllerViewController : UIViewController <UIWebViewDelegate>

- (IBAction)tapCancel:(id)sender;
@property (strong, nonatomic) NSURL *url;
@end
