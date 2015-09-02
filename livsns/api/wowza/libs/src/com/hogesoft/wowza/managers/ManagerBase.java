package com.hogesoft.wowza.managers;

import com.hogesoft.wowza.exceptions.UndefinedActionTypeException;

public abstract class ManagerBase {

	protected enum ActionType {
		INSERT, DELETE, UPDATE, SELECT, LIST, START, STOP, CHANGE;

		private static final String INSERT_ITEM = "insert";

		private static final String DELETE_ITEM = "delete";

		private static final String UPDATE_ITEM = "update";

		private static final String SELECT_ITEM = "select";

		private static final String LIST_ITEM = "list";

		private static final String START_ITEM = "start";

		private static final String STOP_ITEM = "stop";

		private static final String CHANGE_ITEM = "change";

		public static ActionType getActionType(String action) throws Exception {
			if (INSERT_ITEM.equalsIgnoreCase(action))
				return INSERT;
			else if (DELETE_ITEM.equalsIgnoreCase(action))
				return DELETE;
			else if (UPDATE_ITEM.equalsIgnoreCase(action))
				return UPDATE;
			else if (SELECT_ITEM.equalsIgnoreCase(action))
				return SELECT;
			else if (LIST_ITEM.equalsIgnoreCase(action))
				return LIST;
			else if (START_ITEM.equalsIgnoreCase(action))
				return START;
			else if (STOP_ITEM.equalsIgnoreCase(action))
				return STOP;
			else if (CHANGE_ITEM.equalsIgnoreCase(action))
				return CHANGE;
			else
				throw new UndefinedActionTypeException(action);
		}
	}
}
