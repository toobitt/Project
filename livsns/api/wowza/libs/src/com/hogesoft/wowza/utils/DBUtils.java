package com.hogesoft.wowza.utils;

import java.sql.Connection;
import java.sql.DriverManager;

public final class DBUtils {

	public static Connection connect(String dbPath) throws Exception {
		Class.forName("org.sqlite.JDBC");
		Connection conn = DriverManager.getConnection("jdbc:sqlite:" + dbPath);
		conn.setAutoCommit(true);
		return conn;
	}

	public static void disConnect(Connection conn) throws Exception {
		if (conn != null) {
			conn.close();
			conn = null;
		}
	}
}