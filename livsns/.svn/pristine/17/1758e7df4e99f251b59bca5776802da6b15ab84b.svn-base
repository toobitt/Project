package com.hogesoft.wowza.input.utils;

import com.hogesoft.wowza.input.exceptions.UndefinedSourceTypeException;

public enum SourceType {
	INPUT, DELAY, LIST, FILE, OUTPUT, URL;

	public static final int INPUT_ITEM = 1;

	public static final int DELAY_ITEM = 2;

	public static final int LIST_ITEM = 3;

	public static final int FILE_ITEM = 4;

	public static final int OPTPUT_ITEM = 5;

	public static final int URL_ITEM = 6;

	public static SourceType getSourceType(int type) throws Exception {
		switch (type) {
			case INPUT_ITEM:
				return INPUT;
			case DELAY_ITEM:
				return DELAY;
			case LIST_ITEM:
				return LIST;
			case FILE_ITEM:
				return FILE;
			case OPTPUT_ITEM:
				return OUTPUT;
			case URL_ITEM:
				return URL;
		}
		throw new UndefinedSourceTypeException(type);
	}
}
