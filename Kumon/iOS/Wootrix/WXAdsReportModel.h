//
//  WXAdsReportModel.h
//  Wootrix
//
//  Created by Mayank Pahuja on 18/02/15.
//  Copyright (c) 2015 Techahead. All rights reserved.
//

#import "JSONModel.h"

@interface WXAdsReportModel : JSONModel

@property (nonatomic, strong) NSString *type;
@property (nonatomic, strong) NSString *device;
@property (nonatomic, strong) NSString *token;
@property (nonatomic, strong) NSString *applanguage;
@property (nonatomic, strong) NSString *latitude;
@property (nonatomic, strong) NSString *longitude;
@property (nonatomic, strong) NSString *advertisementId;

@end
