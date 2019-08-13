//
//  HCGoogleDelegate.m
//  iHealthConnect
//
//  Created by Rahul Jain on 16/10/14.
//  Copyright (c) 2014 TechAhead. All rights reserved.
//

#import "HCGoogleDelegate.h"

@implementation HCGoogleDelegate


- (BOOL)openURL:(NSURL*)url
{
    if ([[url absoluteString] hasPrefix:@"googlechrome-x-callback:"]||[[url absoluteString] hasPrefix:@"com.google.gppconsent"])
    {
        return NO;
    }
    else if ([[url absoluteString] hasPrefix:@"https://accounts.google.com/o/oauth2/auth"])
    {
        [[NSNotificationCenter defaultCenter] postNotificationName:kApplicationOpenGoogleAuthNotification object:url];
        return NO;
    }//
    
    return [super openURL:url];
}

@end
