//
//  TAFacebookHelper.m
//  PhotoAlbum
//
//  Created by Mayank Pahuja on 05/08/13.
//  Copyright (c) 2013 Mayank Pahuja. All rights reserved.
//

#import "TAFacebookHelper.h"

@implementation TAFacebookHelper

#pragma mark - ///////////////
#pragma mark -Create Session with app Id

////+ (void)openActiveSessionWithPermissions:(NSArray*)permissions withAppId:(NSString*)appId allowLoginUI:(BOOL)allowLoginUI allowSystemAccount:(BOOL)allowSystemAccount isRead:(BOOL)isRead defaultAudience:(FBSessionDefaultAudience)defaultAudience completionHandler:(FBSessionStateHandler)handler
////{
////    if (FBSession.activeSession.isOpen == YES)
////        [TAFacebookHelper logoutFB];
////    BOOL result = NO;
////    [FBSettings setDefaultAppID:appId];
////    FBSession *session = [[FBSession alloc] initWithAppID:appId permissions:permissions defaultAudience:defaultAudience urlSchemeSuffix:nil tokenCacheStrategy:nil];
////    if (allowLoginUI || session.state == FBSessionStateCreatedTokenLoaded)
////    {
////        [FBSession setActiveSession:session];
////        // we open after the fact, in order to avoid overlapping close
////        // and open handler calls for blocks
////        FBSessionLoginBehavior howToBehave = allowSystemAccount ?
////        FBSessionLoginBehaviorUseSystemAccountIfPresent :
////        FBSessionLoginBehaviorWithFallbackToWebView;
////        [session openWithBehavior:howToBehave
////                completionHandler:handler];
////        result = session.isOpen;
////        if(result == YES)
////        {
////          
////        }
////            //DLog(@"FB Session Opened");
////    }
////}
//
//#pragma mark - Logout Facebook
//+ (void) logoutFB
//{
//    // Logout from FB
//    NSHTTPCookie *cookie;
//    NSHTTPCookieStorage *storage = [NSHTTPCookieStorage sharedHTTPCookieStorage];
//    for (cookie in [storage cookies])
//    {
//        NSString* domainName = [cookie domain];
//        NSRange domainRange = [domainName rangeOfString:@"facebook"];
//        if(domainRange.length > 0)
//        {
//            [storage deleteCookie:cookie];
//        }
//    }
//    
////    FBSession *session = [FBSession activeSession];
////    if(session)
////    {
////        if([[FBSession activeSession] isOpen])
////        {
////            [[FBSession activeSession] closeAndClearTokenInformation];
////            [[FBSession activeSession] close];
////            [FBSession setActiveSession:nil];
////        }
////    }
//}
//
//#pragma mark - ///////////////
//
//
//
//+ (void)fetchPersonalInfoWithParams:(NSString*)params withPermissions:(NSArray*)permissions completionHandler:(void(^)(id response, NSError *e))completionBlock
//{
////    if (FBSession.activeSession.isOpen)
////    {
////        if (FBSession.activeSession.permissions != permissions)
////        {
////            [FBSession openActiveSessionWithReadPermissions:permissions allowLoginUI:YES completionHandler:^(FBSession *session,FBSessionState status,NSError *error)
////             {
////                 if (error)
////                 {
////                     
////                 }
////                 else if (FB_ISSESSIONOPENWITHSTATE(status))
////                 {
////                     FBRequestConnection *newConnection = [[FBRequestConnection alloc] init];
////                     FBRequest *request = [FBRequest requestWithGraphPath:@"me" parameters:[NSDictionary dictionaryWithObjectsAndKeys:params,@"fields", nil] HTTPMethod:@"GET"];
////                     FBRequestHandler handler = ^(FBRequestConnection *connection, id result, NSError *error)
////                     {
////                         completionBlock(result,error);
////                     };
////                     [newConnection addRequest:request completionHandler:handler];
////                     [newConnection start];
////                     [newConnection release];
////                     
////                 }
////             }];
////        }
////        else
////        {
////            FBRequestConnection *newConnection = [[FBRequestConnection alloc] init];
////            FBRequest *request = [FBRequest requestWithGraphPath:@"me" parameters:[NSDictionary dictionaryWithObjectsAndKeys:params,@"fields", nil] HTTPMethod:@"GET"];
////            FBRequestHandler handler = ^(FBRequestConnection *connection, id result, NSError *error)
////            {
////                completionBlock(result,error);
////            };
////            [newConnection addRequest:request completionHandler:handler];
////            [newConnection start];
////            [newConnection release];
////        }
////    }
////    else
////    {
////        [FBSession openActiveSessionWithReadPermissions:permissions allowLoginUI:YES completionHandler:^(FBSession *session,FBSessionState status,NSError *error)
////         {
////             if (error)
////             {
////               completionBlock(nil,error);
////                 //DLog(@"err:%@",error);
////             }
////             else if (FB_ISSESSIONOPENWITHSTATE(status))
////             {
////                 FBRequestConnection *newConnection = [[FBRequestConnection alloc] init];
////                 FBRequest *request = [FBRequest requestWithGraphPath:@"me" parameters:[NSDictionary dictionaryWithObjectsAndKeys:params,@"fields", nil] HTTPMethod:@"GET"];
////                 FBRequestHandler handler = ^(FBRequestConnection *connection, id result, NSError *error)
////                 {
////                     completionBlock(result,error);
////                 };
////                 [newConnection addRequest:request completionHandler:handler];
////                 [newConnection start];
////                 [newConnection release];
////                 
////             }
////         }];
////    }
//}
//
//+ (void)fetchFriendListWithParams:(NSString*)params withPermissions:(NSArray*)permissions completionHandler:(void(^)(id response, NSError *e))completionBlock
//{
////    if (FBSession.activeSession.isOpen)
////    {
////        if (FBSession.activeSession.permissions != permissions)
////        {
////            [FBSession openActiveSessionWithReadPermissions:permissions allowLoginUI:YES completionHandler:^(FBSession *session,FBSessionState status,NSError *error)
////             {
////                 if (error)
////                 {
////                     
////                 }
////                 else if (FB_ISSESSIONOPENWITHSTATE(status))
////                 {
////                     FBRequestConnection *newConnection = [[FBRequestConnection alloc] init];
////                     FBRequest *request = [FBRequest requestWithGraphPath:@"me/friends" parameters:[NSDictionary dictionaryWithObject:params forKey:@"fields"] HTTPMethod:@"GET"];
////                     
////                     FBRequestHandler handler =
////                     ^(FBRequestConnection *connection, id result, NSError *error)
////                     {
////                         completionBlock(result,error);
////                     };
////                     
////                     [newConnection addRequest:request completionHandler:handler];
////                     [newConnection start];
////                     [newConnection release];
////                     
////                 }
////             }];
////        }
////        else
////        {
////            
////            FBRequestConnection *newConnection = [[FBRequestConnection alloc] init];
////            FBRequest *request = [FBRequest requestWithGraphPath:@"me/friends" parameters:[NSDictionary dictionaryWithObject:params forKey:@"fields"] HTTPMethod:@"GET"];
////            
////            FBRequestHandler handler =
////            ^(FBRequestConnection *connection, id result, NSError *error)
////            {
////                completionBlock(result,error);
////            };
////            
////            [newConnection addRequest:request completionHandler:handler];
////            [newConnection start];
////            [newConnection release];
////        }
////    }
////    else
////    {
////        [FBSession openActiveSessionWithReadPermissions:permissions allowLoginUI:YES completionHandler:^(FBSession *session,FBSessionState status,NSError *error)
////         {
////             if (error)
////             {
////                 
////             }
////             else if (FB_ISSESSIONOPENWITHSTATE(status))
////             {
////                 FBRequestConnection *newConnection = [[FBRequestConnection alloc] init];
////                 FBRequest *request = [FBRequest requestWithGraphPath:@"me/friends" parameters:[NSDictionary dictionaryWithObject:params forKey:@"fields"] HTTPMethod:@"GET"];
////                 
////                 FBRequestHandler handler =
////                 ^(FBRequestConnection *connection, id result, NSError *error)
////                 {
////                     completionBlock(result,error);
////                 };
////                 [newConnection addRequest:request completionHandler:handler];
////                 [newConnection start];
////                 [newConnection release];
////             }
////         }];
////    }
//}
//
//
//#pragma mark - upload video methods
//
//+ (void)uploadVideowithPath:(NSString*)filePath withTitle:(NSString*)videoTitle andDescription:(NSString*)videoDesc completertionHandler:(void(^)(id response,NSError *e))completionBlock
//{
////    if (FBSession.activeSession.isOpen)
////    {
////        NSArray *permissions = [[[NSArray alloc] initWithObjects:
////                                 @"publish_stream",
////                                 nil] autorelease];
////        if (FBSession.activeSession.permissions != permissions)
////        {
////            [FBSession openActiveSessionWithPublishPermissions:permissions defaultAudience:FBSessionDefaultAudienceEveryone allowLoginUI:YES
////                                             completionHandler:^(FBSession *session, FBSessionState status, NSError *error)
////             {
////                 if (error)
////                 {
////                     completionBlock(nil,error);
////                 }
////                 else if (FB_ISSESSIONOPENWITHSTATE(status))
////                 {
////                     NSURL *pathURL = [[NSURL alloc]initFileURLWithPath:filePath isDirectory:NO];
////                     NSData *videoData = [NSData dataWithContentsOfFile:filePath];
////                     
////                     NSDictionary *videoObject = @{
////                                                   @"title": videoTitle,
////                                                   @"description": videoDesc,
////                                                   [pathURL absoluteString]: videoData
////                                                   };
////                     FBRequest *uploadRequest = [FBRequest requestWithGraphPath:@"me/videos"
////                                                                     parameters:videoObject
////                                                                     HTTPMethod:@"POST"];
////                     
////                     [uploadRequest startWithCompletionHandler:^(FBRequestConnection *connection, id result, NSError *error)
////                      {
////                          completionBlock(result,error);
////                      }];
////                     
////                 }
////             }];
////        }
////        else
////        {
////            NSURL *pathURL = [[NSURL alloc]initFileURLWithPath:filePath isDirectory:NO];
////            NSData *videoData = [NSData dataWithContentsOfFile:filePath];
////            
////            NSDictionary *videoObject = @{
////                                          @"title": videoTitle,
////                                          @"description": videoDesc,
////                                          [pathURL absoluteString]: videoData
////                                          };
////            FBRequest *uploadRequest = [FBRequest requestWithGraphPath:@"me/videos"
////                                                            parameters:videoObject
////                                                            HTTPMethod:@"POST"];
////            
////            [uploadRequest startWithCompletionHandler:^(FBRequestConnection *connection, id result, NSError *error)
////             {
////                 completionBlock(result,error);
////             }];
////        }
////    }
////    else
////    {
////        NSArray *permissions = [[[NSArray alloc] initWithObjects:
////                                 @"publish_stream",
////                                 nil] autorelease];
////        
////        [FBSession openActiveSessionWithPublishPermissions:permissions defaultAudience:FBSessionDefaultAudienceEveryone allowLoginUI:YES
////                                         completionHandler:^(FBSession *session, FBSessionState status, NSError *error)
////         {
////             if (error)
////             {
////                 
////             }
////             else if (FB_ISSESSIONOPENWITHSTATE(status))
////             {
////                 NSURL *pathURL = [[NSURL alloc]initFileURLWithPath:filePath isDirectory:NO];
////                 NSData *videoData = [NSData dataWithContentsOfFile:filePath];
////                 
////                 NSDictionary *videoObject = @{
////                                               @"title": videoTitle,
////                                               @"description": videoDesc,
////                                               [pathURL absoluteString]: videoData
////                                               };
////                 FBRequest *uploadRequest = [FBRequest requestWithGraphPath:@"me/videos"
////                                                                 parameters:videoObject
////                                                                 HTTPMethod:@"POST"];
////                 
////                 [uploadRequest startWithCompletionHandler:^(FBRequestConnection *connection, id result, NSError *error)
////                  {
////                      completionBlock(result,error);
////                  }];
////             }
////         }];
////    }
////}
////
////+ (void)getVideoLikesWithId:(NSString*)videoId completertionHandler:(void(^)(id response,NSError *e))completionBlock
////{
////    if (FBSession.activeSession.isOpen)
////    {
////        NSArray *permissions = [[[NSArray alloc] initWithObjects:
////                                 @"user_videos",
////                                 nil] autorelease];
////        if (FBSession.activeSession.permissions != permissions)
////        {
////            [FBSession openActiveSessionWithPublishPermissions:permissions defaultAudience:FBSessionDefaultAudienceEveryone allowLoginUI:YES
////                                             completionHandler:^(FBSession *session, FBSessionState status, NSError *error)
////             {
////                 if (error)
////                 {
////                     completionBlock(nil,error);
////                 }
////                 else if (FB_ISSESSIONOPENWITHSTATE(status))
////                 {
////                     
////                     FBRequest *getLikes = [FBRequest requestWithGraphPath:[NSString stringWithFormat:@"%@/likes",videoId]
////                                                                parameters:nil
////                                                                HTTPMethod:@"GET"];
////                     
////                     [getLikes startWithCompletionHandler:^(FBRequestConnection *connection, id result, NSError *error)
////                      {
////                          completionBlock(result,error);
////                      }];
////                     
////                 }
////             }];
////        }
////        else
////        {
////            FBRequest *getLikes = [FBRequest requestWithGraphPath:[NSString stringWithFormat:@"%@/likes",videoId]
////                                                       parameters:nil
////                                                       HTTPMethod:@"GET"];
////            
////            [getLikes startWithCompletionHandler:^(FBRequestConnection *connection, id result, NSError *error)
////             {
////                 completionBlock(result,error);
////             }];        }
////    }
////    else
////    {
////        NSArray *permissions = [[[NSArray alloc] initWithObjects:
////                                 @"user_videos",
////                                 nil] autorelease];
////        
////        [FBSession openActiveSessionWithPublishPermissions:permissions defaultAudience:FBSessionDefaultAudienceEveryone allowLoginUI:YES
////                                         completionHandler:^(FBSession *session, FBSessionState status, NSError *error)
////         {
////             if (error)
////             {
////                 
////             }
////             else if (FB_ISSESSIONOPENWITHSTATE(status))
////             {
////                 FBRequest *getLikes = [FBRequest requestWithGraphPath:[NSString stringWithFormat:@"%@/likes",videoId]
////                                                            parameters:nil
////                                                            HTTPMethod:@"GET"];
////                 
////                 [getLikes startWithCompletionHandler:^(FBRequestConnection *connection, id result, NSError *error)
////                  {
////                      completionBlock(result,error);
////                  }];
////             }
////         }];
////    }
//}
//
//
//
//#pragma mark - upload image and album methods
//
//+ (void)uploadPhotoToAlbum:(NSString*)albumName withImage:(UIImage*)imageSource completertionHandler:(void(^)(id response,NSError *e))completionBlock
//{
////    if (FBSession.activeSession.isOpen)
////    {
////        NSArray *permissions = [[[NSArray alloc] initWithObjects:
////                                 @"read_stream",
////                                 @"user_birthday",
////                                 @"email",
////                                 nil] autorelease];
////        if (FBSession.activeSession.permissions != permissions)
////        {
////            [TAFacebookHelper askPermissionsForImageUploadToAlbum:albumName withImage:imageSource completertionHandler:^(id response, NSError *e)
////             {
////                 completionBlock(response,e);
////             }];
////        }
////        else
////        {
////            FBRequestConnection *newConnection = [[FBRequestConnection alloc] init];
////            FBRequest *request = [FBRequest requestWithGraphPath:@"me/albums" parameters:nil HTTPMethod:@"GET"];
////            
////            FBRequestHandler handler =
////            ^(FBRequestConnection *connection, id result, NSError *error)
////            {
////                
////                if(!error)
////                {
////                    int index_num = 0;
////                    NSDictionary *list =(NSDictionary*)result;
////                    for (int index = 0; index < [[list objectForKey:@"data"] count];index++)
////                    {
////                        if ([[[[list objectForKey:@"data"] objectAtIndex:index] objectForKey:@"name"] isEqualToString:albumName])
////                        {
////                            [TAFacebookHelper uploadPhotoToAlbumId:[[[list objectForKey:@"data"] objectAtIndex:index] objectForKey:@"id"] photoSource:imageSource completertionHandler:^(id response, NSError *e)
////                             {
////                                 completionBlock(response,e);
////                             }];
////                            index_num = 1;
////                        }
////                        
////                    }
////                    if (index_num == 0)
////                    {
////                        [TAFacebookHelper makeAlbumwitName:albumName forImage:imageSource completertionHandler:^(id response, NSError *e)
////                         {
////                             completionBlock(response,e);
////                         }];
////                    }
////                }
////                else
////                {
////                    completionBlock(result,error);
////                }
////            };
////            
////            [newConnection addRequest:request completionHandler:handler];
////            [newConnection start];
////            [newConnection release];
////        }
////    }
////    else
////    {
////        [TAFacebookHelper askPermissionsForImageUploadToAlbum:albumName withImage:imageSource completertionHandler:^(id response, NSError *e)
////         {
////             completionBlock(response,e);
////         }];
////    }
//    
//}
//
//+ (void)askPermissionsForImageUploadToAlbum:(NSString*)albumName withImage:(UIImage*)imageSource completertionHandler:(void(^)(id response,NSError *e))completionBlock
//{
//    NSArray *permissions = [[[NSArray alloc] initWithObjects:
//                             @"read_stream",
//                             @"user_birthday",
//                             @"email",
//                             nil] autorelease];
//    
//    [FBSession openActiveSessionWithReadPermissions:permissions
//                                       allowLoginUI:YES
//                                  completionHandler:^(FBSession *session,
//                                                      FBSessionState status,
//                                                      NSError *error)
//     {
//         
//         
//         if (error)
//         {
//             
//         }
//         else if (FB_ISSESSIONOPENWITHSTATE(status))
//         {
//             FBRequestConnection *newConnection = [[FBRequestConnection alloc] init];
//             FBRequest *request = [FBRequest requestWithGraphPath:@"me/albums" parameters:nil HTTPMethod:@"GET"];
//             
//             FBRequestHandler handler =
//             ^(FBRequestConnection *connection, id result, NSError *error)
//             {
//                 
//                 if(!error)
//                 {
//                     int index_num = 0;
//                     NSDictionary *list =(NSDictionary*)result;
//                     for (int index = 0; index < [[list objectForKey:@"data"] count];index++)
//                     {
//                         if ([[[[list objectForKey:@"data"] objectAtIndex:index] objectForKey:@"name"] isEqualToString:albumName])
//                         {
//                             
//                             [TAFacebookHelper uploadPhotoToAlbumId:[[[list objectForKey:@"data"] objectAtIndex:index] objectForKey:@"id"] photoSource:imageSource completertionHandler:^(id response, NSError *e)
//                              {
//                                  completionBlock(response,e);
//                              }];
//                             index_num = 1;
//                         }
//                         
//                     }
//                     if (index_num == 0)
//                     {
//                         [TAFacebookHelper makeAlbumwitName:albumName forImage:imageSource completertionHandler:^(id response, NSError *e) {
//                             completionBlock(response,e);
//                         }];
//                     }
//                 }
//                 else
//                 {
//                     completionBlock(result,error);
//                 }
//             };
//             
//             [newConnection addRequest:request completionHandler:handler];
//             [newConnection start];
//             [newConnection release];
//         }
//     }];
//    
//}
//
//+ (void)makeAlbumwitName:(NSString*)albumName forImage:(UIImage*)imageToUpload completertionHandler:(void(^)(id response,NSError *e))completionBlock
//{
//    FBRequestConnection *newConnection = [[FBRequestConnection alloc] init];
//    FBRequest *request = [FBRequest requestWithGraphPath:@"me/albums" parameters:[NSDictionary dictionaryWithObjectsAndKeys:albumName,@"name",@"",@"message", nil] HTTPMethod:@"POST"];
//    
//    FBRequestHandler handler =
//    ^(FBRequestConnection *connection, id result, NSError *error)
//    {
//        
//        if(!error)
//        {
//            [TAFacebookHelper uploadPhotoToAlbumId:[result objectForKey:@"id"] photoSource:imageToUpload completertionHandler:^(id response, NSError *e)
//             {
//                 completionBlock(response,e);
//             }];
//        }
//        else
//        {
//            completionBlock(result,error);
//        }
//    };
//    
//    [newConnection addRequest:request completionHandler:handler];
//    [newConnection start];
//    [newConnection release];
//}
//
//+ (void)makeAlbumwitName:(NSString*)albumName completertionHandler:(void(^)(id response,NSError *e))completionBlock
//{
//    if (FBSession.activeSession.isOpen)
//    {
//        NSArray *permissions = [[[NSArray alloc] initWithObjects:
//                                 @"publish_stream",
//                                 nil] autorelease];
//        if (FBSession.activeSession.permissions != permissions)
//        {
//            [FBSession openActiveSessionWithPublishPermissions:permissions defaultAudience:FBSessionDefaultAudienceEveryone allowLoginUI:YES
//                                             completionHandler:^(FBSession *session, FBSessionState status, NSError *error)
//             {
//                 if (error)
//                 {
//                     
//                 }
//                 else
//                 {
//                     
//                     FBRequestConnection *newConnection = [[FBRequestConnection alloc] init];
//                     FBRequest *request = [FBRequest requestWithGraphPath:@"me/albums" parameters:[NSDictionary dictionaryWithObjectsAndKeys:albumName,@"name",@"",@"message", nil] HTTPMethod:@"POST"];
//                     
//                     FBRequestHandler handler =
//                     ^(FBRequestConnection *connection, id result, NSError *error)
//                     {
//                         completionBlock(result,error);
//                     };
//                     
//                     [newConnection addRequest:request completionHandler:handler];
//                     [newConnection start];
//                     [newConnection release];
//                 }
//             }];
//        }
//        else
//        {
//            FBRequestConnection *newConnection = [[FBRequestConnection alloc] init];
//            FBRequest *request = [FBRequest requestWithGraphPath:@"me/albums" parameters:[NSDictionary dictionaryWithObjectsAndKeys:albumName,@"name",@"",@"message", nil] HTTPMethod:@"POST"];
//            
//            FBRequestHandler handler =
//            ^(FBRequestConnection *connection, id result, NSError *error)
//            {
//                completionBlock(result,error);
//            };
//            
//            [newConnection addRequest:request completionHandler:handler];
//            [newConnection start];
//            [newConnection release];
//            
//        }
//    }
//    else
//    {
//        NSArray *permissions = [[[NSArray alloc] initWithObjects:
//                                 @"publish_stream",
//                                 nil] autorelease];
//        [FBSession openActiveSessionWithPublishPermissions:permissions defaultAudience:FBSessionDefaultAudienceEveryone allowLoginUI:YES
//                                         completionHandler:^(FBSession *session, FBSessionState status, NSError *error)
//         {
//             if (error)
//             {
//             }
//             else
//             {
//                 
//                 FBRequestConnection *newConnection = [[FBRequestConnection alloc] init];
//                 FBRequest *request = [FBRequest requestWithGraphPath:@"me/albums" parameters:[NSDictionary dictionaryWithObjectsAndKeys:albumName,@"name",@"",@"message", nil] HTTPMethod:@"POST"];
//                 
//                 FBRequestHandler handler =
//                 ^(FBRequestConnection *connection, id result, NSError *error)
//                 {
//                     completionBlock(result,error);
//                 };
//                 
//                 [newConnection addRequest:request completionHandler:handler];
//                 [newConnection start];
//                 [newConnection release];
//             }
//         }];
//        
//    }
//}
//
//+ (void)uploadPhotoToAlbumId:(NSString*)album_id photoSource:(UIImage*)imageToUpload completertionHandler:(void(^)(id response,NSError *e))completionBlock
//{
//    NSArray *permissions = [[[NSArray alloc] initWithObjects:
//                             @"publish_stream",
//                             nil] autorelease];
//    [FBSession openActiveSessionWithPublishPermissions:permissions defaultAudience:FBSessionDefaultAudienceEveryone allowLoginUI:YES
//                                     completionHandler:^(FBSession *session, FBSessionState status, NSError *error)
//     {
//         
//         
//         if (error)
//         {
//             
//         }
//         else if (FB_ISSESSIONOPENWITHSTATE(status))
//         {
//             FBRequestConnection *newConnection = [[FBRequestConnection alloc] init];
//             FBRequest *request = [FBRequest requestWithGraphPath:[NSString stringWithFormat:@"%@/photos",album_id] parameters:[NSDictionary dictionaryWithObjectsAndKeys:[TAFacebookHelper generatePhotoThumbnail:imageToUpload],@"source",@"",@"message", nil] HTTPMethod:@"POST"];
//             
//             FBRequestHandler handler =
//             ^(FBRequestConnection *connection, id result, NSError *error)
//             {
//                 completionBlock(result,error);
//             };
//             
//             [newConnection addRequest:request completionHandler:handler];
//             [newConnection start];
//             [newConnection release];
//         }
//     }];
//}
//
//+ (void)tagPhotoWithId:(NSString*)photo_id withPeople:(NSArray*)uidArray completertionHandler:(void(^)(id response,NSError *e))completionBlock
//{
//    if (FBSession.activeSession.isOpen)
//    {
//        NSArray *permissions = [[[NSArray alloc] initWithObjects:
//                                 @"publish_stream",
//                                 nil] autorelease];
//        if (FBSession.activeSession.permissions != permissions)
//        {
//            [FBSession openActiveSessionWithPublishPermissions:permissions defaultAudience:FBSessionDefaultAudienceEveryone allowLoginUI:YES
//                                             completionHandler:^(FBSession *session, FBSessionState status, NSError *error)
//             {
//                 if (error)
//                 {
//                     
//                 }
//                 else
//                 {
//                     
//                     
//                     FBRequestConnection *newConnection = [[FBRequestConnection alloc] init];
//                     NSString *act = [NSString stringWithFormat:@"%@",[[[FBSession activeSession].accessTokenData dictionary] valueForKey:@"com.facebook.sdk:TokenInformationTokenKey"]];
//                     NSMutableArray *arr = [[NSMutableArray alloc]init];
//                     for (int i = 0; i < uidArray.count; i++)
//                     {
//                         [arr addObject:[NSDictionary dictionaryWithObjectsAndKeys:[[uidArray objectAtIndex:i] objectForKey:@"uid"],@"tag_uid", nil]];
//                     }
//                     
//                     NSData *data1 = [NSJSONSerialization dataWithJSONObject:arr options:NSJSONWritingPrettyPrinted error:nil];
//                     NSString *str = [[NSString alloc] initWithData:data1 encoding:NSUTF8StringEncoding];
//                     
//                     NSDictionary *para = [NSDictionary dictionaryWithObjectsAndKeys:str,@"tags",act,@"access_token", nil];
//                     [arr release];
//                     
//                     FBRequest *request = [FBRequest requestWithGraphPath:[NSString stringWithFormat:@"%@/tags",photo_id] parameters:para HTTPMethod:@"POST"];
//                     FBRequestHandler handler =
//                     ^(FBRequestConnection *connection, id result, NSError *error)
//                     {
//                         completionBlock(result,error);
//                         
//                     };
//                     
//                     [newConnection addRequest:request completionHandler:handler];
//                     [newConnection start];
//                     [newConnection release];
//                 }
//             }];
//        }
//        else
//        {
//            FBRequestConnection *newConnection = [[FBRequestConnection alloc] init];
//            NSString *act = [NSString stringWithFormat:@"%@",[[[FBSession activeSession].accessTokenData dictionary] valueForKey:@"com.facebook.sdk:TokenInformationTokenKey"]];
//            NSMutableArray *arr = [[NSMutableArray alloc]init];
//            for (int i = 0; i < uidArray.count; i++)
//            {
//                [arr addObject:[NSDictionary dictionaryWithObjectsAndKeys:[[uidArray objectAtIndex:i] objectForKey:@"uid"],@"tag_uid", nil]];
//            }
//            
//            NSData *data1 = [NSJSONSerialization dataWithJSONObject:arr options:NSJSONWritingPrettyPrinted error:nil];
//            NSString *str = [[NSString alloc] initWithData:data1 encoding:NSUTF8StringEncoding];
//            
//            NSDictionary *para = [NSDictionary dictionaryWithObjectsAndKeys:str,@"tags",act,@"access_token", nil];
//            [arr release];
//            
//            FBRequest *request = [FBRequest requestWithGraphPath:[NSString stringWithFormat:@"%@/tags",photo_id] parameters:para HTTPMethod:@"POST"];
//            FBRequestHandler handler =
//            ^(FBRequestConnection *connection, id result, NSError *error)
//            {
//                completionBlock(result,error);
//                
//            };
//            
//            [newConnection addRequest:request completionHandler:handler];
//            [newConnection start];
//            [newConnection release];
//            
//        }
//    }
//    else
//    {
//        NSArray *permissions = [[[NSArray alloc] initWithObjects:
//                                 @"publish_stream",
//                                 nil] autorelease];
//        [FBSession openActiveSessionWithPublishPermissions:permissions defaultAudience:FBSessionDefaultAudienceEveryone allowLoginUI:YES
//                                         completionHandler:^(FBSession *session, FBSessionState status, NSError *error)
//         {
//             if (error)
//             {
//                 
//             }
//             else
//             {
//                 FBRequestConnection *newConnection = [[FBRequestConnection alloc] init];
//                 NSString *act = [NSString stringWithFormat:@"%@",[[[FBSession activeSession].accessTokenData dictionary] valueForKey:@"com.facebook.sdk:TokenInformationTokenKey"]];
//                 NSMutableArray *arr = [[NSMutableArray alloc]init];
//                 for (int i = 0; i < uidArray.count; i++)
//                 {
//                     [arr addObject:[NSDictionary dictionaryWithObjectsAndKeys:[[uidArray objectAtIndex:i] objectForKey:@"uid"],@"tag_uid", nil]];
//                 }
//                 
//                 NSData *data1 = [NSJSONSerialization dataWithJSONObject:arr options:NSJSONWritingPrettyPrinted error:nil];
//                 NSString *str = [[NSString alloc] initWithData:data1 encoding:NSUTF8StringEncoding];
//                 
//                 NSDictionary *para = [NSDictionary dictionaryWithObjectsAndKeys:str,@"tags",act,@"access_token", nil];
//                 [arr release];
//                 
//                 FBRequest *request = [FBRequest requestWithGraphPath:[NSString stringWithFormat:@"%@/tags",photo_id] parameters:para HTTPMethod:@"POST"];
//                 FBRequestHandler handler =
//                 ^(FBRequestConnection *connection, id result, NSError *error)
//                 {
//                     completionBlock(result,error);
//                     
//                 };
//                 
//                 [newConnection addRequest:request completionHandler:handler];
//                 [newConnection start];
//                 [newConnection release];
//             }
//         }];
//    }
//    
//}
//
//
//#pragma mark - post text message
//
//+ (void)postText:(NSString*)text OnFriendsWall:(NSString*)friendId
//{
//    NSArray *permissions = [[[NSArray alloc] initWithObjects:
//                             @"publish_actions",
//                             nil] autorelease];
//    
//    [FBSession openActiveSessionWithPublishPermissions:permissions defaultAudience:FBSessionDefaultAudienceEveryone allowLoginUI:YES
//                                     completionHandler:^(FBSession *session, FBSessionState status, NSError *error)
//     {
//         if (error)
//         {
//             
//         }
//         else if (FB_ISSESSIONOPENWITHSTATE(status))
//         {
//             FBRequest *uploadRequest = [FBRequest requestWithGraphPath:[NSString stringWithFormat:@"%@",friendId]
//                                                             parameters:[NSDictionary dictionaryWithObjects:[NSArray arrayWithObjects:text, nil] forKeys:[NSArray arrayWithObjects:@"message", nil]]
//                                                             HTTPMethod:@"POST"];
//             
//             [uploadRequest startWithCompletionHandler:^(FBRequestConnection *connection, id result, NSError *error)
//              {
//                  if (!error)
//                  {
//                    
//                  }
//                      //DLog(@"Done: %@", result);
//                  //else
//                  ///  NSLog(@"Error: %@", error.localizedDescription);
//              }];
//             
//         }
//     }];
//}
//
//+ (void)postText:(NSString*)text caption:(NSString*)caption completetionHandler:(void(^)(BOOL resultSend, NSError *e))handler
//{
//    if (FBSession.activeSession.isOpen)
//    {
//        
//        NSMutableDictionary *params = [NSMutableDictionary dictionaryWithObjectsAndKeys:
//                                       @"Buddypass", @"name",
//                                       caption, @"caption",
//                                       text, @"description",
////                                       KAPPSTORELINK, @"link",
//                                       @"http://103.25.130.197/buddypass/assets/img/buddypasslogo.png", @"picture",
//                                       nil];
//        
//        // Show the feed dialog
//        [FBWebDialogs presentFeedDialogModallyWithSession:nil
//                                               parameters:params
//                                                  handler:^(FBWebDialogResult result, NSURL *resultURL, NSError *error) {
//                                                      if (error)
//                                                      {
//                                                          // An error occurred, we need to handle the error
//                                                          // See: https://developers.facebook.com/docs/ios/errors
//                                                          ////DLog(@"Error publishing story: %@", error.description);
//                                                          handler(false,error);
//                                                          
//                                                      } else
//                                                      {
//                                                          if (result == FBWebDialogResultDialogNotCompleted)
//                                                          {
//                                                              // User cancelled.
//                                                              //DLog(@"User cancelled.");
//                                                            
//                                                              handler(false,error);
//
//                                                          } else
//                                                          {
//                                                              // Handle the publish feed callback
//                                                              NSDictionary *urlParams = [self parseURLParams:[resultURL query]];
//                                                              
//                                                              if (![urlParams valueForKey:@"post_id"])
//                                                              {
//                                                                  // User cancelled.
//                                                                  //DLog(@"User cancelled.");
//                                                                  handler(false,error);
//
//                                                                  
//                                                              } else
//                                                              {
//                                                           //        User clicked the Share button
//                                                                //  NSString *result = [NSString stringWithFormat: @"Posted story, id: %@", [urlParams valueForKey:@"post_id"]];
//                                                              //    //DLog(@"result %@", result);
//                                                                  handler(true,error);
//
//                                                              }
//                                                          }
//                                                      }
//                                                  }];
//        
//    }else
//    {
////       // NSArray *permissions = [[[NSArray alloc] initWithObjects:
////                               @"publish_actions",
////                            nil] autorelease];
//        [FBSession openActiveSessionWithPublishPermissions:nil defaultAudience:FBSessionDefaultAudienceEveryone allowLoginUI:YES
//                                         completionHandler:^(FBSession *session, FBSessionState status, NSError *error)
//         {
//             if (error)
//             {
//                 //DLog(@"error  = %@",error);
//             }
//             else if (FB_ISSESSIONOPENWITHSTATE(status))
//             {
//                 NSMutableDictionary *params = [NSMutableDictionary dictionaryWithObjectsAndKeys:
//                                                @"Buddypass", @"name",
//                                                caption, @"caption",
//                                                text, @"description",
////                                                KAPPSTORELINK, @"link",
//                                                @"http://103.25.130.197/buddypass/assets/img/buddypasslogo.png", @"picture",
//                                                nil];
//                 
//                 
//                 // Show the feed dialog
//                 [FBWebDialogs presentFeedDialogModallyWithSession:nil
//                                                        parameters:params
//                                                           handler:^(FBWebDialogResult result, NSURL *resultURL, NSError *error) {
//                                                               if (error)
//                                                               {
//                                                                   // An error occurred, we need to handle the error
//                                                                   // See: https://developers.facebook.com/docs/ios/errors
//                                                                   //DLog(@"Error publishing story: %@", error.description);
//                                                                   handler(false,error);
//                                                                   
//                                                               } else
//                                                               {
//                                                                   if (result == FBWebDialogResultDialogNotCompleted)
//                                                                   {
//                                                                       // User cancelled.
//                                                                       //DLog(@"User cancelled.");
//                                                                     
//                                                                       handler(false,error);
//                                                                       
//                                                                   } else
//                                                                   {
//                                                                       // Handle the publish feed callback
//                                                                       NSDictionary *urlParams = [self parseURLParams:[resultURL query]];
//                                                                       
//                                                                       if (![urlParams valueForKey:@"post_id"])
//                                                                       {
//                                                                           // User cancelled.
//                                                                           //DLog(@"User cancelled.");
//                                                                           handler(false,error);
//                                                                           
//                                                                           
//                                                                       } else
//                                                                       {
////                                                                            User clicked the Share button
//                                                                           NSString *result = [NSString stringWithFormat: @"Posted story, id: %@", [urlParams valueForKey:@"post_id"]];
//                                                                           //DLog(@"result %@", result);
//                                                                           handler(true,error);
//                                                                           
//                                                                       }
//                                                                   }
//                                                               }
//                                                           }];
//                 
//             }
//         }];
//        
//    }
//}
//
//+ (NSDictionary*)parseURLParams:(NSString *)query {
//    NSArray *pairs = [query componentsSeparatedByString:@"&"];
//    NSMutableDictionary *params = [[NSMutableDictionary alloc] init];
//    for (NSString *pair in pairs) {
//        NSArray *kv = [pair componentsSeparatedByString:@"="];
//        NSString *val =
//        [kv[1] stringByReplacingPercentEscapesUsingEncoding:NSUTF8StringEncoding];
//        params[kv[0]] = val;
//    }
//    return params;
//}
//
//+ (void)postAudio
//{
//    if (FBSession.activeSession.isOpen)
//    {
//        NSArray *permissions = [[[NSArray alloc] initWithObjects:
//                                 @"publish_actions",
//                                 nil] autorelease];
//        if (FBSession.activeSession.permissions != permissions)
//        {
//            [FBSession openActiveSessionWithPublishPermissions:permissions defaultAudience:FBSessionDefaultAudienceEveryone allowLoginUI:YES
//                                             completionHandler:^(FBSession *session, FBSessionState status, NSError *error)
//             {
//                 if (error)
//                 {
//                     //DLog(@"err:%@",error);
//                 }
//                 else if (FB_ISSESSIONOPENWITHSTATE(status))
//                 {
//                     
//                     NSMutableDictionary *variables = [NSMutableDictionary dictionaryWithCapacity:4];
//                     
//                     [variables setObject:@"Sharing Audio" forKey:@"message"];
//                     [variables setObject:[NSString stringWithFormat:@"Audio from xyz"] forKey:@"name"];
//                     [variables setObject:[[NSBundle mainBundle] pathForResource:@"aww" ofType:@"mp3"] forKey:@"source"];
//                     
//                     NSURL *pathURL = [[NSURL alloc]initFileURLWithPath:[[NSBundle mainBundle] pathForResource:@"aww" ofType:@"mp3"] isDirectory:NO];
//                     NSData *videoData = [NSData dataWithContentsOfFile:[[NSBundle mainBundle] pathForResource:@"aww" ofType:@"mp3"]];
//                     
//                     NSDictionary *videoObject = @{
//                                                   @"title": @"share",
//                                                   @"description": @"asda",
//                                                   [pathURL absoluteString]: videoData
//                                                   };
//                     
//                     FBRequest *uploadRequest = [FBRequest requestWithGraphPath:@"me/feed"
//                                                                     parameters:videoObject
//                                                                     HTTPMethod:@"POST"];
//                     
//                     [uploadRequest startWithCompletionHandler:^(FBRequestConnection *connection, id result, NSError *error)
//                      {
//                          //DLog(@"success:%@",result);
//                          //DLog(@"err:%@",error);
//                          
//                      }];
//                 }
//             }];
//        }
//        else
//        {
//            NSMutableDictionary *variables = [NSMutableDictionary dictionaryWithCapacity:4];
//            
//            [variables setObject:@"Sharing Audio" forKey:@"message"];
//            [variables setObject:[NSString stringWithFormat:@"Audio from xyz"] forKey:@"name"];
//            [variables setObject:@"Audio URL here.mp3" forKey:@"source"];
//            
//            
//            NSURL *pathURL = [[NSURL alloc]initFileURLWithPath:[[NSBundle mainBundle] pathForResource:@"aww" ofType:@"mp3"] isDirectory:NO];
//            NSData *videoData = [NSData dataWithContentsOfFile:[[NSBundle mainBundle] pathForResource:@"aww" ofType:@"mp3"]];
//            
//            NSDictionary *videoObject = @{
//                                          @"title": @"share",
//                                          @"description": @"asda",
//                                          [pathURL absoluteString]: videoData
//                                          };
//            
//            FBRequest *uploadRequest = [FBRequest requestWithGraphPath:@"me/feed"
//                                                            parameters:videoObject
//                                                            HTTPMethod:@"POST"];
//            [uploadRequest startWithCompletionHandler:^(FBRequestConnection *connection, id result, NSError *error)
//             {
//                 //DLog(@"success:%@",result);
//                 //DLog(@"err:%@",error);
//                 
//             }];
//        }
//    }
//    else
//    {
//        NSArray *permissions = [[[NSArray alloc] initWithObjects:
//                                 @"publish_stream",
//                                 nil] autorelease];
//        
//        [FBSession openActiveSessionWithPublishPermissions:permissions defaultAudience:FBSessionDefaultAudienceEveryone allowLoginUI:YES
//                                         completionHandler:^(FBSession *session, FBSessionState status, NSError *error)
//         {
//             if (error)
//             {
//                 
//             }
//             else if (FB_ISSESSIONOPENWITHSTATE(status))
//             {
//                 NSMutableDictionary *variables = [NSMutableDictionary dictionaryWithCapacity:4];
//                 
//                 [variables setObject:@"Sharing Audio" forKey:@"message"];
//                 [variables setObject:[NSString stringWithFormat:@"Audio from xyz"] forKey:@"name"];
//                 [variables setObject:@"Audio URL here.mp3" forKey:@"source"];
//                 
//                 
//                 NSURL *pathURL = [[NSURL alloc]initFileURLWithPath:[[NSBundle mainBundle] pathForResource:@"aww" ofType:@"mp3"] isDirectory:NO];
//                 NSData *videoData = [NSData dataWithContentsOfFile:[[NSBundle mainBundle] pathForResource:@"aww" ofType:@"mp3"]];
//                 
//                 NSDictionary *videoObject = @{
//                                               @"message": @"share",
//                                               @"description": @"asda",
//                                               [pathURL absoluteString]: videoData
//                                               };
//                 
//                 FBRequest *uploadRequest = [FBRequest requestWithGraphPath:@"me/feed"
//                                                                 parameters:videoObject
//                                                                 HTTPMethod:@"POST"];
//                 
//                 [uploadRequest startWithCompletionHandler:^(FBRequestConnection *connection, id result, NSError *error)
//                  {
//                      //DLog(@"success:%@",result);
//                      //DLog(@"err:%@",error);
//                      
//                  }];
//             }
//         }];
//    }
//    
//    
//    
//    //parse our json
//    //     SBJSON *parser = [[SBJSON alloc] init];
//    //     NSDictionary *facebook_response = [parser objectWithString:fb_graph_response.htmlResponse error:nil];
//    //     [parser release];
//    //
//    //     //let's save the 'id' Facebook gives us so we can delete it if the user presses the 'delete /me/feed button'
//    //     self.feedPostId = (NSString *)[facebook_response objectForKey:@"id"];
//}
//
//
//+ (UIImage *)generatePhotoThumbnail:(UIImage *)image
//{
//    //int kMaxResolution = 320;
//    
//    CGImageRef imgRef = image.CGImage;
//    
//    CGFloat width = CGImageGetWidth(imgRef);
//    CGFloat height = CGImageGetHeight(imgRef);
//    
//    CGAffineTransform transform = CGAffineTransformIdentity;
//    CGRect bounds = CGRectMake(0, 0, width, height);
//    
//    CGFloat scaleRatio = bounds.size.width / width;
//    CGSize imageSize = CGSizeMake(CGImageGetWidth(imgRef), CGImageGetHeight(imgRef));
//    CGFloat boundHeight;
//    UIImageOrientation orient = image.imageOrientation;
//    switch(orient)
//    {
//        case UIImageOrientationUp: //EXIF = 1
//            transform = CGAffineTransformIdentity;
//            break;
//            
//        case UIImageOrientationUpMirrored: //EXIF = 2
//            transform = CGAffineTransformMakeTranslation(imageSize.width, 0.0);
//            transform = CGAffineTransformScale(transform, -1.0, 1.0);
//            break;
//            
//        case UIImageOrientationDown: //EXIF = 3
//            transform = CGAffineTransformMakeTranslation(imageSize.width, imageSize.height);
//            transform = CGAffineTransformRotate(transform, M_PI);
//            break;
//            
//        case UIImageOrientationDownMirrored: //EXIF = 4
//            transform = CGAffineTransformMakeTranslation(0.0, imageSize.height);
//            transform = CGAffineTransformScale(transform, 1.0, -1.0);
//            break;
//            
//        case UIImageOrientationLeftMirrored: //EXIF = 5
//            boundHeight = bounds.size.height;
//            bounds.size.height = bounds.size.width;
//            bounds.size.width = boundHeight;
//            transform = CGAffineTransformMakeTranslation(imageSize.height, imageSize.width);
//            transform = CGAffineTransformScale(transform, -1.0, 1.0);
//            transform = CGAffineTransformRotate(transform, 3.0 * M_PI / 2.0);
//            break;
//            
//        case UIImageOrientationLeft: //EXIF = 6
//            boundHeight = bounds.size.height;
//            bounds.size.height = bounds.size.width;
//            bounds.size.width = boundHeight;
//            transform = CGAffineTransformMakeTranslation(0.0, imageSize.width);
//            transform = CGAffineTransformRotate(transform, 3.0 * M_PI / 2.0);
//            break;
//            
//        case UIImageOrientationRightMirrored: //EXIF = 7
//            boundHeight = bounds.size.height;
//            bounds.size.height = bounds.size.width;
//            bounds.size.width = boundHeight;
//            transform = CGAffineTransformMakeScale(-1.0, 1.0);
//            transform = CGAffineTransformRotate(transform, M_PI / 2.0);
//            break;
//            
//        case UIImageOrientationRight: //EXIF = 8
//            boundHeight = bounds.size.height;
//            bounds.size.height = bounds.size.width;
//            bounds.size.width = boundHeight;
//            transform = CGAffineTransformMakeTranslation(imageSize.height, 0.0);
//            transform = CGAffineTransformRotate(transform, M_PI / 2.0);
//            break;
//        default:
//            [NSException raise:NSInternalInconsistencyException format:@"Invalid image orientation"];
//            break;
//    }
//    
//    UIGraphicsBeginImageContext(bounds.size);
//    
//    CGContextRef context = UIGraphicsGetCurrentContext();
//    
//    if (orient == UIImageOrientationRight || orient == UIImageOrientationLeft)
//    {
//        CGContextScaleCTM(context, -scaleRatio, scaleRatio);
//        CGContextTranslateCTM(context, -height, 0);
//    }
//    else
//    {
//        CGContextScaleCTM(context, scaleRatio, -scaleRatio);
//        CGContextTranslateCTM(context, 0, -height);
//    }
//    
//    CGContextConcatCTM(context, transform);
//    
//    CGContextDrawImage(UIGraphicsGetCurrentContext(), CGRectMake(0, 0, width, height), imgRef);
//    UIImage *imageCopy = UIGraphicsGetImageFromCurrentImageContext();
//    UIGraphicsEndImageContext();
//    
//    return imageCopy;
//    
//}
//
//
//+ (void)askPermissionsCompletertionHandler:(void(^)(id response,NSError *e))completionBlock
//{
//    FBRequestConnection *newConnection = [[FBRequestConnection alloc] init];
//    FBRequest *request = [FBRequest requestWithGraphPath:@"me/albums" parameters:nil HTTPMethod:@"GET"];
//    
//    FBRequestHandler handler =
//    ^(FBRequestConnection *connection, id result, NSError *error)
//    {
//        completionBlock(result,error);
//    };
//    
//    [newConnection addRequest:request completionHandler:handler];
//    [newConnection start];
//    [newConnection release];
//    return;
//    //    NSArray *permissions = [[[NSArray alloc] initWithObjects:
//    //                             @"read_stream",@"user_photos",nil] autorelease];
//    //    
//    //    
//    //    
//    //    [FBSession openActiveSessionWithReadPermissions:permissions
//    //                                       allowLoginUI:YES
//    //                                  completionHandler:^(FBSession *session,
//    //                                                      FBSessionState status,
//    //                                                      NSError *error)
//    //     {
//    //         if (error)
//    //         {
//    //             
//    //         }
//    //         else if (FB_ISSESSIONOPENWITHSTATE(status))
//    //         {
//    //             FBRequestConnection *newConnection = [[FBRequestConnection alloc] init];
//    //             FBRequest *request = [FBRequest requestWithGraphPath:@"me/albums" parameters:nil HTTPMethod:@"GET"];
//    //             
//    //             FBRequestHandler handler =
//    //             ^(FBRequestConnection *connection, id result, NSError *error)
//    //             {
//    //                 completionBlock(result,error);
//    //             };
//    //             
//    //             [newConnection addRequest:request completionHandler:handler];
//    //             [newConnection start];
//    //             [newConnection release];
//    //         }
//    //     }];
//}
//+ (void)getFaceboolAlbumImages:(NSString*)albumId complition:(void(^)(id response,NSError *e))completionBlock
//{
//    [FBRequestConnection startWithGraphPath:[NSString stringWithFormat:@"%@/photos",albumId]
//                                 parameters:nil
//                                 HTTPMethod:@"GET"
//                          completionHandler:^(FBRequestConnection *connection, id result, NSError *error) {
//                              completionBlock(result, error);
//                              
//                          }];
//}



@end
