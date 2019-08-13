package com.ta.wootrix.adapter;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.TextView;

import com.nostra13.universalimageloader.core.ImageLoader;
import com.ta.wootrix.R;
import com.ta.wootrix.modle.CommentsModle;
import com.ta.wootrix.modle.IModel;
import com.ta.wootrix.utils.Utility;

import java.util.ArrayList;

public class CommentListingAdapter extends BaseAdapter
{
	LayoutInflater				mInflater;
	private ArrayList<IModel>	commentsList;
	private Context				context;
	private ImageLoader			mImageLoader;

	public CommentListingAdapter(Context con, ArrayList<IModel> mList)
	{
		this.commentsList = mList;
		this.context = con;
		mInflater = (LayoutInflater) con.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
		mImageLoader = Utility.getImageLoader(context);
	}

	@Override
	public int getCount()
	{
		return commentsList.size();
	}

	@Override
	public Object getItem(int position)
	{
		return commentsList.get(position);
	}

	@Override
	public long getItemId(int position)
	{
		return position;
	}

	@Override
	public View getView(int position, View convertView, ViewGroup parent)
	{
		ViewHolder holder;
		View view = convertView;
		CommentsModle answer = (CommentsModle) commentsList.get(position);
		if (view == null)
		{
			view = mInflater.inflate(R.layout.comment_row, null, false);
			holder = new ViewHolder();
			holder.mTxtVwCommenterName = (TextView) view.findViewById(R.id.comment_row_commenter_name_txtVw);
			holder.mTxtVwComment = (TextView) view.findViewById(R.id.comment_row_comment_txt_txtVw);
			holder.mTxtVwCommentDate = (TextView) view.findViewById(R.id.comment_row_comment_date_txtVw);
			holder.mImgVwCommenterImg = (ImageView) view.findViewById(R.id.comment_row_commenter_image_imgVw);

			view.setTag(holder);
		}
		else
		{
			holder = (ViewHolder) convertView.getTag();
		}
		mImageLoader.displayImage(answer.getPhotoURL(), holder.mImgVwCommenterImg, Utility.getProfilePicDisplayOption());
		holder.mTxtVwCommenterName.setText(answer.getCommenterName());
		holder.mTxtVwComment.setText(answer.getComment());
		holder.mTxtVwCommentDate.setText(Utility.getSystemDataTimeFromUTC(context, answer.getCommentDate()));

		return view;
	}

	public void update(ArrayList<IModel> list)
	{
		commentsList = list;
		notifyDataSetChanged();
	}

	private static class ViewHolder
	{
		TextView	mTxtVwCommenterName, mTxtVwComment, mTxtVwCommentDate;
		ImageView	mImgVwCommenterImg;
	}
}
