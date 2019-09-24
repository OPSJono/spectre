#!/bin/sh
#@description bash script to run UnitTests

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

  This script runs /vagrant/vendor/bin/phpunit, for a specified target <environment>

  OPTIONS:
    -h      Show this message
    -e      Environment name (customer alias) for the tests (required)
    -f      File or Folder of tests to run (default is: /vagrant/app/tests/)
EOF
}

environment=
folder="/vagrant/app/tests/"
## Read options from the command line
while getopts "he:f:" OPTION
do
  case ${OPTION} in
    h)
      usage
      exit 1
      ;;
    e)
      environment=$OPTARG
      ;;
    f)
      folder=$OPTARG
      ;;
    ?)
      usage
      exit 1
      ;;
  esac
done


## error if an environment hasn't been specified
if [ -z "$environment" ]; then
   echoerr -e "${col_red}ERROR${col_default}: Please specify which environment to run the tests for!"
   exit 1
fi

echo "Running PHPUnit tests for: ${environment}"
echo "Tests to run: ${folder}"
echo ""

ENV_ALIAS=${environment} /vagrant/vendor/bin/phpunit ${folder}