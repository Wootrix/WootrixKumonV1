//
//  NetworkDataController.h
//  TechaHeadBase
//
//  Created by Girijesh Kumar on 18/12/14.
//  Copyright (c) 2014 Girijesh Kumar. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "ApiHeaderModel.h"
#import "ServerRequestHeaderJsonModel.h"

/**
 'NetworkDataController' keeps the Hard Coded HTTP header values.
	It also keeps the recent HTTP request common parameters returned from server in 'serverReplyHeader'
	and store it persistent in core data for future use.
 */
@interface NetworkDataController : NSObject


/**
 Creates the Singleton Object of 'NetworkDataController'.
 @return singleton Object of 'NetworkDataController'.
 */
+ (instancetype)sharedInstance;
/**
 *	returns the date formatter being used.
 */
- (NSDateFormatter*)dateFormatter;
/**
 Create 'ApiHeaderModel' model for HTTP header values.
 @return 'ApiHeaderModel' Object.
 */
- (ApiHeaderModel*)apiHeaderModel;


/**
 *	return 'ServerHeaderJsonModel' initialized with common parameters.
 */
- (ServerRequestHeaderJsonModel*)serverHeaderJsonModel;

@end
