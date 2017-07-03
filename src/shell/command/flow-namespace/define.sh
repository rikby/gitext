#!/usr/bin/env bash

set -o pipefail
set -o errexit
set -o nounset
#set -o xtrace

# Set magic variables for current file & dir
__dir="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
__file="${__dir}/$(basename "${BASH_SOURCE[0]}")"
readonly __dir __file

. ${__dir}/../../lib/common.sh
. ${__dir}/../../lib/git.sh

check_flow_branches() {
  local status
  local error_message
  error_message=$(cat <<EOF
Please be sure following branches are created:
  ${namespace_prefix}master
  ${namespace_prefix}develop
EOF
)
  has_branch_error ${namespace_prefix}master "${error_message}"
  has_branch_error ${namespace_prefix}develop "${error_message}"
}

namespace_prefix=''
namespace="${1:-}"

if [ -n "${namespace:-}" ]; then
  namespace_prefix="${namespace}/"
fi

check_flow_branches

# Reset git flow configuration
git config gitflow.branch.master     ${namespace_prefix}master
git config gitflow.branch.develop    ${namespace_prefix}develop
git config gitflow.prefix.feature    ${namespace_prefix}feature/
git config gitflow.prefix.bugfix     ${namespace_prefix}bugfix/
git config gitflow.prefix.release    ${namespace_prefix}release/
git config gitflow.prefix.hotfix     ${namespace_prefix}hotfix/
git config gitflow.prefix.support    ${namespace_prefix}support/
git config gitflow.prefix.versiontag ${namespace_prefix}v

if [ -n "${namespace:-}" ]; then
  echo
  echo -e "  GitFlow namespace = ${t_color_yellow}${namespace}${t_default}"
else
  echo
  echo -e "  GitFlow namespace ${t_color_red}removed${t_default}."
fi
