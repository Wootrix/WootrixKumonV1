//
//  WXChangePasswordViewController.m
//  Wootrix
//
//  Created by Mayank Pahuja on 18/12/14.
//  Copyright (c) 2014 Techahead. All rights reserved.
//

#import "WXChangePasswordViewController.h"



@interface WXChangePasswordViewController ()

@end

@interface WXChangePasswordViewController (Webservice)

- (void)callChangePasswordService;

@end

@implementation WXChangePasswordViewController

- (void)viewDidLoad
{
  [self setFontsAndColors];
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
  
}

#pragma mark - other methods

/**
 *  This method checks if the entered values in the text fields are valid or not.
 *
 *  @return a bool value based on validity
 */

- (BOOL)hasValidValues
{
  _msgString = nil;
  
  if((_txtFieldOldPassword.text.length == 0 || _txtFieldNewPassword.text.length == 0) && ![UserDefaults boolForKey:kRequirePassword])
  {
    _msgString = @"Don't forget to fill all the required fields";
    return NO;
  }
  else if(_txtFieldNewPassword.text.length < 6 )
  {
    _msgString = [WXChangePasswordViewController languageSelectedStringForKey:@"Current password should be atleast 6 chracter long"];
    return NO;
  }
  else if(![_txtFieldNewPassword.text isEqualToString:_txtFieldConfirmPassword.text])
  {
    _msgString = [WXChangePasswordViewController languageSelectedStringForKey:@"Password and Confirm password does not match"];
    return NO;
  }
  
  return YES;
}

/**
 *  This method is called to set the custom font sizes and colors of all objects present on the view
 */
- (void)setFontsAndColors
{
  if ([UserDefaults boolForKey:kRequirePassword])
  {
    [_txtFieldOldPassword setHidden:YES];
    [_lblNavBar setText:[WXChangePasswordViewController languageSelectedStringForKey:@"Add Password"]];
  }
  else
  {
    [_lblNavBar setText:[WXChangePasswordViewController languageSelectedStringForKey:@"Change Password"]];
  }
  
  [_txtFieldOldPassword setPlaceholder:[WXChangePasswordViewController languageSelectedStringForKey:@"Old Password"]];
  [_txtFieldNewPassword setPlaceholder:[WXChangePasswordViewController languageSelectedStringForKey:@"New Password"]];
  [_txtFieldConfirmPassword setPlaceholder:[WXChangePasswordViewController languageSelectedStringForKey:@"Confirm New Password"]];
}



#pragma mark - web services

/**
 *  Calls the service to Change the current password
 *
 *  @param dictPasswordDetails <#dictPasswordDetails description#>
 */
- (void)callServiceChangePassword:(NSDictionary*)dictPasswordDetails
{
  
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
 *  Called on tapping the Done button
 *
 *  @param sender object of UIButton
 */
- (IBAction)btnDoneTapped:(UIButton*)sender
{
  if ([self hasValidValues])
  {
    [self callChangePasswordService];
  }
  else
  {
    [[[UIAlertView alloc] initWithTitle:kAPPName message:_msgString delegate:nil cancelButtonTitle:[WXChangePasswordViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles:nil] show];
  }
  
}
#pragma mark - textfield

- (void)textFieldDidBeginEditing:(UITextField *)textField
{
    
}

- (void)textFieldDidEndEditing:(UITextField *)textField
{
  [self.view setTransform:CGAffineTransformIdentity];
}

- (BOOL)textFieldShouldReturn:(UITextField *)textField
{
  [textField resignFirstResponder];
  return YES;
}

- (BOOL)textField:(UITextField *)textField shouldChangeCharactersInRange:(NSRange)range replacementString:(NSString *)string
{
  return YES;
}
@end


@implementation WXChangePasswordViewController (Webservice)

- (void)callChangePasswordService
{
  if(![[NetworkManager manager] isConnectedToWiFi])
  {
    [[[UIAlertView alloc] initWithTitle:kAPPName message:[WXChangePasswordViewController languageSelectedStringForKey:@"Network Not Available"] delegate:nil cancelButtonTitle:[WXChangePasswordViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles: nil] show];
    return;
  }
  [self showProgressHUD:[WXChangePasswordViewController languageSelectedStringForKey:@"Loading..."]];
     [self.view setUserInteractionEnabled:NO];
  
  [[NetworkManager manager] requestNonJsonModelWithMethod:kHTTPMethodPOST1
                                                URLString:@"changePassword"
                                               parameters:@{@"oldPassword":_txtFieldOldPassword.text,@"newPassword":_txtFieldNewPassword.text,@"token":[UserDefaults objectForKey:kToken],kAppLanguage:[UserDefaults objectForKey:kAppLanguage]}
                                                   sucess:^(id response)
   {
     NSLog(@"res:%@",response);
     if ([[response objectForKey:kSuccess] boolValue] == YES)
     {
       [[[UIAlertView alloc] initWithTitle:kAPPName message:@"Password successfully changed" delegate:nil cancelButtonTitle:[WXChangePasswordViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles:nil] show];
     }
     else
     {
//       [[[UIAlertView alloc] initWithTitle:kAPPName message:[response objectForKey:kMessage] delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil] show];
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
