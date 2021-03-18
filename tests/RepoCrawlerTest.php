<?php

use PHPUnit\Framework\TestCase;
use Assessment\Services\RepoCrawler;

class RepoCrawlerTest extends TestCase {

    public function testRepoCrawlerFetchTheData() {
        $repoCrawler = new RepoCrawler();
        $reposData = $repoCrawler->filterByLanguage('php')->setCurrentPage(1)->getData();

        $this->assertIsArray($reposData);
        $this->assertGreaterThanOrEqual(1, count($reposData));
    }

    public function testRepoCrawlerFetchCorrectPageLimit() {
        $repoCrawler = new RepoCrawler();
        $randomLimit = rand(1, 100);
        $reposData = $repoCrawler->filterByLanguage('php')->setCurrentPage(1)->setPageSize($randomLimit)->getData();

        $this->assertEquals($randomLimit, count($reposData));
    }

    public function testRepoCrawlerDontExceedThePageLimit() {
        $this->expectException(\exception::class);        

        $repoCrawler = new RepoCrawler();
        $randomLimit = rand(101, 1000);
        $repoCrawler->filterByLanguage('php')->setCurrentPage(1)->setPageSize($randomLimit)->getData();        
    }

    public function testRepoCrawlerFilterByLanguage() {
        $repoCrawler = new RepoCrawler();
        $randomIndex = rand(0, 1);
        $randomLanguage = ['PHP', 'Java'][$randomIndex];
        $reposData = $repoCrawler->filterByLanguage($randomLanguage)->setCurrentPage(1)->getData();

        foreach($reposData as $repo) {
            $this->assertEquals($randomLanguage, $repo['language']);
        }
    }

    public function testRepoCrawlerFilterByDate() {

    }
}