package com.hogesoft.wowza.input.managers;

import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;

import org.dom4j.Document;
import org.dom4j.DocumentHelper;
import org.dom4j.Element;

import com.hogesoft.wowza.exceptions.UndefinedActionTypeException;
import com.hogesoft.wowza.input.models.ListModel;
import com.hogesoft.wowza.managers.IManager;
import com.hogesoft.wowza.managers.ManagerBase;

public final class ListManager extends ManagerBase implements IManager {

	@Override
	public String handle(String action, Map<String, List<String>> param) throws Exception {
		Document document = DocumentHelper.createDocument();
		Element root = document.addElement("response"), element;
		root.addAttribute("result", "1");
		HashMap<String, String> map = new HashMap<String, String>();
		switch (ActionType.getActionType(action)) {
			case INSERT:
				if (!param.containsKey("files") || "".equals(param.get("files").get(0).trim())) {
					throw new IllegalArgumentException("files");
				}
				map.put("files", param.get("files").get(0).trim());
				element = root.addElement("list");
				element.addAttribute("id", "" + ListModel.insert(map).getId());
				break;
			case DELETE:
				if (!param.containsKey("id") || "".equals(param.get("id").get(0).trim())) {
					throw new IllegalArgumentException("id");
				}
				ListModel.delete(Integer.parseInt(param.get("id").get(0).trim()));
				break;
			case UPDATE:
				if (!param.containsKey("id") || "".equals(param.get("id").get(0).trim())) {
					throw new IllegalArgumentException("id");
				}
				if (!param.containsKey("files") || "".equals(param.get("files").get(0).trim())) {
					throw new IllegalArgumentException("files");
				}
				int id = Integer.parseInt(param.get("id").get(0).trim());
				map.put("files", param.get("files").get(0));
				ListModel.update(id, map);
				break;
			case SELECT:
				ListModel list;
				if (!param.containsKey("id") || "".equals(param.get("id").get(0).trim())) {
					element = root.addElement("lists");
					for (Iterator<ListModel> listIterator = ListModel.select().iterator(); listIterator.hasNext();) {
						list = listIterator.next();
						Element element0 = element.addElement("list");
						element0.addAttribute("id", "" + list.getId());
						element0.addAttribute("files", "" + list.getFiles());
						element0.addAttribute("enable", list.getEnable() ? "1" : "0");
					}
				} else {
					list = ListModel.select(Integer.parseInt(param.get("id").get(0).trim()));
					element = root.addElement("list");
					element.addAttribute("id", "" + list.getId());
					element.addAttribute("files", "" + list.getFiles());
					element.addAttribute("enable", list.getEnable() ? "1" : "0");
				}
				break;
			case START:
				if (!param.containsKey("id") || "".equals(param.get("id").get(0).trim())) {
					throw new IllegalArgumentException("id");
				}
				ListModel.start(Integer.parseInt(param.get("id").get(0).trim()));
				break;
			case STOP:
				if (!param.containsKey("id") || "".equals(param.get("id").get(0).trim())) {
					throw new IllegalArgumentException("id");
				}
				ListModel.stop(Integer.parseInt(param.get("id").get(0).trim()));
				break;
			default:
				throw new UndefinedActionTypeException(action);
		}
		return document.asXML();
	}
}
