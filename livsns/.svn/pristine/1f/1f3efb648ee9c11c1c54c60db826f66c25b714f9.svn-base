package com.hogesoft.wowza.record;

import java.io.*;

import org.dom4j.Document;
import org.dom4j.DocumentHelper;
import org.dom4j.Element;

import com.hogesoft.wowza.exceptions.UndefinedActionTypeException;
import com.hogesoft.wowza.record.managers.RecordManager;
import com.wowza.wms.http.*;
import com.wowza.wms.logging.*;
import com.wowza.wms.vhost.*;

public class Provider extends HTTProvider2Base {

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
			result = new RecordManager().handle(action, request.getParameterMap());
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