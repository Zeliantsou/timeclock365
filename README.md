# Timelock365 / Test task

## Prerequisites

To build project and start development on host machine must be installed Linux (recommended) or MacOS and some required software:
latest version of `Docker (version 18.06.0+)` [https://docs.docker.com/install/]
and `docker-compose` [https://docs.docker.com/compose/compose-file/],
`Git`, `Java 8` (to run Gradle tasks) and `PHP 8.1+`.

## Installation

You should run commands below to install application correctly:

        ./gradlew build

Next you can check services status:

        docker-compose ps

Default port mapping (see `.env` file):

        Name                             Command                     State                                      Ports                  
    ------------------------------------------------------------------------------------------------------------------------------------------
    test_app_1                       /entrypoint.sh                   Up             127.0.0.1:8083->443/tcp, 127.0.0.1:8080->80/tcp, 9000/tcp
    test_db_1                        /entrypoint.sh mysqld            Up (healthy)   127.0.0.1:8306->3306/tcp, 33060/tcp, 33061/tcp
    test_phpmyadmin_1                /docker-entrypoint.sh apac ...   Up             127.0.0.1:8090->80/tcp