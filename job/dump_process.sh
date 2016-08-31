#!/bin/bash
file="/home/smart/dump/DUMP.txt"
if [ -f "$file" ]
then
mysql selfcare_smart -e 'truncate table tmp_dump_data';
mysql selfcare_smart<<EOFMYSQL
LOAD DATA INFILE '/home/smart/dump/DUMP.txt' replace INTO TABLE tmp_dump_data FIELDS TERMINATED BY '|'  LINES TERMINATED BY '|\n'(msisdn,cust_id,acc_id,group_id,cust_name,status,industry,bill_medium,bill_statement_date,bill_due_date,registration_date,certificate,tin,agreement_number,cust_address,cust_contact,cust_email,pic,pic_email,pic_type,pic_mobile,pic_officeno,kam,kam_email,kam_officeno,kam_mobile,kam_role,kam_department);
#exec DUMP_DATA_PROCESSING;
EOFMYSQL
mysql selfcare_smart -e 'call DUMP_DATA_PROCESSING()';
#yesterday =`date -d " -1 day" +%Y%m%d`
#YESTERDAY= $(date +%d%m%Y -d "yesterday")
cd /home/smart/dump/
cp /home/smart/dump/DUMP.txt /home/smart/Backup/DUMP_`date +%d%m%Y -d "yesterday"`.txt
else
        echo "$file not found."
fi
