package com.ta.wootrix.phone;

import android.Manifest;
import android.annotation.TargetApi;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.graphics.Bitmap;
import android.os.Build;
import android.os.Bundle;
import android.os.Handler;
import android.support.v4.app.ActivityCompat;
import android.support.v4.content.ContextCompat;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.CheckBox;
import android.widget.CompoundButton;
import android.widget.CompoundButton.OnCheckedChangeListener;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import com.nostra13.universalimageloader.core.ImageLoader;
import com.nostra13.universalimageloader.core.assist.FailReason;
import com.nostra13.universalimageloader.core.assist.ImageLoadingListener;
import com.ta.wootrix.R;
import com.ta.wootrix.customDialog.CustomProgressDialog;
import com.ta.wootrix.customDialog.SetImageFromCamera;
import com.ta.wootrix.restapi.APIUtils;
import com.ta.wootrix.utils.Constants;
import com.ta.wootrix.utils.Utility;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.File;

public class MyAccountActivity extends BaseActivity implements OnClickListener {

    private static MyAccountActivity instance;
    protected Handler mPhotoUpdateHandler = new Handler();
    private ImageView mImgVwMainProfileImg;
    private ImageView mImgVwRoundProfileImg;
    private ImageView mImgVwUpdateImage;
    private TextView mTxtVwProfileName;
    private TextView mTxtVwEmail;
    private CheckBox mChkBoxShowPage;
    private TextView mTxtVwChangeLanguage;
    private TextView mTxtVwChangeEmail;
//    private TextView mTxtVwRateApp;
//    private TextView mTxtVwChangePassword;
//    private TextView mTxtVwAboutUs;
    private TextView mTxtVwHeaderTitle;
    private ImageView mImgVwBackBtn;
    private ImageLoader mImageLoader;
    private SetImageFromCamera mImgFromCamera;
    private String mImagePath;
    private CustomProgressDialog mProgressDioalog;
    private static final int PERMISSION_REQUEST_CODE = 1;

    public static MyAccountActivity getInstance() {
        return instance;
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.profile_screen);
        instance = this;
        initView();
    }

    private void initView() {
        mTxtVwHeaderTitle = (TextView) findViewById(R.id.header_text_txtVw);
        mImgVwBackBtn = (ImageView) findViewById(R.id.header_back_btn_imgVw);
        mTxtVwHeaderTitle.setText(getString(R.string.header_my_account));

        mImgVwMainProfileImg = (ImageView) findViewById(R.id.profile_screen_main_profile_img_imgVw);
        mImgVwRoundProfileImg = (ImageView) findViewById(R.id.profile_screen_rounded_profile_img_imgVw);
        mImgVwUpdateImage = (ImageView) findViewById(R.id.profile_screen_update_image_imgVw);

        mTxtVwProfileName = (TextView) findViewById(R.id.profile_screen_profilename_txtVw);
        mTxtVwEmail = (TextView) findViewById(R.id.profile_screen_email_txtVw);

        mChkBoxShowPage = (CheckBox) findViewById(R.id.profile_screen_showpage_toggle_btn);

        mTxtVwChangeLanguage = (TextView) findViewById(R.id.profile_screen_change_language_txtVw);
//        mTxtVwAddAccount = (TextView) findViewById(R.id.profile_screen_add_account);
//        mTxtVwAddAccount.setOnClickListener(this);

        mTxtVwChangeEmail = (TextView) findViewById(R.id.profile_screen_change_email_txtVw);
//        mTxtVwChangePassword = (TextView) findViewById(R.id.profile_screen_changepass_txtVw);
//        mTxtVwRateApp = (TextView) findViewById(R.id.profile_screen_rateapp_txtVw);
//        mTxtVwAboutUs = (TextView) findViewById(R.id.profile_screen_aboutus_txtVw);

        mImgVwUpdateImage.setOnClickListener(this);
        mTxtVwChangeEmail.setOnClickListener(this);
        mTxtVwChangeLanguage.setOnClickListener(this);
//        mTxtVwRateApp.setOnClickListener(this);
//        mTxtVwChangePassword.setOnClickListener(this);
//        mTxtVwAboutUs.setOnClickListener(this);
        mImgVwBackBtn.setOnClickListener(this);
        mImgVwRoundProfileImg.setOnClickListener(this);
        mChkBoxShowPage.setOnCheckedChangeListener(new OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {
                if (isChecked)
                    Utility.setSharedPrefBooleanData(MyAccountActivity.this, Constants.USER_SHOW_PAGE, false);
                else
                    Utility.setSharedPrefBooleanData(MyAccountActivity.this, Constants.USER_SHOW_PAGE, true);
            }
        });
        updateData();

    }

    private void updateData() {
        mTxtVwProfileName.setText(Utility.getSharedPrefStringData(this, Constants.USER_NAME));
        if (mImageLoader == null)
            mImageLoader = Utility.getImageLoader(this);
        String imageURL = Utility.getSharedPrefStringData(this, Constants.USER_IMAGE);
        mImageLoader.displayImage(imageURL, mImgVwMainProfileImg, Utility.getProfileBlurredPicDisplayOption(), new ImageLoadingListener() {
            @Override
            public void onLoadingStarted(String imageUri, View view) {
                // TODO Auto-generated method stub

            }

            @Override
            public void onLoadingFailed(String imageUri, View view, FailReason failReason) {
                // TODO Auto-generated method stub

            }

            @Override
            public void onLoadingComplete(String imageUri, View view, Bitmap loadedImage) {
                if (loadedImage != null)
                    mImgVwMainProfileImg.setImageBitmap(fastblur(loadedImage, 8));
            }

            @Override
            public void onLoadingCancelled(String imageUri, View view) {
                // TODO Auto-generated method stub

            }
        });
        mImageLoader.displayImage(imageURL, mImgVwRoundProfileImg, Utility.getProfilePicDisplayOption());

        if (!Utility.getSharedPrefBooleanData(this, Constants.USER_SHOW_PAGE))
            mChkBoxShowPage.setChecked(true);
        else
            mChkBoxShowPage.setChecked(false);
    }

    @Override
    protected void onResume() {
        super.onResume();
        mTxtVwEmail.setText(Utility.getSharedPrefStringData(this, Constants.USER_EMAIL));
        if (Utility.getSharedPrefBooleanData(this, Constants.USER_REQ_EMAIL))
            mTxtVwChangeEmail.setText(getString(R.string.add_email));
        else
            mTxtVwChangeEmail.setVisibility(View.GONE);
//        if (Utility.getSharedPrefBooleanData(this, Constants.USER_REQ_PASS))
//            mTxtVwChangePassword.setText(getString(R.string.add_pass));
//        else
//            mTxtVwChangePassword.setText(getString(R.string.lbl_change_pass));
    }

    @Override
    public void onClick(View v) {
        switch (v.getId()) {

            case R.id.header_back_btn_imgVw:
                finish();
                break;

            case R.id.profile_screen_rounded_profile_img_imgVw:
            case R.id.profile_screen_update_image_imgVw:

                int currentAPIVersion = Build.VERSION.SDK_INT;
                if (currentAPIVersion >= android.os.Build.VERSION_CODES.M) {
                    boolean result = checkPermission();
                    if (result) {
                        if (mImgFromCamera == null)
                            mImgFromCamera = new SetImageFromCamera(this);
                        mImgFromCamera.showCameraDialog();
                    }
                } else {
                    if (mImgFromCamera == null)
                        mImgFromCamera = new SetImageFromCamera(this);
                    mImgFromCamera.showCameraDialog();
                }

                break;

            case R.id.profile_screen_change_language_txtVw:
                startActivity(new Intent(this, ChangeLanguageActivity.class));
                break;

            case R.id.profile_screen_change_email_txtVw:
                startActivity(new Intent(this, ChangeEmailActivity.class));
                break;
            
            default:
                break;
        }
    }

    @TargetApi(Build.VERSION_CODES.JELLY_BEAN)
    public boolean checkPermission() {
        int currentAPIVersion = Build.VERSION.SDK_INT;
        if (currentAPIVersion >= android.os.Build.VERSION_CODES.M) {
            if (ContextCompat.checkSelfPermission(MyAccountActivity.this, Manifest.permission.CAMERA) != PackageManager.PERMISSION_GRANTED) {
                if (ActivityCompat.shouldShowRequestPermissionRationale(MyAccountActivity.this, Manifest.permission.CAMERA)) {
                    ActivityCompat.requestPermissions(MyAccountActivity.this, new String[]{Manifest.permission.CAMERA}, PERMISSION_REQUEST_CODE);
                } else {
                    ActivityCompat.requestPermissions(MyAccountActivity.this, new String[]{Manifest.permission.CAMERA}, PERMISSION_REQUEST_CODE);
                }
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    @Override
    public void onRequestPermissionsResult(int requestCode, String[] permissions, int[] grantResults) {
        super.onRequestPermissionsResult(requestCode, permissions, grantResults);
        switch (requestCode) {
            case PERMISSION_REQUEST_CODE:
                if (grantResults.length > 0 && grantResults[0] == PackageManager.PERMISSION_GRANTED) {
                    if (mImgFromCamera == null)
                        mImgFromCamera = new SetImageFromCamera(this);
                    mImgFromCamera.showCameraDialog();
                } else {
                    Toast.makeText(this, "Permission Denied, You cannot access Camera.", Toast.LENGTH_LONG).show();
                }
                break;
        }
    }

    public void setProfileImage(String mImagePath, Bitmap image) {
        this.mImagePath = mImagePath;
        updatePhotoOnServer();
    }

    private void updatePhotoOnServer() {
        if (mProgressDioalog != null)
            mProgressDioalog.dismissDialog();
        mProgressDioalog = new CustomProgressDialog(this, "");

        new Thread(new Runnable() {
            String response = "";

            @Override
            public void run() {
                JSONObject json = null;
                try {
                    json = new JSONObject();
                    json.put("token", Utility.getSharedPrefStringData(MyAccountActivity.this, Constants.USER_TOKEN));
                    json.put("appLanguage", Utility.getDrfaultLanguage());
                } catch (JSONException e) {
                    e.printStackTrace();
                }

                Log.e("params for first", json.toString());
                String url = APIUtils.getBaseURL(APIUtils.UPDATE_PROFILE_PHOTO);
                response = Utility.httpPostRequestToServerWithImageData(url, json.toString(), MyAccountActivity.this, new File(mImagePath));
                mPhotoUpdateHandler.post(new Runnable() {
                    @Override
                    public void run() {
                        mProgressDioalog.dismissDialog();
                        if (response != null && response.length() > 0) {
                            JSONObject JsonObject;
                            try {
                                Log.e("msg", "is:" + response);
                                JsonObject = new JSONObject(response);
                                if (JsonObject.optBoolean("success")) {
                                    JSONObject mainObject = JsonObject.getJSONArray("data").getJSONObject(0);

                                    JSONObject userOb = mainObject.getJSONArray("user").getJSONObject(0);
                                    Utility.setSharedPrefStringData(MyAccountActivity.this, Constants.USER_IMAGE, userOb.optString("photoUrl").toString());
                                    Utility.showToastMessage(MyAccountActivity.this, JsonObject.optString("message"));
                                    updateData();
                                } else {
                                    Utility.showMsgDialog(MyAccountActivity.this, JsonObject.optString("message"));
                                }

                            } catch (JSONException e) {
                                e.printStackTrace();
                                mProgressDioalog.dismissDialog();
                            }

                        }

                    }
                });

            }
        }).start();

    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        if (mImgFromCamera != null)
            mImgFromCamera.onImgTknFrmCamOrGalleryResult(requestCode, resultCode, data);
    }

    public Bitmap fastblur(Bitmap sentBitmap, int radius) {

        // Stack Blur v1.0 from
        // http://www.quasimondo.com/StackBlurForCanvas/StackBlurDemo.html
        //
        // Java Author: Mario Klingemann <mario at quasimondo.com>
        // http://incubator.quasimondo.com
        // created Feburary 29, 2004
        // Android port : Yahel Bouaziz <yahel at kayenko.com>
        // http://www.kayenko.com
        // ported april 5th, 2012

        // This is a compromise between Gaussian Blur and Box blur
        // It creates much better looking blurs than Box Blur, but is
        // 7x faster than my Gaussian Blur implementation.
        //
        // I called it Stack Blur because this describes best how this
        // filter works internally: it creates a kind of moving stack
        // of colors whilst scanning through the image. Thereby it
        // just has to add one new block of color to the right side
        // of the stack and remove the leftmost color. The remaining
        // colors on the topmost layer of the stack are either added on
        // or reduced by one, depending on if they are on the right or
        // on the left side of the stack.
        //
        // If you are using this algorithm in your code please add
        // the following line:
        //
        // Stack Blur Algorithm by Mario Klingemann <mario@quasimondo.com>

        Bitmap bitmap = sentBitmap.copy(sentBitmap.getConfig(), true);

        if (radius < 1) {
            return (null);
        }

        int w = bitmap.getWidth();
        int h = bitmap.getHeight();

        int[] pix = new int[w * h];
        Log.e("pix", w + " " + h + " " + pix.length);
        bitmap.getPixels(pix, 0, w, 0, 0, w, h);

        int wm = w - 1;
        int hm = h - 1;
        int wh = w * h;
        int div = radius + radius + 1;

        int r[] = new int[wh];
        int g[] = new int[wh];
        int b[] = new int[wh];
        int rsum, gsum, bsum, x, y, i, p, yp, yi, yw;
        int vmin[] = new int[Math.max(w, h)];

        int divsum = (div + 1) >> 1;
        divsum *= divsum;
        int dv[] = new int[256 * divsum];
        for (i = 0; i < 256 * divsum; i++) {
            dv[i] = (i / divsum);
        }

        yw = yi = 0;

        int[][] stack = new int[div][3];
        int stackpointer;
        int stackstart;
        int[] sir;
        int rbs;
        int r1 = radius + 1;
        int routsum, goutsum, boutsum;
        int rinsum, ginsum, binsum;

        for (y = 0; y < h; y++) {
            rinsum = ginsum = binsum = routsum = goutsum = boutsum = rsum = gsum = bsum = 0;
            for (i = -radius; i <= radius; i++) {
                p = pix[yi + Math.min(wm, Math.max(i, 0))];
                sir = stack[i + radius];
                sir[0] = (p & 0xff0000) >> 16;
                sir[1] = (p & 0x00ff00) >> 8;
                sir[2] = (p & 0x0000ff);
                rbs = r1 - Math.abs(i);
                rsum += sir[0] * rbs;
                gsum += sir[1] * rbs;
                bsum += sir[2] * rbs;
                if (i > 0) {
                    rinsum += sir[0];
                    ginsum += sir[1];
                    binsum += sir[2];
                } else {
                    routsum += sir[0];
                    goutsum += sir[1];
                    boutsum += sir[2];
                }
            }
            stackpointer = radius;

            for (x = 0; x < w; x++) {

                r[yi] = dv[rsum];
                g[yi] = dv[gsum];
                b[yi] = dv[bsum];

                rsum -= routsum;
                gsum -= goutsum;
                bsum -= boutsum;

                stackstart = stackpointer - radius + div;
                sir = stack[stackstart % div];

                routsum -= sir[0];
                goutsum -= sir[1];
                boutsum -= sir[2];

                if (y == 0) {
                    vmin[x] = Math.min(x + radius + 1, wm);
                }
                p = pix[yw + vmin[x]];

                sir[0] = (p & 0xff0000) >> 16;
                sir[1] = (p & 0x00ff00) >> 8;
                sir[2] = (p & 0x0000ff);

                rinsum += sir[0];
                ginsum += sir[1];
                binsum += sir[2];

                rsum += rinsum;
                gsum += ginsum;
                bsum += binsum;

                stackpointer = (stackpointer + 1) % div;
                sir = stack[(stackpointer) % div];

                routsum += sir[0];
                goutsum += sir[1];
                boutsum += sir[2];

                rinsum -= sir[0];
                ginsum -= sir[1];
                binsum -= sir[2];

                yi++;
            }
            yw += w;
        }
        for (x = 0; x < w; x++) {
            rinsum = ginsum = binsum = routsum = goutsum = boutsum = rsum = gsum = bsum = 0;
            yp = -radius * w;
            for (i = -radius; i <= radius; i++) {
                yi = Math.max(0, yp) + x;

                sir = stack[i + radius];

                sir[0] = r[yi];
                sir[1] = g[yi];
                sir[2] = b[yi];

                rbs = r1 - Math.abs(i);

                rsum += r[yi] * rbs;
                gsum += g[yi] * rbs;
                bsum += b[yi] * rbs;

                if (i > 0) {
                    rinsum += sir[0];
                    ginsum += sir[1];
                    binsum += sir[2];
                } else {
                    routsum += sir[0];
                    goutsum += sir[1];
                    boutsum += sir[2];
                }

                if (i < hm) {
                    yp += w;
                }
            }
            yi = x;
            stackpointer = radius;
            for (y = 0; y < h; y++) {
                // Preserve alpha channel: ( 0xff000000 & pix[yi] )
                pix[yi] = (0xff000000 & pix[yi]) | (dv[rsum] << 16) | (dv[gsum] << 8) | dv[bsum];

                rsum -= routsum;
                gsum -= goutsum;
                bsum -= boutsum;

                stackstart = stackpointer - radius + div;
                sir = stack[stackstart % div];

                routsum -= sir[0];
                goutsum -= sir[1];
                boutsum -= sir[2];

                if (x == 0) {
                    vmin[y] = Math.min(y + r1, hm) * w;
                }
                p = x + vmin[y];

                sir[0] = r[p];
                sir[1] = g[p];
                sir[2] = b[p];

                rinsum += sir[0];
                ginsum += sir[1];
                binsum += sir[2];

                rsum += rinsum;
                gsum += ginsum;
                bsum += binsum;

                stackpointer = (stackpointer + 1) % div;
                sir = stack[stackpointer];

                routsum += sir[0];
                goutsum += sir[1];
                boutsum += sir[2];

                rinsum -= sir[0];
                ginsum -= sir[1];
                binsum -= sir[2];

                yi += w;
            }
        }

        Log.e("pix", w + " " + h + " " + pix.length);
        bitmap.setPixels(pix, 0, w, 0, 0, w, h);

        return (bitmap);
    }
}
