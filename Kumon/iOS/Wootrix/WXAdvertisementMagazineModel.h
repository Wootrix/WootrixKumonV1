//
//  WXAdvertisementMagazineModel.h
//  Wootrix
//
//  Created by Mayank Pahuja on 14/02/15.
//  Copyright (c) 2015 Techahead. All rights reserved.
//

#import "JSONModel.h"

@interface WXAdvertisementMagazineModel : JSONModel

@property (nonatomic, strong) NSString *platform;
@property (nonatomic, strong) NSString *magazineId;
@property (nonatomic, strong) NSString *articleLanguage;
@property (nonatomic, strong) NSString *layoutId;
@property (nonatomic, strong) NSString *applanguage;


@end
