<?php 
if(false)
{    	
    if( !isset($_SERVER['HTTPS'] ) ) 
    {
    	if($_SERVER['QUERY_STRING']!="")
    		header("Location:https://www.frotels.com/?".$_SERVER['QUERY_STRING']);
    	else
    		header("Location:https://www.frotels.com/");
    }
}
else if(1)
{
    if($_SERVER['SERVER_NAME']=="frotels.com")
    	header("Location:https://www.frotels.com/");
}

define("db_host", "localhost");
define("db_user", "frotels_dbuser");
define("db_password", "india@2012AWS#2008");
define("db_database", "frotels_frote8dj_frotelslive");
define("base_url", "https://www.frotels.com/");
//define("base_url", "http://localhost:81/frotels/");
define("server_root", "");
//define("mailer_path", "PHPMailer_5.2.0/class.phpmailer.php");
define("mailer_path", "/home/frotels/public_html/frotels/PHPMailer_5.2.0/class.phpmailer.php");



ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$conn = new mysqli(db_host,db_user,db_password,db_database);

if($conn->connect_error)
{
	echo "Databse Connection Error";
	exit();
}

session_start();
ob_start();?>