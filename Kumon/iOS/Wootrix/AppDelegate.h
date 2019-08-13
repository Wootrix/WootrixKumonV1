//
//  AppDelegate.h
//  Wootrix
//
//  Created by Saurabh Verma on 11/12/14.
//  Copyright (c) 2014 Techahead. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <CoreData/CoreData.h>

//Client : com.pedro.wootrix
//Techahead  :  com.techahead.wootrixDev

@interface AppDelegate : UIResponder <UIApplicationDelegate>

@property (strong, nonatomic) UIWindow *window;

@property (readonly, strong, nonatomic) NSManagedObjectContext *managedObjectContext;
@property (readonly, strong, nonatomic) NSManagedObjectModel *managedObjectModel;
@property (readonly, strong, nonatomic) NSPersistentStoreCoordinator *persistentStoreCoordinator;

@property (strong, nonatomic) UIStoryboard *storyBoard;
@property (strong, nonatomic) UINavigationController *navController;
@property (strong, nonatomic) NSString *latString;
@property (strong, nonatomic) NSString *longString;
@property (strong, nonatomic) NSDictionary *articleAlert;
@property (strong, nonatomic) NSTimer *timer_LocationUpdate;

//test
- (NSDictionary *)methodToLoadArticleForCloseMagazine;

- (void)saveContext;
- (NSURL *)applicationDocumentsDirectory;

- (void)startLocationManager;
- (void)stopLocationManager;

@end

