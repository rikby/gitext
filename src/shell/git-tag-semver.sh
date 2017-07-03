#!/usr/bin/env bash
set -o pipefail
set -o errexit
set -o nounset
#set -o xtrace

# Set magic variables for current file & dir
__dir="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
__file="${__dir}/$(basename "${BASH_SOURCE[0]}")"
readonly __dir __file

if [ $# == 0 ]; then
  ${GITEXT_SEMVER_BIN:-semver}
  echo && echo "error: No arguments." > /dev/stderr
  exit 3
fi

bash ${__dir}/api/git-add-semver-tag.sh "${@}"
