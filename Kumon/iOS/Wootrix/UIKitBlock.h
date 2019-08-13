//
//  UIKitBlock.h
//  AlertController
//
//  Created by Brijesh on 17/01/15.
//  Copyright (c) 2015 Keith Harrison. All rights reserved.
//

typedef NS_ENUM(NSInteger, ImagePickerType)
{
    ImagePickerTypeCameraPhotoCapture = 0,
    ImagePickerTypeCameraVideoCapture,
    ImagePickerTypePhotoCameraRollOnly,
    ImagePickerTypeVideoCameraRollOnly,
    ImagePickerTypePhotoAlbum,
    ImagePickerTypeVideoAlbum
};

#import <Foundation/Foundation.h>

@interface UIKitBlock : NSObject
+ (UIKitBlock *)sharedInstance;

- (void)alertViewWithTitle:(NSString *)title
                   message:(NSString *)message
                   buttons:(NSArray *)buttonArray
                completion:(void(^)(NSInteger buttonIndex))block;

- (void)alertTextEntryWithPlaceHolder:(NSString *)placeHolder
                                title:(NSString *)title
                              message:(NSString *)message
                              buttons:(NSArray *)buttonArray
                           completion:(void(^)(UITextField *textField, NSInteger buttonIndex))block;
- (void)actionSheetWithTitle:(NSString *)title
                     message:(NSString *)message
                     buttons:(NSArray *)buttonArray
                  completion:(void(^)(NSInteger buttonIndex))block;

- (void)imagePickerWithType:(ImagePickerType)pickerType
                  allowEdit:(BOOL)allowEdit
                 completion:(void(^)(id response))block;

- (void)mailComposerWithSubject:(NSString *)subject
                    messageBody:(NSString *)messageBody
                     recipients:(NSArray *)recipientsArray
                     completion:(void(^)(BOOL success))block;

- (void)messageComposerWithSubject:(NSString *)subject
                       messageBody:(NSString *)messageBody
                        recipients:(NSArray *)recipientsArray
                        completion:(void(^)(BOOL success))block;

@end
