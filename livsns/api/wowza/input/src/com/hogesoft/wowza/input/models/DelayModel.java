package com.hogesoft.wowza.input.models;

import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import com.hogesoft.wowza.exceptions.OperateFaultException;
import com.hogesoft.wowza.input.utils.Settings;
import com.hogesoft.wowza.input.workers.DelayWorker;
import com.hogesoft.wowza.models.ModelBase;
import com.hogesoft.wowza.utils.DBUtils;

public final class DelayModel extends ModelBase {

	private static Map<Integer, DelayWorker> workers = new HashMap<Integer, DelayWorker>();

	private int _id;

	private int _inputId;

	private int _length;

	private boolean _enable;

	public int getId() {
		return _id;
	}

	public int getInputId() {
		return _inputId;
	}

	public int getLength() {
		return _length;
	}

	public boolean getEnable() {
		return _enable;
	}

	DelayModel(int id, int inputId, int length, boolean enable) {
		super();
		_id = id;
		_inputId = inputId;
		_length = length;
		_enable = enable;
	}

	public static DelayModel insert(Map<String, String> map) throws Exception {
		int length = Integer.parseInt(map.get("length"));
		if (length <= 0)
			throw new OperateFaultException(" insert length <= 0");
		InputModel input = InputModel.select(Integer.parseInt(map.get("inputId")));
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();
			StringBuilder sb = new StringBuilder("INSERT INTO delay VALUES (null, ");
			sb.append(input.getId()).append(", ").append(length).append(", 0);");
			stat.executeUpdate(sb.toString());
			ResultSet rs = stat.executeQuery("SELECT LAST_INSERT_ROWID() AS last_id;");
			if (!rs.next()) {
				DBUtils.disConnect(conn);
				throw new OperateFaultException("insert");
			}
			DelayModel model = new DelayModel(Integer.parseInt(rs.getString("last_id")), input.getId(), length, false);
			DBUtils.disConnect(conn);
			return model;
		} catch (SQLException e) {
			if (conn != null)
				DBUtils.disConnect(conn);
			throw e;
		}
	}

	public static void delete(int id) throws Exception {
		DelayModel model = select(id);
		model.stop();
		model.delete();
	}

	public static void update(int id, Map<String, String> map) throws Exception {
		DelayModel model = select(id);
		model.stop();
		int inputId = map.containsKey("inputId") ? Integer.parseInt(map.get("inputId")) : model.getInputId();
		int length = map.containsKey("length") ? Integer.parseInt(map.get("length")) : model.getLength();
		model.update(inputId, length, model.getEnable());
	}

	public static List<DelayModel> select() throws Exception {
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();
			ResultSet rs = stat.executeQuery("SELECT * FROM delay;");
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

	public static DelayModel select(int id) throws Exception {
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();
			StringBuilder sb = new StringBuilder().append("SELECT * FROM delay WHERE id = ").append(id).append(" LIMIT 1;");
			ResultSet rs = stat.executeQuery(sb.toString());
			if (!rs.next())
				throw new OperateFaultException("select: " + id);
			DelayModel model = new DelayModel(rs.getInt("id"), rs.getInt("input_id"), rs.getInt("length"), rs.getBoolean("enable"));
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

	private void delete() throws Exception {
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();
			StringBuilder sb = new StringBuilder().append("DELETE FROM delay WHERE id = ").append(_id).append(";");
			stat.executeUpdate(sb.toString());
			DBUtils.disConnect(conn);
		} catch (SQLException e) {
			if (conn != null)
				DBUtils.disConnect(conn);
			throw e;
		}
	}

	private void update(int inputId, int length, boolean enable) throws Exception {
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();
			StringBuilder sb = new StringBuilder().append("UPDATE delay SET input_id = ").append(inputId).append(", length = ").append(length).append(", enable = ").append(enable ? 1 : 0).append(" WHERE id = ").append(_id).append(";");
			stat.executeUpdate(sb.toString());
			DBUtils.disConnect(conn);
			_inputId = inputId;
			_length = length;
			_enable = enable;
		} catch (SQLException e) {
			if (conn != null)
				DBUtils.disConnect(conn);
			throw e;
		}
	}

	private void start() throws Exception {
		String srcStreamName = _inputId + ".stream";

		synchronized (workers) {
			if (!workers.containsKey(srcStreamName)) {
				String dstStreamName = _id + ".delay";
				DelayWorker worker = new DelayWorker(srcStreamName, dstStreamName, _length * 1000);
				workers.put(_id, worker);
				worker.start();
			}
		}

		try {
			update(_inputId, _length, true);
		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	private void stop() {
		DelayWorker worker = null;
		synchronized (workers) {
			worker = workers.remove(_id);
			if (worker != null) {
				worker.quit();
			}
		}

		try {
			update(_inputId, _length, false);
		} catch (Exception e) {
			e.printStackTrace();
		}
	}

}