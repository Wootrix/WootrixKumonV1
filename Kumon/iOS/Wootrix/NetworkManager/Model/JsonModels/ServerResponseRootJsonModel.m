//
//  ServerResponseRootJsonModel.m
//  TechaHeadBase
//
//  Created by Girijesh Kumar on 18/12/14.
//  Copyright (c) 2014 Girijesh Kumar. All rights reserved.
//

#import "ServerResponseRootJsonModel.h"
#import "JSONKeyMapper+AdditionalMethod.h"

@implementation ServerResponseRootJsonModel

+ (JSONKeyMapper*)keyMapper
{
  return [JSONKeyMapper mapperFirstLowerCharacter];
}

@end
