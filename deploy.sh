#!/bin/bash

#create config.ini
echo "*** Generating config.ini ***"
cat > config.ini << EOF
[site]
base_url = "https://www.jeremyrobson.com"
admin_name = "Jeremy Robson"
admin_email = "jeremy23@jeremyrobson.com"
contact_email = "contact@jeremyrobson.com"
[mail]
host = $mail_host
port = $mail_port
auth = true
username = $mail_username
password = $mail_password
secure = $mail_secure
debug = "0"
EOF