package com.ta.wootrix.asynctask;

import android.content.Context;
import android.os.AsyncTask;
import android.util.Log;

import com.ta.wootrix.customDialog.CustomProgressDialog;
import com.ta.wootrix.parser.Parser;
import com.ta.wootrix.restapi.APIUtils.REQUEST_TYPE;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.File;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

/**
 * @author deepchand BaseAsynk abstract class to handle progress dialog during async call.
 */
public abstract class BaseAsynkTask extends AsyncTask<Void, Void, Integer> implements IGetPostRequest
{
	protected CustomProgressDialog	mDialog;
	protected Context				mContext;
	protected String				mDialogMessage, mApiMessage;
	protected boolean				mShowDialog;
	// protected HashMap<String, String> mParams;

	protected JSONObject			mParams;
	protected REQUEST_TYPE			mRequestType;
	protected IAsyncCaller			caller;
	protected String				mUrl;
	@SuppressWarnings("rawtypes")
	protected Parser				parser;
	protected HashMap<String, File>	mFileMap;

		/*
	 * use for image/file and string params request type must be either multipart_get or
	 * multipart_post
	 */
	public BaseAsynkTask(Context context, String dialogMessage, boolean showDialog, String url, JSONObject params, HashMap<String, File> fileList, REQUEST_TYPE requestType, IAsyncCaller caller,
			Parser parser)
	{
		this.mContext = context;
		this.mDialogMessage = dialogMessage;
		mShowDialog = showDialog;
		mParams = params;
		mRequestType = requestType;
		this.caller = caller;
		this.mUrl = url;
		this.parser = parser;
		this.mFileMap = fileList;
	};

	/*
	 * use for only string params request type must be either multipart_get or multipart_post
	 */
	public BaseAsynkTask(Context context, String dialogMessage, boolean showDialog, String url, JSONObject params, REQUEST_TYPE requestType, IAsyncCaller caller, Parser parser)
	{
		this.mContext = context;
		this.mDialogMessage = dialogMessage;
		mShowDialog = showDialog;
		mParams = params;
		mRequestType = requestType;
		this.caller = caller;
		this.mUrl = url;
		this.parser = parser;
	}

	@Override
	protected void onPreExecute()
	{

		super.onPreExecute();
		if (mShowDialog)
		{
			mDialog = new CustomProgressDialog(mContext, mDialogMessage, this);
		}

	}

	public abstract Integer doInBackground(Void... params);

	@Override
	protected void onPostExecute(Integer result)
	{

		super.onPostExecute(result);
		try
		{
			if (mDialog != null)
			{
				mDialog.dismissDialog();
			}
		}
		catch (final IllegalArgumentException e)
		{
		}
		catch (final Exception e)
		{
		}
		finally
		{
			this.mDialog = null;
		}

	}

	@Override
	public String getURL(String url, HashMap<String, String> paramMap)
	{
		StringBuilder sb = new StringBuilder().append(url);
		boolean first = true;
		if (paramMap != null && paramMap.size() > 0)
		{
			sb.append("?");
			for (Map.Entry<String, String> entry : paramMap.entrySet())
			{
				if (first)
				{
					sb.append(entry.getKey()).append("=").append(entry.getValue());
					first = false;
				}
				else
				{
					sb.append("&").append(entry.getKey()).append("=").append(entry.getValue());
				}

			}
		}
		return sb.toString();
	}

	@Override
	public List<NameValuePair> getParams(HashMap<String, String> paramMap)
	{
		if (paramMap == null)
		{
			return null;
		}
		List<NameValuePair> paramsList = new ArrayList<NameValuePair>();

		for (Map.Entry<String, String> entry : paramMap.entrySet())
		{
			paramsList.add(new BasicNameValuePair(entry.getKey(), entry.getValue()));
		}
		return paramsList;
	}

	@Override
	public String getJSONParams(HashMap<String, String> paramMap)
	{
		if (paramMap == null)
		{
			return null;
		}
		JSONArray jsonArray = new JSONArray();
		JSONObject obj = new JSONObject();
		for (Map.Entry<String, String> entry : paramMap.entrySet())
		{
			try
			{
				obj.put(entry.getKey(), entry.getValue());
			}
			catch (JSONException e)
			{
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		}
		jsonArray.put(obj);
		Log.e("request param", jsonArray.toString());
		return jsonArray.toString();
	}

	private HashMap<String, String> createHashMap(String userId)
	{

		HashMap<String, String> map = new HashMap<String, String>();
		map.put("UserID", userId);
		return map;
	}

public static enum STATUS
	{
		OK, ERROR
	}

}
