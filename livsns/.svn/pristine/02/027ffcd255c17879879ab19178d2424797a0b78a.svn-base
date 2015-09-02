package com.hogesoft.wowza.models;

import java.io.File;

public class ModelBase {

	protected static void delTree(File file) {
		if (file.exists()) {
			if (file.isFile()) {
				file.delete();
			} else if (file.isDirectory()) {
				File files[] = file.listFiles();
				for (int i = 0; i < files.length; i++) {
					delTree(files[i]);
				}
			}
			file.delete();
		}
	}
}
