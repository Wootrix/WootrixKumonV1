package com.ta.wootrix.utils;

import android.app.Activity;
import android.app.Fragment;
import android.content.ContentValues;
import android.content.Intent;
import android.provider.MediaStore;
import android.provider.MediaStore.Video;
import android.util.Log;
import android.widget.Toast;

import com.ta.wootrix.R;

public class ImageCaputureUtility
{
	ImageCaputureUtility imageCaptureRefrence;

	public static ImageCaputureUtility getInstance()
	{
		return new ImageCaputureUtility();
	}

	// code for camera button event
	public void captureImage(Activity activity, Fragment fragment)
	{
		ContentValues values = new ContentValues();
		String fileName = System.currentTimeMillis() + ".png";
		values.put(MediaStore.Images.Media.TITLE, fileName);
		try
		{
			Constants.URI_IMAGE_CAPTURED = activity.getContentResolver().insert(MediaStore.Images.Media.EXTERNAL_CONTENT_URI, values);
			Intent intent = new Intent(MediaStore.ACTION_IMAGE_CAPTURE);
			intent.putExtra(MediaStore.EXTRA_OUTPUT, Constants.URI_IMAGE_CAPTURED);
			Log.e("constant", Constants.URI_IMAGE_CAPTURED.getPath() + "\n" + Constants.URI_IMAGE_CAPTURED);
			if (fragment != null)
				fragment.startActivityForResult(intent, Constants.REQUEST_CODE_IMAGE_CAPTURED);
			else
				activity.startActivityForResult(intent, Constants.REQUEST_CODE_IMAGE_CAPTURED);
		}
		catch (Exception e)
		{
			e.printStackTrace();
			Log.w("in", "capture");
			Toast.makeText(activity, activity.getResources().getString(R.string.action_settings), Toast.LENGTH_LONG).show();
		}
	}

	// code for gallery button evevnt
	public void selectFromGalley(Activity activity, Fragment fragment)
	{
		try
		{
			Intent picImage = new Intent();
			picImage.setAction(Intent.ACTION_PICK);
			picImage.setType("image/*");
			picImage.setData(MediaStore.Images.Media.EXTERNAL_CONTENT_URI);
			if (fragment != null)
				fragment.startActivityForResult(picImage, Constants.REQUEST_CODE_IMAGE_GALLERY);
			else
				activity.startActivityForResult(picImage, Constants.REQUEST_CODE_IMAGE_GALLERY);
		}
		catch (Exception e)
		{
			e.printStackTrace();
			Toast.makeText(activity, activity.getResources().getString(R.string.sd_card_not_available), Toast.LENGTH_LONG).show();
		}
		Log.w("in", "gallery");
	}

	public void captureVideo(Activity fragmentActivity, Fragment fragment)
	{
		ContentValues values = new ContentValues();
		String fileName = System.currentTimeMillis() + ".mp4";
		values.put(MediaStore.Video.Media.TITLE, fileName);
		values.put(Video.Media.MIME_TYPE, "video/mp4");
		try
		{
			Constants.URI_VIDEO_CAPTURED = fragmentActivity.getContentResolver().insert(MediaStore.Video.Media.EXTERNAL_CONTENT_URI, values);
			Intent intent = new Intent(MediaStore.ACTION_VIDEO_CAPTURE);
			intent.putExtra("android.intent.extra.durationLimit", 15);
			intent.putExtra(MediaStore.EXTRA_SIZE_LIMIT, 10485760L);// 10 mb

			// intent.putExtra(android.provider.MediaStore.EXTRA_VIDEO_QUALITY, 0);
			// intent.putExtra(MediaStore.EXTRA_SIZE_LIMIT, value)
			// intent.putExtra("android.intent.extra.videoQuality", 0);
			// intent.putExtra(MediaStore.EXTRA_SIZE_LIMIT,4393216L);//4 mb
			// intent.putExtra(MediaStore.EXTRA_OUTPUT, Constants.URI_VIDEO_CAPTURED);

			if (fragment != null)
				fragment.startActivityForResult(intent, Constants.REQUEST_CODE_VIDEO_CAPTURED);
			else
				fragmentActivity.startActivityForResult(intent, Constants.REQUEST_CODE_VIDEO_CAPTURED);
		}
		catch (Exception e)
		{
			e.printStackTrace();
			Log.w("in", "capture");
			Toast.makeText(fragmentActivity, fragmentActivity.getResources().getString(R.string.sd_card_not_available), Toast.LENGTH_LONG).show();
		}
	}

	// code for gallery button evevnt
	public void selectVideoFromGalley(Activity fragmentActivity, Fragment fragmemt)
	{
		try
		{
			Intent VideoIntent = new Intent();
			VideoIntent.setAction(Intent.ACTION_PICK);
			VideoIntent.setData(MediaStore.Images.Media.EXTERNAL_CONTENT_URI);
			VideoIntent.setType("video/*");
			if (fragmemt != null)
				fragmemt.startActivityForResult(VideoIntent, Constants.REQUEST_CODE_VIDEO_GALLERY);
			else
				fragmentActivity.startActivityForResult(VideoIntent, Constants.REQUEST_CODE_VIDEO_GALLERY);
		}
		catch (Exception e)
		{
			e.printStackTrace();
			Toast.makeText(fragmentActivity, fragmentActivity.getResources().getString(R.string.sd_card_not_available), Toast.LENGTH_LONG).show();
		}
		Log.w("in", "gallery");
	}

}
