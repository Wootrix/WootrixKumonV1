//
//  WXLandingResponseModel.h
//  Wootrix
//
//  Created by Mayank Pahuja on 16/01/15.
//  Copyright (c) 2015 Techahead. All rights reserved.
//

#import "JSONModel.h"

@interface WXLandingResponseModel : JSONModel

@property (strong, nonatomic) NSString *allowComment;
@property (strong, nonatomic) NSString *allowShare;
@property (strong, nonatomic) NSString *articleDesc;
@property (strong, nonatomic) NSString *articleDescPlain;
@property (strong, nonatomic) NSString *articleId;
@property (strong, nonatomic) NSString *articleType;
@property (strong, nonatomic) NSString *articleVideoUrl;
@property (strong, nonatomic) NSString *commentsCount;
@property (strong, nonatomic) NSString *coverPhotoUrl;
@property (strong, nonatomic) NSString *createdBy;
@property (strong, nonatomic) NSString *createdDate;
@property (strong, nonatomic) NSString *source;
@property (strong, nonatomic) NSString *title;
@property (nonatomic, strong) NSString *applanguage;


@end
