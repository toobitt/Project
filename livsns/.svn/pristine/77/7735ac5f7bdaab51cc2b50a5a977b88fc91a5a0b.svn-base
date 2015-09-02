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
import com.hogesoft.wowza.models.ModelBase;
import com.hogesoft.wowza.input.utils.Settings;
import com.hogesoft.wowza.input.workers.ScheduleWorker;
import com.hogesoft.wowza.utils.DBUtils;
import com.hogesoft.wowza.input.utils.SourceType;

public final class ScheduleModel extends ModelBase {

	private int _id;

	private int _outputId;
	
	private int _sourceId;
	
	private int _sourceType;

	private int _startTime;

	private int _duration;

	public int getId() {
		return _id;
	}

	public int getOutputId() {
		return _outputId;
	}

	public int getSourceId() {
		return _sourceId;
	}

	public int getSourceType() {
		return _sourceType;
	}

	public int getStartTime() {
		return _startTime;
	}

	public int getDuration() {
		return _duration;
	}

	public ScheduleModel(int id, int outputId, int sourceId, int sourceType, int startTime, int duration) {
		super();
		_id = id;
		_outputId = outputId;
		_sourceId = sourceId;
		_sourceType = sourceType;
		_startTime = startTime;
		_duration = duration;
	}

	public static ScheduleModel insert(Map<String, String> map) throws Exception {
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();
			int duration = Integer.parseInt(map.get("duration"));
			if (duration <= 0)
				throw new OperateFaultException("duration <= 0");
			int time = (int) (System.currentTimeMillis() / 1000);
			int startTime = Integer.parseInt(map.get("startTime"));
			if (startTime < time)
				throw new OperateFaultException("startTime < now");

			int outputId = OutputModel.select(Integer.parseInt(map.get("outputId"))).getId();

			int sourceId = 0, sourceType = Integer.parseInt(map.get("sourceType"));
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

			StringBuilder sb = new StringBuilder().append("INSERT INTO schedule VALUES (null, ").append(outputId).append(", ").append(sourceId).append(", ").append(sourceType).append(", ").append(startTime).append(", ").append(duration).append(");");
			stat.executeUpdate(sb.toString());
			ResultSet rs = stat.executeQuery("SELECT LAST_INSERT_ROWID() AS last_id;");
			if (!rs.next()) {
				DBUtils.disConnect(conn);
				throw new OperateFaultException("insert");
			}
			ScheduleModel model = new ScheduleModel(Integer.parseInt(rs.getString("last_id")), outputId, sourceId, sourceType, startTime, duration);
			DBUtils.disConnect(conn);
			ScheduleWorker.getInstance().add(model);
			return model;
		} catch (SQLException e) {
			if (conn != null)
				DBUtils.disConnect(conn);
			throw e;
		}
	}

	public static void delete(int id) throws Exception {
		ScheduleModel model = select(id);
		ScheduleWorker.getInstance().remove(model);
		model.delete();
	}

	public static List<ScheduleModel> select() throws Exception {
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();
			ResultSet rs = stat.executeQuery("SELECT * FROM schedule;");
			List<ScheduleModel> list = new ArrayList<ScheduleModel>();
			while (rs.next()) {
				list.add(new ScheduleModel(rs.getInt("id"), rs.getInt("output_id"), rs.getInt("source_id"), rs.getInt("source_type"), rs.getInt("start_time"), rs.getInt("duration")));
			}
			DBUtils.disConnect(conn);
			return list;
		} catch (SQLException e) {
			if (conn != null)
				DBUtils.disConnect(conn);
			throw e;
		}
	}

	public static ScheduleModel select(int id) throws Exception {
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();
			StringBuilder sb = new StringBuilder().append("SELECT * FROM schedule WHERE id = ").append(id).append(" LIMIT 1;");
			ResultSet rs = stat.executeQuery(sb.toString());
			if (!rs.next())
				throw new OperateFaultException("select: " + id);
			ScheduleModel model = new ScheduleModel(rs.getInt("id"), rs.getInt("output_id"), rs.getInt("source_id"), rs.getInt("source_type"), rs.getInt("start_time"), rs.getInt("duration"));
			DBUtils.disConnect(conn);
			return model;
		} catch (SQLException e) {
			if (conn != null)
				DBUtils.disConnect(conn);
			throw e;
		}
	}

	public static Map<String, List<ScheduleModel>> list() throws Exception {
		Map<String, List<ScheduleModel>> map = new HashMap<String, List<ScheduleModel>>();
		map.put("start", ScheduleWorker.getInstance().selectStartSchedule());
		map.put("end", ScheduleWorker.getInstance().selectEndSchedule());
		return map;
	}

	public static void start(int id) throws Exception {
		ScheduleModel model = select(id);
		model.start();
	}

	public static void stop(int id, boolean back) throws Exception {
		ScheduleModel model = select(id);
		model.stop(back);
	}

	@Override
	public boolean equals(Object obj) {
		return obj instanceof ScheduleModel ? _id == ((ScheduleModel) obj).getId() : false;
	}

	private void delete() throws Exception {
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();
			StringBuilder sb = new StringBuilder().append("DELETE FROM schedule WHERE id = ").append(_id).append(";");
			stat.executeUpdate(sb.toString());
			DBUtils.disConnect(conn);
		} catch (SQLException e) {
			if (conn != null)
				DBUtils.disConnect(conn);
			throw e;
		}
	}

	private void start() throws Exception {
		if (SourceType.LIST == SourceType.getSourceType(_sourceType)) {
			ListModel.start(_sourceId);
		}
		final Map<String, String> map = new HashMap<String, String>();
		map.put("sourceId", "" + _sourceId);
		map.put("sourceType", "" + _sourceType);
		new Thread() {
			@Override
			public void run() {
				try {
					sleep(1000);
					OutputModel.change(_outputId, map);
				} catch (Exception e) {
					e.printStackTrace();
				}
				super.run();
			}

		}.start();
	}

	private void stop(boolean back) throws Exception {
		if (SourceType.LIST == SourceType.getSourceType(_sourceType)) {
			ListModel.stop(_sourceId);
		}
		if (back)
			OutputModel.recover(_outputId);
	}
}
