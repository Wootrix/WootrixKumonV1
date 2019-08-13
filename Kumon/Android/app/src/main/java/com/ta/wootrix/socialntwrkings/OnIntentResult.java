package com.ta.wootrix.socialntwrkings;

import android.content.Intent;

public interface OnIntentResult
{
	void updateUI(int requestCode, int resultCode, Intent bundle);
}
