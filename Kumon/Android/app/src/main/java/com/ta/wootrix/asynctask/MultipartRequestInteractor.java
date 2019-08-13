package com.ta.wootrix.asynctask;

import android.os.AsyncTask;
import android.util.Log;

import org.apache.http.HttpVersion;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.ResponseHandler;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.mime.HttpMultipartMode;
import org.apache.http.entity.mime.MultipartEntity;
import org.apache.http.entity.mime.content.FileBody;
import org.apache.http.entity.mime.content.StringBody;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.params.BasicHttpParams;
import org.apache.http.params.CoreProtocolPNames;
import org.apache.http.params.HttpParams;

import java.io.File;
import java.io.IOException;
import java.util.Map;

public class MultipartRequestInteractor
{
	String						data, url;
	ResponseHandler<Object>		responseHandler;
	private DefaultHttpClient	mHttpClient;
	private Map<String, String>	dataMap;
	private Map<String, File>	fileMap;

	public MultipartRequestInteractor(Map<String, String> mymap, Map<String, File> fileMap2, String url, ResponseHandler responseHandler)
	{
		this.responseHandler = responseHandler;

		this.url = url;
		this.fileMap = fileMap2;
		this.dataMap = mymap;
		HttpParams params = new BasicHttpParams();
		params.setParameter(CoreProtocolPNames.PROTOCOL_VERSION, HttpVersion.HTTP_1_1);
		mHttpClient = new DefaultHttpClient(params);
		// execute task
		new UploadMultipartData().execute();
	}

	private class UploadMultipartData extends AsyncTask<Void, Void, String>
	{

		@Override
		protected void onPreExecute()
		{
			// TODO Auto-generated method stub
			super.onPreExecute();
		}

		@Override
		protected String doInBackground(Void... params)
		{

			try
			{
				HttpPost httppost = new HttpPost(url);

				MultipartEntity multipartEntity = new MultipartEntity(HttpMultipartMode.BROWSER_COMPATIBLE);
				for (Map.Entry<String, String> data : dataMap.entrySet())
				{
					multipartEntity.addPart(data.getKey(), new StringBody(data.getValue()));
				}

				for (Map.Entry<String, File> data : fileMap.entrySet())
				{
					multipartEntity.addPart(data.getKey(), new FileBody(data.getValue()));
				}

				httppost.setEntity(multipartEntity);

				return (String) mHttpClient.execute(httppost, responseHandler);
			}
			catch (Exception e)
			{
				Log.e(MultipartRequestInteractor.class.getName(), e.getLocalizedMessage(), e);

			}
			try
			{
				responseHandler.handleResponse(null);
			}
			catch (ClientProtocolException e1)
			{

				e1.printStackTrace();
			}
			catch (IOException e1)
			{

				e1.printStackTrace();
			}
			finally
			{
				if (mHttpClient != null)
					mHttpClient.getConnectionManager().shutdown();
			}
			return null;
		}

		@Override
		protected void onPostExecute(String result)
		{

			super.onPostExecute(result);

		}

	}

}
