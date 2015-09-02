package com.hogesoft.wowza.output.managers;

import java.util.List;
import java.util.Map;

import org.dom4j.Document;
import org.dom4j.DocumentHelper;
import org.dom4j.Element;

import com.hogesoft.wowza.exceptions.UndefinedActionTypeException;
import com.hogesoft.wowza.managers.IManager;
import com.hogesoft.wowza.managers.ManagerBase;
import com.hogesoft.wowza.output.models.ApplicationModel;
import com.hogesoft.wowza.output.models.SnapshotModel;
import com.hogesoft.wowza.output.models.StreamModel;

public final class SnapshotManager extends ManagerBase implements IManager {

	@Override
	public String handle(String action, Map<String, List<String>> param) throws Exception {
		Document document = DocumentHelper.createDocument();
		Element root = document.addElement("response"), element;
		root.addAttribute("result", "1");
		switch (ActionType.getActionType(action)) {
			case START:
				if (!param.containsKey("streamId") || "".equals(param.get("streamId").get(0).trim())) {
					throw new IllegalArgumentException("streamId");
				}
				element = root.addElement("snapshot");
				StreamModel stream = StreamModel.select(Integer.parseInt(param.get("streamId").get(0).trim()));
				ApplicationModel application = ApplicationModel.select(stream.getApplicationId());
				
				element.addCDATA(SnapshotModel.capture(application.getName(), stream.getName() + ".stream"));
				break;
			default:
				throw new UndefinedActionTypeException(action);
		}
		return document.asXML();
	}

}
