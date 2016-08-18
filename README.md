# GitExt: Git extra commands
### Commands list
There are commands:
##### Sort tags
```
git tags
```
Sorting Git tags according to [semantic versioning](semver.org).
##### Remove tag
```
git tag-remove TAG
```
Remove a Git tag locally and on remote "origin"
##### Move tag
```
git tag-move TAG
```
Move a Git tag to a current commit locally and on remote "origin" (requires `git tag-remove`).
### Installation
Get package via composer:
```
$ composer global require rikby/gitext ^0.1
```
And install commands:
```
$ gitext install
```

#### Inside
Please take a look command files which use for installation [in list](src/shell/command).
