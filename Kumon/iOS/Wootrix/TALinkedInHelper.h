//
//  TALinkedInHelper.h
//  Engrami
//
//  Created by Teena Nath Paul on 01/09/14.
//  Copyright (c) 2014 Teena Nath Paul. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "LIALinkedInHttpClient.h"
#import "LIALinkedInApplication.h"
#import "AFNetworking.h"

@interface TALinkedInHelper : NSObject

+ (TALinkedInHelper *)sharedInstance;
- (void)getLinkedInUserInfoForFields:(NSString *)fields completion:(void(^)(id resonse, NSError *error))block;
- (void)getLinkedInFriendsInfoWithCompletion:(void(^)(id resonse, NSError *error))block;
- (void)shareOnLinkedWithParams:(NSDictionary *)params SuccessBlock:(void(^)(id resonse, NSError *error))block;

@end
