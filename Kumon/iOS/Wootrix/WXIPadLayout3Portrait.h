//
//  WXIPadLayout3Portrait.h
//  Wootrix
//
//  Created by Mayank Pahuja on 28/01/15.
//  Copyright (c) 2015 Techahead. All rights reserved.
//

#import <UIKit/UIKit.h>
@interface UIView (ViewExtension)
+ (id)loadXIB:(NSString *)nibName forClass:(Class)classType;
@end
@interface WXIPadLayout3Portrait : UIView

@property (strong, nonatomic) IBOutlet UIView *view1;
@property (strong, nonatomic) IBOutlet UIImageView *imgView1;
@property (strong, nonatomic) IBOutlet UILabel *lbltitleView1;
@property (strong, nonatomic) IBOutlet UILabel *lbldateView1;
@property (strong, nonatomic) IBOutlet UILabel *lblUrlView1;
@property (strong, nonatomic) IBOutlet UITextView *txtViewDescView1;
@property (strong, nonatomic) IBOutlet UIButton *btnView1;


@property (strong, nonatomic) IBOutlet UIView *view2;
@property (strong, nonatomic) IBOutlet UIImageView *imgView2;
@property (strong, nonatomic) IBOutlet UILabel *lblTitleView2;
@property (strong, nonatomic) IBOutlet UILabel *lbldateView2;
@property (strong, nonatomic) IBOutlet UILabel *lblUrlView2;
@property (strong, nonatomic) IBOutlet UIButton *btnView2;


@property (strong, nonatomic) IBOutlet UIView *view3;
@property (strong, nonatomic) IBOutlet UIImageView *imgView3;
@property (strong, nonatomic) IBOutlet UILabel *lblTitleView3;
@property (strong, nonatomic) IBOutlet UILabel *lblDateView3;
@property (strong, nonatomic) IBOutlet UILabel *lblUrlView3;
@property (strong, nonatomic) IBOutlet UITextView *txtViewdescView3;
@property (strong, nonatomic) IBOutlet UIButton *btnView3;

@property (strong, nonatomic) IBOutlet UIView *view4;
@property (strong, nonatomic) IBOutlet UIImageView *imgView4;
@property (strong, nonatomic) IBOutlet UILabel *lblTitleView4;
@property (strong, nonatomic) IBOutlet UILabel *lbldateView4;
@property (strong, nonatomic) IBOutlet UILabel *lblurlView4;
@property (strong, nonatomic) IBOutlet UITextView *txtViewDescView4;
@property (strong, nonatomic) IBOutlet UIButton *btnView4;

@property (strong, nonatomic) IBOutlet UIView *view5;
@property (strong, nonatomic) IBOutlet UIImageView *imgView5;
@property (strong, nonatomic) IBOutlet UILabel *lblTitleView5;
@property (strong, nonatomic) IBOutlet UILabel *lbldateView5;
@property (strong, nonatomic) IBOutlet UILabel *lblUrlView5;
@property (strong, nonatomic) IBOutlet UITextView *tctViewDescView5;
@property (strong, nonatomic) IBOutlet UIButton *btnView5;

@property (strong, nonatomic) IBOutlet UIView *viewAdvert;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewAdvert;
@property (weak, nonatomic) IBOutlet UIButton *btnImage;
@property (weak, nonatomic) IBOutlet UIButton *btnVideo;

@property (strong, nonatomic) IBOutlet UIImageView *imgViewPlayButton1;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewPlayButton2;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewPlayButton3;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewPlayButton4;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewPlayButton5;
@property (weak, nonatomic) IBOutlet UILabel *lblAdvertisement;

@property (strong, nonatomic) IBOutlet UIImageView *imgViewGlobeView1;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewGlobeView2;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewGlobeView3;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewGlobeView4;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewGlobeView5;


@end
