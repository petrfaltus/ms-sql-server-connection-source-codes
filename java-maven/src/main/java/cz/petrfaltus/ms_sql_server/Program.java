package cz.petrfaltus.ms_sql_server;

import static java.lang.System.out;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.ResultSetMetaData;
import java.sql.SQLException;
import java.sql.Statement;

import java.util.Enumeration;
import java.util.Properties;

public class Program {
	private static final String DB_DRIVER = "com.microsoft.sqlserver.jdbc.SQLServerDriver";
	private static final String DB_TYPE = "jdbc:sqlserver";

	private static final String DB_HOST = "localhost";
	private static final int DB_PORT = 1433;
	private static final String DB_NAME = "testdb";
	private static final String DB_USERNAME = "testuser";
	private static final String DB_PASSWORD = "T3stUs3r!";

	private static final String DB_TABLE = "animals";

	public static void main(String[] args) {
		try {
			Class.forName(DB_DRIVER);

			// Build the connection string and connect the database
			String url = DB_TYPE + "://" + DB_HOST + ":" + DB_PORT + ";database=" + DB_NAME;
			Connection conn = DriverManager.getConnection(url, DB_USERNAME, DB_PASSWORD);

			Properties connInfo = conn.getClientInfo();
			@SuppressWarnings("unchecked")
			Enumeration<String> connInfoPropNames =  (Enumeration<String>) connInfo.propertyNames();
			while (connInfoPropNames.hasMoreElements()) {
				String key = connInfoPropNames.nextElement();
				String value = connInfo.getProperty(key);
				out.println(key + " : " + value);
			}

			// Full SELECT statement
			Statement stm1 = conn.createStatement();
			ResultSet rs1 = stm1.executeQuery("select * from " + DB_TABLE);
			ResultSetMetaData rsmd1 = rs1.getMetaData();

			int columns1 = rsmd1.getColumnCount();
			out.println("Total columns: " + columns1);
			for (int ii = 1; ii <= columns1; ii++) {
				out.println(" - " + rsmd1.getColumnName(ii) + " " + rsmd1.getColumnTypeName(ii) + " (" + rsmd1.getPrecision(ii) + ")");
			}

			int rowNumber1 = 0;
			while (rs1.next()) {
				++rowNumber1;

				out.print(rowNumber1 + ")");

				for (int ii = 1; ii <= columns1; ii++) {
					out.print(" '" + rs1.getObject(ii) + "'");
				}

				out.println();
			}
			out.println();

			// Disconnect the database
			conn.close();

		} catch (ClassNotFoundException cnfex) {
			out.println(cnfex.getMessage());

		} catch (SQLException sex) {
			out.println("SQL error code: " + sex.getErrorCode());
			out.println(sex.getMessage() + " " + sex.getErrorCode());

		}

	}

}
