# GitExt: Git extra commands
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

## Using
### Commands list
There are commands:
##### Sort tags
```
git tags
```
Sorting Git tags according to [semantic versioning](semver.org).

Actually it uses PHP function `version_compare()` but seem it works in the same way.

There is no tag name validation.
##### GitFlow settings for multi composer repository
```
git flow-namespace
```

Only for [multi composer repository](../../../../andkirby/multi-repo-composer) repository.

Define `gitflow` settings based upon branch namespace.

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
