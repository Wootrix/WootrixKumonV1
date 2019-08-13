package com.ta.wootrix.parser;

import com.ta.wootrix.modle.IModel;
import com.ta.wootrix.modle.MagazineModle;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.Collection;

public class MagazineParser implements Parser<IModel>
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
		/*
		 * { "message": "", "data": [ { "magazines": [ { "magazineId": "", "coverPhotoUrl": "",
		 * "mobileAppBarColorRGB": "100:255:255", "customerLogoUrl": "" }, { "magazineId": "",
		 * "coverPhotoUrl": "", "mobileAppBarColorRGB": "100:255:255", "customerLogoUrl": "" } ] }
		 * ], "statusCode": "" }
		 */
		JSONObject obj = new JSONObject(resp);
		if (obj.getBoolean("success") && obj.getJSONArray("data").length() > 0)
		{
			JSONObject mainTopicsJson = obj.getJSONArray("data").getJSONObject(0);

			JSONArray magzineArray = mainTopicsJson.getJSONArray("magazines");
			if (magzineArray != null && magzineArray.length() > 0)
			{
				for (int i = 0; i < magzineArray.length(); i++)
				{
					JSONObject magazinJsn = magzineArray.getJSONObject(i);
					MagazineModle magModle = new MagazineModle();
					magModle.setMagazineId(magazinJsn.optString("magazineId"));
					magModle.setCoverPhotoUrl(magazinJsn.optString("coverPhotoUrl"));
					magModle.setCustomerLogoUrl(magazinJsn.optString("customerLogoUrl"));
					magModle.setMobileAppBarColorRGB(magazinJsn.optString("mobileAppBarColorRGB"));
					list.add(magModle);
				}
			}
			return list;
		}
		else
		{
			com.ta.wootrix.modle.Error error = new com.ta.wootrix.modle.Error();
			error.setError(obj.getString("message"));

			if( obj.has("magazineId") ){
				error.setMagazineId(obj.getString("magazineId"));
			}

			if( obj.has("hasMagazine") ){
				error.setHasMagazine(obj.getBoolean("hasMagazine"));
			}

			list.add(error);
			return list;
		}

	}
}
