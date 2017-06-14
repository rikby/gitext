#!/usr/bin/env bash

branch=$(git rev-parse --abbrev-ref HEAD)

if [ -z "${branch}" ] || [ "${branch}" == 'HEAD' ]; then
  # disable parsing asterisks, or set -f
  set -o noglob

  # get current branch
  branch=$(git branch | grep '*' | grep -Eo '[A-z]+/[^)]+')

  # enable parsing asterisks
  set +o noglob

  # Trim remote name
  branch=$(echo "${branch}" | sed -r 's:^'$(git remote | xargs echo | tr ' ' '|')'::' | sed -r 's:^/::')
fi

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
