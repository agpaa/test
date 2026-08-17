<?php

define('API_URL', 'https://api.hubapi.com');



//  API call to POST Contact
function makeApiCall($post_data) {
    $url = API_URL . '/crm/v3/objects/contacts';
    $headers = [
     'Authorization: Bearer pat-na2-ca237504-4d3b-4ff9-a447-646249021dd6', // need to be saved secretly somewhere.
    'Content-Type: application/json'
];
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($post_data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
  //echo $response;
    if ($response === false) {
        return false;
    }

    curl_close($ch);

    // Decode the API response
    $data = json_decode($response, true);
    return true;
}

?>
