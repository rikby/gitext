#!/usr/bin/env bash

branch=$(git rev-parse --abbrev-ref HEAD)
switch_branch=''
if [[ ${branch} =~ \/ ]]; then
    IFS='/' read -ra branch <<< "${branch}"
    for i in "${branch[@]}"; do
        switch_branch=${i}
        break
    done
fi

current_dir="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd -P)"
bash ${current_dir}/git-flow-namespace.sh ${switch_branch}
