package com.ta.wootrix.parser;

import com.ta.wootrix.modle.IModel;
import com.ta.wootrix.modle.LoginSignupModle;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.Collection;

public class LoginSignupParser implements Parser<IModel>
{
	// {
	// "data": [
	// {
	// "socialAccountToken":
	// "CAAM4w6s6t7kBAF0aZBR7hOSElkux0yQDsYyZCsfAqev4jjSuQInkeONbniGNp86SQ8kiZCY0cUijzWcMFtE5knS9OXwxNWZBXlz8wXByCZBZBDtQWzEvP30LBz7XGU8oGZBcGT29uGIGEmiSpOXqmE1P6XNrFV94EZAzEpWOFVb1D9BHvpLk2VV3DCG1hbNnSTZAoSfYDoEOvrISPZBizUiABlJrSeP0FtDc4HmR4ZBJ0SxhgZDZD",
	// "user": {
	// "name": "Teena NathPaul",
	// "photoUrl": "http:\/\/graph.facebook.com\/398035967016081\/picture?type=large",
	// "email": "fb.tnpaul@gmail.com"
	// },
	// "requireEmail": "N",
	// "requirePassword": "N",
	// "token": "1",
	// "tokenExpiryDate": "2015-01-21 11:34:26"
	// }
	// ],
	// "message": "Login Successfull",
	// "success": true
	// }@
	@Override
	public IModel parse(JSONObject json) throws JSONException
	{
		boolean success = json.optBoolean("success");

		if (success)
		{
			JSONObject mainObject = json.getJSONArray("data").getJSONObject(0);
			LoginSignupModle model = new LoginSignupModle();

			JSONObject userOb = mainObject.getJSONObject("user");

			model.setToken(mainObject.getString("token"));
			model.setTokenExpDate(mainObject.getString("tokenExpiryDate"));
			model.setEmailRequired(mainObject.optString("requireEmail").equalsIgnoreCase("Y") ? true : false);
			model.setPasswordRequired(mainObject.optString("requirePassword").equalsIgnoreCase("Y") ? true : false);
			model.setUsername(userOb.getString("name"));
			model.setPhotoUrl(userOb.getString("photoUrl"));
			model.setEmail(userOb.getString("email"));

			/* multiple account add check */
			model.setFB(mainObject.optBoolean("is_facebook"));
			model.setTW(mainObject.optBoolean("is_twitter"));
			model.setGP(mainObject.optBoolean("is_google"));
			model.setLN(mainObject.optBoolean("is_linkedin"));

			boolean ismail = mainObject.optBoolean("is_email");

			return model;
		}
		else
		{
			com.ta.wootrix.modle.Error error = new com.ta.wootrix.modle.Error();
			error.setError(json.optString("message"));
			return error;
		}
	}

	@Override
	public Collection<IModel> parse(JSONArray array) throws JSONException
	{
		return null;
	}

	@Override
	public ArrayList<IModel> parse(String resp) throws JSONException
	{
		return null;
	}

}
