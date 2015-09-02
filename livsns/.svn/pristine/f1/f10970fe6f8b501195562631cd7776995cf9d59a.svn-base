package com.hogesoft.wowza.output.models;

import java.io.File;
import java.io.FileWriter;
import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.Iterator;
import java.util.List;
import java.util.Map;

import org.dom4j.Document;
import org.dom4j.io.SAXReader;
import org.dom4j.io.XMLWriter;

import com.hogesoft.wowza.exceptions.OperateFaultException;
import com.hogesoft.wowza.models.ModelBase;
import com.hogesoft.wowza.output.utils.Settings;
import com.hogesoft.wowza.utils.DBUtils;
import com.wowza.wms.vhost.IVHost;
import com.wowza.wms.vhost.VHostSingleton;

public final class ApplicationModel extends ModelBase {

	private int _id;

	private String _name;

	private int _length;

	private int _type;

	private int _drm;

	public int getId() {
		return _id;
	}

	public String getName() {
		return _name;
	}

	public int getLength() {
		return _length;
	}

	public int getType() {
		return _type;
	}

	public int getDrm() {
		return _drm;
	}

	ApplicationModel(int id, String name, int length, int type, int drm) {
		super();
		_id = id;
		_name = name;
		_length = length;
		_type = type;
		_drm = drm;
	}

	public static ApplicationModel insert(Map<String, String> map) throws Exception {
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();
			int id = Integer.parseInt(map.get("id"));
			int length = Integer.parseInt(map.get("length"));
			int drm = Integer.parseInt(map.get("drm"));
			int type = Integer.parseInt(map.get("type"));
			if (id > 0) {
				StringBuilder sb = new StringBuilder().append("INSERT INTO application VALUES (").append(id).append(", '").append(map.get("name")).append("', ").append(length).append(", ").append(type).append(", ").append(drm).append(");");
				stat.executeUpdate(sb.toString());
			} else {
				StringBuilder sb = new StringBuilder().append("INSERT INTO application VALUES (null, '").append(map.get("name")).append("', ").append(length).append(", ").append(type).append(", ").append(drm).append(");");
				stat.executeUpdate(sb.toString());
				ResultSet rs = stat.executeQuery("SELECT LAST_INSERT_ROWID() AS last_id;");
				if (!rs.next()) {
					DBUtils.disConnect(conn);
					throw new OperateFaultException("insert");
				}
				id = rs.getInt("last_id");
			}
			ApplicationModel model = new ApplicationModel(id, map.get("name"), length, type, drm);
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
		ApplicationModel model = select(id);
		model.stop();
		for (Iterator<StreamModel> iterator = model.list().iterator(); iterator.hasNext();) {
			StreamModel.delete(iterator.next().getId());
		}
		model.delete();
	}

	public static void update(int id, Map<String, String> map) throws Exception {
		ApplicationModel model = select(id);
		model.stop();
		String name = map.containsKey("name") ? map.get("name") : model.getName();
		int type = map.containsKey("type") ? Integer.parseInt(map.get("type")) : model.getType();
		int length = map.containsKey("length") ? Integer.parseInt(map.get("length")) : model.getLength();
		int drm = map.containsKey("drm") ? Integer.parseInt(map.get("drm")) : model.getDrm();
		model.update(name, type, length, drm);
	}

	public static List<ApplicationModel> select() throws Exception {
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();
			ResultSet rs = stat.executeQuery("SELECT * FROM application;");
			List<ApplicationModel> list = new ArrayList<ApplicationModel>();
			while (rs.next()) {
				list.add(new ApplicationModel(rs.getInt("id"), rs.getString("name"), rs.getInt("length"), rs.getInt("type"), rs.getInt("drm")));
			}
			DBUtils.disConnect(conn);
			return list;
		} catch (SQLException e) {
			if (conn != null)
				DBUtils.disConnect(conn);
			throw e;
		}
	}

	public static ApplicationModel select(int id) throws Exception {
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();
			StringBuilder sb = new StringBuilder().append("SELECT * FROM application WHERE id = ").append(id).append(" LIMIT 1;");
			ResultSet rs = stat.executeQuery(sb.toString());
			if (!rs.next())
				throw new OperateFaultException("select: " + id);
			ApplicationModel model = new ApplicationModel(rs.getInt("id"), rs.getString("name"), rs.getInt("length"), rs.getInt("type"), rs.getInt("drm"));
			DBUtils.disConnect(conn);
			return model;
		} catch (SQLException e) {
			if (conn != null)
				DBUtils.disConnect(conn);
			throw e;
		}
	}

	public static List<StreamModel> list(int id) throws Exception {
		return select(id).list();
	}

	public static void start(int id) throws Exception {
		ApplicationModel model = select(id);
		model.start();
		for (Iterator<StreamModel> iterator = list(model.getId()).iterator(); iterator.hasNext();) {
			StreamModel.start(iterator.next().getId());
		}
	}

	public static void stop(int id) throws Exception {
		ApplicationModel model = select(id);
		for (Iterator<StreamModel> iterator = list(model.getId()).iterator(); iterator.hasNext();) {
			StreamModel.stop(iterator.next().getId());
		}
		model.stop();
	}

	private void delete() throws Exception {
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();
			StringBuilder sb = new StringBuilder().append("DELETE FROM application WHERE id = ").append(_id).append(";");
			stat.executeUpdate(sb.toString());
			DBUtils.disConnect(conn);
			rm();
		} catch (SQLException e) {
			if (conn != null)
				DBUtils.disConnect(conn);
			throw e;
		}
	}

	private void update(String name, int type, int length, int drm) throws Exception {
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();
			StringBuilder sb = new StringBuilder().append("UPDATE application SET name = '").append(name).append("', type = ").append(type).append(", length = ").append(length).append(", drm = ").append(drm).append(" WHERE id = ").append(_id).append(";");
			stat.executeUpdate(sb.toString());
			DBUtils.disConnect(conn);
			rm();
			_name = name;
			_type = type;
			_length = length;
			_drm = drm;
			save();
		} catch (SQLException e) {
			if (conn != null)
				DBUtils.disConnect(conn);
			throw e;
		}
	}

	private List<StreamModel> list() throws Exception {
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();
			StringBuilder sb = new StringBuilder().append("SELECT * FROM stream WHERE application_id = ").append(_id).append(";");
			ResultSet rs = stat.executeQuery(sb.toString());
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

	private void start() {
		IVHost vhost = VHostSingleton.getInstance(Settings.DEFAULT_VHOST);
		vhost.startApplicationInstance(_name);
	}

	private void stop() {
		IVHost vhost = VHostSingleton.getInstance(Settings.DEFAULT_VHOST);
		vhost.shutdownApplication(_name);
	}

	private void save() throws Exception {
		File file = new File(Settings.WMS_PATH + "applications" + File.separator + _name);
		if (!file.exists())
			file.mkdir();
		file = new File(Settings.WMS_PATH + "conf" + File.separator + _name);
		if (!file.exists())
			file.mkdir();
		SAXReader reader = new SAXReader();
		file = new File(Settings.CONF_FILE);
		if (!file.exists())
			throw new OperateFaultException("save src");
		Document document = reader.read(file);
		document.getRootElement().element("Application").element("Streams").element("StorageDir").setText("${com.wowza.wms.context.VHostConfigHome}/content/" + _id);
		String type;
		switch (_type) {
		case 1:
			type = "sanjosestreaming,dvrchunkstreaming";
			break;
		case 2:
			type = "cupertinostreaming,dvrchunkstreaming";
			break;
		case 3:
			type = "sanjosestreaming,cupertinostreaming,dvrchunkstreaming";
			break;
		default:
			type = "dvrchunkstreaming";
		}
		document.getRootElement().element("Application").element("HTTPStreamers").setText(type);
		document.getRootElement().element("Application").element("DVR").element("WindowDuration").setText("" + _length);
		XMLWriter writer = new XMLWriter(new FileWriter(Settings.WMS_PATH + "conf" + File.separator + _name + File.separator + "Application.xml"));
		writer.write(document);
		writer.close();
	}

	private void rm() {
		File file = new File(Settings.WMS_PATH + "applications" + File.separator + _name);
		if (file.exists())
			delTree(file);
		file = new File(Settings.WMS_PATH + "conf" + File.separator + _name);
		if (file.exists())
			delTree(file);
	}
}
