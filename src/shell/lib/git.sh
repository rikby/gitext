#!/usr/bin/env bash

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
