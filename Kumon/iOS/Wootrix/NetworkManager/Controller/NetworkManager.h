//
//  NetworkManager.h
//  TechaHeadBase
//
//  Created by Girijesh Kumar on 18/12/14.
//  Copyright (c) 2014 Girijesh Kumar. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "AFNetworking.h"
#import "NetworkDataController.h"
#import "NetworkURLs.h"

@class JSONModel;

typedef enum{
 
 kHTTPMethodGET1 = 0,
 kHTTPMethodPOST1,
 kHTTPMethodPOSTMultiPart1,
}kHTTPMethod;

/**
 'NetworkManager' subclass of 'AFHTTPRequestOperationManager' is the singleton class performs
 all network operations over HTTP or HTTPS protocol over GET, POST http methods.
 
 This sigleton class creates single 'NSOperationQueue' in which all web service calls goes as an
 'NSOperation'. This gives the flexibility to know what all web services are running at particular
 time and gives useful methods to cancel all or any operation.
 
 ## NOTE: Do not alloc init this class.
 */
@interface NetworkManager : AFHTTPRequestOperationManager

/**
 Creates the Singleton Object of 'NetworkManager'.
 @return singleton Object of 'NetworkManager'.
 */
+ (instancetype)manager;


/**
 Initiates HTTPS or HTTP request over |kHTTPMethod| method and returns success or failure block.
 @param method is enum type declared in |kHTTPMethod|. 
 @param URLString The URL string used to create the request URL.. Eg: api/SplashScreen.
 @param parameters The parameters to be encoded according to the client request serializer.
 @param success A block object to be executed when the request operation finishes successfully. 
 This block has no return value and takes 1 argument: the response object created by the client 
 response serializer.
 @param failure A block object to be executed when the request operation finishes unsuccessfully, 
 or that finishes successfully, but encountered an error while parsing the response data. This block
 has no return value and takes one argument: the error describing the network or parsing error 
 that occurred.
 */
- (void)requestWithMethod:(kHTTPMethod)method
								URLString:(NSString *)URLString
							 parameters:(JSONModel*)parameters
									 sucess:(void (^)(id response))success
									failure:(void (^)(NSError *error))failure;

- (void)requestNonJsonModelWithMethod:(kHTTPMethod)method
                            URLString:(NSString *)URLString
                           parameters:(id)newParameters
                               sucess:(void (^)(id response))success
                              failure:(void (^)(NSError *error))failure;

+ (void)callServiceWithImages:(NSData *)imageData params:(NSDictionary *)params  serviceIdentifier:(NSString*)serviceName success:(void (^)(id response))success
                      failure:(void (^)(NSError *error))failure;
/**
 Cancels all HTTP requests
 */
- (void)cancelAllRequests;

/**
 Cancel particular request identified with |URLString|.
 */
- (void)cancelRequestWithURLString:(NSString*)URLString;

/**
 Returns number of requests submitted to Manager.
 */
- (NSUInteger)numberOfRequestsInManager;

/**
 Recursive function to check whether device is connected to WiFi upto 3 times.
 @return return boolean if it was success or failure.
 */
- (BOOL)isConnectedToWiFi;

/**
 *  <#Description#>
 *
 *  @return <#return value description#>
 */
- (BOOL)isConnectedToNetwork;

@end
