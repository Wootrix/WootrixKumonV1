//
//  TALinkedInHelper.m
//  Engrami
//
//  Created by Teena Nath Paul on 01/09/14.
//  Copyright (c) 2014 Teena Nath Paul. All rights reserved.
//

#import "TALinkedInHelper.h"



#define LINKEDIN_CLIENT_ID      @"75ubes5y7j6jj1"
#define LINKEDIN_CLIENT_SECRET  @"sFqqmy5LX9SpaeeH"
#define LINKEDIN_REDIRECT_URL   @"http://wootrix.com/"

@interface TALinkedInHelper ()

@property (strong, nonatomic) LIALinkedInHttpClient *client;
@property (copy, nonatomic) void(^linkedInUserInfoBlock)(id resonse, NSError *error);
@property (copy, nonatomic) void(^linkedInFriendsInfoBlock)(id resonse, NSError *error);
@property (copy, nonatomic) void(^linkedInShareBlock)(id resonse, NSError *error);

@end

@implementation TALinkedInHelper

+ (TALinkedInHelper *)sharedInstance
{
    static TALinkedInHelper *_sharedInstance = nil;
    
    static dispatch_once_t onceToken;
    dispatch_once(&onceToken, ^{
        _sharedInstance = [[TALinkedInHelper alloc] init];
    });
    
    return _sharedInstance;
}

- (LIALinkedInHttpClient *)client
{
    //@[@"r_fullprofile", @"r_network",@"r_emailaddress",@"rw_groups",@"r_contactinfo",@"rw_company_admin",@"rw_nus",@"w_messages",@"r_network",@"r_basicprofile"]
    
    //@[@"r_fullprofile",@"r_emailaddress",@"r_contactinfo",@"rw_nus"]
    
    LIALinkedInApplication *application = [LIALinkedInApplication applicationWithRedirectURL:LINKEDIN_REDIRECT_URL
                                                                                    clientId:LINKEDIN_CLIENT_ID
                                                                                clientSecret:LINKEDIN_CLIENT_SECRET
                                                                                       state:@"DCEEFWF45453sdffef424"
                                                                               grantedAccess:@[@"r_basicprofile", @"r_emailaddress", @"w_share"]];
    return [LIALinkedInHttpClient clientForApplication:application presentingViewController:nil];
}


- (void)requestMeWithToken:(NSString *)accessToken andFields:(NSString *)fields
{
    //id,first-name,last-name,industry,picture-url,location:(name),positions:(company:(name),title),specialties,date-of-birth,interests,languages,email-address
    
    [self.client GET:[NSString stringWithFormat:@"https://api.linkedin.com/v1/people/~:(%@)?oauth2_access_token=%@&format=json",fields,accessToken] parameters:nil success:^(AFHTTPRequestOperation *operation, NSDictionary *result)
     {
         if (_linkedInUserInfoBlock)
         {
             _linkedInUserInfoBlock(result, nil);
             _linkedInUserInfoBlock = nil;
         }
         
     }
     failure:^(AFHTTPRequestOperation *operation, NSError *error)
     {
         NSLog(@"failed to fetch current user %@", error);
         if (_linkedInUserInfoBlock)
         {
             _linkedInUserInfoBlock(nil, error);
             _linkedInUserInfoBlock = nil;
         }
     }];
}

- (void)requestConnectionsWithToken:(NSString *)accessToken
{
    [self.client GET:[NSString stringWithFormat:@"https://api.linkedin.com/v1/people/~/connections?oauth2_access_token=%@&format=json", accessToken] parameters:nil success:^(AFHTTPRequestOperation *operation, NSDictionary *result)
     {
         if (_linkedInFriendsInfoBlock)
         {
             _linkedInFriendsInfoBlock(result, nil);
             _linkedInFriendsInfoBlock = nil;
         }
     }
     failure:^(AFHTTPRequestOperation *operation, NSError *error)
     {
         NSLog(@"failed to fetch current user %@", error);
         if (_linkedInFriendsInfoBlock)
         {
             _linkedInFriendsInfoBlock(nil, error);
             _linkedInFriendsInfoBlock = nil;
         }
     }];
}



- (void)getLinkedInUserInfoForFields:(NSString *)fields completion:(void(^)(id resonse, NSError *error))block
{
    _linkedInUserInfoBlock = block;
    
    [self.client getAuthorizationCode:^(NSString *code)
     {
         [self.client getAccessToken:code success:^(NSDictionary *accessTokenData)
          {
              NSLog(@"accessToken is = %@", [accessTokenData objectForKey:@"access_token"]);
              NSString *accessToken = [accessTokenData objectForKey:@"access_token"];
              [self requestMeWithToken:accessToken andFields:fields];
          }
          failure:^(NSError *error)
          {
              NSLog(@"Quering accessToken failed %@", error);
              if (_linkedInUserInfoBlock)
              {
                  _linkedInUserInfoBlock(nil, error);
                  _linkedInUserInfoBlock = nil;
              }
          }];
     }
     cancel:^
     {
         NSLog(@"Authorization was cancelled by user");
         if (_linkedInUserInfoBlock)
         {
             _linkedInUserInfoBlock(nil, [NSError errorWithDomain:@"Authorization was cancelled by user" code:0 userInfo:nil]);
             _linkedInUserInfoBlock = nil;
         }
     }
     failure:^(NSError *error)
     {
         NSLog(@"Authorization failed %@", error);
         if (_linkedInUserInfoBlock)
         {
             _linkedInUserInfoBlock(nil, error);
             _linkedInUserInfoBlock = nil;
         }
     }];
}

- (void)getLinkedInFriendsInfoWithCompletion:(void(^)(id resonse, NSError *error))block
{
    _linkedInFriendsInfoBlock = block;
    
    [self.client getAuthorizationCode:^(NSString *code)
     {
         [self.client getAccessToken:code success:^(NSDictionary *accessTokenData)
          {
              NSLog(@"accessToken is = %@", [accessTokenData objectForKey:@"access_token"]);
              NSString *accessToken = [accessTokenData objectForKey:@"access_token"];
              [self requestConnectionsWithToken:accessToken];
          }
          failure:^(NSError *error)
          {
              NSLog(@"Quering accessToken failed %@", error);
              if (_linkedInFriendsInfoBlock)
              {
                  _linkedInFriendsInfoBlock(nil, error);
                  _linkedInFriendsInfoBlock = nil;
              }
          }];
     }
     cancel:^
     {
         NSLog(@"Authorization was cancelled by user");
         if (_linkedInFriendsInfoBlock)
         {
             _linkedInFriendsInfoBlock(nil, [NSError errorWithDomain:@"Authorization was cancelled by user" code:0 userInfo:nil]);
             _linkedInFriendsInfoBlock = nil;
         }
     }
     failure:^(NSError *error)
     {
         NSLog(@"Authorization failed %@", error);
         if (_linkedInFriendsInfoBlock)
         {
             _linkedInFriendsInfoBlock(nil, error);
             _linkedInFriendsInfoBlock = nil;
         }
     }];
}

//700 Characters is the limit
/*
NSDictionary *content = @{@"title" : @"Title name",
                          @"description" : @"description",
                          @"submitted-url" : @"http://www.engrami.com",
                          @"submitted-image-url":@"http://d9hhrg4mnvzow.cloudfront.net/www.engrami.com/aaf32e04-lp-screenshots-04_08z0f808z0cx000000.png"
                          };

NSDictionary *share = @{@"comment": @"Engrami iOS App",
                        @"content":content,
                        @"visibility":@{@"code":@"anyone"}
                        };
*/
- (void)shareOnLinkedWithParams:(NSDictionary *)params SuccessBlock:(void(^)(id resonse, NSError *error))block
{
    _linkedInShareBlock = block;
    
    if ([self.client validToken])
    {
        [self requestForSharingWithToken:[self.client accessToken] andParams:params];
    }
    else
    {
        [self.client getAuthorizationCode:^(NSString *code)
         {
             [self.client getAccessToken:code success:^(NSDictionary *accessTokenData)
              {
                  NSLog(@"accessToken is = %@", [accessTokenData objectForKey:@"access_token"]);
                  NSString *accessToken = [accessTokenData objectForKey:@"access_token"];
                  [self requestForSharingWithToken:accessToken andParams:params];
              }
                                 failure:^(NSError *error)
              {
                  NSLog(@"Quering accessToken failed %@", error);
                  if (_linkedInShareBlock)
                  {
                      _linkedInShareBlock(nil, error);
                      _linkedInShareBlock = nil;
                  }
              }];
         }
                                   cancel:^
         {
             NSLog(@"Authorization was cancelled by user");
             if (_linkedInShareBlock)
             {
                 _linkedInShareBlock(nil, [NSError errorWithDomain:@"Authorization was cancelled by user" code:10 userInfo:nil]);
                 _linkedInShareBlock = nil;
             }
         }
                                  failure:^(NSError *error)
         {
             NSLog(@"Authorization failed %@", error);
             if (_linkedInShareBlock)
             {
                 _linkedInShareBlock(nil, error);
                 _linkedInShareBlock = nil;
             }
         }];
    }
    
}

//Linked in Sharing testing
- (void)requestForSharingWithToken:(NSString *)accessToken andParams:(NSDictionary *)params
{
    NSString *url = [NSString stringWithFormat:@"https://api.linkedin.com/v1/people/~/shares?oauth2_access_token=%@&format=json",accessToken];
    NSData *data = [NSJSONSerialization dataWithJSONObject:params options:0 error:nil];
    
    
    NSMutableURLRequest *request = [NSMutableURLRequest requestWithURL:[NSURL URLWithString:url]];
    [request setHTTPMethod:@"POST"];
    [request setValue:@"application/json" forHTTPHeaderField:@"Content-Type"];
    [request setHTTPBody:data];
    
    [NSURLConnection sendAsynchronousRequest:request queue:[NSOperationQueue mainQueue] completionHandler:^(NSURLResponse *response, NSData *data, NSError *connectionError)
    {
        if (connectionError || data == nil)
        {
            if (_linkedInShareBlock)
            {
                _linkedInShareBlock(nil, connectionError);
                _linkedInShareBlock = nil;
            }
        }
        else
        {
            if (_linkedInShareBlock)
            {
                NSDictionary *responseObject = [NSJSONSerialization JSONObjectWithData:data options:0 error:nil];
                _linkedInShareBlock(responseObject, nil);
                _linkedInShareBlock = nil;
            }
            
        }
    }];
}





@end
