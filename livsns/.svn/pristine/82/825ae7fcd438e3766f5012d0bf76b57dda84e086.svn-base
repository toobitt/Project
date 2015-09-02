package com.hogesoft.wowza.record.managers;

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
import com.hogesoft.wowza.record.models.RecordModel;
import com.hogesoft.wowza.record.workers.RecordWorker;

public final class RecordManager extends ManagerBase implements IManager {
	
	@Override
	public String handle(String action, Map<String, List<String>> param) throws Exception {
		Document document = DocumentHelper.createDocument();
		Element root = document.addElement("response"), element;
		root.addAttribute("result", "1");
		HashMap<String, String> map = new HashMap<String, String>();
		RecordModel model;
		switch (ActionType.getActionType(action)) {
			case INSERT:
				if (!param.containsKey("url") || "".equals(param.get("url").get(0).trim())) {
					throw new IllegalArgumentException("url");
				}
				if (!param.containsKey("callback") || "".equals(param.get("callback").get(0).trim())) {
					throw new IllegalArgumentException("callback");
				}
				if (!param.containsKey("startTime") || "".equals(param.get("startTime").get(0).trim())) {
					throw new IllegalArgumentException("startTime");
				}
				if (!param.containsKey("duration") || "".equals(param.get("duration").get(0).trim())) {
					throw new IllegalArgumentException("duration");
				}
				map.put("url", param.get("url").get(0).trim());
				map.put("callback", param.get("callback").get(0).trim());
				map.put("startTime", param.get("startTime").get(0).trim());
				map.put("duration", param.get("duration").get(0).trim());
				model = RecordModel.insert(map);
				element = root.addElement("record");
				element.addAttribute("id", "" + model.getId());
				break;
			case DELETE:
				if (!param.containsKey("id") || "".equals(param.get("id").get(0).trim())) {
					throw new IllegalArgumentException("id");
				}
				int id = Integer.parseInt(param.get("id").get(0).trim());
				RecordModel.delete(id);
				break;
			case SELECT:
				element = root.addElement("records");
				Element elements = root.addElement("start"), node;
				
				for (Iterator<RecordModel> iterator = RecordWorker.getInstance().selectStartSchedule().iterator(); iterator.hasNext();) {
					model = iterator.next();
					node = elements.addElement("item");
					node.addAttribute("id", "" + model.getId());
					node.addAttribute("url", model.getUrl());
					node.addAttribute("callback", model.getCallback());
					node.addAttribute("duration", "" + model.getDuration());
					node.addAttribute("startTime", "" + model.getStartTime());
				}
				elements = root.addElement("end");
				for (Iterator<RecordModel> iterator = RecordWorker.getInstance().selectEndSchedule().iterator(); iterator.hasNext();) {
					model = iterator.next();
					node = elements.addElement("item");
					node.addAttribute("id", "" + model.getId());
					node.addAttribute("url", model.getUrl());
					node.addAttribute("callback", model.getCallback());
					node.addAttribute("duration", "" + model.getDuration());
					node.addAttribute("startTime", "" + model.getStartTime());
				}
				break;
			default:
				throw new UndefinedActionTypeException(action);
		}
		return document.asXML();
	}
}
