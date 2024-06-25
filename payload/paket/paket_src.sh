#!/bin/bash
#
#  Copyright ©  2008 ... 2016 
#  Gebr. Märklin & Cie GmbH
#  Abteilung TEE
#
#  15.06.16 by Andreas Kielkopf
#
# Diese Datei enthaelt Funktionen die von anderen Paket-Scripten genutzt werden koennen
# Ausgabe des aktuellen Paketscriptes zum debuging
echo "------- paket -- ${0#*paket/} -------"

. update_src2.sh

kleiner () { #$1 erste Zahl in iec, $2 zweite Zahl in iec
	ZAHL1=$(LC_ALL=C numfmt --from=iec $1)
	ZAHL2=$(LC_ALL=C numfmt --from=iec $2)
	[ "$3" != "" ] && ZAHL3=$(LC_ALL=C numfmt --from=iec $3) && let ZAHL2+=$ZAHL3	 
	[ "$4" != "" ] && ZAHL4=$(LC_ALL=C numfmt --from=iec $4) && let ZAHL2+=$ZAHL4	 
	[ "$5" != "" ] && ZAHL5=$(LC_ALL=C numfmt --from=iec $5) && let ZAHL2+=$ZAHL5	 
	[ "$6" != "" ] && ZAHL6=$(LC_ALL=C numfmt --from=iec $6) && let ZAHL2+=$ZAHL6	 
	[ $ZAHL1 -le $ZAHL2 ]
	ERG=$?
	if [ $ERG == 0 ]
		then return 0
		else return -1
	fi
}

FREI=$(LC_ALL=C df --output=avail -h / | grep -v Avail)
SD_FREI=$(LC_ALL=C df --output=avail -h /media/sd-mmcblk1p1 | grep -v Avail)
