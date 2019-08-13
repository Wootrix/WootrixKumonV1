package com.ta.wootrix.modle;

import android.os.Parcel;

/**
 * This class used to handle simple messgae response for any request
 */
public class ResponseMsgInfo implements IModel
{
	private String message = "Some internal error occured!";
	private String userId = "null";

	public String getMessage()
	{
		return message;
	}

	public void setMessage(String error)
	{
		this.message = error;
	}

	public String getUserId()
	{
		return userId;
	}

	public void setUserId(String userId)
	{
		this.userId = userId;
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
