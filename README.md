# Nebulatask

Welcome to the Nebulatask project! This web application is built using Laravel, Inertia.js, and Vue.js to provide a modern and efficient platform for tracking and managing issues.

## Table of Contents

-   [Introduction](#introduction)
-   [Features](#features)
-   [Getting Started with Devilbox](#getting-started-with-devilbox)
    -   [Prerequisites](#prerequisites)
    -   [Installation](#installation)
-   [Setting up Vite Proxy in Devilbox](#setting-up-vite-proxy-in-devilbox)

## Introduction

This project aims to provide a robust and user-friendly issue tracking system for better project management. It utilizes Laravel on the backend to handle server-side operations, Inertia.js for seamless single-page application development, and Vue.js for dynamic and reactive user interfaces.

## Features

-   **User Authentication**: Secure user authentication system to ensure only authorized users can access and manage issues.
-   **Create and Manage Issues**: Create, update, and delete issues with ease.

## Getting Started with Devilbox

### Prerequisites

Before you begin, ensure you have the following software installed on your machine:

-   [Docker](https://www.docker.com/)
-   [Docker Compose](https://docs.docker.com/compose/)
-   [Node.js](https://nodejs.org/) (>= 12)
-   [NPM](https://www.npmjs.com/)

### Installation

1. Start Devilbox:

    ```bash
    cd /path/to/devilbox
    docker-compose up
    ```

2. Open the Devilbox shell with PHP running:

    ```bash
    ./shell.sh
    ```

3. Change to the project directory:

    ```bash
    cd /path/to/nebulatask
    ```

4. Install PHP dependencies:

    ```bash
    composer install
    ```

5. Install Node.js dependencies:

    ```bash
    npm install
    ```

6. Generate an application key:

    ```bash
    php artisan key:generate
    ```

7. Migrate the database:

    ```bash
    php artisan migrate
    ```

Now, you should be able to access the application at [https://nebulatask.dvlsite](https://nebulatask.dvlsite).

## Setting up Vite Proxy in Devilbox

When using Vite for asset development, follow these steps to configure a proxy in Devilbox:

1.  Create a new folder inside the Devilbox web root, for example, `hmr.nebulatask.dvlsite`.
2.  Inside the newly created folder, add a subfolder named `.devilbox`.
3.  Create a file named `backend.cfg` inside the `.devilbox` folder.
4.  Add the following content to `backend.cfg`:
    ```
    conf:rproxy:ws:php:8081
    ```
    Note: You may need to adjust the port (in this example, `8081`) based on its availability.
5.  Now, in the `vite.config.js` file of the Nebulatask project, modify the following properties under the `server` configuration.

    ```javascript
    export default defineConfig({
        server: {
            https: false,
            host: "0.0.0.0",
            port: 8088, // Set the same port as in the backend.cfg file
            hmr: {
                host: "hmr.nebulatask.dvlsite",
                clientPort: 443,
                protocol: "wss",
                https: true,
            },
        },

        // ... other configurations
    });
    ```

    Ensure that the `port` property matches the port specified in the `backend.cfg` file.

Now, when you run Vite for asset development, it should use the Devilbox proxy configuration to access assets properly.
