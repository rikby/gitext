#!/usr/bin/env bash
set -o pipefail
set -o errexit
set -o nounset
#set -o xtrace

# Set magic variables for current file & dir
__dir="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
__file="${__dir}/$(basename "${BASH_SOURCE[0]}")"
readonly __dir __file

. ${__dir}/../lib/text-colors.sh
. ${__dir}/../lib/git.sh

semver_bin=${GITEXT_SEMVER_BIN:-semver}

if [ -z "${1:-}" ]; then
  echo 'gitext error: Please define semver options in first argument.'
  exit 2
fi

semver_options=${1}
point_tag=${2:-$(last_tag)}





echo $(tag_prefix)$(create_semver_tag "${semver_options}" ${point_tag}) | xargs -i sh -c 'git tag {} && echo "New tag: {}"'
