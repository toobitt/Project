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
import com.hogesoft.wowza.output.models.StreamModel;

public final class StreamManager extends ManagerBase implements IManager {

	@Override
	public String handle(String action, Map<String, List<String>> param) throws Exception {
		Document document = DocumentHelper.createDocument();
		Element root = document.addElement("response"), element;
		root.addAttribute("result", "1");
		HashMap<String, String> map = new HashMap<String, String>();
		StreamModel stream;
		switch (ActionType.getActionType(action)) {
			case INSERT:
				if (!param.containsKey("applicationId") || "".equals(param.get("applicationId").get(0).trim())) {
					throw new IllegalArgumentException("applicationId");
				}
				if (!param.containsKey("name") || "".equals(param.get("name").get(0).trim())) {
					throw new IllegalArgumentException("name");
				}
				if (!param.containsKey("url") || "".equals(param.get("url").get(0).trim())) {
					throw new IllegalArgumentException("url");
				}
				if (param.containsKey("id")) {
					map.put("id",  "" + param.get("id").get(0).trim());
				}
				map.put("applicationId", param.get("applicationId").get(0).trim());
				map.put("name", param.get("name").get(0).trim());
				map.put("url", param.get("url").get(0).trim());
				element = root.addElement("stream");
				element.addAttribute("id", "" + StreamModel.insert(map).getId());
				break;
			case DELETE:
				if (!param.containsKey("id") || "".equals(param.get("id").get(0).trim())) {
					throw new IllegalArgumentException("id");
				}
				StreamModel.delete(Integer.parseInt(param.get("id").get(0).trim()));
				break;
			case UPDATE:
				if (!param.containsKey("id") || "".equals(param.get("id").get(0).trim())) {
					throw new IllegalArgumentException("id");
				}
				int id = Integer.parseInt(param.get("id").get(0).trim());
				if (param.containsKey("url")) {
					map.put("url", param.get("url").get(0));
				}
				if (param.containsKey("name")) {
					map.put("name", param.get("name").get(0));
				}
				StreamModel.update(id, map);
				break;
			case SELECT:
				if (!param.containsKey("id") || "".equals(param.get("id").get(0).trim())) {
					element = root.addElement("streams");
					Element element0;
					for (Iterator<StreamModel> iterator = StreamModel.select().iterator(); iterator.hasNext();) {
						stream = iterator.next();
						element0 = element.addElement("stream");
						element0.addAttribute("id", "" + stream.getId());
						element0.addAttribute("name", stream.getName());
						element0.addAttribute("url", stream.getUrl());
						element0.addAttribute("enable", stream.getEnable() ? "1" : "0");
						element0.addAttribute("applicationId", "" + stream.getApplicationId());
					}
				} else {
					stream = StreamModel.select(Integer.parseInt(param.get("id").get(0).trim()));
					element = root.addElement("stream");
					element.addAttribute("id", "" + stream.getId());
					element.addAttribute("name", stream.getName());
					element.addAttribute("url", stream.getUrl());
					element.addAttribute("enable", stream.getEnable() ? "1" : "0");
					element.addAttribute("applicationId", "" + stream.getApplicationId());
				}
				break;
			case START:
				if (!param.containsKey("id") || "".equals(param.get("id").get(0).trim())) {
					throw new IllegalArgumentException("url");
				}
				StreamModel.start(Integer.parseInt(param.get("id").get(0)));
				break;
			case STOP:
				if (!param.containsKey("id") || "".equals(param.get("id").get(0).trim())) {
					throw new IllegalArgumentException("url");
				}
				StreamModel.stop(Integer.parseInt(param.get("id").get(0)));
				break;
			case CHANGE:
				if (!param.containsKey("id") || "".equals(param.get("id").get(0).trim())) {
					throw new IllegalArgumentException("id");
				}
				if (!param.containsKey("url") || "".equals(param.get("url").get(0).trim())) {
					throw new IllegalArgumentException("url");
				}
				StreamModel.change(Integer.parseInt(param.get("id").get(0).trim()), param.get("url").get(0));
				break;
			default:
				throw new UndefinedActionTypeException(action);
		}
		
		
		return document.asXML();
	}
}
