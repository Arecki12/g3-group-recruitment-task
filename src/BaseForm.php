<?php

namespace App;

use App\Exception\MissingRequiredFieldsException;
use App\Exception\SanitizeDataException;

class BaseForm
{
    public const TABLE_NAME = 'zadanie';

    protected QueryManager $dbManager;

    protected array $requiredFields =
        [
            'name',
            'surname',
            'email',
            'phone',
            'choose',
            'agreement1',
            'agreement2',
            'account'
        ];

    public function __construct(
        QueryManager $dbManager
    ) {
        $this->dbManager = $dbManager;
    }

    protected function validateData(array $data): array {
        foreach ($this->requiredFields as $field) {
            if (empty($data[$field])) {
                $errors = "Field $field is required";
            }
        }

        if (!empty($errors)) {
            throw new MissingRequiredFieldsException(json_encode($errors));
        }

        return $data;
    }

    protected function sanitizeData(array $data): array {
        $sanitizedData = [];
        foreach ($data as $key => $value) {
            if ($value == null) {
                continue;
            }
            $sanitizedValue = filter_var($value, FILTER_SANITIZE_STRING);
            if ($sanitizedValue === false || $sanitizedValue === null || trim($sanitizedValue) === '') {
                $errors[] = "Invalid data for key: $key";
            }
            $sanitizedData[$key] = $sanitizedValue;
        }

        if (!empty($errors)) {
            throw new SanitizeDataException(json_encode($errors));
        }

        return $sanitizedData;
    }
}
