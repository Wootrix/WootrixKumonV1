package com.ta.wootrix.parser;

import com.ta.wootrix.modle.ArticleModle;
import com.ta.wootrix.modle.IModel;
import com.ta.wootrix.modle.LandingPageModle;
import com.ta.wootrix.modle.MagazineModle;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.Collection;

public class LandingPageParser implements Parser<IModel>
{
	// {
	// "message": "",
	// "data": [
	// {
	// "openArticle": [
	// {
	// "articleId": "",
	// "articleType": "text/video",
	// "title": "",
	// "createdDate": "",
	// "source": "",
	// "articleDescPlain": "",
	// "coverPhotoUrl": "",
	// "articleVideoUrl": "",
	// "allowShare": "",
	// "allowComment": "",
	// "commentsCount": ""
	// }
	// ],
	// "magazines": [
	// {
	// "magazineId": "",
	// "coverPhotoUrl": "",
	// "mobileAppBarColorRGB": "100:255:255",
	// "customerLogoUrl": ""
	// }
	// ]
	// }
	// ],
	// "statusCode": ""
	// }
	@Override
	public IModel parse(JSONObject json) throws JSONException
	{
		boolean success = json.optBoolean("success");
		if (success)
		{
			JSONObject mainObject = json.getJSONArray("data").getJSONObject(0);
			LandingPageModle landingModel = new LandingPageModle();
			ArticleModle modle = null;
			JSONObject openArticleJson = mainObject.optJSONArray("openArticle").optJSONObject(0);
			if (openArticleJson != null)
			{
				modle = new ArticleModle();
				modle.setArticleID(openArticleJson.optString("articleId"));
				modle.setArticleType(openArticleJson.optString("articleType"));
				modle.setArticleDescPlain(openArticleJson.optString("articleDescPlain"));
				modle.setArticleDescHtml(openArticleJson.optString("articleDesc"));
				modle.setTitle(openArticleJson.optString("title"));
				modle.setCommentCount(openArticleJson.optString("commentsCount"));
				modle.setCoverPhotoUrl(openArticleJson.optString("coverPhotoUrl"));
				modle.setCreateDate(openArticleJson.optString("createdDate"));
				modle.setSource(openArticleJson.optString("source"));
				modle.setVideoURL(openArticleJson.optString("articleVideoUrl"));
				modle.setCanComment(openArticleJson.optString("allowComment").equalsIgnoreCase("Y") ? true : false);
				modle.setCanShare(openArticleJson.optString("allowShare").equalsIgnoreCase("Y") ? true : false);
				modle.setDetailLoadFromHtmlData(openArticleJson.optString("createdBy").equalsIgnoreCase("1") ? true : false);

				/* for embedded video */
				modle.setEmbeddedThumb(openArticleJson.optString("embedded_thumbnail"));
				modle.setEmbeddedVideoUrl(openArticleJson.optString("embedded_video_link"));
			}
			JSONArray magazineJsonArray = mainObject.getJSONArray("magazines");
			ArrayList<MagazineModle> magazineList = new ArrayList<MagazineModle>();
			if (magazineJsonArray != null && magazineJsonArray.length() > 0)
			{
				for (int i = 0; i < magazineJsonArray.length(); i++)
				{
					JSONObject magazinJsn = magazineJsonArray.getJSONObject(i);
					MagazineModle magModle = new MagazineModle();
					magModle.setMagazineId(magazinJsn.optString("magazineId"));
					magModle.setCoverPhotoUrl(magazinJsn.optString("coverPhotoUrl"));
					magModle.setCustomerLogoUrl(magazinJsn.optString("customerLogoUrl"));
					magModle.setMobileAppBarColorRGB(magazinJsn.optString("mobileAppBarColorRGB"));
					magModle.setMagazineName(magazinJsn.optString("magazineName"));
					magazineList.add(magModle);
				}
			}
			landingModel.setMagazineList(magazineList);
			landingModel.setOpenArticle(modle);
			return landingModel;
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
