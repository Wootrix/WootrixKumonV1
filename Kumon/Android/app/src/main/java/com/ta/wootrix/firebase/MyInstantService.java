package com.ta.wootrix.firebase;

import android.util.Log;

import com.google.firebase.iid.FirebaseInstanceId;
import com.google.firebase.iid.FirebaseInstanceIdService;

import java.io.IOException;

import okhttp3.FormBody;
import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.RequestBody;

/**
 * Created by CIPL0424 on 8/8/2016.
 */
public class MyInstantService extends FirebaseInstanceIdService
{

	private static final String TAG = "FireBaseIdSercvice";

	@Override
	public void onTokenRefresh()
	{
		String refreshedToken = FirebaseInstanceId.getInstance().getToken();
		Log.d(TAG, "Refreshed token: " + refreshedToken);

		// TODO: Implement this method to send any registration to your app's servers.
		sendRegistrationToServer(refreshedToken);
	}

	private void sendRegistrationToServer(String token)
	{
		OkHttpClient okHttpClient = new OkHttpClient();
		RequestBody body = new FormBody.Builder().add("deviceID", token).build();
		Request request = new Request.Builder().url("http://cipldev.com/wootrix/index.php/webservices/Device_token/deviceToken").post(body).build();
		try
		{
			okHttpClient.newCall(request).execute();
		}
		catch (IOException e)
		{
			e.printStackTrace();
		}
	}
}
