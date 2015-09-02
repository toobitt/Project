package com.hogesoft.wowza.output.models;

import java.io.File;
import java.io.FileWriter;
import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.List;
import java.util.Map;

import com.hogesoft.wowza.exceptions.OperateFaultException;
import com.hogesoft.wowza.models.ModelBase;
import com.hogesoft.wowza.output.utils.Settings;
import com.hogesoft.wowza.utils.DBUtils;
import com.wowza.wms.application.IApplication;
import com.wowza.wms.application.IApplicationInstance;
import com.wowza.wms.vhost.IVHost;
import com.wowza.wms.vhost.VHostSingleton;

public final class StreamModel extends ModelBase {

	private int _id;

	private int _applicationId;

	private String _name;

	private String _url;

	private boolean _enable;

	public int getId() {
		return _id;
	}

	public int getApplicationId() {
		return _applicationId;
	}

	public String getName() {
		return _name;
	}

	public String getUrl() {
		return _url;
	}

	public boolean getEnable() {
		return _enable;
	}

	public StreamModel(int id, int applicationId, String name, String url, boolean enable) {
		super();
		_id = id;
		_applicationId = applicationId;
		_name = name;
		_url = url;
		_enable = enable;
	}

	public static StreamModel insert(Map<String, String> map) throws Exception {
		ApplicationModel application = ApplicationModel.select(Integer.parseInt(map.get("applicationId")));
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();

			StringBuilder sb = new StringBuilder().append("SELECT * FROM stream WHERE application_id = ").append(application.getId()).append(" AND name = '").append(map.get("name")).append("' LIMIT 1;");
			ResultSet rs = stat.executeQuery(sb.toString());
			if (rs.next()) {
				DBUtils.disConnect(conn);
				throw new OperateFaultException("stream exist");
			}
			int id = Integer.parseInt(map.get("id"));
			if (id > 0) {
				sb = new StringBuilder().append("INSERT INTO stream VALUES (").append(id).append(", ").append(application.getId()).append(", '").append(map.get("name")).append("', '").append(map.get("url")).append("', 0);");
				stat.executeUpdate(sb.toString());
			} else {
				sb = new StringBuilder().append("INSERT INTO stream VALUES (null, ").append(application.getId()).append(", '").append(map.get("name")).append("', '").append(map.get("url")).append("', 0);");
				stat.executeUpdate(sb.toString());
				rs = stat.executeQuery("SELECT LAST_INSERT_ROWID() AS last_id;");
				if (!rs.next()) {
					DBUtils.disConnect(conn);
					throw new OperateFaultException("insert");
				}
				id = rs.getInt("last_id");
			}
			StreamModel model = new StreamModel(id, application.getId(), map.get("name"), map.get("url"), false);
			DBUtils.disConnect(conn);
			model.save();
			return model;
		} catch (SQLException e) {
			if (conn != null)
				DBUtils.disConnect(conn);
			throw e;
		}
	}

	public static void delete(int id) throws Exception {
		StreamModel model = select(id);
		model.stop();
		model.delete();
	}

	public static void update(int id, Map<String, String> map) throws Exception {
		StreamModel model = select(id);
		if (map.containsKey("name"))
			model._name = map.get("name");
		if (map.containsKey("url"))
			model._url = map.get("url");
		model.stop();
	}

	public static List<StreamModel> select() throws Exception {
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();
			ResultSet rs = stat.executeQuery("SELECT * FROM stream");
			List<StreamModel> list = new ArrayList<StreamModel>();
			while (rs.next()) {
				list.add(new StreamModel(rs.getInt("id"), rs.getInt("application_id"), rs.getString("name"), rs.getString("url"), rs.getBoolean("enable")));
			}
			DBUtils.disConnect(conn);
			return list;
		} catch (SQLException e) {
			if (conn != null)
				DBUtils.disConnect(conn);
			throw e;
		}
	}

	public static StreamModel select(int id) throws Exception {
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();
			StringBuilder sb = new StringBuilder().append("SELECT * FROM stream WHERE id = ").append(id).append(" LIMIT 1;");
			ResultSet rs = stat.executeQuery(sb.toString());
			if (!rs.next()) {
				DBUtils.disConnect(conn);
				throw new OperateFaultException("select: " + id);
			}
			StreamModel model = new StreamModel(rs.getInt("id"), rs.getInt("application_id"), rs.getString("name"), rs.getString("url"), rs.getBoolean("enable"));
			DBUtils.disConnect(conn);
			return model;
		} catch (SQLException e) {
			if (conn != null)
				DBUtils.disConnect(conn);
			throw e;
		}
	}

	public static void start(int id) throws Exception {
		select(id).start();
	}

	public static void stop(int id) throws Exception {
		select(id).stop();
	}

	public static void change(int id, String url) throws Exception {
		StreamModel model = select(id);
		model.change(url);
	}

	private void delete() throws Exception {
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();
			stat.executeUpdate("DELETE FROM stream WHERE id = " + _id + ";");
			DBUtils.disConnect(conn);
			rm();
		} catch (SQLException e) {
			if (conn != null)
				DBUtils.disConnect(conn);
			throw e;
		}
	}

	private void update(int id, String name, String url, boolean enable) throws Exception {
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();

			StringBuilder sb;
			if (!name.equalsIgnoreCase(_name)) {
				sb = new StringBuilder().append("SELECT * FROM stream WHERE application_id = ").append(_applicationId).append(" AND name = '").append(name).append("' LIMIT 1;");
				ResultSet rs = stat.executeQuery(sb.toString());
				if (rs.next()) {
					DBUtils.disConnect(conn);
					throw new OperateFaultException(sb.toString());
				}
			}

			sb = new StringBuilder().append("UPDATE stream SET name = '").append(name).append("', url = '").append(url).append("', enable = ").append(enable ? 1 : 0).append(" WHERE id = ").append(_id).append(";");
			stat.executeUpdate(sb.toString());
			DBUtils.disConnect(conn);
			rm();
			_name = name;
			_url = url;
			_enable = enable;
			save();
		} catch (SQLException e) {
			if (conn != null)
				DBUtils.disConnect(conn);
			throw e;
		}
	}

	private void start() throws Exception {
		IVHost vhost = VHostSingleton.getInstance(Settings.DEFAULT_VHOST);
		IApplication application = vhost.getApplication(ApplicationModel.select(_applicationId).getName());
		IApplicationInstance instance = application.getAppInstance(Settings.DEFAULT_INSTANCE);

		instance.startMediaCasterStream(_name + ".stream", "liverepeater");
		update(_id, _name, _url, true);
	}

	private void stop() throws Exception {
		IVHost vhost = VHostSingleton.getInstance(Settings.DEFAULT_VHOST);
		IApplication application = vhost.getApplication(ApplicationModel.select(_applicationId).getName());
		IApplicationInstance instance = application.getAppInstance(Settings.DEFAULT_INSTANCE);

		instance.stopMediaCasterStream(_name + ".stream");
		update(_id, _name, _url, false);
	}

	private void change(String url) throws Exception {
		IVHost vhost = VHostSingleton.getInstance(Settings.DEFAULT_VHOST);
		IApplication application = vhost.getApplication(ApplicationModel.select(_applicationId).getName());
		IApplicationInstance instance = application.getAppInstance(Settings.DEFAULT_INSTANCE);

		String tmp = _url;
		_url = url;
		save();
		_url = tmp;

		instance.stopMediaCasterStream(_name + ".stream");
		instance.startMediaCasterStream(_name + ".stream", "liverepeater");
	}

	private void save() throws Exception {
		IVHost vhost = VHostSingleton.getInstance(Settings.DEFAULT_VHOST);
		IApplication application = vhost.getApplication(ApplicationModel.select(_applicationId).getName());
		IApplicationInstance instance = application.getAppInstance(Settings.DEFAULT_INSTANCE);

		StringBuilder sb = new StringBuilder(instance.getStreamStorageDir()).append(File.separator).append(_name).append(".stream");
		File file = new File(sb.toString());
		FileWriter fw = new FileWriter(file);
		fw.write(_url);
		fw.close();
	}

	private void rm() throws Exception {
		IVHost vhost = VHostSingleton.getInstance(Settings.DEFAULT_VHOST);
		IApplication application = vhost.getApplication(ApplicationModel.select(_applicationId).getName());
		IApplicationInstance instance = application.getAppInstance(Settings.DEFAULT_INSTANCE);

		StringBuilder sb = new StringBuilder(instance.getStreamStorageDir()).append(File.separator).append(_name).append(".stream");
		File file = new File(sb.toString());
		if (file.exists())
			file.delete();
	}
}
