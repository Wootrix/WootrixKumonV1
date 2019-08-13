package com.ta.wootrix.asynctask;

import android.os.AsyncTask;

import com.ta.wootrix.restapi.APIUtils;
import com.ta.wootrix.utils.BranchDelegate;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.StringEntity;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.protocol.HTTP;
import org.apache.http.util.EntityUtils;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.io.UnsupportedEncodingException;

public class BranchAsyncTask extends AsyncTask<Void, Void, String> {

    private BranchDelegate delegate;

    private String articleId, magazineId;

    public BranchAsyncTask(BranchDelegate connectDelegate, String articleId, String magazineId){
        delegate = connectDelegate;
        this.articleId = articleId;
        this.magazineId = magazineId;
    }

    @Override
    protected void onPreExecute() {
        delegate.preCreate();
    }

    @Override
    protected String doInBackground(Void... voids) {

        String resultString = null;

        try {

            HttpClient httpClient = new DefaultHttpClient();
            HttpPost post = new HttpPost(APIUtils.BASE_URL + "getBranchDetails");

            JSONObject json = new JSONObject();
            json.put("articleId", articleId);
            json.put("magazineId", magazineId);

            post.addHeader(HTTP.CONTENT_TYPE, "application/json");
            post.addHeader("Authorization", "Basic Q29wcGVyTW9iaWxlOmN1cGlk");

            HttpEntity entity = new StringEntity(json.toString(), "UTF-8");
            post.setEntity(entity);

            HttpResponse response = httpClient.execute(post);

            if( response != null ){

                try {
                    resultString = EntityUtils.toString(response.getEntity());
                    JSONObject obj = new JSONObject(resultString);
                    JSONObject dataObj = obj.getJSONObject("data");
                    resultString = dataObj.getString("branchLink");
                } catch (IOException e) {
                    e.printStackTrace();
                } catch (JSONException e) {
                    e.printStackTrace();
                }

            }

        } catch (UnsupportedEncodingException e) {
            resultString = null;
            e.printStackTrace();
        } catch (ClientProtocolException e) {
            resultString = null;
            e.printStackTrace();
        } catch (IOException e) {
            resultString = null;
            e.printStackTrace();
        } catch (JSONException e) {
            e.printStackTrace();
        }

        return resultString;

    }

    @Override
    protected void onPostExecute(String result) {
        delegate.postCreate(result);
    }

}
