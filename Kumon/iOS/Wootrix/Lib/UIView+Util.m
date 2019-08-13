//
//  UView+Util.m
//  TotvsEmporium
//
//  Created by Factory Technologies on 7/29/16.
//  Copyright © 2016 DnaShopper. All rights reserved.
//

#import "UIView+Util.h"
#import "MBProgressHUD.h"
#import "MRProgress.h"

@implementation UIView (Util)

-(void) addGradientWithBase:(UIColor *)color
{
    CAGradientLayer *gradient = [CAGradientLayer layer];
    
    gradient.frame = self.bounds;
    gradient.colors = [NSArray arrayWithObjects:(id)[[UIColor colorWithRed:1 green:1 blue:1 alpha:.1] CGColor],
                       (id)[[UIColor colorWithRed:0 green:0 blue:0 alpha:.1] CGColor], nil];
    gradient.locations = [NSArray arrayWithObjects:[NSNumber numberWithInt:0.0],[NSNumber numberWithInt:1.0], nil];
    
    
    self.backgroundColor = color;
    [self.layer insertSublayer:gradient atIndex:0];
}


/*!
 gets the top most view
 */
- (UIViewController*)topViewController
{
    return [self topViewControllerWithRootViewController:[UIApplication sharedApplication].keyWindow.rootViewController];
}

/*!
 selector method used to find the top most view
 */
- (UIViewController*)topViewControllerWithRootViewController:(UIViewController*)rootViewController
{
    if ([rootViewController isKindOfClass:[UITabBarController class]])
    {
        UITabBarController* tabBarController = (UITabBarController*)rootViewController;
        return [self topViewControllerWithRootViewController:tabBarController.selectedViewController];
    }
    else if ([rootViewController isKindOfClass:[UINavigationController class]])
    {
        UINavigationController* navigationController = (UINavigationController*)rootViewController;
        return [self topViewControllerWithRootViewController:navigationController.visibleViewController];
    }
    else if (rootViewController.presentedViewController)
    {
        UIViewController* presentedViewController = rootViewController.presentedViewController;
        return [self topViewControllerWithRootViewController:presentedViewController];
    } else
        return rootViewController;
    
}

/*!
 displays a toast message
 */
-(void) ShowToast:(NSString *)toast
{
    //UIViewController *rootVc = [self topViewController];
    
    MBProgressHUD *hud = [MBProgressHUD showHUDAddedTo:[UIApplication sharedApplication].keyWindow animated:YES];
    
    // Set the annular determinate mode to show task progress.
    hud.mode = MBProgressHUDModeText;
    hud.label.text = toast;
    // Move to bottm center.
    hud.offset = CGPointMake(0.f, MBProgressMaxOffset);
    
    [hud hideAnimated:YES afterDelay:3.0f];
}

-(void) ShowWaitingProgress
{
    MRProgressOverlayView *overlayView = [MRProgressOverlayView showOverlayAddedTo:[UIApplication sharedApplication].keyWindow animated:YES];
    
    overlayView.tintColor = [UIColor blackColor];
    overlayView.mode = MRProgressOverlayViewModeIndeterminate;
    overlayView.titleLabelText = @"";
}

-(void) DismissWaitingProgress
{
    [MRProgressOverlayView dismissOverlayForView:[UIApplication sharedApplication].keyWindow animated:true];
}

-(void) ShowWaitingProgressForSelf
{
    MRProgressOverlayView *overlayView = [MRProgressOverlayView showOverlayAddedTo:self animated:YES];
    
    overlayView.tintColor = [UIColor blackColor];
    overlayView.mode = MRProgressOverlayViewModeIndeterminateSmall;
    overlayView.titleLabelText = @"";
}

-(void) DismissWaitingProgressForSelf
{
    [MRProgressOverlayView dismissOverlayForView:self animated:true];
}

- (UIImage *) imageFromView
{
    UIGraphicsBeginImageContextWithOptions(self.bounds.size, self.opaque, 0.0);
    [self.layer renderInContext:UIGraphicsGetCurrentContext()];
    
    UIImage * img = UIGraphicsGetImageFromCurrentImageContext();
    
    UIGraphicsEndImageContext();
    
    return img;
}

@end
