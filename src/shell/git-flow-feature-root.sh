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

if [ -z "$(git config gitflow.branch.develop)" ]; then
  check_error 2 'It looks like GitFlow is not initiated. Please use command '\
"\n    git flow init"
fi

if [ -n "${namespace:-}" ]; then
  # Validate namespace
  # It should be already created
  has_branch_error "feature/${namespace}" \
    "No such branch 'feature/${namespace}' to define GitFlow Root Feature."

  # Store config for recovery
  git config gitext.gitflow.branch.develop-default    $(git config gitflow.branch.develop)
  git config gitext.gitflow.branch.feature-default    $(git config gitflow.branch.feature)
  # Set git flow configuration
  git config gitflow.branch.develop    feature/${namespace}
  git config gitflow.prefix.feature    feature/${namespace}-
  echo
  echo -e "  GitFlow Root Feature = ${t_color_yellow}${namespace}${t_default}"
elif [ -n "$(git config gitext.gitflow.branch.develop-default)" ]; then
  # Set git flow configuration
  git config gitflow.branch.develop    $(git config gitext.gitflow.branch.develop-default)
  git config gitflow.branch.feature    $(git config gitext.gitflow.branch.feature-default)
  echo
  echo -e "  GitFlow Root Feature has been ${t_color_red}removed${t_default}."
else
  check_error 2 'GitFlow Root Feature was not initiated. Nothing to rollback.'
fi
