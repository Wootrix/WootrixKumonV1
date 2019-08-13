//
//  WXIPhoneLayout1.h
//  Wootrix
//
//  Created by Mayank Pahuja on 15/01/15.
//  Copyright (c) 2015 Techahead. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface UIView (ViewExtension)
+ (id)loadXIB:(NSString *)nibName forClass:(Class)classType;
@end


@interface WXIPhoneLayout1 : UIView

@property (strong, nonatomic) IBOutlet UIView *view1;
@property (strong, nonatomic) IBOutlet UILabel *lblCaptionView1;
@property (strong, nonatomic) IBOutlet UILabel *lblTitleView1;
@property (strong, nonatomic) IBOutlet UILabel *lblDateView1;
@property (strong, nonatomic) IBOutlet UILabel *lblWebUrlView1;
@property (strong, nonatomic) IBOutlet UITextView *txtViewDescriptionView1;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewBGView1;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewPlayButton1;
@property (strong, nonatomic) IBOutlet UIButton *btnView1;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewGlobeView1;


@property (weak, nonatomic) IBOutlet UIImageView *imgViewBGView2;
@property (strong, nonatomic) IBOutlet UIView *view2;
@property (strong, nonatomic) IBOutlet UILabel *lblTitleView2;
@property (strong, nonatomic) IBOutlet UILabel *lblTimeView2;
@property (strong, nonatomic) IBOutlet UILabel *lblWebUrlView2;
@property (strong, nonatomic) IBOutlet UITextView *txtViewDescriptionView2;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewPlayButton2;
@property (strong, nonatomic) IBOutlet UIButton *btnView2;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewGlobeView2;

@property (strong, nonatomic) IBOutlet UIView *viewAdvert;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewAdvert;
@property (weak, nonatomic) IBOutlet UIButton *btnImage;
@property (weak, nonatomic) IBOutlet UIButton *btnVideo;

@property (strong, nonatomic) IBOutlet UIView *view3;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewBgView3;
@property (strong, nonatomic) IBOutlet UILabel *lblTimeView3;
@property (strong, nonatomic) IBOutlet UILabel *lblTitleView3;
@property (strong, nonatomic) IBOutlet UILabel *lblUrlView3;
@property (strong, nonatomic) IBOutlet UITextView *txtViewDescriptionView3;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewPlayButton3;
@property (strong, nonatomic) IBOutlet UIButton *btnView3;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewGlobeView3;

@property (strong, nonatomic) IBOutlet UIView *view4;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewBGView4;
@property (strong, nonatomic) IBOutlet UILabel *lblDateView4;
@property (strong, nonatomic) IBOutlet UILabel *lblURLView4;
@property (strong, nonatomic) IBOutlet UILabel *lblTitleView4;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewPlayButton4;
@property (weak, nonatomic) IBOutlet UILabel *lblAdvertisement;
@property (strong, nonatomic) IBOutlet UIButton *btnView4;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewGlobeView4;
@property (strong, nonatomic) IBOutlet UITextView *txtViewDescriptionView4;

@end
