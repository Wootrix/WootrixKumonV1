//
//  AFKPageFlipperIphone.h
//  AFKPageFlipperIphone
//
//  Created by Marco Tabini on 10-10-11.
//  Copyright 2010 AFK Studio Partnership. All rights reserved.
//
//  Modified by Reefaq Mohammed on 16/07/11.
 

#import <UIKit/UIKit.h>
#import <QuartzCore/QuartzCore.h>
typedef enum : NSUInteger {
    FlipStartedIphone,
    FlipInProgressIphone,
    FlipEndedIphone,
} FlipActionIphone;


@class AFKPageFlipperIphone;


@protocol AFKPageFlipperIphoneDataSource

- (NSInteger) numberOfPagesForPageFlipperIphone:(AFKPageFlipperIphone *) pageFlipper;
- (UIView *) viewForPage:(NSInteger) page inFlipperIphone:(AFKPageFlipperIphone *) pageFlipper;

- (void)pageFlipActionType:(FlipActionIphone)action;
- (void)flipEndedFromLeft:(AFKPageFlipperIphone *)pageFlipper;

@end


typedef enum {
	AFKPageFlipperDirectionLeftIphone,
	AFKPageFlipperDirectionRightIphone,
} AFKPageFlipperDirectionIphone;



@interface AFKPageFlipperIphone : UIView {
	NSObject <AFKPageFlipperIphoneDataSource> *dataSource;
	NSInteger currentPage;
	NSInteger numberOfPages;
	
	NSInteger pageDifference;
	
	UIView *currentView;
	UIView *newView;
	
	// shadows
	CALayer *frontLayerShadow;
	CALayer *backLayerShadow;
	CALayer *leftLayerShadow;
	CALayer *rightLayerShadow;
	// shadows
  
	CALayer *backgroundAnimationLayer;
	CALayer *flipAnimationLayer;
	CALayer *blankFlipAnimationLayerOnLeft1;
	CALayer *blankFlipAnimationLayerOnRight1;
	
	CALayer *blankFlipAnimationLayerOnLeft2;
	CALayer *blankFlipAnimationLayerOnRight2;	
	
	AFKPageFlipperDirectionIphone flipDirection;
	float startFlipAngle;
	float endFlipAngle;
	float currentAngle;
  NSInteger pageNumberAfterFlipEnd;

	BOOL setNewViewOnCompletion;
	BOOL animating;
	
	BOOL disabled;
	
	UIImage *flipIllusionPortrait;
	UIImage *flipIllusionLandscape;
	
}

@property (nonatomic,retain) NSObject <AFKPageFlipperIphoneDataSource> *dataSource;
@property (nonatomic,assign) NSInteger currentPage;
@property (nonatomic,assign) NSInteger numberOfPages;
@property (nonatomic,assign) NSInteger pageDifference;
@property (nonatomic,assign) BOOL disabled;
@property (nonatomic,assign) UIView *currentView;
@property (nonatomic,assign) UIView *newView;
@property (nonatomic,readonly) BOOL animating;


- (void) setCurrentPage:(NSInteger) value animated:(BOOL) animated;

@end
