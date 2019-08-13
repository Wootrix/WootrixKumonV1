//
//  WXIPadLayout2Portrait.h
//  Wootrix
//
//  Created by Mayank Pahuja on 27/01/15.
//  Copyright (c) 2015 Techahead. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface UIView (ViewExtension)
+ (id)loadXIB:(NSString *)nibName forClass:(Class)classType;
@end

@interface WXIPadLayout2Portrait : UIView

@property (strong, nonatomic) IBOutlet UIView *view1;
@property (strong, nonatomic) IBOutlet UIImageView *imgView1;
@property (strong, nonatomic) IBOutlet UILabel *lblTitleVeiw1;
@property (strong, nonatomic) IBOutlet UILabel *lblDateView1;
@property (strong, nonatomic) IBOutlet UILabel *lblUrlView1;
@property (strong, nonatomic) IBOutlet UITextView *txtViewDescView1;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewPlayButton1;
@property (strong, nonatomic) IBOutlet UIButton *btnView1;

@property (strong, nonatomic) IBOutlet UIView *viewAdvert;
@property (strong, nonatomic) IBOutlet UIImageView *imgViewAdvert;
@property (weak, nonatomic) IBOutlet UIButton *btnImage;
@property (weak, nonatomic) IBOutlet UIButton *btnVideo;
@property (weak, nonatomic) IBOutlet UILabel *lblAdvertisement;

@property (strong, nonatomic) IBOutlet UIImageView *imgViewGlobeView1;


@end
