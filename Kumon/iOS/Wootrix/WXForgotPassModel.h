//
//  WXForgotPassModel.h
//  Wootrix
//
//  Created by Mayank Pahuja on 18/12/14.
//  Copyright (c) 2014 Techahead. All rights reserved.
//

#import "JSONModel.h"

@interface WXForgotPassModel : JSONModel

@property (nonatomic, strong) NSString *email;
@property (nonatomic, strong) NSString *appLanguage;

@end
