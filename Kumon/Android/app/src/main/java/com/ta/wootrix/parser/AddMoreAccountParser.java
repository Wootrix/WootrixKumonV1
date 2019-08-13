package com.ta.wootrix.parser;

import com.ta.wootrix.modle.AddMoreAccountModle;
import com.ta.wootrix.modle.IModel;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.Collection;

public class AddMoreAccountParser implements Parser<IModel>
{
	@Override
	public IModel parse(JSONObject json) throws JSONException
	{
		boolean success = json.optBoolean("success");
		AddMoreAccountModle model = new AddMoreAccountModle();
		model.setMsg(json.optString("message"));
		model.setMsgId(json.optString("id"));
		if (success)
		{
			JSONObject mainObject = json.optJSONObject("data");
			if (mainObject != null)
			{
				model.setFB(mainObject.optBoolean("is_facebook"));
				model.setLN(mainObject.optBoolean("is_linkedin"));
				model.setTW(mainObject.optBoolean("is_twitter"));
				model.setGP(mainObject.optBoolean("is_google"));
			}

			return model;
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
		return null;
	}

	@Override
	public ArrayList<IModel> parse(String resp) throws JSONException
	{
		return null;
	}

}
