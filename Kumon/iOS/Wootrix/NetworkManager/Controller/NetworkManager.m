//
//  NetworkManager.m
//  TechaHeadBase
//
//  Created by Girijesh Kumar on 18/12/14.
//  Copyright (c) 2014 Girijesh Kumar. All rights reserved.
//

#import "NetworkManager.h"
#import "ServerResponseRootJsonModel.h"
#import "Config.h"
#import "NetworkURLs.h"
#import "AESCrypt.h"
#import <Reachability/Reachability.h>

#define kAuthentication @"Authentication"

#define kEncryptionKey      @"Wootrix"

#define kBundleVersion @"BundleVersion"

@interface NetworkManager ()

/**
 * Get 'ApiHeaderModel' model for HTTP header values from 'NetworkDataController'
 * @return 'ApiHeaderModel' Object.
 */
- (ApiHeaderModel*)apiHeaderModel;
/**
 * Get 'ServerRequestHeaderJsonModel' model for HTTP request common parameters from 'NetworkDataController'.
 * @return 'ServerRequestHeaderJsonModel' Object.
 */
- (ServerRequestHeaderJsonModel*)apiCommonParameters;
/**
 *	notification method when reachability status changes.
 */
- (void)reachabilityDidChangeNotification:(NSNotification*)notif;
@end

@implementation NetworkManager



static NetworkManager *manager;

#pragma mark - Alloc Singleton Class Object
+ (instancetype)manager
{
 static dispatch_once_t onceToken;
 dispatch_once(&onceToken, ^{
	
	manager = [[NetworkManager alloc] initWithBaseURL:[NSURL URLWithString:kBASEURL]];
	 manager.securityPolicy.allowInvalidCertificates = YES;
  [manager.reachabilityManager startMonitoring];
	 
	[[NSNotificationCenter defaultCenter] addObserver:manager
																					 selector:@selector(reachabilityDidChangeNotification:)
																							 name:AFNetworkingReachabilityDidChangeNotification
																						 object:nil];

 });
  manager.requestSerializer = [AFJSONRequestSerializer serializer];
	[manager.requestSerializer setAuthorizationHeaderFieldWithUsername:kHTTPUsername
																														password:kHTTPPassword];

 return manager;
}
- (instancetype)initWithBaseURL:(NSURL *)url {
	
	self = [super initWithBaseURL:url];
	if (!self) {
		return nil;
	}
	return self;
}
+ (id)alloc
{
 @synchronized(self)
 {
	NSAssert(manager == nil, @"Attempted to allocate a second instance of a singleton NetworkManager.");
	manager = [super alloc];
 }
 
 return manager;
}
- (void)dealloc {
	
	[[NSNotificationCenter defaultCenter] removeObserver:manager
                                                  name:AFNetworkingReachabilityDidChangeNotification
                                                object:nil];
}
#pragma mark - PRIVATE METHODS
- (void)reachabilityDidChangeNotification:(NSNotification*)notif {
    
    TRC_ENTRY()
	switch (manager.reachabilityManager.networkReachabilityStatus) {
		case AFNetworkReachabilityStatusNotReachable:
		{
			DDLogWarn(@"Network not reachable");
		}
			break;
		case AFNetworkReachabilityStatusReachableViaWiFi:
		{
			DDLogInfo(@"Network reachable with WiFi");
		}
			break;
		case AFNetworkReachabilityStatusReachableViaWWAN:
		{
			DDLogInfo(@"Network reachable with WWAN");
		}
			break;
		case AFNetworkReachabilityStatusUnknown:
		{
			DDLogInfo(@"Network status unknown");
		}
			break;
		default:
			break;
	}
}
- (ApiHeaderModel*)apiHeaderModel{
 
 ApiHeaderModel* apiHeader = [[NetworkDataController sharedInstance] apiHeaderModel];
 return apiHeader;
 
}
- (ServerRequestHeaderJsonModel*)apiCommonParameters {
	
	return [[NetworkDataController sharedInstance] serverHeaderJsonModel];
}

+(NSString *)encryptRequestString:(NSString *)requestStr
{
  NSString *plainTextStr=[requestStr stringByAppendingString:[NSString stringWithFormat:@"_%f",[NetworkManager getCurrentTimeStamp]]];
  NSString *encyptedStrng=[AESCrypt encrypt:plainTextStr password:kEncryptionKey];
  DDLogInfo(@"encyptedStrng %@",encyptedStrng);
  NSString *decryptedStrng=[AESCrypt decrypt:encyptedStrng password:kEncryptionKey];
  DDLogInfo(@"decryptedStrng %@",decryptedStrng);
  return encyptedStrng;
}

+(NSTimeInterval )getCurrentTimeStamp
{
  NSTimeInterval timeInterval=[[NSDate date] timeIntervalSince1970];
  return timeInterval;
}

#pragma mark - Methods Overriden
- (AFHTTPRequestOperation *)HTTPRequestOperationWithRequest:(NSURLRequest *)request
                                                    success:(void (^)(AFHTTPRequestOperation *operation,
																																			id responseObject))success
                                                    failure:(void (^)(AFHTTPRequestOperation *operation,
																																			NSError *error))failure
{
	//DDLogInfo(@"Final Request %@", request.URL.absoluteString);
	return [super HTTPRequestOperationWithRequest:(NSURLRequest*)request
																				success:success
																				failure:failure];
}

+ (void)callServiceWithImages:(NSData *)imageData params:(NSDictionary *)params  serviceIdentifier:(NSString*)serviceName success:(void (^)(id response))success
                     failure:(void (^)(NSError *error))failure
{
  AFHTTPRequestOperationManager *manager = [AFHTTPRequestOperationManager manager];
  manager.requestSerializer = [AFJSONRequestSerializer serializer];
  [manager.requestSerializer setValue:@"application/json" forHTTPHeaderField:@"Content-Type"];
  [manager.requestSerializer setValue:@"application/json" forHTTPHeaderField:@"Accept"];
  [manager.requestSerializer setValue:[NetworkManager encryptRequestString:serviceName] forHTTPHeaderField:kAuthentication];
  manager.responseSerializer.acceptableContentTypes = [manager.responseSerializer.acceptableContentTypes setByAddingObject:@"text/html"];
  [manager.requestSerializer setValue:@"Basic Q29wcGVyTW9iaWxlOmN1cGlk" forHTTPHeaderField:@"Authorization"];
  NSString *serviceUrl = [NSString stringWithFormat:@"%@/%@",kBASEURL,serviceName];
  
  [manager POST:serviceUrl parameters:nil constructingBodyWithBlock:^(id<AFMultipartFormData> formData) {
    
//    for(int i=0;i<[imagesArray count];i++)
//    {
//      
      NSString *imageName=[NSString stringWithFormat:@"photo"];
      NSString *fileName=[NSString stringWithFormat:@"photo.jpg"];
      [formData appendPartWithFileData:imageData name:imageName fileName:fileName mimeType:@"image/jpg"];
//    }
    
    [formData appendPartWithFormData:[NSJSONSerialization dataWithJSONObject:params options:NSJSONWritingPrettyPrinted error:nil] name:@"json"];
    
  } success:^(AFHTTPRequestOperation *operation, id responseObject) {
    NSLog(@"Success: %@", responseObject);
    success (responseObject);
  } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
    NSLog(@"Error: %@", error);
    failure ( error);
  }];
}

#pragma mark - PUBLIC METHODS
- (void)requestWithMethod:(kHTTPMethod)method
								URLString:(NSString *)URLString
							 parameters:(JSONModel*)parameters
									 sucess:(void (^)(id response))success
									failure:(void (^)(NSError *error))failure
{
 
	//TRC_ENTRY()
	DDLogInfo(@"Connecting to Host with URL %@%@", kBASEURL, URLString);
    NSLog(@"Connecting to Host with URL %@/%@", kBASEURL, URLString);
 
	//NSAssert Statements
	NSAssert(method != kHTTPMethodGET1 || method != kHTTPMethodPOST1 || method != kHTTPMethodPOSTMultiPart1,
					@"kHTTPMethod should be one of kHTTPMethodGET|kHTTPMethodPOST|kHTTPMethodPOSTMultiPart.");
	NSAssert(nil != URLString, @"URLString cannot be nil.");
 
	//Get Common request parameters
	id newParameters = [parameters toDictionary];
	if (nil == parameters) {
    newParameters = [[self apiCommonParameters] toDictionary];
	}
  
  //NSString *bundleVersion=[NSString stringWithFormat:@"%@",[[NSBundle mainBundle] objectForInfoDictionaryKey:(NSString *)kCFBundleVersionKey]];
  //[manager.requestSerializer setValue:[NetworkManager encryptRequestString:URLString] forHTTPHeaderField:kAuthentication];
  //[manager.requestSerializer setValue:bundleVersion forHTTPHeaderField:kBundleVersion];
  
  
  NSString *authStr = [NSString stringWithFormat:@"%@:%@", kHTTPUsername, kHTTPPassword];
  NSData *authData = [authStr dataUsingEncoding:NSASCIIStringEncoding];
  NSString *authValue = [NSString stringWithFormat:@"Basic %@", [authData base64EncodedStringWithOptions:80]];
  [self.requestSerializer setValue:@"Basic Q29wcGVyTW9iaWxlOmN1cGlk" forHTTPHeaderField:@"Authorization"];
  
  
//  NSString *header = [NSString stringWithFormat:@"%@:%@",kHTTPUsername,kHTTPPassword];
//  NSString *encriptedKey = [[header dataUsingEncoding:NSUTF8StringEncoding] base64EncodedStringWithOptions:NSDataBase64Encoding64CharacterLineLength];
//  encriptedKey = [NSString stringWithFormat:@"Basic %@",encriptedKey];
//  [self.requestSerializer setValue:encriptedKey forHTTPHeaderField:kAuthentication];
  self.responseSerializer.acceptableContentTypes = [self.responseSerializer.acceptableContentTypes setByAddingObject:@"text/html"];

  
  NSLog(@"with parameters :%@",newParameters);
	switch (method) {
		case kHTTPMethodGET1:
		{
			// Call HTTPRequestOperationWithRequest
			[self GET:URLString
		 parameters:newParameters
				success:^(AFHTTPRequestOperation *operation, id responseObject) {
				
					DDLogInfo(@"Response of service %@:\n%@", URLString, responseObject);
					DDLogInfo(@"HTTP Status Code Received %li", (long)operation.response.statusCode);
					
//					NSError* err = nil;
//					ServerResponseRootJsonModel* headerResponse = [[ServerResponseRootJsonModel alloc]
//																												 initWithDictionary:(NSDictionary*)responseObject
//																												 error:&err];
					
					//[[NetworkDataController sharedInstance]updateApiCommonParameters:headerResponse.serverReplyHeader];
					
					//Sucess block of Web Service
					success(responseObject);
					
				} failure:^(AFHTTPRequestOperation *operation, NSError *error) {
                    
					DDLogInfo(@"operation responseString:%@",operation.responseString);
					DDLogInfo(@"HTTP Status Code Received %li", (long)operation.response.statusCode);

					NSDictionary* json = nil;
					
					if (operation.responseData) {
						json = [NSJSONSerialization
										JSONObjectWithData:operation.responseData
										options:kNilOptions
										error:nil];
					}
					else {
						json = [NSDictionary dictionaryWithObjectsAndKeys:NSLocalizedString(@"NETWORK_FAILED", nil),@"Message", nil];
					}
					
					//Failure block of Web service
					NSError* newError = [[NSError alloc] initWithDomain:error.domain
																												 code:operation.response.statusCode
																										 userInfo:json];
					failure(newError);
				}];
		}
			break;
		case kHTTPMethodPOST1:
		{
			[self POST:URLString
			parameters:newParameters
				 success:^(AFHTTPRequestOperation *operation, id responseObject) {
					 

					 NSLog(@"HTTP Status Code Received %li", (long)operation.response.statusCode);
					 
					 NSError* err = nil;
					 ServerResponseRootJsonModel* headerResponse = [[ServerResponseRootJsonModel alloc]
																													initWithDictionary:(NSDictionary*)responseObject
																													error:&err];
					 
					// [[NetworkDataController sharedInstance]
					//	updateApiCommonParameters:headerResponse.serverReplyHeader];
					 
					 //Sucess block of Web Service
					 success(responseObject);
					 
				 } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
					 
					 NSLog(@"operation responseString:%@",operation.responseString);
					 NSLog(@"HTTP Status Code Received %li", (long)operation.response.statusCode);
					 //Failure block of Web service
					 NSError* newError = [[NSError alloc] initWithDomain:error.domain
																													code:operation.response.statusCode
																											userInfo:error.userInfo];
					 failure(newError);
					 
				 }];
		}
			break;
		case kHTTPMethodPOSTMultiPart1:
		{
			[self POST:URLString
											 parameters:newParameters
				constructingBodyWithBlock:nil
													success:^(AFHTTPRequestOperation *operation, id responseObject) {
					 
														//Sucess block of Web Service
														success(responseObject);
					 
													} failure:^(AFHTTPRequestOperation *operation, NSError *error) {
					 
														//Failure block of Web service
														failure(error);
					 
													}];
		}
			break;
		default:
		{
			// NSAssert Statement
		}
			break;
	}
}

- (void)requestNonJsonModelWithMethod:(kHTTPMethod)method
                URLString:(NSString *)URLString
               parameters:(id)newParameters
                   sucess:(void (^)(id response))success
                  failure:(void (^)(NSError *error))failure
{
  
  //TRC_ENTRY()
  DDLogInfo(@"Connecting to Host with URL %@%@", kBASEURL, URLString);
  
  //NSAssert Statements
  NSAssert(method != kHTTPMethodGET1 || method != kHTTPMethodPOST1 || method != kHTTPMethodPOSTMultiPart1,
           @"kHTTPMethod should be one of kHTTPMethodGET|kHTTPMethodPOST|kHTTPMethodPOSTMultiPart.");
  NSAssert(nil != URLString, @"URLString cannot be nil.");
  
  //Get Common request parameters
//  id newParameters = [parameters toDictionary];
//  if (nil == parameters) {
//    newParameters = [[self apiCommonParameters] toDictionary];
//  }
  
  //NSString *bundleVersion=[NSString stringWithFormat:@"%@",[[NSBundle mainBundle] objectForInfoDictionaryKey:(NSString *)kCFBundleVersionKey]];
  //[manager.requestSerializer setValue:[NetworkManager encryptRequestString:URLString] forHTTPHeaderField:kAuthentication];
  //[manager.requestSerializer setValue:bundleVersion forHTTPHeaderField:kBundleVersion];
  
  
  NSString *authStr = [NSString stringWithFormat:@"%@:%@", kHTTPUsername, kHTTPPassword];
  NSData *authData = [authStr dataUsingEncoding:NSASCIIStringEncoding];
  NSString *authValue = [NSString stringWithFormat:@"Basic %@", [authData base64EncodedStringWithOptions:80]];
  [self.requestSerializer setValue:@"Basic Q29wcGVyTW9iaWxlOmN1cGlk" forHTTPHeaderField:@"Authorization"];
  
  
  //  NSString *header = [NSString stringWithFormat:@"%@:%@",kHTTPUsername,kHTTPPassword];
  //  NSString *encriptedKey = [[header dataUsingEncoding:NSUTF8StringEncoding] base64EncodedStringWithOptions:NSDataBase64Encoding64CharacterLineLength];
  //  encriptedKey = [NSString stringWithFormat:@"Basic %@",encriptedKey];
  //  [self.requestSerializer setValue:encriptedKey forHTTPHeaderField:kAuthentication];
  self.responseSerializer.acceptableContentTypes = [self.responseSerializer.acceptableContentTypes setByAddingObject:@"text/html"];
  
  
  DDLogInfo(@"with parameters :%@",newParameters);
  switch (method) {
    case kHTTPMethodGET1:
    {
      // Call HTTPRequestOperationWithRequest
      [self GET:URLString
     parameters:newParameters
        success:^(AFHTTPRequestOperation *operation, id responseObject) {
          
          DDLogInfo(@"Response of service %@:\n%@", URLString, responseObject);
          DDLogInfo(@"HTTP Status Code Received %li", (long)operation.response.statusCode);
          
          NSError* err = nil;
          ServerResponseRootJsonModel* headerResponse = [[ServerResponseRootJsonModel alloc]
                                                         initWithDictionary:(NSDictionary*)responseObject
                                                         error:&err];
          
          //[[NetworkDataController sharedInstance]updateApiCommonParameters:headerResponse.serverReplyHeader];
          
          //Sucess block of Web Service
          success(responseObject);
          
        } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
          
          DDLogInfo(@"operation responseString:%@",operation.responseString);
          DDLogInfo(@"HTTP Status Code Received %li", (long)operation.response.statusCode);
          
          NSDictionary* json = nil;
          
          if (operation.responseData) {
            json = [NSJSONSerialization
                    JSONObjectWithData:operation.responseData
                    options:kNilOptions
                    error:nil];
          }
          else {
            json = [NSDictionary dictionaryWithObjectsAndKeys:NSLocalizedString(@"NETWORK_FAILED", nil),@"Message", nil];
          }
          
          //Failure block of Web service
          NSError* newError = [[NSError alloc] initWithDomain:error.domain
                                                         code:operation.response.statusCode
                                                     userInfo:json];
          failure(newError);
        }];
    }
      break;
    case kHTTPMethodPOST1:
    {
      [self POST:URLString
      parameters:newParameters
         success:^(AFHTTPRequestOperation *operation, id responseObject) {
           
           DDLogInfo(@"Response of service %@:\n%@", URLString, responseObject);
           DDLogInfo(@"HTTP Status Code Received %li", (long)operation.response.statusCode);
           
           NSError* err = nil;
           ServerResponseRootJsonModel* headerResponse = [[ServerResponseRootJsonModel alloc]
                                                          initWithDictionary:(NSDictionary*)responseObject
                                                          error:&err];
           
           // [[NetworkDataController sharedInstance]
           //	updateApiCommonParameters:headerResponse.serverReplyHeader];
           
           //Sucess block of Web Service
           success(responseObject);
           
         } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
           
           DDLogInfo(@"operation responseString:%@",operation.responseString);
           DDLogInfo(@"HTTP Status Code Received %li", (long)operation.response.statusCode);
           //Failure block of Web service
           NSError* newError = [[NSError alloc] initWithDomain:error.domain
                                                          code:operation.response.statusCode
                                                      userInfo:error.userInfo];
           failure(newError);
           
         }];
    }
      break;
    case kHTTPMethodPOSTMultiPart1:
    {
      [self POST:URLString
      parameters:newParameters
constructingBodyWithBlock:nil
         success:^(AFHTTPRequestOperation *operation, id responseObject) {
           
           //Sucess block of Web Service
           success(responseObject);
           
         } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
           
           //Failure block of Web service
           failure(error);
           
         }];
    }
      break;
    default:
    {
      // NSAssert Statement
    }
      break;
  }
}

- (void)cancelAllRequests {
	//Cancel all requests ie: all NSOperations in operationQueue
	[self.operationQueue cancelAllOperations];
 
}
- (void)cancelRequestWithURLString:(NSString *)URLString {
 
}
- (NSUInteger)numberOfRequestsInManager {
	return [self.operationQueue operationCount];
}

- (BOOL)isConnectedToNetwork
{
  
  return [[AFNetworkReachabilityManager sharedManager]isReachable];
}

- (BOOL)isConnectedToWiFi
{
  
  BOOL isInternetAvailable = false;
  Reachability *internetReach = [Reachability reachabilityForInternetConnection];
  [internetReach startNotifier];
  NetworkStatus netStatus = [internetReach currentReachabilityStatus];
  switch (netStatus)
  {
    case NotReachable:
      isInternetAvailable = FALSE;
      break;
    case ReachableViaWWAN:
      isInternetAvailable = TRUE;
      break;
    case ReachableViaWiFi:
      isInternetAvailable = TRUE;
      break;
  }
  [internetReach stopNotifier];
  return isInternetAvailable;

	
//	static int wifiCheckCount = 1;
//	BOOL success = NO;
//	if(self.reachabilityManager.isReachableViaWiFi)
//  {
//    wifiCheckCount = 1;
//		success = YES;
//	}
//	else if(wifiCheckCount > 3)
//  {
//		success = NO;
//	}
//	else{
//		++wifiCheckCount;
//		[self isConnectedToWiFi];
//	}
////  BOOL success = YES;
//	return success;
}
@end
