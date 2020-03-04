# Admin [Docker]

This repository is meant to act as a guide for setting up the Docker development environment for **Admin**

## Pre-requisites

### 1. System requirements

- Git >= 2.13
- [Docker](https://docs.docker.com/engine/installation/) >= 17.10
- [Docker Compose](https://docs.docker.com/compose/install) >= 1.16

Platform specific Docker guides:

- **Mac** users: [Get started with Docker for Mac](https://docs.docker.com/docker-for-mac/)
- **Windows** users: [Get started with Docker for Windows](https://docs.docker.com/docker-for-windows/)

##### For Ansible provisioning:

- Python 3.5
- Ansible >= 2.4.0.0

### 2. Setup local hosts

Add the following block to your `hosts` file:

```
### Docker: Admin
127.0.0.1 admin.local
127.0.0.1 www.admin.local
```

Hosts file paths:

| OS          | Path                                    |
|-------------|-----------------------------------------|
| Linux / Mac | `/etc/hosts`                            |
| Android     | `/system/etc/hosts`                     |
| Windows     | `C:\Windows\System32\Drivers\etc\hosts` |

Other uses:

- To access Docker hosts via **LAN** - replace `127.0.0.1` with the LAN address of your serving machine and include the host entries on your secondary machine(s).
    - It helps to add a *Static DHCP* entry on your router to prevent the need to re-edit the hosts file each time the internal network allocated IP changes.
    - This can be used in conjunction with a _VPN_ to allow access to the Docker hosts while away. One example might be offloading Docker to a more powerful workstation while work is being done on a lighter and/or weaker device.

### 3. Cloning this repository and attached projects
```bash
# Recursive clone to init git submodules as well
$ git clone --recursive git@github.com:FLYGOPROJECT/admin-docker.git

# Since initialized submodules are linked to a specific commit
# we need to bring them up-to-date on the master branch
$ cd admin-docker/admin-app && git checkout master && git pull
```

### 4. Pulling production database

>_TODO once a production version is live_

## Common development tasks

### 1. Container operations

```bash
# Create and start containers
$ docker-compose up

# Stop containers running in detached mode
$ docker-compose stop

# Stop and destroy containers
$ docker-compose down

# (Re)build the image for a certain container
$ docker-compose build container-name-here
```

> **Note:** Add the option `-d` to start containers in detached mode (Run containers in the background). e.g. `docker-compose up -d`

### 2. Open a shell on a container

```bash
# Shortcut access to App container
$ sh shell_app.sh

# Traditional method
$ docker exec -i -t $(docker ps -aqf "name=admin_app") bash
```

### 3. Docker Sync

Docker Sync can be run like so:

```bash
# Running Docker Sync alongside Docker Compose
$ docker-sync start
$ docker-compose up

# A shortcut for the above commands
$ docker-sync-stack start
```

## Ansible provisioning

> TODO!
