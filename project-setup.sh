#! /bin/bash
echo 'Creating env file'
cp .env.example .env

echo 'Installing composer dependencies'
composer install

echo 'Installing npm dependencies'
npm install
npm run dev

echo 'Generating application key'
php artisan key:generate

echo 'Setting up pre-commit hooks'
cp git-hooks/pre-commit .git/hooks/pre-commit
chmod +x .git/hooks/pre-commit
