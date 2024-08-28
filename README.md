<p align="center"><a href="https://www.dxbrunners.com" target="_blank"><img src="https://www.dxbrunners.com/wp-content/uploads/2022/01/IMG-20220119-WA0057.jpg" width="400" alt="MikhmanPOS Logo"></a></p>

# MikhmanPOS Setup

This guide will walk you through the setup process to get the application running smoothly on your local development machine.

## Prerequisites

Before you begin, ensure you have the following installed on your system:
- PHP (version 8.3 or greater)
- Composer
- A suitable SQL database (MySQL, PostgreSQL, SQLite, etc.)
- [Laravel requirements](https://laravel.com/docs/8.x/deployment#server-requirements)

## Installation

Follow these steps to install the application:

### 1. Clone the Repository

Start by cloning the source repository from GitHub (or other version control systems) to your local machine.

```bash
git clone https://github.com/Maunga/mikhman_pos
```

### 2. Install Dependencies

Navigate to the project directory and install PHP dependencies with Composer:

```bash
cd your-project-name
composer install
```

### 3. Generate Application Key

Generate a new unique key for your Laravel application:

```bash
php artisan key:generate
```

### 4. Database Migration

Run the database migrations to create the necessary tables in your database:

```bash
php artisan migrate
```

### 5. Database Seeding

Populate the database with initial data using the database seeder:

```bash
php artisan db:seed
```

## Important Notice

Do Not Run Jobs: Please do not execute any queued jobs as they are configured to synchronize with AgentX. Running these jobs without proper configuration or during initial setup might cause conflicts or unwanted syncs with AgentX.

## Running the Application

After completing the installation steps, you can run the application using Laravel's built-in server:

```bash
php artisan serve
```

This command will start a development server at http://localhost:8000.

## Troubleshooting

If you encounter any issues during installation, consider the following:

- Check the .env file settings, especially the database connections.
- Ensure all services (e.g., MySQL) are running.
- Review the application logs for any errors that may provide more insight.
- For further assistance, visit the Laravel documentation or seek help from the community forums.