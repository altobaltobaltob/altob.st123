<?php
/*
file: call_something_daily.php       每日呼叫匯整
*/           
require_once '/home/bigbang/apps/coworker/station.config.php'; 

define('APP_NAME', 'call_something_daily');    // application name

// 發生錯誤時集中在此處理
function error_handler($errno, $errstr, $errfile, $errline, $errcontext)
{   
	//$str = date('H:i:s')."|{$errstr}|{$errfile}|{$errline}|{$errno}\n";               
	$str = date('H:i:s')."|{$errstr}\n";               
	
	echo $str;
	error_log($str, 3, LOG_PATH.APP_NAME . '.' . date('Ymd').'.log.txt');   // 3代表參考後面的檔名
}

set_error_handler('error_handler', E_ALL);

trigger_error('..start..');

// 呼叫
function post_data($url, $data)
{
	try
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); 
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_POST, FALSE);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,10);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10); //timeout in seconds
		$output = curl_exec($ch);
		curl_close($ch);

		trigger_error(__FUNCTION__ . "|{$url}|{$output}|" . json_encode($data));
	}
	catch(Exception $e)
	{
		trigger_error('ERROR: ' . $e->getMessage());
	}
	
	sleep(1);	// sleep for 3 seconds
}

// 需要排程呼叫的加在這
post_data('http://localhost/carpark.html/station_setting_query/', array('reload' => 1));	// 重新取得場站資訊

trigger_error('..completed..');