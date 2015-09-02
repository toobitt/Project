package com.hogesoft.wowza.output.models;

import java.io.File;
import java.util.HashMap;

import com.hogesoft.wowza.models.ModelBase;
import com.hogesoft.wowza.output.utils.Settings;
import com.hogesoft.wowza.utils.HttpUtils;

public final class DvrModel extends ModelBase {
	
	private static final String DVR_PATH = "dvr/";

	public static void delete(int StreamId, HashMap<String, String> map) throws Exception {
		final StreamModel stream = StreamModel.select(StreamId);
		final ApplicationModel application = ApplicationModel.select(stream.getApplicationId());
		StreamModel.stop(StreamId);

		final long start = Long.parseLong(map.get("time"));
		final long end = start + Long.parseLong(map.get("duration"));
		final String callback = map.get("callback");
		
		new Thread() {
			@Override
			public void run() {
				File dir = new File(Settings.WMS_PATH + DVR_PATH + application.getName() + "/_definst_/" + stream.getName() + ".stream.0");
				
				if(dir.isDirectory())
				{
					File[] files = dir.listFiles();
					for(int i = 0, c = files.length; i < c; i++)
					{

						if(files[i].isDirectory() && start <= files[i].lastModified() && end > files[i].lastModified())
							delTree(files[i]);
					}
				}
				if(null == callback)
					return;
				try {
					new HttpUtils().sendToURL(callback);
				} catch (Exception e) {
					e.printStackTrace();
				}
			}
		}.start();
		
	}
}

