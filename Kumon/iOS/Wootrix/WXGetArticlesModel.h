//
//  WXGetArticlesModel.h
//  Wootrix
//
//  Created by Mayank Pahuja on 18/12/14.
//  Copyright (c) 2014 Techahead. All rights reserved.
//

#import "JSONModel.h"

@interface WXGetArticlesModel : JSONModel

@property (nonatomic, strong) NSString *deviceType;
@property (nonatomic, strong) NSString *pageNumber;
@property (nonatomic, strong) NSString *topics;
@property (nonatomic, strong) NSString *articleLanguage;
@property (nonatomic, strong) NSString *token;
@property (nonatomic, strong) NSString *magazineId;
@property (nonatomic, strong) NSString *appLanguage;

@end
