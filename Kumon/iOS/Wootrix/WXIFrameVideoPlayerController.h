//
//  WXIFrameVideoPlayerController.h
//  Wootrix
//
//  Created by Teena Nath Paul on 24/07/15.
//  Copyright (c) 2015 Techahead. All rights reserved.
//

#import <UIKit/UIKit.h>

@protocol WXIFrameDelegate <NSObject>

- (void)openMessageFromEmbedwithType:(NSString*)type andData:(NSDictionary*)data;

@end

typedef enum : NSUInteger {
    iFrameVideoTypeArticle,
    iFrameVideoTypeAdvertisement
} iFrameVideoType;



@interface WXIFrameVideoPlayerController : UIViewController

@property (strong, nonatomic) id<WXIFrameDelegate> delegate;
@property (strong, nonatomic) NSString *strIFrameURL;
@property (strong, nonatomic) NSDictionary *dictData;
@property (strong, nonatomic) NSString *type;
@property (strong, nonatomic) NSString *magazineId;
@property (assign, nonatomic) iFrameVideoType iFrameVideoType;
@property BOOL Pushed;

@end
