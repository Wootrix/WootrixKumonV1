package com.ta.wootrix.parser;

import com.ta.wootrix.modle.CustomAdvModel;
import com.ta.wootrix.modle.IModel;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.Collection;

public class CustomAdvParser implements Parser<IModel> {

    @Override
    public IModel parse(JSONObject obj) throws JSONException {
        return null;
    }

    @Override
    public Collection<IModel> parse(JSONArray array) throws JSONException {
        return null;
    }

    @Override
    public ArrayList<IModel> parse(String resp) throws JSONException {

        JSONObject obj = new JSONObject(resp);

        CustomAdvModel modle = new CustomAdvModel();

        if (obj.getBoolean("success") && obj.getJSONObject("data").length() > 0) {

            JSONObject json = obj.getJSONObject("data");

            if (json != null && json.length() > 0) {

                modle.setId(json.optString("id"));
                modle.setMagazineId(json.optString("magazine_id"));
                modle.setLink(json.optString("link"));

            }

        }

        ArrayList<IModel> list = new ArrayList<>();
        list.add(modle);
        return list;

    }
}
