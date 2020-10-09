using System;
using System.Data.SqlClient;

public class MicrosoftSQLserverClient
{
    private const string db_host = "localhost";
    private const int db_port = 1433;
    private const string db_name = "testdb";
    private const string db_username = "testuser";
    private const string db_password = "T3stUs3r!";

    private const string db_table = "animals";

    private const string db_update_column = "remark";
    private const string db_update_column_variable = "@updatevar";

    private const string db_column = "id";
    private const string db_column_variable = "@var";
    private const int db_column_value = 1;

    private const string db_total_name = "total";

    private const string db_factorial_variable = "@n";
    private const int db_factorial_value = 4;

    private const string db_result_name = "result";

    private static string GetNow()
    {
        DateTime dateTimeNow = DateTime.Now;
        return dateTimeNow.ToString();
    }

    public static void Main(string[] args)
    {
        // Build the connection string
        SqlConnectionStringBuilder connBuilder = new SqlConnectionStringBuilder();
        connBuilder.DataSource = String.Format("{0},{1}\\{2}", db_host, db_port, db_name);
        connBuilder.UserID = db_username;
        connBuilder.Password = db_password;

        try
        {
            using (SqlConnection conn = new SqlConnection(connBuilder.ConnectionString))
            {
                // Connect the database
                conn.Open();

                Console.WriteLine("Data source: {0}", conn.DataSource);
                Console.WriteLine("Server version: {0}", conn.ServerVersion);
                Console.WriteLine("Database: {0}", conn.Database);
                Console.WriteLine("Connection timeout: {0}", conn.ConnectionTimeout);
                Console.WriteLine("State: {0}", conn.State);
                Console.WriteLine();

                // UPDATE statement
                string new_comment = "C# " + GetNow();

                string sql0 = String.Format("update {0} set {1}={2} where {3}!={4}", db_table, db_update_column, db_update_column_variable, db_column, db_column_variable);
                using (var cmd = new SqlCommand(sql0, conn))
                {
                    cmd.Parameters.AddWithValue(db_update_column_variable, new_comment);
                    cmd.Parameters.AddWithValue(db_column_variable, db_column_value);

                    int updatedRows = cmd.ExecuteNonQuery();
                    Console.WriteLine("Total updated rows: {0}", updatedRows);
                }

                Console.WriteLine();

                // Full SELECT statement
                string sql1 = String.Format("select * from {0}", db_table);
                using (var cmd = new SqlCommand(sql1, conn))
                    using (SqlDataReader reader = cmd.ExecuteReader())
                    {
                        int columns = reader.FieldCount;
                        Console.WriteLine("Total columns: {0}", columns);

                        for (int ii = 0; ii < columns; ii++)
                        {
                            Console.WriteLine(" - {0} {1}", reader.GetName(ii), reader.GetDataTypeName(ii));
                        }

                        int number = 0;
                        while (reader.Read())
                        {
                            number++;
                            Console.Write(number);

                            for (int ii = 0; ii < columns; ii++)
                            {
                                string type = reader.GetDataTypeName(ii);

                                string value = "?";
                                if (!reader.IsDBNull(ii))
                                {
                                    if (type.EndsWith("char"))
                                    {
                                        value = reader.GetString(ii);
                                    }
                                    else if (type.Equals("datetime"))
                                    {
                                        value = reader.GetDateTime(ii).ToString();
                                    }
                                    else if (type.Equals("int"))
                                    {
                                        value = reader.GetInt32(ii).ToString();
                                    }
                                    else if (type.Equals("tinyint"))
                                    {
                                        value = reader.GetByte(ii).ToString();
                                    }
                                }
                                else
                                {
                                    value = "(null)";
                                }

                                Console.Write(" '{0}'", value);
                            }

                            Console.WriteLine();
                        }
                    }

                Console.WriteLine();

                // SELECT WHERE statement
                string sql2 = String.Format("select count(*) as {0} from {1} where {2}!={3}", db_total_name, db_table, db_column, db_column_variable);
                using (var cmd = new SqlCommand(sql2, conn))
                {
                    cmd.Parameters.AddWithValue(db_column_variable, db_column_value);

                    Object result = cmd.ExecuteScalar();
                    Console.WriteLine("Result: {0}", result);
                }

                Console.WriteLine();

                // SELECT function statement
                string sql3 = String.Format("select dbo.factorial({0}) as {1}", db_factorial_variable, db_result_name);
                using (var cmd = new SqlCommand(sql3, conn))
                {
                    cmd.Parameters.AddWithValue(db_factorial_variable, db_factorial_value);

                    Object result = cmd.ExecuteScalar();
                    Console.WriteLine("Result: {0}", result);
                }
            }
        }
        catch (SqlException mex)
        {
            Console.WriteLine("Sql error {0}: {1}", mex.Number, mex.Message);
        }

    }
}
