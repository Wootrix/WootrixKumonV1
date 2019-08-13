package com.ta.wootrix.firebase;

import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.media.RingtoneManager;
import android.net.Uri;
import android.support.v4.app.NotificationCompat;
import android.util.Log;

import com.google.firebase.messaging.FirebaseMessagingService;
import com.google.firebase.messaging.RemoteMessage;
import com.ta.wootrix.R;

/**
 * Created by CIPL0424 on 8/5/2016.
 */
public class MyFireBaseMessage extends FirebaseMessagingService {

    private static final String TAG = "Kumon";

    String stringCurrentDate = null, messageBody = null, article_id = null, url = null, messageType = null;

    String allowShare = null, fullSource = null, allowComment = null, embedded_video = null, title = null,
            createdBy = null, articleDesc = null, articleVideoUrl = null, coverPhotoUrl = null, articleType = null, artArticle_id = null,close_subType = null;

    @Override
    public void onMessageReceived(RemoteMessage remoteMessage) {
        super.onMessageReceived(remoteMessage);

        stringCurrentDate= String.valueOf(System.currentTimeMillis());
        stringCurrentDate = stringCurrentDate.replace("+","");
        stringCurrentDate = stringCurrentDate.replace("-","");
        messageBody = remoteMessage.getData().get("contents");
        article_id = remoteMessage.getData().get("article_id");
        url = remoteMessage.getData().get("url");
        messageType = remoteMessage.getData().get("messagetype");
        if (messageType.equals("article")) {
            allowShare = remoteMessage.getData().get("allowShare");
            fullSource = remoteMessage.getData().get("fullSoruce");
            allowComment = remoteMessage.getData().get("allowComment");
            embedded_video = remoteMessage.getData().get("embedded_video");
            title = remoteMessage.getData().get("title");
            createdBy = remoteMessage.getData().get("createdBy");
            articleDesc = remoteMessage.getData().get("articleDesc");
            articleVideoUrl = remoteMessage.getData().get("articleVideoUrl");
            coverPhotoUrl = remoteMessage.getData().get("coverPhotoUrl");
            articleType = remoteMessage.getData().get("articleType");
            artArticle_id = remoteMessage.getData().get("article_id");
        }
        else if(messageType.equals("closemagazine"))
        {
            close_subType = remoteMessage.getData().get("messagetype_sub");
            if(close_subType.equals("close_advertisement"))
            {
                allowShare = remoteMessage.getData().get("allowShare");
                fullSource = remoteMessage.getData().get("url");
                allowComment = remoteMessage.getData().get("allowComment");
                embedded_video = remoteMessage.getData().get("embedded_video");
                title = remoteMessage.getData().get("title");
                createdBy = remoteMessage.getData().get("createdBy");
                articleDesc = remoteMessage.getData().get("articleDesc");
                articleVideoUrl = remoteMessage.getData().get("articleVideoUrl");
                coverPhotoUrl = remoteMessage.getData().get("coverPhotoUrl");
                articleType = remoteMessage.getData().get("articleType");
                artArticle_id = remoteMessage.getData().get("article_id");
            }
            else if (close_subType.equals("close_article"))
            {
                allowShare = remoteMessage.getData().get("allowShare");
                fullSource = remoteMessage.getData().get("url");
                allowComment = remoteMessage.getData().get("allowComment");
                embedded_video = remoteMessage.getData().get("embedded_video");
                title = remoteMessage.getData().get("title");
                createdBy = remoteMessage.getData().get("createdBy");
                articleDesc = remoteMessage.getData().get("articleDesc");
                articleVideoUrl = remoteMessage.getData().get("articleVideoUrl");
                coverPhotoUrl = remoteMessage.getData().get("coverPhotoUrl");
                articleType = remoteMessage.getData().get("articleType");
                artArticle_id = remoteMessage.getData().get("article_id");
            }
            else {
                allowShare = remoteMessage.getData().get("allowShare");
                fullSource = remoteMessage.getData().get("fullSoruce");
                allowComment = remoteMessage.getData().get("allowComment");
                embedded_video = remoteMessage.getData().get("embedded_video");
                title = remoteMessage.getData().get("title");
                createdBy = remoteMessage.getData().get("createdBy");
                articleDesc = remoteMessage.getData().get("articleDesc");
                articleVideoUrl = remoteMessage.getData().get("articleVideoUrl");
                coverPhotoUrl = remoteMessage.getData().get("coverPhotoUrl");
                articleType = remoteMessage.getData().get("articleType");
                artArticle_id = remoteMessage.getData().get("article_id");
            }
        }

        Log.d("String Time",stringCurrentDate);
        Log.d("MessageType",messageType);
        sendNotification(TAG, messageBody, article_id, url, messageType, allowShare, fullSource, allowComment, embedded_video, title, createdBy, articleDesc
                , articleVideoUrl, coverPhotoUrl, articleType, artArticle_id);
    }

    private void sendNotification(String title, String messageBody, String article_id, String url, String messageType,
                                  String allowShare, String fullSource, String allowComment, String embedded_video, String artiTitle,
                                  String createdBy, String articleDesc, String articleVideoUrl, String coverPhotoUrl,
                                  String articleType, String artArticle_id) {

        Intent intent = new Intent(this, com.ta.wootrix.phone.SplashActivity.class);
        intent.putExtra("contents", messageBody);
        intent.putExtra("article_id", article_id);
        intent.putExtra("url", url);
        intent.putExtra("messagetype", messageType);

        if (messageType.equals("article")) {
            intent.putExtra("allowShare", allowShare);
            intent.putExtra("fullSoruce", fullSource);
            intent.putExtra("allowComment", allowComment);
            intent.putExtra("embedded_video", embedded_video);
            intent.putExtra("title", artiTitle);
            intent.putExtra("createdBy", createdBy);
            intent.putExtra("articleDesc", articleDesc);
            intent.putExtra("articleVideoUrl", articleVideoUrl);
            intent.putExtra("coverPhotoUrl", coverPhotoUrl);
            intent.putExtra("articleType", articleType);
            intent.putExtra("article_id", artArticle_id);
        }
        else if(messageType.equals("closemagazine"))
        {
            intent.putExtra("allowShare", allowShare);
            intent.putExtra("fullSoruce", fullSource);
            intent.putExtra("allowComment", allowComment);
            intent.putExtra("embedded_video", embedded_video);
            intent.putExtra("title", artiTitle);
            intent.putExtra("createdBy", createdBy);
            intent.putExtra("articleDesc", articleDesc);
            intent.putExtra("articleVideoUrl", articleVideoUrl);
            intent.putExtra("coverPhotoUrl", coverPhotoUrl);
            intent.putExtra("articleType", articleType);
            intent.putExtra("article_id", artArticle_id);
            intent.putExtra("close_subtype", close_subType);
        }

        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);

        PendingIntent pendingIntent = PendingIntent.getActivity(this, (int) (Long.parseLong(stringCurrentDate)+1), intent, PendingIntent.FLAG_ONE_SHOT);

        Uri defaultSoundUri = RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);
        NotificationCompat.Builder notificationBuilder = new NotificationCompat.Builder(this)
                .setSmallIcon(seticon())
                .setContentTitle(title)
                .setContentText(messageBody)
                .setAutoCancel(true)
                .setColor(Color.WHITE)
                .setSound(defaultSoundUri)
                .setContentIntent(pendingIntent);

        NotificationManager notificationManager = (NotificationManager) getSystemService(Context.NOTIFICATION_SERVICE);
        notificationManager.notify((int) (Long.parseLong(stringCurrentDate)+1), notificationBuilder.build());
    }

    private int seticon() {
        if (android.os.Build.VERSION.SDK_INT >= android.os.Build.VERSION_CODES.LOLLIPOP) {
            return R.drawable.push;
        } else {
            return R.drawable.app_icon;
        }
    }
}