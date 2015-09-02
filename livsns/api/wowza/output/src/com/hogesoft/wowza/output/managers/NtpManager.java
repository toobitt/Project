package com.hogesoft.wowza.output.managers;

import java.util.List;
import java.util.Map;

import org.dom4j.Document;
import org.dom4j.DocumentHelper;
import org.dom4j.Element;

import com.hogesoft.wowza.exceptions.UndefinedActionTypeException;
import com.hogesoft.wowza.managers.IManager;
import com.hogesoft.wowza.managers.ManagerBase;
import com.hogesoft.wowza.output.models.NtpModel;

public final class NtpManager extends ManagerBase implements IManager {

	@Override
	public String handle(String action, Map<String, List<String>> param) throws Exception {
		Document document = DocumentHelper.createDocument();
		Element root = document.addElement("response"), element;
		root.addAttribute("result", "1");
		switch (ActionType.getActionType(action)) {
			case START:
				element = root.addElement("ntp");
				element.addAttribute("utc", "" + NtpModel.start());
				break;
			default:
				throw new UndefinedActionTypeException(action);
		}
		return document.asXML();
	}

}
