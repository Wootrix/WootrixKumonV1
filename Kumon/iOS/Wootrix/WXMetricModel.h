//
//  WXMetricModel.h
//  Wootrix
//
//  Created by Factory Technologies on 08/03/17.
//  Copyright © 2017 Moonlighters. All rights reserved.
//

#import "JSONModel.h"

/*!
 model used on the event to collect article usage
 */
@interface WXMetricModel : JSONModel

//properties
@property (nonatomic, strong) NSString *id_magazine;
@property (nonatomic, strong) NSString *id_article;
@property (nonatomic, strong) NSString *date_access;
@property (nonatomic, strong) NSString *token;
@property (nonatomic, strong) NSString *so_access;
@property (nonatomic, strong) NSString *type_device_access;
@property (nonatomic, strong) NSString *latitude;
@property (nonatomic, strong) NSString *longitude;

@end
