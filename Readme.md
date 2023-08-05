# Project Name Readme

This repository contains the source code for the **Project Name** application. It provides a set of Docker-based commands to initialize, run, and test the application. The application is built using PHP and Symfony.

## Prerequisites

Before running the application, please make sure you have the following software installed on your system:

- Docker
- Docker Compose



## Getting Started

To set up the application, follow these steps:

1. Clone this repository to your local machine.
2. Navigate to the project root directory.
3. Run
```
docker-compose up -d
```
## Recruitment task 1

### 1. Initialize the Application
Run the following command to install dependencies and bring up the backend services:

```
make init
```

### 2. Create Database
Then To create the database for the application, use the following command:

```
make create-database
```

### 3. Import Invoices

To import invoices into the application, use the following command:

```
make import-invoices
```

This command will execute the necessary console command to import invoices.

### 4. Import Payments

To import payments into the application, use the following command:

```
make import-payments
```

## Recruitment task 2
After preforming operations from task 1
### 1. Initialize the Application
Run the following command to install dependencies and bring up the backend services:

```
make init
```
### 2. Initialize the Application
Use
```/api/customers/balances ```

To get customer balances

Use
```/api/customers/balances?customerId=%customerId  ```

To get specific customer balance

## Usage

The Makefile in the project provides several commands to interact with the application.

### 1. Initialize the Application

Run the following command to install dependencies and bring up the backend services:

```
make init
```

This command will execute the `install` and `run-backend` commands.

### 2. Install Dependencies

To install the required dependencies for the application, use the following command:

```
make install
```

This command will run the `composer install` command within the PHP container.

### 3. Run Backend Services

To start the backend services, execute the following command:

```
make run-backend
```

This command will run the Docker Compose command to bring up the required services in detached mode.

### 4. Remove Docker Containers and Volumes

If you need to clean up and remove Docker containers and volumes, use the following command:

```
make docker-rm
```

This command will run the Docker Compose command to bring down the containers and remove volumes.

### 5. Stop Docker Containers

To stop Docker containers without removing volumes, run the following command:

```
make docker-stop
```

This command will run the Docker Compose command to stop the containers.

### 6. Create Database

To create the database for the application, use the following command:

```
make create-database
```

This command will execute the necessary Doctrine commands to create the database and apply migrations.

### 7. Import Invoices

To import invoices into the application, use the following command:

```
make import-invoices
```

This command will execute the necessary console command to import invoices.

### 8. Import Payments

To import payments into the application, use the following command:

```
make import-payments
```

This command will execute the necessary console command to import payments.

### 9. Testing

To run tests for the application, several test commands are available:

- Run all tests (Behat, PHPSpec, and PHPUnit):
  ```
  make test
  ```

- Initialize backend for testing:
  ```
  make test-init-backend
  ```

- Run Behat tests:
  ```
  make test-behat
  ```

- Run PHPSpec tests:
  ```
  make test-spec
  ```

- Run PHPUnit tests (Unit tests):
  ```
  make test-unit
  ```

### 10. SSH into MySQL Container

To access the MySQL container via SSH, use the following command:

```
make ssh-mysql
```

### 11. PHP Coding Standards Fixer

To fix coding standards issues in the `src` directory, run the following command:

```
make php-cs-fix
```

### 12. PHPStan Analysis

To analyze the code using PHPStan, run the following command:

```
make php-stan-analise
```

## Contributions

Contributions to this project are welcome! If you find any issues or have improvements to suggest, please feel free to open an issue or submit a pull request.

## License

This project is licensed under the [MIT License](LICENSE).