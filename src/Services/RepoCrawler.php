<?php
declare(strict_types=1);

namespace Assessment\Services;

class RepoCrawler {

    /**
     * @var int, default page size 10
     */
    private $pageSize = 10;

    /**
     * @var int, default value 1
     */
    private $page = 1;

    /**
     * @var string, sorting data by, default value 'stars'
     */
    private $sortingBy = 'stars';
    
    /**
     * @var string Sorting Order
     */
    private $sortingOrder = 'desc';

    /**
     * @var string, Repo created from
     */
    private $createdFrom;

    /**
     * @var string, Repo programming language
     */
    private $language;

    /**
     * @var string
     */
    private $requestUrl = 'https://api.github.com/search/repositories';

    /**
     * @param int $pageSize, request page size
     * @throw \Exception with message Maximum page size is 100
     */
    public function setPageSize(int $pageSize) {
        if ($pageSize > 100) {
            throw new \Exception("Maximum page size is 100");
        }

        $this->pageSize = $pageSize;
        return $this;
    }

    /**
     * @param int $page, curentt page number
     * @throw \Exception with message Page number is not valid
     */
    public function setCurrentPage(int $page) {
        if ($page < 1) {
            throw new \Exception("Page number is not valid");
        }

        $this->page = $page;
        return $this;
    }

    /**
     * Set the Sorting fields, ex. stars
     */
    public function setSortingBy($sortingBy) {
        $this->sortingBy = $sortingBy;
        return $this;
    }

    /**
     * Set the Sorting Order asc, desc
     */
    public function setSortingOrder($sortingOrder) {
        $this->sortingOrder = $sortingOrder;
        return $this;
    }

    /**
     * Add filter by the creation date of the repo, format Y-m-d
     */
    public function filterByCreatedFromDate($createdFrom) {
        $this->createdFrom = $createdFrom;
        return $this;
    }

    /**
     * Add filter by the repo development language, ex. PHP, Java
     */
    public function filterByLanguage($language) {
        $this->language = $language;
        return $this;
    }

    /**
     * Call the API and get the response items
     */
    public function getData() {
        $requestQueryString = $this->getRequestQueryParams();
        $requestUrl = $this->requestUrl . "?{$requestQueryString}";
           
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $requestUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');        
        $output = curl_exec($ch);        
        curl_close($ch);

        $outputData = json_decode($output, true);
        
        return $outputData['items'];
    }

    /**
     * Build the Query param field
     */
    private function getFilterQueryParams() {
        $filterQueryParameters = [];

        if ($this->createdFrom) {
            $filterQueryParameters[] = "created:>{$this->createdFrom}";
        }

        if ($this->language) {
            $filterQueryParameters[] = "language:{$this->language}";
        }

        if (!empty($filterQueryParameters)) {
            return ["q" => implode("+", $filterQueryParameters)];
        }
        
        throw new \Exception("Filter query is not valid", 422);
    }

    /**
     * Prepare the request query parameters
     */
    private function getRequestQueryParams() {
        $requestQueryParameters = [
            'page'     => $this->page,
            'per_page' => $this->pageSize,
            'order'    => $this->sortingOrder,
            'sort'     => $this->sortingBy
        ];

        $filterQueryParameters = $this->getFilterQueryParams();

        return urldecode(http_build_query(array_merge($filterQueryParameters, $requestQueryParameters)));
    }
}