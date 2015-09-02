package com.hogesoft.wowza.input.models;

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
import com.hogesoft.wowza.input.utils.Settings;
import com.hogesoft.wowza.models.ModelBase;
import com.hogesoft.wowza.utils.DBUtils;
import com.wowza.wms.application.IApplication;
import com.wowza.wms.application.IApplicationInstance;
import com.wowza.wms.vhost.IVHost;
import com.wowza.wms.vhost.VHostSingleton;

public final class InputModel extends ModelBase {

	private int _id;

	private String _url;
	
	private boolean _enable;

	public int getId() {
		return _id;
	}

	public String getUrl() {
		return _url;
	}

	public boolean getEnable() {
		return _enable;
	}

	public boolean isVideoReady() {
		IVHost vhost = VHostSingleton.getInstance(Settings.DEFAULT_VHOST);
		IApplication application = vhost.getApplication(Settings.APPLICATION);
		IApplicationInstance instance = application.getAppInstance(Settings.DEFAULT_INSTANCE);
		String streamName = _id + ".stream";
		if(_enable && null != instance.getStreams().getStream(streamName))
		{
			return instance.getStreams().getStream(streamName).isPublishStreamReady(false, true);
		}
		return false;
	}

	public boolean isAudioReady() {
		IVHost vhost = VHostSingleton.getInstance(Settings.DEFAULT_VHOST);
		IApplication application = vhost.getApplication(Settings.APPLICATION);
		IApplicationInstance instance = application.getAppInstance(Settings.DEFAULT_INSTANCE);
		String streamName = _id + ".stream";
		if(_enable && null != instance.getStreams().getStream(streamName))
		{
			return instance.getStreams().getStream(streamName).isPublishStreamReady(true, false);
		}
		return false;
	}

	InputModel(int id, String url, boolean enable) {
		super();
		_id = id;
		_url = url;
		_enable = enable;
	}

	public static InputModel insert(Map<String, String> map) throws Exception {
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();
			String url = map.get("url");
			StringBuilder sb = new StringBuilder().append("INSERT INTO input VALUES (null, '").append(url).append("', 0);");
			stat.executeUpdate(sb.toString());
			ResultSet rs = stat.executeQuery("SELECT LAST_INSERT_ROWID() AS last_id;");
			if (!rs.next()) {
				DBUtils.disConnect(conn);
				throw new OperateFaultException("insert");
			}
			InputModel model = new InputModel(Integer.parseInt(rs.getString("last_id")), url, false);
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
		InputModel model = select(id);
		model.stop();
		model.delete();
	}

	public static void update(int id, Map<String, String> map) throws Exception {
		if (map.size() == 0)
			throw new OperateFaultException("update");
		InputModel model = select(id);
		model.stop();
		String url = map.containsKey("url") ? map.get("url") : model.getUrl();
		model.update(url, model.getEnable());
		model.save();
	}

	public static List<InputModel> select() throws Exception {
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);;
			Statement stat = conn.createStatement();
			ResultSet rs = stat.executeQuery("SELECT * FROM input;");
			List<InputModel> list = new ArrayList<InputModel>();
			while (rs.next()) {
				list.add(new InputModel(rs.getInt("id"), rs.getString("url"), rs.getBoolean("enable")));
			}
			DBUtils.disConnect(conn);
			return list;
		} catch (SQLException e) {
			if (conn != null)
				DBUtils.disConnect(conn);
			throw e;
		}
	}

	public static InputModel select(int id) throws Exception {
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);;
			Statement stat = conn.createStatement();
			StringBuilder sb = new StringBuilder().append("SELECT * FROM input WHERE id = ").append(id).append(" LIMIT 1;");
			ResultSet rs = stat.executeQuery(sb.toString());
			if (!rs.next())
				throw new OperateFaultException("select: " + id);
			InputModel model = new InputModel(rs.getInt("id"), rs.getString("url"), rs.getBoolean("enable"));
			DBUtils.disConnect(conn);
			return model;
		} catch (SQLException e) {
			if (conn != null)
				DBUtils.disConnect(conn);
			throw e;
		}
	}

	public static List<DelayModel> list(int id) throws Exception {
		return select(id).list();
	}

	public static void start(int id) throws Exception {
		InputModel model = select(id);
		model.start();
	}

	public static void stop(int id) throws Exception {
		InputModel model = select(id);
		model.stop();
	}

	private void delete() throws Exception {
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);;
			Statement stat = conn.createStatement();
			StringBuilder sb = new StringBuilder().append("DELETE FROM input WHERE id = ").append(_id).append(";");
			stat.executeUpdate(sb.toString());
			DBUtils.disConnect(conn);
			rm();
		} catch (SQLException e) {
			if (conn != null)
				DBUtils.disConnect(conn);
			throw e;
		}
	}

	private void update(String url, boolean enable) throws Exception {
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);;
			Statement stat = conn.createStatement();
			StringBuilder sb = new StringBuilder().append("UPDATE input SET url = '").append(url).append("', enable = ").append(enable ? 1 : 0).append(" WHERE id = ").append(_id).append(";");
			stat.executeUpdate(sb.toString());
			DBUtils.disConnect(conn);
			_url = url;
			_enable = enable;
		} catch (SQLException e) {
			if (conn != null)
				DBUtils.disConnect(conn);
			throw e;
		}
	}

	private List<DelayModel> list() throws Exception {
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);;
			Statement stat = conn.createStatement();
			StringBuilder sb = new StringBuilder().append("SELECT * FROM delay WHERE input_id = ").append(_id).append(";");
			ResultSet rs = stat.executeQuery(sb.toString());
			List<DelayModel> list = new ArrayList<DelayModel>();
			while (rs.next()) {
				list.add(new DelayModel(rs.getInt("id"), rs.getInt("input_id"), rs.getInt("length"), rs.getBoolean("enable")));
			}
			DBUtils.disConnect(conn);
			return list;
		} catch (SQLException e) {
			if (conn != null)
				DBUtils.disConnect(conn);
			throw e;
		}

	}

	private void start() throws Exception {
		IVHost vhost = VHostSingleton.getInstance(Settings.DEFAULT_VHOST);
		IApplication application = vhost.getApplication(Settings.APPLICATION);
		IApplicationInstance instance = application.getAppInstance(Settings.DEFAULT_INSTANCE);
		
		String streamName = _id + ".stream";
		File file = new File(instance.getStreamStorageDir() + File.separator + streamName);
		if (!file.exists()) {
			save();
		}
		instance.startMediaCasterStream(streamName, "liverepeater");
		
		update(_url, true);
	}

	private void stop() throws Exception {
		IVHost vhost = VHostSingleton.getInstance(Settings.DEFAULT_VHOST);
		IApplication app = vhost.getApplication(Settings.APPLICATION);
		IApplicationInstance instance = app.getAppInstance(Settings.DEFAULT_INSTANCE);
		instance.stopMediaCasterStream(_id + ".stream");

		update(_url, false);
	}

	private void save() throws Exception {
		IVHost vhost = VHostSingleton.getInstance(Settings.DEFAULT_VHOST);
		IApplication application = vhost.getApplication(Settings.APPLICATION);
		IApplicationInstance instance = application.getAppInstance(Settings.DEFAULT_INSTANCE);
		
		StringBuilder sb = new StringBuilder(instance.getStreamStorageDir()).append(File.separator).append(_id).append(".stream");
		File file = new File(sb.toString());
		FileWriter fw = new FileWriter(file);
		fw.write(_url);
		fw.close();
	}

	private void rm() throws Exception {
		IVHost vhost = VHostSingleton.getInstance(Settings.DEFAULT_VHOST);
		IApplication application = vhost.getApplication(Settings.APPLICATION);
		IApplicationInstance instance = application.getAppInstance(Settings.DEFAULT_INSTANCE);
		
		StringBuilder sb = new StringBuilder(instance.getStreamStorageDir()).append(File.separator).append(_id).append(".stream");
		File file = new File(sb.toString());
		if(file.exists())
			file.delete();
	}
}
