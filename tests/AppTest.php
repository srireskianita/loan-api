<?php

use PHPUnit\Framework\TestCase;

class LoanApiTest extends TestCase
{
    // Test valid loan submission
    public function testValidLoanSubmission()
    {
        $data = [
            'name' => 'John Doe',
            'ktp' => '1234561505901234',
            'loan_amount' => 5000,
            'loan_purpose' => 'vacation',
            'date_of_birth' => '1990-05-15', // Fix format
            'sex' => 'male'
        ];

        $validationErrors = validateLoanApplication($data);
        $this->assertEmpty($validationErrors, "Validation should pass for valid data.");
    }

    // Test invalid name
    public function testInvalidName()
    {
        $data = [
            'name' => 'John', // Invalid: Only one word
            'ktp' => '1234561505901234',
            'loan_amount' => 5000,
            'loan_purpose' => 'vacation',
            'date_of_birth' => '1990-05-15',
            'sex' => 'male'
        ];

        $validationErrors = validateLoanApplication($data);
        $this->assertContains('Name must include at least first and last name.', $validationErrors);
    }

    // Test invalid loan amount
    public function testInvalidLoanAmount()
    {
        $data = [
            'name' => 'John Doe',
            'ktp' => '1234561505901234',
            'loan_amount' => 15000, // Invalid: Too high
            'loan_purpose' => 'vacation',
            'date_of_birth' => '1990-05-15',
            'sex' => 'male'
        ];

        $validationErrors = validateLoanApplication($data);
        $this->assertContains('Loan amount must be between 1000 and 10000.', $validationErrors);
    }
}

// Pastikan validateLoanApplication() ada (jika belum ada)
function validateLoanApplication($data)
{
    $errors = [];

    // Validate name
    if (empty($data['name']) || count(explode(' ', $data['name'])) < 2) {
        $errors[] = 'Name must include at least first and last name.';
    }

    // Validate loan amount
    if ($data['loan_amount'] < 1000 || $data['loan_amount'] > 10000) {
        $errors[] = 'Loan amount must be between 1000 and 10000.';
    }

    // Validate date_of_birth format
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['date_of_birth'])) {
        $errors[] = 'Invalid date of birth format. Use YYYY-MM-DD.';
    }

    return $errors;
}
