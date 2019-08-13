//
//  WXSignupViewController.m
//  Wootrix
//
//  Created by Mayank Pahuja on 12/12/14.
//  Copyright (c) 2014 Techahead. All rights reserved.
//

#import "WXSignupViewController.h"
#import "WXSignupModel.h"

@interface WXSignupViewController ()

+(BOOL) NSStringIsValidEmail:(NSString *)checkString;

@end

@interface WXSignupViewController (WebserviceMethods)

- (void)callSignupWebservice;

@end

@implementation WXSignupViewController

- (void)viewDidLoad {
    [super viewDidLoad];
  [self setFontsAndColors];
    // Do any additional setup after loading the view.
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    

    // Dispose of any resources that can be recreated.
}
#pragma mark - webservice methods

/**
 *  Called on tapping the signup button after validating all the fields
 *
 *  @param dictSignup contains dictionary that contains new signup information
 */
- (void)callServiceSignup:(NSDictionary*)dictSignup
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
  
  if(_txtFieldName.text.length == 0 || _txtFieldPassword.text.length == 0 || _txtFieldEmail.text.length == 0 || _txtFieldConfirmPassword.text.length == 0)
  {
    _msgString = @"Don't forget to fill all the required fields";
    return NO;
  }
  else if (![WXSignupViewController NSStringIsValidEmail:_txtFieldEmail.text])
  {
    _msgString = [WXSignupViewController languageSelectedStringForKey:@"Invalid email address"];
    return NO;
  }
  else if(_txtFieldPassword.text.length < 6 )
  {
    _msgString = [WXSignupViewController languageSelectedStringForKey:@"Password should be atleast 6 character long."];
    return NO;
  }
  else if(![_txtFieldPassword.text isEqualToString:_txtFieldConfirmPassword.text])
  {
    _msgString = [WXSignupViewController languageSelectedStringForKey:@"Password and Confirm password does not match"];
    return NO;
  }

  return YES;
}

/**
 *  This method is called to set the custom font sizes and colors of all objects present on the view
 */
- (void)setFontsAndColors
{
  NSDictionary *linkAttributes = @{@(kCTUnderlineStyleSingle):(id)kCTUnderlineStyleAttributeName};
  _lblAlredyHave.linkAttributes = linkAttributes;
  _lblAlredyHave.delegate = self;
  _lblAlredyHave.numberOfLines = 0;
  
  NSDictionary *attributesFontColor = @{NSFontAttributeName: [UIFont fontWithName:@"HelveticaNeue" size:14],NSForegroundColorAttributeName:[UIColor whiteColor]};
  NSDictionary *attributesBold = @{NSFontAttributeName: [UIFont fontWithName:@"HelveticaNeue-Bold" size:14],NSForegroundColorAttributeName:[UIColor whiteColor]};
  
  
  NSString *message = [NSString stringWithFormat:@"%@",[WXSignupViewController languageSelectedStringForKey:@"Alreay have an account? Login here"]];
  
  NSMutableAttributedString *attributedMessage = [[NSMutableAttributedString alloc] initWithString:message attributes:nil];
  [attributedMessage addAttributes:attributesFontColor range:[message rangeOfString:message]];
  [attributedMessage addAttributes:attributesBold range:[message rangeOfString:[WXSignupViewController languageSelectedStringForKey:@"Log in"]]];
  [attributedMessage addAttributes:attributesBold range:[message rangeOfString:[WXSignupViewController languageSelectedStringForKey:@"Acesse"]]];
  [attributedMessage addAttributes:attributesBold range:[message rangeOfString:[WXSignupViewController languageSelectedStringForKey:@"Entre"]]];

    
    
  _lblAlredyHave.text = attributedMessage;
  [_lblAlredyHave setTextAlignment:NSTextAlignmentCenter];
  
  
  NSDictionary *courseDict = @{@"event": @"1"};
  [_lblAlredyHave addLinkToTransitInformation:courseDict withRange:[message rangeOfString:[WXSignupViewController languageSelectedStringForKey:@"Log in"]]];
  [_lblAlredyHave addLinkToTransitInformation:courseDict withRange:[message rangeOfString:[WXSignupViewController languageSelectedStringForKey:@"Acesse"]]];
  [_lblAlredyHave addLinkToTransitInformation:courseDict withRange:[message rangeOfString:[WXSignupViewController languageSelectedStringForKey:@"Entre"]]];
    
  
  [_btnSignup setTitle:[WXSignupViewController languageSelectedStringForKey:@"Sign up"] forState:UIControlStateNormal];
  
  NSMutableAttributedString *attriutedPasswordPlaceholder = [[NSMutableAttributedString alloc] initWithString:[WXSignupViewController languageSelectedStringForKey:@"Password"] attributes:@{NSForegroundColorAttributeName:[UIColor whiteColor]}];
  [_txtFieldPassword setAttributedPlaceholder:attriutedPasswordPlaceholder];
  
  
  NSMutableAttributedString *attriutedEmailPlaceholder = [[NSMutableAttributedString alloc] initWithString:[WXSignupViewController languageSelectedStringForKey:@"Email"] attributes:@{NSForegroundColorAttributeName:[UIColor whiteColor]}];
  [_txtFieldEmail setAttributedPlaceholder:attriutedEmailPlaceholder];
  
  NSMutableAttributedString *attriutedNamePlaceholder = [[NSMutableAttributedString alloc] initWithString:[WXSignupViewController languageSelectedStringForKey:@"Name"] attributes:@{NSForegroundColorAttributeName:[UIColor whiteColor]}];
  [_txtFieldName setAttributedPlaceholder:attriutedNamePlaceholder];
  
  
  NSMutableAttributedString *attriutedConfirmPasswordPlaceholder = [[NSMutableAttributedString alloc] initWithString:[WXSignupViewController languageSelectedStringForKey:@"Confirm Password"] attributes:@{NSForegroundColorAttributeName:[UIColor whiteColor]}];
  [_txtFieldConfirmPassword setAttributedPlaceholder:attriutedConfirmPasswordPlaceholder];

}


#pragma mark - validatation
/**
 *  checks if the string passed is a valid email id or not
 *
 *  @param checkString string containing an email id
 *
 *  @return returns YES if the string passed is a valid email id sting
 */
+(BOOL) NSStringIsValidEmail:(NSString *)checkString
{
  BOOL stricterFilter = YES;
  NSString *stricterFilterString = @"[A-Z0-9a-z\\._%+-]+@([A-Za-z0-9-]+\\.)+[A-Za-z]{2,4}";
  NSString *laxString = @".+@([A-Za-z0-9]+\\.)+[A-Za-z]{2}[A-Za-z]*";
  NSString *emailRegex = stricterFilter ? stricterFilterString : laxString;
  NSPredicate *emailTest = [NSPredicate predicateWithFormat:@"SELF MATCHES %@", emailRegex];
  return [emailTest evaluateWithObject:checkString];
}
#pragma mark - button actions

- (IBAction)btnLoginTapped:(UIButton*)sender
{
  [self.navigationController popViewControllerAnimated:YES];
}

- (IBAction)btnSignupTapped:(UIButton*)sender
{
  if ([self hasValidValues])
  {
    [self callSignupWebservice];
  }
  else
  {
    [[[UIAlertView alloc] initWithTitle:kAPPName message:_msgString delegate:nil cancelButtonTitle:[WXSignupViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles:nil] show];
  }
}


#pragma mark - textfield

- (void)textFieldDidBeginEditing:(UITextField *)textField
{
  
}
- (void)textFieldDidEndEditing:(UITextField *)textField
{
  
}
- (BOOL)textFieldShouldReturn:(UITextField *)textField
{
  [textField resignFirstResponder];
  return YES;
}

#pragma mark - TTTdelegates

- (void)attributedLabel:(TTTAttributedLabel *)label didSelectLinkWithTextCheckingResult:(NSTextCheckingResult *)result
{
  
  [self.navigationController popViewControllerAnimated:YES];
}

#pragma mark - PROTECTED METHODS

- (void)executeBlock:(dispatch_block_t)block
{
  // By design this method should not be invoked from the introQueue.
  //
  NSAssert(!dispatch_get_specific(introQueueTag), @"Invoked on incorrect queue");
  
  dispatch_async(introQueue, ^{ @autoreleasepool {
    
    block();
    
  }});
}
@end

#pragma mark - Web Service Category Methods

@implementation WXSignupViewController (WebserviceMethods)

- (void)callSignupWebservice
{
  if(![[NetworkManager manager] isConnectedToWiFi])
  {
    [[[UIAlertView alloc] initWithTitle:kAPPName message:[WXSignupViewController languageSelectedStringForKey:@"Network Not Available"] delegate:nil cancelButtonTitle:[WXSignupViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles: nil] show];
    return;
  }
  [self showProgressHUD:[WXSignupViewController languageSelectedStringForKey:@"Loading..."]];
     [self.view setUserInteractionEnabled:NO];
//  if([[NetworkManager manager] isConnectedToNetwork])
//  {
    WXSignupRequest *signuprequest= [[WXSignupRequest alloc] init];
    [signuprequest setEmail              :_txtFieldEmail.text];
    [signuprequest setPassword           :_txtFieldPassword.text];
  [signuprequest setAppLanguage        :[UserDefaults objectForKey:kAppLanguage]?[UserDefaults objectForKey:kAppLanguage]:@"en"];
    [signuprequest setName               :_txtFieldName.text];
    [[NetworkManager manager] requestWithMethod:kHTTPMethodPOST1
                                      URLString:@"signup"
                                     parameters:signuprequest
                                         sucess:^(id response)
     {
       [self dismissProgressHUD];
          [self.view setUserInteractionEnabled:YES];
       NSLog(@"res:%@",response);
       if ([[response objectForKey:kSuccess] boolValue] == YES)
       {
         [self.navigationController popViewControllerAnimated:YES];
         [[[UIAlertView alloc] initWithTitle:kAPPName message:[response objectForKey:kMessage] delegate:nil cancelButtonTitle:[WXSignupViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles: nil] show];
       }
       else
       {
         [[[UIAlertView alloc] initWithTitle:kAPPName message:[response objectForKey:kMessage] delegate:nil cancelButtonTitle:[WXSignupViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles: nil] show];
       }
       
     }failure:^(NSError *error)
     {
       [self dismissProgressHUD];
          [self.view setUserInteractionEnabled:YES];
     }];
//  }
}
@end
