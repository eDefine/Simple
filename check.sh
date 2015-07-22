#!/bin/bash

RED='\033[1;31m'
GREEN='\033[1;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

applications=(crudini)

for application in ${applications[*]}; do
    printf "Checking for ${YELLOW}$application${NC} "
    if type "$application" > /dev/null 2>&1; then
        printf "[${GREEN}FOUND${NC}]\n"
    else
        printf "[${RED}NOT FOUND${NC}]\n"
    fi
done