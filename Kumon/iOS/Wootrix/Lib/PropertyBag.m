//
//  PropertyBag.m
//  Controly
//
//  Created by Factory Technologies on 12/23/14.
//  Copyright (c) 2014 Moonlighters. All rights reserved.
//

#import "PropertyBag.h"

/**
 used as a cache and global access to variables
 */
@implementation PropertyBag
{
    NSMutableDictionary *_bag;
}

//instance bag
static PropertyBag *instance;

/**
 Implemented by subclasses to initialize a new object (the receiver) immediately after memory for it has been allocated.
 */
- (instancetype)init
{
    self = [super init];
    
    if (self)
    {
        _bag = [[NSMutableDictionary alloc]init];
    }
    return self;
}

//singleton instance
+(PropertyBag *)Instance
{
    if (!instance)
        instance = [[PropertyBag alloc] init];
    
    return instance;
}

//add object to the bag
-(void)Add:(NSObject *)obj forKey:(NSString *)key
{
    [_bag setObject:obj forKey:key];
}

//get an object from the bag
-(NSObject *)Get:(NSString *)key
{
    return ([_bag objectForKey:key]);
}

-(void) Flush
{
    _bag = [[NSMutableDictionary alloc]init];
}

@end
