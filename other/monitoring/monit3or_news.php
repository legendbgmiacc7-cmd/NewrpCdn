<?php

ini_set('error_reporting', false);
ini_set('display_errors', false);
ini_set('html_errors', false);
ini_set("allow_url_fopen", 1);

define('ROOT_DIR', dirname (__FILE__));
define('INC_DIR', ROOT_DIR.'/api');

require_once INC_DIR.'/api.php';

// $url = 'http://cdn.crime-mobile.ru/monitoring/online.json';
$obj = json_decode(file_get_contents('./online.json'), true);

$RolePlayS1MinJson = $obj[0]['online'];
$RolePlayS1PingJson = $obj[0]['ping'];
$RolePlayS2MinJson = $obj[1]['online'];
$RolePlayS2PingJson = $obj[1]['ping'];

$API = new ServicesAPI('nAhYuTgHMQ2A8DTDTacT3fPUDqbikjIUvGTrpkJn');

$server_ip_S1 = '79.137.97.42:7777';
$server_ip_S2 = '79.137.97.41:7777';

$server_info_S1 = $API->method("server.get", array("address" => $server_ip_S1));
$server_info_S2 = $API->method("server.get", array("address" => $server_ip_S2));

$RolePlayS1Min = 0;
$RolePlayS1Max = 0;
$RolePlayS2Min = 0;
$RolePlayS2Max = 0;

if ($server_info_S1['result']['players']['now'] != null && $server_info_S1['result']['ping'] != null) {
	if ($server_info_S1['result']['players']['now'] == 0) {
		$RolePlayS1Min = $RolePlayS1MinJson;
		$RolePlayS1Max = $RolePlayS1PingJson;
	} else {
		$RolePlayS1Min = $server_info_S1['result']['players']['now'];
		$RolePlayS1Max = $server_info_S1['result']['ping'];
	}
} else {
	$RolePlayS1Min = $RolePlayS1MinJson;
	$RolePlayS1Max = $RolePlayS1PingJson;
}

if ($server_info_S2['result']['players']['now'] != null && $server_info_S2['result']['ping'] != null) {
	if ($server_info_S2['result']['players']['now'] == 0) {
		$RolePlayS2Min = $RolePlayS2MinJson;
		$RolePlayS2Max = $RolePlayS2PingJson;
	} else {
		$RolePlayS2Min = $server_info_S2['result']['players']['now'];
		$RolePlayS2Max = $server_info_S2['result']['ping'];
	}
} else {
	$RolePlayS2Min = $RolePlayS2MinJson;
	$RolePlayS2Max = $RolePlayS2PingJson;
}

$str = "
	[
	  {
	  	\"name\": \"Crime Mobile | Server 1\",
	  	\"online\":".$RolePlayS1Min.",
	  	\"ping\":".$RolePlayS1Max."
	  },
	  {
	  	\"name\": \"Crime Mobile | Server 2\",
	  	\"online\":".$RolePlayS2Min.",
	  	\"ping\":".$RolePlayS2Max."
	  }
	  	]";

$f=fopen('online.json','w');
fwrite($f,$str);
fclose($f);

echo "
	[
	  {
	  	\"name\": \"Crime Mobile | Server 1\",
	  	\"online\":".$RolePlayS1Min.",
	  	\"ping\":".$RolePlayS1Max."
	  },
	  {
	  	\"name\": \"Crime Mobile | Server 2\",
	  	\"online\":".$RolePlayS2Min.",
	  	\"ping\":".$RolePlayS2Max."
	  }
	  	]";
?>