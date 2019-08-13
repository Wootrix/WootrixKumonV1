//
//  WXAdvertisementOpenRequestModel.h
//  Wootrix
//
//  Created by Sunidhi Gupta on 03/02/15.
//  Copyright (c) 2015 Techahead. All rights reserved.
//

#import "JSONModel.h"

@interface WXAdvertisementOpenRequestModel : JSONModel

@property (nonatomic, strong) NSString *platform;
@property (nonatomic, strong) NSString *topics;
@property (nonatomic, strong) NSString *articleLanguage;
@property (nonatomic, strong) NSString *layoutId;
@property (nonatomic, strong) NSString *applanguage;

@end
