#!/usr/bin/env bash

# DESCR: Create new SemVer PreRelease tag based upon the last one.

set -o pipefail
set -o errexit
set -o nounset
#set -o xtrace

# Set magic variables for current file & dir
__dir="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
__file="${__dir}/$(basename "${BASH_SOURCE[0]}")"
readonly __dir __file

bash ${__dir}/../api/git-add-semver-tag.sh '--increment prerelease'
