#!/bin/bash

do_commit=true
commitmsg="";

while getopts ":um:l" opt; do
  case $opt in
    u)
      # Update Mustache
        composer update

		php vendor/mustache/mustache/bin/build_bootstrap.php
		cp vendor/mustache/mustache/mustache.php src

      ;;
    m)
      commitmsg=$OPTARG
      ;;
    l)
      # Local Only
      do_commit=false
      ;;
    \?)
      echo "Invalid option: -$OPTARG" >&2
      exit 1
      ;;
    :)
      echo "Option -$OPTARG requires an argument." >&2
      exit 1
      ;;
  esac
done

versiony package.json --patch
grunt

if [[ $do_commit ]]; then

	git add .

	if [[ ! -z "$commitmsg" ]]; then git commit -m "$1"; else git commit; fi;

	git push

fi