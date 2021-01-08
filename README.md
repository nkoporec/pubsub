# About
A simple Pub/Sub example written in Lumen Framework and using Redis.

It consist of three parts:
  - Tracking system
  - Pub/sub service
  - CLI app

## Tracking system
<p>And endpoint is located at `BASE_URL/accountId?data=”<data>` that accepts an account ID. If the account exists it will check if it is active and then publish Account ID, Account Name and name query parameter to Redis channel</p>

To start publishing to a channel, use API endpoint (POST).
`BASE_URL/accountId?data=”<data>`

## Pub/Sub
<p>It's using a built-in lumen Redis commands to interact with the Redis</p>

## Cli App
<p>It's a Lumen artisan command, than accepts an optional account_id parameter. The parameter will filter messages so it's only displaying it for the required account id.</p>
<p> The command will connect to Redis and listen to messages on a predefined channel</p>

To start listening, use <br>
`make track`

You can filter by account id, by using command: <br>
`make track ACCOUNT_ID=`

# Requirements
 - Docker
 - Docker-compose

# Install

1. Clone repository
2. Run `make install`

This will build the docker containers and seed the database.

# Tests
Run `make test`

