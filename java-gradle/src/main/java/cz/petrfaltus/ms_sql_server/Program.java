package cz.petrfaltus.ms_sql_server;

import static java.lang.System.out;

import java.sql.CallableStatement;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.ResultSetMetaData;
import java.sql.SQLException;
import java.sql.Statement;
import java.sql.Types;

import java.text.DateFormat;
import java.text.SimpleDateFormat;

import java.util.Date;
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

	private static final String DB_UPDATE_COLUMN = "remark";

	private static final String DB_COLUMN = "id";
	private static final int DB_COLUMN_VALUE = 1;

	private static final String DB_TOTAL_NAME = "total";

	private static final int DB_FACTORIAL_VALUE = 4;

	private static final String DB_RESULT_NAME = "result";

	private static final int DB_ADD_AND_SUBTRACT_A_VALUE = 12;
	private static final int DB_ADD_AND_SUBTRACT_B_VALUE = 5;

	private static String getNow()
	{
		Date date = new Date();
		DateFormat dateFormat = new SimpleDateFormat("d.M.yyyy H:mm:ss");
		String retValue = dateFormat.format(date);

		return retValue;
	}

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

			// UPDATE statement
			String new_comment = "Java " + getNow();

			String stm0query = "update " + DB_TABLE + " set " + DB_UPDATE_COLUMN + "=? where " + DB_COLUMN + "!=?";
			out.println(stm0query);

			PreparedStatement stm0 = conn.prepareStatement(stm0query);
			stm0.setString(1, new_comment);
			stm0.setInt(2, DB_COLUMN_VALUE);
			int updatedRows0 = stm0.executeUpdate();
			out.println("Total updated rows: " + updatedRows0);
			out.println();

			// Full SELECT statement
			String stm1query = "select * from " + DB_TABLE;
			out.println(stm1query);

			Statement stm1 = conn.createStatement();
			ResultSet rs1 = stm1.executeQuery(stm1query);
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

			// SELECT WHERE statement
			String stm2query = "select count(*) as " + DB_TOTAL_NAME + " from " + DB_TABLE + " where " + DB_COLUMN + "!=?";
			out.println(stm2query);

			PreparedStatement stm2 = conn.prepareStatement(stm2query);
			stm2.setInt(1, DB_COLUMN_VALUE);
			ResultSet rs2 = stm2.executeQuery();
			ResultSetMetaData rsmd2 = rs2.getMetaData();

			int columns2 = rsmd2.getColumnCount();
			out.println("Total columns: " + columns2);
			for (int ii = 1; ii <= columns2; ii++) {
				out.println(" - " + rsmd2.getColumnName(ii) + " " + rsmd2.getColumnTypeName(ii) + " (" + rsmd2.getPrecision(ii) + ")");
			}

			int rowNumber2 = 0;
			while (rs2.next()) {
				++rowNumber2;

				out.print(rowNumber2 + ")");

				for (int ii = 1; ii <= columns2; ii++) {
					out.print(" '" + rs2.getObject(ii) + "'");
				}

				out.println();
			}
			out.println();

			// SELECT function statement
			String stm3query = "select dbo.factorial(?) as " + DB_RESULT_NAME;
			out.println(stm3query);

			PreparedStatement stm3 = conn.prepareStatement(stm3query);
			stm3.setInt(1, DB_FACTORIAL_VALUE);
			ResultSet rs3 = stm3.executeQuery();
			ResultSetMetaData rsmd3 = rs3.getMetaData();

			int columns3 = rsmd3.getColumnCount();
			out.println("Total columns: " + columns3);
			for (int ii = 1; ii <= columns3; ii++) {
				out.println(" - " + rsmd3.getColumnName(ii) + " " + rsmd3.getColumnTypeName(ii) + " (" + rsmd3.getPrecision(ii) + ")");
			}

			int rowNumber3 = 0;
			while (rs3.next()) {
				++rowNumber3;

				out.print(rowNumber3 + ")");

				for (int ii = 1; ii <= columns3; ii++) {
					out.print(" '" + rs3.getObject(ii) + "'");
				}

				out.println();
			}
			out.println();

			// EXECUTE procedure statement
			String stm4query = "execute dbo.add_and_subtract ?, ?, ?, ?";
			out.println(stm4query);

			CallableStatement stm4 = conn.prepareCall(stm4query);
			stm4.setInt(1, DB_ADD_AND_SUBTRACT_A_VALUE);
			stm4.setInt(2, DB_ADD_AND_SUBTRACT_B_VALUE);
			stm4.registerOutParameter(3, Types.INTEGER);
			stm4.registerOutParameter(4, Types.INTEGER);
			stm4.execute();

			out.println("'" + stm4.getObject(3) + "'");
			out.println("'" + stm4.getObject(4) + "'");

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
