//
//  Categories.h
//  Wootrix
//
//  Copyright (c) 2014 Techahead Software. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "SVProgressHUD.h"

//========================  UIView Category ====================
@interface UIView (SubviewHunting)
- (UIView*) huntedSubviewWithClassName:(NSString*) className;
- (UIView*) huntedSuperViewWithClassName:(Class) className;
- (void) debugSubviews;
- (void) debugSubviews:(NSUInteger) count;

- (void)setHeight:(CGFloat)height;
- (void)setWidth:(CGFloat)height;
- (void)setOrigin:(CGPoint)point;
- (void)setXOrigin:(CGFloat)x;
- (void)setYOrigin:(CGFloat)y;
- (CGFloat)width;
- (CGFloat)height;
- (CGFloat)xOrigin;
- (CGFloat)yOrigin;
@end

//========================  NSDictionary Category ====================
@interface NSDictionary (DictExpanded)
- (id)objectForKeyNonNull:(id)aKey;
@end

//========================  NSMutableDictionary Category ====================
@interface NSMutableDictionary (Expanded)
- (id)objectForKeyNonNull:(id)aKey;
@end

@interface NSString (NSStringExtended)
- (BOOL) appendToFile;
@end

@interface UIViewController (ProgressHud)

- (void)showProgressHUD:(NSString*)statusMsg;
- (void)dismissProgressHUD;
+ (NSString*)languageSelectedStringForKey:(NSString*)key;

@end

@interface NSArray (Reverse)

- (NSArray *)reversedArray;
@end

@interface NSMutableArray (Reverse)

- (void)reverse;

@end

@interface NSURL (Extended)

+ (NSURL *)encodedURLWithString:(NSString *)urlString;

@end

@interface Categories : NSObject

@end
