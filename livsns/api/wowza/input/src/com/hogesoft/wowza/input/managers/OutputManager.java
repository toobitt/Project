package com.hogesoft.wowza.input.managers;

import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;

import org.dom4j.Document;
import org.dom4j.DocumentHelper;
import org.dom4j.Element;

import com.hogesoft.wowza.exceptions.UndefinedActionTypeException;
import com.hogesoft.wowza.input.models.OutputModel;
import com.hogesoft.wowza.managers.IManager;
import com.hogesoft.wowza.managers.ManagerBase;

public final class OutputManager extends ManagerBase implements IManager {

	@Override
	public String handle(String action, Map<String, List<String>> param) throws Exception {
		Document document = DocumentHelper.createDocument();
		Element root = document.addElement("response"), element;
		root.addAttribute("result", "1");
		HashMap<String, String> map = new HashMap<String, String>();
		OutputModel model;
		switch (ActionType.getActionType(action)) {
			case INSERT:
				if (!param.containsKey("sourceId") || "".equals(param.get("sourceId").get(0).trim()))
					throw new IllegalArgumentException("sourceId");
				if (!param.containsKey("sourceType") || "".equals(param.get("sourceType").get(0).trim()))
					throw new IllegalArgumentException("sourceType");
				map.put("sourceId", param.get("sourceId").get(0).trim());
				map.put("sourceType", param.get("sourceType").get(0).trim());
				element = root.addElement("output");
				element.addAttribute("id", "" + OutputModel.insert(map).getId());
				break;
			case DELETE:
				if (!param.containsKey("id") || "".equals(param.get("id").get(0).trim()))
					throw new IllegalArgumentException("id");
				OutputModel.delete(Integer.parseInt(param.get("id").get(0).trim()));
				break;
			case UPDATE:
				if (!param.containsKey("id") || "".equals(param.get("id").get(0).trim()))
					throw new IllegalArgumentException("id");
				if (param.containsKey("sourceId") && !"".equals(param.get("sourceId").get(0).trim()))
					map.put("sourceId", param.get("sourceId").get(0).trim());
				if (param.containsKey("sourceType") && !"".equals(param.get("sourceType").get(0).trim()))
					map.put("sourceType", param.get("sourceType").get(0).trim());
				OutputModel.update(Integer.parseInt(param.get("id").get(0).trim()), map);
				break;
			case SELECT:
				if (!param.containsKey("id") || "".equals(param.get("id").get(0).trim())) {
					element = root.addElement("outputs");
					Element stream;
					for (Iterator<OutputModel> iterator = OutputModel.select().iterator(); iterator.hasNext();) {
						stream = element.addElement("output");
						model = iterator.next();
						stream.addAttribute("id", "" + model.getId());
						stream.addAttribute("sourceId", "" + model.getSourceId());
						stream.addAttribute("sourceType", "" + model.getSourceType());
						stream.addAttribute("enable", model.getEnable() ? "1" : "0");
					}
				} else {
					model = OutputModel.select(Integer.parseInt(param.get("id").get(0).trim()));
					element = root.addElement("output");
					element.addAttribute("id", "" + model.getId());
					element.addAttribute("sourceId", "" + model.getSourceId());
					element.addAttribute("sourceType", "" + model.getSourceType());
					element.addAttribute("enable", model.getEnable() ? "1" : "0");
				}
				break;
			case START:
				if (!param.containsKey("id") || "".equals(param.get("id").get(0).trim()))
					throw new IllegalArgumentException("id");
				OutputModel.start(Integer.parseInt(param.get("id").get(0).trim()));
				break;
			case STOP:
				if (!param.containsKey("id") || "".equals(param.get("id").get(0).trim()))
					throw new IllegalArgumentException("id");
				OutputModel.stop(Integer.parseInt(param.get("id").get(0).trim()));
				break;
			case CHANGE:
				if (!param.containsKey("id") || "".equals(param.get("id").get(0).trim()))
					throw new IllegalArgumentException("id");
				if (!param.containsKey("sourceId") || "".equals(param.get("sourceId").get(0).trim()))
					throw new IllegalArgumentException("sourceId");
				if (!param.containsKey("sourceId") || "".equals(param.get("sourceId").get(0).trim()))
					throw new IllegalArgumentException("sourceId");
				if (!param.containsKey("sourceType") || "".equals(param.get("sourceType").get(0).trim()))
					throw new IllegalArgumentException("sourceType");
				
				map.put("sourceId", "" + Integer.parseInt(param.get("sourceId").get(0).trim()));
				map.put("sourceType", "" + Integer.parseInt(param.get("sourceType").get(0).trim()));
				if (param.containsKey("notify") && !"".equals(param.get("notify").get(0).trim()))
					map.put("notify", param.get("notify").get(0).trim());
				OutputModel.change(Integer.parseInt(param.get("id").get(0).trim()), map);
				break;
			default:
				throw new UndefinedActionTypeException(action);
		}
		return document.asXML();
	}
}