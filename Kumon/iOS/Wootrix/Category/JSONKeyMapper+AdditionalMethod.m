//
//  JSONKeyMapper+AdditionalMethod.m
//  TechaHeadBase
//
//  Created by Girijesh Kumar on 18/12/14.
//  Copyright (c) 2014 Girijesh Kumar. All rights reserved.
//

#import "JSONKeyMapper+AdditionalMethod.h"

@implementation JSONKeyMapper (AdditionalMethod)

+(instancetype)mapperFirstLowerCharacter
{
    JSONModelKeyMapBlock toModel = ^ NSString* (NSString* keyName) {
        //        NSString*lowercaseString = [keyName lowercaseString];
        
        return keyName;
    };
    
    JSONModelKeyMapBlock toJSON = ^ NSString* (NSString* keyName) {
        
    
        NSString *first = [keyName substringWithRange:NSMakeRange(0,1)];
        first = [first uppercaseString];
        NSString *uppercaseString = [first stringByAppendingString:[keyName substringWithRange:NSMakeRange(1,[keyName length]-1)]];
        return uppercaseString;
    };
    
    return [[self alloc] initWithJSONToModelBlock:toModel
                                 modelToJSONBlock:toJSON];
    
}

@end
