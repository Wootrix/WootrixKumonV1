//
//  WXArticleModel.h
//  Wootrix
//
//  Created by Factory Technologies on 05/04/17.
//  Copyright © 2017 Techahead. All rights reserved.
//

#import <JSONModel/JSONModel.h>

@interface WXArticleModel : JSONModel

@property (nonatomic, strong) NSString *magazineId;
@property (nonatomic, strong) NSString *articleId;

@end
