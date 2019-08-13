package com.ta.wootrix.parser;

import android.text.TextUtils;

import com.ta.wootrix.modle.IModel;
import com.ta.wootrix.modle.TopicsAndLanguage;
import com.ta.wootrix.utils.Utility;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.Collection;

public class TopicsLaguageParser implements Parser<IModel>
{
	@Override
	public IModel parse(JSONObject json) throws JSONException
	{
		boolean success = json.optBoolean("success");
		/*
		 * { "data": { "": null, "": null, "": null }, "message": false, "success": true }
		 */
		if (success)
		{
			JSONObject mainObject = json.getJSONObject("data");
			TopicsAndLanguage model = new TopicsAndLanguage();

			model.setAppLanguage(getAppLanguage(mainObject.getString("website_language")));
			String articleLanguage = mainObject.getString("article_language");
			if (articleLanguage != null && articleLanguage.length() > 0)
			{
				String abc[] = articleLanguage.toString().split(",");
				String prenames[] = new String[abc.length];
				for (int i = 0; i < abc.length; i++)
				{
					prenames[i] = getLanguageNamesFromNumber(abc[i]);
				}
				model.setArticle_language(TextUtils.join(",", prenames));
			}
			else
				model.setArticle_language(Utility.getDrfaultLanguage());
			String catetgories = mainObject.optString("category");
			if (catetgories != null && catetgories.length() > 0)
			{
				String[] cat = catetgories.toString().split("\\|");
				model.setCategory(TextUtils.join(",", cat));
			}
			else
				model.setCategory("");

			return model;
		}
		else
		{
			com.ta.wootrix.modle.Error error = new com.ta.wootrix.modle.Error();
			error.setError(json.optString("message"));
			return error;
		}
	}

	private String getAppLanguage(String string)
	{
		if (string.equalsIgnoreCase("english"))
			return "en";
		else if (string.equalsIgnoreCase("spanish"))
			return "es";
		else if (string.equalsIgnoreCase("portuguese"))
			return "pt";
		else
			return Utility.getDrfaultLanguage();
	}

	private String getLanguageNamesFromNumber(String string)
	{
		if (string.equalsIgnoreCase("1"))
			return "en";
		else if (string.equalsIgnoreCase("2"))
			return "pt";
		else
			return "es";
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
