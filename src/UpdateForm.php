<?php

namespace App;

class UpdateForm extends BaseForm
{

    public function __construct(QueryManager $dbManager)
    {
        parent::__construct($dbManager);
    }

    public function processAndStoreFormData(array $post): void {
        $data = $this->sanitizeData($post);
        $data = $this->validateData($data);
        $formData = [
            'name' => $data['name'],
            'surname' => $data['surname'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'choose' => $data['choose'],
            'client_no' => $data['account'],
            'agreement1' => $data['agreement1'],
            'agreement2' => $data['agreement2'],
            'user_info' => $data['user_info'] ?? null,
        ];
        $this->dbManager->insert(self::TABLE_NAME, $formData);
    }
}
