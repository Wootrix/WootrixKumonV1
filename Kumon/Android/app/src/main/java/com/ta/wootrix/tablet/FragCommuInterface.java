package com.ta.wootrix.tablet;

import com.ta.wootrix.modle.IModel;

public interface FragCommuInterface
{
	public void launchMagOrOPenAricleData(IModel data, boolean isOpenArticle);

	public void showHidePagingSlider(boolean showPage);

	public void onImageUpdate(String imagepath);

}
