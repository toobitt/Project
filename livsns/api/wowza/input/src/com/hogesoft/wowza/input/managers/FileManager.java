package com.hogesoft.wowza.input.managers;

import java.util.HashMap;
import java.util.List;
import java.util.Map;

import org.dom4j.Document;
import org.dom4j.DocumentHelper;
import org.dom4j.Element;

import com.hogesoft.wowza.exceptions.UndefinedActionTypeException;
import com.hogesoft.wowza.input.models.FileModel;
import com.hogesoft.wowza.managers.IManager;
import com.hogesoft.wowza.managers.ManagerBase;

public final class FileManager extends ManagerBase implements IManager {

	@Override
	public String handle(String action, Map<String, List<String>> param) throws Exception {
		Document document = DocumentHelper.createDocument();
		Element root = document.addElement("response"), element;
		root.addAttribute("result", "1");
		HashMap<String, String> map = new HashMap<String, String>();
		switch (ActionType.getActionType(action)) {
			case INSERT:
				if (!param.containsKey("url") || "".equals(param.get("url").get(0).trim())) {
					throw new IllegalArgumentException("url");
				}
				if (!param.containsKey("callback") || "".equals(param.get("callback").get(0).trim())) {
					throw new IllegalArgumentException("callback");
				}
				map.put("url", param.get("url").get(0).trim());
				map.put("callback", param.get("callback").get(0).trim());
				element = root.addElement("file");
				element.addAttribute("id", "" + FileModel.insert(map).getId());
				break;
			case DELETE:
				if (!param.containsKey("id") || "".equals(param.get("id").get(0).trim())) {
					throw new IllegalArgumentException("id");
				}
				FileModel.delete(Integer.parseInt(param.get("id").get(0).trim()));
				break;
			default:
				throw new UndefinedActionTypeException(action);
		}
		return document.asXML();
	}
}
