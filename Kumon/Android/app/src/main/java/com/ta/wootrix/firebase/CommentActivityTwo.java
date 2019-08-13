package com.ta.wootrix.firebase;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
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
import com.ta.wootrix.phone.BaseActivity;
import com.ta.wootrix.restapi.APIUtils;
import com.ta.wootrix.restapi.APIUtils.REQUEST_TYPE;
import com.ta.wootrix.utils.Constants;
import com.ta.wootrix.utils.Utility;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

public class CommentActivityTwo extends BaseActivity implements OnClickListener, IAsyncCaller
{
    private TextView				mTxtVwHeaderTitle;
    private ImageView				mImgVwBackBtn;
    private ListView				mListVwCommentsList;
    private EditText				mEdtTxtComment;
    private Button					mBtnSendComment;
    private CommentListingAdapter	mAdapter;
    private ArrayList<IModel>		mCommetsList	= new ArrayList<IModel>();
    private TextView				mTxtVwNoComments;
    private String					mArticleID;
    private boolean					isMagzine;
    private boolean					ispostComment;

    @Override
    protected void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.comment_popup_screen_two);
        mArticleID = getIntent().getExtras().getString("articleID");
        isMagzine = getIntent().getBooleanExtra("isMagzine", false);
        initViews();
        getCommentsAsync();
    }

    @Override
    protected void onDestroy()
    {
        super.onDestroy();
    }

    private void getCommentsAsync()
    {
        if (Utility.isNetworkAvailable(this))
        {
            new ServerIntractorAsync(this, getString(R.string.please_wait_lbl), true, APIUtils.getBaseURL(APIUtils.GET_COMMENTS), getJsonParams(), REQUEST_TYPE.POST, this, new CommentParser())
                    .execute();
        }
        else
        {
            Utility.showNetworkNotAvailToast(this);
        }
    }

    private JSONObject getJsonParams()
    {
        try
        {
            JSONObject json = new JSONObject();
            json.put("articleId", mArticleID);
            // open/magazine
            json.put("type", isMagzine ? "magazine" : "open");

            json.put("token", Utility.getSharedPrefStringData(this, Constants.USER_TOKEN));
            json.put("appLanguage", Utility.getDrfaultLanguage());
            return json;
        }
        catch (JSONException e)
        {
            e.printStackTrace();
        }
        return null;
    }

    private void setAdapter()
    {
        if (mCommetsList != null && mCommetsList.size() > 0)
        {
            if (mAdapter == null)
            {
                mAdapter = new CommentListingAdapter(this, mCommetsList);
                mListVwCommentsList.setAdapter(mAdapter);
            }
            else
                mAdapter.notifyDataSetChanged();

            if (ispostComment)
            {
                ispostComment = false;
                mListVwCommentsList.smoothScrollToPosition(mCommetsList.size() - 1);
            }
        }
        else
        {
            mTxtVwNoComments.setVisibility(View.VISIBLE);
            mListVwCommentsList.setVisibility(View.GONE);
        }
    }

    private void initViews()
    {
        mListVwCommentsList = (ListView) findViewById(R.id.comment_popup_scrn__listviw);
        mEdtTxtComment = (EditText) findViewById(R.id.comment_popup_scrn_comment_edtTxt);
        mBtnSendComment = (Button) findViewById(R.id.comment_popup_scrn_send_comment_btn);
        mTxtVwNoComments = (TextView) findViewById(R.id.comment_popup_scrn_no_comments_txt);

        mBtnSendComment.setOnClickListener(this);
    }

    @Override
    public void onClick(View v)
    {
        switch ( v.getId() )
        {
            case R.id.comment_popup_scrn_send_comment_btn :
                validateComment();
                break;

            default :
                break;
        }
    }

    @Override
    public void onBackPressed()
    {

        finish();
    }

    private void validateComment()
    {
        if (mEdtTxtComment.getText().toString().trim().length() == 0)
        {
            Utility.showToastMessage(this, getString(R.string.enter_comment));
        }
        else
        {
            if (Utility.isNetworkAvailable(this))
            {
                ispostComment = true;
                new ServerIntractorAsync(this, getString(R.string.please_wait_lbl), true, APIUtils.getBaseURL(APIUtils.POST_COMMENT), getPostJsonParams(), REQUEST_TYPE.POST, this, new MessageParser())
                        .execute();
            }
            else
            {
                Utility.showNetworkNotAvailToast(this);
            }
        }
    }

    private JSONObject getPostJsonParams()
    {
        try
        {
            JSONObject json = new JSONObject();
            json.put("articleId", mArticleID);
            json.put("type", isMagzine ? "magazine" : "open");
            json.put("token", Utility.getSharedPrefStringData(this, Constants.USER_TOKEN));
            json.put("comment", mEdtTxtComment.getText().toString().trim());
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
            Utility.showToastMessage(this, ((Error) object).getError());
        }
        else if (object instanceof ResponseMsgInfo)
        {
            mEdtTxtComment.setText("");
            getCommentsAsync();
        }
    }

    @Override
    public void onComplete(ArrayList<IModel> object, String message, boolean status)
    {
        if (object.get(0) instanceof Error)
        {
            Utility.showToastMessage(this, ((Error) object.get(0)).getError());
        }
        else if (object.get(0) instanceof CommentsModle)
        {
            mCommetsList.clear();
            mCommetsList.addAll((ArrayList<IModel>) object);
            setAdapter();
        }
    }
}
