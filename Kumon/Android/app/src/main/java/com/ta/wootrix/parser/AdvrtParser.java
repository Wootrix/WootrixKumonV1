package com.ta.wootrix.parser;

import com.ta.wootrix.modle.AdvertisementModle;
import com.ta.wootrix.modle.AdvtMainModle;
import com.ta.wootrix.modle.IModel;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.Collection;

public class AdvrtParser implements Parser<IModel>
{
	/*
	 * { "data": { "advertisement_layout1": [ { "type": "standard", "bannerURL":
	 * "http://localhost/wootrix/index.php/assets/Advertise/2014-12-19MGw_org.jpg" , "portraitURL":
	 * "http://localhost/wootrix/index.php/assets/Advertise/", "landscapeURL":
	 * "http://localhost/wootrix/index.php/assets/Advertise/", "linkToOpen": "dddddd", "videoURL":
	 * "", "timeInSeconds": "00:00:00" } ], }, "message": "Open Advertisement Data", "success": true
	 * }
	 */
	@Override
	public IModel parse(JSONObject json) throws JSONException
	{

		boolean success = json.optBoolean("success");
		if (success)
		{

			JSONObject mainObject = json.getJSONObject("data");
			AdvtMainModle advtModle = new AdvtMainModle();
			ArrayList<AdvertisementModle> lyt1List = new ArrayList<AdvertisementModle>();
			ArrayList<AdvertisementModle> lyt2List = new ArrayList<AdvertisementModle>();
			ArrayList<AdvertisementModle> lyt3List = new ArrayList<AdvertisementModle>();
			ArrayList<AdvertisementModle> lyt4List = new ArrayList<AdvertisementModle>();

			JSONArray portJsonArray = mainObject.optJSONArray("advertisement_layout1");

			if (portJsonArray != null && portJsonArray.length() > 0)
			{
				for (int i = 0; i < portJsonArray.length(); i++)
				{
					JSONObject magazinJsn = portJsonArray.getJSONObject(i);
					AdvertisementModle magModle = new AdvertisementModle();
					magModle.setAdvertiseID(magazinJsn.optString("advertisementId"));
					magModle.setPortaitURL(magazinJsn.optString("portraitURL"));
					magModle.setBannerURL(magazinJsn.optString("bannerURL"));
					magModle.setEmbededThumbUrl(magazinJsn.optString("videoThumbURL"));
					magModle.setEmbededVideoUrl(magazinJsn.optString("embededUrl"));
					magModle.setLandscapeURL(magazinJsn.optString("landscapeURL"));
					magModle.setLikeToOpen(magazinJsn.optString("linkToOpen"));
					magModle.setVideoURL(magazinJsn.optString("videoURL"));
					magModle.setTimeInSeconds(magazinJsn.optInt("timeInSeconds"));
					magModle.setType(magazinJsn.optString("type"));
					lyt1List.add(magModle);
				}
			}

			// advertisement for the second layout
			portJsonArray = mainObject.optJSONArray("advertisement_layout2");

			if (portJsonArray != null && portJsonArray.length() > 0)
			{
				for (int i = 0; i < portJsonArray.length(); i++)
				{
					JSONObject magazinJsn = portJsonArray.getJSONObject(i);
					AdvertisementModle magModle = new AdvertisementModle();
					magModle.setAdvertiseID(magazinJsn.optString("advertisementId"));
					magModle.setBannerURL(magazinJsn.optString("bannerURL"));
					magModle.setEmbededThumbUrl(magazinJsn.optString("videoThumbURL"));
					magModle.setEmbededVideoUrl(magazinJsn.optString("embededUrl"));
					magModle.setPortaitURL(magazinJsn.optString("portraitURL"));
					magModle.setLandscapeURL(magazinJsn.optString("landscapeURL"));
					magModle.setLikeToOpen(magazinJsn.optString("linkToOpen"));
					magModle.setVideoURL(magazinJsn.optString("videoURL"));
					magModle.setTimeInSeconds(magazinJsn.optInt("timeInSeconds"));
					magModle.setType(magazinJsn.optString("type"));
					lyt2List.add(magModle);
				}
			}

			// advertisement for the third layout
			portJsonArray = mainObject.optJSONArray("advertisement_layout3");

			if (portJsonArray != null && portJsonArray.length() > 0)
			{
				for (int i = 0; i < portJsonArray.length(); i++)
				{
					JSONObject magazinJsn = portJsonArray.getJSONObject(i);
					AdvertisementModle magModle = new AdvertisementModle();
					magModle.setAdvertiseID(magazinJsn.optString("advertisementId"));
					magModle.setBannerURL(magazinJsn.optString("bannerURL"));
					magModle.setEmbededThumbUrl(magazinJsn.optString("videoThumbURL"));
					magModle.setEmbededVideoUrl(magazinJsn.optString("embededUrl"));
					magModle.setPortaitURL(magazinJsn.optString("portraitURL"));
					magModle.setLandscapeURL(magazinJsn.optString("landscapeURL"));
					magModle.setLikeToOpen(magazinJsn.optString("linkToOpen"));
					magModle.setVideoURL(magazinJsn.optString("videoURL"));
					magModle.setTimeInSeconds(magazinJsn.optInt("timeInSeconds"));
					magModle.setType(magazinJsn.optString("type"));
					lyt3List.add(magModle);
				}
			}

			// advertisement for the third layout
			portJsonArray = mainObject.optJSONArray("advertisement_layout4");

			if (portJsonArray != null && portJsonArray.length() > 0)
			{
				for (int i = 0; i < portJsonArray.length(); i++)
				{
					JSONObject magazinJsn = portJsonArray.getJSONObject(i);
					AdvertisementModle magModle = new AdvertisementModle();
					magModle.setAdvertiseID(magazinJsn.optString("advertisementId"));
					magModle.setBannerURL(magazinJsn.optString("bannerURL"));
					magModle.setEmbededThumbUrl(magazinJsn.optString("videoThumbURL"));
					magModle.setEmbededVideoUrl(magazinJsn.optString("embededUrl"));
					magModle.setPortaitURL(magazinJsn.optString("portraitURL"));
					magModle.setLandscapeURL(magazinJsn.optString("landscapeURL"));
					magModle.setLikeToOpen(magazinJsn.optString("linkToOpen"));
					magModle.setVideoURL(magazinJsn.optString("videoURL"));
					magModle.setTimeInSeconds(magazinJsn.optInt("timeInSeconds"));
					magModle.setType(magazinJsn.optString("type"));
					lyt4List.add(magModle);
				}
			}

			advtModle.setLyt1List(lyt1List);
			advtModle.setLyt2List(lyt2List);
			advtModle.setLyt3List(lyt3List);
			advtModle.setLyt4List(lyt4List);
			return advtModle;
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
