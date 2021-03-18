<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

$repo = new \Assessment\Services\RepoCrawler();

try {
    $page = $_GET['page'];
    $limit = $_GET['limit'];    
    $language = $_GET['language'];
    $createdFrom = $_GET['created_from'];
    $sortBy = $_GET['sort_by'];

    $page = is_numeric($page) ? (int) $page : 1;
    $limit = is_numeric($limit) ? (int) $limit : 10;
    $repos = $repo->setPageSize($limit);

    if (!empty($createdFrom)) {
        $repos->filterByCreatedFromDate($createdFrom);
    }

    if (!empty($language)) {
        $repos->filterByLanguage($language);
    }

    $data = $repo->setCurrentPage($page)->getData();
    echo json_encode(["success" => true, "data" => $data]);

} catch (\Exception $ex) {
    echo json_encode(["success" => false, "message" => $ex->getMessage()]);
}