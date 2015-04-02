#!/usr/bin/env bash

export REPO_URL="https://github.com/umple-ucosp/umpr.data.git"
export REPO_PATH="./public/data/umpr_repos/"

export REPO_REMOTE="origin"
export REPO_BRANCH="master"


if [ ! -d "${REPO_PATH}/.git" ]; then
    # If the repository hasn't been cloned; clone it.
    git clone ${REPO_URL} ${REPO_PATH}

else
    # update from master
    cd ${REPO_PATH}

    git pull ${REPO_REMOTE} ${REPO_BRANCH}
fi



