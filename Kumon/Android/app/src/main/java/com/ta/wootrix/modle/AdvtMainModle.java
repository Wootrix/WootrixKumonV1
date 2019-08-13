package com.ta.wootrix.modle;

import android.os.Parcel;

import java.util.ArrayList;

public class AdvtMainModle implements IModel
{

	ArrayList<AdvertisementModle> Lyt1List, Lyt2List, Lyt3List, Lyt4List;

	public ArrayList<AdvertisementModle> getLyt1List()
	{
		return Lyt1List;
	}

	public void setLyt1List(ArrayList<AdvertisementModle> lyt1List)
	{
		Lyt1List = lyt1List;
	}

	public ArrayList<AdvertisementModle> getLyt2List()
	{
		return Lyt2List;
	}

	public void setLyt2List(ArrayList<AdvertisementModle> lyt2List)
	{
		Lyt2List = lyt2List;
	}

	public ArrayList<AdvertisementModle> getLyt3List()
	{
		return Lyt3List;
	}

	public void setLyt3List(ArrayList<AdvertisementModle> lyt3List)
	{
		Lyt3List = lyt3List;
	}

	public ArrayList<AdvertisementModle> getLyt4List()
	{
		return Lyt4List;
	}

	public void setLyt4List(ArrayList<AdvertisementModle> lyt4List)
	{
		Lyt4List = lyt4List;
	}

	@Override
	public int describeContents()
	{
		return 0;
	}

	@Override
	public void writeToParcel(Parcel dest, int flags)
	{

	}
}
