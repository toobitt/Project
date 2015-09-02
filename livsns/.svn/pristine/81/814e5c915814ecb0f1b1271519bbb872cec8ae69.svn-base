package com.hogesoft.wowza.input.managers;

import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;

import org.dom4j.Document;
import org.dom4j.DocumentHelper;
import org.dom4j.Element;

import com.hogesoft.wowza.exceptions.UndefinedActionTypeException;
import com.hogesoft.wowza.input.models.InputModel;
import com.hogesoft.wowza.managers.IManager;
import com.hogesoft.wowza.managers.ManagerBase;

public final class InputManager extends ManagerBase implements IManager {

	@Override
	public String handle(String action, Map<String, List<String>> param) throws Exception {
		Document document = DocumentHelper.createDocument();
		Element root = document.addElement("response"), element;
		root.addAttribute("result", "1");
		HashMap<String, String> map = new HashMap<String, String>();
		InputModel stream;
		switch (ActionType.getActionType(action)) {
			case INSERT:
				if (!param.containsKey("url") || "".equals(param.get("url").get(0).trim())) {
					throw new IllegalArgumentException("url");
				}
				map.put("url", param.get("url").get(0).trim());
				element = root.addElement("input");
				element.addAttribute("id", "" + InputModel.insert(map).getId());
				break;
			case DELETE:
				if (!param.containsKey("id") || "".equals(param.get("id").get(0).trim())) {
					throw new IllegalArgumentException("id");
				}
				InputModel.delete(Integer.parseInt(param.get("id").get(0).trim()));
				break;
			case UPDATE:
				if (!param.containsKey("id") || "".equals(param.get("id").get(0).trim())) {
					throw new IllegalArgumentException("id");
				}
				int id = Integer.parseInt(param.get("id").get(0).trim());
				if (param.containsKey("url")) {
					map.put("url", param.get("url").get(0));
				}
				InputModel.update(id, map);
				break;
			case SELECT:
				if (!param.containsKey("id") || "".equals(param.get("id").get(0).trim())) {
					element = root.addElement("inputs");
					Element element0;
					for (Iterator<InputModel> iterator = InputModel.select().iterator(); iterator.hasNext();) {
						stream = iterator.next();
						element0 = element.addElement("input");
						element0.addAttribute("id", "" + stream.getId());
						element0.addAttribute("url", stream.getUrl());
						element0.addAttribute("isAudioReady", stream.isAudioReady() ? "1" : "0");
						element0.addAttribute("isVideoReady", stream.isVideoReady() ? "1" : "0");
						element0.addAttribute("enable", stream.getEnable() ? "1" : "0");
					}
				} else {
					stream = InputModel.select(Integer.parseInt(param.get("id").get(0).trim()));
					element = root.addElement("input");
					element.addAttribute("id", "" + stream.getId());
					element.addAttribute("isAudioReady", stream.isAudioReady() ? "1" : "0");
					element.addAttribute("isVideoReady", stream.isVideoReady() ? "1" : "0");
					element.addAttribute("url", stream.getUrl());
					element.addAttribute("enable", stream.getEnable() ? "1" : "0");
				}
				break;
			case START:
				if (!param.containsKey("id") || "".equals(param.get("id").get(0).trim())) {
					throw new IllegalArgumentException("id");
				}
				InputModel.start(Integer.parseInt(param.get("id").get(0).trim()));
				break;
			case STOP:
				if (!param.containsKey("id") || "".equals(param.get("id").get(0).trim())) {
					throw new IllegalArgumentException("id");
				}
				InputModel.stop(Integer.parseInt(param.get("id").get(0).trim()));
				break;
			default:
				throw new UndefinedActionTypeException(action);
		}
		return document.asXML();
	}
}
