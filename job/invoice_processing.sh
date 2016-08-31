#!/bin/bash
## /var/www/html/selfcare/job/invoice_processing.sh
## PDF_BILL_201511  date +%Y-%m-%d
#*/1 * * * * echo `date` > /home/smart/test-crontab.txt
#redifine to previos mont for testing only: 201511
 
function log() {
	LOGFILE="invoice_processing.log"
	echo $(date +"%Y-%m-%d %T")": " $1 >> $LOGFILE
}
log "----------------------------------"
log "Start..."

CURR_MONTH=`date +%Y%m`
#CURR_MONTH="201511"
month_dir="invoice/PDF_BILL_${CURR_MONTH}"
#echo $month_dir

##First check if there is an "unrar" running already, if so, exit.
if ps -ef | grep -v grep | grep -v unrarall | grep unrar  
then
	log "Unrar is down!"
	exit 0
fi

if [ -d "$month_dir" -a ! -h "$month_dir" ] 
then
	
	for filename in "${month_dir}/"*.rar
	do
		log "Found $filename"
		log "START Unrar"
		unrar x -o- -r "$filename" "${month_dir}/" > /dev/null
		log "END Unrar"
	
	done

else
   log "Invoice directory ${month_dir} not found!"
fi
log "...Finish"
