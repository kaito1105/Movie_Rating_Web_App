<?php
// This file does not generate an HTML page.
// Require in first line of all application pages.

// Database Connection Info
//////////////////////////////////////////////////////////////////

// XAMPP for localhost
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'csci327_local');

// Make the Database Connection
//////////////////////////////////////////////////////////////////
$db_conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
if ($db_conn->connect_errno) {
  echo "Failed to connect to MySQL: (" . $db_conn->connect_errno . ") " . $db_conn->connect_error;
  exit;
}

// Function for database queries with SQL error reporting
//////////////////////////////////////////////////////////////////
function db_query($sql)
{
  // This is a wrapper for the PHP $mysqli->query() function which doesn't have good SQL error reporting
  // On a failed SQL query, this function relays error messages from the database connection

  global $db_conn;  // DB connection created above

  $result = $db_conn->query($sql) or trigger_error("Query Failed! SQL: $query - Error: " . mysqli_error($db_conn), E_USER_ERROR);
  return $result;
}

// Start PHP's built-in session capabilities
//////////////////////////////////////////////////////////////////
session_start();
?>