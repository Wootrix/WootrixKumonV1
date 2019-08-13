//
//  WXOpenArticlesModel.h
//  Wootrix
//
//  Created by Mayank Pahuja on 16/01/15.
//  Copyright (c) 2015 Techahead. All rights reserved.
//

#import "JSONModel.h"

@interface WXOpenArticlesModel : JSONModel

@property (nonatomic, strong) NSString *deviceType;
@property (nonatomic, strong) NSString *pageNumber;
@property (nonatomic, strong) NSString *topics;
@property (nonatomic, strong) NSString *articleLanguage;
@property (nonatomic, strong) NSString *token;
@property (nonatomic, strong) NSString *appLanguage;
@property (nonatomic, strong) NSString *deviceId;
@property (nonatomic, strong) NSString *device;
@end
