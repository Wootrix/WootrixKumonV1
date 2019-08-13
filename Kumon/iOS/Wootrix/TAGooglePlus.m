//
//  TAGooglePlus.m
//  NewGooglePlus
//
//  Created by Rohit Singh on 12/05/14.
//  Copyright (c) 2014 TechAhead. All rights reserved.
//

#import "TAGooglePlus.h"
#import <GooglePlus/GooglePlus.h>



// it contains GTLPlusGooglePlus method.
#import <GoogleOpenSource/GoogleOpenSource.h>


@implementation TAGooglePlus
{
        //GPPSignIn  *signIn;

}


+(TAGooglePlus *) goolePlus
{

    static TAGooglePlus *google = nil;
    
    static dispatch_once_t oncetoken;
    
    dispatch_once(&oncetoken, ^{
        
        google = [[TAGooglePlus alloc] init];
    });
    
    return google;
}


//---- Function of signing in
-(void)callSignInGooglePlus:(NSString *)clientID completionHandler:(GooglePlusCompletionHandlerForLogin)completionHandlerForLogin
{
    googlePlusCompletionHandlerForLogin = [completionHandlerForLogin copy];

   GPPSignIn  * signIn = [GPPSignIn sharedInstance];
    
    // Enable Google Plus Service. It will send request to Google Plus to perform sharing and login functionality
    GTLServicePlus *plusService = [[GTLServicePlus alloc] init];
    plusService.retryEnabled = YES;
    
    signIn.clientID = clientID;
    signIn.delegate = self;
    
    // Setting the sacope
    signIn.scopes = [NSArray arrayWithObjects:kGTLAuthScopePlusLogin, nil];
    signIn.shouldFetchGooglePlusUser = YES;
    signIn.shouldFetchGoogleUserEmail = YES;
    signIn.shouldFetchGoogleUserID = YES;
    signIn.delegate = self;
    [signIn trySilentAuthentication];
    [signIn authenticate];
    
    
}



-(void)callSignOut:(GooglePlusCompleteHandler)completionHandler
{
    googlePlusCompleteHandler=[completionHandler copy];
    
    
    [[GPPSignIn sharedInstance] signOut];
    
    //googlePlusCompleteHandler(YES,@"Logout");
    
  }

//---- Function of Link Sharing

-(void)callShareLinkOnGooglePlus:(NSString *)pretext andlink:(NSURL *)link completionHandler:(GooglePlusCompleteHandler)completionHandler
{
    googlePlusCompleteHandler=[completionHandler copy];
    
    id<GPPNativeShareBuilder> shareBuilder = [[GPPShare sharedInstance] nativeShareDialog];
    [GPPShare sharedInstance].delegate = self;
    
    // This line will fill out the title, description, and thumbnail from
    // the URL that you are sharing and includes a link to that URL.
    // [shareBuilder setPrefillText:@"Test Sharing Url"];
    
    [shareBuilder setPrefillText:pretext];
    //[shareBuilder setURLToShare:[NSURL URLWithString:@"https:yahoo.com"]];
    [shareBuilder setURLToShare:link];
    [shareBuilder open];
    
    
    
}
// --- Function of Image Sharing
//-(void)callShareImageOnGooglePlus:(NSString *)pretext andimagename:(NSString *)imagename
-(void)callShareImageOnGooglePlus:(NSString *)pretext andimagename:(NSString *)imagename isbundle:(BOOL)isBundle completionHandler:(GooglePlusCompleteHandler)completionHandler
{
    googlePlusCompleteHandler=[completionHandler copy];

    id<GPPNativeShareBuilder> sharebuilder = [[GPPShare sharedInstance] nativeShareDialog];
    
    [GPPShare sharedInstance].delegate = self;
    //  [sharebuilder setPrefillText:@"Test Image Sharing"];
    
    [sharebuilder setPrefillText:pretext];
    //  [sharebuilder attachImage:[UIImage imageNamed:@"user.png"]];
    
    UIImage *image = nil;
    if(!isBundle)
        image = [UIImage imageWithContentsOfFile:imagename];
    else
        image = [UIImage imageNamed:imagename];
        
    
    [sharebuilder attachImage:image];
    [sharebuilder open];
}

//------- Function of video Sharing
-(void)callShareVideoOnGooglePlus:(NSString *)pretext videoName:(NSString *)videoName extension:(NSString *)Extension completionHandler:(GooglePlusCompleteHandler)completionHandler
{
    googlePlusCompleteHandler=[completionHandler copy];

    id<GPPNativeShareBuilder> sharedBuider = [[GPPShare sharedInstance] nativeShareDialog];
    
    [GPPShare sharedInstance].delegate = self;
    // [sharedBuider setPrefillText:@"Test Video Sharing"];
    [sharedBuider setPrefillText:pretext];
    
    //    NSString *fileName = @"Test";
    //    NSString *extension = @"mp4";
    //    NSURL *urlpath = [[NSBundle mainBundle] URLForResource:fileName withExtension:extension];
    NSURL *urlpath = [[NSBundle mainBundle] URLForResource:videoName withExtension:Extension];
    
    [sharedBuider attachVideoURL:urlpath];
    [sharedBuider open];
    
}


// Call will come after authenticated from the google plus
-(void)finishedWithAuth: (GTMOAuth2Authentication *)auth
                   error: (NSError *) error
{
    GPPSignIn  * signIn = [GPPSignIn sharedInstance];

    if(!auth)
    {
        //[Shared shared].isThirdPartyLogin = NO;
        [self callSignOut:^(BOOL isCheck, id status) {
            
            
        }];
        NSLog(@"please login");
    }
    else
    {
   // [Shared shared].isThirdPartyLogin = YES;

    //BOOL check = [auth isau]
    NSLog(@"user google user email /n %@",signIn.userEmail);
    // To get logged in user's id
    NSLog(@"user google user id /n %@",signIn.userID);
    NSLog(@"user google id token /n %@",signIn.idToken);
    // To get Full User information
   // NSLog(@"%@",signIn.googlePlusUser);
    
    // Sending Value to the block called in your own class
       // [ setObject:auth.accessToken forKey:kGooglePlusAccessToken];
       // [UserDefault synchronize];
        isFirstCall = NO;
        if(!isFirstCall)
        {
            googlePlusCompletionHandlerForLogin((!error)?YES:NO,signIn);
            isFirstCall = YES;//igonre scond call expicitly
            
        }
    }
  
    
}




// Optional
// Detecting Whether post is sucessful or not
-(void)finishedSharingWithError:(NSError *)error {
    NSString *text;
    
    if (!error) {
        text = @"Success";
        // NSLog(@"%@",text);
    } else if (error.code == kGPPErrorShareboxCanceled) {
        text = @"Cancelled";
        //NSLog(@"%@",text);
    } else {
        //text = [NSString stringWithFormat:@"Error (%@)", [error localizedDescription]];
        NSLog(@"%@",@"please login");
    }
    
    NSLog(@"Status: %@", text);
    NSLog(@"%@",text);
    googlePlusCompleteHandler((!error)?YES:NO,text);
}

-(id<GPPNativeShareBuilder>)attachImage:(UIImage *)imageAttachment
{
    return nil;
}

- (id<GPPNativeShareBuilder>)attachImageData:(NSData *)imageData
{
    return nil;
}

-(id<GPPNativeShareBuilder>)attachVideoURL:(NSURL *)videoAttachment
{
    return nil;
}

-(id<GPPShareBuilder>)setTitle:(NSString *)title description:(NSString *)description thumbnailURL:(NSURL *)thumbnailURL
{
    return nil;
}

-(id<GPPShareBuilder>)setContentDeepLinkID:(NSString *)contentDeepLinkID
{
    return nil;
}

-(id<GPPShareBuilder>)setCallToActionButtonWithLabel:(NSString *)label URL:(NSURL *)url deepLinkID:(NSString *)deepLinkID
{

    return nil;
}

-(id<GPPNativeShareBuilder>)setPreselectedPeopleIDs:(NSArray *)preselectedPeopleIDs
{

    return nil;
}

-(id<GPPShareBuilder>)setPrefillText:(NSString *)prefillText
{
    return nil;
}

-(id<GPPShareBuilder>)setURLToShare:(NSURL *)urlToShare
{

    return nil;
}
@end


