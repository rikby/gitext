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
Sorting Git tags using PHP function [`version_compare()`](http://php.net/version_compare).

##### Increment tag using `semver`
[SemVer](https://github.com/npm/node-semver) must be installed.

For example we need to add new build/pre-release
```
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

##### Remove tag
```
git tag-remove TAG
```
Remove a Git tag locally and on remote "origin"
##### Move tag
```
git tag-move TAG
```
##### GitFlow settings for multi composer repository
```
git flow-namespace
```
Define `gitflow` settings based upon branch namespace.

This command will be useful within [multi composer repository](../../../../andkirby/multi-repo-composer) repository.

A namespace will be set automatically by branch name.
