package com.hogesoft.wowza.record.workers;

import java.util.ArrayList;
import java.util.Date;
import java.util.Iterator;
import java.util.List;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;

import com.hogesoft.wowza.record.models.RecordModel;

public final class RecordWorker extends Thread {

	private static RecordWorker _instance = null;

	private static final int ROUND = 1000;

	private boolean _run = true;

	private Object _lock = new Object();

	private List<RecordModel> _start;

	private List<RecordModel> _end;

	private Date _now;

	private class Worker extends Thread {
		@Override
		public void run() {
			int time = (int) (_now.getTime() / 1000);
			RecordModel model;
			Iterator<RecordModel> iterator;
			synchronized (_instance) {
				for (iterator = _start.iterator(); iterator.hasNext();) {
					model = iterator.next();
					if (time >= model.getStartTime()) {
						try {
							RecordModel.start(model.getId());
							iterator.remove();
							_end.add(model);
						} catch (Exception e) {
							e.printStackTrace();
						}
					}
				}
				for (iterator = _end.iterator(); iterator.hasNext();) {
					model = iterator.next();
					if (time >= (model.getStartTime() + model.getDuration())) {
						try {
							RecordModel.stop(model.getId());
							RecordModel.delete(model.getId());
							iterator.remove();
						} catch (Exception e) {
							e.printStackTrace();
						}
					}
				}
			}
			super.run();
		}
	};

	public static RecordWorker getInstance() {
		return _instance;
	}

	public RecordWorker() throws Exception {
		super();
		if (_instance != null)
			throw new Exception("singletion");
		_instance = this;
	}

	@Override
	public void run() {
		_run = true;
		ExecutorService executor = Executors.newFixedThreadPool(1);
		try {
			_start = RecordModel.select();
			_end = new ArrayList<RecordModel>();
			while (true) {
				synchronized (_lock) {
					if (!_run)
						break;
				}
				_now = new Date();
				executor.execute(new Worker());
				sleep(ROUND);
			}
		} catch (Exception e) {
		}
		_instance = null;
		super.run();
	}

	public void add(RecordModel model) {
		synchronized (_instance) {
			_start.add(model);
		}
	}

	public void remove(RecordModel model) {
		synchronized (_instance) {
			_start.remove(model);
		}
	}

	public List<RecordModel> selectStartSchedule() {
		return _start;
	}

	public List<RecordModel> selectEndSchedule() {
		return _end;
	}
}
