//
//  WXSearchArticlesModel.h
//  Wootrix
//
//  Created by Mayank Pahuja on 21/01/15.
//  Copyright (c) 2015 Techahead. All rights reserved.
//

#import "JSONModel.h"

@interface WXSearchArticlesModel : JSONModel

@property (nonatomic, strong) NSString *searchKeyword;
@property (nonatomic, strong) NSString *topics;
@property (nonatomic, strong) NSString *articleLanguage;
@property (nonatomic, strong) NSString *token;
@property (nonatomic, strong) NSString *applanguage;


@end
