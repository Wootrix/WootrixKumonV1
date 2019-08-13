package com.ta.wootrix.asynctask;

import com.ta.wootrix.modle.IModel;

import java.util.ArrayList;

/**
 * @author deepchand Interface for async executor to get callback response either bean object or
 *         collection of bean object
 */
public interface IAsyncCaller
{
	public static final int	STATE_OK	= 1;
	public static final int	STATE_ERROR	= 0;

	public void onComplete(IModel object, String message, boolean status);

	public void onComplete(ArrayList<IModel> object, String message, boolean status);

}
