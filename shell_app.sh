#!/bin/sh

# Please NOTE: This script will auto-connect to the given container once it is running; there is NO need to use `sleep`

CONTAINER='admin_app'

command -v sudo > /dev/null 2>&1
HAS_SUDO=$?

[ ${EUID} == 0 ] || [ ${HAS_SUDO} != 0 ] &&
    SUDO=false ||
    SUDO=true

running_status() {
    $SUDO &&
        OUTPUT=$(sudo docker ps -aqf "name=${CONTAINER}" -f 'status=running') ||
        OUTPUT=$(docker ps -aqf "name=${CONTAINER}" -f 'status=running')

    [ -z "${OUTPUT}" ] && echo 0 || echo 1
}

while [ $(running_status) -eq 0 ]; do
    sleep 1s
done

$SUDO &&
    sudo docker exec -i -t $(sudo docker ps -aqf "name=${CONTAINER}") bash ||
    docker exec -i -t $(docker ps -aqf "name=${CONTAINER}") bash
