package com.hogesoft.wowza.input.managers;

import java.text.SimpleDateFormat;
import java.util.Date;
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
import com.hogesoft.wowza.input.models.ScheduleModel;

public final class ScheduleManager extends ManagerBase implements IManager {
	
	@Override
	public String handle(String action, Map<String, List<String>> param) throws Exception {
		Document document = DocumentHelper.createDocument();
		Element root = document.addElement("response"), element;
		root.addAttribute("result", "1");
		HashMap<String, String> map = new HashMap<String, String>();
		ScheduleModel model;
		switch (ActionType.getActionType(action)) {
			case INSERT:
				if (!param.containsKey("outputId") || "".equals(param.get("outputId").get(0).trim())) {
					throw new IllegalArgumentException("outputId");
				}
				if (!param.containsKey("sourceId") || "".equals(param.get("sourceId").get(0).trim())) {
					throw new IllegalArgumentException("sourceId");
				}
				if (!param.containsKey("sourceType") || "".equals(param.get("sourceType").get(0).trim())) {
					throw new IllegalArgumentException("sourceType");
				}
				if (!param.containsKey("startTime") || "".equals(param.get("startTime").get(0).trim())) {
					throw new IllegalArgumentException("startTime");
				}
				if (!param.containsKey("duration") || "".equals(param.get("duration").get(0).trim())) {
					throw new IllegalArgumentException("duration");
				}
				map.put("outputId", param.get("outputId").get(0).trim());
				map.put("sourceId", param.get("sourceId").get(0).trim());
				map.put("sourceType", param.get("sourceType").get(0).trim());
				map.put("startTime", param.get("startTime").get(0).trim());
				map.put("duration", param.get("duration").get(0).trim());
				model = ScheduleModel.insert(map);
				element = root.addElement("schedule");
				element.addAttribute("id", "" + model.getId());
				break;
			case DELETE:
				if (!param.containsKey("id") || "".equals(param.get("id").get(0).trim())) {
					throw new IllegalArgumentException("id");
				}
				int id = Integer.parseInt(param.get("id").get(0).trim());
				ScheduleModel.delete(id);
				break;
			case LIST:
				SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd hh:mm:ss");
				root.addAttribute("time", format.format(new Date()));
				int time = (int) (System.currentTimeMillis() / 1000);
				root.addAttribute("utc", "" + time);
				element = root.addElement("schedules");
				Element element0 = element.addElement("start"),  element1 = element.addElement("end");
				Map<String, List<ScheduleModel>> list = ScheduleModel.list();
				for (Iterator<ScheduleModel> iterator = list.get("start").iterator(); iterator.hasNext();) {
					model = iterator.next();
					element = element0.addElement("schedule");
					element.addAttribute("id", "" + model.getId());
					element.addAttribute("outputId", "" + model.getOutputId());
					element.addAttribute("sourceId", "" + model.getSourceId());
					element.addAttribute("sourceType", "" + model.getSourceType());
					element.addAttribute("startTime", "" + model.getStartTime());
					element.addAttribute("duration", "" + model.getDuration());
					element.addAttribute("will", "" + (model.getStartTime() - time));
				}
				for (Iterator<ScheduleModel> iterator = list.get("end").iterator(); iterator.hasNext();) {
					model = iterator.next();
					element = element1.addElement("schedule");
					element.addAttribute("id", "" + model.getId());
					element.addAttribute("outputId", "" + model.getOutputId());
					element.addAttribute("sourceId", "" + model.getSourceId());
					element.addAttribute("sourceType", "" + model.getSourceType());
					element.addAttribute("startTime", "" + model.getStartTime());
					element.addAttribute("duration", "" + model.getDuration());
					element.addAttribute("will", "" + (model.getStartTime() + model.getDuration() - time));
				}
				break;
			default:
				throw new UndefinedActionTypeException(action);
		}
		return document.asXML();
	}
}
