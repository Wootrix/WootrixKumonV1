//
//  ApiHeaderModel.h
//  TechaHeadBase
//
//  Created by Girijesh Kumar on 18/12/14.
//  Copyright (c) 2014 Girijesh Kumar. All rights reserved.
//

#import <Foundation/Foundation.h>

/**
 'ApiHeaderModel' is the model class for storing http header values.
 */
@interface ApiHeaderModel : NSObject

@property (nonatomic, strong) NSString* accept;
@property (nonatomic, strong) NSString* contentType;
@property (nonatomic, strong) NSString* acceptEncoding;
@property (nonatomic, strong) NSString* username;
@property (nonatomic, strong) NSString* password;

- (instancetype)initWithAccept:(NSString*)headerAccept
									 contentType:(NSString*)headerContentType
								acceptEncoding:(NSString*)headerAcceptEncoding
											username:(NSString*)headerUsername
											password:(NSString*)headerPassword;
@end
