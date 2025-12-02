<?php /*
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
*/

// Enable CORS for frontend requests
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}
//sharvesh system start
// define("db_host", "localhost");
// define("db_user", "u721475486_pmseasy"); 
// define("db_password", "banqueteasy@2012AWS#2008");
// define("db_database", "u721475486_pmseasy");
//sharvesh system end
define("db_host", "localhost");
define("db_user", "root"); 
define("db_password", "");
define("db_database", "pms");
// production db start 
// define("db_host", "srv921.hstgr.io");
// define("db_user", "u620203265_frotels");
// define("db_password", "Vcet@2024");
// define("db_database", "u620203265_frotels");
// production db end 


// define("base_url", "https://www.nikhilkadam.com/frotels/");
define("base_url", "http://localhost/pms");
// define("base_url", "https://www.pmseasy.in/pms");
define("server_root", "");
//define("mailer_path", "PHPMailer_5.2.0/class.phpmailer.php");
define("mailer_path", "/files/public_html/frotels/PHPMailer_5.2.0/class.phpmailer.php");



ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(1); //E_ALL


$conn = new mysqli(db_host,db_user,db_password,db_database);

if($conn->connect_error)
{
	echo "Databse Connection Error";
	exit();
}

session_start();
ob_start();
?>