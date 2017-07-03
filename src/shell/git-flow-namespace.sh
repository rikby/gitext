#!/usr/bin/env bash

set -o pipefail
set -o errexit
set -o nounset
#set -o xtrace

# Set magic variables for current file & dir
__dir="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
__file="${__dir}/$(basename "${BASH_SOURCE[0]}")"
readonly __dir __file

. ${__dir}/lib/common.sh

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

bash "$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd -P)"/command/flow-namespace/define.sh ${switch_branch}
