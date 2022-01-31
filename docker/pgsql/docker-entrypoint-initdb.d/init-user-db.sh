#!/bin/bash

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "$POSTGRES_DB" <<-EOSQL
    CREATE USER testuser WITH ENCRYPTED PASSWORD 'testpass';
    CREATE DATABASE testapp;
    GRANT ALL PRIVILEGES ON DATABASE testapp TO testuser;
EOSQL
