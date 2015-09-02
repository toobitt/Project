package com.hogesoft.wowza.record;

import java.sql.Connection;
import java.sql.Statement;

import com.hogesoft.wowza.record.utils.Settings;
import com.hogesoft.wowza.record.workers.RecordWorker;
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
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();
			stat.executeUpdate("CREATE TABLE IF NOT EXISTS record (id INTEGER PRIMARY KEY AUTOINCREMENT, url VARCHAR(512) NOT NULL, start_time INTEGER NOT NULL, duration INTEGER NOT NULL, callback VARCHAR(512) NOT NULL);");
			DBUtils.disConnect(conn);
			
			new RecordWorker().start();
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
