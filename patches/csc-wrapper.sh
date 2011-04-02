#!/bin/sh

# By Bill Shupp
# hostmaster@shupp.org
# 4/15/2006

CSCBINARY="/usr/local/bin/clamd-stream-client"
CLAMDHOST="192.168.1.1"
CLAMDPORT="3310"

for i in `find .` ; do
    if [ -f $i ] ; then
        CLAMDOUTPUT=`$CSCBINARY -f -d $CLAMDHOST -p $CLAMDPORT < $i`
        CSCRETURN="$?"
        if [ "$CSCRETURN" != "0" ] ; then
            exit 1
        fi
    fi
done
exit 0
