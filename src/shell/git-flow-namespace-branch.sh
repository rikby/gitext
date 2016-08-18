#!/usr/bin/env bash

branch=$(git rev-parse --abbrev-ref HEAD)

if [[ ${branch} =~ \/ ]]; then
    IFS='/' read -ra branch <<< "${branch}"
    for i in "${branch[@]}"; do
        current_dir="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd -P)"
        bash ${current_dir}/git-flow-namespace.sh ${i}
        break
    done
fi

