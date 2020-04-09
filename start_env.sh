#!/usr/bin/env bash


function startDocker() {

    echo ":starting Docker"

    open /Applications/Docker.app

    sleep 1

    local out=":waiting for Docker..."

    until docker ps > /dev/null 2>&1;
    do
        echo -ne "$out\r"
        out+="."
        sleep 2;
    done

    echo -e ":starting Docker.....DONE"

}

# avvia Docker
startDocker

# avvia Container
echo -e ":Docker says..."
echo -e ":working in $PWD/../docker"
(cd $PWD/../docker/webapp; docker-compose up -d)
open -a "/Applications/Google Chrome.app" 'http://localhost:8000/'
php artisan serve
echo -e ":all done"