<?php

$q = $_REQUEST['term'];
// TelecomsXChange Buyer Username, get one at www.telecomsxchange.com/buyerjoin

$api_login ="ENTER YOUR BUYER USERNAME HERE";

//API key
$api_key = "ENTER YOUR API KEY"; 
// initialising CURL
$ch = curl_init();

//controller is a script name, so in case lookup.php controller is lookup
$controller = "get_zoom_list";

//unix timestamp to ensure that signature will be valid temporary
$ts = time();

//compose signature concatenating controller api_key api_login and unix timestamp
$signature = hash( 'sha256', $controller .  $api_key   . $api_login  . $ts);

$params = array(
                'ts' => $ts,  //provide TS
                'q' => $q,
                'api_login' => $api_login,
                'signature' => $signature,
                'webapi' => 1,
                //...

                );


//query against api. URL

//debug CURL?
//curl_setopt($ch, CURLOPT_VERBOSE, true);


curl_setopt($ch, CURLOPT_URL,"https://members.telecomsxchange.com/scripts/autocomplete/$controller.php");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,
http_build_query($params));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$server_output = curl_exec ($ch);
curl_close ($ch);

//analyze JSON output
//echo "server_output:$server_output";
$response = json_decode($server_output, JSON_OBJECT_AS_ARRAY);
//print_r($response);
if($response['status'] == 'success' ) {
        header('Content-Type: application/json');
        echo json_encode($response['entries']);
}


?>
