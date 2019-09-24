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

runCommandForMigrationFolders()
{
    command=$1;

    folder=data-migrations

    if [ ${command} = "migrate" ]; then
        folder=migrations
    fi

    echo "Running ${command} for path: app/database/${folder}/baseline"
    ENV_ALIAS=${environment} LARAVEL_ENVIRONMENT=${server_env} INNOVED_LARAVEL_PATH=${innoved_laravel_path} php artisan ${command} --path=app/database/${folder}/baseline --database=mysql

    echo "Running ${command} for path: app/database/${folder}/applied"
    ENV_ALIAS=${environment} LARAVEL_ENVIRONMENT=${server_env} INNOVED_LARAVEL_PATH=${innoved_laravel_path} php artisan ${command} --path=app/database/${folder}/applied --database=mysql

    for j in "*.*.*" "*ems*" "*EMS*" "ccf" "cpd";
        do for i in `ls -1d app/database/${folder}/${j}`;
            do echo "Running ${command} for path: $i..." && ENV_ALIAS=${environment} LARAVEL_ENVIRONMENT=${server_env} INNOVED_LARAVEL_PATH=${innoved_laravel_path} php artisan ${command} --path=${i} --database=mysql;
        done;
    done

    echo "Running ${command} for path: app/database/${folder}"
    ENV_ALIAS=${environment} LARAVEL_ENVIRONMENT=${server_env} INNOVED_LARAVEL_PATH=${innoved_laravel_path} php artisan ${command} --path=app/database/${folder} --database=mysql
}


usage()
{
cat << EOF
  usage: $0 options

  This script runs a php artisan <command>, for a specified target <environment>

  OPTIONS:
    -h      Show this message
    -e      Target environment (customer alias) for the command (required)
    -c      The artisan command to run (required)
EOF
}


## Read options from the command line
environment=
while getopts "he:c:b:" OPTION
do
  case ${OPTION} in
    h)
      usage
      exit 1
      ;;
    e)
      environment=$OPTARG
      ;;
    c)
      command=$OPTARG
      ;;
    ?)
      usage
      exit 1
      ;;
  esac
done


## error if an environment hasn't been specified
if [ -z "$environment" ]; then
   echoerr -e "${col_red}ERROR${col_default}: Please specify which environment is to be used!"
   exit 1
fi

## error if an environment hasn't been specified
if [ -z "$command" ]; then
   echoerr -e "${col_red}ERROR${col_default}: Please specify which command to run!"
   exit 1
fi

server_env=development
innoved_laravel_path=${INNOVED_LARAVEL_PATH}

cd ${innoved_laravel_path}

echo "Running command: ${command} for ${environment}"

# Commands which will run through migration folders
if [ "${command}" = "migrate" ]; then
    runCommandForMigrationFolders "${command}"
elif [ "${command}" = "innoved:data-migrate" ]; then
    runCommandForMigrationFolders "${command}"
else
    ENV_ALIAS=${environment} LARAVEL_ENVIRONMENT=${server_env} INNOVED_LARAVEL_PATH=${innoved_laravel_path} php artisan ${command}
fi


echo "Command '${command}' completed for ${environment}"