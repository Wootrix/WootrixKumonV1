package com.ta.wootrix.parser;

import android.content.Context;

import com.ta.wootrix.modle.IModel;
import com.ta.wootrix.modle.TopicsModle;
import com.ta.wootrix.utils.Constants;
import com.ta.wootrix.utils.Utility;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.Collection;
import java.util.List;

public class TopicsParser implements Parser<IModel>
{

	private Context context;

	public TopicsParser(Context settingActivity)
	{
		this.context = settingActivity;
	}

	@Override
	public IModel parse(JSONObject json) throws JSONException
	{
		// TODO Auto-generated method stub
		return null;
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
		ArrayList<IModel> list = new ArrayList<IModel>();
		/*
		 * { "message": "", "data": [ { "topics": [ { "topicId": "", "topicTitle": "" }, {
		 * "topicId": "", "topicTitle": "" } ] } ], "statusCode": "" }
		 */
		JSONObject obj = new JSONObject(resp);
		if (obj.getBoolean("success") && obj.getJSONArray("data").length() > 0)
		{
			JSONObject mainTopicsJson = obj.getJSONArray("data").getJSONObject(0);

			JSONArray topicArray = mainTopicsJson.getJSONArray("topics");
			List<String> selctedTopics = new ArrayList<String>(Arrays.asList(Utility.getSharedPrefStringData(context, Constants.USER_TOPICS).split(",")));
			if (topicArray != null && topicArray.length() > 0)
			{
				for (int i = 0; i < topicArray.length(); i++)
				{
					JSONObject answerResponse = topicArray.getJSONObject(i);
					TopicsModle topics = new TopicsModle();
					topics.setTopicID(answerResponse.optString("topicId"));
					topics.setTopicName(answerResponse.optString("topicTitle"));
					if (selctedTopics.contains(answerResponse.optString("topicId")))
						topics.setTopicSelected(true);
					else
						topics.setTopicSelected(false);
					list.add(topics);
				}
			}
			return list;
		}
		else
		{
			com.ta.wootrix.modle.Error error = new com.ta.wootrix.modle.Error();
			error.setError(obj.getString("message"));
			list.add(error);
			return list;
		}
	}
}