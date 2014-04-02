# QUICK START

Run Responder.py -i <handler_IP> from your handler box
Setup the html folder on a web server with at least apache and PHP
This can be setup on the same box as Responder.py, or a different one
Change the IP in config.php to <handler_IP>

NOTE: <handler_IP> is used as to identify the handler box to the CLIENT and to the pass.php script. This matters for firewall rules, see the FIREWALL RULES section for more details

# THEORY OF OPERATION

When a victim visits index.php with IE it triggers the UNC path lookup that hits the <handler_IP>. Responder.py stores the <filename> from the lookup in a global dictionary that persists as long as Responder.py runs. On the victim machine when index.php finishes loading javascript an ajax call is made to pass.php with the <filename> in a GET request; name=<filename>. pass.php uses a TCP socket to connect to <handler_IP> on port 8080 and feeds in the <filename>. Responder.py is listening on port 8080 and takes the <filename> and checks it against the dictionary of filenames that have been passed via IE triggered UNC lookup. If the filename exists in the dictionary Responder.py returns a 1 to the PHP script (pass.php), which passes the 1 along to the javascript running on the victim machine. If the filename does not exist in the dictionary Responder.py returns a 0.

The javascript on the page checks to see what the result of the GET request to pass.php was
if it was a 1, the VULNERABLE text is displayed; if it was a 0 the not vuln text stays displayed.

# ASCII ART FLOW

VICTIM_IE    --- request / ----> WEBSERVER

VICTIM_IE    <-- index.php ----- WEBSERVER (unique filename generated for UNC lookup)

VICTIM_IE    --- UNC LOOKUP ---> HANDLER (store filename)

VICTIM_IE    <-- AUTHFAIL ------ HANDLER


VICTIM_IE_JS --  FILENAME -----> WEBSERVER

WEBSERVER    --  FILENAME -----> HANDLER (over port 8080)

WEBSERVER    <-- 1 OR 0   ------ HANDLER (checks dictionary of filenames seen over UNC)

VICTIM_IE_JS <-- 1 OR 0   ------ WEBSERVER (passthrough from HANDLER)

VICTIM_EYES <-- VULN/NOTVULN --- VICTIM_IE


# FIREWALL RULES

## WEBSERVER

### INBOUND
allow from all victms IP on tcp port 80

### OUTBOUND
allow to handler IP on tcp port 8080

## HANDLER

### INBOUND
allow from all victims IP on tcp port 445
allow from webserver on tcp port 8080

# TODO

In Responder.py denote the successful parsing of a hash for a given SMB filename request.
