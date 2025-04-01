# Deployment Guidelines

This document provides a step-by-step guide to deploying the project using GitHub Actions and `deploy.yml`. Follow these steps to configure deployment as per your project details.

## Prerequisites
Ensure you have the following in place before proceeding with deployment:

- A remote server (AWS EC2, etc.) running Ubuntu.
- SSH access to the server.
- A domain or subdomain for staging (e.g., `https://staging.example.com`).
- Required environment variables stored as GitHub Secrets.

## Configuration Steps

### 1. Update `deploy.yml`
Modify `.github/workflows/deploy.yml` as per your project requirements:

- **Branch:** Ensure the `on.push.branches` is set to the correct deployment branch.
- **Environment URL:** Change `url: "https://staging.example.com"` to match your domain.

### 2. Set GitHub Secrets
Go to **Settings → Secrets and variables → Actions** and configure the following secrets:

- `SSH_HOST` – Your server's IP or domain.
- `SSH_USERNAME` – The SSH username for the server.
- `SSH_PRIVATE_KEY` – The private SSH key for authentication.
- `SSH_BUILD_DIRECTORY` – The absolute path where the project is deployed on the server.

### 3. Configure Server for Deployment

Ensure your server has the required dependencies installed:

```bash
sudo apt update && sudo apt install -y nginx php82 php82-cli php82-mbstring php82-xml php82-curl composer git
```

Also, make sure your Laravel `.env` file is correctly configured and has the correct database connection details.

### 4. Deployment Process

Once everything is set up, pushing code to the `main` branch will trigger the deployment workflow. The following steps occur:

1. **Checkout Repository:** The latest code is pulled from the repository.
2. **Pull Changes:** The `git pull` command updates the codebase.
3. **Run Migrations:** Database migrations are executed (`artisan migrate`).
4. **Install Dependencies:** Composer dependencies are installed and optimized.
5. **Optimize Application:** The Laravel app is optimized for better performance.
6. **Deployment Complete:** The project is deployed successfully.

### 5. Manual Deployment
If needed, you can manually run the deployment script:

```bash
cd /path/to/project
sudo git pull origin main
sudo /usr/bin/php82 artisan migrate
sudo /usr/bin/php82 /usr/bin/composer install --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist --optimize-autoloader
sudo /usr/bin/php82 /usr/bin/composer dump-autoload
sudo /usr/bin/php82 optimize
```

### 6. Troubleshooting

- **Permission Issues:** Ensure correct permissions are set for the project directory (`chown -R www-data:www-data /path/to/project`).
- **Deployment Failures:** Check the GitHub Actions logs for errors and manually SSH into the server to debug.
- **Database Issues:** If migrations fail, ensure the database credentials in `.env` are correct and the database is accessible.

---

Following these steps should ensure a smooth deployment process for your project. Happy coding!

