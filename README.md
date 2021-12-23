## Laravel Boilerplate

![GitHub issues](https://img.shields.io/github/issues/coloredcow/laravel-init)
![GitHub issues](https://img.shields.io/github/issues-closed/coloredcow/laravel-init)
![GitHub pull request](https://img.shields.io/github/issues-pr/coloredcow/laravel-init)
![GitHub pull request](https://img.shields.io/github/issues-pr-closed/coloredcow/laravel-init)
![GitHub milestones](https://img.shields.io/github/milestones/all/coloredcow/laravel-init)
[![Coding Standards](https://github.com/coloredcow/laravel-init/actions/workflows/coding-standards.yml/badge.svg?branch=develop)](https://github.com/coloredcow/laravel-init/actions/workflows/coding-standards.yml)

### Introduction

:wave: Welcome to Laravel Init - a boilerplate for installing laravel application. It covers:
1. Pre-commit hooks for [Laravel coding standards](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md). This will lint the code changes during commit. In case coding standard is not followed, it aborts the commit and return warnings.
2. One-liner [shell script](./project-setup.sh) to set up the boilerplate.
3. GitHub [Issues](./.github/ISSUE_TEMPLATE/) and [Pull Requests](./.github/PULL_REQUEST_TEMPLATE.md) templates.
4. [GitHub Action](https://github.com/ColoredCow/laravel-init/blob/develop/.github/workflows/coding-standards.yml) for coding standards check:
    - PHP CS - PHP coding linter
    - ESLint - JavaScript coding linter
    - Larastan - Laravel static code analyzer

### Installation

1. Clone the repo using the below command
	```sh
	git clone https://github.com/ColoredCow/laravel-init.git
	```
2. Go to the project directory using below command
	```sh
	cd laravel-init
	```
3. Run this command to setup the project
	```sh
	sh project-setup.sh
	```
