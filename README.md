## Introduction

This README describes how to setup the development environment.
These instructions address the development with a local environment, i.e. on the machine (that can be a VM) **without using a Docker container for PHP or Laravel**.
Containers are used for PostgreSQL and pgAdmin, though.

The template was prepared to run on Ubuntu 21.10, but it should be fairly easy to follow and adapt for other operating systems.

## Installing the Software Dependencies

To prepare you computer for development you need to install some software, namely PHP and the PHP package manager Composer.

We recommend using an __Ubuntu__ distribution that ships PHP 8.0 (e.g Ubuntu 21.10). You may install the required software with:

```bash
sudo apt install git composer php8.0 php8.0-mbstring php8.0-xml php8.0-pgsql
```


The following links provide instructions for installing [Docker](https://docs.docker.com/get-docker/) and [Docker Compose](https://docs.docker.com/compose/install/)

## Installing local PHP dependencies

After the steps above you will have updated your repository with the required Laravel structure from this repository.
Afterwards, the command bellow will install all local dependencies, required for development.

```bash
composer install
```

Navigate on your browser to http://localhost:4321 to access pgAdmin4 and manage your database. Depending on your installation setup, you might need to use the IP address from the virtual machine providing docker instead of localhost. Please refer to your installation documentation.
Use the following credentials to login:

    Email: postgres@lbaw.com
    Password: pg!password

On the first usage you will need to add the connection to the database using the following attributes:

    hostname: postgres
    username: postgres
    password: pg!password

Hostname is _postgres_ instead of _localhost_ since _Docker Compose_ creates an internal DNS entry to facilitate the connection between linked containers.

Access http://localhost:8000 to access the app. Username is `admin@example.com`, and password `1234`. These credentials are copied to the database on the first instruction above.

Technical diagrams, user stories, openapi specification and other information regarding the development process can be found in the following files:
- [lbaw2222_eap.pdf](lbaw2222_eap.pdf)
- [lbaw2222_ebd.pdf](lbaw2222_ebd.pdf)
- [lbaw2222_er.pdf](lbaw2222_er.pdf)
- [lbaw2222_pa.pdf](lbaw2222_pa.pdf)
  
A visual overview of the project and it's functionality can be observed in this [link to a youtube presentation](https://youtu.be/-hjULtfdjc4)
