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
. ${__dir}/lib/git.sh

namespace="${1:-}"

has_flow_config

if [ -n "${namespace:-}" ]; then
  # Validate namespace
  # It should be already created
  has_branch_error "$(git config gitflow.prefix.feature)${namespace}" \
    "No such branch '$(git config gitflow.prefix.feature)${namespace}' to define GitFlow Root Feature."

  # Store config for recovery
  git config gitext.gitflow.branch.develop-default    $(git config gitflow.branch.develop)
  git config gitext.gitflow.prefix.feature-default    $(git config gitflow.prefix.feature)
  # Set git flow configuration
  git config gitflow.branch.develop    $(git config gitflow.prefix.feature)${namespace}
  git config gitflow.prefix.feature    $(git config gitflow.prefix.feature)${namespace}-
  echo
  echo -e "  GitFlow Root Feature = ${t_color_yellow}${namespace}${t_default}"
elif [ -n "$(git config gitext.gitflow.branch.develop-default)" ]; then
  # Set git flow configuration
  git config gitflow.branch.develop    $(git config gitext.gitflow.branch.develop-default)
  git config gitflow.prefix.feature    $(git config gitext.gitflow.prefix.feature-default)
  git config --remove-section gitext.gitflow.branch
  git config --remove-section gitext.gitflow.prefix
  echo
  echo -e "  GitFlow Root Feature has been ${t_color_red}removed${t_default}."
else
  check_error 2 'GitFlow Root Feature is not set.'
fi
