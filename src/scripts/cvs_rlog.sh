#!/bin/bash
cd /var/www/
TMP=cvs_new.log
export CVSROOT=/opt/data/iief
echo "Start cvs log..."
cvs rlog crb > $TMP
cvs rlog solo >> $TMP
cvs rlog esb >> $TMP
if [ -s $TMP ]
  then
   mv $TMP /var/www/temp/cvs.log
fi
echo "End cvs log"
