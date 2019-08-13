//
//  WXUserArticle.h
//  Wootrix
//
//  Created by Factory Technologies on 25/07/17.
//  Copyright © 2017 Techahead. All rights reserved.
//

#import <JSONModel/JSONModel.h>

@interface WXUserArticle : JSONModel

@property (nonatomic, strong) NSString *userId;
@property (nonatomic, strong) NSString *magazineId;

@end
