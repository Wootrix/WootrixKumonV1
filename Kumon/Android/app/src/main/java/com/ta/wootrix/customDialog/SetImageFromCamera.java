package com.ta.wootrix.customDialog;

import android.app.Activity;
import android.app.Fragment;
import android.content.Intent;
import android.database.Cursor;
import android.graphics.Bitmap;
import android.net.Uri;
import android.os.Handler;
import android.provider.MediaStore;
import android.util.Log;

import com.ta.wootrix.R;
import com.ta.wootrix.customDialog.CustomDialog.DIALOG_TYPE;
import com.ta.wootrix.phone.MyAccountActivity;
import com.ta.wootrix.tablet.MyAccountFragment;
import com.ta.wootrix.utils.Constants;
import com.ta.wootrix.utils.ImageCaputureUtility;
import com.ta.wootrix.utils.Utility;

import org.apache.commons.io.FileUtils;

import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileOutputStream;

/**
 * @author ashok
 */
public class SetImageFromCamera implements IActionOKCancel
{
	private static final long		serialVersionUID	= 1L;
	private final int				REQ_CAMERA_GALLERY	= 234;
	ImageCaputureUtility			mImageCapRefrence;
	private Handler					mHandler			= new Handler();
	private CustomProgressDialog	dialog;
	private Activity				activity;
	private android.app.Fragment	fragment;
	private String					mImagePath;

	public SetImageFromCamera(Activity Activity)
	{
		activity = Activity;
		mImageCapRefrence = ImageCaputureUtility.getInstance();
	}

	public SetImageFromCamera(Activity Activity, Fragment postFragment)
	{
		activity = Activity;
		fragment = postFragment;
		mImageCapRefrence = ImageCaputureUtility.getInstance();
	}

	public void showCameraDialog()
	{
		CustomDialog dialog = CustomDialog.getInstance(activity, this, activity.getResources().getString(R.string.msg_select_image), null, DIALOG_TYPE.OK_CANCEL, -1,
				activity.getResources().getString(R.string.lbl_camera), activity.getResources().getString(R.string.lbl_gallery), REQ_CAMERA_GALLERY);
		dialog.setCancellable(true);
		dialog.show();

	}

	private void setImageAndBorder(Bitmap image)
	{
		// mImageVPhoto.setBackgroundResource(R.drawable.album_border);
		Log.e("reached here", "hjasdjk");
		if (activity != null && activity instanceof MyAccountActivity)
		{
			((MyAccountActivity) activity).setProfileImage(mImagePath, image);
		}
		else if (fragment != null && fragment instanceof MyAccountFragment)
		{
			((MyAccountFragment) fragment).setProfileImage(mImagePath, image);
		}

	}

	// code for getting the imagepath of image taken via Camera to imageView
	public void onPhotoTaken(Intent data)
	{
		try
		{
			String[] projection = { MediaStore.Images.Media.DATA };
			@SuppressWarnings("deprecation")
			Cursor cursor = activity.managedQuery(Constants.URI_IMAGE_CAPTURED, projection, null, null, null);
			// startManagingCursor(cursor);
			int column_index_data = cursor.getColumnIndexOrThrow(MediaStore.Images.Media.DATA);
			// mContext.startManagingCursor(cursor);
			cursor.moveToFirst();
			mImagePath = cursor.getString(column_index_data);
			Log.e("Image path", mImagePath);
			if (dialog == null)
				dialog = new CustomProgressDialog(activity, "Please wait");
			new Thread() {
				private Bitmap unRotatedImaghe;

				public void run()
				{
					try
					{
						String newPath = activity.getCacheDir() + "/" + System.currentTimeMillis() + ".png";
						FileUtils.copyFile(new File(mImagePath), new File(newPath));

						mImagePath = newPath;
						Bitmap image = Utility.decodeFile(new File(mImagePath));
						unRotatedImaghe = Utility.getUnRotatedImage(mImagePath, image);

						ByteArrayOutputStream bytes = new ByteArrayOutputStream();
						unRotatedImaghe.compress(Bitmap.CompressFormat.PNG, 100, bytes);

						// you can create a new file name "test.jpg" in sdcard folder.
						File f = new File(mImagePath);
						// write the bytes in file
						FileOutputStream fo = new FileOutputStream(f);
						fo.write(bytes.toByteArray());

						// remember close de FileOutput
						fo.flush();
						fo.close();
					}
					catch (Exception e)
					{
						if (dialog != null && dialog.isVisible())
							dialog.dismissDialog();
						e.printStackTrace();
					}
					mHandler.post(new Runnable() {
						@Override
						public void run()
						{
							if (dialog != null && dialog.isVisible())
								dialog.dismissDialog();
							try
							{
								if (unRotatedImaghe != null)
									setImageAndBorder(unRotatedImaghe);
							}
							catch (Exception e)
							{

							}
						}
					});
				}
			}.start();
		}
		catch (Exception e)
		{
			if (dialog != null && dialog.isVisible())
				dialog.dismissDialog();
			e.printStackTrace();
			Log.e("imagePath", "onactivity result");
		}
	}

	// code for getting the imagepath of image taken from Gallery to imageView
	public void onPhotoTakenFromgallery(Intent data)
	{
		try
		{
			Uri _uri = data.getData();
			if (_uri != null)
			{
				Cursor cursor = activity.getContentResolver().query(_uri, new String[] { android.provider.MediaStore.Images.ImageColumns.DATA, MediaStore.Images.Media.ORIENTATION }, null, null, null);
				int column_index = cursor.getColumnIndexOrThrow(MediaStore.Images.Media.DATA);
				// startManagingCursor(cursor);
				cursor.moveToFirst();
				mImagePath = cursor.getString(column_index);
				Log.e("Image path", mImagePath);
				if (dialog != null)
					dialog = null;
				dialog = new CustomProgressDialog(activity, "Please wait");
				new Thread() {
					private Bitmap unRotatedImaghe;

					public void run()
					{
						try
						{
							String newPath = activity.getCacheDir() + "/" + System.currentTimeMillis() + ".png";
							FileUtils.copyFile(new File(mImagePath), new File(newPath));

							mImagePath = newPath;
							Bitmap image = Utility.decodeFile(new File(mImagePath));
							unRotatedImaghe = Utility.getUnRotatedImage(mImagePath, image);

							ByteArrayOutputStream bytes = new ByteArrayOutputStream();
							unRotatedImaghe.compress(Bitmap.CompressFormat.PNG, 100, bytes);

							// you can create a new file name "test.jpg" in sdcard folder.
							File f = new File(mImagePath);
							// write the bytes in file
							FileOutputStream fo = new FileOutputStream(f);
							fo.write(bytes.toByteArray());

							// remember close de FileOutput
							fo.flush();
							fo.close();
						}
						catch (Exception e)
						{
							if (dialog != null && dialog.isVisible())
								dialog.dismissDialog();
							e.printStackTrace();
						}

						mHandler.post(new Runnable() {
							@Override
							public void run()
							{
								if (dialog != null && dialog.isVisible())
									dialog.dismissDialog();
								try
								{
									if (unRotatedImaghe != null)
										setImageAndBorder(unRotatedImaghe);
								}
								catch (Exception e)
								{
								}
							}
						});
					}
				}.start();
			}
		}
		catch (Exception e)
		{
			if (dialog != null && dialog.isVisible())
				dialog.dismissDialog();
			e.printStackTrace();
			Log.e("imagePath", "onactivity result");
		}
	}

	public void onImgTknFrmCamOrGalleryResult(int requestCode, int resultCode, Intent data)
	{
		if (resultCode == Activity.RESULT_OK)
		{
			if (requestCode == Constants.REQUEST_CODE_IMAGE_CAPTURED)// capture// image// form//
																		// camera
			{
				onPhotoTaken(data);
			}
			else if (requestCode == Constants.REQUEST_CODE_IMAGE_GALLERY && data != null) // pick
																							// picture
																							// from
																							// gallary
			{
				onPhotoTakenFromgallery(data);
			}
		}
	}

	@Override
	public void onActionOk(int requestCode)
	{
		if (requestCode == REQ_CAMERA_GALLERY)
		{
			if (mImageCapRefrence != null)
				mImageCapRefrence.captureImage(activity, fragment);
		}

	}

	@Override
	public void onActionCancel(int requestCode)
	{
		if (requestCode == REQ_CAMERA_GALLERY)
		{
			if (mImageCapRefrence != null)
				mImageCapRefrence.selectFromGalley(activity, fragment);
		}
	}

	@Override
	public void onActionNeutral(int requestCode)
	{

	}
}
