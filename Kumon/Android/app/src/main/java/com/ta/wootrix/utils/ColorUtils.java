package com.ta.wootrix.utils;

import android.view.View;
import android.widget.TextView;

/**
 * Class for setting color on the textviews
 * 
 * @author ashok
 */

public class ColorUtils
{

	/**
	 * @param color
	 * @param textView
	 */
	public static void setColor(int color, TextView... textView)
	{
		for (TextView tv : textView)
		{
			tv.setTextColor(color);
		}
	}

	/**
	 * @param view
	 */
	public static void hideViews(View... view)
	{
		for (View tv : view)
		{
			tv.setVisibility(View.INVISIBLE);
		}
	}

	/**
	 * @param view
	 */
	public static void showViews(View... view)
	{
		for (View tv : view)
		{
			tv.setVisibility(View.VISIBLE);
		}
	}

	public static void setDescHeights(final TextView... view)
	{
		// for (final TextView tv : view)
		// {
		// ViewTreeObserver vto = tv.getViewTreeObserver();
		// vto.addOnGlobalLayoutListener(new OnGlobalLayoutListener()
		// {
		// private int maxLines = -1;
		//
		// @Override
		// public void onGlobalLayout()
		// {
		// // tv.getViewTreeObserver().removeGlobalOnLayoutListener(this);
		// if (maxLines < 0 && tv.getHeight() > 0 && tv.getLineHeight() > 0)
		// {
		// int height = tv.getHeight();
		// int lineHeight = tv.getLineHeight();
		// maxLines = height / lineHeight;
		// tv.setMaxLines(maxLines);
		// tv.setEllipsize(TruncateAt.END);
		// }
		// }
		// });
		// }
	}

}
