//
//  WXIPhoneLayout2.h
//  Wootrix
//
//  Created by Mayank Pahuja on 15/01/15.
//  Copyright (c) 2015 Techahead. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface UIView (ViewExtension)
+ (id)loadXIB:(NSString *)nibName forClass:(Class)classType;
@end

@interface WXIPhoneLayout2 : UIView
@property (strong, nonatomic) IBOutlet UIView *view1;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewBgView1;
@property (strong, nonatomic) IBOutlet UITextView *txtViewDescView1;
@property (strong, nonatomic) IBOutlet UILabel *lblTitleView1;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewPlayButton1;
@property (strong, nonatomic) IBOutlet UIButton *btnView1;
@property (strong, nonatomic) IBOutlet UILabel *lblDateView1;
@property (strong, nonatomic) IBOutlet UILabel *lblWebUrlView1;


@property (weak, nonatomic) IBOutlet UIImageView *imgViewBgView2;
@property (strong, nonatomic) IBOutlet UIView *view2;
@property (strong, nonatomic) IBOutlet UILabel *lblTitleView2;
@property (strong, nonatomic) IBOutlet UILabel *lblDateView2;
@property (strong, nonatomic) IBOutlet UILabel *lblEmailView2;
@property (strong, nonatomic) IBOutlet UITextView *txtViewDescriptionView2;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewPlayButton2;
@property (strong, nonatomic) IBOutlet UIButton *btnView2;


@property (weak, nonatomic) IBOutlet UIImageView *imgViewBgView3;
@property (strong, nonatomic) IBOutlet UIView *view3;
@property (strong, nonatomic) IBOutlet UILabel *lblTitleView3;
@property (strong, nonatomic) IBOutlet UILabel *lblTimeView3;
@property (strong, nonatomic) IBOutlet UILabel *lblWebUrlView3;
@property (strong, nonatomic) IBOutlet UITextView *txtViewDescriptionView3;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewPlayButton3;
@property (strong, nonatomic) IBOutlet UIButton *btnView3;



@property (strong, nonatomic) IBOutlet UIView *viewAdvert;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewAdvert;
@property (weak, nonatomic) IBOutlet UIButton *btnImage;
@property (weak, nonatomic) IBOutlet UIButton *btnVideo;


@property (strong, nonatomic) IBOutlet UIView *view4;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewBgView4;
@property (strong, nonatomic) IBOutlet UILabel *lblTimeView4;
@property (strong, nonatomic) IBOutlet UILabel *lblTitleView4;
@property (strong, nonatomic) IBOutlet UILabel *lblUrlView4;
@property (strong, nonatomic) IBOutlet UITextView *txtViewDescriptionView4;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewPlayButton4;
@property (strong, nonatomic) IBOutlet UIButton *btnView4;


@property (strong, nonatomic) IBOutlet UIView *view5;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewBGView5;
@property (strong, nonatomic) IBOutlet UILabel *lblDateView5;
@property (strong, nonatomic) IBOutlet UILabel *lblURLView5;
@property (strong, nonatomic) IBOutlet UILabel *lblTitleView5;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewPlayButton5;
@property (weak, nonatomic) IBOutlet UILabel *lblAdvertisement;
@property (strong, nonatomic) IBOutlet UIButton *btnView5;
@property (weak, nonatomic) IBOutlet UITextView *txtViewDescView5;

@property (strong, nonatomic) IBOutlet UIImageView *imgViewGlobeView1;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewGlobeView2;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewGlobeView3;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewGlobeView4;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewGlobeView5;


@end
