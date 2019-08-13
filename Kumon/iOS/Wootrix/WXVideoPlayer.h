//
//  WXVideoPlayer.h
//  Wootrix
//
//  Created by Mayank Pahuja on 15/12/14.
//  Copyright (c) 2014 Techahead. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <MediaPlayer/MediaPlayer.h>

@interface WXVideoPlayer : MPMoviePlayerController
/**
 *  Initializes the video player
 *
 *  @return object of MPMoviePlayerController class
 */
- (instancetype)init;
@end
