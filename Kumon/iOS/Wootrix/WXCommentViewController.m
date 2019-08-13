//
//  WXCommentViewController.m
//  Wootrix
//
//  Created by Mayank Pahuja on 15/12/14.
//  Copyright (c) 2014 Techahead. All rights reserved.
//

#import "WXCommentViewController.h"

@interface WXCommentViewController ()
{
  NSDateFormatter *dateFormatter;
  NSInteger tableHeight;
}

@property (strong, nonatomic) id observerWillShow;

@property (strong, nonatomic) id observerWillHide;

@end

@interface WXCommentViewController (WebserviceMethods)

- (void)callServiceGetComments;
- (void)callServicePostComments;

@end

@implementation WXCommentViewController

- (void)viewDidLoad
{
  
    [super viewDidLoad];
  dateFormatter = [[NSDateFormatter alloc] init];
  tableHeight = _tblViewComments.height;
  [self addObservers];
    // Do any additional setup after loading the view.
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

 - (void)viewDidAppear:(BOOL)animated
{
  [self setFontsAndColors];
}

-(void)dealloc
{
  [self removeObservers];
}

#pragma mark - notificatiopn methods
- (void)addObservers
{
  _observerWillShow = [[NSNotificationCenter defaultCenter] addObserverForName:UIKeyboardWillShowNotification object:Nil queue:[NSOperationQueue mainQueue] usingBlock:^(NSNotification *note) {
    if (!isDeviceIPad)
    {
      NSDictionary *info = note.userInfo;
      CGSize kbSize = [[info objectForKey:UIKeyboardFrameEndUserInfoKey] CGRectValue].size;
      
      [UIView animateWithDuration:0.25 animations:^{
        _commentBarBottomConstrint.constant = kbSize.height;
      }];
    }
  }];
  
  _observerWillHide = [[NSNotificationCenter defaultCenter] addObserverForName:UIKeyboardWillHideNotification object:Nil queue:[NSOperationQueue mainQueue] usingBlock:^(NSNotification *note) {

    if (!isDeviceIPad)
    {
      [UIView animateWithDuration:0.25 animations:^{
        _commentBarBottomConstrint.constant = 0;
      }];
    }
  }];
  
}


- (void)removeObservers
{
  [[NSNotificationCenter defaultCenter] removeObserver:_observerWillShow name:UIKeyboardWillShowNotification object:nil];
  _observerWillShow = nil;
  
  [[NSNotificationCenter defaultCenter] removeObserver:_observerWillHide name:UIKeyboardWillHideNotification object:nil];
  _observerWillHide = nil;
}


#pragma mark - other methods
/**
 *  This method is called to set the custom font sizes and colors of all objects present on the view
 */
- (void)setFontsAndColors
{
  [_imgViewTextBg.layer setCornerRadius:2];
  [_imgViewTextBg setClipsToBounds:YES];
  
  [_btnSend.layer setCornerRadius:2];
  [_btnSend setClipsToBounds:YES];
  
  [_viewSeperator.layer setBorderWidth:1];
  [_viewSeperator.layer setBorderColor:[UIColor lightGrayColor].CGColor];
  
  [_lblNavBar setText:[WXCommentViewController languageSelectedStringForKey:@"Comments"]];
  
  [_btnSend setTitle:[WXCommentViewController languageSelectedStringForKey:@"Send"] forState:UIControlStateNormal];
  
  [_txtFieldComment setPlaceholder:[WXCommentViewController languageSelectedStringForKey:@"Write a comment..."]];
  [_txtFieldComment setTextColor:[UIColor blackColor]];
  
  [self callServiceGetComments];

}

/**
 *  This method checks if the entered values in the text fields are valid or not.
 *
 *  @return a bool value based on validity
 */

- (BOOL)hasValidValues
{
  return NO;
}

#pragma mark - UITableView

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
  return arrComments.count;
}

- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath
{
  NSString *comment = [[arrComments objectAtIndex:indexPath.row] objectForKey:@"comment"];
  
  //CGSize size = [comment sizeWithAttributes:@{NSFontAttributeName: [UIFont systemFontOfSize:13.0f]}];

  CGRect rect = [[[NSAttributedString alloc] initWithString:comment] boundingRectWithSize:(CGSize){tableView.width-65, CGFLOAT_MAX}
                                             options:NSStringDrawingUsesLineFragmentOrigin
                                             context:nil];
  
  
  NSInteger height = 40 + rect.size.height;
  
  return 120;
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    UITableViewCell *cell = nil;

    cell = (UITableViewCell *)[[[NSBundle mainBundle] loadNibNamed:@"CellComments" owner:self options:nil] objectAtIndex:0];
  
  UILabel *lblUserName = (UILabel*)[cell.contentView viewWithTag:60];
  UILabel *lblDate = (UILabel*)[cell.contentView viewWithTag:61];
  UITextView *txtViewComment = (UITextView*)[cell.contentView viewWithTag:62];
  UIImageView *imgViewUser = (UIImageView*)[cell.contentView viewWithTag:63];
  
  [lblUserName setText:[[arrComments objectAtIndex:indexPath.row] objectForKey:@"name"]];
  
  [lblDate setText:[self timeAgo:[[arrComments objectAtIndex:indexPath.row] objectForKey:@"commentDate"]]];
  
  [txtViewComment setText:[[arrComments objectAtIndex:indexPath.row] objectForKey:@"comment"]];
  txtViewComment.font = [UIFont systemFontOfSize:13.0f];
  
  [imgViewUser setImageWithURL:[NSURL URLWithString:[[arrComments objectAtIndex:indexPath.row] objectForKey:@"photoUrl"]]];
  
  
    return cell;
  }

#pragma mark - button action

- (IBAction)btnBackTapped:(UIButton*)sender
{
  [self.navigationController popViewControllerAnimated:YES];
}

- (IBAction)btnSendTapped:(UIButton*)sender
{
  if ([_txtFieldComment.text stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]].length > 0)
  {
    [self callServicePostComments];
  }
}

#pragma mark - textfield

- (void)textFieldDidBeginEditing:(UITextField *)textField
{
//  if (!isDeviceIPad)
//  {
////    [self.view setTransform:CGAffineTransformMakeTranslation(0, -216)];
//    [_viewCommentBar setYOrigin:self.view.yOrigin-216 ];
//    [_tblViewComments setHeight:_tblViewComments.height - 216];
//  }
  
  
}

- (void)textFieldDidEndEditing:(UITextField *)textField
{
////  [self.view setTransform:CGAffineTransformIdentity];
//  [_viewCommentBar setYOrigin:self.view.yOrigin+216 ];
//  [_tblViewComments setHeight:_tblViewComments.height + 216];
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


#pragma mark - date
-(NSDate *)getUTCTime
{
  NSTimeZone *tz = [NSTimeZone defaultTimeZone];
  NSInteger seconds = -[tz secondsFromGMTForDate: [NSDate date]];
  return [NSDate dateWithTimeInterval: seconds sinceDate: [NSDate date]];
}

- (NSString *)timeAgo:(NSString *)time
{
  NSDateFormatter *formatter = [[NSDateFormatter alloc] init];
  [formatter setDateFormat:@"yyyy-MM-dd HH:mm:ss"];
  [formatter setLocale:[[NSLocale alloc] initWithLocaleIdentifier:@"en_US"]];
  
  NSInteger interval = [[self getUTCTime] timeIntervalSinceDate:[formatter dateFromString:time]];
  interval = abs(interval);
  
  if (interval < 60)
  {
//    return [NSString stringWithFormat:@"%d %@",interval,[WXCommentViewController languageSelectedStringForKey:@"secs ago"]];
    return [[WXCommentViewController languageSelectedStringForKey:@"secs ago"] stringByReplacingOccurrencesOfString:@"XXX" withString:[NSString stringWithFormat:@"%d",interval]];
  }
  else if(interval>= 60 && interval < 60*60)
  {
    if (interval/60 == 1)
    {
//      return [NSString stringWithFormat:@"%d %@",interval/60,[WXCommentViewController languageSelectedStringForKey:@"min ago"]];
      return [[WXCommentViewController languageSelectedStringForKey:@"min ago"] stringByReplacingOccurrencesOfString:@"XXX" withString:[NSString stringWithFormat:@"%d",interval/60]];
    }
//    return [NSString stringWithFormat:@"%d %@",interval/60,[WXCommentViewController languageSelectedStringForKey:@"mins ago"]];
    return [[WXCommentViewController languageSelectedStringForKey:@"mins ago"] stringByReplacingOccurrencesOfString:@"XXX" withString:[NSString stringWithFormat:@"%d",interval/60]];
  }
  else if(interval>= 60*60 && interval < 60*60*24)
  {
    if (interval/60/60 == 1)
    {
//      return [NSString stringWithFormat:@"%d %@",interval/60/60,[WXCommentViewController languageSelectedStringForKey:@"hour ago"]];
      return [[WXCommentViewController languageSelectedStringForKey:@"hour ago"] stringByReplacingOccurrencesOfString:@"XXX" withString:[NSString stringWithFormat:@"%d",interval/60/60]];
    }
//    return [NSString stringWithFormat:@"%d %@",interval/60/60,[WXCommentViewController languageSelectedStringForKey:@"hours ago"]];
    return [[WXCommentViewController languageSelectedStringForKey:@"hours ago"] stringByReplacingOccurrencesOfString:@"XXX" withString:[NSString stringWithFormat:@"%d",interval/60/60]];
  }
  else
  {
    
    [dateFormatter setDateFormat:@"yyyy-MM-dd HH:mm:ss"];
    NSDate *artcleDate = [dateFormatter dateFromString:time];
    [dateFormatter setDateFormat:@"MMM. dd, yyyy"];
//    if (interval/60/60/24 == 1) {
//      return [NSString stringWithFormat:@"%d day ago",interval/60/60/24];
//    }
    return [NSString stringWithFormat:@"%@",[dateFormatter stringFromDate:artcleDate]];
  }
}

@end


@implementation WXCommentViewController (WebserviceMethods)

- (void)callServiceGetComments
{
  if(![[NetworkManager manager] isConnectedToWiFi])
  {
    [[[UIAlertView alloc] initWithTitle:kAPPName message:[WXCommentViewController languageSelectedStringForKey:@"Network Not Available"] delegate:nil cancelButtonTitle:[WXCommentViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles: nil] show];
    return;
  }
  [self showProgressHUD:[WXCommentViewController languageSelectedStringForKey:@"Loading..."]];
     [self.view setUserInteractionEnabled:NO];
  WXGetCommentsModel *getComments = [[WXGetCommentsModel alloc] init];
  [getComments setArticleId           :[_dictArticleData objectForKey:@"articleId"]];
  [getComments setToken               :[UserDefaults objectForKey:kToken]]; //6 Ashok
  [getComments setAppLanguage         :[UserDefaults objectForKey:kAppLanguage]?[UserDefaults objectForKey:kAppLanguage]:@"en"];
  [getComments setType                :_type];
  
  [[NetworkManager manager] requestWithMethod:kHTTPMethodPOST1
                                    URLString:@"getComments"
                                   parameters:getComments
                                       sucess:^(id response)
   {
     NSLog(@"res:%@",response);
     if ([[response objectForKey:kSuccess] boolValue] == YES)
     {
       arrComments = nil;
       arrComments = [[NSMutableArray alloc] initWithArray:[[[response objectForKey:kData] firstObject] objectForKey:@"comments"]];
//       [arrComments reverse];
       if (isDeviceIPad)
       {
         [_delegate getCommentNumber:arrComments.count];
       }
       [_tblViewComments reloadData];
       
       if (arrComments.count>0)
       {
         [_tblViewComments scrollToRowAtIndexPath:[NSIndexPath indexPathForRow:arrComments.count-1 inSection:0] atScrollPosition:UITableViewScrollPositionBottom animated:YES];
       }
       
//       [_tblViewComments scrollsToTop];
     }
     else
     {
       [[[UIAlertView alloc] initWithTitle:kAPPName message:[response objectForKey:kMessage] delegate:nil cancelButtonTitle:[WXCommentViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles:nil] show];
     }
     [self dismissProgressHUD];
        [self.view setUserInteractionEnabled:YES];
     
   }failure:^(NSError *error)
   {
     [self dismissProgressHUD];
        [self.view setUserInteractionEnabled:YES];
   }];
  
}

- (void)callServicePostComments
{
  if(![[NetworkManager manager] isConnectedToWiFi])
  {
    [[[UIAlertView alloc] initWithTitle:kAPPName message:[WXCommentViewController languageSelectedStringForKey:@"Network Not Available"] delegate:nil cancelButtonTitle:[WXCommentViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles: nil] show];
    return;
  }
  [self showProgressHUD:[WXCommentViewController languageSelectedStringForKey:@"Loading..."]];
     [self.view setUserInteractionEnabled:NO];
  WXPostCommentsModel *postComments = [[WXPostCommentsModel alloc] init];
  [postComments setArticleId            :[_dictArticleData objectForKey:@"articleId"]];
  [postComments setToken                :[UserDefaults objectForKey:kToken]]; //6 Ashok
  [postComments setComment              :_txtFieldComment.text];
  [postComments setAppLanguage          :[UserDefaults objectForKey:kAppLanguage]?[UserDefaults objectForKey:kAppLanguage]:@"en"];
  [postComments setType                 :_type];
  
  [[NetworkManager manager] requestWithMethod:kHTTPMethodPOST1
                                    URLString:@"postComment"
                                   parameters:postComments
                                       sucess:^(id response)
   {
     NSLog(@"res:%@",response);
     if ([[response objectForKey:kSuccess] boolValue] == YES)
     {
       _txtFieldComment.text = @"";
       [self callServiceGetComments];
     }
     else
     {
       [[[UIAlertView alloc] initWithTitle:kAPPName message:[response objectForKey:kMessage] delegate:nil cancelButtonTitle:[WXCommentViewController languageSelectedStringForKey:@"Ok"] otherButtonTitles:nil] show];
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
