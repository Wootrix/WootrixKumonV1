//
//  NetworkDataController.m
//  TechaHeadBase
//
//  Created by Girijesh Kumar on 18/12/14.
//  Copyright (c) 2014 Girijesh Kumar. All rights reserved.
//

#import "NetworkDataController.h"
#import "NetworkURLs.h"

@implementation NetworkDataController

static NetworkDataController *sharedInstance;

#pragma mark - Alloc Singleton Class Object
+ (instancetype)sharedInstance
{
 static dispatch_once_t onceToken;
 dispatch_once(&onceToken, ^{
     
	
	sharedInstance = [[NetworkDataController alloc] init];
	
 });
 return sharedInstance;
}
+ (id)alloc
{
 @synchronized(self)
 {
	NSAssert(sharedInstance == nil, @"Attempted to allocate a second instance of a singleton NetworkDataController.");
	sharedInstance = [super alloc];
 }
 
 return sharedInstance;
}
#pragma mark - PUBLIC METHODS
static NSDateFormatter *dateFormatter;
- (NSDateFormatter*)dateFormatter {
	
	static dispatch_once_t onceToken;
	dispatch_once(&onceToken, ^{
		
		dateFormatter = [[NSDateFormatter alloc] init];
		[dateFormatter setLocale:[NSLocale localeWithLocaleIdentifier:@"en_US_POSIX"]];
		[dateFormatter setDateFormat:@"yyyy-MM-dd'T'HH:mm:ss.SSSZ"];
		
	});
	
	return dateFormatter;
}
- (ApiHeaderModel*)apiHeaderModel {
 
 NSString* httpHeaderValueAccept = @"application/vnd.ds.fixture.v1+json";
 NSString* httpHEaderValueContentType = @"application/json";
 NSString* httpHeaderValueAcceptEncoding = @"application/json";
 NSString* httpHeaderValueUsername = kHTTPUsername;
 NSString* httpHeaderValuePassword = kHTTPPassword;
 
 ApiHeaderModel* apiHeaderModel = [[ApiHeaderModel alloc] initWithAccept:httpHeaderValueAccept
																													contentType:httpHEaderValueContentType
																											 acceptEncoding:httpHeaderValueAcceptEncoding
																														 username:httpHeaderValueUsername
																														 password:httpHeaderValuePassword];
 
 return apiHeaderModel;
}



- (ServerRequestHeaderJsonModel*)serverHeaderJsonModel {
	
	ServerRequestHeaderJsonModel* requestHeader = [[ServerRequestHeaderJsonModel alloc] init];
	//requestHeader.scannerId = commonParameters.scannerId;
	return requestHeader;
}

@end
