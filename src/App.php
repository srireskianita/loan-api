<?php
use Slim\App;
use Respect\Validation\Validator as v;

return function (App $app) {
    // POST /loan route
    $app->post('/loan', function ($request, $response, $args) {
        // Get request data
        $data = $request->getParsedBody();

        // Check if data is valid

        if (is_null($data) || empty($data)) {
            $response->getBody()->write(json_encode(['error' => 'Invalid request body']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        // Validate required fields
        if (!isset($data['name'], $data['ktp'], $data['loan_amount'], $data['loan_purpose'], $data['date_of_birth'], $data['sex'])) {
            $response->getBody()->write(json_encode(['error' => 'Missing required fields']));
            return $response->withHeader('Content-Type', 'application/json')
                            ->withStatus(400);
        }

        // Validate Name (at least two names and between 3-50 characters)
        $nameValidator = v::stringType()->notEmpty()->length(3, 50);
   
        if (empty($data['name']) || count(explode(' ', $data['name'])) < 2) {
            $response->getBody()->write(json_encode(['error' => 'Name must include at least first and last name']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }


        // Validate KTP (PIN) with regex for proper format
        $ktpValidator = null;
        if ($data['sex'] === 'male') {
            // For male, the KTP follows the format: XXXXXXDDMMYYXXXX
            $ktpValidator = v::regex('/^\d{6}[0-3][0-9][01][0-9]\d{2}\d{4}$/');
        } elseif ($data['sex'] === 'female') {
            // For female, the KTP follows the format: XXXXXX(DD+40)MMYYXXXX
            $ktpValidator = v::regex('/^\d{6}(4[1-9]|5[0-9]|6[0-9]|7[0-9])[01][0-9]\d{2}\d{4}$/');
        }
        
        if (!$ktpValidator || !$ktpValidator->validate($data['ktp'])) {
            return $response->withHeader('Content-Type', 'application/json')
                            ->withStatus(400)
                            ->write(json_encode(['error' => 'Invalid KTP number format']));
        }

        // Validate Loan Amount (between 1000 and 10000)
        $loanAmountValidator = v::intType()->between(1000, 10000);
        if (!$loanAmountValidator->validate($data['loan_amount'])) {
            $response->getBody()->write(json_encode(['error' => 'Loan amount must be between 1000 and 10000']));
            return $response->withHeader('Content-Type', 'application/json')
                            ->withStatus(400);
        }

        // Validate Loan Purpose (one of the predefined options)
        $purposeValidator = v::in(['vacation', 'renovation', 'electronics', 'wedding', 'rent', 'car', 'investment']);
        if (!$purposeValidator->validate($data['loan_purpose'])) {
            $response->getBody()->write(json_encode(['error' => 'Invalid loan purpose']));
            return $response->withHeader('Content-Type', 'application/json')
                            ->withStatus(400);
        }

        // Validate Date of Birth (must be in 'Y-m-d' format)
        $dobValidator = v::date('Y-m-d');
        if (!$dobValidator->validate($data['date_of_birth'])) {
            $response->getBody()->write(json_encode(['error' => 'Invalid date of birth format']));
            return $response->withHeader('Content-Type', 'application/json')
                            ->withStatus(400);
        }

        // Validate Sex (either 'male' or 'female')
        $sexValidator = v::in(['male', 'female']);
        if (!$sexValidator->validate($data['sex'])) {
            $response->getBody()->write(json_encode(['error' => 'Invalid sex']));
            return $response->withHeader('Content-Type', 'application/json')
                            ->withStatus(400);
        }

        // Save the application data to a JSON file
        $file = 'loan_applications.json';
        $applications = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
        $applications[] = $data;

        // Ensure the file is writable
        if (!is_writable($file) && !is_writable(dirname($file))) {
            $response->getBody()->write(json_encode(['error' => 'Unable to write to file']));
            return $response->withHeader('Content-Type', 'application/json')
                            ->withStatus(500);
        }

        file_put_contents($file, json_encode($applications, JSON_PRETTY_PRINT));

        // Return success response
        $response->getBody()->write(json_encode(['status' => 'success', 'message' => 'Loan application received successfully.']));
        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus(200);
    });
};
