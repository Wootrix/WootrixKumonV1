//
//  WXLandingRequestModel.h
//  Wootrix
//
//  Created by Mayank Pahuja on 14/01/15.
//  Copyright (c) 2015 Techahead. All rights reserved.
//

#import "JSONModel.h"

@interface WXLandingRequestModel : JSONModel


@property (nonatomic, strong) NSString *token;
@property (nonatomic, strong) NSString *articleLanguage;
@property (nonatomic, strong) NSString *applanguage;
@property (nonatomic, strong) NSString *deviceId;
@property (nonatomic, strong) NSString *device;


@end
