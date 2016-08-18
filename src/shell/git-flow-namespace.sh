#!/usr/bin/env bash

git config gitflow.branch.master "$1"/master
git config gitflow.branch.develop "$1"/develop
git config gitflow.prefix.feature "$1"/feature/
git config gitflow.prefix.bugfix "$1"/bugfix/
git config gitflow.prefix.release "$1"/release/
git config gitflow.prefix.hotfix "$1"/hotfix/
git config gitflow.prefix.support "$1"/support/
git config gitflow.prefix.versiontag "$1"/v

