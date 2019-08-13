//
//  WXChangeLanguageViewController.m
//  Wootrix
//
//  Created by Mayank Pahuja on 31/01/15.
//  Copyright (c) 2015 Techahead. All rights reserved.
//

#import "WXChangeLanguageViewController.h"

@interface WXChangeLanguageViewController ()

@end

@implementation WXChangeLanguageViewController

- (void)viewDidLoad {
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

/*
#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}
*/
- (void)setFontsAndColors
{
  [_lblNavBartitle setText:[WXChangeLanguageViewController languageSelectedStringForKey:@"Change Language"]];
}
#pragma mark - UITableView

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
  return 3;
}

- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath
{
  return 44;
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
  UITableViewCell *cell = nil;
  
  cell = (UITableViewCell *)[[[NSBundle mainBundle] loadNibNamed:@"CellLanguage" owner:self options:nil] objectAtIndex:0];
  
  UILabel *lblTitle = (UILabel*)[cell.contentView viewWithTag:60];
  UIButton *btnSelect = (UIButton*)[cell.contentView viewWithTag:61];
  [btnSelect addTarget:self action:@selector(btnSelectLanguageTapped:) forControlEvents:UIControlEventValueChanged];
  

  switch (indexPath.row)
  {
    case 0:
      [lblTitle setText:[WXChangeLanguageViewController languageSelectedStringForKey:@"English"]];
      if ([[UserDefaults objectForKey:kAppLanguage] isEqualToString:@"en"])
      {
        [btnSelect setSelected:YES];
      }
      else
      {
        [btnSelect setSelected:NO];
      }
      break;
    case 1:
      [lblTitle setText:[WXChangeLanguageViewController languageSelectedStringForKey:@"Spanish"]];
      if ([[UserDefaults objectForKey:kAppLanguage] isEqualToString:@"es"])
      {
        [btnSelect setSelected:YES];
      }
      else
      {
        [btnSelect setSelected:NO];
      }
      break;
    case 2:
      [lblTitle setText:[WXChangeLanguageViewController languageSelectedStringForKey:@"Portuguese"]];
      if ([[UserDefaults objectForKey:kAppLanguage] isEqualToString:@"pt"])
      {
        [btnSelect setSelected:YES];
      }
      else
      {
        [btnSelect setSelected:NO];
      }
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
    case 0:
      [UserDefaults setObject:@"en" forKey:kAppLanguage];
      break;
    case 1:
      [UserDefaults setObject:@"es" forKey:kAppLanguage];
      break;
    case 2:
      [UserDefaults setObject:@"pt" forKey:kAppLanguage];
      break;
    default:
      break;
  }
  [UserDefaults synchronize];

  
//  [[NSUserDefaults standardUserDefaults] removeObjectForKey:kSavedTopics];
  
  [_tblViewLanguages reloadData];
  [self viewDidAppear:YES];
  
  [[NSNotificationCenter defaultCenter] postNotificationName:@"ChangeLanguage" object:nil];
  
}
#pragma mark - button actions

- (void)btnSelectLanguageTapped:(UIButton*)sender
{
  
}
- (IBAction)btnBackTapped:(UIButton *)sender
{
  [self.navigationController popViewControllerAnimated:YES];
}
@end
