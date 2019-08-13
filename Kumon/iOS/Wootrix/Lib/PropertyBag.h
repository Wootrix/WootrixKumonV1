//
//  PropertyBag.h
//  Controly
//
//  Created by Factory Technologies on 12/23/14.
//  Copyright (c) 2014 Moonlighters. All rights reserved.
//

#import <Foundation/Foundation.h>


/**
 used as a cache and global access to variables
 */
@interface PropertyBag : NSObject

//singleton instance
+(PropertyBag *)Instance;
//add object to the bag
-(void)Add:(NSObject *)obj forKey:(NSString *)key;
//get an object from the bag
-(NSObject *)Get:(NSString *)key;

-(void) Flush;
@end
