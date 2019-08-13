package com.ta.wootrix.parser;

import com.ta.wootrix.firebase.StorePreference;
import com.ta.wootrix.modle.IModel;
import com.ta.wootrix.modle.ResponseMsgInfo;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.Collection;

public class MessageParser implements Parser<IModel>
{
	@Override
	public IModel parse(JSONObject json) throws JSONException
	{
		/*
		 * { "Success": true, "Error": 2, "Message": "sample string 3", "Result": { "UserId":
		 * "sample string 1" } }
		 */
		// {"Success":false,"Error":0,"Message":"Invalid User.","Result":{"UserId":""}}

		boolean success = json.optBoolean("success");

		if (success)
		{
			ResponseMsgInfo responseMsg = new ResponseMsgInfo();

			responseMsg.setMessage(json.getString("message"));

			return responseMsg;
		}
		else
		{

			com.ta.wootrix.modle.Error error = new com.ta.wootrix.modle.Error();
			error.setError(json.optString("message"));
			return error;
		}

	}

	@Override
	public Collection<IModel> parse(JSONArray array) throws JSONException
	{
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public ArrayList<IModel> parse(String resp) throws JSONException
	{
		// TODO Auto-generated method stub
		return null;
	}

}
