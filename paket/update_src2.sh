#!/bin/bash
#
#  Copyright ©  2008 ... 2016
#  Gebr. Märklin & Cie GmbH
#  Abteilung TEE
#
#  15.06.16 by Andreas Kielkopf
#
# Diese Datei enthält Funktionen die von anderen Scripten genutzt werden können

# Konstanten:
BTRFS_MOUNTPOINT="/mnt"
KENNUNG_INTERN="[Maske:|Mask:|netmask ]255.255.248.0"
SERVER_EXTERN="213.158.103.25"
SERVER_DNS="cs3update.maerklin.com"

# früheren Parameter auch beruecksichtigen
if [ -f /etc/cs3/updateversion ] ; then
  source /etc/cs3/updateversion
  chmod 666 /etc/cs3/updateversion
  chmod 777 /etc/cs3
fi

# Die Version kann als Variable gesetzt werden, bevor das Script ausgeführt wird, und bleibt dann erhalten
! [ -v VERSION ] && VERSION="CS3UPDATE"
! [ -v TIMEOUT ] && TIMEOUT=30
! [ -v BWLIMIT ] && BWLIMIT=500
! [ -v LOGDIR ]  && LOGDIR=/tmp/log
mkdir -p $LOGDIR

# Die folgenden Variablen können später noch geändert werden, bevor die entsprechende Funktion aufgerufen wird:
BTRFS_IMAGE="/media/usb?/*.btrfs /media/sd[a-z]?/*.btrfs"
PARAMETER="--exclude=CVS --include=/home/cs3/.ssh/cs3update_key --exclude=/home/cs3/.ssh/* --timeout=$TIMEOUT --info=progress2"
INSTSRC=""

QUELLE="/paket/"
ZIEL="/run/paket/"
RSYNC="-acxXHPz --delay-updates -hhh"
ZUSATZ="-n"   # ZUSATZ muß extern gesetzt werden. Ansonsten nur dry-run !!!
KEY="/home/cs3/.ssh/cs3update_key"
USER="cs3update"

#Auswahl des vorhandenen Keys mit den meisten Berechtigungen. 
#Hintergrund: Nicht jeder soll die HEAD/ALPHA/FREIGABE abrufen können
check_keys () {
  if [ -e /home/cs3/.ssh/cs3developer_key ] ; then
    KEY="/home/cs3/.ssh/cs3developer_key"
    USER="cs3developer"
  elif [ -e /home/cs3/.ssh/cs3extended_tester_key ] ; then
    KEY="/home/cs3/.ssh/cs3extended_tester_key"
    USER="cs3exttester"
  elif [ -e /home/cs3/.ssh/cs3tester_key ] ; then
    KEY="/home/cs3/.ssh/cs3tester_key"
    USER="cs3tester"
  elif [ -e /home/cs3/.ssh/cs3update_key ] ; then
    KEY="/home/cs3/.ssh/cs3update_key"
    USER="cs3update"
  fi
}

#Bestimmen des Keys mit den meisten Berechtigungen
check_keys

# Funktionen fuer Scripte (meist mit Rueckgabewert)
eth0_bereit () {
  echo -n "eth0_bereit ?"
  /sbin/ifconfig -s eth0|egrep 'BMR?U'
}

dns_2_ip () {
  # echo "DNS_testen"
  eth0_bereit >/dev/null || return -1
  IP=$(arp $DNS|egrep -o '[0-9.]{7,15}')
}

usb_rsync () {
  PARAMETER="$PARAMETER --bwlimit=0"
  echo "rsync mount=$BTRFS_MOUNTPOINT$QUELLE ziel=$ZIEL parameter=$PARAMETER zusatz=$ZUSATZ"
  SYNCQUELLE=""
  for f in $QUELLE ; do
    SYNCQUELLE="$SYNCQUELLE $BTRFS_MOUNTPOINT/$f"
  done
  rsync $RSYNC $ZUSATZ $PARAMETER $SYNCQUELLE $ZIEL
}

ssh_rsync () {
  PARAMETER="$PARAMETER --bwlimit=$BWLIMIT"
  chmod 600 $KEY
  chown cs3:cs3 $KEY
  echo "rsync ip=$IP quelle=$VERSION$QUELLE ziel=$ZIEL parameter=$PARAMETER zusatz=$ZUSATZ"
  SYNCQUELLE=""
  for f in $QUELLE ; do
    SYNCQUELLE="$SYNCQUELLE $USER@$IP:/srv/cs3/$VERSION/$f"
  done
  
  INSTSRC="Netzwerk Update"
  rsync $RSYNC $ZUSATZ $PARAMETER -e "/usr/bin/ssh -o StrictHostKeyChecking=no -i $KEY" $SYNCQUELLE $ZIEL
}

umount_usbupdate () {
  egrep -q "$BTRFS_MOUNTPOINT" /proc/mounts && umount -d $BTRFS_MOUNTPOINT
}

mount_usb () { # BTRFS_IMAGE ist entweder ein Wildcard, oder ein Dateiname der uebergeben wurde
  for BTRFS in $BTRFS_IMAGE ; do
    if [ -f "$BTRFS" ] ; then
      echo "mount $BTRFS"
      umount_usbupdate && echo "$BTRFS_MOUNTPOINT war noch gemountet"
      losetup -D
      if mount -o loop,ro "$BTRFS" $BTRFS_MOUNTPOINT ; then
        if [ -d $BTRFS_MOUNTPOINT/paket ] ; then     # Es darf kein falscher Update sein.
          INSTSRC="$BTRFS"
          return 0                                  # Rueckgabewert zeigt, dass der mount geklappt hat
        else
          echo "$BTRFS ist kein passendes Update-Image"
          umount -d $BTRFS_MOUNTPOINT
        fi
      fi
    else
      echo "kein Update-Image auf dem USB-Stick $BTRFS gefunden"
    fi
  done
  return -1
}

#--- bis hierher sind es grundlegende Funktionen, ab hier spezielle Funktionen

update_usb () {
  mount_usb || return -1
  # nur wenn eine Datei gemountet wurde
  usb_rsync
  ERG=$?
  sync
  umount_usbupdate
  return $ERG
}

update_ip () {
  ! [ -v IP ] && echo "Es ist keine IP gesetzt" && return -1
  echo "IP=$IP"
  ! eth0_bereit && echo "Es ist keine Netzwerkverbindung vorhanden" && return -1
  ssh_rsync
  ERG=$?
  sync
  return $ERG
}

update_dns () {
  echo -n "DNS-Update $DNS ="
  dns_2_ip &&
  echo "=> $IP" &&
  update_ip
}

update_all () {
  ERG=0
  if echo "$1"|egrep -o --color '/media/.*[.]btrfs' ; then
	BTRFS_IMAGE="$1"
	echo "USB-Update $BTRFS_IMAGE"
	update_usb && return
	ERG=$?
  elif echo "$1"|egrep --color '([0-9]{1,3}[.]){3}[0-9]{1,3}' ; then
    IP="$1"
    echo "IP-Update" $IP
    update_ip && return
    ERG=$?
  elif echo "$1"|egrep --color 'maerklin' ; then
    DNS="$1"
    update_dns && return
    ERG=$?
  else
    echo "AUTO-Update: USB-Versuch"
    update_usb && return
    echo "AUTO-Update: DNS-Versuch"
    DNS=$SERVER_DNS && update_dns && return
    echo "AUTO-Update: Extern-Versuch"
    IP=$SERVER_EXTERN && update_ip && return
    ERG=$?
    echo "keiner der Update-Versuche war erfolgreich"
  fi
  echo "IP=$IP : USB=$INSTSRC : DNS=$DNS ($1) $ERG"
  return $ERG
}
