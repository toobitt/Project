package com.hogesoft.wowza.input.models;

import java.io.File;
import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.Map;

import com.hogesoft.wowza.exceptions.OperateFaultException;
import com.hogesoft.wowza.input.utils.Settings;
import com.hogesoft.wowza.models.ModelBase;
import com.hogesoft.wowza.utils.DBUtils;
import com.hogesoft.wowza.utils.HttpUtils;

import com.wowza.wms.application.IApplication;
import com.wowza.wms.application.IApplicationInstance;
import com.wowza.wms.vhost.IVHost;
import com.wowza.wms.vhost.VHostSingleton;

public final class FileModel extends ModelBase {

	private int _id;
	
	public int getId() {
		return _id;
	}
	
	public String getName() {
		return "vod_" + _id + ".mp4";
	}

	public FileModel(int id) {
		super();
		_id = id;
	}

	public static FileModel insert(Map<String, String> map) throws Exception {
		IVHost vhost = VHostSingleton.getInstance(Settings.DEFAULT_VHOST);
		IApplication application = vhost.getApplication(Settings.APPLICATION);
		IApplicationInstance instance = application.getAppInstance(Settings.DEFAULT_INSTANCE);
		
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();
			StringBuilder sb = new StringBuilder().append("INSERT INTO file VALUES (null);");
			stat.executeUpdate(sb.toString());
			ResultSet rs = stat.executeQuery("SELECT LAST_INSERT_ROWID() AS last_id;");
			if (!rs.next()) {
				DBUtils.disConnect(conn);
				throw new OperateFaultException("insert");
			}
			FileModel model = new FileModel(rs.getInt("last_id"));
			DBUtils.disConnect(conn);
			
			final String url = map.get("url");
			final String callback = new StringBuilder(map.get("callback")).append("&result=").toString();
			final File file = new File(new StringBuilder(instance.getStreamStorageDir()).append(File.separator).append("vod_").append(model.getId()).append(".mp4").toString());
			
			new Thread() {
				@Override
				public void run() {
					HttpUtils http = new HttpUtils();
					http.setOnCompleteListener(new HttpUtils.OnCompleteListener() {
						@Override
						public void completeHandler(String response) {
							try {
								new HttpUtils().sendToURL(callback + 1);
							} catch (Exception e) {
								e.printStackTrace();
							}
						}
					});
					http.setOnErrorListener(new HttpUtils.OnErrorListener() {
						@Override
						public void errorHandler(int statusCode) {
							try {
								new HttpUtils().sendToURL(callback + 0);
							} catch (Exception e) {
								e.printStackTrace();
							}
						}
					});
					try {
						http.download(file, url);
					} catch (Exception e) {
						e.printStackTrace();
					}
				}
			}.start();
			return model;
		} catch (SQLException e) {
			if (conn != null)
				DBUtils.disConnect(conn);
			throw e;
		}
	}
	
	public static void delete(int id) throws Exception {
		select(id).delete();
	}
	
	public static FileModel select(int id) throws Exception {
		IVHost vhost = VHostSingleton.getInstance(Settings.DEFAULT_VHOST);
		IApplication application = vhost.getApplication(Settings.APPLICATION);
		IApplicationInstance instance = application.getAppInstance(Settings.DEFAULT_INSTANCE);
		
		File file = new File(new StringBuilder(instance.getStreamStorageDir()).append(File.separator).append("vod_").append(id).append(".mp4").toString());
		if(!file.exists())
			throw new OperateFaultException("select: " + id);
		FileModel model = new FileModel(id);
		return model;
	}
	
	public void delete() throws Exception {
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);;
			Statement stat = conn.createStatement();
			StringBuilder sb = new StringBuilder().append("DELETE FROM file WHERE id = ").append(_id).append(";");
			stat.executeUpdate(sb.toString());
			DBUtils.disConnect(conn);
		} catch (SQLException e) {
			if (conn != null)
				DBUtils.disConnect(conn);
			throw e;
		}
		
		IVHost vhost = VHostSingleton.getInstance(Settings.DEFAULT_VHOST);
		IApplication application = vhost.getApplication(Settings.APPLICATION);
		IApplicationInstance instance = application.getAppInstance(Settings.DEFAULT_INSTANCE);
		
		File file = new File(new StringBuilder(instance.getStreamStorageDir()).append(File.separator).append("vod_").append(_id).append(".mp4").toString());
		if(file.exists())
			file.delete();
	}

	@Override
	public String toString() {
		return "" + _id;
	}
}
