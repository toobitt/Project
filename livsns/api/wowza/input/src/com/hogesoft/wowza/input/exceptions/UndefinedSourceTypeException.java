package com.hogesoft.wowza.input.exceptions;

import com.hogesoft.wowza.exceptions.MediaException;
import com.hogesoft.wowza.input.utils.ExceptionMessage;

public final class UndefinedSourceTypeException extends MediaException {

	private static final long serialVersionUID = 1L;

	public UndefinedSourceTypeException(int message) {
		super(ExceptionMessage.UndefinedSourceType + ": " + message);
	}

	public UndefinedSourceTypeException() {
		super(ExceptionMessage.UndefinedSourceType);
	}

}
