package com.ta.wootrix.restapi;

import android.app.IntentService;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;

import com.ta.wootrix.utils.Utility;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

public class RestService extends IntentService
{
	public static final String		URL_KEY	= "url", DATA_MAP_KEY = "data", FILE_MAP_KEY = "file", RESULT_RECIEVER = "receiver";
	public static String			response;
	private HashMap<String, Object>	keyValue;
	private String					url;

	public RestService()
	{
		super("test");
	}

	public RestService(String name)
	{
		super("test");
	}

	@Override
	protected void onHandleIntent(Intent intent)
	{

		if (intent != null)
		{
			Bundle bundle = intent.getExtras();
			keyValue = bundle.containsKey(DATA_MAP_KEY) ? (HashMap<String, Object>) bundle.get(DATA_MAP_KEY) : null;
			url = bundle.containsKey(URL_KEY) ? (String) bundle.get(URL_KEY) : null;

			if (Utility.isNetworkAvailable(this) && url != null && keyValue != null)
			{

				response = Utility.httpPostRequestToServer(APIUtils.getBaseURL(url), getJSONParams(keyValue), null);
				if (response != null)
					Log.e("response= ", response);
			}
			else
			{
				// Utility.showNetworkNotAvailToast(this);
			}

		}

	}

	public String getJSONParams(HashMap<String, Object> paramMap)
	{
		if (paramMap == null)
		{
			return null;
		}
		JSONObject obj = new JSONObject();
		for (Map.Entry<String, Object> entry : paramMap.entrySet())
		{
			try
			{
				if (entry.getKey().equalsIgnoreCase("Emails"))
				{
					String[] emailArray = (String[]) entry.getValue();
					JSONArray emailJsonArray = new JSONArray();
					for (int i = 0; i < emailArray.length; i++)
					{
						emailJsonArray.put(emailArray[i]);
					}
					obj.put(entry.getKey(), emailJsonArray);
				}
				else
				{
					obj.put(entry.getKey(), entry.getValue());
				}

			}
			catch (JSONException e)
			{
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		}
		return obj.toString();
	}

	// public void createNotifications(String msg)
	// {
	// Notification notification = new
	// NotificationCompat.Builder(this).setContentTitle(getString(R.string.app_name)).setContentText(msg).setSmallIcon(R.drawable.ic_launcher)
	// .setTicker(getString(R.string.app_name)).setAutoCancel(true).build();
	//
	// notification.defaults |= Notification.DEFAULT_SOUND;
	// // long[] vibrate = { 0, 100, 200, 300 };
	// // notification.vibrate = vibrate;
	// // Intent pushIntent = new Intent();
	// Intent pushIntent = new Intent();
	// pushIntent.putExtra("notification", "notification");
	// pushIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
	// PendingIntent contentIntent = PendingIntent.getActivity(this, 0, pushIntent, 0);
	//
	// notification.contentIntent = contentIntent;
	// notification.defaults |= Notification.DEFAULT_LIGHTS;
	//
	// NotificationManager notificationManager = (NotificationManager)
	// getSystemService(NOTIFICATION_SERVICE);
	// notificationManager.notify(12, notification);
	// }

}
