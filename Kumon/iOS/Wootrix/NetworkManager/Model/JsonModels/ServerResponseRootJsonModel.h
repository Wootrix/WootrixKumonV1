//
//  ServerResponseRootJsonModel.h
//  TechaHeadBase
//
//  Created by Girijesh Kumar on 18/12/14.
//  Copyright (c) 2014 Girijesh Kumar. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "JSONModel.h"
#import "ServerResponseHeaderJsonModel.h"

@interface ServerResponseRootJsonModel : JSONModel

@property (nonatomic, strong) ServerResponseHeaderJsonModel *serverReplyHeader;
@end
