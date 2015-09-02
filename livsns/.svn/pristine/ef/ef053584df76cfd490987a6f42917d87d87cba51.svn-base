package com.hogesoft.wowza.output.managers;

import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;

import org.dom4j.Document;
import org.dom4j.DocumentHelper;
import org.dom4j.Element;

import com.hogesoft.wowza.exceptions.UndefinedActionTypeException;
import com.hogesoft.wowza.managers.IManager;
import com.hogesoft.wowza.managers.ManagerBase;
import com.hogesoft.wowza.output.models.ApplicationModel;
import com.hogesoft.wowza.output.models.StreamModel;

public final class ApplicationManager extends ManagerBase implements IManager {

	@Override
	public String handle(String action, Map<String, List<String>> param) throws Exception {
		Document document = DocumentHelper.createDocument();
		Element root = document.addElement("response"), element;
		root.addAttribute("result", "1");
		ApplicationModel application;
		HashMap<String, String> map = new HashMap<String, String>();
		switch (ActionType.getActionType(action)) {
			case INSERT:
				if (!param.containsKey("name") || "".equals(param.get("name").get(0).trim())) {
					throw new IllegalArgumentException("name");
				}
				if (!param.containsKey("length") || "".equals(param.get("length").get(0).trim())) {
					throw new IllegalArgumentException("length");
				}
				if (!param.containsKey("type") || "".equals(param.get("type").get(0).trim())) {
					throw new IllegalArgumentException("type");
				}
				if (!param.containsKey("drm") || "".equals(param.get("drm").get(0).trim())) {
					throw new IllegalArgumentException("drm");
				}
				if (param.containsKey("id")) {
					map.put("id",  "" + param.get("id").get(0).trim());
				}
				map.put("name",  "" + param.get("name").get(0).trim());
				map.put("length",  "" + param.get("length").get(0).trim());
				map.put("drm",  "" + param.get("drm").get(0).trim());
				map.put("type",  "" + param.get("type").get(0).trim());
				element = root.addElement("application");
				element.addAttribute("id", "" + ApplicationModel.insert(map).getId());
				break;
			case DELETE:
				if (!param.containsKey("id") || "".equals(param.get("id").get(0).trim())) {
					throw new IllegalArgumentException("id");
				}
				ApplicationModel.delete(Integer.parseInt(param.get("id").get(0).trim()));
				break;
			case UPDATE:
				if (!param.containsKey("id") || "".equals(param.get("id").get(0).trim())) {
					throw new IllegalArgumentException("id");
				}
				int id = Integer.parseInt(param.get("id").get(0).trim());
				if (param.containsKey("name")) {
					map.put("name", param.get("name").get(0));
				}
				if (param.containsKey("length")) {
					map.put("length", param.get("length").get(0));
				}
				if (param.containsKey("type")) {
					map.put("type", param.get("type").get(0));
				}
				if (param.containsKey("drm")) {
					map.put("drm", param.get("drm").get(0));
				}
				ApplicationModel.update(id, map);
				break;
			case SELECT:
				if (!param.containsKey("id") || "".equals(param.get("id").get(0).trim())) {
					element = root.addElement("applications");
					Element element0;
					for (Iterator<ApplicationModel> iterator = ApplicationModel.select().iterator(); iterator.hasNext();) {
						application = iterator.next();
						element0 = element.addElement("application");
						element0.addAttribute("id", "" + application.getId());
						element0.addAttribute("name", application.getName());
						element0.addAttribute("length", "" + application.getLength());
						element0.addAttribute("type", "" + application.getType());
						element0.addAttribute("drm", "" + application.getDrm());
					}
				} else {
					application = ApplicationModel.select(Integer.parseInt(param.get("id").get(0).trim()));
					element = root.addElement("application");
					element.addAttribute("id", "" + application.getId());
					element.addAttribute("name", application.getName());
					element.addAttribute("length", "" + application.getLength());
					element.addAttribute("type", "" + application.getType());
					element.addAttribute("drm", "" + application.getDrm());
				}
				break;
			case LIST:
				if (!param.containsKey("id") || "".equals(param.get("id").get(0).trim()))
					throw new IllegalArgumentException("id");
				element = root.addElement("streams");
				StreamModel model;
				Element element1;
				for (Iterator<StreamModel> iterator = ApplicationModel.list(Integer.parseInt(param.get("id").get(0).trim())).iterator(); iterator.hasNext();) {
					model = iterator.next();
					element1 = element.addElement("stream");
					element1.addAttribute("id", "" + model.getId());
					element1.addAttribute("url", model.getUrl());
					element1.addAttribute("enable", model.getEnable() ? "1" : "0");
				}
				break;
			default:
				throw new UndefinedActionTypeException(action);
		}
		return document.asXML();
	}
}
