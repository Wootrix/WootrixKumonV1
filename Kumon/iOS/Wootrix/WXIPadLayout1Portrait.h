//
//  WXIPadLayout1Portrait.h
//  Wootrix
//
//  Created by Mayank Pahuja on 27/01/15.
//  Copyright (c) 2015 Techahead. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface UIView (ViewExtensionIpadPortrait3)
+ (id)loadXIB:(NSString *)nibName forClass:(Class)classType;
@end

@interface WXIPadLayout1Portrait : UIView

@property (strong, nonatomic) IBOutlet UIView *view1;
@property (strong, nonatomic) IBOutlet UIImageView *imgView1;
@property (strong, nonatomic) IBOutlet UILabel *lblTitleView1;
@property (strong, nonatomic) IBOutlet UILabel *lblDateView1;
@property (strong, nonatomic) IBOutlet UITextView *txtViewDescView1;
@property (strong, nonatomic) IBOutlet UILabel *lblUrlView1;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewPlayButton1;
@property (strong, nonatomic) IBOutlet UIButton *btnView1;


@property (strong, nonatomic) IBOutlet UIView *view2;
@property (strong, nonatomic) IBOutlet UILabel *lblTitleView2;
@property (strong, nonatomic) IBOutlet UIImageView *imgView2;
@property (strong, nonatomic) IBOutlet UILabel *lblDateView2;
@property (strong, nonatomic) IBOutlet UILabel *lblUrlView2;
@property (strong, nonatomic) IBOutlet UITextView *txtViewDescView2;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewPlayButton2;
@property (strong, nonatomic) IBOutlet UIButton *btnView2;

@property (strong, nonatomic) IBOutlet UIView *view3;
@property (strong, nonatomic) IBOutlet UIImageView *imgView3;
@property (strong, nonatomic) IBOutlet UILabel *lbltitleView3;
@property (strong, nonatomic) IBOutlet UILabel *lblDateView3;
@property (strong, nonatomic) IBOutlet UILabel *lblUrlView3;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewPlayButton3;
@property (strong, nonatomic) IBOutlet UITextView *txtViewDescView3;
@property (strong, nonatomic) IBOutlet UIButton *btnView3;


@property (strong, nonatomic) IBOutlet UIView *viewAdvert;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewAdvert;
@property (weak, nonatomic) IBOutlet UIButton *btnImage;
@property (weak, nonatomic) IBOutlet UIButton *btnVideo;
@property (weak, nonatomic) IBOutlet UILabel *lblAdvertisement;

@property (strong, nonatomic) IBOutlet UIImageView *imgViewGlobeView1;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewGlobeView2;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewGlobeView3;


@end
