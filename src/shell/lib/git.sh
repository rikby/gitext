#!/usr/bin/env bash

# GIT API methods

set -o pipefail
set -o errexit
set -o nounset
#set -o xtrace

tag_prefix() {
  ${GITEXT_GIT_BIN:-git} config gitext.tag-prefix 2> /dev/null || \
    ${GITEXT_GIT_BIN:-git} tag | tail -1 | grep -Eo ^[^0-9]
}

all_tags() {
  if [ -n "$(tag_prefix)" ]; then
    ${GITEXT_GIT_BIN:-git} tag | grep -E "^$(tag_prefix)"
  else
    ${GITEXT_GIT_BIN:-git} tag
  fi
}

has_branch() {
  git branch | tr '*' ' ' \
    | sed 's/^ *//;s/ *$//' | grep "${1}" > /dev/null
}
has_branch_error() {
  local err \
        branch=${1}
  shift
  if [[ -n "${@}" ]]; then
    err="${@}"
  else
    err="No such branch '${branch}'."
  fi
  if ! has_branch ${branch} ; then
    check_error 2 "${err}"
  fi
}

last_tag() {
  ${GITEXT_SEMVER_BIN:-semver} $(all_tags) | tail -1
}

create_semver_tag() {
  ${GITEXT_SEMVER_BIN:-semver} ${1} ${2:-$(last_tag)}
}

add_tag() {
  ${GITEXT_GIT_BIN:-git} tag ${1}
  echo "New tag: ${1}"
}

check_semver() {
  if ! ${GITEXT_SEMVER_BIN:-semver} 2> /dev/null 1> /dev/null; then
    echo 'gitext error: Semver binary file "'${GITEXT_SEMVER_BIN:-semver}'" not found. Please check installation on https://github.com/npm/node-semver.' > /dev/stderr
    exit 127
  fi
}

check_version() {
  if ! ${GITEXT_SEMVER_BIN:-semver} ${1} 1> /dev/null; then
    check_semver
    echo 'gitext error: Tag "'${1}'" is not valid. Please check schema in semver.org.' > /dev/stderr
    exit 2
  fi
}

##
# Check if git flow branches are initiated
#
# $ check_flow_branches [BRANCH_MASTER] [BRANCH_DEVELOP] [PREFIX]
#
check_flow_branches() {
  local status=0 \
    master=${1:-master} \
    develop=${2:-develop}
    namespace_prefix=${3:-}

  if ! has_branch_error ${namespace_prefix}${master} \
    "GitFlow branch '${namespace_prefix}${master}' is not created."; then
    status=2
  fi
  if ! has_branch_error ${namespace_prefix}${develop} \
    "GitFlow branch '${namespace_prefix}${develop}' is not created."; then
    status=2
  fi

  return ${status}
}

##
# Check if GitFlow configuration is completely set
#
has_flow_config() {
  local path \
    paths=$(cat <<EOF
gitflow.branch.master
gitflow.branch.develop
gitflow.prefix.feature
gitflow.prefix.bugfix
gitflow.prefix.release
gitflow.prefix.hotfix
gitflow.prefix.support
gitflow.prefix.versiontag
EOF
)
  echo -e "${paths}" | while read path; do
    if ! git config ${path} > /dev/null; then
      check_error -1 "GitFlow config '${path}' is not defined."
      check_error 2 'Please init GitFlow configuration by command:'\
"\n\n    git flow init"
    fi
  done
}
