REM UTF-8 support for mysql cli client
REM Place this file to \xampp\mysql\bin
REM Usage: my [mysql_user_name]

@echo off
chcp 65001
mysql --default-character-set=utf8 -u %1 -p
