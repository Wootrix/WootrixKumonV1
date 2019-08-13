package com.ta.wootrix.modle;

import android.os.Parcel;

import java.util.ArrayList;

public class LandingPageModle implements IModel
{
	ArticleModle				openArticle;
	ArrayList<MagazineModle>	magazineList;

	public ArticleModle getOpenArticle()
	{
		return openArticle;
	}

	public void setOpenArticle(ArticleModle openArticle)
	{
		this.openArticle = openArticle;
	}

	public ArrayList<MagazineModle> getMagazineList()
	{
		return magazineList;
	}

	public void setMagazineList(ArrayList<MagazineModle> magazineList)
	{
		this.magazineList = magazineList;
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
