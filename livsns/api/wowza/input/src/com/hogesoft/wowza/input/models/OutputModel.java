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
import com.hogesoft.wowza.input.workers.PublishWorker;
import com.hogesoft.wowza.input.workers.ScheduleWorker;
import com.hogesoft.wowza.models.ModelBase;
import com.hogesoft.wowza.utils.DBUtils;
import com.hogesoft.wowza.input.utils.SourceType;

public final class OutputModel extends ModelBase {

	private static Map<Integer, PublishWorker> workers = new HashMap<Integer, PublishWorker>();

	private int _id;

	private int _sourceId;

	private int _sourceType;

	private boolean _enable;

	public int getId() {
		return _id;
	}

	public int getSourceId() {
		return _sourceId;
	}

	public int getSourceType() {
		return _sourceType;
	}

	public boolean getEnable() {
		return _enable;
	}

	OutputModel(int id, int sourceId, int sourceType, boolean enable) {
		super();
		_id = id;
		_sourceId = sourceId;
		_sourceType = sourceType;
		_enable = enable;
	}

	public static OutputModel insert(Map<String, String> map) throws Exception {
		int sourceType = Integer.parseInt(map.get("sourceType"));
		int sourceId = 0;
		switch (SourceType.getSourceType(sourceType)) {
		case INPUT:
			sourceId = InputModel.select(Integer.parseInt(map.get("sourceId"))).getId();
			break;
		case DELAY:
			sourceId = DelayModel.select(Integer.parseInt(map.get("sourceId"))).getId();
			break;
		case LIST:
			sourceId = ListModel.select(Integer.parseInt(map.get("sourceId"))).getId();
			break;
		case FILE:
			sourceId = FileModel.select(Integer.parseInt(map.get("sourceId"))).getId();
			break;
		default:
			throw new IllegalArgumentException("error source type");
		}

		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();
			StringBuilder sb = new StringBuilder("INSERT INTO output VALUES (null, ");
			sb.append(sourceId).append(", ").append(sourceType).append(", 0);");
			stat.executeUpdate(sb.toString());
			ResultSet rs = stat.executeQuery("SELECT LAST_INSERT_ROWID() AS last_id;");
			if (!rs.next()) {
				DBUtils.disConnect(conn);
				throw new OperateFaultException("insert");
			}
			OutputModel model = new OutputModel(Integer.parseInt(rs.getString("last_id")), sourceId, sourceType, false);
			DBUtils.disConnect(conn);
			return model;
		} catch (SQLException e) {
			if (conn != null)
				DBUtils.disConnect(conn);
			throw e;
		}
	}

	public static void delete(int id) throws Exception {
		OutputModel model = select(id);
		model.stop();
		model.delete();
	}

	public static void update(int id, Map<String, String> map) throws Exception {
		OutputModel model = select(id);
		int sourceId = map.containsKey("sourceId") ? Integer.parseInt(map.get("sourceId")) : model.getSourceId();
		int sourceType = map.containsKey("sourceType") ? Integer.parseInt(map.get("sourceType")) : model.getSourceType();
		model.update(sourceId, sourceType, model.getEnable());
	}

	public static List<OutputModel> select() throws Exception {
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();
			ResultSet rs = stat.executeQuery("SELECT * FROM output;");
			List<OutputModel> list = new ArrayList<OutputModel>();
			while (rs.next()) {
				list.add(new OutputModel(rs.getInt("id"), rs.getInt("source_id"), rs.getInt("source_type"), rs.getBoolean("enable")));
			}
			DBUtils.disConnect(conn);
			return list;
		} catch (SQLException e) {
			if (conn != null)
				DBUtils.disConnect(conn);
			throw e;
		}
	}

	public static OutputModel select(int id) throws Exception {
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();
			StringBuilder sb = new StringBuilder().append("SELECT * FROM output WHERE id = ").append(id).append(" LIMIT 1;");
			ResultSet rs = stat.executeQuery(sb.toString());
			if (!rs.next())
				throw new OperateFaultException("select: " + id);
			OutputModel model = new OutputModel(rs.getInt("id"), rs.getInt("source_id"), rs.getInt("source_type"), rs.getBoolean("enable"));
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

	public static void change(int id, Map<String, String> map) throws Exception {
		int sourceId = Integer.parseInt(map.get("sourceId")), sourceType = Integer.parseInt(map.get("sourceType"));
		boolean notify = map.containsKey("notify") && map.get("notify").equals("1") ? true : false;
		select(id).change(sourceId, sourceType, notify);
	}

	public static void recover(int id) throws Exception {
		OutputModel model = select(id);
		model.change(model.getSourceId(), model.getSourceType(), false);
	}

	private void delete() throws Exception {
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();
			StringBuilder sb = new StringBuilder().append("DELETE FROM output WHERE id = ").append(_id).append(";");
			stat.executeUpdate(sb.toString());
			DBUtils.disConnect(conn);
		} catch (SQLException e) {
			if (conn != null)
				DBUtils.disConnect(conn);
			throw e;
		}
	}

	private void update(int sourceId, int sourceType, boolean enable) throws Exception {
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();
			StringBuilder sb = new StringBuilder().append("UPDATE output SET source_id = ").append(sourceId).append(", source_type = ").append(sourceType).append(", enable = ").append(enable ? 1 : 0).append(" WHERE id = ").append(_id).append(";");
			stat.executeUpdate(sb.toString());
			DBUtils.disConnect(conn);
			change(sourceId, sourceType, false);
			_enable = enable;
		} catch (SQLException e) {
			if (conn != null)
				DBUtils.disConnect(conn);
			throw e;
		}
	}

	private void start() throws Exception {
		String publishName = _id + ".output";

		synchronized (workers) {
			if (!workers.containsKey(_id)) {
				PublishWorker worker = new PublishWorker(publishName, _sourceId, _sourceType);
				workers.put(_id, worker);
				worker.start();
			}
		}

		try {
			update(_sourceId, _sourceType, true);
		} catch (Exception e) {
			e.printStackTrace();
		}

		new Thread() {

			@Override
			public void run() {
				try {
					sleep(5000);
				} catch (InterruptedException e) {
					e.printStackTrace();
				}
				ScheduleWorker.getInstance().outputNotify(_id);
				super.run();
			}

		}.start();
	}

	private void stop() {
		PublishWorker worker = null;
		synchronized (workers) {
			worker = workers.remove(_id);
			if (worker != null) {
				worker.quit();
			}
		}

		try {
			update(_sourceId, _sourceType, false);
		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	private void change(int sourceId, int sourceType, boolean notify) throws Exception {
		PublishWorker worker = null;
		_sourceId = sourceId;
		_sourceType = sourceType;
		synchronized (workers) {
			worker = workers.get(_id);
		}
		if (worker != null) {
			worker.change(sourceId, sourceType);
		}
		if (notify) {
			ScheduleWorker.getInstance().outputNotify(_id);
		}
	}
}