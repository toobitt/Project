package com.hogesoft.wowza.managers;

import java.util.List;
import java.util.Map;

public interface IManager {
	
	public String handle(String action, Map<String, List<String>> param) throws Exception;

}
