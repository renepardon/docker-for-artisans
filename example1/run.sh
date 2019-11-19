#!/usr/bin/env bash

docker run -it -d --publish 8080:80 --name webserver nginx

open http://localhost:8080