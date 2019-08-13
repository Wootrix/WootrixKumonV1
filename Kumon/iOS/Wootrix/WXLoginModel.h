//
//  WXLoginModel.h
//  Wootrix
//
//  Created by Mayank Pahuja on 12/12/14.
//  Copyright (c) 2014 Techahead. All rights reserved.
//

#import "JSONModel.h"

@interface WXLoginModel : JSONModel

@property (nonatomic, strong) NSString      *requireEmail;
@property (nonatomic, strong) NSString      *requirePassword;
@property (nonatomic, strong) NSString      *socialAccountToken;
@property (nonatomic, strong) NSString      *token;
@property (nonatomic, strong) NSString      *tokenExpiryDate;
@property (nonatomic, strong) NSDictionary  *user;

@end
