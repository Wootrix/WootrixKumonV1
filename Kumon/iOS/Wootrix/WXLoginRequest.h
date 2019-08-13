//
//  WXLoginRequest.h
//  Wootrix
//
//  Created by Mayank Pahuja on 12/01/15.
//  Copyright (c) 2015 Techahead. All rights reserved.
//

#import "JSONModel.h"

@interface WXLoginRequest : JSONModel

@property (nonatomic, strong) NSString *email;
@property (nonatomic, strong) NSString *password;
@property (nonatomic, strong) NSString *device;
@property (nonatomic, strong) NSString *osVersion;
@property (nonatomic, strong) NSString *socialAccountType;
@property (nonatomic, strong) NSString *socialAccountId;
@property (nonatomic, strong) NSString *socialAccountToken;
@property (nonatomic, strong) NSString *appLanguage;
@property (nonatomic, strong) NSString *name;
@property (nonatomic, strong) NSString *photoUrl;
@property (nonatomic, strong) NSString *latitude;
@property (nonatomic, strong) NSString *longitude;
@property (nonatomic, strong) NSString *deviceId;

@end
