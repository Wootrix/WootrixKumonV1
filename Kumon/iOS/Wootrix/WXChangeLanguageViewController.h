//
//  WXChangeLanguageViewController.h
//  Wootrix
//
//  Created by Mayank Pahuja on 31/01/15.
//  Copyright (c) 2015 Techahead. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface WXChangeLanguageViewController : UIViewController

@property (nonatomic, strong) IBOutlet UITableView *tblViewLanguages;
@property (strong, nonatomic) IBOutlet UILabel *lblNavBartitle;
- (IBAction)btnBackTapped:(UIButton *)sender;

@end
