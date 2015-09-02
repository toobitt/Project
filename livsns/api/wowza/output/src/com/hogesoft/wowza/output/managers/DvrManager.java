package com.hogesoft.wowza.output.managers;

import java.util.HashMap;
import java.util.List;
import java.util.Map;

import org.dom4j.Document;
import org.dom4j.DocumentHelper;
import org.dom4j.Element;

import com.hogesoft.wowza.managers.IManager;
import com.hogesoft.wowza.managers.ManagerBase;
import com.hogesoft.wowza.output.models.DvrModel;

public final class DvrManager extends ManagerBase implements IManager {

	private final static long DURATION = 7200000;

	@Override
	public String handle(String action, Map<String, List<String>> param) throws Exception {
		Document document = DocumentHelper.createDocument();
		Element root = document.addElement("response");
		root.addAttribute("result", "1");
		HashMap<String, String> map = new HashMap<String, String>();
		switch (ActionType.getActionType(action)) {
		case DELETE:
			if (!param.containsKey("id") || "".equals(param.get("id").get(0).trim())) {
				throw new IllegalArgumentException("id");
			}
			long duration = param.containsKey("duration") ? Long.parseLong(param.get("duration").get(0).trim()) : DURATION;
			map.put("time", param.containsKey("time") ? param.get("time").get(0).trim() : "" + (System.currentTimeMillis() - duration));
			map.put("duration", "" + duration);
			map.put("callback", param.containsKey("callback") ? param.get("callback").get(0).trim() : null);
			DvrModel.delete(Integer.parseInt(param.get("id").get(0).trim()), map);
			break;
		default:
			break;
		}

		return document.asXML();
	}

}
