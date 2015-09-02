package com.hogesoft.wowza.input.workers;

import com.hogesoft.wowza.input.models.DelayModel;
import com.hogesoft.wowza.input.models.FileModel;
import com.hogesoft.wowza.input.models.InputModel;
import com.hogesoft.wowza.input.models.ListModel;
import com.hogesoft.wowza.input.models.OutputModel;
import com.hogesoft.wowza.input.utils.Settings;
import com.hogesoft.wowza.input.utils.SourceType;
import com.wowza.wms.application.IApplication;
import com.wowza.wms.application.IApplicationInstance;
import com.wowza.wms.stream.publish.IPublishingProvider;
import com.wowza.wms.stream.publish.Publisher;
import com.wowza.wms.stream.publish.PublishingProviderLive;
import com.wowza.wms.stream.publish.PublishingProviderMediaReader;
import com.wowza.wms.vhost.IVHost;
import com.wowza.wms.vhost.VHostSingleton;

public final class PublishWorker extends Thread {

	private String publishName = "";

	private int sourceId;

	private int sourceType = 0;

	private boolean change = false;

	private long sleepTime = 25;

	private boolean running = true;

	private Object lock = new Object();

	public PublishWorker(String publishName, int id, int type) {
		this.publishName = publishName;
		sourceId = id;
		sourceType = type;
	}

	public synchronized void quit() {
		synchronized (lock) {
			running = false;
		}
	}

	private String getUrl(int sourceId) throws Exception {
		String str = "";
		switch (SourceType.getSourceType(sourceType)) {
		case INPUT:
			str = InputModel.select(sourceId).getId() + ".stream";
			break;
		case DELAY:
			str = DelayModel.select(sourceId).getId() + ".delay";
			break;
		case LIST:
			str = ListModel.select(sourceId).getId() + ".list";
			break;
		case FILE:
			str = "mp4:" + FileModel.select(sourceId).getName();
			break;
		case OUTPUT:
			str = OutputModel.select(sourceId).getId() + ".output";
			break;
		}
		return str;
	}

	public synchronized void change(int id, int type) {
		change = true;
		sourceType = type;
		sourceId = id;
	}

	public void run() {
		IVHost vhost = VHostSingleton.getInstance(Settings.DEFAULT_VHOST);
		IApplication application = vhost.getApplication(Settings.APPLICATION);
		IApplicationInstance instance = application.getAppInstance(Settings.DEFAULT_INSTANCE);

		Publisher publisher = Publisher.createInstance(vhost, Settings.APPLICATION);
		IPublishingProvider provider = null;

		int i = 0;
		while (i < 10) {
			if (instance.getStreams().getStream(publishName) != null) {
				try {
					i++;
					sleep(1000);
				} catch (InterruptedException e) {
					e.printStackTrace();
				}
			} else {
				publisher.publish(publishName);
				break;
			}
		}

		long currentTime;
		boolean moreInFile;

		while (true) {
			moreInFile = provider != null ? provider.play(publisher) : false;
			currentTime = System.currentTimeMillis();
			if (!moreInFile || change) {
				if (provider != null)
					provider.close();

				try {
					switch (SourceType.getSourceType(sourceType)) {
					case INPUT:
						provider = new PublishingProviderLive(publisher, publisher.getMaxTimecode(), InputModel.select(sourceId).getId() + ".stream");
						// System.out.println("==============================\n"
						// + "publishName :" + publishName + "     source: " +
						// InputModel.select(sourceId).getId() + ".stream" +
						// "\n==============================");
						break;
					case DELAY:
						provider = new PublishingProviderLive(publisher, publisher.getMaxTimecode(), DelayModel.select(sourceId).getId() + ".delay");
						// System.out.println("==============================\n"
						// + "publishName :" + publishName + "     source: " +
						// DelayModel.select(sourceId).getId() + ".delay" +
						// "\n==============================");
						break;
					case LIST:
						// ListModel.start(sourceId);
						provider = new PublishingProviderLive(publisher, publisher.getMaxTimecode(), ListModel.select(sourceId).getId() + ".list");
						// System.out.println("==============================\n"
						// + "publishName :" + publishName + "     source: " +
						// ListModel.select(sourceId).getId() + ".list" +
						// "\n==============================");
						break;
					case FILE:
						provider = new PublishingProviderMediaReader(publisher, publisher.getMaxTimecode(), "mp4:" + FileModel.select(sourceId).getName());
						// System.out.println("==============================\n"
						// + "publishName :" + publishName + "     source: " +
						// "mp4:" + FileModel.select(sourceId).getName() +
						// "\n==============================");
						break;
					case OUTPUT:
						provider = new PublishingProviderLive(publisher, publisher.getMaxTimecode(), OutputModel.select(sourceId).getId() + ".output");
						break;
					}
					provider.setRealTimeStartTime(currentTime);
					change = false;

				} catch (Exception e) {
					e.printStackTrace();
					try {
						sleep(sleepTime);
					} catch (InterruptedException e1) {
					}
				}
			} else {
				try {
					sleep(sleepTime);
				} catch (InterruptedException e) {
				}
			}

			synchronized (lock) {
				if (!running)
					break;
			}
		}

		synchronized (lock) {
			running = false;
		}

		provider.close();
		publisher.unpublish();
		publisher.close();
		// System.out.println("==============================\n" +
		// "publishName :" + publishName +
		// " closeed\n==============================");

	}
}