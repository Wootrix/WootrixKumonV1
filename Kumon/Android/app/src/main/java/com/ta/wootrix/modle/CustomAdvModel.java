package com.ta.wootrix.modle;

import android.os.Parcel;

public class CustomAdvModel implements IModel {

	private String id;

	private String magazineId;

	private String link;

	public String getId() {
		return id;
	}

	public void setId(String id) {
		this.id = id;
	}

	public String getMagazineId() {
		return magazineId;
	}

	public void setMagazineId(String magazineId) {
		this.magazineId = magazineId;
	}

	public String getLink() {
		return link;
	}

	public void setLink(String link) {
		this.link = link;
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
