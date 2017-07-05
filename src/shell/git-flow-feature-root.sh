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

root_feature="${1:-}"

# Validate git-flow configuration
has_flow_config

config_active='gitext.gitflow.feature-root.active'
config_lock='gitext.gitflow.feature-root.lock'

show_help() {
  cat <<EOF
"Root Feature"
It can be used for using your "super" issue key in GitFlow.
When you can start feature for a sub-task completely using git-flow.
In this case "develop" is a "super" issue branch instead of "right" develop.
So, there are two modes:
  DEFAULT
    feature prefix = feature/
    develop branch = develop
  ROOT FEATURE
    feature prefix = feature/super-
    develop branch = feature/super

Here are commands for GitFlow "Root Feature"
  $ git flow-feature-root ROOT_FEATURE
    Further git flow "feature" commands will use this root feature.
    It will set root feature as "develop" and "feature prefix" as "feature/root feature-".
    Feature prefix will be taken from your current configuration.

  $ git flow-feature-root show
    Show current "root feature".

  $ git flow-feature-root
    Remove current "root feature".

  $ git flow-feature-root help
    Show this help.

  $ git config ${config_lock} true
    Set lock for an active root feature.

  $ git config --unset ${config_lock}
    Unset lock for an active root feature (default behavior).
EOF
}

is_root_feature_set?() {
  git config ${config_active} > /dev/null
}

feature_prefix() {
  git config gitflow.prefix.feature
}

unset_root_feature() {
  # Set git flow configuration
  if git config gitext.gitflow.branch.develop-default > /dev/null; then
    git config gitflow.branch.develop    $(git config gitext.gitflow.branch.develop-default)
  fi
  if git config gitext.gitflow.prefix.feature-default > /dev/null; then
    git config gitflow.prefix.feature    $(git config gitext.gitflow.prefix.feature-default)
  fi

  git config --remove-section gitext.gitflow.branch 2> /dev/null || true
  git config --remove-section gitflow.prefix.feature 2> /dev/null || true

  # remove stored root_feature
  is_root_feature_set? && git config --unset ${config_active} || true
}

set_root_feature() {
  # Store config for recovery
  git config gitext.gitflow.branch.develop-default $(feature_prefix)
  git config gitext.gitflow.prefix.feature-default $(feature_prefix)

  # Set git flow configuration
  git config gitflow.branch.develop    $(git config gitflow.prefix.feature)${root_feature}
  git config gitflow.prefix.feature    $(git config gitflow.prefix.feature)${root_feature}-

  # store root_feature
  git config ${config_active} ${root_feature}
}

##
# Scenario code
#
if [ "${root_feature:-}" == 'help' ]; then
  show_help
elif [ "${root_feature:-}" == 'show' ]; then
  git config ${config_active} || true
elif [ -z "${root_feature:-}" ]; then
  unset_root_feature

  echo -e "GitFlow 'root feature' has been ${t_color_red}removed${t_default}."
  echo -e "GitFlow feature prefix = ${t_color_yellow}$(feature_prefix)${t_default}"
elif [ -n "${root_feature:-}" ]; then
  # Validate root_feature
  if is_root_feature_set?; then
    if git config ${config_lock} > /dev/null; then
      check_error 2 'GitFlow "root feature" already defined for "'$(git config ${config_active})'".'
    else
      unset_root_feature
    fi
  fi

  # It should be already created
  has_branch_error "$(git config gitflow.prefix.feature)${root_feature}" \
    "No such branch '$(git config gitflow.prefix.feature)${root_feature}' to define GitFlow 'root feature'."

  set_root_feature

  echo -e "GitFlow 'root feature' = ${t_color_green}${root_feature}${t_default}"
  echo -e "GitFlow feature prefix = ${t_color_yellow}$(feature_prefix)${t_default}"
else
  check_error 2 'GitFlow "root feature" is not set.'
fi
