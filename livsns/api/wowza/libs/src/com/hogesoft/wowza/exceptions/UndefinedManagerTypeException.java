package com.hogesoft.wowza.exceptions;

import com.hogesoft.wowza.utils.ExceptionMessage;

public final class UndefinedManagerTypeException extends MediaException {

	private static final long serialVersionUID = 1L;

	public UndefinedManagerTypeException(String message) {
		super(ExceptionMessage.UndefinedManagerType + ": " + message);
	}

	public UndefinedManagerTypeException() {
		super(ExceptionMessage.UndefinedManagerType);
	}
}
