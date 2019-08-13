//
//  WXSignupRequest.h
//  Wootrix
//
//  Created by Mayank Pahuja on 14/01/15.
//  Copyright (c) 2015 Techahead. All rights reserved.
//

#import "JSONModel.h"

@interface WXSignupRequest : JSONModel

@property (nonatomic, strong) NSString *name;
@property (nonatomic, strong) NSString *email;
@property (nonatomic, strong) NSString *password;
@property (nonatomic, strong) NSString *appLanguage;

@end
