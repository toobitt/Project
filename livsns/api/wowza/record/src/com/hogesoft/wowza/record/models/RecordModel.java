package com.hogesoft.wowza.record.models;

import java.io.File;
import java.io.FileWriter;
import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import com.hogesoft.wowza.exceptions.OperateFaultException;
import com.hogesoft.wowza.models.ModelBase;
import com.hogesoft.wowza.record.utils.Settings;
import com.hogesoft.wowza.record.workers.RecordWorker;
import com.hogesoft.wowza.utils.DBUtils;
import com.hogesoft.wowza.utils.HttpUtils;
import com.wowza.wms.application.IApplication;
import com.wowza.wms.application.IApplicationInstance;
import com.wowza.wms.plugin.integration.liverecord.ILiveStreamRecord;
import com.wowza.wms.plugin.integration.liverecord.LiveStreamRecorderMP4;
import com.wowza.wms.stream.IMediaStream;
import com.wowza.wms.vhost.IVHost;
import com.wowza.wms.vhost.VHostSingleton;

public final class RecordModel extends ModelBase {

	private static Map<String, ILiveStreamRecord> recorders = new HashMap<String, ILiveStreamRecord>();

	private int _id;

	private String _url;

	private String _callback;

	private int _startTime;

	private int _duration;

	public int getId() {
		return _id;
	}

	public String getUrl() {
		return _url;
	}

	public String getCallback() {
		return _callback;
	}

	public int getStartTime() {
		return _startTime;
	}

	public int getDuration() {
		return _duration;
	}

	public RecordModel(int id, String url, String callback, int startTime, int duration) {
		super();
		_id = id;
		_url = url;
		_callback = callback;
		_startTime = startTime;
		_duration = duration;
	}

	public static RecordModel insert(Map<String, String> map) throws Exception {
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();
			int duration = Integer.parseInt(map.get("duration"));
			if (duration <= 0)
				throw new OperateFaultException("duration <= 0");
			int time = (int) (new Date().getTime() / 1000);
			int startTime = Integer.parseInt(map.get("startTime"));
			if (startTime < time)
				throw new OperateFaultException("startTime < now");

			StringBuilder sb = new StringBuilder().append("INSERT INTO record VALUES (null, '").append(map.get("url")).append("', ").append(startTime).append(", ").append(duration).append(", '").append(map.get("callback")).append("');");
			stat.executeUpdate(sb.toString());
			ResultSet rs = stat.executeQuery("SELECT LAST_INSERT_ROWID() AS last_id;");
			if (!rs.next()) {
				DBUtils.disConnect(conn);
				throw new OperateFaultException("insert");
			}
			RecordModel model = new RecordModel(Integer.parseInt(rs.getString("last_id")), map.get("url"), map.get("callback"), startTime, duration);
			DBUtils.disConnect(conn);
			model.save();
			RecordWorker.getInstance().add(model);
			return model;
		} catch (SQLException e) {
			if (conn != null)
				DBUtils.disConnect(conn);
			throw e;
		}
	}

	public static void delete(int id) throws Exception {
		RecordModel model = select(id);
		model.stop();
		model.delete();
		RecordWorker.getInstance().remove(model);
	}

	public static List<RecordModel> select() throws Exception {
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();
			ResultSet rs = stat.executeQuery("SELECT * FROM record;");
			List<RecordModel> list = new ArrayList<RecordModel>();
			while (rs.next()) {
				list.add(new RecordModel(rs.getInt("id"), rs.getString("url"), rs.getString("callback"), rs.getInt("start_time"), rs.getInt("duration")));
			}
			DBUtils.disConnect(conn);
			return list;
		} catch (SQLException e) {
			if (conn != null)
				DBUtils.disConnect(conn);
			throw e;
		}
	}

	public static RecordModel select(int id) throws Exception {
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();
			StringBuilder sb = new StringBuilder().append("SELECT * FROM record WHERE id = ").append(id).append(" LIMIT 1;");
			ResultSet rs = stat.executeQuery(sb.toString());
			if (!rs.next())
				throw new OperateFaultException("select: " + id);
			RecordModel model = new RecordModel(rs.getInt("id"), rs.getString("url"), rs.getString("callback"), rs.getInt("start_time"), rs.getInt("duration"));
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

	private static void startRecord(IMediaStream stream, String outputPath) {
		String streamName = stream.getName();
		ILiveStreamRecord recorder = new LiveStreamRecorderMP4();
		synchronized (recorders) {
			ILiveStreamRecord prevRecorder = recorders.get(streamName);
			if (prevRecorder != null)
				prevRecorder.stopRecording();
			recorders.put(streamName, recorder);
		}
		recorder.setRecordData(true);
		recorder.setStartOnKeyFrame(true);
		recorder.startRecording(stream, outputPath, true);
	}

	private static String stopRecording(String streamName) {
		ILiveStreamRecord recorder = null;
		synchronized (recorders) {
			recorder = recorders.remove(streamName);
		}
		String outputPath = null;
		if (recorder != null) {
			outputPath = recorder.getFilePath();
			recorder.stopRecording();
		}
		return outputPath;
	}

	private void delete() throws Exception {
		Connection conn = null;
		try {
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();
			StringBuilder sb = new StringBuilder().append("DELETE FROM record WHERE id = ").append(_id).append(";");
			stat.executeUpdate(sb.toString());
			DBUtils.disConnect(conn);
			rm();
		} catch (SQLException e) {
			if (conn != null)
				DBUtils.disConnect(conn);
			throw e;
		}
	}

	private void start() throws Exception {
		IVHost vhost = VHostSingleton.getInstance(Settings.DEFAULT_VHOST);
		IApplication app = vhost.getApplication(Settings.APPLICATION);
		IApplicationInstance instance = app.getAppInstance(Settings.DEFAULT_INSTANCE);

		String streamName = _id + ".stream";
		File file = new File(instance.getStreamStorageDir() + File.separator + streamName);
		if (!file.exists()) {
			save();
		}
		file = new File(Settings.STORAGE_DIR);
		if (!file.exists()) {
			file.mkdir();
		}
		instance.startMediaCasterStream(streamName, "liverepeater");
		IMediaStream stream = instance.getStreams().getStream(streamName);

		startRecord(stream, Settings.STORAGE_DIR + _id + ".mp4");
	}

	private void stop() throws Exception {
		IVHost vhost = VHostSingleton.getInstance(Settings.DEFAULT_VHOST);
		IApplication app = vhost.getApplication(Settings.APPLICATION);
		IApplicationInstance instance = app.getAppInstance(Settings.DEFAULT_INSTANCE);
		
		String streamName = _id + ".stream";
		stopRecording(streamName);
		if(instance.getPublishStreamNames().contains(streamName)) {
			instance.stopMediaCasterStream(streamName);

			File file = new File(Settings.STORAGE_DIR + _id + ".mp4");
			if (file.exists() && file.length() > 100) {
				try {
					new HttpUtils().upload(file, _callback);
				} catch (Exception e) {
					e.printStackTrace();
				}
			}
			
		}
	}

	private void save() throws Exception {
		IVHost vhost = VHostSingleton.getInstance(Settings.DEFAULT_VHOST);
		IApplication app = vhost.getApplication(Settings.APPLICATION);
		IApplicationInstance instance = app.getAppInstance(Settings.DEFAULT_INSTANCE);
		
		StringBuilder sb = new StringBuilder(instance.getStreamStorageDir()).append(File.separator).append(_id).append(".stream");
		File file = new File(sb.toString());
		FileWriter fw = new FileWriter(file);
		fw.write(_url);
		fw.close();
	}

	private void rm() throws Exception {
		IVHost vhost = VHostSingleton.getInstance(Settings.DEFAULT_VHOST);
		IApplication app = vhost.getApplication(Settings.APPLICATION);
		IApplicationInstance instance = app.getAppInstance(Settings.DEFAULT_INSTANCE);
		
		StringBuilder sb = new StringBuilder(instance.getStreamStorageDir()).append(File.separator).append(_id).append(".stream");
		File file = new File(sb.toString());
		if (file.exists())
			file.delete();
	}

	@Override
	public boolean equals(Object obj) {
		return obj instanceof RecordModel ? _id == ((RecordModel) obj).getId() : false;
	}
}
