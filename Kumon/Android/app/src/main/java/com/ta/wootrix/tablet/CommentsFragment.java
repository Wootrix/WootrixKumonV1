package com.ta.wootrix.tablet;

import android.app.Fragment;
import android.content.Context;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.TextView;

import com.ta.wootrix.R;
import com.ta.wootrix.adapter.CommentListingAdapter;
import com.ta.wootrix.asynctask.IAsyncCaller;
import com.ta.wootrix.asynctask.ServerIntractorAsync;
import com.ta.wootrix.modle.CommentsModle;
import com.ta.wootrix.modle.Error;
import com.ta.wootrix.modle.IModel;
import com.ta.wootrix.modle.ResponseMsgInfo;
import com.ta.wootrix.parser.CommentParser;
import com.ta.wootrix.parser.MessageParser;
import com.ta.wootrix.restapi.APIUtils;
import com.ta.wootrix.restapi.APIUtils.REQUEST_TYPE;
import com.ta.wootrix.utils.Constants;
import com.ta.wootrix.utils.Utility;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

public class CommentsFragment extends Fragment implements IAsyncCaller, OnClickListener
{
	ArrayList<IModel>				mCommentsList	= new ArrayList<IModel>();
	private ListView				mListVw;
	private TextView				mTxtVwCmntsNotFound;
	private EditText				mEdtTxtComments;
	private Button					mBtnSendComments;
	private Context					mContext;
	private String					mArticleID;
	private CommentListingAdapter	mAdapter;
	private TextView				mTxtVwHeader;
	private LinearLayout			mLnrLyt;
	private boolean					isMagzine;
	private boolean					ispostComment;

	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState)
	{
		mContext = getActivity();
		View v = inflater.inflate(R.layout.comment_popup_screen, container, false);
		mArticleID = getArguments().getString("mArticleID");
		isMagzine = getArguments().getBoolean("isMagzine", false);
		mListVw = (ListView) v.findViewById(R.id.comment_popup_scrn__listviw);
		mTxtVwCmntsNotFound = (TextView) v.findViewById(R.id.comment_popup_scrn_no_comments_txt);
		mEdtTxtComments = (EditText) v.findViewById(R.id.comment_popup_scrn_comment_edtTxt);
		mBtnSendComments = (Button) v.findViewById(R.id.comment_popup_scrn_send_comment_btn);
		mLnrLyt = (LinearLayout) v.findViewById(R.id.comment_popup_scrn_main_lnrlyt);
		mBtnSendComments.setOnClickListener(this);
		mLnrLyt.setOnClickListener(this);
		getCommentsAsync();
		return v;
	}

	private void postCommentsAsync()
	{
		if (Utility.isNetworkAvailable(mContext))
		{
			try
			{
				ispostComment = true;
				JSONObject json = new JSONObject();
				json.put("articleId", mArticleID);
				json.put("token", Utility.getSharedPrefStringData(mContext, Constants.USER_TOKEN));
				json.put("comment", mEdtTxtComments.getText().toString().trim());
				json.put("type", isMagzine ? "magazine" : "open");
				json.put("appLanguage", Utility.getDrfaultLanguage());

				new ServerIntractorAsync(mContext, getString(R.string.please_wait_lbl), true, APIUtils.getBaseURL(APIUtils.POST_COMMENT), json, REQUEST_TYPE.POST, this, new MessageParser()).execute();
			}
			catch (JSONException e)
			{
				e.printStackTrace();
			}
		}
		else
		{
			Utility.showNetworkNotAvailToast(mContext);
		}
	}

	private void getCommentsAsync()
	{
		if (Utility.isNetworkAvailable(mContext))
		{
			new ServerIntractorAsync(mContext, getString(R.string.please_wait_lbl), true, APIUtils.getBaseURL(APIUtils.GET_COMMENTS), getJsonParams(), REQUEST_TYPE.POST, this, new CommentParser())
					.execute();
		}
		else
		{
			Utility.showNetworkNotAvailToast(mContext);
		}
	}

	private JSONObject getJsonParams()
	{
		try
		{
			JSONObject json = new JSONObject();
			json.put("articleId", mArticleID);
			json.put("type", isMagzine ? "magazine" : "open");
			json.put("token", Utility.getSharedPrefStringData(mContext, Constants.USER_TOKEN));
			json.put("appLanguage", Utility.getDrfaultLanguage());
			return json;
		}
		catch (JSONException e)
		{
			e.printStackTrace();
		}
		return null;
	}

	@Override
	public void onComplete(IModel object, String message, boolean status)
	{
		if (object instanceof Error)
		{
			Utility.showToastMessage(mContext, ((Error) object).getError());
		}
		else if (object instanceof ResponseMsgInfo)
		{
			mEdtTxtComments.setText("");
			getCommentsAsync();
		}
	}

	@Override
	public void onComplete(ArrayList<IModel> object, String message, boolean status)
	{
		if (object.get(0) instanceof Error)
		{
			Utility.showToastMessage(mContext, ((Error) object.get(0)).getError());
		}
		else if (object.get(0) instanceof CommentsModle)
		{
			mCommentsList.clear();
			mCommentsList.addAll((ArrayList<IModel>) object);
			setAdapter();
			if (mContext instanceof TabletArticleDetailActivity)
				((TabletArticleDetailActivity) mContext).updateCommentCount(mCommentsList.size());
			else if (mContext instanceof TabletEmbeddedArticleDetailActivity)
				((TabletEmbeddedArticleDetailActivity) mContext).updateCommentCount(mCommentsList.size());
		}
	}

	private void setAdapter()
	{
		if (mCommentsList != null && mCommentsList.size() > 0)
		{
			mTxtVwCmntsNotFound.setVisibility(View.GONE);
			mListVw.setVisibility(View.VISIBLE);
			if (mAdapter == null)
			{
				mAdapter = new CommentListingAdapter(mContext, mCommentsList);
				mListVw.setAdapter(mAdapter);
			}
			else
				mAdapter.notifyDataSetChanged();

			if (ispostComment)
			{
				ispostComment = false;
				mListVw.smoothScrollToPosition(mCommentsList.size() - 1);
			}
		}
		else
		{
			mTxtVwCmntsNotFound.setVisibility(View.VISIBLE);
			mListVw.setVisibility(View.GONE);
		}
	}

	@Override
	public void onClick(View v)
	{
		if (v == mBtnSendComments)
		{
			String commentText = mEdtTxtComments.getText().toString().trim();
			if (commentText != null && commentText.length() == 0)
				Utility.showToastMessage(mContext, getString(R.string.enter_comment));
			else
				postCommentsAsync();
		}
	}
}
