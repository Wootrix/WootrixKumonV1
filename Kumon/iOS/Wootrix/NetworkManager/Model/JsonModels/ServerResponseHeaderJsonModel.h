//
//  ServerResponseHeaderJsonModel.h
//  TechaHeadBase
//
//  Created by Girijesh Kumar on 18/12/14.
//  Copyright (c) 2014 Girijesh Kumar. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "JSONModel.h"

@interface ServerResponseHeaderJsonModel : JSONModel


@property(nonatomic,assign)NSInteger status;
@property(nonatomic,strong)NSString *message;
@property(nonatomic,strong)NSArray *dataArray;

@end
