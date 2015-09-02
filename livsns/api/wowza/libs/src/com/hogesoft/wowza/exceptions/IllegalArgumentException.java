package com.hogesoft.wowza.exceptions;

import com.hogesoft.wowza.utils.ExceptionMessage;

public final class IllegalArgumentException extends MediaException {

	private static final long serialVersionUID = 1L;

	public IllegalArgumentException(String message) {
		super(ExceptionMessage.IllegalArgument + ": " + message);
	}

	public IllegalArgumentException() {
		super(ExceptionMessage.IllegalArgument);
	}

}
