# Technical Test: Back-End Knowledge for E-commerce and API Creation with Laravel 11 & Docker

This repository contains a Laravel 11-based e-commerce API built using Docker. It handles CRUD operations for products, categories, and orders, and supports user authentication with Laravel Sanctum.

## Table of Contents
1. [Prerequisites](#prerequisites)
2. [Installation](#installation)
3. [Environment Configuration](#environment-configuration)
4. [Running the Application](#running-the-application)
5. [Testing the API](#testing-the-api)
6. [Routes and Endpoints](#routes-and-endpoints)
7. [Troubleshooting](#troubleshooting)

---

## Prerequisites

Before starting, ensure you have the following installed on your machine:

- **Docker**: [Install Docker](https://docs.docker.com/get-docker/)
- **Docker Compose**: [Install Docker Compose](https://docs.docker.com/compose/install/)
- **PHP** (for local development, optional): [Install PHP](https://www.php.net/manual/en/install.php)
- **Postman** (for testing the API, optional): [Install Postman](https://www.postman.com/downloads/)

## Installation

1. **Clone the Repository:**
   Clone this repository to your local machine.

   ```bash
   git clone https://github.com/your-username/ecommerce-api.git
   cd ecommerce-api
   ```

2. **Set Up Docker:**

   The project comes preconfigured with Docker. If Docker is properly installed, you can build and start the application using the following command:

   ```bash
   docker-compose up --build
   ```

   This will:
   - Build the necessary Docker containers.
   - Start the Laravel application and its dependencies (e.g., MySQL, Redis).

3. **Install Dependencies:**
   Once the Docker containers are up, you need to install the Laravel dependencies:

   ```bash
   docker-compose exec app composer install
   ```

4. **Set Up Environment Variables:if you open your .env.example you will have ready all the variables for your project enviroment as i prepare for you to make it easier. Anyway always check if they are correct.**
   
   Copy the `.env.example` file to create your `.env` file:

   ```bash
   cp .env.example .env
   ```

   Open the `.env` file and configure the following settings:
   - `DB_HOST` should be set to `mysql`.
   - `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` should match the credentials in your Docker MySQL container.

   Example:

   ```env
   DB_HOST=mysql
   DB_DATABASE=db_test
   DB_USERNAME=sail
   DB_PASSWORD=password
   ```
 
6. **Generate the Application Key:**

   Run the following command to generate a new application key:

   ```bash
   docker-compose exec app php artisan key:generate
   ```

7. **Run Migrations:**

   Run the database migrations to set up the necessary tables:

   ```bash
   docker-compose exec app php artisan migrate
   ```

8. **Create a Super User (Optional):**
   You can create a test user for API access using Laravel's `tinker`:

   ```bash
   docker-compose exec app php artisan tinker
   ```

   Then, in the tinker shell, create a new user:

   ```php
   \App\Models\User::create([
       'name' => 'Test User',
       'email' => 'testuser@example.com',
       'password' => bcrypt('password')
   ]);
   ```

---

## Running the Application

Once everything is set up, you can access the application at:

```
http://localhost
```

The API will be available at `http://localhost/api`.

If you're using Postman, you can test the API by making requests to the endpoints listed below.

---

## Testing the API using postman (https://web.postman.co/)

### Authentication

1. **Register:** 

   Use the following endpoint to register:

   - `POST http://localhost/api/register`
   
   Request body:

   ```json
   {
       "name": "Test User",
       "email": "testuser@example.com",
       "password": "password"
       "password_confirmation": "password"
   }
   ```

 2. **To generate a bearer token:**
  
     - `POST http://localhost/api/generate-token`
   
    This will show you the token you need to copy in Postman>>Authorization>>Bearer Token and test the endpoints that needs authentication, as creating new products, new orders or new categories.

### Available Endpoints

#### **Products**

- **List Products**: `GET /api/products`
- **View Product Details**: `GET /api/products/{id}`
- **Create Product**: `POST /api/products`
- **Update Product**: `PUT /api/products/{id}`
- **Delete Product**: `DELETE /api/products/{id}`

#### **Categories**

- **List Categories**: `GET /api/categories`
- **View Category Details**: `GET /api/categories/{id}`
- **Create Category**: `POST /api/categories`
- **Update Category**: `PUT /api/categories/{id}`
- **Delete Category**: `DELETE /api/categories/{id}`

#### **Orders**

- **List Orders**: `GET /api/orders`
- **Create Order**: `POST /api/orders`
- **View Order Details**: `GET /api/orders/{id}`
- **Update Order Status**: `PUT /api/orders/{id}`
- **Cancel Order**: `PUT /api/orders/{id}/cancel`

#### Example creating a product :

```json
{
    "name": "T-shirt with design",
    "description": "made with 100% organic cotton",
    "price": 100.49,
    "stock": 5,
    "category_id": 1,
    "image": "image-03.jpg"
}
```

---

#### Example Request to Create an Order:

```json
{
  "products": [
    {
      "id": 1,
      "quantity": 3,
      "price": 68.49
    },
    {
      "id": 2,
      "quantity": 1,
      "price": 67.59
    }
  ],
  "total": 273.06
}
```

---

## Troubleshooting

- **500 Internal Server Error**:
  - Ensure that your `.env` file is properly configured.
  - Check the logs with `docker-compose exec app tail -f storage/logs/laravel.log` for more details.
  
- **Invalid Token Error**:
  - Ensure you are sending the correct Bearer token in the Authorization header.
  
- **Database Connection Error**:
  - Ensure that your database settings in the `.env` file are correct, especially `DB_HOST`, `DB_USERNAME`, `DB_PASSWORD`, and `DB_DATABASE`.

---
