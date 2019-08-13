package com.ta.wootrix.asynctask;

import org.apache.http.NameValuePair;

import java.util.HashMap;
import java.util.List;

/**
 * @author deepchand need to be implemented by class do httpget or post request to generate get URL
 *         oir post request params
 */
public interface IGetPostRequest
{
	String getURL(String url, HashMap<String, String> paramMap);

	List<NameValuePair> getParams(HashMap<String, String> paramMap);

	String getJSONParams(HashMap<String, String> paramMap);
}
