#!/usr/bin/env bash

# Description: Set root feature branch. On "feature finish" your sub-feature branch will be merge into the root one.

set -o pipefail
set -o errexit
set -o nounset
#set -o xtrace

# Set magic variables for current file & dir
__dir="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
__file="${__dir}/$(basename "${BASH_SOURCE[0]}")"
readonly __dir __file

. ${__dir}/lib/common.sh

namespace="${1:-}"

if [ -n "${namespace:-}" ]; then
  # Validate namespace
  # It should be already created
  if ! git branch | tr '*' ' ' \
      | sed 's/^ *//;s/ *$//' | grep "feature/${namespace}"; then
    check_error 2 "No such branch 'feature/${namespace}' to define GitFlow Root Feature."
  fi

  # Set git flow configuration
  git config gitflow.branch.develop    feature/${namespace}
  git config gitflow.prefix.feature    feature/${namespace}-
  echo
  echo -e "  GitFlow Root Feature = ${t_color_yellow}${namespace}${t_default}"
else
  # Set git flow configuration
  git config gitflow.branch.develop    develop
  git config gitflow.prefix.feature    feature/
  echo
  echo -e "  GitFlow Root Feature has been ${t_color_red}removed${t_default}."
fi
