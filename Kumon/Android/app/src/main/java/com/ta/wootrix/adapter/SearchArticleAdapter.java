package com.ta.wootrix.adapter;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

import com.ta.wootrix.R;
import com.ta.wootrix.modle.ArticleModle;
import com.ta.wootrix.modle.IModel;
import com.ta.wootrix.modle.MagazineModle;
import com.ta.wootrix.phone.ArticleDetailActivity;
import com.ta.wootrix.phone.EmbeddedArticleDetailActivity;
import com.ta.wootrix.tablet.TabletArticleDetailActivity;
import com.ta.wootrix.tablet.TabletEmbeddedArticleDetailActivity;
import com.ta.wootrix.tablet.TabletHomeActivity;
import com.ta.wootrix.utils.AccessRegisterListener;

import java.util.ArrayList;
import java.util.List;

public class SearchArticleAdapter extends BaseAdapter implements OnClickListener
{
	LayoutInflater			mInflater;
	private List<IModel>	mList;
	private Activity		act;
	private MagazineModle	magazineData;
	private AccessRegisterListener accessRegisterListener;

	public SearchArticleAdapter(Activity act, ArrayList<IModel> arrayList)
	{
		this.mList = arrayList;
		this.act = act;
		mInflater = (LayoutInflater) act.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
	}

	public SearchArticleAdapter(Activity act, ArrayList<IModel> arrayList, AccessRegisterListener accessRegisterListener)
	{
		this.mList = arrayList;
		this.act = act;
		mInflater = (LayoutInflater) act.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        this.accessRegisterListener = accessRegisterListener;
	}

	public void updateIsMagazineData(MagazineModle magazineData)
	{
		this.magazineData = magazineData;
	}

	@Override
	public int getCount()
	{
		return mList.size();
	}

	@Override
	public Object getItem(int position)
	{
		return mList.get(position);
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
		ArticleModle answer = (ArticleModle) mList.get(position);
		if (view == null)
		{
			view = mInflater.inflate(R.layout.simple_row_item, null, false);
			holder = new ViewHolder();
			holder.mTxtVwPlayerNames = (TextView) view.findViewById(R.id.simple_row_item_txtVw);
			view.setTag(holder);
		}
		else
		{
			holder = (ViewHolder) convertView.getTag();
		}
		holder.mTxtVwPlayerNames.setText(answer.getTitle());
		holder.mTxtVwPlayerNames.setTag(position);
		holder.mTxtVwPlayerNames.setOnClickListener(this);
		return view;
	}

	public void update(List<IModel> list)
	{
		mList = list;
		notifyDataSetChanged();
	}

	@Override
	public void onClick(View v)
	{
		int position = (Integer) v.getTag();

		switch ( v.getId() )
		{
			case R.id.simple_row_item_txtVw :
				Intent myIntent;

				ArticleModle model = (ArticleModle) mList.get(position);
				String type = model.getArticleType();
				if (type.equalsIgnoreCase("embedded")) {
                    myIntent = new Intent(act, act instanceof TabletHomeActivity ? TabletEmbeddedArticleDetailActivity.class : EmbeddedArticleDetailActivity.class);
                } else {
                    myIntent = new Intent(act, act instanceof TabletHomeActivity ? TabletArticleDetailActivity.class : ArticleDetailActivity.class);
                }

                String typeDevice = act instanceof TabletHomeActivity ? "Tablet" : "Smartphone";

				myIntent.putExtra("articleData", mList.get(position));
				myIntent.putExtra("position", position);
				myIntent.putExtra("launchFrom", false);
				myIntent.putExtra("isMagzine", magazineData != null ? true : false);
				myIntent.putExtra("magzineID", magazineData != null ? magazineData.getMagazineId() : null);
				act.startActivity(myIntent);

                if( accessRegisterListener != null ){
                    accessRegisterListener.sendAccessData(magazineData != null ? magazineData.getMagazineId() : null,
                            ((ArticleModle) mList.get(position)).getArticleID(), typeDevice);
                }

				break;

			default :
				break;
		}
	}

	private static class ViewHolder
	{
		TextView mTxtVwPlayerNames;
	}
}
