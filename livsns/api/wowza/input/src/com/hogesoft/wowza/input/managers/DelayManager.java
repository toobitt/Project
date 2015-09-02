package com.hogesoft.wowza.input.managers;

import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;

import org.dom4j.Document;
import org.dom4j.DocumentHelper;
import org.dom4j.Element;

import com.hogesoft.wowza.exceptions.UndefinedActionTypeException;
import com.hogesoft.wowza.input.models.DelayModel;
import com.hogesoft.wowza.managers.IManager;
import com.hogesoft.wowza.managers.ManagerBase;

public final class DelayManager extends ManagerBase implements IManager {

	@Override
	public String handle(String action, Map<String, List<String>> param) throws Exception {
		Document document = DocumentHelper.createDocument();
		Element root = document.addElement("response"), element;
		root.addAttribute("result", "1");
		HashMap<String, String> map = new HashMap<String, String>();
		DelayModel model;
		switch (ActionType.getActionType(action)) {
			case INSERT:
				if (!param.containsKey("inputId") || "".equals(param.get("inputId").get(0).trim()))
					throw new IllegalArgumentException("inputId");
				if (!param.containsKey("length") || "".equals(param.get("length").get(0).trim()))
					throw new IllegalArgumentException("length");
				map.put("inputId", param.get("inputId").get(0).trim());
				map.put("length", param.get("length").get(0).trim());
				element = root.addElement("delay");
				element.addAttribute("id", "" + DelayModel.insert(map).getId());
				break;
			case DELETE:
				if (!param.containsKey("id") || "".equals(param.get("id").get(0).trim()))
					throw new IllegalArgumentException("id");
				DelayModel.delete(Integer.parseInt(param.get("id").get(0).trim()));
				break;
			case UPDATE:
				if (!param.containsKey("id") || "".equals(param.get("id").get(0).trim()))
					throw new IllegalArgumentException("id");
				map.put("inputId", param.get("inputId").get(0).trim());
				map.put("length", param.get("length").get(0).trim());
				DelayModel.update(Integer.parseInt(param.get("id").get(0).trim()), map);
				break;
			case SELECT:
				if (!param.containsKey("id") || "".equals(param.get("id").get(0).trim())) {
					element = root.addElement("delays");
					Element stream;
					for (Iterator<DelayModel> iterator = DelayModel.select().iterator(); iterator.hasNext();) {
						stream = element.addElement("delay");
						model = iterator.next();
						stream.addAttribute("id", "" + model.getId());
						stream.addAttribute("inputId", "" + model.getInputId());
						stream.addAttribute("length", "" + model.getLength());
						stream.addAttribute("enable", model.getEnable() ? "1" : "0");
					}
				} else {
					model = DelayModel.select(Integer.parseInt(param.get("id").get(0).trim()));
					element = root.addElement("delay");
					element.addAttribute("id", "" + model.getId());
					element.addAttribute("inputId", "" + model.getInputId());
					element.addAttribute("length", "" + model.getLength());
					element.addAttribute("enable", model.getEnable() ? "1" : "0");
				}
				break;
			case START:
				if (!param.containsKey("id") || "".equals(param.get("id").get(0).trim()))
					throw new IllegalArgumentException("id");
				DelayModel.start(Integer.parseInt(param.get("id").get(0).trim()));
				break;
			case STOP:
				if (!param.containsKey("id") || "".equals(param.get("id").get(0).trim()))
					throw new IllegalArgumentException("id");
				DelayModel.stop(Integer.parseInt(param.get("id").get(0).trim()));
				break;
			default:
				throw new UndefinedActionTypeException(action);
		}
		return document.asXML();
	}
}