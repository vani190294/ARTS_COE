<?php
ini_set("date.timezone", "Asia/Kolkata");
date_default_timezone_set("Asia/Kolkata");

ini_set('max_execution_time', 0);
ini_set('memory_limit', '1024M');

$allow_ip = ['150.150.1.10','150.150.1.1','150.150.1.2','150.150.1.3','150.150.1.4','150.150.1.5','150.150.1.6','150.150.1.7','150.150.1.8','150.150.1.9','127.0.0.1','::1','172.26.18.4',];
//$allow_ip = ['127.0.0.1'];
if(in_array($_SERVER['REMOTE_ADDR'], $allow_ip))
{
	// comment out the following two lines when deployed to production
	
	if($_SERVER['REMOTE_ADDR']=='127.0.0.1' || $_SERVER['REMOTE_ADDR']=='::1' || $_SERVER['REMOTE_ADDR']=='http://localhost:8081')
	{
		defined('YII_DEBUG') or define('YII_DEBUG', true);
		defined('YII_ENV') or define('YII_ENV', 'dev');		
	}
	require(__DIR__ . '/vendor/autoload.php');
	require(__DIR__ . '/vendor/yiisoft/yii2/Yii.php');

	$config = require(__DIR__ . '/config/web.php');

	(new yii\web\Application($config))->run();

}
else
{

	$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	$url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

	$filename = __DIR__.'/access_logs/log_'.date("j.n.Y").'.txt';
    $content  = "User: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
		        "Accessed URLS: ".$url.PHP_EOL.
		        "----------------------------------------------------------------".PHP_EOL;

	//print_r(parse_url($url)); // This will returns the parts of the URL
	
	$removed_path = str_replace('index.php', '', parse_url($url, PHP_URL_PATH));
	$image_path = parse_url($url, PHP_URL_SCHEME)."://".parse_url($url, PHP_URL_HOST).parse_url($url, PHP_URL_PORT).$removed_path.'images/notfound.png'; 
	
	$image_path = $url.'/images/notfound.png'; 

    if(file_put_contents($filename, $content.PHP_EOL , FILE_APPEND | LOCK_EX))
    {   
    	$file_content = file_get_contents($filename, true);
    	echo "<div style='width:1000px;  text-align: center; margin: 0 auto;'><img src='".$image_path."' alt='not found' height='600' width='900' align='center' /></div>";
        
    }
    else {
         echo 'Nothing Found Log Failed';
    } 
}
