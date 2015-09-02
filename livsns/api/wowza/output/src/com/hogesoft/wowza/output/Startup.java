package com.hogesoft.wowza.output;

import java.sql.Connection;
import java.sql.Statement;
import java.util.Iterator;

import com.hogesoft.wowza.output.models.StreamModel;
import com.hogesoft.wowza.output.utils.Settings;
import com.hogesoft.wowza.utils.DBUtils;
import com.wowza.wms.logging.WMSLoggerFactory;
import com.wowza.wms.server.*;

public class Startup implements IServerNotify2 {

	public void onServerConfigLoaded(IServer server) {
	}

	public void onServerCreate(IServer server) {
	}

	public void onServerInit(IServer server) {
		Connection conn = null;
		
		try {
			Thread.sleep(5000);
		} catch (InterruptedException e2) {
		}
		System.out.println("========== output start");
		try {
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();
			stat.executeUpdate("CREATE TABLE IF NOT EXISTS application (id INTEGER PRIMARY KEY AUTOINCREMENT, name VARCHAR(32) NOT NULL UNIQUE, length INTEGER DEFAULT 0, type INTEGER DEFAULT 0, drm INTEGER DEFAULT 0);");
			stat.executeUpdate("CREATE TABLE IF NOT EXISTS stream (id INTEGER PRIMARY KEY AUTOINCREMENT, application_id INTEGER NOT NULL, name VARCHAR(32) NOT NULL, url VARCHAR(512) NOT NULL, enable BOOL NOT NULL);");
			DBUtils.disConnect(conn);
			
			StreamModel stream;
			for (Iterator<StreamModel> streamIterator = StreamModel.select().iterator(); streamIterator.hasNext();) {
				stream = streamIterator.next();
				if(stream.getEnable())
					StreamModel.start(stream.getId());
			}
			
		} catch (Exception e) {
			if (conn != null) {
				try {
					DBUtils.disConnect(conn);
				} catch (Exception e1) {
					WMSLoggerFactory.getLogger(null).error(e1);
				}
			}
		}
	}

	public void onServerShutdownStart(IServer server) {
	}

	public void onServerShutdownComplete(IServer server) {
	}

}
