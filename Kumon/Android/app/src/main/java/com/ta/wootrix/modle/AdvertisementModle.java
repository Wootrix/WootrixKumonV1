package com.ta.wootrix.modle;

import android.os.Parcel;

public class AdvertisementModle implements IModel
{
	// "type": "standard",
	// "bannerURL": "http://localhost/wootrix/index.php/assets/Advertise/2014-12-19MGw_org.jpg",
	// "portraitURL": "http://localhost/wootrix/index.php/assets/Advertise/",
	// "landscapeURL": "http://localhost/wootrix/index.php/assets/Advertise/",
	// "linkToOpen": "dddddd",
	// "videoURL": "",
	// "timeInSeconds": "00:00:00"

	String	AdvertiseID, PortaitURL, landscapeURL, LikeToOpen = "", VideoURL, Type, bannerURL;
	String	embededThumbUrl, embededVideoUrl;
	int timeInSeconds;

	public String getEmbededVideoUrl()
	{
		return embededVideoUrl;
	}

	public void setEmbededVideoUrl(String embededVideoUrl)
	{
		this.embededVideoUrl = embededVideoUrl;
	}

	public String getEmbededThumbUrl()
	{
		return embededThumbUrl;
	}

	public void setEmbededThumbUrl(String embededThumbUrl)
	{
		this.embededThumbUrl = embededThumbUrl;
	}

	public String getBannerURL()
	{
		return bannerURL;
	}

	public void setBannerURL(String bannerURL)
	{
		this.bannerURL = bannerURL;
	}

	public String getPortaitURL()
	{
		return PortaitURL;
	}

	public void setPortaitURL(String portaitURL)
	{
		PortaitURL = portaitURL;
	}

	public String getLandscapeURL()
	{
		return landscapeURL;
	}

	public void setLandscapeURL(String landscapeURL)
	{
		this.landscapeURL = landscapeURL;
	}

	public int getTimeInSeconds()
	{
		return timeInSeconds;
	}

	public void setTimeInSeconds(int timeInSeconds)
	{
		this.timeInSeconds = timeInSeconds;
	}

	public String getType()
	{
		return Type;
	}

	public void setType(String type)
	{
		Type = type;
	}

	public String getAdvertiseID()
	{
		return AdvertiseID;
	}

	public void setAdvertiseID(String advertiseID)
	{
		AdvertiseID = advertiseID;
	}

	public String getLikeToOpen()
	{
		return LikeToOpen;
	}

	public void setLikeToOpen(String likeToOpen)
	{
		LikeToOpen = likeToOpen;
	}

	public String getVideoURL()
	{
		return VideoURL;
	}

	public void setVideoURL(String videoURL)
	{
		VideoURL = videoURL;
	}

	@Override
	public int describeContents()
	{
		// TODO Auto-generated method stub
		return 0;
	}

	@Override
	public void writeToParcel(Parcel arg0, int arg1)
	{
		// TODO Auto-generated method stub

	}

}
