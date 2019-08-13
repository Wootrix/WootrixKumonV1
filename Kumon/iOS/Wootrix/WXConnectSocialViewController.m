//
//  WXConnectSocialViewController.m
//  Wootrix
//
//  Created by Teena Nath Paul on 15/07/15.
//  Copyright (c) 2015 Techahead. All rights reserved.
//

#import "WXConnectSocialViewController.h"
#import "WXLoginViewController.h"
#import "HCGoogleWebControllerViewController.h"
#import "WXHomeViewController.h"
#import <GooglePlus/GooglePlus.h>

#define kFaceBookAppID  @"906837972662201"
//#define kGoogleClientId @"410271304403-i4e4mo8kj94mh9rg8trc6n6a61h58rda.apps.googleusercontent.com"
#define kGoogleClientId @"181469443324-eql5au35e3uku3f2kuqa8jdr3kinpcis.apps.googleusercontent.com"


@interface WXConnectSocialViewController ()
{
  NSMutableArray *arrOptions;
  NSIndexPath *currentIndexPath;
  NSDictionary *dictCurrentParams;
}
@property (weak, nonatomic) IBOutlet UITableView *tblView;
@property (nonatomic, retain)ACAccountStore *facebookACAccountStore;
@property (nonatomic, retain)ACAccountStore *twitterACAccountStore;

@end

@implementation WXConnectSocialViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view.
  
  [self callAPIGetAccountDetails];
  
  self.tblView.tableFooterView = [[UIView alloc] initWithFrame:CGRectZero];
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (void)setUpDate
{
  arrOptions = [[NSMutableArray alloc] initWithObjects:@{@"name":@"Facebook",
                                                         @"image":@"facebook",
                                                         @"isConnected":@([UserDefaults boolForKey:kIsFacebook])
                                                         },
                @{@"name":@"Google",
                  @"image":@"google",
                  @"isConnected":@([UserDefaults boolForKey:kIsGoogle])
                  },
                @{@"name":@"Twitter",
                  @"image":@"twitter",
                  @"isConnected":@([UserDefaults boolForKey:kIsTwitter])
                  },
                @{@"name":@"LinkedIn",
                  @"image":@"linkedin",
                  @"isConnected":@([UserDefaults boolForKey:kIsLinkedIn])
                  }, nil];
  [_tblView reloadData];
}


#pragma mark - Table Delegates
- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
  return arrOptions.count;
}
- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
  UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:@"Cell" forIndexPath:indexPath];
  NSDictionary *dict = [arrOptions objectAtIndex:indexPath.row];

  UIImageView *img = (UIImageView *)[cell.contentView viewWithTag:10];
  img.image = [UIImage imageNamed:[dict objectForKey:@"image"]];
  
  UILabel *lblHeading = (UILabel *)[cell.contentView viewWithTag:11];
  lblHeading.text = [dict objectForKey:@"name"];
  
  UILabel *lblSubHeading = (UILabel *)[cell.contentView viewWithTag:12];
  lblSubHeading.text = @"Connected";
  if ([[dict objectForKey:@"isConnected"] boolValue]) {
    lblSubHeading.textColor = [UIColor colorWithRed:49.0/255.0 green:164.0/255.0 blue:230.0/255.0 alpha:1.0];
    lblSubHeading.text = @"Connected";
  }
  else {
    lblSubHeading.textColor = [UIColor lightGrayColor];
    lblSubHeading.text = @"Connect";
  }

  return cell;
}

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
  NSDictionary *dict = [arrOptions objectAtIndex:indexPath.row];

  if ([[dict objectForKey:@"isConnected"] boolValue]) {
    return;
  }
  
  currentIndexPath = indexPath;
  
  if ([[dict objectForKey:@"name"] isEqualToString:@"Facebook"])
  {
    [self connectToFacebook];
  }
  else if ([[dict objectForKey:@"name"] isEqualToString:@"Twitter"])
  {
    [self connectToTwitter];
  }
  else if ([[dict objectForKey:@"name"] isEqualToString:@"Google"])
  {
    [self connectToGoogle];
  }
  else if ([[dict objectForKey:@"name"] isEqualToString:@"LinkedIn"])
  {
    [self connectToLinedIn];
  }
  
}

#pragma mark - Button Actions
- (IBAction)buttonBackTapped:(UIButton *)sender
{
  [self.navigationController popViewControllerAnimated:YES];
}

#pragma mark - Social Apis

/**
 *  Called to fetch data from twitter account in settings menu
 *
 *  @param completeHandler Returns nsdictionary of desired values
 */
-(void)loginWithTwitter:(void(^)(NSDictionary *response))completeHandler
{
  
  self.twitterACAccountStore = [[ACAccountStore alloc] init];
  
  ACAccountType *twitterTypeAccount = [self.twitterACAccountStore accountTypeWithAccountTypeIdentifier:ACAccountTypeIdentifierTwitter];
  
  [self.twitterACAccountStore requestAccessToAccountsWithType:twitterTypeAccount
                                                      options:nil
                                                   completion:^(BOOL granted, NSError *error)
   {
     if(granted)
     {
       NSArray *accounts = [self.twitterACAccountStore accountsWithAccountType:twitterTypeAccount];
       if(accounts.count==0)
       {
         dispatch_async(dispatch_get_main_queue(), ^
                        {
                          completeHandler(nil);
                        });
         
         return ;
       }
       ACAccount *twitterAccount=[accounts objectAtIndex:0];
       
       //NSURL *meurl = [NSURL URLWithString:@"http://api.twitter.com/1.1/users/show.json"];
       NSURL *meurl = [NSURL URLWithString:@"https://api.twitter.com/1/account/verify_credentials.json"];
       
       //NSDictionary *params = [NSDictionary dictionaryWithObjectsAndKeys:@"",@"screen_name",nil];
       
       SLRequest *merequest = [SLRequest requestForServiceType:SLServiceTypeTwitter
                                                 requestMethod:SLRequestMethodGET
                                                           URL:meurl
                                                    parameters:nil];
       
       merequest.account = twitterAccount;
       
       [merequest performRequestWithHandler:^(NSData *responseData, NSHTTPURLResponse *urlResponse, NSError *error)
        {
          //NSString *meDataString = [[NSString alloc] initWithData:responseData encoding:NSUTF8StringEncoding];
          
          //NSLog(@"%@", meDataString);
          dispatch_async(dispatch_get_main_queue(), ^
                         {
                           if(error)
                           {
                             completeHandler(nil);
                             
                           }
                           else
                           {
                             NSDictionary *jsonDict=[NSJSONSerialization JSONObjectWithData:responseData options:0 error:nil];
                             completeHandler(jsonDict);
                             
                           }
                           
                         });
        }];
     }else{
       // ouch
       dispatch_async(dispatch_get_main_queue(), ^{
         completeHandler(nil);
         
       });
     }
   }];
  
}

- (void)isFacebookConfigured:(void(^)(ACAccount *account))block
{
  self.facebookACAccountStore = [[ACAccountStore alloc] init];
  NSArray *permissionsArray = [[NSArray alloc] initWithObjects:
                               @"read_stream",
                               @"user_birthday",
                               @"email",
                               nil];
  
  ACAccountType *facebookTypeAccount = [self.facebookACAccountStore accountTypeWithAccountTypeIdentifier:ACAccountTypeIdentifierFacebook];
  
  [self.facebookACAccountStore requestAccessToAccountsWithType:facebookTypeAccount
                                                       options:@{ACFacebookAppIdKey: kFaceBookAppID, ACFacebookPermissionsKey:permissionsArray,ACFacebookAudienceKey : ACFacebookAudienceEveryone}
                                                    completion:^(BOOL granted, NSError *error)
   {
     
     dispatch_async(dispatch_get_main_queue(), ^
                    {
                      if(granted)
                      {
                        NSArray *accounts = [self.facebookACAccountStore accountsWithAccountType:facebookTypeAccount];
                        if(accounts.count==0)
                        {
                          block(nil);
                        }
                        else
                        {
                          ACAccount *faceBookAccount=[accounts objectAtIndex:0];
                          block(faceBookAccount);
                        }
                      }
                      else
                      {
                        block(nil);
                      }
                      
                    });
   }];
}

- (void)registerNewUserForGoogle:(GoogleCompletionHandler)completionHandler
{
  //[Utils startActivityIndicatorInView:SharedAppDelegate.window withMessage:kLoading];
  
//  [[NSNotificationCenter defaultCenter] addObserver:self
//                                           selector:@selector(callGoogleLoginPromptController:)
//                                               name:kApplicationOpenGoogleAuthNotification
//                                             object:nil];
  
//  [[GPPSignIn sharedInstance] signOut];
  [[GPPSignIn sharedInstance] trySilentAuthentication];
  [[TAGooglePlus goolePlus] callSignInGooglePlus:kGoogleClientId completionHandler:^(BOOL isSignIn, GPPSignIn *signIn)
   {
     [self dismissProgressHUD];
        [self.view setUserInteractionEnabled:YES];
     if(isSignIn)
     {
       GTLPlusPerson *person = [GPPSignIn sharedInstance].googlePlusUser;
       
       NSDictionary *dictSendParams = @{@"email"              :signIn.userEmail,
                                        @"password"           :@"",
                                        @"device"             :@"iPhone",
                                        @"osversion"          :[[UIDevice currentDevice] systemVersion],
                                        @"socialaccounttype"  :@"gPlus",
                                        @"socialaccountid"    :[signIn userID],
                                        @"socialaccounttoken" :@"",
                                        @"applanguage"        :@"en",
                                        @"name"               :person.name.familyName,
                                        @"photourl"           :person.image.url};
       completionHandler(dictSendParams);
     }
     else
     {
     }
   }];
}

- (void)callGoogleLoginPromptController: (NSNotification *)notif
{
  
  
  HCGoogleWebControllerViewController *loginGoogle = [[HCGoogleWebControllerViewController alloc] init];
  loginGoogle.url = [NSURL URLWithString:[NSString stringWithFormat:@"%@",notif.object]];
  
  [self.navigationController presentViewController:loginGoogle animated:YES completion:nil];
  
}


- (void)loginWithFacebookWithCompletion:(void(^)(NSDictionary *response,BOOL isCancelled))block
{
//  [self isFacebookConfigured:^(ACAccount *account)
//   {
//     if (account)
//     {
//       ACAccount *faceBookAccount = account;
//       
//       
//       NSDictionary *params = @{@"fields": @"id,email,picture.width(640),gender,work,first_name,last_name,location"};
//       NSURL *meurl = [NSURL URLWithString:@"https://graph.facebook.com/me"];
//       
//       SLRequest *merequest = [SLRequest requestForServiceType:SLServiceTypeFacebook
//                                                 requestMethod:SLRequestMethodGET
//                                                           URL:meurl
//                                                    parameters:params];
//       merequest.account = faceBookAccount;
//       
//       [merequest performRequestWithHandler:^(NSData *responseData, NSHTTPURLResponse *urlResponse, NSError *error)
//        {
//          NSError* e;
//          NSDictionary* json = [NSJSONSerialization JSONObjectWithData:responseData
//                                                               options:kNilOptions
//                                                                 error:&e];
//          block(json,NO);
//        }];
//     }
//     else
//     {
//       
//       NSArray *permissionsArray = [[NSArray alloc] initWithObjects:
//                                    @"read_stream",
//                                    @"user_birthday",
//                                    @"email",
//                                    nil];
//       NSString *params = @"id,email,picture.width(640),gender,work,first_name,last_name,location";
//       [TAFacebookHelper fetchPersonalInfoWithParams:params withPermissions:permissionsArray completionHandler:^(id response, NSError *e) {
//         
//         if (response)
//         {
//           block(response, NO);
//         }
//         else
//         {
//           if ([[e localizedFailureReason] isEqualToString:@"com.facebook.sdk:UserLoginCancelled"])
//           {
//             block(nil,YES);
//           }
//           else
//           {
//             block(response, NO);
//           }
//         }
//       }];
//     }
//   }];
}

#pragma mark - Connect

- (void)connectToFacebook
{
  [self showProgressHUD:[WXLoginViewController languageSelectedStringForKey:@"Loading..."]];
     [self.view setUserInteractionEnabled:NO];
  [self loginWithFacebookWithCompletion:^(NSDictionary *response, BOOL isCancelled)
   {
     [self dismissProgressHUD];
      [self.view setUserInteractionEnabled:YES];
     if (response) {
       NSDictionary *dictSendParams = @{@"email"              :[response objectForKey:@"email"],
                                        @"password"           :@"",
                                        @"device"             :@"iPhone",
                                        @"osversion"          :[[UIDevice currentDevice] systemVersion],
                                        @"socialaccounttype"  :@"fb",
                                        @"socialaccountid"    :[response objectForKey:@"id"],
                                        @"socialaccounttoken" :@"",
                                        @"applanguage"        :@"en",
                                        @"name"               :[response objectForKey:@"first_name"],
                                        @"photourl"           :[[[response objectForKey:@"picture"]
                                                                 objectForKey:@"data"] objectForKey:@"url"]};
       [self callAPIForAddAccountForDict:dictSendParams];

     }
   }
   ];
}

- (void)connectToTwitter
{
  [self showProgressHUD:[WXLoginViewController languageSelectedStringForKey:@"Loading..."]];
     [self.view setUserInteractionEnabled:NO];
  [self loginWithTwitter:^(NSDictionary *response)
   {
     
     [self dismissProgressHUD];
        [self.view setUserInteractionEnabled:YES];
     
     if (response)
     {
       NSDictionary *dictSendParams = @{@"email"              :[response objectForKey:@"screen_name"],
                                        @"password"           :@"",
                                        @"device"             :@"iPhone",
                                        @"osversion"          :[[UIDevice currentDevice] systemVersion],
                                        @"socialaccounttype"  :@"tw",
                                        @"socialaccountid"    :[response objectForKey:@"id_str"],
                                        @"socialaccounttoken" :@"",
                                        @"applanguage"        :@"en",
                                        @"name"               :[response objectForKey:@"name"],
                                        @"photourl"           :[response objectForKey:@"profile_image_url"]
                                        };
       [self callAPIForAddAccountForDict:dictSendParams];
     }
     
   }];
}

- (void)connectToGoogle
{
  [self registerNewUserForGoogle:^(NSDictionary *response) {
    
    if (response)
    {
      [self callAPIForAddAccountForDict:response];
    }
    
  }];
}

- (void)connectToLinedIn
{
  NSString *fields = @"id,first-name,last-name,industry,picture-urls::(original),location:(name),positions:(company:(name),title),specialties,languages,email-address,last-modified-timestamp,proposal-comments,associations,interests,publications,patents,skills,certifications,educations,courses,volunteer,three-current-positions,three-past-positions,num-recommenders,recommendations-received,following,job-bookmarks,suggestions,date-of-birth,member-url-resources,related-profile-views,honors-awards";
  
  [[TALinkedInHelper sharedInstance] getLinkedInUserInfoForFields:fields completion:^(id response, NSError *error)
   {
     if (response)
     {
       
       NSString *picURL = @"";
       NSArray *arrPics = [[response objectForKey:@"pictureUrls"] objectForKey:@"values"];
       if ([arrPics isKindOfClass:[NSArray class]] && arrPics.count > 0) {
         picURL = arrPics[0];
       }
       
       NSDictionary *dictSendParams = @{@"email"              :[response objectForKey:@"emailAddress"],
                                        @"password"           :@"",
                                        @"device"             :@"iPhone",
                                        @"osversion"          :[[UIDevice currentDevice] systemVersion],
                                        @"socialaccounttype"  :@"in",
                                        @"socialaccountid"    :[response objectForKey:@"id"],
                                        @"socialaccounttoken" :@"",
                                        @"applanguage"        :@"en",
                                        @"name"               :[response objectForKey:@"firstName"],
                                        @"photourl"           :picURL
                                        };
       [self callAPIForAddAccountForDict:dictSendParams];
     }
   }];
}

#pragma mark - APIs

- (void)callAPIGetAccountDetails
{
  //json.put("token", userid);
  //json.put("appLanguage", Utility.getDrfaultLanguage());
  
  [self showProgressHUD:[WXHomeViewController languageSelectedStringForKey:@"Loading..."]]; // hardcoded for testing
  NSDictionary *params = @{
                           @"appLanguage" : @"en",
                           @"token" : [UserDefaults valueForKey:kToken],
                           };
  
  [[NetworkManager manager] requestNonJsonModelWithMethod:kHTTPMethodPOST1 URLString:@"get_account_details" parameters:params sucess:^(id response) {
    [self dismissProgressHUD];
       [self.view setUserInteractionEnabled:YES];

    if (response)
    {
      NSDictionary *dictData = [response objectForKey:kData];
      
      [UserDefaults setBool:[[dictData objectForKey:kIsFacebook] boolValue] forKey:kIsFacebook];
      [UserDefaults setBool:[[dictData objectForKey:kIsTwitter] boolValue] forKey:kIsTwitter];
      [UserDefaults setBool:[[dictData objectForKey:kIsGoogle] boolValue] forKey:kIsGoogle];
      [UserDefaults setBool:[[dictData objectForKey:kIsLinkedIn] boolValue] forKey:kIsLinkedIn];
      [UserDefaults setBool:[[dictData objectForKey:kIsEmail] boolValue] forKey:kIsEmail];
      [UserDefaults synchronize];
      
      [self setUpDate];
    }
    
  } failure:^(NSError *error) {
    
  }];
  
}

- (void)callAPIForAddAccountForDict:(NSDictionary *)dict
{
  //add_more_accounts
  //{"socialAccountId":267164107,"osVersion":"Andriod 4.4.219 (19)","appLanguage":"en","socialAccountToken":"vipinkesari","email":"saurav.kala@techaheadcorp.com","token":"SauravKala","socialAccountType":"tw","device":"Android","user_id":"SauravKala"}
  NSDictionary *params = @{
                           @"socialAccountId" : [dict objectForKeyNonNull:@"socialaccountid"],
                           @"osVersion" : [dict objectForKeyNonNull:@"osversion"],
                           @"appLanguage" : [dict objectForKeyNonNull:@"applanguage"],
                           @"socialAccountToken" : @"",
                           @"email" : [dict objectForKeyNonNull:@"email"],
                           @"token" : [UserDefaults valueForKey:kToken],
                           @"device" : [dict objectForKeyNonNull:@"device"],
                           @"user_id" : [UserDefaults valueForKey:kToken],
                           @"socialAccountType" : [dict objectForKeyNonNull:@"socialaccounttype"]
                           };
  
  [self showProgressHUD:[WXHomeViewController languageSelectedStringForKey:@"Loading..."]]; // hardcoded for testing
     [self.view setUserInteractionEnabled:NO];
  [[NetworkManager manager] requestNonJsonModelWithMethod:kHTTPMethodPOST1 URLString:@"add_more_accounts" parameters:params sucess:^(id response) {
    [self dismissProgressHUD];
       [self.view setUserInteractionEnabled:YES];

    if (response) {
      
      if ([[response objectForKey:@"id"] boolValue] == 0)
      {
        [[[UIAlertView alloc] initWithTitle:kAPPName message:[response objectForKey:@"message"] delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil] show];
      }
      else
      {
        dictCurrentParams = params;
        
//        [[UIKitBlock sharedInstance] alertViewWithTitle:kAPPName message:[response objectForKey:@"message"] buttons:@[@"Merge",@"Cancel"] completion:^(NSInteger buttonIndex) {
//          
//          if (buttonIndex == 0) {
//            [self callAPIForMergeAccountForParams:params];
//          }
//        }];
        
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:kAPPName message:[response objectForKey:@"message"] delegate:self cancelButtonTitle:@"Merge" otherButtonTitles:@"Cancel", nil];
        alert.delegate =self;
        [alert show];
      }
    }
    
  } failure:^(NSError *error) {
    
  }];
}

- (void)alertView:(UIAlertView *)alertView clickedButtonAtIndex:(NSInteger)buttonIndex
{
  if (buttonIndex == 0) {
    if (buttonIndex == 0) {
      [self callAPIForMergeAccountForParams:dictCurrentParams];
    }
  }
}

- (void)callAPIForMergeAccountForParams:(NSDictionary *)params
{
  //add_more_accounts
  //{"socialAccountId":267164107,"osVersion":"Andriod 4.4.219 (19)","appLanguage":"en","socialAccountToken":"vipinkesari","email":"saurav.kala@techaheadcorp.com","token":"SauravKala","socialAccountType":"tw","device":"Android","user_id":"SauravKala"}
  
  [self showProgressHUD:[WXHomeViewController languageSelectedStringForKey:@"Loading..."]]; // hardcoded for testing
     [self.view setUserInteractionEnabled:NO];
  [[NetworkManager manager] requestNonJsonModelWithMethod:kHTTPMethodPOST1 URLString:@"merge_accounts" parameters:params sucess:^(id response) {
    
    [self dismissProgressHUD];
       [self.view setUserInteractionEnabled:YES];

    if (response) {
 
      NSMutableDictionary *dictSocial = [[arrOptions objectAtIndex:currentIndexPath.row] mutableCopy];
      [dictSocial setValue:@1 forKey:@"isConnected"];
      [arrOptions replaceObjectAtIndex:currentIndexPath.row withObject:dictSocial];
      [self.tblView reloadRowsAtIndexPaths:@[currentIndexPath] withRowAnimation:UITableViewRowAnimationAutomatic];
      
      if ([[dictSocial objectForKey:@"name"] isEqualToString:@"Facebook"])
      {
        [UserDefaults setBool:YES forKey:kIsFacebook];
      }
      else if ([[dictSocial objectForKey:@"name"] isEqualToString:@"Twitter"])
      {
        [UserDefaults setBool:YES forKey:kIsTwitter];
      }
      else if ([[dictSocial objectForKey:@"name"] isEqualToString:@"Google"])
      {
        [UserDefaults setBool:YES forKey:kIsGoogle];
      }
      else if ([[dictSocial objectForKey:@"name"] isEqualToString:@"LinkedIn"])
      {
        [UserDefaults setBool:YES forKey:kIsLinkedIn];
      }
      
      [UserDefaults synchronize];
    }
    
  } failure:^(NSError *error) {
    
  }];
}


@end
