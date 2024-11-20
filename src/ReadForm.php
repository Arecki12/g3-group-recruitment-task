<?php

namespace App;

class ReadForm extends BaseForm
{
    public function __construct(QueryManager $dbManager)
    {
        parent::__construct($dbManager);
    }

    public function fetchAllData(?string $sortKey = 'id_form', ?string $sortDirection = 'ASC'): array
    {
        $where = [];
        return $this->dbManager->select(self::TABLE_NAME, ['*'], $where, $sortKey, $sortDirection);
    }

    public function getCounterForSurname(array $get): int {
        $data = $this->sanitizeData($get);
        $where = ['surname' => $data['surname']];
        return $this->dbManager->count(self::TABLE_NAME, $where);
    }

    public function getCounterForLikeDomain(array $get): int {
        $data = $this->sanitizeData($get);
        $where = ['email' => '%' . $data['domain'] . '%'];
        return $this->dbManager->count(self::TABLE_NAME, $where);
    }

}
