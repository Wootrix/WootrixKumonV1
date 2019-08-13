package com.ta.wootrix.adapter;

import android.content.Context;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import com.nostra13.universalimageloader.core.ImageLoader;
import com.ta.wootrix.R;
import com.ta.wootrix.modle.MagazineModle;
import com.ta.wootrix.utils.ItemClickDelegate;
import com.ta.wootrix.utils.Utility;

import java.util.List;

public class MagazineAdapter extends RecyclerView.Adapter {

	private Context mContext;

	private List<MagazineModle> mList;

	private LayoutInflater inflater;

	private ItemClickDelegate itemClickDelegate;

	private ImageLoader imageLoader;

    public MagazineAdapter(Context c, ItemClickDelegate itemClickDelegate, List<MagazineModle> list) {

        mContext = c;
        mList = list;
		this.itemClickDelegate = itemClickDelegate;
        imageLoader = Utility.getImageLoader(mContext);

        inflater = (LayoutInflater) mContext.getSystemService(Context.LAYOUT_INFLATER_SERVICE);

    }

	public static class ViewHolder extends RecyclerView.ViewHolder {
        public TextView txName;
        public ImageView image;

		public ViewHolder(View view) {
			super(view);

			txName = (TextView) view.findViewById(R.id.txName);
			image = (ImageView) view.findViewById(R.id.image);
		}
	}

	@Override
	public RecyclerView.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {

        View v = LayoutInflater.from(parent.getContext()).inflate(R.layout.list_magazine_item, parent, false);
        MagazineAdapter.ViewHolder vh = new MagazineAdapter.ViewHolder(v);

        return vh;

	}

	@Override
	public void onBindViewHolder(RecyclerView.ViewHolder holder, int position) {

        final MagazineModle entity = mList.get(position);

        final MagazineAdapter.ViewHolder featuredViewHolder = (MagazineAdapter.ViewHolder) holder;

		featuredViewHolder.txName.setText(entity.getMagazineName());

        imageLoader.displayImage(entity.getCoverPhotoUrl(), featuredViewHolder.image, Utility.getMagazinesDisplayOption());

        featuredViewHolder.itemView.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                itemClickDelegate.onItemClick(entity.getMagazineId(), view);
            }
        });

	}

    public MagazineModle getById(String id ){

        for( MagazineModle ee : mList ){

            if(ee.getMagazineId().equalsIgnoreCase(id)){
                return ee;
            }

        }

        return null;

    }

	@Override
	public long getItemId(int position) {
		return Integer.parseInt( mList.get(position).getMagazineId() );
	}

	@Override
	public int getItemCount() {
		return mList != null ? mList.size() : 0;
	}

}
