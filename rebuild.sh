#!/bin/bash

cd "$(dirname "$0")"

while getopts "i" opt
do
    case "$opt" in
        "i") install=true;;
    esac
done

if [ ! -f config.ini ]; then
    cp config.ini.dist config.ini
    nano config.ini
fi

# Loading parameters from config.ini
database_name=$(crudini --get config.ini database name)
database_user=$(crudini --get config.ini database user)
database_password=$(crudini --get config.ini database password)
system_password=$(crudini --get config.ini system password)

if [ $install ]; then
    echo "Granting permissions"
    echo $system_password | sudo -S -p "" chmod 777 -R log/ tmp/ public/uploads/

    echo "Installing vendors"
    composer install
fi

echo "Loading initial database"
mysql -u $database_user -p$database_password $database_name < sql/init.sql

echo "Loading fixtures"
php bin/console.php fixtures:load
