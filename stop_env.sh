#!/usr/bin/env bash


# avvia Container

echo ":stopping"
echo -e ":Docker says..."
echo -e ":working in $PWD/../docker"
(cd $PWD/../docker/webapp; docker-compose down)
echo -e ":all done"




