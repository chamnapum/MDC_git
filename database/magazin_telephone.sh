#!/bin/bash

 mysql -h localhost -u root -pvi8x0vgC magasin3_bdd < /root/public_ftp/$1.sql &> /root/public_ftp/logupdate$1.log

