package com.ta.wootrix.modle;

import android.os.Parcel;
import android.os.Parcelable;

public class MagazineModle implements IModel {
    // "magazineId": "",
    // "coverPhotoUrl": "",
    // "mobileAppBarColorRGB": "100:255:255",
    // "customerLogoUrl": ""

    public static final Parcelable.Creator<MagazineModle> CREATOR = new Parcelable.Creator<MagazineModle>() {
        @Override
        public MagazineModle createFromParcel(Parcel source) {
            return new MagazineModle(source);
        }

        @Override
        public MagazineModle[] newArray(int size) {
            return new MagazineModle[size];
        }
    };
    String magazineId;
    String coverPhotoUrl;
    String mobileAppBarColorRGB;
    String customerLogoUrl;
    String magazineName;

    public MagazineModle(Parcel source) {
        magazineId = source.readString();
        coverPhotoUrl = source.readString();
        mobileAppBarColorRGB = source.readString();
        customerLogoUrl = source.readString();
        magazineName = source.readString();
    }

    public MagazineModle() {
        // TODO Auto-generated constructor stub
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeString(magazineId);
        dest.writeString(coverPhotoUrl);
        dest.writeString(mobileAppBarColorRGB);
        dest.writeString(customerLogoUrl);
        dest.writeString(magazineName);
    }

    public String getMagazineId() {
        return magazineId;
    }

    public void setMagazineId(String magazineId) {
        this.magazineId = magazineId;
    }

    public String getCoverPhotoUrl() {
        return coverPhotoUrl;
    }

    public void setCoverPhotoUrl(String coverPhotoUrl) {
        this.coverPhotoUrl = coverPhotoUrl;
    }

    public String getMobileAppBarColorRGB() {
        return mobileAppBarColorRGB;
    }

    public void setMobileAppBarColorRGB(String mobileAppBarColorRGB) {
        this.mobileAppBarColorRGB = mobileAppBarColorRGB;
    }

    public String getCustomerLogoUrl() {
        return customerLogoUrl;
    }

    public void setCustomerLogoUrl(String customerLogoUrl) {
        this.customerLogoUrl = customerLogoUrl;
    }

    public String getMagazineName() {
        return magazineName;
    }

    public void setMagazineName(String magazineName) {
        this.magazineName = magazineName;
    }
}
