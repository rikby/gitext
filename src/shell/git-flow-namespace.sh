#!/usr/bin/env bash

t_color_red='\e[31m'
t_color_yellow='\e[33m'
t_default='\e[0m'

namespace_prefix=''
namespace="${1:-}"

if [ -n "${namespace:-}" ]; then
  namespace_prefix="${namespace}/"
fi

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
