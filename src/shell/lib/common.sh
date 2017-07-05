#!/usr/bin/env bash

. "$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"/text-colors.sh

##
# Check status and show error
#
# It checks error status and in case it's non-zero it'll show error.
#
# check_error STATUS ERROR_MESSAGE
#
check_error () {
  local status=${1}
  shift
  if [ '0' != "${status}" ]; then
    echo -e "${t_color_red}error${t_default}: ${@}" > /dev/stderr
  fi
  if [ '-1' != "${status}" ] && [ '0' != "${status}" ]; then
    return ${status}
  fi
}

show_warning() {
  echo -e "${t_color_yellow}warning${t_default}: ${@}" > /dev/stderr
}

show_note() {
  echo -e "note: ${@}"
}
