#!/usr/bin/env bash

set -e
set -x

CURRENT_BRANCH="7.x"

function split()
{
    if [[ `uname  -a` =~ 'Darwin' ]];then
        SHA1=`./bin/splitsh-lite-darwin --prefix=$1`
        git push $2 "$SHA1:refs/heads/$CURRENT_BRANCH" -f
    else
        SHA1=`./bin/splitsh-lite --prefix=$1`
        git push $2 "$SHA1:refs/heads/$CURRENT_BRANCH" -f
    fi
}

function remote()
{
    git remote add $1 $2 || true
}

git pull origin $CURRENT_BRANCH

remote auth git@github.com:royalcms/royalcms-auth.git
remote broadcasting git@github.com:royalcms/royalcms-broadcasting.git
remote bus git@github.com:royalcms/royalcms-bus.git
remote cache git@github.com:royalcms/royalcms-cache.git
remote class-loader git@github.com:royalcms/royalcms-class-loader.git
remote config git@github.com:royalcms/royalcms-config.git
remote console git@github.com:royalcms/royalcms-console.git
remote container git@github.com:royalcms/royalcms-container.git
remote contracts git@github.com:royalcms/royalcms-contracts.git
remote cookie git@github.com:royalcms/royalcms-cookie.git
remote database git@github.com:royalcms/royalcms-database.git
remote encryption git@github.com:royalcms/royalcms-encryption.git
remote events git@github.com:royalcms/royalcms-events.git
remote exception git@github.com:royalcms/royalcms-exception.git
remote filesystem git@github.com:royalcms/royalcms-filesystem.git
remote hashing git@github.com:royalcms/royalcms-hashing.git
remote http git@github.com:royalcms/royalcms-http.git
remote log git@github.com:royalcms/royalcms-log.git
remote mail git@github.com:royalcms/royalcms-mail.git
remote notifications git@github.com:royalcms/royalcms-notifications.git
remote pagination git@github.com:royalcms/royalcms-pagination.git
remote pipeline git@github.com:royalcms/royalcms-pipeline.git
remote preloader git@github.com:royalcms/royalcms-preloader.git
remote queue git@github.com:royalcms/royalcms-queue.git
remote redis git@github.com:royalcms/royalcms-redis.git
remote routing git@github.com:royalcms/royalcms-routing.git
remote session git@github.com:royalcms/royalcms-session.git
remote support git@github.com:royalcms/royalcms-support.git
remote translation git@github.com:royalcms/royalcms-translation.git
remote validation git@github.com:royalcms/royalcms-validation.git
remote view git@github.com:royalcms/royalcms-view.git

split 'src/Royalcms/Component/Auth' auth
split 'src/Royalcms/Component/Broadcasting' broadcasting
split 'src/Royalcms/Component/Bus' bus
split 'src/Royalcms/Component/Cache' cache
split 'src/Royalcms/Component/ClassLoader' class-loader
split 'src/Royalcms/Component/Config' config
split 'src/Royalcms/Component/Console' console
split 'src/Royalcms/Component/Container' container
split 'src/Royalcms/Component/Contracts' contracts
split 'src/Royalcms/Component/Cookie' cookie
split 'src/Royalcms/Component/Database' database
split 'src/Royalcms/Component/Encryption' encryption
split 'src/Royalcms/Component/Events' events
split 'src/Royalcms/Component/exception' exception
split 'src/Royalcms/Component/Filesystem' filesystem
split 'src/Royalcms/Component/Hashing' hashing
split 'src/Royalcms/Component/Http' http
split 'src/Royalcms/Component/Log' log
split 'src/Royalcms/Component/Mail' mail
split 'src/Royalcms/Component/Notifications' notifications
split 'src/Royalcms/Component/Pagination' pagination
split 'src/Royalcms/Component/Pipeline' pipeline
split 'src/Royalcms/Component/Preloader' preloader
split 'src/Royalcms/Component/Queue' queue
split 'src/Royalcms/Component/Redis' redis
split 'src/Royalcms/Component/Routing' routing
split 'src/Royalcms/Component/Session' session
split 'src/Royalcms/Component/Support' support
split 'src/Royalcms/Component/Translation' translation
split 'src/Royalcms/Component/Validation' validation
split 'src/Royalcms/Component/View' view