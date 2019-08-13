package com.ta.wootrix.modle;

import android.os.Parcel;

public class Error implements IModel
{
	private String error = "Some internal error occured!";

	private boolean hasMagazine;

	private String magazineId;

	public String getError()
	{
		return error;
	}

	public void setError(String error)
	{
		this.error = error;
	}

	public boolean getHasMagazine() {
		return hasMagazine;
	}

	public void setHasMagazine(boolean hasMagazine) {
		this.hasMagazine = hasMagazine;
	}

	public String getMagazineId() {
		return magazineId;
	}

	public void setMagazineId(String magazineId) {
		this.magazineId = magazineId;
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
