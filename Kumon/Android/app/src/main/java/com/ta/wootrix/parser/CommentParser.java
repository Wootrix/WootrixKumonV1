package com.ta.wootrix.parser;

import com.ta.wootrix.modle.CommentsModle;
import com.ta.wootrix.modle.IModel;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.Collection;

public class CommentParser implements Parser<IModel>
{

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
		// {
		// "message": "",
		// "data": [
		// {
		// "comments": [
		// {
		// "name": "",
		// "photoUrl": "",
		// "comment": "",
		// "commentDate": ""
		// },
		// {
		// "name": "",
		// "photoUrl": "",
		// "comment": "",
		// "commentDate": ""
		// }
		// ]
		// }
		// ],
		// "statusCode": ""
		// }
		JSONObject obj = new JSONObject(resp);
		if (obj.getBoolean("success") && obj.getJSONArray("data").length() > 0)
		{
			JSONObject mainTopicsJson = obj.getJSONArray("data").getJSONObject(0);

			JSONArray topicArray = mainTopicsJson.getJSONArray("comments");
			if (topicArray != null && topicArray.length() > 0)
			{
				for (int i = 0; i < topicArray.length(); i++)
				{
					JSONObject answerResponse = topicArray.getJSONObject(i);
					CommentsModle topics = new CommentsModle();
					topics.setComment(answerResponse.optString("comment"));
					topics.setCommentDate(answerResponse.optString("commentDate"));
					topics.setPhotoURL(answerResponse.optString("photoUrl"));
					topics.setCommenterName(answerResponse.optString("name"));

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
