package com.hogesoft.wowza.utils;

import com.wowza.wms.application.IApplicationInstance;
import com.wowza.wms.vhost.IVHost;

public class Settings {
	
	private static boolean debug = false;
	
	public static final String WMS_PATH = debug ? "/Library/WowzaMediaServer/" : "/usr/local/WowzaMediaServer/";

	public static final String DEFAULT_VHOST = IVHost.VHOST_DEFAULT;

	public static final String DEFAULT_INSTANCE = IApplicationInstance.DEFAULT_APPINSTANCE_NAME;
}