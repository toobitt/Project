package com.hogesoft.wowza.output;

import java.io.*;
import java.util.ArrayList;
import java.util.List;
import java.util.StringTokenizer;

import org.dom4j.Document;
import org.dom4j.DocumentHelper;
import org.dom4j.Element;

import com.hogesoft.wowza.exceptions.UndefinedActionTypeException;
import com.hogesoft.wowza.exceptions.UndefinedManagerTypeException;
import com.hogesoft.wowza.output.managers.*;
import com.wowza.wms.http.*;
import com.wowza.wms.logging.*;
import com.wowza.wms.vhost.*;

public class Provider extends HTTProvider2Base {

	private enum ManagerType {
		APPLICATION, STREAM, SNAPSHOT, NTP, DVR;

		private static final String APPLICATION_ITEM = "application";

		private static final String STREAM_ITEM = "stream";

		private static final String SNAPSHOT_ITEM = "snapshot";

		private static final String NTP_ITEM = "ntp";

		private static final String DVR_ITEM = "dvr";

		public static ManagerType getManagerType(String url) throws Exception {

			List<String> list = parseUrl(url);

			String manager = list.size() > 1 ? list.get(1).trim() : null;

			if (APPLICATION_ITEM.equalsIgnoreCase(manager)) {
				return APPLICATION;
			} else if (STREAM_ITEM.equalsIgnoreCase(manager)) {
				return STREAM;
			} else if (SNAPSHOT_ITEM.equalsIgnoreCase(manager)) {
				return SNAPSHOT;
			} else if (NTP_ITEM.equalsIgnoreCase(manager)) {
				return NTP;
			} else if (DVR_ITEM.equalsIgnoreCase(manager)) {
				return DVR;
			}
			throw new UndefinedManagerTypeException(manager);
		}
	}

	private static List<String> parseUrl(String url) {
		ArrayList<String> list = new ArrayList<String>();
		StringTokenizer st = new StringTokenizer(url, "/");
		while (st.hasMoreTokens()) {
			list.add((String) st.nextElement());
		}
		return list;
	}

	public void onHTTPRequest(IVHost vhost, IHTTPRequest request, IHTTPResponse response) {
		if (!doHTTPAuthentication(vhost, request, response))
			return;

		OutputStream out = response.getOutputStream();
		response.setHeader("Content-Type", "text/xml");
		String result = "";
		try {
			if (request.getMethod().equalsIgnoreCase("post"))
				request.parseBodyForParams(true);

			if (null == request.getParameterValues("action"))
				throw new UndefinedActionTypeException("null");

			String action = request.getParameterValues("action")[0];
			switch (ManagerType.getManagerType(request.getRequestURL())) {
				case APPLICATION:
					result = new ApplicationManager().handle(action, request.getParameterMap());
					break;
				case STREAM:
					result = new StreamManager().handle(action, request.getParameterMap());
					break;
				case SNAPSHOT:
					result = new SnapshotManager().handle(action, request.getParameterMap());
					break;
				case NTP:
					result = new NtpManager().handle(action, request.getParameterMap());
					break;
				case DVR:
					result = new DvrManager().handle(action, request.getParameterMap());
					break;
			}
		} catch (Exception e) {
			e.printStackTrace();
			Document document = DocumentHelper.createDocument();
			Element root = document.addElement("response");
			root.addAttribute("result", "0");
			Element element = root.addElement("error");
			element.addAttribute("message", "[" + e.getClass().getSimpleName() + "] " + e.getMessage());
			result = document.asXML();
		} finally {
			try {
				out.write(result.getBytes());
			} catch (Exception e) {
				WMSLoggerFactory.getLogger(getClass()).error("InputManagerProvider.onHTTPRequest: socket write error");
			}
		}
	}
}