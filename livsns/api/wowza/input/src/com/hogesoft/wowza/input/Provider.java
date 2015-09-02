package com.hogesoft.wowza.input;

import java.io.OutputStream;
import java.util.ArrayList;
import java.util.List;
import java.util.StringTokenizer;

import org.dom4j.Document;
import org.dom4j.DocumentHelper;
import org.dom4j.Element;

import com.hogesoft.wowza.exceptions.UndefinedActionTypeException;
import com.hogesoft.wowza.exceptions.UndefinedManagerTypeException;
import com.hogesoft.wowza.input.managers.*;

import com.wowza.wms.http.HTTProvider2Base;
import com.wowza.wms.http.IHTTPRequest;
import com.wowza.wms.http.IHTTPResponse;
import com.wowza.wms.logging.WMSLoggerFactory;
import com.wowza.wms.vhost.IVHost;

public final class Provider extends HTTProvider2Base {

	private enum ManagerType {
		
		INPUT, OUTPUT, DELAY, SCHEDULE, FILE, LIST;

		private static final String INPUT_ITEM = "input";

		private static final String OUTPUT_ITEM = "output";

		private static final String DELAY_ITEM = "delay";

		private static final String SCHEDULE_ITEM = "schedule";

		private static final String FILE_ITEM = "file";

		private static final String LIST_ITEM = "list";

		public static ManagerType getManagerType(String url) throws Exception {

			List<String> list = parseUrl(url);

			String manager = list.size() > 1 ? list.get(1).trim() : null;
			if (INPUT_ITEM.equalsIgnoreCase(manager)) {
				return INPUT;
			} else if (OUTPUT_ITEM.equalsIgnoreCase(manager)) {
				return OUTPUT;
			} else if (DELAY_ITEM.equalsIgnoreCase(manager)) {
				return DELAY;
			} else if (SCHEDULE_ITEM.equalsIgnoreCase(manager)) {
				return SCHEDULE;
			} else if (FILE_ITEM.equalsIgnoreCase(manager)) {
				return FILE;
			} else if (LIST_ITEM.equalsIgnoreCase(manager)) {
				return LIST;
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
				case INPUT:
					result = new InputManager().handle(action, request.getParameterMap());
					break;
				case OUTPUT:
					result = new OutputManager().handle(action, request.getParameterMap());
					break;
				case DELAY:
					result = new DelayManager().handle(action, request.getParameterMap());
					break;
				case SCHEDULE:
					result = new ScheduleManager().handle(action, request.getParameterMap());
					break;
				case FILE:
					result = new FileManager().handle(action, request.getParameterMap());
					break;
				case LIST:
					result = new ListManager().handle(action, request.getParameterMap());
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
