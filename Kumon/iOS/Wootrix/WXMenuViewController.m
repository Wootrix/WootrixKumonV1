//
//  WXMenuViewController.m
//  Wootrix
//
//  Created by Mayank Pahuja on 15/12/14.
//  Copyright (c) 2014 Techahead. All rights reserved.
//

#import "WXMenuViewController.h"
#import "WXMyAccountViewController.h"
#import "WXHomeViewController.h"
#import "WXInsertUserSelectedTabModel.h"
#import "WXDeleteMagazine.h"
#import <GooglePlus/GooglePlus.h>
#import <GoogleOpenSource/GoogleOpenSource.h>
//#import <SDWebImage/UIImageView+WebCache.h>

#define kMagazineButtonTagOffset 10000

@interface WXMenuViewController ()
{
    WXDeleteMagazine *deleteMagazines;
    int buttonId;
}

@end

@interface WXMenuViewController (MenuWebserviceMethods)

- (void)callGetTopicsWebService;
- (void)callAddMagazineWebServiceWithPassword:(NSString*)password;
- (void)callGetMagazinesWebService;
- (void)callLogoutService;
- (void)callInsertUserSelectedTabWebService;
- (void)callDeleteMagazinesWebService;

@end

@implementation WXMenuViewController

- (void)viewDidLoad
{
    [super viewDidLoad];
    // Do any additional setup after loading the view.
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (void)viewDidAppear:(BOOL)animated
{
    
    arrTempSavedLanguages = nil;
    arrTempSavedLanguages = [NSMutableArray arrayWithArray:[UserDefaults objectForKey:kArticleLanguages]];
    
    arrTempSavedTopics = nil;
    arrTempSavedTopics = [NSMutableArray arrayWithArray:[UserDefaults objectForKey:kSavedTopics]];
    
    
    [self setFontsAndColors];
}
#pragma mark - UITableView

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    if (tableView == _tblViewTopics)
    {
        return [arrTopics count];
    }
    return [arrLanguages count];
}

- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath
{
    return 30;
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    UITableViewCell *cell = nil;
    
    cell = (UITableViewCell *)[[[NSBundle mainBundle] loadNibNamed:@"CellMenu" owner:self options:nil] objectAtIndex:0];
    UILabel *lblTitle = (UILabel*)[cell.contentView viewWithTag:100];
    UIButton *btnCheck = (UIButton*)[cell.contentView viewWithTag:101];
    btnCheck.tag = indexPath.row+2000;
    
    [cell setSelectionStyle:UITableViewCellSelectionStyleNone];
    
    if (tableView == _tblViewTopics)
    {
        [lblTitle setText:[[arrTopics objectAtIndex:indexPath.row] objectForKey:@"topicTitle"]];
        //    if ([[[arrTopics objectAtIndex:indexPath.row] objectForKey:@"isSaved"] boolValue])
        //    {
        //      [btnCheck setSelected:YES];
        //    }
        if ([UserDefaults objectForKey:kSavedTopics])
        {
            if ([[UserDefaults objectForKey:kSavedTopics] containsObject:[[arrTopics objectAtIndex:indexPath.row] objectForKey:@"topicId"]])
            {
                [btnCheck setSelected:YES];
            }
        }
        [btnCheck addTarget:self action:@selector(btnTopicsTapped:withEvent:) forControlEvents:UIControlEventTouchUpInside];
    }
    else
    {
        [lblTitle setText:[arrLanguages objectAtIndex:indexPath.row]];
        [btnCheck addTarget:self action:@selector(btnLanguageTapped:) forControlEvents:UIControlEventTouchUpInside];
        if ([UserDefaults objectForKey:kArticleLanguages])
        {
            if ([[UserDefaults objectForKey:kArticleLanguages] containsObject:@"en"])
            {
                if (indexPath.row == 0)
                {
                    [btnCheck setSelected:YES];
                }
            }
            if ([[UserDefaults objectForKey:kArticleLanguages] containsObject:@"es"])
            {
                if (indexPath.row == 1)
                {
                    [btnCheck setSelected:YES];
                }
            }
            if ([[UserDefaults objectForKey:kArticleLanguages] containsObject:@"pt"])
            {
                if (indexPath.row == 2)
                {
                    [btnCheck setSelected:YES];
                }
            }
            /*NSString *currentLanguage = [[NSUserDefaults standardUserDefaults] objectForKey:kAppLanguage];
             if ([currentLanguage rangeOfString:@"en"].location != NSNotFound)
             {
             if (indexPath.row == 0)
             {
             [btnCheck setSelected:YES];
             }
             }
             if ([currentLanguage rangeOfString:@"es"].location != NSNotFound)
             {
             if (indexPath.row == 1)
             {
             [btnCheck setSelected:YES];
             }
             }
             if ([currentLanguage rangeOfString:@"pt"].location != NSNotFound)
             {
             if (indexPath.row == 2)
             {
             [btnCheck setSelected:YES];
             }
             }*/
        }
    }
    return cell;
}

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
    if (tableView == _tblViewTopics)
    {
        UITableViewCell *cell = [_tblViewTopics cellForRowAtIndexPath:indexPath];
        UIButton *sender = (UIButton*)[cell.contentView viewWithTag:2000+indexPath.row];
        
        [sender setSelected:![sender isSelected]];
        
        
        if (![UserDefaults objectForKey:kSavedTopics])
        {
            [UserDefaults setObject:[NSMutableArray array] forKey:kSavedTopics];
            [UserDefaults synchronize];
        }
        NSMutableArray *array = [[NSMutableArray alloc] initWithArray:[UserDefaults objectForKey:kSavedTopics]];
        if ([sender isSelected])
        {
            [array addObject:[[arrTopics objectAtIndex:indexPath.row] objectForKey:@"topicId"]];
        }
        else
        {
            [array removeObject:[[arrTopics objectAtIndex:indexPath.row] objectForKey:@"topicId"]];
        }
        [UserDefaults setObject:array forKey:kSavedTopics];
        [UserDefaults synchronize];
    }
    else
    {
        UITableViewCell *cell = [_tblViewLanguages cellForRowAtIndexPath:indexPath];
        UIButton *sender = (UIButton*)[cell.contentView viewWithTag:2000+indexPath.row];
        
        [sender setSelected:![sender isSelected]];
        if (![UserDefaults objectForKey:kArticleLanguages])
        {
            [UserDefaults setObject:[NSMutableArray array] forKey:kArticleLanguages];
            [UserDefaults synchronize];
        }
        NSMutableArray *array = [[NSMutableArray alloc] initWithArray:[UserDefaults objectForKey:kArticleLanguages]];
        switch (sender.tag - 2000)
        {
            case 0:
            {
                if ([sender isSelected])
                {
                    [array addObject:@"en"];
                }
                else
                {
                    [array removeObject:@"en"];
                }
            }
                break;
            case 1:
            {
                if ([sender isSelected])
                {
                    [array addObject:@"es"];
                }
                else
                {
                    [array removeObject:@"es"];
                }
            }
                break;
            case 2:
            {
                if ([sender isSelected])
                {
                    [array addObject:@"pt"];
                }
                else
                {
                    [array removeObject:@"pt"];
                }
            }
                break;
        }
        [UserDefaults setObject:array forKey:kArticleLanguages];
        [UserDefaults synchronize];
    }
}
#pragma mark - web services

/**
 *  This method calls the service to show availabe topics
 *
 *  @param dictGetTopics <#dictGetTopics description#>
 */
- (void)callServiceGetTopics:(NSDictionary*)dictGetTopics
{
    
}

/**
 *  This method calls the service to set the prefered topics as selected by user from the get topics list.
 *
 *  @param dictSetTopicPreference <#dictSetTopicPreference description#>
 */
- (void)callServiceSaveTopicPreference:(NSDictionary*)dictSetTopicPreference
{
    
}

/**
 *  Fetches all the subscibed magazines that have been  added by the user.
 *
 *  @param dictGetMagazines <#dictGetMagazines description#>
 */
- (void)callServiceGetMagazines:(NSDictionary*)dictGetMagazines
{
    
}

/**
 *  Add a new magazine to the subscribed magazine by entering in a valid password
 *
 *  @param dictSubscribeMagazine <#dictSubscribeMagazine description#>
 */
- (void)callServiceSubscribeMagazine:(NSDictionary*)dictSubscribeMagazine
{
    
}

/**
 *  Logs the user of if the application
 */
- (void)callServiceLogout
{
    
}

#pragma mark - other methods
/**
 *  This method is called to set the custom font sizes and colors of all objects present on the view
 */
- (void)setFontsAndColors
{
    
    arrLanguages = [[NSArray alloc] initWithObjects:[WXMenuViewController languageSelectedStringForKey:@"English"],[WXMenuViewController languageSelectedStringForKey:@"Spanish"],[WXMenuViewController languageSelectedStringForKey:@"Portuguese"], nil];
    
    
    [_lblNavBar setText:[WXMenuViewController languageSelectedStringForKey:@"Content"]];
    [_lblArticleLanguages setText:[WXMenuViewController languageSelectedStringForKey:@"Article Languages"]];
    [_lblMagazines setText:[WXMenuViewController languageSelectedStringForKey:@"Magazines"]];
    [_lblOpenArticles setText:[WXMenuViewController languageSelectedStringForKey:@"Open Articles"]];
    [_lblTopics setText:[WXMenuViewController languageSelectedStringForKey:@"Topics"]];
    
    [_btnCancel setTitle:[WXMenuViewController languageSelectedStringForKey:@"CANCEL"] forState:UIControlStateNormal];
    _btnCancel.width = 100;
    //  CGSize constraint = CGSizeMake(9999,44);
    //  CGRect frame = [[WXMenuViewController languageSelectedStringForKey:@"CANCEL"] boundingRectWithSize:constraint options:NSStringDrawingUsesFontLeading attributes:@{NSFontAttributeName:_btnCancel.titleLabel.font} context:nil];
    //  [_btnCancel setWidth:frame.size.width];
    
    [_btnDone setTitle:[WXMenuViewController languageSelectedStringForKey:@"DONE"] forState:UIControlStateNormal];
    [_btnLogout setTitle:[WXMenuViewController languageSelectedStringForKey:@"Log out"] forState:UIControlStateNormal];
    
    [_tblViewLanguages reloadData];
    
    [self makeMagazineView];
    [self callGetTopicsWebService];
    [self callGetMagazinesWebService];
}

/**
 *  This method is called after receiving data from webservices regarding the available magazines
 *
 *  @param magazineData contains all information about magazines that are available
 */
- (void)makeMagazineViewWithData:(id)magazineData
{
    
}

- (void)makeTopicsList:(id)topics
{
    
}
/**
 *  This method is called after receiving data from webservices regarding the available magazines
 */
- (void)makeMagazineView
{
    for (UIView *view in _scrlViewMagazines.subviews) {
        [view removeFromSuperview];
    }
    [_scrlViewMagazines setContentSize:CGSizeMake(0, _scrlViewMagazines.frame.size.height)];
    
    CGFloat heightMagazine = _scrlViewMagazines.frame.size.height - _scrlViewMagazines.frame.size.height/10;
    CGFloat widthMagazine = (_scrlViewMagazines.frame.size.width - _scrlViewMagazines.frame.size.width/5)/3;
    CGFloat xMargin = _scrlViewMagazines.frame.size.width/20;
    CGFloat yMargin = _scrlViewMagazines.frame.size.height/20;
    
    
    
//    UIButton *btnMagazine = [UIButton buttonWithType:UIButtonTypeCustom];
//    [btnMagazine setFrame:CGRectMake(0+ xMargin , yMargin, widthMagazine, heightMagazine)];
//
//    [btnMagazine setBackgroundImage:[UIImage imageNamed:@"AddNewMagazine"] forState:UIControlStateNormal];
//
//    [btnMagazine addTarget:self action:@selector(btnAddnewMagazineTapped:) forControlEvents:UIControlEventTouchUpInside];
//
//    [_scrlViewMagazines addSubview:btnMagazine];
//
//    UILabel *lblAddMagazine = [[UILabel alloc] initWithFrame:CGRectMake(5, 0, widthMagazine-10, 50)];
//    lblAddMagazine.center = CGPointMake(btnMagazine.center.x, btnMagazine.center.y-20);
//    [lblAddMagazine setFont:[UIFont systemFontOfSize:11]];
//    [lblAddMagazine setTextAlignment:NSTextAlignmentCenter];
//    [lblAddMagazine setTextColor:[UIColor grayColor]];
//    [lblAddMagazine setText:[WXMenuViewController languageSelectedStringForKey:@"Add New Magazines"]];
//    [lblAddMagazine setNumberOfLines:0];
//
//    [_scrlViewMagazines addSubview:lblAddMagazine];
//
//    UIImageView *imgViewAddIcon = [[UIImageView alloc] initWithImage:[UIImage imageNamed:@"iconAddNewMagazine"]];
//    [imgViewAddIcon setFrame:CGRectMake(0, lblAddMagazine.frame.origin.y+50, 20, 20)];
//    CGPoint center = imgViewAddIcon.center;
//    center.x = btnMagazine.center.x;
//    imgViewAddIcon.center = center;
//    [_scrlViewMagazines addSubview:imgViewAddIcon];
    
    
    
    for (int index = 0; index < arrMagazines.count; index++)
    {
        CGFloat heightMagazine = _scrlViewMagazines.frame.size.height - _scrlViewMagazines.frame.size.height/10;
        CGFloat widthMagazine = (_scrlViewMagazines.frame.size.width - _scrlViewMagazines.frame.size.width/5)/3;
        CGFloat xMargin = _scrlViewMagazines.frame.size.width/20;
        CGFloat yMargin = _scrlViewMagazines.frame.size.height/20;
        UIView *magazineView = [[UIView alloc] initWithFrame:CGRectMake((widthMagazine*index)+ xMargin*(index+1), 0, widthMagazine, heightMagazine)];
        
        UIButton *btnMagazine = [UIButton buttonWithType:UIButtonTypeCustom];
        //    [btnMagazine setBackgroundImage:[UIImage imageNamed:@"MagazineSettingMenu"] forState:UIControlStateNormal];
        [btnMagazine setTag:index + kMagazineButtonTagOffset];
        [btnMagazine.imageView setImageWithURL:[NSURL URLWithString:[[arrMagazines objectAtIndex:index/*-1*/] objectForKey:@"coverPhotoUrl"]]];
        
        [btnMagazine addTarget:self action:@selector(btnMagazineTapped:) forControlEvents:UIControlEventTouchUpInside];
        
        [btnMagazine setFrame:CGRectMake(0, yMargin, widthMagazine, heightMagazine)];
        
        [magazineView addSubview:btnMagazine];
        
        
        
        UIImageView *imgViewButtonImage = [[UIImageView alloc] init];
        imgViewButtonImage.frame = btnMagazine.frame;
        
        [imgViewButtonImage setImageWithURL:[NSURL URLWithString:[[arrMagazines objectAtIndex:index/*-1*/] objectForKey:@"coverPhotoUrl"]] placeholderImage:[UIImage imageNamed:@"PlaceHolderMagazine"]];
        [imgViewButtonImage setTag:index + kMagazineButtonTagOffset];
        [magazineView addSubview:imgViewButtonImage];
        
        [imgViewButtonImage setUserInteractionEnabled:NO];
        
        _scrlViewMagazines.contentSize = CGSizeMake((widthMagazine*index)+ xMargin*(index+1)+widthMagazine,_scrlViewMagazines.frame.size.height);
        UIButton *btnCloseMagazine = [UIButton buttonWithType:UIButtonTypeCustom];
        //    [btnMagazine setBackgroundImage:[UIImage imageNamed:@"MagazineSettingMenu"] forState:UIControlStateNormal];
        [btnCloseMagazine setTag:index + kMagazineButtonTagOffset];
        [btnCloseMagazine setBackgroundImage:[UIImage imageNamed:@"close"] forState:UIControlStateNormal];
        [btnCloseMagazine addTarget:self action:@selector(btnDeleteCloseArticlesTapped:) forControlEvents:UIControlEventTouchUpInside];
        
        [btnCloseMagazine setFrame:CGRectMake(widthMagazine - 20, yMargin, 20, 20)];
        
        [magazineView addSubview:btnCloseMagazine];
        magazineView.tag = index;
        [_scrlViewMagazines addSubview:magazineView];
        if(arrMagazines.count > 0){
                    }

    }
}
- (void)btnDeleteCloseArticlesTapped:(UIButton *)sender
{

     buttonId  = (int)sender.tag - kMagazineButtonTagOffset/*-1*/;
    
    UIAlertController * alert=   [UIAlertController
                                  alertControllerWithTitle:[WXMenuViewController languageSelectedStringForKey:@"Alert"]
                                  message:[WXMenuViewController languageSelectedStringForKey:@"Do you want to delete the magazine"]
                                  preferredStyle:UIAlertControllerStyleAlert];
    
    UIAlertAction* ok = [UIAlertAction
                         actionWithTitle:[WXMenuViewController languageSelectedStringForKey:@"Ok"]
                         style:UIAlertActionStyleDefault
                         handler:^(UIAlertAction * action)
                         {
                             for (int i=0;i<[arrMagazines count]; i++) {
                                 NSDictionary  *dict = [arrMagazines objectAtIndex:i];
                                 if (i == buttonId) {
                                     deleteMagazines = [[WXDeleteMagazine alloc] init];
                                     [deleteMagazines setToken        :[UserDefaults objectForKey:kToken]];
                                     [deleteMagazines setMagazine_id:[dict objectForKey:@"magazineId"]];
                                     [self callDeleteMagazinesWebService];
                                 }
                                 
                             }

                             
                         }];
    UIAlertAction* cancel = [UIAlertAction
                             actionWithTitle:[WXMenuViewController languageSelectedStringForKey:@"Cancel"]
                             style:UIAlertActionStyleDefault
                             handler:^(UIAlertAction * action)
                             {
                                 [alert dismissViewControllerAnimated:YES completion:nil];
                                 
                             }];
    
    [alert addAction:ok];
    [alert addAction:cancel];
    
    [self presentViewController:alert animated:YES completion:nil];
    
}

//- (void)callLoginWebserviceWithparams:(NSDictionary*)params
//{
//    
//    if(![[NetworkManager manager] isConnectedToWiFi])
//    {
//        [[[UIAlertView alloc] initWithTitle:kAPPName message:[WXMenuViewController languageSelectedStringForKey:@"Network Not Available"] delegate:nil cancelButtonTitle:[WXMenuViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles: nil] show];
//        return;
//    }
//    [self showProgressHUD:[WXMenuViewController languageSelectedStringForKey:@"Loading..."]];
////    WXLoginRequest *loginrequest= [[WXLoginRequest alloc] init];
////    [loginrequest setEmail              :[params objectForKey:@"email"]];
////    [loginrequest setPassword           :[params objectForKey:@"password"]];
////    [loginrequest setDevice             :[params objectForKey:@"device"]];
////    [loginrequest setOsVersion          :[params objectForKey:@"osversion"]];
////    [loginrequest setSocialAccountType  :[params objectForKey:@"socialaccounttype"]];
////    [loginrequest setSocialAccountId    :[params objectForKey:@"socialaccountid"]];
////    [loginrequest setSocialAccountToken :[params objectForKey:@"socialaccounttoken"]];
////    [loginrequest setAppLanguage        :[UserDefaults objectForKey:kAppLanguage]?[UserDefaults objectForKey:kAppLanguage]:@"en"];
////    [loginrequest setName               :[params objectForKey:@"name"]];
////    [loginrequest setPhotoUrl           :[params objectForKey:@"photourl"]];
////    [loginrequest setLatitude            :[params objectForKey:@"latitude"]];
////    [loginrequest setLongitude         :[params objectForKey:@"longitude"]];
////    [loginrequest setDeviceId        :[params objectForKey:@"deviceId"]];
//    [[NetworkManager manager] requestWithMethod:kHTTPMethodPOST1
//                                      URLString:@"deleteMagazine"
//                                     parameters:loginrequest
//                                         sucess:^(id response)
//     {
//         // [self dismissProgressHUD];
//         
//         NSLog(@"response:%@",response);
//         
//         if ([[response objectForKey:kSuccess] boolValue] == YES)
//         {
//             if ([[response objectForKey:@"data"] count] > 0)
//             {
//                 _loginModel = [[WXLoginModel alloc] initWithDictionary:[[response objectForKey:@"data"] objectAtIndex:0] error:nil];
//                 
//                 [UserDefaults setObject:_loginModel.token forKey:kToken];
//                 [UserDefaults setBool:YES forKey:kIsLoggedin];
//                 [UserDefaults setObject:[[[[response objectForKey:@"data"] firstObject] objectForKey:@"user"] objectForKey:@"photoUrl"] forKey:kUserImage];
//                 [UserDefaults setObject:[[[[response objectForKey:@"data"] firstObject] objectForKey:@"user"] objectForKey:@"name"] forKey:kUserName];
//                 [UserDefaults setObject:[[[[response objectForKey:@"data"] firstObject] objectForKey:@"user"] objectForKey:@"email"] forKey:kUserEmail];
//                 [UserDefaults setBool:[[[[response objectForKey:@"data"] firstObject] objectForKey:kRequirePassword] boolValue] forKey:kRequirePassword];
//                 [UserDefaults setBool:[[[[response objectForKey:@"data"] firstObject] objectForKey:kRequireEmail] boolValue] forKey:kRequireEmail];
//                 
//                 [UserDefaults setBool:[[[[response objectForKey:@"data"] firstObject] objectForKey:kIsEmail] boolValue] forKey:kIsEmail];
//                 [UserDefaults setBool:[[[[response objectForKey:@"data"] firstObject] objectForKey:kIsFacebook] boolValue] forKey:kIsFacebook];
//                 [UserDefaults setBool:[[[[response objectForKey:@"data"] firstObject] objectForKey:kIsTwitter] boolValue] forKey:kIsTwitter];
//                 [UserDefaults setBool:[[[[response objectForKey:@"data"] firstObject] objectForKey:kIsLinkedIn] boolValue] forKey:kIsLinkedIn];
//                 [UserDefaults setBool:[[[[response objectForKey:@"data"] firstObject] objectForKey:kIsGoogle] boolValue] forKey:kIsGoogle];
//                 
//                 
//                 [UserDefaults synchronize];
//                 
//                 
//                 
//                 //  [self callLoginWebServiceToLoadFcmToken:params];
//                 [self callGetUserSelectedWebserviceWithparams];
//                 //           getUserSelectedTab
//                 
//             }
//         }
//         else
//         {
//             [[[UIAlertView alloc] initWithTitle:kAPPName message:[response objectForKey:kMessage] delegate:nil cancelButtonTitle:[WXLoginViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles: nil] show];
//         }
//         
//         
//     }failure:^(NSError *error)
//     {
//         NSLog(@"error:%@",error);
//         [self dismissProgressHUD];
//     }];
//    //  }
//}


- (void) deleteMagazine: (int) buttonId1
{
    for (int i=0;i<[arrMagazines count]; i++) {
        NSString *item = [arrMagazines objectAtIndex:i];
        if (i == buttonId1) {
            NSMutableArray *magazine = [[NSMutableArray alloc]initWithArray:arrMagazines];
            [magazine removeObject:item];
            arrMagazines = [magazine mutableCopy];
            for ( UIView *view in _scrlViewMagazines.subviews) {
                
                int viewRemove = (int)view.tag;
                if (viewRemove  == buttonId1) {
                    [view removeFromSuperview];
                    [self makeMagazineView];
                    [self.scrlViewMagazines setNeedsDisplay];
                }
            }
        }
        
    }
    
}

#pragma mark - button action


/**
 *  Called on tapping the + button. This presents a field to enter the code to add a new magazine
 *
 *  @param sender object of UIButton
 */
- (void)btnAddnewMagazineTapped:(UIButton*)sender
{
    UIAlertView *alertView = [[UIAlertView alloc] initWithTitle:kAPPName message:[WXMenuViewController languageSelectedStringForKey:@"Enter Subscription Password"] delegate:self cancelButtonTitle:[WXMenuViewController languageSelectedStringForKey:@"Cancel"] otherButtonTitles:[WXMenuViewController languageSelectedStringForKey:@"Ok"],nil];
    [alertView setAlertViewStyle:UIAlertViewStylePlainTextInput];
    alertView.tag = 101;
    UITextField *textField = [alertView textFieldAtIndex:0];
    [textField setKeyboardType:UIKeyboardTypeEmailAddress];
    [textField setPlaceholder:[WXMenuViewController languageSelectedStringForKey:@"Password"]];
    textField.keyboardType = UIKeyboardTypeEmailAddress;
    [alertView show];
}
/**
 *  Called on tapping the cancel button
 *
 *  @param sender object of UIButton
 */
- (IBAction)btnCancelTapped:(UIButton*)sender
{
    
    [UserDefaults setObject:arrTempSavedTopics forKey:kSavedTopics];
    [UserDefaults setObject:arrTempSavedLanguages forKey:kArticleLanguages];
    [UserDefaults synchronize];
    if (isDeviceIPad)
    {
        [_delegate cancelTapped];
    }
    else
    {
        [self.navigationController popViewControllerAnimated:YES];
    }
    
}

/**
 *  Called on tapping the Done button
 *
 *  @param sender object of UIButton
 */
- (IBAction)btnDoneTapped:(UIButton*)sender
{
    [self callInsertUserSelectedTabWebService];
    
    if ([[UserDefaults objectForKey:kArticleLanguages] count] == 0 || ![UserDefaults objectForKey:kArticleLanguages])
    {
        [[[UIAlertView alloc] initWithTitle:kAPPName message:[WXMenuViewController languageSelectedStringForKey:@"Please choose at least one language"] delegate:nil cancelButtonTitle:[WXMenuViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles:nil] show];
    }
    else
    {
        if (isDeviceIPad)
        {
            [_delegate doneTapped];
        }
        else
        {
            [self.navigationController popViewControllerAnimated:YES];
        }
    }
    
}

/**
 *  Called on tapping the Logout Button
 *
 *  @param sender object of UIButton
 */
- (IBAction)btnLogoutTapped:(UIButton*)sender
{
    [UserDefaults setBool:NO forKey:kIsLoggedin];
    [UserDefaults synchronize];
    [[GPPSignIn sharedInstance] signOut];
    [[GPPSignIn sharedInstance] disconnect];
    if (isDeviceIPad)
    {
        //    WXHomeViewController *homeViewC = (WXHomeViewController*)self.parentViewController.parentViewController;
        
        //    [[homeViewC popOverViewC] dismissPopoverAnimated:NO];
        [self.delegate logoutAction];
    }
    else
    {
        [self.navigationController popToRootViewControllerAnimated:YES];
    }
}

- (IBAction)btnSettingsTapped:(UIButton *)sender
{
    if (isDeviceIPad)
    {
        WXMyAccountViewController *myAccountViewC = [[WXMyAccountViewController alloc] initWithNibName:@"WXMyAccountViewIPad" bundle:nil];
        [self.navigationController pushViewController: myAccountViewC animated:YES];
    }
    else
    {
        WXMyAccountViewController *myAccountViewC = [self.storyboard instantiateViewControllerWithIdentifier:@"WXMyAccountViewController"];
        [self.navigationController pushViewController:myAccountViewC animated:YES];
    }
}

/**
 *  Called on tapping a magazine from list of available magazines
 */

- (void)magazineTapped
{
    
}
/**
 *  <#Description#>
 */
- (void)btnTopicsTapped:(UIButton*)sender withEvent:(id)event

{
    NSSet *touches = [event allTouches];
    UITouch *touch = [touches anyObject];
    CGPoint currentTouchPosition = [touch locationInView:_tblViewTopics];
    NSIndexPath *indexPath = [_tblViewTopics indexPathForRowAtPoint: currentTouchPosition];
    [sender setSelected:![sender isSelected]];
    
    
    if (![UserDefaults objectForKey:kSavedTopics])
    {
        [UserDefaults setObject:[NSMutableArray array] forKey:kSavedTopics];
        [UserDefaults synchronize];
    }
    NSMutableArray *array = [[NSMutableArray alloc] initWithArray:[UserDefaults objectForKey:kSavedTopics]];
    if ([sender isSelected])
    {
        [array addObject:[[arrTopics objectAtIndex:indexPath.row] objectForKey:@"topicId"]];
    }
    else
    {
        [array removeObject:[[arrTopics objectAtIndex:indexPath.row] objectForKey:@"topicId"]];
    }
    [UserDefaults setObject:array forKey:kSavedTopics];
    [UserDefaults synchronize];
    //  {
    //    if (![arrSavedTopics containsObject:[[arrTopics objectAtIndex:indexPath.row] objectForKey:@"topicId"]])
    //    {
    //
    //      NSString *strTopics = @"";
    //
    //      if (![UserDefaults objectForKey:kSavedTopics])
    //      {
    //        strTopics = [NSString stringWithFormat:@"%@",[[arrTopics objectAtIndex:indexPath.row] objectForKey:@"topicId"]];
    //      }
    //      else
    //      {
    //        strTopics = [NSString stringWithFormat:@"%@,%@",[UserDefaults objectForKey:kSavedTopics],[[arrTopics objectAtIndex:indexPath.row] objectForKey:@"topicId"]];
    //      }
    //      [UserDefaults setObject:strTopics forKey:kSavedTopics];
    //      [UserDefaults synchronize];
    //
    //      arrSavedTopics = [[[UserDefaults objectForKey:kSavedTopics] componentsSeparatedByString:@","] mutableCopy];
    //
    //    }
    //  }
    //  else
    //  {
    //    for (int index = 0; index < arrSavedTopics.count; index++)
    //    {
    //      if ([[arrSavedTopics objectAtIndex:index] isEqualToString:[[arrTopics objectAtIndex:indexPath.row] objectForKey:@"topicId"]])
    //      {
    //        [arrSavedTopics removeObjectAtIndex:index];
    //      }
    //    }
    //  }
    //
    //  for (int index = 0; index <arrTopics.count; index++)
    //  {
    //    NSDictionary *tempDict = [arrTopics objectAtIndex:index];
    //    for (int i = 0; i<arrSavedTopics.count; i++)
    //    {
    //      if ([[tempDict objectForKey:@"topicId"] isEqualToString:[arrSavedTopics objectAtIndex:i]])
    //      {
    //        NSDictionary *newDict = @{@"topicId":[tempDict objectForKey:@"topicId"],
    //                                  @"topicTitle":[tempDict objectForKey:@"topicTitle"],
    //                                  @"isSaved":@"1"};
    //        [arrTopics replaceObjectAtIndex:index withObject:newDict];
    //      }
    //    }
    //
    //  }
    
    
}

/**
 *
 */
- (void)btnLanguageTapped:(UIButton*)sender
{
    [sender setSelected:![sender isSelected]];
    if (![UserDefaults objectForKey:kArticleLanguages])
    {
        [UserDefaults setObject:[NSMutableArray array] forKey:kArticleLanguages];
        [UserDefaults synchronize];
    }
    NSMutableArray *array = [[NSMutableArray alloc] initWithArray:[UserDefaults objectForKey:kArticleLanguages]];
    switch (sender.tag - 2000)
    {
        case 0:
        {
            if ([sender isSelected])
            {
                [array addObject:@"en"];
            }
            else
            {
                [array removeObject:@"en"];
            }
        }
            break;
        case 1:
        {
            if ([sender isSelected])
            {
                [array addObject:@"es"];
            }
            else
            {
                [array removeObject:@"es"];
            }
        }
            break;
        case 2:
        {
            if ([sender isSelected])
            {
                [array addObject:@"pt"];
            }
            else
            {
                [array removeObject:@"pt"];
            }
        }
            break;
    }
    [UserDefaults setObject:array forKey:kArticleLanguages];
    [UserDefaults synchronize];
    NSLog(@"arr:%@",array);
}
/**
 *
 *
 *  @param sender
 */
- (void)btnMagazineTapped:(UIButton*)sender
{
    NSInteger index = sender.tag - kMagazineButtonTagOffset/* - 1*/;
    if (isDeviceIPad)
    {
        [_delegate magazineTapped:[arrMagazines objectAtIndex:index]];
    }
    else
    {
        NSInteger flag = 0;
        for (UIViewController *viewC in self.navigationController.viewControllers)
        {
            if ([viewC isKindOfClass:[WXHomeViewController class]])
            {
                flag = 1;
                WXHomeViewController *homeViewC = (WXHomeViewController*)viewC;
                [homeViewC setIsMagazine:YES];
                [homeViewC setDictMagazine:[arrMagazines objectAtIndex:index]];
                [UserDefaults setObject:[[arrMagazines objectAtIndex:index] objectForKey:@"mobileAppBarColorRGB"] forKey:kMagazineColor];
                [UserDefaults synchronize];
                homeViewC.isMagazinePhotoShown = YES;
                [self.navigationController popToViewController:homeViewC animated:YES];
            }
        }
        if (flag == 0)
        {
            WXHomeViewController *homeViewController = [self.storyboard instantiateViewControllerWithIdentifier:@"WXHomeViewController"];
            [homeViewController setIsMagazine:YES];
            [homeViewController setDictMagazine:[arrMagazines objectAtIndex:index]];
            [UserDefaults setObject:[[arrMagazines objectAtIndex:index] objectForKey:@"mobileAppBarColorRGB"] forKey:kMagazineColor];
            [UserDefaults synchronize];
            homeViewController.isMagazinePhotoShown = YES;
            [self.navigationController pushViewController:homeViewController animated:YES];
        }
    }
}
- (IBAction)btnOpenArticlesTapped:(UIButton *)sender
{
    if (isDeviceIPad)
    {
        [_delegate openArticleTapped];
    }
    else
    {
        NSInteger flag = 0;
        for (UIViewController *viewC in self.navigationController.viewControllers)
        {
            if ([viewC isKindOfClass:[WXHomeViewController class]])
            {
                flag = 1;
                WXHomeViewController *homeViewC = (WXHomeViewController*)viewC;
                [homeViewC setIsMagazine:NO];
                [self.navigationController popToViewController:homeViewC animated:YES];
            }
        }
        if (flag == 0)
        {
            WXHomeViewController *homeViewController = [self.storyboard instantiateViewControllerWithIdentifier:@"WXHomeViewController"];
            [homeViewController setIsMagazine:NO];
            [self.navigationController pushViewController:homeViewController animated:YES];
        }
    }
    
}



#pragma mark - alertview delegates

- (void)alertView:(UIAlertView *)alertView didDismissWithButtonIndex:(NSInteger)buttonIndex
{
    UITextField *textField = [alertView textFieldAtIndex:0];
    if (buttonIndex == 1)
    {
        [self callAddMagazineWebServiceWithPassword:textField.text];
    }
}

@end

@implementation WXMenuViewController (MenuWebserviceMethods)

- (void)callGetTopicsWebService
{
    if(![[NetworkManager manager] isConnectedToWiFi])
    {
        [[[UIAlertView alloc] initWithTitle:kAPPName message:[WXMenuViewController languageSelectedStringForKey:@"Network Not Available"] delegate:nil cancelButtonTitle:[WXMenuViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles: nil] show];
        return;
    }
    [self showProgressHUD: [WXMenuViewController languageSelectedStringForKey:@"Loading..."]];
     [self.view setUserInteractionEnabled:NO];
   
    WXGetTopicsModel *getTopics = [[WXGetTopicsModel alloc] init];
    //  [getTopics setLanguage  :@"en"];
    [getTopics setToken     :[UserDefaults objectForKey:kToken]];
    [getTopics setAppLanguage:[UserDefaults objectForKey:kAppLanguage]?[UserDefaults objectForKey:kAppLanguage]:@"en"];
    
    [[NetworkManager manager] requestWithMethod:kHTTPMethodPOST1
                                      URLString:@"getTopics"
                                     parameters:getTopics
                                         sucess:^(id response)
     {
         NSLog(@"res:%@",response);
         if ([[response objectForKey:kSuccess] boolValue] == YES)
         {
             
             arrTopics = nil;
             arrTopics = [[NSMutableArray alloc] initWithArray:[[[response objectForKey:kData] firstObject] objectForKey:@"topics"]];
             //       for (int index = 0; index <arrTopics.count; index++)
             //       {
             //         NSDictionary *tempDict = [arrTopics objectAtIndex:index];
             //         for (int i = 0; i<arrSavedTopics.count; i++)
             //         {
             //           if ([[tempDict objectForKey:@"topicId"] isEqualToString:[arrSavedTopics objectAtIndex:i]])
             //           {
             //             NSDictionary *newDict = @{@"topicId":[tempDict objectForKey:@"topicId"],
             //                                       @"topicTitle":[tempDict objectForKey:@"topicTitle"],
             //                                       @"isSaved":@"1"};
             //             [arrTopics replaceObjectAtIndex:index withObject:newDict];
             //           }
             //         }
             //
             //       }
             [_tblViewTopics reloadData];
         }
         else
         {
             [[[UIAlertView alloc] initWithTitle:kAPPName message:[response objectForKey:kMessage] delegate:nil cancelButtonTitle:[WXMenuViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles:nil] show];
         }
         [self dismissProgressHUD];
          [self.view setUserInteractionEnabled:YES];
         
     }failure:^(NSError *error)
     {
         [self dismissProgressHUD];
          [self.view setUserInteractionEnabled:YES];
     }];
    
}

- (void)callAddMagazineWebServiceWithPassword:(NSString*)password
{
    if(![[NetworkManager manager] isConnectedToWiFi])
    {
        [[[UIAlertView alloc] initWithTitle:kAPPName message:[WXMenuViewController languageSelectedStringForKey:@"Network Not Available"] delegate:nil cancelButtonTitle:[WXMenuViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles: nil] show];
        return;
    }
    [self showProgressHUD:[WXChangePasswordViewController languageSelectedStringForKey:@"Subscribing Magazine..."]];
    WXSubscribeMagazineModel *subscribeMagazine = [[WXSubscribeMagazineModel alloc] init];
    [subscribeMagazine setSubscriptionPassword:password];
    [subscribeMagazine setToken     :[UserDefaults objectForKey:kToken]];
    [subscribeMagazine setAppLanguage:[UserDefaults objectForKey:kAppLanguage]?[UserDefaults objectForKey:kAppLanguage]:@"en"];
    
    [[NetworkManager manager] requestWithMethod:kHTTPMethodPOST1
                                      URLString:@"subscribeMagazine"
                                     parameters:subscribeMagazine
                                         sucess:^(id response)
     {
         NSLog(@"res:%@",response);
         if ([[response objectForKey:kSuccess] boolValue] == YES)
         {
             UIAlertController * alertcontroller = [UIAlertController alertControllerWithTitle:kAPPName message:[WXChangePasswordViewController languageSelectedStringForKey:@"Magazine subscribed successfully."]  preferredStyle:UIAlertControllerStyleAlert];
             UIAlertAction * ok = [UIAlertAction actionWithTitle:[WXMenuViewController languageSelectedStringForKey:@"Ok"] style:UIAlertActionStyleDefault handler:nil];
             [alertcontroller addAction:ok];
             [self presentViewController:alertcontroller animated:NO completion:nil];
//             [[[UIAlertView alloc] initWithTitle:kAPPName message:[WXChangePasswordViewController languageSelectedStringForKey:@"Magazine subscribed successfully."] delegate:nil cancelButtonTitle:[WXMenuViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles:nil] show];
             [self callGetMagazinesWebService];
         }
         else
         {
             UIAlertController * alertcontroller = [UIAlertController alertControllerWithTitle:kAPPName message:[NSString stringWithFormat:@"%@",[response objectForKey:kMessage]]  preferredStyle:UIAlertControllerStyleAlert];
             UIAlertAction * ok = [UIAlertAction actionWithTitle:[WXMenuViewController languageSelectedStringForKey:@"Ok"] style:UIAlertActionStyleDefault handler:nil];
             [alertcontroller addAction:ok];
             [self presentViewController:alertcontroller animated:NO completion:nil];
//             [[[UIAlertView alloc] initWithTitle:kAPPName message:[NSString stringWithFormat:@"%@",[response objectForKey:kMessage]] delegate:nil cancelButtonTitle:[WXMenuViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles:nil] show];
         }
         [self dismissProgressHUD];
          [self.view setUserInteractionEnabled:YES];
         
     }failure:^(NSError *error)
     {
         [self dismissProgressHUD];
          [self.view setUserInteractionEnabled:YES];
     }];
    
}

- (void)callGetMagazinesWebService
{
    
    //  [self showProgressHUD:@"Fetching Magazines"];
     [self showProgressHUD:[WXChangePasswordViewController languageSelectedStringForKey:@"Loading..."]];
     [self.view setUserInteractionEnabled:NO];
    WXGetMagazinesModel *getMagazines = [[WXGetMagazinesModel alloc] init];
    [getMagazines setLanguage     :@"en"];
    [getMagazines setToken        :[UserDefaults objectForKey:kToken]];
    [getMagazines setAppLanguage  :[UserDefaults objectForKey:kAppLanguage]?[UserDefaults objectForKey:kAppLanguage]:@"en"];
    
    [[NetworkManager manager] requestWithMethod:kHTTPMethodPOST1
                                      URLString:@"getMagazines"
                                     parameters:getMagazines
                                         sucess:^(id response)
     {
         NSLog(@"res:%@",response);
         if ([[response objectForKey:kSuccess] boolValue] == YES)
         {
             arrMagazines = nil;
             arrMagazines = [[NSArray alloc] initWithArray:[[[response objectForKey:kData] firstObject] objectForKey:@"magazines"]];
             [[NSUserDefaults standardUserDefaults] removeObjectForKey:@"listOfCloseMagazines"];
             NSLog(@"Data in listOfCloseMagazines %@",[[NSUserDefaults standardUserDefaults]objectForKey:@"listOfCloseMagazines"]);
             [[NSUserDefaults standardUserDefaults]setObject:arrMagazines forKey:@"listOfCloseMagazines"];
             NSLog(@"Data in listOfCloseMagazines after adding arrmagazines %@",[[NSUserDefaults standardUserDefaults]objectForKey:@"listOfCloseMagazines"]);
             [[NSUserDefaults standardUserDefaults]synchronize];

             [self makeMagazineView];
         }
         else
         {
             UIAlertController * alertcontroller = [UIAlertController alertControllerWithTitle:kAPPName message:[NSString stringWithFormat:@"%@",[response objectForKey:kMessage]]  preferredStyle:UIAlertControllerStyleAlert];
             UIAlertAction * ok = [UIAlertAction actionWithTitle:[WXMenuViewController languageSelectedStringForKey:@"Ok"] style:UIAlertActionStyleDefault handler:nil];
             [alertcontroller addAction:ok];
             [self presentViewController:alertcontroller animated:NO completion:nil];

//             [[[UIAlertView alloc] initWithTitle:kAPPName message:[response objectForKey:kMessage] delegate:nil cancelButtonTitle:[WXMenuViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles:nil] show];
         }
         [self dismissProgressHUD];
          [self.view setUserInteractionEnabled:YES];
         
     }failure:^(NSError *error)
     {
         [self dismissProgressHUD];
          [self.view setUserInteractionEnabled:YES];
     }];
    
}

- (void)callInsertUserSelectedTabWebService
{
    
    NSString *articleLanguageNames = [NSString stringWithFormat:@"%@",[self getArticleLanguageName:[UserDefaults objectForKey:kAppLanguage]?[UserDefaults objectForKey:kAppLanguage]:@"en"]];
    //  if ([UserDefaults objectForKey:kArticleLanguages])
    //  {
    //    for (int i = 0; i<[[UserDefaults objectForKey:kArticleLanguages] count]; i++)
    //    {
    //      if (i == 0)
    //      {
    //        articleLanguageNames = [NSString stringWithFormat:@"%@",[self getArticleLanguageName:[[UserDefaults objectForKey:kArticleLanguages] firstObject]]];
    //      }
    //      else
    //      {
    //        articleLanguageNames = [NSString stringWithFormat:@"%@,%@",articleLanguageNames,[self getArticleLanguageName:[[UserDefaults objectForKey:kArticleLanguages] objectAtIndex:i]]];
    //      }
    //    }
    //  }
    
    NSString *articleLanguages = @"";
    if ([UserDefaults objectForKey:kArticleLanguages])
    {
        for (int i = 0; i<[[UserDefaults objectForKey:kArticleLanguages] count]; i++)
        {
            if (i == 0)
            {
                articleLanguages = [NSString stringWithFormat:@"%@",[self getArticleLanguageId:[[UserDefaults objectForKey:kArticleLanguages] firstObject]]];
            }
            else
            {
                articleLanguages = [NSString stringWithFormat:@"%@,%@",articleLanguages,[self getArticleLanguageId:[[UserDefaults objectForKey:kArticleLanguages] objectAtIndex:i]]];
            }
        }
    }
    
    NSString *savedTopics = @"";
    if ([UserDefaults objectForKey:kSavedTopics])
    {
        for (int i = 0; i<[[UserDefaults objectForKey:kSavedTopics] count]; i++)
        {
            if (i == 0)
            {
                savedTopics = [NSString stringWithFormat:@"%@",[[UserDefaults objectForKey:kSavedTopics] firstObject]];
            }
            else
            {
                savedTopics = [NSString stringWithFormat:@"%@|%@",savedTopics,[[UserDefaults objectForKey:kSavedTopics] objectAtIndex:i]];
            }
        }
    }
    
    //  [self showProgressHUD:@"Fetching Magazines"];
    WXInsertUserSelectedTabModel *userSelected = [[WXInsertUserSelectedTabModel alloc] init];
    [userSelected setCategory           :savedTopics];
    [userSelected setUser_id            :[UserDefaults objectForKey:kToken]];
    [userSelected setWeb_language       :articleLanguageNames];
    [userSelected setArticle_language   :articleLanguages];
    
    [[NetworkManager manager] requestWithMethod:kHTTPMethodPOST1
                                      URLString:@"insertUserSelectedTab"
                                     parameters:userSelected
                                         sucess:^(id response)
     {
         NSLog(@"res:%@",response);
         if ([[response objectForKey:kSuccess] boolValue] == YES)
         {
             
         }
         else
         {
             UIAlertController *alertController = [UIAlertController alertControllerWithTitle:kAPPName message:[response objectForKey:kMessage] preferredStyle:UIAlertControllerStyleAlert];
             UIAlertAction *ok = [UIAlertAction actionWithTitle:[WXMenuViewController languageSelectedStringForKey:@"Ok"] style:UIAlertActionStyleDefault handler:nil];
             [alertController addAction:ok];
             [self presentViewController:alertController animated:NO completion:nil];
         }
         [self dismissProgressHUD];
          [self.view setUserInteractionEnabled:YES];
         
     }failure:^(NSError *error)
     {
         [self dismissProgressHUD];
          [self.view setUserInteractionEnabled:YES];
     }];
    
}

- (void)callLogoutService
{
    
}

- (NSString*)getArticleLanguageId:(NSString*)languageCode
{
    if ([languageCode isEqualToString:@"en"])
    {
        return @"1";
    }
    else if ([languageCode isEqualToString:@"es"])
    {
        return @"3";
    }
    return @"2";
}

- (NSString*)getArticleLanguageName:(NSString*)languageCode
{
    if ([languageCode isEqualToString:@"en"])
    {
        return @"english";
    }
    else if ([languageCode isEqualToString:@"es"])
    {
        return @"spanish";
    }
    return @"portuguese";
}

- (void)callDeleteMagazinesWebService
{
    if(![[NetworkManager manager] isConnectedToWiFi])
    {
        [[[UIAlertView alloc] initWithTitle:kAPPName message:[WXMenuViewController languageSelectedStringForKey:@"Network Not Available"] delegate:nil cancelButtonTitle:[WXMenuViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles: nil] show];
        return;
    }
    [self showProgressHUD:@"Deleting Magazine"];
    
    [[NetworkManager manager] requestWithMethod:kHTTPMethodPOST1
                                      URLString:@"magzinedelete"
                                     parameters:deleteMagazines
                                         sucess:^(id response)
     {
         NSLog(@"res:%@",response);
         if ([[response objectForKey:kSuccess] boolValue] == YES)
         {
             [self deleteMagazine:buttonId];
              [[[UIAlertView alloc] initWithTitle:[WXMenuViewController languageSelectedStringForKey:@"Alert"] message:[WXMenuViewController languageSelectedStringForKey:@"Magazine Deleted Successfully"]delegate:nil cancelButtonTitle:[WXMenuViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles:nil] show];
         }
         else
         {
             [[[UIAlertView alloc] initWithTitle:kAPPName message:[response objectForKey:kMessage] delegate:nil cancelButtonTitle:[WXMenuViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles:nil] show];
         }
         [self dismissProgressHUD];
          [self.view setUserInteractionEnabled:YES];
         
     }failure:^(NSError *error)
     {
         [self dismissProgressHUD];
          [self.view setUserInteractionEnabled:YES];
     }];
    
}

@end
