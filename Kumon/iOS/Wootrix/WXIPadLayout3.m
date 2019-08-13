//
//  WXIPadLayout3.m
//  Wootrix
//
//  Created by Mayank Pahuja on 28/01/15.
//  Copyright (c) 2015 Techahead. All rights reserved.
//

#import "WXIPadLayout3.h"
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
@implementation WXIPadLayout3

/*
// Only override drawRect: if you perform custom drawing.
// An empty implementation adversely affects performance during animation.
- (void)drawRect:(CGRect)rect {
    // Drawing code
}
*/

@end
