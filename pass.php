<?php

$headers = apache_request_headers();
$headers_str = "";
$headers_str = $_SERVER['REMOTE_ADDR'] . "\n";
foreach ($headers as $key => $header) {
    $headers_str .= $key . ": " . $header . "\n";
}

if ( isset($_GET['name']) ) {
    
    $raw_name = $_GET['name'];
    $name_length = strlen($raw_name);

    if ( $name_length > 64 ) {
        #bail
        die();
    }

    $valid_chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890.-";

    for ($i = 0; $i < $name_length; $i++) {
        $found = strpos($valid_chars, $raw_name[$i]);
        if ( $found === FALSE ) {
            #bail
            die();
        }
    }

    $clean_name = $raw_name;

    require_once('config.php');
    $ret = "";
    $fp = fsockopen($handler, $uuid_check_port, $errno, $errstr, 1);
    if (!$fp) {
        echo "$errstr ($errno)<br />\n";
    } else {
        $out = $clean_name;
        fwrite($fp, $out);
        while (!feof($fp)) {
            $ret .= fgets($fp, 128); 
        }
        fclose($fp);
        echo $ret;
    }

    $headers_str .= $clean_name . "\n" . $ret;

    //$fp = fopen('connect_log.txt', 'a');
    //fwrite($fp, "\n");
    //fwrite($fp, $headers_str);
    //fwrite($fp, "\n");
    //fclose($fp);
}
