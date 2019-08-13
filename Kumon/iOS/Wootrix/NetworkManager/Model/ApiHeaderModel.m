//
//  ApiHeaderModel.m
//  TechaHeadBase
//
//  Created by Girijesh Kumar on 18/12/14.
//  Copyright (c) 2014 Girijesh Kumar. All rights reserved.
//

#import "ApiHeaderModel.h"

@implementation ApiHeaderModel

@synthesize accept;
@synthesize acceptEncoding;
@synthesize contentType;
@synthesize username;
@synthesize password;

- (instancetype)initWithAccept:(NSString*)headerAccept
									 contentType:(NSString*)headerContentType
								acceptEncoding:(NSString*)headerAcceptEncoding
											username:(NSString*)headerUsername
											password:(NSString*)headerPassword
{
 self = [super init];
 if (self) {
	self.accept = headerAccept;
	self.contentType = headerContentType;
	self.acceptEncoding = headerAcceptEncoding;
	self.username = headerUsername;
	self.password = headerPassword;
 }
 return self;
}

@end
