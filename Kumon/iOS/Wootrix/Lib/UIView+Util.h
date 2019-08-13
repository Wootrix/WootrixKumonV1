//
//  UView+Util.h
//  TotvsEmporium
//
//  Created by Factory Technologies on 7/29/16.
//  Copyright © 2016 DnaShopper. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface UIView (Util)

-(void) addGradientWithBase:(UIColor *)color;
/*!
 gets the top most view
 */
- (UIViewController*)topViewController;

/*!
 displays a toast message
 */
-(void) ShowToast:(NSString *)toast;

-(void) ShowWaitingProgress;

-(void) DismissWaitingProgress;

-(void) ShowWaitingProgressForSelf;

-(void) DismissWaitingProgressForSelf;

- (UIImage *) imageFromView;

@end
