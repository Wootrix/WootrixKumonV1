package com.ta.wootrix.modle;

import android.os.Parcel;

public class LoginSignupModle implements IModel
{

	String	Username, Email, photoUrl, token, tokenExpDate;
	boolean	isFB, isTW, isGP, isLN;
	boolean isEmailRequired, isPasswordRequired;

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

	public String getTokenExpDate()
	{
		return tokenExpDate;
	}

	public void setTokenExpDate(String tokenExpDate)
	{
		this.tokenExpDate = tokenExpDate;
	}

	public boolean isEmailRequired()
	{
		return isEmailRequired;
	}

	public void setEmailRequired(boolean isEmailRequired)
	{
		this.isEmailRequired = isEmailRequired;
	}

	public boolean isPasswordRequired()
	{
		return isPasswordRequired;
	}

	public void setPasswordRequired(boolean isPasswordRequired)
	{
		this.isPasswordRequired = isPasswordRequired;
	}

	public String getToken()
	{
		return token;
	}

	public void setToken(String token)
	{
		this.token = token;
	}

	public String getUsername()
	{
		return Username;
	}

	public void setUsername(String username)
	{
		Username = username;
	}

	public String getEmail()
	{
		return Email;
	}

	public void setEmail(String email)
	{
		Email = email;
	}

	public String getPhotoUrl()
	{
		return photoUrl;
	}

	public void setPhotoUrl(String photoUrl)
	{
		this.photoUrl = photoUrl;
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
