//
//  ServerResponseHeaderJsonModel.m
//  TechaHeadBase
//
//  Created by Girijesh Kumar on 18/12/14.
//  Copyright (c) 2014 Girijesh Kumar. All rights reserved.
//

#import "ServerResponseHeaderJsonModel.h"
#import "JSONKeyMapper+AdditionalMethod.h"

@implementation ServerResponseHeaderJsonModel
+(BOOL)propertyIsOptional:(NSString*)propertyName
{
	return YES;
}
+(JSONKeyMapper*)keyMapper
{
	return [JSONKeyMapper mapperFirstLowerCharacter];
}
@end
