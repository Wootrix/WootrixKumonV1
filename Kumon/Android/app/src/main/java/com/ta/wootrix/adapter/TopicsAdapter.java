package com.ta.wootrix.adapter;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.CheckBox;

import com.google.android.gms.analytics.Tracker;
import com.ta.wootrix.R;
import com.ta.wootrix.firebase.AnalyticsApplication;
import com.ta.wootrix.modle.IModel;
import com.ta.wootrix.modle.TopicsModle;

import java.util.ArrayList;

public class TopicsAdapter extends BaseAdapter
{
	LayoutInflater				mInflater;
	Tracker						mTracker;
	AnalyticsApplication		application;
	private ArrayList<IModel>	topicsList;
	private Context				context;

	public TopicsAdapter(Context con, ArrayList<IModel> mList)
	{
		this.topicsList = mList;
		this.context = con;
		this.mTracker = mTracker;
		this.application = application;
		mInflater = (LayoutInflater) con.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
	}

	@Override
	public int getCount()
	{
		return topicsList.size();
	}

	@Override
	public Object getItem(int position)
	{
		return topicsList.get(position);
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
		TopicsModle answer = (TopicsModle) topicsList.get(position);
		if (view == null)
		{
			view = mInflater.inflate(R.layout.topics_row, null, false);
			holder = new ViewHolder();
			holder.mChkBoxTopicName = (CheckBox) view.findViewById(R.id.topics_row_item_chkbox);

			view.setTag(holder);
		}
		else
		{
			holder = (ViewHolder) convertView.getTag();
		}
		holder.mChkBoxTopicName.setText(answer.getTopicName());
		if (answer.isTopicSelected())
			holder.mChkBoxTopicName.setChecked(true);
		else
			holder.mChkBoxTopicName.setChecked(false);
		holder.mChkBoxTopicName.setTag(position);
		holder.mChkBoxTopicName.setOnClickListener(new OnClickListener() {

			@Override
			public void onClick(View v)
			{
				int pos = (Integer) v.getTag();
				if (((CheckBox) v).isChecked())
					((TopicsModle) topicsList.get(pos)).setTopicSelected(true);
				else
					((TopicsModle) topicsList.get(pos)).setTopicSelected(false);
				notifyDataSetChanged();
			}
		});
		return view;
	}

	public void update(ArrayList<IModel> list)
	{
		topicsList = list;
		notifyDataSetChanged();
	}

	private static class ViewHolder
	{
		CheckBox mChkBoxTopicName;
	}
}