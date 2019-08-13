//
//  UIKitBlock.m
//  AlertController
//
//  Created by Brijesh on 17/01/15.
//  Copyright (c) 2015 Keith Harrison. All rights reserved.
//

#define IS_IOS_GREATER_OR_EQUAL(x) ([[[UIDevice currentDevice] systemVersion] integerValue] >= x)

#import <UIKit/UIKit.h>
#import <MobileCoreServices/MobileCoreServices.h>
#import <MessageUI/MessageUI.h>
#import "UIKitBlock.h"

@interface UIKitBlock()<UIAlertViewDelegate,UIActionSheetDelegate,UINavigationControllerDelegate, UIImagePickerControllerDelegate,MFMailComposeViewControllerDelegate,MFMessageComposeViewControllerDelegate>

@property (assign, nonatomic) ImagePickerType imagePickerType;
@property (copy, nonatomic) void(^NormalAlertBlock)(NSInteger buttonIndex);
@property (copy, nonatomic) void(^TextFieldAlertBlock)(UITextField *textField, NSInteger buttonIndex);
@property (copy, nonatomic) void(^ActionSheetBlock)(NSInteger buttonIndex);
@property (copy, nonatomic) void(^MailMessageComposerBlock)(BOOL success);

@property (strong, nonatomic) UIImagePickerController *imagePicker;

@end

@implementation UIKitBlock

+ (UIKitBlock *)sharedInstance
{
    static UIKitBlock *_sharedInstance = nil;
    static dispatch_once_t onceToken;
    dispatch_once(&onceToken, ^{
        _sharedInstance = [[UIKitBlock alloc] init];
    });
    return _sharedInstance;
}

#pragma mark -
#pragma mark === Alert View ===
#pragma mark -

- (void)alertViewWithTitle:(NSString *)title
                   message:(NSString *)message
                   buttons:(NSArray *)buttonArray
                completion:(void(^)(NSInteger buttonIndex))block
{
    if (IS_IOS_GREATER_OR_EQUAL(8.0))
    {
        UIAlertController *alertController = [UIAlertController alertControllerWithTitle:title message:message preferredStyle:UIAlertControllerStyleAlert];
        for (NSString *buttonTitle in buttonArray)
        {
            UIAlertAction *action = [UIAlertAction actionWithTitle:buttonTitle style:UIAlertActionStyleDefault handler:^(UIAlertAction *action)
               {
                   NSInteger index = [buttonArray indexOfObject:action.title];
                   block(index);
               }];
            [alertController addAction:action];
        }
        UIViewController *controller = [[[[UIApplication sharedApplication] delegate] window] rootViewController];
        [controller presentViewController:alertController animated:YES completion:nil];
    }
    else
    {
        _NormalAlertBlock = block;
        UIAlertView *alertView = [[UIAlertView alloc] initWithTitle:title message:message delegate:self cancelButtonTitle:nil otherButtonTitles:nil];
        for (NSString *buttonTitle in buttonArray)
        {
            [alertView addButtonWithTitle:buttonTitle];
        }
        [alertView show];
    }
}

- (void)alertTextEntryWithPlaceHolder:(NSString *)placeHolder
                                title:(NSString *)title
                              message:(NSString *)message
                              buttons:(NSArray *)buttonArray
                           completion:(void(^)(UITextField *textField, NSInteger buttonIndex))block
{
    if (IS_IOS_GREATER_OR_EQUAL(8.0))
    {
        UIAlertController *alertController = [UIAlertController alertControllerWithTitle:title message:message preferredStyle:UIAlertControllerStyleAlert];
        for (NSString *buttonTitle in buttonArray)
        {
            UIAlertAction *action = [UIAlertAction actionWithTitle:buttonTitle style:UIAlertActionStyleDefault handler:^(UIAlertAction *action)
                                     {
                                         NSInteger index = [buttonArray indexOfObject:action.title];
                                         UITextField *textField = alertController.textFields.firstObject;
                                         block(textField,index);
                                     }];
            [alertController addAction:action];
        }
        
        [alertController addTextFieldWithConfigurationHandler:^(UITextField *textField)
         {
             textField.placeholder = placeHolder;
         }];
        
        UIViewController *controller = [[[[UIApplication sharedApplication] delegate] window] rootViewController];
        [controller presentViewController:alertController animated:YES completion:nil];
    }
    else
    {
        _TextFieldAlertBlock = block;
        UIAlertView *alertView = [[UIAlertView alloc] initWithTitle:title message:message delegate:self cancelButtonTitle:nil otherButtonTitles:nil];
        for (NSString *buttonTitle in buttonArray)
        {
            [alertView addButtonWithTitle:buttonTitle];
        }
        [alertView setAlertViewStyle:UIAlertViewStylePlainTextInput];
        UITextField *textField = [alertView textFieldAtIndex:0];
        textField.placeholder = placeHolder;
        [alertView show];
    }
}

- (void)alertView:(UIAlertView *)alertView clickedButtonAtIndex:(NSInteger)buttonIndex
{
    if (_NormalAlertBlock)
    {
        _NormalAlertBlock(buttonIndex);
        _NormalAlertBlock = nil;
    }
    
    if (_TextFieldAlertBlock)
    {
        UITextField *textField = [alertView textFieldAtIndex:0];
        _TextFieldAlertBlock(textField, buttonIndex);
        _TextFieldAlertBlock = nil;
    }
}

#pragma mark -
#pragma mark === Action Sheet ===
#pragma mark -

- (void)actionSheetWithTitle:(NSString *)title
                   message:(NSString *)message
                   buttons:(NSArray *)buttonArray
                completion:(void(^)(NSInteger buttonIndex))block
{
    if (IS_IOS_GREATER_OR_EQUAL(8.0))
    {
        UIAlertController *alertController = [UIAlertController alertControllerWithTitle:title message:message preferredStyle:UIAlertControllerStyleActionSheet];
        for (NSString *buttonTitle in buttonArray)
        {
            UIAlertAction *action = [UIAlertAction actionWithTitle:buttonTitle style:UIAlertActionStyleDefault handler:^(UIAlertAction *action)
                                     {
                                         NSInteger index = [buttonArray indexOfObject:action.title];
                                         block(index);
                                     }];
            [alertController addAction:action];
        }
        
        UIAlertAction *action = [UIAlertAction actionWithTitle:@"Cancel" style:UIAlertActionStyleCancel handler:^(UIAlertAction *action)
                                 {
                                     block(buttonArray.count);
                                 }];
        [alertController addAction:action];
        
        UIViewController *controller = [[[[UIApplication sharedApplication] delegate] window] rootViewController];
        [controller presentViewController:alertController animated:YES completion:nil];
    }
    else
    {
        _ActionSheetBlock = block;
        UIActionSheet *actionSheet = [[UIActionSheet alloc] initWithTitle:title delegate:self cancelButtonTitle:nil destructiveButtonTitle:nil otherButtonTitles:nil];
        for (NSString *buttonTitle in buttonArray)
        {
            [actionSheet addButtonWithTitle:buttonTitle];
        }
        [actionSheet addButtonWithTitle:@"Cancel"];
        [actionSheet setCancelButtonIndex:buttonArray.count];
        UIViewController *controller = [[[[UIApplication sharedApplication] delegate] window] rootViewController];
        [actionSheet showInView:controller.view];
    }
}

- (void)actionSheet:(UIActionSheet *)actionSheet clickedButtonAtIndex:(NSInteger)buttonIndex
{
    if (_ActionSheetBlock)
    {
        _ActionSheetBlock(buttonIndex);
        _ActionSheetBlock = nil;
    }
}

#pragma mark -
#pragma mark === Image Picker ===
#pragma mark -

- (void)imagePickerWithType:(ImagePickerType)pickerType
                  allowEdit:(BOOL)allowEdit
                 completion:(void(^)(id response))block
{
    self.imagePickerType = pickerType;
    if (pickerType == ImagePickerTypeCameraPhotoCapture || pickerType == ImagePickerTypeCameraVideoCapture)
    {
        if (![UIImagePickerController isSourceTypeAvailable:UIImagePickerControllerSourceTypeCamera])
        {
            return;
        }
    }
    _imagePicker = [[UIImagePickerController alloc] init];
    _imagePicker.editing = allowEdit;
    _imagePicker.delegate = self;
    switch (self.imagePickerType)
    {
        case ImagePickerTypeCameraPhotoCapture:
        {
            _imagePicker.sourceType = UIImagePickerControllerSourceTypeCamera;
        }
            break;
        case ImagePickerTypeCameraVideoCapture:
        {
            _imagePicker.sourceType = UIImagePickerControllerSourceTypeCamera;
            [_imagePicker setVideoMaximumDuration:15];
            _imagePicker.mediaTypes = @[(NSString *)kUTTypeMovie];
            _imagePicker.cameraDevice = UIImagePickerControllerCameraDeviceRear;
            _imagePicker.showsCameraControls = YES;
            _imagePicker.navigationBarHidden = YES;
            _imagePicker.toolbarHidden = YES;
            _imagePicker.edgesForExtendedLayout = UIRectEdgeAll;
            _imagePicker.videoQuality = UIImagePickerControllerQualityTypeHigh;
        }
            break;
        case ImagePickerTypePhotoCameraRollOnly:
        {
            _imagePicker.sourceType = UIImagePickerControllerSourceTypePhotoLibrary;
        }
            break;
        case ImagePickerTypeVideoCameraRollOnly:
        {
            _imagePicker.mediaTypes = @[(NSString *)kUTTypeMovie];
            _imagePicker.sourceType = UIImagePickerControllerSourceTypePhotoLibrary;
        }
            break;
        case ImagePickerTypePhotoAlbum:
        {
            _imagePicker.sourceType = UIImagePickerControllerSourceTypeSavedPhotosAlbum;
        }
            break;
        case ImagePickerTypeVideoAlbum:
        {
            _imagePicker.mediaTypes = @[(NSString *)kUTTypeMovie];
            _imagePicker.sourceType = UIImagePickerControllerSourceTypeSavedPhotosAlbum;
        }
            break;
            
        default:
            break;
    }
    
    UIViewController *controller = [[[[UIApplication sharedApplication] delegate] window] rootViewController];
    [controller presentViewController:_imagePicker animated:YES completion:NULL];
}
- (void)imagePickerController:(UIImagePickerController *)picker didFinishPickingMediaWithInfo:(NSDictionary *)info
{
    //TODO: To be handled for all cases
}

#pragma mark -
#pragma mark === Mail Composer ===
#pragma mark -

- (void)mailComposerWithSubject:(NSString *)subject
                    messageBody:(NSString *)messageBody
                     recipients:(NSArray *)recipientsArray
                     completion:(void(^)(BOOL success))block
{
    if (![MFMailComposeViewController canSendMail])
    {
        block(NO);
        return;
    }
    
    _MailMessageComposerBlock = block;
    MFMailComposeViewController *composer = [[MFMailComposeViewController alloc] init];
    [composer setMailComposeDelegate:self];
    [composer setToRecipients:recipientsArray];
    [composer setMessageBody:messageBody isHTML:NO];
    [composer setSubject:subject];
    UIViewController *controller = [[[[UIApplication sharedApplication] delegate] window] rootViewController];
    [controller presentViewController:composer animated:YES completion:NULL];
}

- (void)mailComposeController:(MFMailComposeViewController *)controller
          didFinishWithResult:(MFMailComposeResult)result
                        error:(NSError *)error
{
    [controller dismissViewControllerAnimated:YES completion:NULL];
    BOOL success = NO;
    if (result == MFMailComposeResultSent)
    {
        NSLog(@"Email sent.");
        success = YES;
    }
    if (error)
    {
        NSLog(@"Email sending error = %@",error);
    }
    
    if (_MailMessageComposerBlock)
    {
        _MailMessageComposerBlock(success);
        _MailMessageComposerBlock = nil;
    }
}

#pragma mark -
#pragma mark === Message Composer ===
#pragma mark -

- (void)messageComposerWithSubject:(NSString *)subject
                    messageBody:(NSString *)messageBody
                     recipients:(NSArray *)recipientsArray
                     completion:(void(^)(BOOL success))block
{
    if (![MFMessageComposeViewController canSendText])
    {
        block(NO);
        return;
    }
    
    MFMessageComposeViewController *composer = [[MFMessageComposeViewController alloc] init];
    [composer setMessageComposeDelegate:self];
    [composer setSubject:subject];
    [composer setBody:messageBody];
    [composer setRecipients:recipientsArray];
    UIViewController *controller = [[[[UIApplication sharedApplication] delegate] window] rootViewController];
    [controller presentViewController:composer animated:YES completion:NULL];
}

- (void)messageComposeViewController:(MFMessageComposeViewController *)controller didFinishWithResult:(MessageComposeResult)result
{
    [controller dismissViewControllerAnimated:YES completion:NULL];
    BOOL success = NO;
    if (result == MessageComposeResultSent)
    {
        NSLog(@"Email sent.");
        success = YES;
    }

    if (_MailMessageComposerBlock)
    {
        _MailMessageComposerBlock(success);
        _MailMessageComposerBlock = nil;
    }
}

@end
