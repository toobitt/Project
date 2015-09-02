package com.hogesoft.wowza.input.models;

import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.Iterator;
import java.util.List;
import java.util.Map;
import java.util.StringTokenizer;

import com.hogesoft.wowza.exceptions.OperateFaultException;
import com.hogesoft.wowza.input.utils.Settings;
import com.hogesoft.wowza.models.ModelBase;
import com.hogesoft.wowza.utils.DBUtils;
import com.wowza.wms.application.IApplication;
import com.wowza.wms.application.IApplicationInstance;
import com.wowza.wms.stream.publish.Stream;
import com.wowza.wms.vhost.IVHost;
import com.wowza.wms.vhost.VHostSingleton;

public final class ListModel extends ModelBase {

	private int _id;

	private String _files;

	private boolean _enable;

	public int getId() {
		return _id;
	}

	public boolean getEnable() {
		return _enable;
	}

	public ListModel(int id, String files, boolean enable) {
		super();
		_id = id;
		_files = files;
		_enable = enable;
	}

	public List<FileModel> getFiles() {
		List<FileModel> list = new ArrayList<FileModel>();
		for (Iterator<String> fileIdIterator = parseFiles(_files).iterator(); fileIdIterator.hasNext();) {
			try {
				list.add(FileModel.select(Integer.parseInt(fileIdIterator.next())));
			} catch (Exception e) {
				e.printStackTrace();
			}
		}
		return list;
	}

	public static ListModel insert(Map<String, String> map) throws Exception {
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();
			StringBuilder sb = new StringBuilder().append("INSERT INTO list VALUES (null, '").append(map.get("files")).append(("', 0);"));
			stat.executeUpdate(sb.toString());
			ResultSet rs = stat.executeQuery("SELECT LAST_INSERT_ROWID() AS last_id;");
			if (!rs.next()) {
				DBUtils.disConnect(conn);
				throw new OperateFaultException("insert");
			}
			ListModel model = new ListModel(rs.getInt("last_id"), map.get("files"), false);
			DBUtils.disConnect(conn);
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

	public static void update(int id, Map<String, String> map) throws Exception {
		if (map.size() == 0)
			throw new OperateFaultException("update");
		ListModel model = select(id);
		model.stop();
		String files = map.containsKey("files") ? map.get("files") : model._files;
		model.update(files, model.getEnable());
	}

	public static List<ListModel> select() throws Exception {
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();
			ResultSet rs = stat.executeQuery("SELECT * FROM list;");
			List<ListModel> list = new ArrayList<ListModel>();
			while (rs.next()) {
				list.add(new ListModel(rs.getInt("id"), rs.getString("files"), rs.getBoolean("enable")));
			}
			DBUtils.disConnect(conn);
			return list;
		} catch (SQLException e) {
			if (conn != null)
				DBUtils.disConnect(conn);
			throw e;
		}
	}

	public static ListModel select(int id) throws Exception {
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();
			StringBuilder sb = new StringBuilder().append("SELECT * FROM list WHERE id = ").append(id).append(" LIMIT 1;");
			ResultSet rs = stat.executeQuery(sb.toString());
			if (!rs.next())
				throw new OperateFaultException("select: " + id);
			ListModel model = new ListModel(rs.getInt("id"), rs.getString("files"), rs.getBoolean("enable"));
			DBUtils.disConnect(conn);
			return model;
		} catch (SQLException e) {
			if (conn != null)
				DBUtils.disConnect(conn);
			throw e;
		}
	}

	public static void start(int id) throws Exception {
		ListModel.select(id).start();
	}

	public static void stop(int id) throws Exception {
		ListModel.select(id).stop();
	}

	private static List<String> parseFiles(String str) {
		ArrayList<String> list = new ArrayList<String>();
		StringTokenizer st = new StringTokenizer(str, ",");
		while (st.hasMoreTokens()) {
			list.add((String) st.nextElement());
		}
		return list;
	}
	
	public void delete() throws Exception {
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();
			StringBuilder sb = new StringBuilder().append("DELETE FROM list WHERE id = ").append(_id).append(";");
			stat.executeUpdate(sb.toString());
			DBUtils.disConnect(conn);
		} catch (SQLException e) {
			if (conn != null)
				DBUtils.disConnect(conn);
			throw e;
		}
	}

	private void update(String files, boolean enable) throws Exception {
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();
			StringBuilder sb = new StringBuilder().append("UPDATE list SET files = '").append(files).append("', enable = ").append(enable ? 1 : 0).append(" WHERE id = ").append(_id).append(";");
			stat.executeUpdate(sb.toString());
			DBUtils.disConnect(conn);
			_files = files;
			_enable = enable;
		} catch (SQLException e) {
			if (conn != null)
				DBUtils.disConnect(conn);
			throw e;
		}
	}
	
	public void start() throws Exception {
		IVHost vhost = VHostSingleton.getInstance(Settings.DEFAULT_VHOST);
		IApplication application = vhost.getApplication(Settings.APPLICATION);
		IApplicationInstance instance = application.getAppInstance(Settings.DEFAULT_INSTANCE);
		
		String streamName = _id + ".list";
		if (instance.getPublishStreamNames().contains(streamName)) 
			return;
		
		Stream stream = Stream.createInstance(vhost, Settings.APPLICATION, streamName);

		for (Iterator<FileModel> fileIterator = getFiles().iterator(); fileIterator.hasNext();) {
			stream.play("mp4:vod_" + fileIterator.next().getId() + ".mp4", 0, -1, false);
		}
		update(_files, true);
	}
	
	public void stop() throws Exception {
		IVHost vhost = VHostSingleton.getInstance(Settings.DEFAULT_VHOST);
		IApplication application = vhost.getApplication(Settings.APPLICATION);
		IApplicationInstance instance = application.getAppInstance(Settings.DEFAULT_INSTANCE);
		
		String streamName = _id + ".list";
		if (!instance.getPublishStreamNames().contains(streamName)) 
			return;
		
		instance.getStreams().getStream(streamName).shutdown();
		update(_files, false);
	}
}
