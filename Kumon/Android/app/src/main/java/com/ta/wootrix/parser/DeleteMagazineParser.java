package com.ta.wootrix.parser;

import com.ta.wootrix.modle.IModel;
import com.ta.wootrix.modle.ResponseDeleteInfo;
import com.ta.wootrix.modle.ResponseMsgInfo;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.Collection;

/**
 * Created by CIPL0424 on 9/16/2016.
 */
public class DeleteMagazineParser implements Parser<IModel>
{
    @Override
    public IModel parse(JSONObject json) throws JSONException
    {

        boolean success = json.optBoolean("success");

        if (success)
        {
            ResponseDeleteInfo responseMsg = new ResponseDeleteInfo();

            responseMsg.setMessage(json.getString("message"));

            return responseMsg;
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
        // TODO Auto-generated method stub
        return null;
    }

    @Override
    public ArrayList<IModel> parse(String resp) throws JSONException
    {
        // TODO Auto-generated method stub
        return null;
    }

}
