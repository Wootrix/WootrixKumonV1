//
//  WXSubscribeMagazineModel.h
//  Wootrix
//
//  Created by Mayank Pahuja on 18/12/14.
//  Copyright (c) 2014 Techahead. All rights reserved.
//

#import "JSONModel.h"

@interface WXSubscribeMagazineModel : JSONModel

@property (nonatomic, strong)NSString *subscriptionPassword;
@property (nonatomic, strong)NSString *token;
@property (nonatomic, strong)NSString *appLanguage;


@end
