#! /bin/bash
touch /tmp/nodered_dep

echo "Début installation"
echo "0%" > /tmp/nodered_dep

# installing node-red
sudo npm install -g node-red
echo "10%" > /tmp/nodered_dep

# Apt dependencies
sudo apt-get -y install libavahi-compat-libdnssd-dev libusb-1.0-0-dev build-essential
echo "15%" > /tmp/nodered_dep

# installing some additionnals modules
# Avahi/Bonjour discovery module
sudo npm install node-red-node-discovery -g
echo "20%" > /tmp/nodered_dep

# google module
sudo npm install node-red-node-google -g
echo "25%" > /tmp/nodered_dep

# sun events module
sudo npm install node-red-contrib-sunevents -g
echo "30%" > /tmp/nodered_dep

# Json path
sudo npm install node-red-contrib-jsonpath -g
echo "35%" > /tmp/nodered_dep

# geofence module, check if localisation is in zone
sudo npm install node-red-node-geofence -g
# geohas, decode latitude longitude from string
sudo npm install node-red-node-geohash -g
# Foursquare, recommandation on location
sudo npm install node-red-node-foursquare -g
echo "40%" > /tmp/nodered_dep

# Ping
sudo npm install node-red-contrib-advanced-ping -g
sudo npm install node-red-node-ping -g
echo "45%" > /tmp/nodered_dep

# WOL
sudo npm install node-red-node-wol -g
echo "50%" > /tmp/nodered_dep

# SNMP
sudo npm install node-red-node-snmp -g
echo "60%" > /tmp/nodered_dep

# Weather
sudo npm install node-red-node-forecastio -g
sudo npm install node-red-node-openweathermap -g
sudo npm install node-red-node-weather-underground -g
echo "70%" > /tmp/nodered_dep

# General GPIO
sudo npm install node-red-contrib-gpio -g
echo "80%" > /tmp/nodered_dep

    escaped="$1"

    # escape all backslashes first
    escaped="${escaped//\\/\\\\}"

    # escape slashes
    escaped="${escaped//\//\\/}"

    # escape asterisks
    escaped="${escaped//\*/\\*}"

    # escape full stops
    escaped="${escaped//./\\.}"

    # escape [ and ]
    escaped="${escaped//\[/\\[}"
    escaped="${escaped//\[/\\]}"

    # escape ^ and $
    escaped="${escaped//^/\\^}"
    escaped="${escaped//\$/\\\$}"

    # remove newlines
    escaped="${escaped//[$'\n']/}"

echo $1
echo $escaped
cp ${1}settings.tpl ${1}settings.js
sed -i -e 's/###jeedom###/'${escaped}'/g' ${1}settings.js
echo "90%" > /tmp/nodered_dep

#si jeedom v1, dynamic rules, si v2, répertoire
if [ ! `cat /etc/nginx/sites-available/default | grep "jeedom\.d"` ]; then
  sudo mkdir /etc/nginx/sites-available/jeedom.d
  sudo cp ${1}nginx /etc/nginx/sites-available/jeedom.d/nodered.conf
  sudo service nginx restart
fi

echo "Fin installation"
rm /tmp/nodered_dep
