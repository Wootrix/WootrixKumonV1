package com.ta.wootrix.firebase;

import android.content.Context;
import android.content.SharedPreferences;

public class StorePreference
{
	Context						myContext;
	public static final String	MyPREFERENCESNAME	= "Kumon";

	SharedPreferences			myPreferences;
	SharedPreferences.Editor	myEditor;

	public StorePreference(Context aContext)
	{
		myContext = aContext;
		myPreferences = myContext.getSharedPreferences(MyPREFERENCESNAME, Context.MODE_PRIVATE);
		myEditor = myPreferences.edit();
	}


	public void putMessageBody(String messageBody) {

		myEditor.putString("MESSAGEBODY", messageBody);
		myEditor.commit();
	}

	public String getMessageBody() {

		String aName = "";
		aName = myPreferences.getString("MESSAGEBODY", aName);
		return aName;
	}


	public void remove() {
		myEditor.putString("MESSAGEBODY", null);
		myEditor.commit();
		//myEditor.remove("MESSAGEBODY").commit();
	}
}
