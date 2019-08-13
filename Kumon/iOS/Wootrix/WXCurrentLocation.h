//
//  WXCurrentLocation.h
//  Wootrix
//
//  Created by Nazma on 2/1/17.
//  Copyright © 2017 Techahead. All rights reserved.
//

#import <JSONModel/JSONModel.h>

@interface WXCurrentLocation : JSONModel
@property (nonatomic, strong) NSString *userid;
@property (nonatomic, strong) NSString *latitude;
@property (nonatomic, strong) NSString *longitude;
@end
