package com.ta.wootrix.asynctask;

import android.content.Context;
import android.util.Log;

import com.ta.wootrix.R;
import com.ta.wootrix.modle.IModel;
import com.ta.wootrix.parser.ArticesParser;
import com.ta.wootrix.parser.CommentParser;
import com.ta.wootrix.parser.CustomAdvParser;
import com.ta.wootrix.parser.MagazineParser;
import com.ta.wootrix.parser.Parser;
import com.ta.wootrix.parser.TopicsParser;
import com.ta.wootrix.restapi.APIUtils.REQUEST_TYPE;
import com.ta.wootrix.utils.Utility;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.ParseException;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.ResponseHandler;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.mime.HttpMultipartMode;
import org.apache.http.entity.mime.MultipartEntity;
import org.apache.http.entity.mime.content.FileBody;
import org.apache.http.entity.mime.content.StringBody;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.util.EntityUtils;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;
import java.util.ArrayList;
import java.util.HashMap;

public class ServerIntractorAsync extends BaseAsynkTask
{

	public static final int				PREFRENCES		= 0, ACCCOUNTS_DETAIL = 1;
	// variable to check whether it can write object in file or not
	int									canWriteFile	= -1;
	private Context						mCtx;
	private MultipartResponseHandler	mRespHandler;
	private String						mResponse		= null;
	private String						mMsg			= "";
	// global variable stores response object
	private IModel						model;
	private ArrayList<IModel>			modelList;
	private boolean						saveInFref		= false;
	private int							requestFor;

	public ServerIntractorAsync(Context context, String dialogMessage, boolean showDialog, String url, JSONObject params, HashMap<String, File> fileList, REQUEST_TYPE requestType, IAsyncCaller caller,
			@SuppressWarnings("rawtypes") Parser parser)
	{
		super(context, dialogMessage, showDialog, url, params, fileList, requestType, caller, parser);
		this.mCtx = context;
		mRespHandler = new MultipartResponseHandler();
	}

	public ServerIntractorAsync(Context context, String dialogMessage, boolean showDialog, String url, JSONObject params, REQUEST_TYPE requestType, IAsyncCaller caller, Parser parser)
	{
		super(context, dialogMessage, showDialog, url, params, requestType, caller, parser);
		this.mCtx = context;
	}

	public boolean isSaveInFref()
	{
		return saveInFref;
	}

	public void setSaveInFref(boolean saveInFref)
	{
		this.saveInFref = saveInFref;
	}

	public int getRequestFor()
	{
		return requestFor;
	}

	public void setRequestFor(int requestFor)
	{
		this.requestFor = requestFor;
	}

	public void setCanWrite(int canWrite)
	{
		this.canWriteFile = canWrite;
	}

	@Override
	public Integer doInBackground(Void... params)
	{

		if (!Utility.isNetworkAvailable(mCtx))
		{
			return 0;
		}

		switch ( mRequestType )
		{
			case GET :
				mResponse = Utility.httpGetRequest("", mUrl);
				Log.e("mResponse", mResponse + "@");
				return parseGetPostResposnse(mResponse);

			case POST :

				if (mParams != null)
				{
					Log.e("urls", mUrl.toString());
					Log.e("params", mParams.toString());
					mResponse = Utility.httpPostRequestToServer(mUrl, mParams, mContext);
				}
				else
				{
					Log.e("url", mUrl);
					mResponse = Utility.httpPostRequestToServer(mUrl, "", mContext);
				}
				Log.e("mResponse", mResponse + "@");

				return parseGetPostResposnse(mResponse);

			case MULTIPART_GET :
				break;
			case MULTIPART_POST :
				Log.e("url", mUrl.toString());
				if (mParams != null)
					Log.e("parms", mParams.toString());
				try
				{
					HttpClient client = new DefaultHttpClient();
					HttpPost post = new HttpPost(mUrl);

					// FileBody fileBody = new FileBody(f, fileName, "image/jpeg",
					// null);

					MultipartEntity reqEntity = new MultipartEntity(HttpMultipartMode.BROWSER_COMPATIBLE);

					reqEntity.addPart("json", new StringBody(mParams.toString()));

					if (!mFileMap.isEmpty())
					{
						if (mFileMap.containsKey("image"))
						{
							FileBody fileBody = new FileBody(mFileMap.get("image"));
							/*
							 * FileBody fileBody =new FileBody(mFileMap.get("image"), "image",
							 * "image/*", null);
							 */
							Log.e("image", mFileMap.get("image").toString());
							reqEntity.addPart("image", fileBody);
							Log.e("image", mFileMap.get("image").toString());

						}

						if (mFileMap.containsKey("video"))
						{
							FileBody fileBody = new FileBody(mFileMap.get("video"));
							Log.e("video", mFileMap.get("video").toString());
							reqEntity.addPart("video", fileBody);
						}
						if (mFileMap.containsKey("videoThumbImage"))
						{
							FileBody fileBody = new FileBody(mFileMap.get("videoThumbImage"));

							reqEntity.addPart("videoThumbImage", fileBody);
						}
					}

					post.setEntity(reqEntity);
					HttpResponse response = client.execute(post);
					HttpEntity resEntity = response.getEntity();

					// if (resEntity != null)
					// {
					// mResponse = EntityUtils.toString(resEntity);
					// Log.e(" server response ", mResponse.toString());
					// return parseGetPostResposnse(mResponse);
					// }

					if (resEntity != null)
					{
						String responseEntity = EntityUtils.toString(response.getEntity());
						responseEntity = responseEntity.trim();
						Log.w("server resp:", responseEntity);
						Log.e("server resp:", responseEntity);
						if (responseEntity != null && !responseEntity.equalsIgnoreCase(""))
							return parseGetPostResposnse(responseEntity);
						else
							return parseGetPostResposnse("{ \"Success\": false,\"Message\": \"" + mContext.getString(R.string.server_ntwrk_error) + "\",\"Result\": []}");
					}
					// else
					// return parseGetPostResposnse("{ \"Success\": false,\"Message\": \"Network
					// error Occoured.\",\"Result\": []}");
				}
				catch (Exception e)
				{
					e.printStackTrace();
					return parseGetPostResposnse("{ \"Success\": false,\"Message\": \"" + mContext.getString(R.string.server_ntwrk_error) + "\",\"Result\": []}");
				}

				break;

			default :
				break;
		}

		return -1;
	}

	@Override
	protected void onPostExecute(Integer result)
	{
		super.onPostExecute(result);
		if (!isCancelled() && result == 1)
		{
			if (saveInFref)
			{
				switch ( requestFor )
				{
					/*
					 * case PREFRENCES: if (modelList != null && !(modelList.get(0) instanceof
					 * Error)) Utility.setSharedPrefStringData(mContext,
					 * Constants.SHARED_PREF_MUSIC_PREFERENCES, mResponse); break; default: break;
					 */
				}
			}

			if (model != null)
			{
				caller.onComplete(model, mMsg, true);
			}
			else
			{
				if (modelList != null)
				{
					if (canWriteFile == 1 && (modelList.get(0)) instanceof IModel)
					{
						try
						{
							File f = new File(mCtx.getCacheDir(), "MyFeed.txt");
							if (!f.exists())
								f.createNewFile();

							FileOutputStream fOut = new FileOutputStream(f);
							byte[] rs = mResponse.getBytes();
							fOut.write(rs);
						}
						catch (FileNotFoundException e)
						{
							// TODO Auto-generated catch block
							e.printStackTrace();
						}
						catch (IOException e)
						{
							// TODO Auto-generated catch block
							e.printStackTrace();
						}

					}

					caller.onComplete(modelList, mMsg, true);
				}
				else
				{
					Utility.showToastMessage(mContext, mContext.getString(R.string.service_failed));
				}
			}
		}
		else if (result == 0)
		{
			Utility.showNetworkNotAvailToast(mCtx);
		}
		else
		{
			Utility.showToastMessage(mContext, mContext.getString(R.string.service_failed));
		}
	}

	@SuppressWarnings("unchecked")
	private int parseGetPostResposnse(String response)
	{
		if (response != null)
		{
			try
			{

				if (parser instanceof TopicsParser || parser instanceof MagazineParser || parser instanceof CommentParser || parser instanceof ArticesParser || parser instanceof CustomAdvParser)
				{
					modelList = parser.parse(response);
				}
				else
				{
					model = parser.parse(new JSONObject(response));
				}
				return 1;
			}
			catch (JSONException e1)
			{
				e1.printStackTrace();
			}
		}
		return -1;
	}

	private int parseAndUpdateObject(String response)
	{
		if (response == null)
			return -1;
		else
		{
			try
			{
				model = parser.parse(new JSONObject(response));
				return 1;
			}
			catch (JSONException e)
			{
				return -1;

			}
		}
	}

	private class MultipartResponseHandler implements ResponseHandler<Object>
	{
		@Override
		public Object handleResponse(HttpResponse response) throws ClientProtocolException, IOException
		{
			HttpEntity r_entity = response.getEntity();
			String responseString = "";
			try
			{
				responseString = EntityUtils.toString(r_entity);
			}
			catch (ParseException p)
			{
				p.printStackTrace();
			}
			Log.e(getClass().getSimpleName(), "resp" + responseString + response);
			return null;
		}

	}
}
