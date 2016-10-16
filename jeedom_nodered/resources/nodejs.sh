#!/bin/bash
cd $1
touch /tmp/nodered_dep
echo "Début de l'installation"

echo 0 > /tmp/nodered_dep
DIRECTORY="/var/www"
if [ ! -d "$DIRECTORY" ]; then
  echo "Création du home www-data pour npm"
  sudo mkdir $DIRECTORY
fi
sudo chown -R www-data $DIRECTORY
echo 10 > /tmp/nodered_dep
actual=`nodejs -v`;
echo "Version actuelle : ${actual}"

if [[ $actual == *"4."* || $actual == *"5."* ]]
then
  echo "Ok, version suffisante";
else
  echo "KO, version obsolète à upgrader";
  echo "Suppression du Nodejs existant et installation du paquet recommandé"
  sudo apt-get -y --purge autoremove nodejs npm
  arch=`arch`;
  echo 30 > /tmp/nodered_dep
  if [[ $arch == "armv6l" ]]
  then
    echo "Raspberry 1 détecté, utilisation du paquet pour armv6"
    sudo rm /etc/apt/sources.list.d/nodesource.list
    wget http://node-arm.herokuapp.com/node_latest_armhf.deb
    sudo dpkg -i node_latest_armhf.deb
    sudo ln -s /usr/local/bin/node /usr/local/bin/nodejs
    rm node_latest_armhf.deb
  fi

  if [[ $arch == "aarch64" ]]
  then
    wget http://dietpi.com/downloads/binaries/c2/nodejs_5-1_arm64.deb
    sudo dpkg -i nodejs_5-1_arm64.deb
    sudo ln -s /usr/local/bin/node /usr/local/bin/nodejs
    rm nodejs_5-1_arm64.deb
  fi

  if [[ $arch != "aarch64" && $arch != "armv6l" ]]
  then
    echo "Utilisation du dépot officiel"
    curl -sL https://deb.nodesource.com/setup_5.x | sudo -E bash -
    sudo apt-get install -y nodejs
  fi
  new=`nodejs -v`;
  echo "Version actuelle : ${new}"
fi

echo 70 > /tmp/nodered_dep

npm cache clean
sudo npm cache clean

sudo npm install -g node-red
echo 75 > /tmp/nodered_dep

# Apt dependencies
sudo apt-get -y install libavahi-compat-libdnssd-dev libusb-1.0-0-dev build-essential
echo 80 > /tmp/nodered_dep

# installing some additionnals modules
# Avahi/Bonjour discovery module
sudo npm install node-red-node-discovery -g
echo 85 > /tmp/nodered_dep

# google module
sudo npm install node-red-node-google -g
echo 88 > /tmp/nodered_dep

# sun events module
sudo npm install node-red-contrib-sunevents -g
echo 90 > /tmp/nodered_dep

# Json path
sudo npm install node-red-contrib-jsonpath -g
echo 92 > /tmp/nodered_dep

# geofence module, check if localisation is in zone
sudo npm install node-red-node-geofence -g
# geohas, decode latitude longitude from string
sudo npm install node-red-node-geohash -g
# Foursquare, recommandation on location
sudo npm install node-red-node-foursquare -g
echo 94 > /tmp/nodered_dep

# Ping
sudo npm install node-red-contrib-advanced-ping -g
sudo npm install node-red-node-ping -g
echo 95 > /tmp/nodered_dep

# WOL
sudo npm install node-red-node-wol -g
echo 96 > /tmp/nodered_dep

# SNMP
sudo npm install node-red-node-snmp -g
echo 97 > /tmp/nodered_dep

# Weather
sudo npm install node-red-node-forecastio -g
sudo npm install node-red-node-openweathermap -g
sudo npm install node-red-node-weather-underground -g
echo 98 > /tmp/nodered_dep

# General GPIO
sudo npm install node-red-contrib-gpio -g
echo 99 > /tmp/nodered_dep

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

rm /tmp/nodered_dep

echo "Fin de l'installation"
