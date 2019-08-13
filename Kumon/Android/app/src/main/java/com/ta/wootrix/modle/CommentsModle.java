package com.ta.wootrix.modle;

import android.os.Parcel;

public class CommentsModle implements IModel
{
	// "name": "",
	// "photoUrl": "",
	// "comment": "",
	// "commentDate": ""
	String commenterName, photoURL, comment, commentDate;

	public String getCommenterName()
	{
		return commenterName;
	}

	public void setCommenterName(String commenterName)
	{
		this.commenterName = commenterName;
	}

	public String getPhotoURL()
	{
		return photoURL;
	}

	public void setPhotoURL(String photoURL)
	{
		this.photoURL = photoURL;
	}

	public String getComment()
	{
		return comment;
	}

	public void setComment(String comment)
	{
		this.comment = comment;
	}

	public String getCommentDate()
	{
		return commentDate;
	}

	public void setCommentDate(String commentDate)
	{
		this.commentDate = commentDate;
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
