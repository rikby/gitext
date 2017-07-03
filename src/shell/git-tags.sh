#!/usr/bin/env bash
set -o pipefail
set -o errexit
set -o nounset
#set -o xtrace

# Set magic variables for current file & dir
__dir="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
__file="${__dir}/$(basename "${BASH_SOURCE[0]}")"
readonly __dir __file

php_bin=${GITEXT_PHP_BIN:-php}
git tag | grep -E "^${GITEXT_VERSION_PREFIX:-v}" \
  | xargs -i -0 ${php_bin} ${__dir}/version-sort.php {}
