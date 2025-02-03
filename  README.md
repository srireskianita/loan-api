# Loan API - Slim Framework

## Installation

1. Clone this repository:
   ```sh
   git clone https://github.com/srireskianita/loan-api.git
   cd loan-api
   ```
2. Install dependencies using Composer:
   ```sh
   composer install
   ```

## Running the Application

1. Start the Slim application:
   ```sh
   php -S localhost:8080 -t public
   ```
2. The API will be available at `http://localhost:8080/loan`

## API Endpoint

### Submit Loan Application

- **Endpoint:** `POST /loan`
- **Request Body (JSON):**
  ```json
  {
    "name": "John Doe",
    "ktp": "1234560101901234",
    "loan_amount": 5000,
    "loan_period": 12,
    "loan_purpose": "vacation",
    "date_of_birth": "1990-01-01",
    "sex": "male"
  }
  ```
- **Response (Success):**
  ```json
  {
    "message": "Loan application submitted successfully"
  }
  ```
- **Response (Validation Errors):**
  ```json
  {
    "errors": {
      "name": "Name must contain at least first and last name"
    }
  }
  ```

## Running Unit Tests

1. Install PHPUnit:
   ```sh
   composer require --dev phpunit/phpunit
   ```
2. Run the tests:
   ```sh
   vendor/bin/phpunit tests
   ```

## File Storage

All valid loan applications are stored in `loans.json`.

## Dependencies

- PHP (latest version)
- Slim Framework
- Respect\Validation
- PHPUnit (for unit testing)

## License

MIT

