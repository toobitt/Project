package com.hogesoft.wowza.output.models;

//import java.io.BufferedOutputStream;
import java.io.BufferedOutputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileOutputStream;
//import java.io.File;
//import java.io.FileOutputStream;

import com.hogesoft.wowza.models.ModelBase;
import com.hogesoft.wowza.output.utils.Settings;
import com.wowza.util.FLVUtils;
import com.wowza.wms.amf.AMFPacket;
import com.wowza.wms.application.IApplication;
import com.wowza.wms.application.IApplicationInstance;
import com.wowza.wms.stream.IMediaStream;
import com.wowza.wms.vhost.IVHost;
import com.wowza.wms.vhost.VHostSingleton;

import sun.misc.BASE64Encoder;

public final class SnapshotModel extends ModelBase {

	public static String capture(String applicationName, String streamName) throws Exception {
		String rtn = null;
		IVHost vhost = VHostSingleton.getInstance(Settings.DEFAULT_VHOST);
		IApplication application = vhost.getApplication(applicationName);
		IApplicationInstance instance = application.getAppInstance(Settings.DEFAULT_INSTANCE);
		IMediaStream stream = instance.getStreams().getStream(streamName);
		if(null == stream || !stream.isPlay())
			return null;
		
		AMFPacket packet = stream.getLastKeyFrame();
		AMFPacket codecConfig = stream.getVideoCodecConfigPacket(packet.getAbsTimecode());
		
		File newFile = stream.getStreamFileForWrite(streamName, null, null);
		String filePath = newFile.getPath().substring(0, newFile.getPath().length() - 4) + ".flv";
		if (packet != null) {
			BufferedOutputStream out = new BufferedOutputStream(new FileOutputStream(new File(filePath), false));
			FLVUtils.writeHeader(out, 0, null);

			if (codecConfig != null)
				FLVUtils.writeChunk(out, codecConfig.getDataBuffer(), codecConfig.getSize(), 0, (byte) codecConfig.getType());

			FLVUtils.writeChunk(out, packet.getDataBuffer(), packet.getSize(), 0, (byte) packet.getType());
			out.close();
		}

		try {
			FileInputStream in = new FileInputStream(filePath);
			byte[] tempbytes = new byte[in.available()];
			in.read(tempbytes);
			rtn = new BASE64Encoder().encode(tempbytes);
			in.close();
		} catch (Exception e1) {
			e1.printStackTrace();
		}

//		if (packet != null) {
//			ByteArrayOutputStream out = new ByteArrayOutputStream();
//			FLVUtils.writeHeader(out, 0, null);
//			if (codecConfig != null)
//				FLVUtils.writeChunk(out, codecConfig.getDataBuffer(), codecConfig.getSize(), 0, (byte) codecConfig.getType());
//			FLVUtils.writeChunk(out, codecConfig.getDataBuffer(), codecConfig.getSize(), 0, (byte) codecConfig.getType());
//			out.flush();
//			rtn = new BASE64Encoder().encode(out.toByteArray());
//			out.close();
//		}

		return rtn;
	}

}
