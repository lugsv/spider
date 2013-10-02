#!/bin/bash

MYLOG=$1
AIRPORT_N=$2
DATA_P=$3
DATA_A=$4
ORA_P=$5
MIN_P=$6
ORA_A=$7
MIN_A=$8
WEB_FILE=$9
PRODUCT=${10}

# Define MYURI
if [[ "$PRODUCT" == "P" ]]
then
  DOMAIN="parkingaeroporto"
fi

if [[ "$PRODUCT" == "T" ]]
then
  DOMAIN="parkingstazione"
fi

if [[ "$PRODUCT" == "M" ]]
then
  DOMAIN="parkingporto"
fi

MYURI="http://www.${DOMAIN}.it/book/engine.aspx?product=$PRODUCT&airport=$AIRPORT_N&departuredate=$DATA_P&returndate=$DATA_A&departurehours=$ORA_P&departureminutes=$MIN_P&returnhours=$ORA_A&returnminutes=$MIN_A"

# Define temporary log folder
TEMPDIR=/tmp/

CURL=/usr/bin/curl
CAT=/bin/cat
CP=/bin/cp
ECHO=/bin/echo
TR=/usr/bin/tr

# Office
WGET=/usr/bin/wget
GREP=/bin/grep
SED=/bin/sed
TOUCH=/bin/touch

# Home
#WGET=/opt/local/bin/wget
#GREP=/usr/bin/grep
#SED=/usr/bin/sed
#TOUCH=/usr/bin/touch

# Define temporary file
AIR_LOG=$TEMPDIR${AIRPORT_N}.log

# First URL request to get encoded URL
PERL_URL=`$CURL -sS $MYURI | $SED -n -e 's/.*<h2>\(.*\)<\/h2>.*/\1/p' | $SED 's/^.*<a href="//' | $SED 's/".*$//'`

# Define MYCLOG log for curl
MYCLOG=$TEMPDIR$MYLOG

# Check if MYCLOG exists
if [ -f $MYCLOG ]
then
  $CP /dev/null $MYCLOG
else
  $TOUCH $MYCLOG  
fi

#$ECHO $PERL_URL > /tmp/resut.flag

# Pass to perl encoded URL
./urlParse.pl "$PERL_URL" $MYCLOG 

# Process $MYCLOG and extract Parking name and Price
MYRES=`$CAT $MYCLOG | $SED -e 's/^[ \t]*//' | $TR -d '\r' | $GREP -v "^$"`

# Create the first temporary log
$ECHO $MYRES > $AIR_LOG 

# Check if $WEB_FILE exists
if [ -f $WEB_FILE ]
then
  $CP /dev/null $WEB_FILE
else
  $TOUCH $WEB_FILE  
fi

# Set date NOW
NOW=$(date +"%d-%m-%Y - %H:%M:%S")

$ECHO "<div style=\"background:#eeeeee\">" >> $WEB_FILE
$ECHO "Ricerca eseguita il <strong>$NOW</strong><br />" >> $WEB_FILE

if [[ "$PRODUCT" == "P" ]]
then
  $ECHO "Codice aeroporto: <strong>$AIRPORT_N</strong><br />" >> $WEB_FILE
fi

if [[ "$PRODUCT" == "T" ]]
then
  $ECHO "Codice stazione: <strong>$AIRPORT_N</strong><br />" >> $WEB_FILE
fi

$ECHO "Data partenza <strong>$DATA_P</strong> alle <strong>$ORA_P:$MIN_P</strong> - Data ritiro <strong>$DATA_A</strong> alle <strong>$ORA_A:$MIN_A</strong>" >> $WEB_FILE
$ECHO "<br /><br />" >> $WEB_FILE

# Insert parsed data to the web file for the current airport
$CAT $AIR_LOG | $SED -e 's/^[ \t]*//' | $TR -d '\r' | $GREP -v "^$" | $SED -e 's/&nbsp;//g' | $SED 's/title=\"PRENOTI\"//g' | $SED -e 's/value=\"PRENOTI\ >"//g' | $SED -e 's/  \/>/>/g' | $SED -e 's/<[a-zA-Z\/][^>]*>//g' | $SED -e 's/^[[:space:]]*//' -e 's/[[:space:]]*$//' | $SED -e 's/Prenotazione Inclusa, Importo Totale:  /\n/g' | $SED -e 's/Non /\n/g' | $SED -e 's/disponibile/N\/A/g' | $SED '/^$/d' | $SED -e 's/[[:space:]]*$//' | $SED 's/$/<br \/>/g' >> $WEB_FILE

$ECHO "</div>" >> $WEB_FILE
