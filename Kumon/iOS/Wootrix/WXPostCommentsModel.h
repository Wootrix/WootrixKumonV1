//
//  WXPostCommentsModel.h
//  Wootrix
//
//  Created by Mayank Pahuja on 18/12/14.
//  Copyright (c) 2014 Techahead. All rights reserved.
//

#import "JSONModel.h"

@interface WXPostCommentsModel : JSONModel


@property (nonatomic, strong) NSString *articleId;
@property (nonatomic, strong) NSString *token;
@property (nonatomic, strong) NSString *comment;
@property (nonatomic, strong) NSString *appLanguage;
@property (nonatomic, strong) NSString *type;

@end
