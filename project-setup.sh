#! /bin/bash
echo 'starting the project'
cp .env.example .env
echo 'Installing Composer'
composer install
echo 'Installing npm'
npm install
npm run dev
echo 'Generating application key'
php artisan key:generate
echo 'Setting Pre-commit hooks'
cp git-hooks/pre-commit .git/hooks/pre-commit
chmod +x .git/hooks/pre-commit
