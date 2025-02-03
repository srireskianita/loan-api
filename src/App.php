<?php

use Slim\Factory\AppFactory;
use Respect\Validation\Validator as v;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

// Route for loan application
$app->post('/loan', function ($request, $response, $args) {
    // Parse incoming JSON data
    $data = json_decode($request->getBody()->getContents(), true);

    // Validate the data using Respect\Validation
    $errors = validateLoanApplication($data);

    if (!empty($errors)) {
        // Return validation errors
        $response->getBody()->write(json_encode(['errors' => $errors]));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }

    // If validation passes, write to a file (JSON format)
    file_put_contents(__DIR__ . '/../loan-applications.json', json_encode($data, JSON_PRETTY_PRINT) . PHP_EOL, FILE_APPEND);

    // Respond with success message
    $response->getBody()->write(json_encode(['message' => 'Application submitted successfully!']));
    return $response->withHeader('Content-Type', 'application/json');
});

// Validation function
function validateLoanApplication($data) {
    $errors = [];

    // Validate Name (must have at least two names)
    if (!isset($data['name']) || !preg_match('/\s/', $data['name'])) {
        $errors[] = 'Name must have at least a first and last name.';
    }

    // Validate KTP (ID Number)
    if (!isset($data['ktp']) || !preg_match('/^\d{6}(0[1-9]|1[0-2])\d{2}\d{4}$/', $data['ktp'])) {
        $errors[] = 'KTP must match the specified format (XXXXXXDDMMYYXXXX).';
    }

    // Validate Loan amount (must be between 1000 and 10000)
    if (!isset($data['loan_amount']) || $data['loan_amount'] < 1000 || $data['loan_amount'] > 10000) {
        $errors[] = 'Loan amount must be between 1000 and 10000.';
    }

    // Validate Loan Purpose (must match predefined set of values)
    if (!isset($data['loan_purpose']) || !in_array(strtolower($data['loan_purpose']), ['vacation', 'renovation', 'electronics', 'wedding', 'rent', 'car', 'investment'])) {
        $errors[] = 'Loan purpose must be one of the following: vacation, renovation, electronics, wedding, rent, car, investment.';
    }

    // Validate Date of Birth (DDMMYY format)
    if (!isset($data['dob']) || !preg_match('/^\d{6}$/', $data['dob'])) {
        $errors[] = 'Date of birth must be in the format DDMMYY.';
    }

    // Validate Sex (must be "male" or "female")
    if (!isset($data['sex']) || !in_array(strtolower($data['sex']), ['male', 'female'])) {
        $errors[] = 'Sex must be either "male" or "female".';
    }

    return $errors;
}

$app->run();
