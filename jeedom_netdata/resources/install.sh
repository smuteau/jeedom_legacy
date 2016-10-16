#! /bin/bash
touch /tmp/nodered_dep

echo "Début installation"
echo "0" > /tmp/nodered_dep
cd ${1}

# Apt dependencies
sudo apt-get -y install zlib1g-dev gcc make git autoconf autogen automake pkg-config
echo "20" > /tmp/nodered_dep

# download it - the directory 'netdata.git' will be created
git clone https://github.com/firehol/netdata.git --depth=1
cd netdata
echo "60" > /tmp/nodered_dep

# build it
sudo ./netdata-installer.sh --install /opt

echo "90" > /tmp/nodered_dep

echo "Fin installation"
rm /tmp/nodered_dep
