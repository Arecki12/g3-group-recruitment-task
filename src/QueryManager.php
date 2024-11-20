<?php

namespace App;

use PDO;

class QueryManager
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function insert(string $table, array $data): void
    {
        $fields = implode(', ', array_keys($data));
        $values = implode(', ', array_map(fn($value) => ":$value", array_keys($data)));
        $sql = "INSERT INTO $table ($fields) VALUES ($values)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
    }

    public function select(string $table, array $fields = ['*'], array $where = [], ?string $sortKey = null, ?string $sortDirection = null): array
    {
        $fields = implode(', ', $fields);
        $where = $this->prepareWhere($where);
        $sql = "SELECT $fields FROM $table $where";
        if ($sortKey && $sortDirection) {
            $allowedSortColumns = ['id_form', 'name', 'surname', 'email', 'phone', 'choose', 'client_no', 'agreement1', 'agreement2', 'userinfo', 'date'];
            if (in_array($sortKey, $allowedSortColumns)) {
                $sql .= " ORDER BY $sortKey $sortDirection";
            }
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count(string $table, array $where = []): int
    {
        $whereClause = $this->prepareWhere($where);
        $sql = "SELECT COUNT(*) FROM $table $whereClause";
        $stmt = $this->db->prepare($sql);
        foreach ($where as $key => $value) {
            $stmt->bindValue(":$key", $value, PDO::PARAM_STR);
        }
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    private function prepareWhere(array $where): string
    {
        if (empty($where)) {
            return '';
        }
        $conditions = array_map(
            function ($key, $value) {
                $operator = strpos($value, '%') !== false ? 'LIKE' : '=';
                return "$key $operator :$key";
            },
            array_keys($where),
            $where
        );
        return 'WHERE ' . implode(' AND ', $conditions);
    }
}
