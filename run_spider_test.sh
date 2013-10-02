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

# Define MYURI
MYURI="http://www.parkingaeroporto.it/book/engine.aspx?product=P&airport=$AIRPORT_N&departuredate=$DATA_P&returndate=$DATA_A&departurehours=$ORA_P&departureminutes=$MIN_P&returnhours=$ORA_A&returnminutes=$MIN_A"

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
$ECHO "Ricerca eseguita il <strong>$NOW</strong>" >> $WEB_FILE
$ECHO "<br /><br />" >> $WEB_FILE

# Insert parsed data to the web file for the current airport
$CAT $AIR_LOG | sed -e 's/^[ \t]*//' | tr -d '\r' | grep -v "^$" | sed -e 's/&nbsp;//g' | sed 's/title=\"PRENOTI\"//g' | sed -e 's/value=\"PRENOTI\ >"//g' | sed -e 's/  \/>/>/g' | sed -e 's/<[a-zA-Z\/][^>]*>//g' | sed -e 's/^[[:space:]]*//' -e 's/[[:space:]]*$//' | sed -e 's/Prenotazione Inclusa, Importo Totale:  /\n/g' | sed -e 's/Non /\n/g' | sed -e 's/disponibile/N\/A/g' | sed '/^$/d' | sed -e 's/[[:space:]]*$//' | sed 's/$/<br \/>/g' >> $WEB_FILE

$ECHO "</div>" >> $WEB_FILE 
