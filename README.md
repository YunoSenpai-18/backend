## Laravel Setup Instructions

This guide will walk you through setting up a local development environment for a Laravel project.

## Prerequisites

Before you begin, ensure you have the following installed:

### 1. **Download Laravel Herd**

- Download [Laravel Herd](https://herd.laravel.com/windows)

### 2. **Install DBngin**

- Download and install [DBngin](https://dbngin.com/), a MySQL management tool for local development.

### 3. **Install TablePlus**

- Download and install [TablePlus](https://tableplus.com/download/), a MySQL database management tool.

### 4. **Install Composer**

- Download and install [Composer](https://getcomposer.org/) for managing PHP dependencies.

## Installation and Setup

1. **Clone or Download the Repository**: 
    - Clone the repository using:
    ```bash
    git clone https://github.com/YunoSenpai-18/backend.git
    cd your-laravel-app
    ```
    - Alternatively, you can download the ZIP and unzip it in your desired directory.

2. **Install Project Dependencies**: Run the following command to install the necessary PHP dependencies using Composer:
    ```bash
    composer install
    ```

3. **Copy the `.env` file**: Copy the `.env.example` file to `.env`:
    ```bash
    cp .env.example .env
    ```

4. **Edit the `.env` File**: Open the `.env` file and change the `SESSION_DRIVER` configuration:
    ```plaintext
    SESSION_DRIVER=file
    ```

5. **Generate Application Key**: Generate the application key:
    ```bash
    php artisan key:generate
    ```

6. **Run Database Migrations**: Set up the database by running the migrations:
    ```bash
    php artisan migrate
    ```

7. **Seed the Database**: Seed the database with the UserSeeder:
    ```bash
    php artisan db:seed --class=UserSeeder
    ```

8. **Start MySQL Database**: Open DBngin and click the Plus (+) button to create a MySQL instance. Choose MySQL as the database, then click **Create**.

9. **Configure TablePlus**: Open TablePlus and configure your local MySQL connection:
    - **Name**: localhost
    - **Host/IP**: 127.0.0.1
    - **Port**: 3306
    - **User**: root
    - **Password**: (leave blank or as per your setup)
    Click **Save** to establish the connection.

10. **Configure Laravel Herd**: Open Laravel Herd and make sure all services are running (green status). Under **General**, add the path to folder with the project inside.

11. **Expose your Local Server**: 
    - Create an **Expose** account and obtain your Expose token from [Expose](https://expose.dev/).
    - Use the following command to share your local Laravel app over the internet:
    ```bash
    expose share http://backend-main.test
    ```
    - Copy the **public URL** provided by Expose.

12. **Test Accessibility**: Make sure the URL is accessible in a web browser. It should look something like this: `https://your-expose-url.expose.dev`.

13. **Connect with React Native**: Open your React Native app and paste the **Expose public URL** into the backend configuration or API URL setting to establish a connection between the frontend and backend for testing.