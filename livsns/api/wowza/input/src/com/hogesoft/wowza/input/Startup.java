package com.hogesoft.wowza.input;

import java.sql.Connection;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.Iterator;
import java.util.List;
import java.util.StringTokenizer;

import com.hogesoft.wowza.input.models.DelayModel;
import com.hogesoft.wowza.input.models.InputModel;
import com.hogesoft.wowza.input.models.ListModel;
import com.hogesoft.wowza.input.models.OutputModel;
import com.hogesoft.wowza.input.utils.Settings;
import com.hogesoft.wowza.input.utils.SourceType;
import com.hogesoft.wowza.input.workers.ScheduleWorker;
import com.hogesoft.wowza.utils.DBUtils;
import com.wowza.wms.application.IApplication;
import com.wowza.wms.application.IApplicationInstance;
import com.wowza.wms.logging.WMSLoggerFactory;
import com.wowza.wms.mediacaster.IMediaCaster;
import com.wowza.wms.mediacaster.IMediaCasterNotify2;
import com.wowza.wms.server.*;
import com.wowza.wms.stream.IMediaStream;
import com.wowza.wms.stream.IMediaStreamPlay;
import com.wowza.wms.vhost.IVHost;
import com.wowza.wms.vhost.VHostSingleton;

public class Startup implements IServerNotify2 {

	public void onServerConfigLoaded(IServer server) {
	}

	public void onServerCreate(IServer server) {
	}

	public void onServerInit(IServer server) {
		Connection conn = null;
		System.out.println("========== input start");
		try {
			conn = DBUtils.connect(Settings.DB);
			Statement stat = conn.createStatement();
			stat.executeUpdate("CREATE TABLE IF NOT EXISTS input (id INTEGER PRIMARY KEY AUTOINCREMENT, url VARCHAR(512) NOT NULL, enable BOOL NOT NULL);");
			stat.executeUpdate("CREATE TABLE IF NOT EXISTS delay (id INTEGER PRIMARY KEY AUTOINCREMENT, input_id INTEGER NOT NULL, length INTEGER NOT NULL, enable BOOL NOT NULL);");
			stat.executeUpdate("CREATE TABLE IF NOT EXISTS output (id INTEGER PRIMARY KEY AUTOINCREMENT, source_id INTEGER NOT NULL, source_type INTEGER NOT NULL, enable BOOL NOT NULL);");
			stat.executeUpdate("CREATE TABLE IF NOT EXISTS schedule (id INTEGER PRIMARY KEY AUTOINCREMENT, output_id INTEGER NOT NULL, source_id INTEGER NOT NULL, source_type INTEGER NOT NULL, start_time INTEGER NOT NULL, duration INTEGER NOT NULL);");
			stat.executeUpdate("CREATE TABLE IF NOT EXISTS file (id INTEGER PRIMARY KEY AUTOINCREMENT);");
			stat.executeUpdate("CREATE TABLE IF NOT EXISTS list (id INTEGER PRIMARY KEY AUTOINCREMENT, files VARCHAR(1024) NOT NULL, enable BOOL NOT NULL);");

			DBUtils.disConnect(conn);
		} catch (Exception e) {
			if (conn != null) {
				try {
					DBUtils.disConnect(conn);
				} catch (Exception e1) {
					WMSLoggerFactory.getLogger(null).error(e1);
				}
			}
		}
		IVHost vhost = VHostSingleton.getInstance(Settings.DEFAULT_VHOST);
		IApplication application = vhost.getApplication(Settings.APPLICATION);
		IApplicationInstance instance = application.getAppInstance(Settings.DEFAULT_INSTANCE);
		instance.addMediaCasterListener(new MediaCasterNotify2());

		try {
			InputModel input;
			for (Iterator<InputModel> iterator = InputModel.select().iterator(); iterator.hasNext();) {
				input = iterator.next();
				if (input.getEnable())
					InputModel.start(input.getId());
			}
		} catch (Exception e) {
			WMSLoggerFactory.getLogger(null).error(e);
		}
		// try {
		// DelayModel delay;
		// for (Iterator<DelayModel> iterator = DelayModel.select().iterator();
		// iterator.hasNext();) {
		// delay = iterator.next();
		// if (delay.getEnable())
		// DelayModel.start(delay.getId());
		// }
		// } catch (Exception e) {
		// WMSLoggerFactory.getLogger(null).error(e);
		// }
		try {
			ListModel list;
			for (Iterator<ListModel> iterator = ListModel.select().iterator(); iterator.hasNext();) {
				list = iterator.next();
				if (list.getEnable())
					ListModel.start(list.getId());
			}
		} catch (Exception e) {
			WMSLoggerFactory.getLogger(null).error(e);
		}
		try {
			OutputModel output;
			for (Iterator<OutputModel> iterator = OutputModel.select().iterator(); iterator.hasNext();) {
				output = iterator.next();
			}
		} catch (Exception e) {
			WMSLoggerFactory.getLogger(null).error(e);
		}
		try {
			new ScheduleWorker().start();
		} catch (Exception e) {
			WMSLoggerFactory.getLogger(null).error(e);
		}
	}

	public void onServerShutdownStart(IServer server) {
	}

	public void onServerShutdownComplete(IServer server) {
	}

	private class MediaCasterNotify2 implements IMediaCasterNotify2 {

		@Override
		public void onMediaCasterCreate(IMediaCaster mediaCaster) {
			// TODO Auto-generated method stub

		}

		@Override
		public void onMediaCasterDestroy(IMediaCaster mediaCaster) {
			// TODO Auto-generated method stub

		}

		@Override
		public void onRegisterPlayer(IMediaCaster mediaCaster, IMediaStreamPlay player) {
			// TODO Auto-generated method stub

		}

		@Override
		public void onSetSourceStream(IMediaCaster mediaCaster, IMediaStream stream) {
			// TODO Auto-generated method stub

		}

		@Override
		public void onUnRegisterPlayer(IMediaCaster mediaCaster, IMediaStreamPlay player) {
			// TODO Auto-generated method stub

		}

		@Override
		public void onConnectFailure(IMediaCaster mediaCaster) {
			// TODO Auto-generated method stub

		}

		@Override
		public void onConnectStart(IMediaCaster mediaCaster) {
			// TODO Auto-generated method stub

		}

		@Override
		public void onConnectSuccess(IMediaCaster mediaCaster) {
			int inputId = Integer.parseInt(parseStreamName(mediaCaster.getStream().getName()).get(0));

			List<Integer> list = new ArrayList<Integer>();
			try {
				for (Iterator<DelayModel> iterator = DelayModel.select().iterator(); iterator.hasNext();) {
					DelayModel delay = iterator.next();
					if (delay.getInputId() == inputId && delay.getEnable()) {
						list.add(delay.getId());
						DelayModel.stop(delay.getId());
						DelayModel.start(delay.getId());
					}
				}
			} catch (Exception e) {
				e.printStackTrace();
			}

			try {
				for (Iterator<OutputModel> iterator = OutputModel.select().iterator(); iterator.hasNext();) {
					OutputModel output = iterator.next();
					if ((output.getSourceId() == inputId && output.getSourceType() == SourceType.INPUT_ITEM) || (list.contains(output.getSourceId()) && output.getSourceType() == SourceType.DELAY_ITEM)) {
						if (output.getEnable()) {
							OutputModel.stop(output.getId());
							OutputModel.start(output.getId());
						}
					}
				}
			} catch (Exception e) {
				e.printStackTrace();
			}
		}

		@Override
		public void onStreamStart(IMediaCaster mediaCaster) {
			// TODO Auto-generated method stub

		}

		@Override
		public void onStreamStop(IMediaCaster mediaCaster) {
			// TODO Auto-generated method stub
		}

		private List<String> parseStreamName(String name) {
			ArrayList<String> list = new ArrayList<String>();
			StringTokenizer st = new StringTokenizer(name, ".");
			while (st.hasMoreTokens()) {
				list.add((String) st.nextElement());
			}
			return list;
		}
	}
}
