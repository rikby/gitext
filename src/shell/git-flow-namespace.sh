#!/usr/bin/env bash

t_color_red='\e[31m'
t_color_yellow='\e[33m'
t_default='\e[0m'

namespace=$1
if [ -n "$1" ]; then
    namespace=$1'/'
fi

# Reset git flow configuration
git config gitflow.branch.master     ${namespace}master
git config gitflow.branch.develop    ${namespace}develop
git config gitflow.prefix.feature    ${namespace}feature/
git config gitflow.prefix.bugfix     ${namespace}bugfix/
git config gitflow.prefix.release    ${namespace}release/
git config gitflow.prefix.hotfix     ${namespace}hotfix/
git config gitflow.prefix.support    ${namespace}support/
git config gitflow.prefix.versiontag ${namespace}v

if [ -n "${namespace}" ]; then
    echo
    echo -e "  GitFlow namespace = ${t_color_yellow}$1${t_default}"
else
    echo
    echo -e "  GitFlow namespace ${t_color_red}removed${t_default}."
fi
