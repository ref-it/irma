# Installation Guide

## Requirements
* php
  * ext-ldap
  * ext-pdo 
* openldap server (see below)
* mysql
* composer
* npm
* webserver which is .htaccess compatible (apache) -> default laravel stuff 

## LDAP-Requirements
* you need the following ./configure flags during compilation, if you compile openldap from scratch you can use the 
following compile script for LTS LDAP 2.5.X
* otp,  sssvlv and ppolicy are there for future use
* tls, argon2 and dynlist are needed 
```bash
#!/bin/bash

set -e #stop on error
set -x #prints commands

cd openldap-2.5.15/

./configure \
--with-tls=yes \
--enable-modules --enable-ppolicy --enable-otp --enable-argon2 --enable-sssvlv --enable-dynlist

make depend
make
make install
```


