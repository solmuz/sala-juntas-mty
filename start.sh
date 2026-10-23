#!/bin/sh
node /app/proxy.js &
sleep 1
nginx -g "daemon off;"
