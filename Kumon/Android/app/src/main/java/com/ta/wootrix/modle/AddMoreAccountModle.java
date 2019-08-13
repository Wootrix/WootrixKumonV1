package com.ta.wootrix.modle;

import android.os.Parcel;

public class AddMoreAccountModle implements IModel
{

	String	Email	= "", msg = "";
	boolean	isFB, isTW, isGP, isLN;
	String	msgId	= "";

	public boolean isFB()
	{
		return isFB;
	}

	public void setFB(boolean isFB)
	{
		this.isFB = isFB;
	}

	public boolean isTW()
	{
		return isTW;
	}

	public void setTW(boolean isTW)
	{
		this.isTW = isTW;
	}

	public boolean isGP()
	{
		return isGP;
	}

	public void setGP(boolean isGP)
	{
		this.isGP = isGP;
	}

	public boolean isLN()
	{
		return isLN;
	}

	public void setLN(boolean isLN)
	{
		this.isLN = isLN;
	}

	public String getMsgId()
	{
		return msgId;
	}

	public void setMsgId(String msgId)
	{
		this.msgId = msgId;
	}

	public String getEmail()
	{
		return Email;
	}

	public void setEmail(String email)
	{
		Email = email;
	}

	public String getMsg()
	{
		return msg;
	}

	public void setMsg(String msg)
	{
		this.msg = msg;
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
