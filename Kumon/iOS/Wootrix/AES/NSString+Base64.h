//
//  NSString+Base64.h
//
//
//  Created by 

#import <Foundation/NSString.h>

@interface NSString (Base64Additions)

+ (NSString *)base64StringFromData:(NSData *)data length:(NSUInteger)length;

@end
