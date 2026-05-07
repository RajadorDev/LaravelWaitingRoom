#!/bin/bash

while true
do 
    php artisan schedule:run
    sleep 1
done