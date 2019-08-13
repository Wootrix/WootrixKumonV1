package com.ta.wootrix.modle;

import android.os.Parcel;

public class TopicsModle implements IModel
{
	String	topicID, topicName;
	boolean	isTopicSelected;

	public String getTopicID()
	{
		return topicID;
	}

	public void setTopicID(String topicID)
	{
		this.topicID = topicID;
	}

	public String getTopicName()
	{
		return topicName;
	}

	public void setTopicName(String topicName)
	{
		this.topicName = topicName;
	}

	public boolean isTopicSelected()
	{
		return isTopicSelected;
	}

	public void setTopicSelected(boolean isTopicSelected)
	{
		this.isTopicSelected = isTopicSelected;
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
