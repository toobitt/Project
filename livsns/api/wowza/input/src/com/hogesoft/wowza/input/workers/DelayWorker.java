package com.hogesoft.wowza.input.workers;

import java.util.ArrayList;
import java.util.List;

import com.hogesoft.wowza.input.utils.Settings;
import com.wowza.util.FLVUtils;
import com.wowza.wms.amf.AMFPacket;
import com.wowza.wms.application.IApplication;
import com.wowza.wms.application.IApplicationInstance;
import com.wowza.wms.stream.IMediaStream;
import com.wowza.wms.stream.MediaStreamMap;
import com.wowza.wms.stream.publish.Publisher;
import com.wowza.wms.vhost.IVHost;
import com.wowza.wms.vhost.VHostSingleton;

public final class DelayWorker extends Thread {
	private IApplicationInstance appInstance = null;
	private String srcStreamName = null;
	private String dstStreamName = null;
	private boolean running = true;
	private boolean quit = false;
	private boolean isFirstVideo = true;
	private boolean isFirstAudio = true;
	private int lastSrcStreamId = -1;
	private long lastTC = -1;
	private long lastSeq = -1;
	private long timecodeOffset = -1;
	private boolean foundStartPacket = false;
	private int idleTimeout = 50;
	private int delay = 0; // in milliseconds
	private List<AMFPacket> packetList = new ArrayList<AMFPacket>();

	public DelayWorker(String srcStreamName, String dstStreamName, int delay) {
		IVHost vhost = VHostSingleton.getInstance(Settings.DEFAULT_VHOST);
		IApplication application = vhost.getApplication(Settings.APPLICATION);
		appInstance = application.getAppInstance(Settings.DEFAULT_INSTANCE);
		this.srcStreamName = srcStreamName;
		this.dstStreamName = dstStreamName;
		this.delay = delay;
	}

	public synchronized boolean isRunning() {
		return this.running;
	}

	public synchronized void quit() {
		this.quit = true;
	}

	public int getIdleTimeout() {
		return idleTimeout;
	}

	public void setIdleTimeout(int idleTimeout) {
		this.idleTimeout = idleTimeout;
	}

	public int getDelay() {
		return delay;
	}

	public void setDelay(int delay) {
		this.delay = delay;
	}

	public void run() {

		Publisher publisher = Publisher.createInstance(appInstance);

		publisher.publish(dstStreamName);

		MediaStreamMap streams = appInstance.getStreams();
		long startTime = -1;

		while (true) {
			try {
				IMediaStream stream = streams.getStream(srcStreamName);
				while (true) {
					if (stream == null)
						continue;

					List<AMFPacket> packets = stream.getPlayPackets();
					if (packets == null)
						break;

					int size = packets.size();

					if (size == 0)
						break;

					if (stream.getSrc() != lastSrcStreamId) {
						lastSrcStreamId = stream.getSrc();
						this.timecodeOffset = -1;
						this.lastSeq = -1;
						if (startTime == -1)
							startTime = System.currentTimeMillis();
						foundStartPacket = false;
					}

					AMFPacket packet = null;
					packet = (AMFPacket) packets.get(0);
					long startSeq = packet.getSeq();
					int startIdx = (this.lastSeq == -1 ? 0 : (int) (this.lastSeq - startSeq + 1));
					if (startIdx < 0)
						startIdx = 0;
					if (startIdx >= size)
						break;

					if (!foundStartPacket) {
						int idx = size - 1;
						for (; idx >= 0; idx--) {
							packet = (AMFPacket) packets.get(idx);

							int packetType = packet.getType();
							if (packetType != IVHost.CONTENTTYPE_VIDEO && packetType != IVHost.CONTENTTYPE_AUDIO)
								continue;

							if (packetType == IVHost.CONTENTTYPE_VIDEO) {
								if (FLVUtils.isVideoKeyFrame(packet)) {
									startIdx = idx;

									long timeoffset = 0;
									if (this.lastTC != -1)
										timeoffset = System.currentTimeMillis() - startTime;

									timecodeOffset = timeoffset - packet.getAbsTimecode();
									foundStartPacket = true;
									break;
								}
							}
						}

					}

					if (foundStartPacket) {
						for (int idx = startIdx; idx < size; idx++) {
							packet = (AMFPacket) packets.get(idx);
							int packetType = packet.getType();
							this.lastSeq = packet.getSeq();

							long adjTimecode = packet.getAbsTimecode() + timecodeOffset;
							this.lastTC = adjTimecode;

							AMFPacket newPacket = new AMFPacket(packetType, 0, packet.getSize());
							newPacket.setAbsTimecode(adjTimecode);
							newPacket.addData(packet.getData(), 0, packet.getSize());

							packetList.add(newPacket);
						}
					}
					break;
				}

				while (true) {
					if (stream == null)
						break;

					if (packetList.size() > 0) {
						long maxTimecode = System.currentTimeMillis() - startTime - delay;
						while (true) {
							if (maxTimecode <= 0)
								break;

							AMFPacket amfPacket = (AMFPacket) packetList.get(0);
							if (amfPacket.getAbsTimecode() > maxTimecode)
								break;

							switch (amfPacket.getType()) {
							case IVHost.CONTENTTYPE_AUDIO:
								if (isFirstAudio) {
									AMFPacket configPacket = stream.getAudioCodecConfigPacket(amfPacket.getAbsTimecode());
									if (configPacket != null)
										publisher.addAudioData(configPacket.getData(), configPacket.getSize(), amfPacket.getAbsTimecode());
									isFirstAudio = false;
								}
								publisher.addAudioData(amfPacket.getData(), amfPacket.getSize(), amfPacket.getAbsTimecode());
								break;
							case IVHost.CONTENTTYPE_VIDEO:
								if (isFirstVideo) {
									AMFPacket configPacket = stream.getVideoCodecConfigPacket(amfPacket.getAbsTimecode());
									if (configPacket != null)
										publisher.addVideoData(configPacket.getData(), configPacket.getSize(), amfPacket.getAbsTimecode());
									isFirstVideo = false;
								}
								publisher.addVideoData(amfPacket.getData(), amfPacket.getSize(), amfPacket.getAbsTimecode());
								break;
							case IVHost.CONTENTTYPE_DATA0:
							case IVHost.CONTENTTYPE_DATA3:
								publisher.addDataData(amfPacket.getData(), amfPacket.getSize(), amfPacket.getAbsTimecode());
								break;
							}

							packetList.remove(0);
							if (packetList.size() == 0)
								break;
						}
					}
					break;
				}

				sleep(idleTimeout);

				synchronized (this) {
					if (this.quit) {
						this.running = false;
						break;
					}
				}
			} catch (Exception e) {
				e.printStackTrace();
			}
		}

		publisher.unpublish();
		publisher.close();
	}
}