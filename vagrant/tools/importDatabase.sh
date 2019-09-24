#!/bin/sh
#@description php artisan command script for ansible to call after a deployment

echoerr() { echo "$@" 1>&2; }

if [ -t 1 ]; then
    col_default="\e[39m"
    col_green="\e[38;5;82m"
    col_bold="\e[1m"
    col_bold_reset="\e[21m"
    col_red="\e[38;5;198m"
fi


usage()
{
cat << EOF
  usage: $0 options

  This script imports a .sql.gz file to a database in MySql, for a specified target <environment>

  OPTIONS:
    -h      Show this message
    -e      Target database name (customer alias) for the import (required)
    -d      Target database date for the import (optional)
    -f      The name of the database file to import (optional)
EOF
}

date=`date '+%Y-%m-%d'`
## Read options from the command line
while getopts "he:f:d:" OPTION
do
  case ${OPTION} in
    h)
      usage
      exit 1
      ;;
    e)
      database=$OPTARG
      ;;
    f)
      file=$OPTARG
      ;;
    d)
      date=$OPTARG
      ;;
    ?)
      usage
      exit 1
      ;;
  esac
done


## error if an environment hasn't been specified
if [ -z "$database" ]; then
   echoerr -e "${col_red}ERROR${col_default}: Please specify which database to import to!"
   exit 1
fi

## error if an environment hasn't been specified
if [ -z "$file" ]; then
   file=${database}-${date}.sql.gz
fi

time1=$(date +%s)

echo "Dropping and recreating Database: ${database}"

recreate="DROP DATABASE IF EXISTS \`${database}\`; CREATE DATABASE \`${database}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

echo ${recreate} | mysql -u root -preverse >> /dev/null 2>&1

dbsize=`gzip -l /vagrant/ad-hoc/${file} | sed -n 2p | awk '{print $2}'`

zcat /vagrant/vagrant/tools/files/_before-import.sql.gz \
          /vagrant/ad-hoc/${file} \
          /vagrant/vagrant/tools/files/_after-import.sql.gz \
           | pv --size ${dbsize} --name "Importing file: ${file} to database: ${database}..." | mysql -u root -preverse ${database} 2>/dev/null

time2=$(date +%s)

dt=$((time2-time1))
dd=$((dt/86400))
dt2=$((dt-86400*dd))
dh=$((dt2/3600))
dt3=$((dt2-3600*dh))
dm=$((dt3/60))
ds=$((dt3-60*dm))

echo "Finished importing!"
printf "Total runtime: %02d:%02d:%02.0f\n" ${dh} ${dm} ${ds}