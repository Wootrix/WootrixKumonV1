package com.ta.wootrix.modle;

import android.os.Parcel;

public class TopicsAndLanguage implements IModel
{
	String appLanguage, article_language, category;

	public String getAppLanguage()
	{
		return appLanguage;
	}

	public void setAppLanguage(String appLanguage)
	{
		this.appLanguage = appLanguage;
	}

	public String getArticle_language()
	{
		return article_language;
	}

	public void setArticle_language(String article_language)
	{
		this.article_language = article_language;
	}

	public String getCategory()
	{
		return category;
	}

	public void setCategory(String category)
	{
		this.category = category;
	}

	@Override
	public int describeContents()
	{
		// TODO Auto-generated method stub
		return 0;
	}

	@Override
	public void writeToParcel(Parcel dest, int flags)
	{
		// TODO Auto-generated method stub

	}
}
