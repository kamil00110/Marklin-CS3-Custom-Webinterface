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
echo "-- update aller pakete -- ${0#*paket/} -----------------------------";
. paket_src.sh

for D in *;do [ -d $D ] && ./$D/pre-install.cmd && ./$D/install.cmd && ./$D/post-install.cmd ;done
