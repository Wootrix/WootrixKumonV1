//
//  WXMyAccountViewController.m
//  Wootrix
//
//  Created by Mayank Pahuja on 15/12/14.
//  Copyright (c) 2014 Techahead. All rights reserved.
//

#import "WXMyAccountViewController.h"
#import "WXAboutUsViewController.h"
#import "WXChangePasswordViewController.h"
#import "WXConnectSocialViewController.h"

@interface WXMyAccountViewController ()

@end

@implementation WXMyAccountViewController


#pragma mark - view life cycle
- (void)viewDidLoad
{
  
  [super viewDidLoad];
  
  // Do any additional setup after loading the view.
}

- (void)didReceiveMemoryWarning {
  [super didReceiveMemoryWarning];
  // Dispose of any resources that can be recreated.
}

- (void)viewDidAppear:(BOOL)animated
{
  [self setFontsAndColors];
}
#pragma mark - other methods


/**
 *  This method is called to set the custom font sizes and colors of all objects present on the view
 */
- (void)setFontsAndColors
{
  if (isUploadingImage)
  {
    isUploadingImage = NO;
  }
  else
  {
    [_imgViewCover setImageWithURL:[NSURL URLWithString:[NSString stringWithFormat:@"%@",[UserDefaults objectForKey:kUserImage]]] placeholderImage:[UIImage imageNamed:@"ArticleLayoutPlaceholder"]];
    
    [_imgViewProfile setImageWithURL:[NSURL URLWithString:[NSString stringWithFormat:@"%@",[UserDefaults objectForKey:kUserImage]]] placeholderImage:[UIImage imageNamed:@"ProfileImagePlaceholder"]];

  }
  [_imgViewProfile.layer setCornerRadius:_imgViewProfile.frame.size.width/2];
  [_imgViewProfile.layer setBorderColor:[UIColor whiteColor].CGColor];
  [_imgViewProfile.layer setBorderWidth:2.0f];
  
  [_lblUsername setText:[UserDefaults objectForKey:kUserName]];
  [_lblUseremail setText:[UserDefaults objectForKey:kUserEmail]];
  
  [_lblNavBar setText:[WXMyAccountViewController languageSelectedStringForKey:@"My Account"]];
  
  
  if ([UserDefaults boolForKey:kRequireEmail])
  {
    arrayMyAccount = nil;
    arrayMyAccount = [NSArray arrayWithObjects:[WXMyAccountViewController languageSelectedStringForKey:@"Show Page"],
                      //[WXMyAccountViewController languageSelectedStringForKey:@"Add Account"],
                      [WXMyAccountViewController languageSelectedStringForKey:@"Change Language"],
                      /*[WXMyAccountViewController languageSelectedStringForKey:@"Change Password"],
                      [WXMyAccountViewController languageSelectedStringForKey:@"Rate App"],
                      [WXMyAccountViewController languageSelectedStringForKey:@"About Us"],
                      [WXMyAccountViewController languageSelectedStringForKey:@"Add Email"],*/ nil];
  }
  else
  {
    arrayMyAccount = nil;
    arrayMyAccount = [NSArray arrayWithObjects:[WXMyAccountViewController languageSelectedStringForKey:@"Show Page"],
                      //[WXMyAccountViewController languageSelectedStringForKey:@"Add Account"],
                      [WXMyAccountViewController languageSelectedStringForKey:@"Change Language"],
                      /*[WXMyAccountViewController languageSelectedStringForKey:@"Change Password"],
                      [WXMyAccountViewController languageSelectedStringForKey:@"Rate App"],
                      [WXMyAccountViewController languageSelectedStringForKey:@"About Us"],*/ nil];
  }
  
  [_tblViewMyAccount reloadData];
  
}

/**
 *  This method is called after receiving data from webservices regarding the available magazines
 *
 *  @param magazineData contains all information about magazines that are subscribed by the user
 */
- (void)makeMagazineViewWithData:(id)magazineData
{
  
}

/**
 *  Called after getting response from profile web service to show that information on screen
 */
- (void)setProfileData:(id)profileData
{
  
}

#pragma mark - web services

/**
 *  Calls the service to fetch the account details of user.
 *
 *  @param dictGetAccountDetails <#dictGetAccountDetails description#>
 */
- (void)callServiceGetAccountDetails:(NSDictionary*)dictGetAccountDetails
{
  
}
#pragma mark - UITableView

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
  return arrayMyAccount.count;
}

- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath
{
  return 60;
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
  UITableViewCell *cell = nil;
  
  cell = (UITableViewCell *)[[[NSBundle mainBundle] loadNibNamed:@"CellMyAccount" owner:self options:nil] objectAtIndex:0];
  
  UILabel *lblTitle = (UILabel*)[cell.contentView viewWithTag:60];
  UISwitch *switchShowPage = (UISwitch*)[cell.contentView viewWithTag:61];
  [switchShowPage addTarget:self action:@selector(btnSwitchShowPage:) forControlEvents:UIControlEventValueChanged];
  
  UIImageView *imgViewArrow = (UIImageView*)[cell.contentView viewWithTag:62];
  
  [lblTitle setText:[arrayMyAccount objectAtIndex:indexPath.row]];
  
  switch (indexPath.row)
  {
      
    case 0:

      [switchShowPage setHidden:NO];
      [imgViewArrow setHidden:YES];
      if ([UserDefaults boolForKey:kHidePage] == YES)
      {
        [switchShowPage setOn:NO];
      }
      else
      {
        [switchShowPage setOn:YES];
      }
      break;
     
    case 3:
    {
      if ([UserDefaults boolForKey:kRequirePassword])
      {
        [lblTitle setText:[WXMyAccountViewController languageSelectedStringForKey:@"Add Password"]];
      }
      else
      {
        [lblTitle setText:[WXMyAccountViewController languageSelectedStringForKey:@"Change Password"]];
      }
      
    }
      break;
      
    case 5:
      [imgViewArrow setHidden:YES];
      break;
    default:
      break;
  }
  
  return cell;
}

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
  switch (indexPath.row)
  {
//    case 1:
//    {
//      NSLog(@"Add Account");
//
////      UIStoryboard *storyBoard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
//      AppDelegate *appDelegate = (AppDelegate *)[[UIApplication sharedApplication] delegate];
//      WXConnectSocialViewController *addAccount = [appDelegate.storyBoard instantiateViewControllerWithIdentifier:@"WXConnectSocialViewController"];
//      [self.navigationController pushViewController:addAccount animated:YES];
//    }
//      break;
    case 1:
      if (isDeviceIPad)
      {
        WXChangeLanguageViewController *changeLanguage = [[WXChangeLanguageViewController alloc] initWithNibName:@"WXChangeLanguageIpad" bundle:nil];
        [self.navigationController pushViewController:changeLanguage animated:YES];
        
      }
      else
      {
        WXChangeLanguageViewController *changeLanguage = [self.storyboard instantiateViewControllerWithIdentifier:@"WXChangeLanguageViewController"];
        [self.navigationController pushViewController:changeLanguage animated:YES];
      }
      
      break;
    case 3:
    {
      if (isDeviceIPad)
      {
        WXChangePasswordViewController *changePassword = [[WXChangePasswordViewController alloc] initWithNibName:@"WxChangePasswordIpad" bundle:nil];
        [self.navigationController pushViewController:changePassword animated:YES];
      }
      else
      {
        WXChangePasswordViewController *changePassword = [self.storyboard instantiateViewControllerWithIdentifier:@"WXChangePasswordViewController"];
        [self.navigationController pushViewController:changePassword animated:YES];
      }
    }
      break;
    case 4:
      break;
    case 5:
    {
      if (isDeviceIPad)
      {
        WXAboutUsViewController *aboutUsViewC = [[WXAboutUsViewController alloc] initWithNibName:@"WXAboutUsIpad" bundle:nil];
        [self.navigationController pushViewController:aboutUsViewC animated:YES];
      }
      else
      {
        WXAboutUsViewController *aboutUs = [self.storyboard instantiateViewControllerWithIdentifier:@"WXAboutUsViewController"];
        [self.navigationController pushViewController:aboutUs animated:YES];
      }
    }
      break;
    case 6:
    {
      
    }
    case 7:
    {
      
    }
      break;
      
    default:
      break;
  }
}
#pragma mark - button action

/**
 *  Called on tapping the back button
 *
 *  @param sender object of UIButton
 */
- (IBAction)btnBackTapped:(UIButton*)sender
{
  [self.navigationController popViewControllerAnimated:YES];
}


/**
 *  Called on tapping the change profile picture camera icon
 *
 *  @param sender object of UIButton
 */
- (IBAction)btnChangeImageTapped:(UIButton*)sender
{
  [self openActionSheet];
}


/**
 *  Called on tapping the "Change your login credentials button"
 *
 *  @param sender object of UIButton
 */
- (IBAction)btnChangeLoginCredentialsTapped:(UIButton*)sender
{
  
}

/**
 *  <#Description#>
 *
 *  @param sender <#sender description#>
 */
- (IBAction)btnSwitchShowPage:(UISwitch*)sender
{
  if ([sender isOn])
  {
    [UserDefaults setBool:NO forKey:kHidePage];
    [UserDefaults synchronize];
    [[NSNotificationCenter defaultCenter] postNotificationName:@"ShowHidePage" object:nil];
    
  }
  else
  {
    [UserDefaults setBool:YES forKey:kHidePage];
    [UserDefaults synchronize];
    [[NSNotificationCenter defaultCenter] postNotificationName:@"ShowHidePage" object:nil];
  }
}


#pragma mark - UIImagePicker Functions and Delegate

- (void)openPhotoLibrary
{
  _imagePicker = nil;
  _imagePicker = [[UIImagePickerController alloc] init];
  _imagePicker.sourceType = UIImagePickerControllerSourceTypePhotoLibrary;
  _imagePicker.delegate = self;
  
//  AppDelegate *apDelegate = (AppDelegate*)[[UIApplication sharedApplication] delegate];
  
  [[NSOperationQueue mainQueue] addOperationWithBlock:^{
    [self presentViewController:_imagePicker animated:YES completion:NULL];
  }];
  
}

- (void)openImageCamera
{
  if (![UIImagePickerController isSourceTypeAvailable:UIImagePickerControllerSourceTypeCamera])
  {

    return;
  }
  _imagePicker = nil;
  _imagePicker = [[UIImagePickerController alloc] init];
  _imagePicker.sourceType = UIImagePickerControllerSourceTypeCamera;
  _imagePicker.editing = NO;
  _imagePicker.delegate = self;
  
  [[NSOperationQueue mainQueue] addOperationWithBlock:^{
    [self presentViewController:_imagePicker animated:YES completion:NULL];
  }];
}

- (void)imagePickerController:(UIImagePickerController *)picker didFinishPickingMediaWithInfo:(NSDictionary *)info
{
  isUploadingImage = YES;
  [self dismissViewControllerAnimated:YES completion:NULL];
  
  
  if ([info valueForKey:UIImagePickerControllerReferenceURL])
  {
    ALAssetsLibrary *assetLibrary=[[ALAssetsLibrary alloc] init];
    [assetLibrary assetForURL:[info valueForKey:UIImagePickerControllerReferenceURL] resultBlock:^(ALAsset *asset) {
      ALAssetRepresentation *rep = [asset defaultRepresentation];
      Byte *buffer = (Byte*)malloc(rep.size);
      NSUInteger buffered = [rep getBytes:buffer fromOffset:0.0 length:rep.size error:nil];
      NSData *data = [NSData dataWithBytesNoCopy:buffer length:buffered freeWhenDone:YES];//this is NSData may be what you want
      UIImage *image = [UIImage imageWithData:data];
      [_imgViewProfile setImage:image];
      [_imgViewCover setImage:image];
      NSData *imgData = UIImageJPEGRepresentation(image, .8);
      
      [self showProgressHUD:@""];
      if (image)
      {
        [NetworkManager callServiceWithImages:imgData params:@{@"token":[UserDefaults objectForKey:kToken],kAppLanguage:@"en"} serviceIdentifier:@"changePhoto" success:^(id response)
         {
           [self dismissProgressHUD];
           NSLog(@"reds:%@",response);
           [UserDefaults setObject:[[[[[response objectForKey:@"data"] firstObject] objectForKey:@"user"] firstObject] objectForKey:@"photoUrl"] forKey:kUserImage];
           [UserDefaults synchronize];
           [[NSNotificationCenter defaultCenter] postNotificationName:@"ChangePhoto" object:nil];
         } failure:^(NSError *error)
         {
           NSLog(@"err:%@",error);
           [self dismissProgressHUD];
         }];

      }
      
    }
    failureBlock:^(NSError *err)
    {
      NSLog(@"Error: %@",[err localizedDescription]);
    }];
  }
  
  else
  {
    UIImage *image = (UIImage*)[info objectForKeyNonNull:UIImagePickerControllerOriginalImage];
    
    [_imgViewProfile setImage:image];
    [_imgViewCover setImage:image];
    NSData *imgData = UIImageJPEGRepresentation(image, .8);
    [self showProgressHUD:@""];
    
    if (image)
    {
      [NetworkManager callServiceWithImages:imgData params:@{@"token":[UserDefaults objectForKey:kToken],@"appLanguage":@"en"} serviceIdentifier:@"changePhoto" success:^(id response)
      {
        [self dismissProgressHUD];
        NSLog(@"reds:%@",response);
        [UserDefaults setObject:[[[[[response objectForKey:@"data"] firstObject] objectForKey:@"user"] firstObject] objectForKey:@"photoUrl"] forKey:kUserImage];
        [UserDefaults synchronize];
        [[NSNotificationCenter defaultCenter] postNotificationName:@"ChangePhoto" object:nil];
      } failure:^(NSError *error)
      {
        NSLog(@"err:%@",error);
        [self dismissProgressHUD];
      }];
    }
  }
}
- (void)openActionSheet
{
  UIActionSheet *aSheet = [[UIActionSheet alloc] initWithTitle:kAPPName delegate:self cancelButtonTitle:[WXMyAccountViewController languageSelectedStringForKey:@"Cancel"] destructiveButtonTitle:[WXMyAccountViewController languageSelectedStringForKey:@"Choose Image"] otherButtonTitles:[WXMyAccountViewController languageSelectedStringForKey:@"Camera"], nil];
  [aSheet showInView:self.view];
  
  
}

#pragma mark - UIActionSheet delegates
- (void)actionSheet:(UIActionSheet *)actionSheet willDismissWithButtonIndex:(NSInteger)buttonIndex
{
  switch (buttonIndex)
  {
    case 0:
    {
      [self openPhotoLibrary];
    }
      break;
    case 1:
    {
      [self openImageCamera];
      
    }
      break;
      
    default:
      break;
  }
}
@end
