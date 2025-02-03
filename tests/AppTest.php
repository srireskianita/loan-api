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
            'dob' => '150590',
            'sex' => 'male'
        ];

        $validationErrors = validateLoanApplication($data);
        $this->assertEmpty($validationErrors);
    }

    // Test invalid name
    public function testInvalidName()
    {
        $data = [
            'name' => 'John',
            'ktp' => '1234561505901234',
            'loan_amount' => 5000,
            'loan_purpose' => 'vacation',
            'dob' => '150590',
            'sex' => 'male'
        ];

        $validationErrors = validateLoanApplication($data);
        $this->assertContains('Name must have at least a first and last name.', $validationErrors);
    }

    // Test invalid loan amount
    public function testInvalidLoanAmount()
    {
        $data = [
            'name' => 'John Doe',
            'ktp' => '1234561505901234',
            'loan_amount' => 15000, // invalid amount
            'loan_purpose' => 'vacation',
            'dob' => '150590',
            'sex' => 'male'
        ];

        $validationErrors = validateLoanApplication($data);
        $this->assertContains('Loan amount must be between 1000 and 10000.', $validationErrors);
    }
}
