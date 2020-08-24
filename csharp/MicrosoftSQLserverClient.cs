using System;
using System.Data.SqlClient;

public class MicrosoftSQLserverClient
{
    private const string db_host = "localhost";
    private const int db_port = 1433;
    private const string db_name = "testdb";
    private const string db_username = "testuser";
    private const string db_password = "T3stUs3r!";

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

            }
        }
        catch (SqlException mex)
        {
            Console.WriteLine("Sql error {0}: {1}", mex.Number, mex.Message);
        }

    }
}
