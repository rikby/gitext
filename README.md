# GitExt: Git extra commands

## Intro
This application helps to extend base GIT API.
It extends:
- `git flow`
- `git tag`

## Installation
Get package via composer:
```
$ composer global require rikby/gitext ^0.3
```
And install commands:
```
$ gitext install
```

#### Inside
Please take a look command files which use for installation [in list](src/shell/command).

## Commands Using
### Sort tags
```shell
$ git tags
```
Sorting Git tags using PHP function [`version_compare()`](http://php.net/version_compare).

### Increment tag using `semver`
[SemVer](https://github.com/npm/node-semver) must be installed.

For example we need to add new build/pre-release
```shell
# show tag sorted by semver
$ semver $(git tag)
v1.0.1
[...]
v1.2.0-alpha.5

$ git tag-semver --increment prerelease
New tag: v1.2.0-alpha.1

$ semver $(git tag)
v1.0.1
[...]
v1.2.0-alpha.5
v1.2.0-alpha.6
```
Sorting Git tags according to [semantic versioning](semver.org).

Actually it uses PHP function `version_compare()` but seem it works in the same way.

There is no tag name validation.
##### GitFlow settings for super feature branch
It can be used for using your "super" issue key in GitFlow.
When you can start feature for a sub-task completely using git-flow.

In this case "develop" is a "super" issue branch instead of "right" develop.

So, there are two modes:
  - DEFAULT
```
feature prefix = feature/
develop branch = develop
```
  - ROOT FEATURE
```
feature prefix = feature/super-
develop branch = feature/super
```

##### GitFlow settings for multi composer repository
```
git flow-namespace
```

Only for [multi composer repository](../../../../andkirby/multi-repo-composer) repository.

Define `GitFlow` settings based upon branch namespace.

A namespace will be set automatically by branch name.

You may add `post-checkout` Git hook.
```
printf "#!""/usr/bin/env bash\n git flow-namespace $@" > $(git rev-parse --show-toplevel)/.git/hooks/post-checkout
```

`.git/hooks/post-checkout` file content:
```
#!/usr/bin/env bash
git flow-namespace $@
```

### Environment variables

- `GITEXT_SEMVER_BIN` - variable for custom path to `semver` binary file.
- `GITEXT_GIT_BIN`    - variable for custom path to `git` binary file.
- `GITEXT_PHP_BIN`    - variable for custom path to `php` binary file.

### User GIT commands
You may create your own commands.
Here is an example.

Create file `~/.gitext/git-hello-there.sh`:
```shell
#!/usr/bin/env bash

# CMD: git hello-there
# DESCR: Some test command.

# you may include some file GitExt files
# . $(gitext source)/shell/lib/git.sh

echo Hello there
```

Check it in commands list:
```shell
$ gitext install --help
[...]
   git hello-there        Some test command.
```

Install and test:
```shell
$ gitext install

$ git hello-there
Hello there
```
