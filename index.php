<?php
require 'vendor/autoload.php';
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

try {
    if (isset($_POST) && count($_POST) > 0) {
        $databaseManager = new \App\QueryManager();
        $fmr = new \App\UpdateForm($databaseManager);
        $fmr->processAndStoreFormData($_POST);
        echo \json_encode(['success' => 'Form has been successfully submitted']);
    } else if (isset($_GET['option']) && $_GET['option'] === 'counter') {
        $databaseManager = new \App\QueryManager();
        $frm = new \App\ReadForm($databaseManager);
        $surnameCounter = $frm->getCounterForSurname(['surname' => "Kowalski"]);
        $domainCounter = $frm->getCounterForLikeDomain(['domain' => "gmail.com"]);
        echo \json_encode(['counterSurname' => $surnameCounter, 'counterDomain' => $domainCounter]);
    } elseif (isset($_GET['option']) && $_GET['option'] === 'results') {
        $sortKey = $_GET['sortKey'] ?? null;
        $sortDirection = $_GET['sortDirection'] ?? null;
        $databaseManager = new \App\QueryManager();
        $frm = new \App\ReadForm($databaseManager);
        $results = $frm->fetchAllData($sortKey, $sortDirection);
        echo \json_encode($results);
    } else {
        die();
    }
} catch (\Exception $e) {
    http_response_code(412);
    echo \json_encode([
        'error' => is_array($e->getMessage()) ? \json_encode($e->getMessage()) : $e->getMessage(),
        'code' => $e->getCode()
    ]);
}

