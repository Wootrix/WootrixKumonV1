//
//  WXInsertUserSelectedTabModel.h
//  Wootrix
//
//  Created by Mayank Pahuja on 23/04/15.
//  Copyright (c) 2015 Techahead. All rights reserved.
//

#import "JSONModel.h"

@interface WXInsertUserSelectedTabModel : JSONModel
@property (nonatomic, strong) NSString *category;
@property (nonatomic, strong) NSString *user_id;
@property (nonatomic, strong) NSString *web_language;
@property (nonatomic, strong) NSString *article_language;
@end
