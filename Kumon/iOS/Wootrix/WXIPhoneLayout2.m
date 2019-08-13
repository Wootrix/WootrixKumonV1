//
//  WXIPhoneLayout2.m
//  Wootrix
//
//  Created by Mayank Pahuja on 15/01/15.
//  Copyright (c) 2015 Techahead. All rights reserved.
//

#import "WXIPhoneLayout2.h"

@implementation UIView (ViewExtension)

+ (id)loadXIB:(NSString *)nibName forClass:(Class)classType
{
  NSArray *nib = [[NSBundle mainBundle] loadNibNamed:nibName owner:self options:nil];
  for (id obj in nib)
  {
    if ([obj isKindOfClass:classType])
    {
      return obj;
      break;
    }
  }
  return nil;
}

@end

@implementation WXIPhoneLayout2

/*
// Only override drawRect: if you perform custom drawing.
// An empty implementation adversely affects performance during animation.
- (void)drawRect:(CGRect)rect {
    // Drawing code
}
*/

@end
