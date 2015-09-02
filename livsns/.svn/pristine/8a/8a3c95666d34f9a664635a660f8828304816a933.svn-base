package com.hogesoft.wowza.input.workers;

import java.util.ArrayList;
import java.util.Date;
import java.util.Iterator;
import java.util.List;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;

import com.hogesoft.wowza.input.models.ScheduleModel;

public final class ScheduleWorker extends Thread {

	private static ScheduleWorker _instance = null;

	private static final int ROUND = 1000;

	private boolean _run = true;

	private Object _lock = new Object();

	private List<ScheduleModel> _start;

	private List<ScheduleModel> _end;

	private Date _now;

	private class Worker extends Thread {
		@Override
		public void run() {
			int time = (int) (_now.getTime() / 1000);
			ScheduleModel model;
			Iterator<ScheduleModel> iterator;
			synchronized (_instance) {
				List<Integer> list = new ArrayList<Integer>();
				for (iterator = _start.iterator(); iterator.hasNext();) {
					model = iterator.next();
					// System.out.println("**************************************time: "
					// + time + "  startTime: " + model.getStartTime());
					if (time >= model.getStartTime()) {
						try {
							ScheduleModel.start(model.getId());
							// System.out.println("**************************************start: "
							// + model.getId());
							list.add(model.getOutputId());
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
							ScheduleModel.stop(model.getId(), !list.contains(model.getOutputId()));
							// System.out.println("**************************************stop: "
							// + model.getId());
							ScheduleModel.delete(model.getId());
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

	public static ScheduleWorker getInstance() {
		return _instance;
	}

	public ScheduleWorker() throws Exception {
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
			_start = ScheduleModel.select();
			_end = new ArrayList<ScheduleModel>();
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

	public void add(ScheduleModel model) {
		synchronized (_instance) {
			_start.add(model);
		}
	}

	public void remove(ScheduleModel model) {
		synchronized (_instance) {
			_start.remove(model);
		}
	}

	public List<ScheduleModel> selectStartSchedule() {
		return _start;
	}

	public List<ScheduleModel> selectEndSchedule() {
		return _end;
	}

	public void outputNotify(int id) {
		ScheduleModel model;
		Iterator<ScheduleModel> iterator;
		synchronized (_instance) {
			for (iterator = _end.iterator(); iterator.hasNext();) {
				model = iterator.next();
				if (id == model.getOutputId()) {
					iterator.remove();
					_start.add(model);
				}
			}
		}
	}
}
