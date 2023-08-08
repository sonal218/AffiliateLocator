<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Services\AffiliateService;
use Illuminate\Support\Facades\Storage;

class AffiliateServiceTest extends TestCase
{
    public function testGetMatchingAffiliatesWithin100km()
    {
        // Create a test affiliates.txt file with sample data
        $filePath = storage_path('app/test_affiliates.txt');
        $fileContent = <<<"EOD"
                        {"affiliate_id": 1, "name": "Affiliate 1", "latitude": "53.3340285", "longitude": "-6.2535495"}
                        {"affiliate_id": 2, "name": "Affiliate 2", "latitude": "53.3440285", "longitude": "-6.2535495"}
                        {"affiliate_id": 3, "name": "Affiliate 3", "latitude": "53.4340285", "longitude": "-6.2535495"}
                        {"affiliate_id": 4, "name": "Affiliate 4", "latitude": "54.3340285", "longitude": "-6.2535495"}
                        EOD;
        file_put_contents($filePath, $fileContent);

        // Create an instance of AffiliateService
        $service = new AffiliateService();

        // Test the case when there are matching affiliates
        $matchingAffiliates = $service->getMatchingAffiliates($filePath)->items();
        $this->assertCount(3, $matchingAffiliates); // Only affiliates 1, 2, and 3 should match
        $this->assertEquals(1, $matchingAffiliates[0]['affiliate_id']);
        $this->assertEquals(2, $matchingAffiliates[1]['affiliate_id']);
        $this->assertEquals(3, $matchingAffiliates[2]['affiliate_id']);
    }

    public function testNonMatchingAffiliatesOutside100Km()
    {
        // Create a test affiliates.txt file with sample data
        $filePath = 'test_affiliates.txt';
        $fileContent = <<<"EOD"
                        {"affiliate_id": 4, "name": "Affiliate 4", "latitude": "54.3340285", "longitude": "-6.2535495"}
                        {"affiliate_id": 5, "name": "Affiliate 5", "latitude": "54.9040285", "longitude": "-6.5035495"}
                        EOD;
        file_put_contents($filePath, $fileContent);

        // Create an instance of AffiliateService
        $service = new AffiliateService();

        // Test the case when there are matching affiliates
        $matchingAffiliates = $service->getMatchingAffiliates($filePath);
        $this->assertEmpty($matchingAffiliates);
    }

    public function testValidFileRead()
    {
        $filePath = storage_path('app/affiliates.txt');
        $service = new AffiliateService();
        $matchingAffiliates = $service->getMatchingAffiliates($filePath)->items();
        // Ensure that the result is an array
        $this->assertIsArray($matchingAffiliates);

        // Ensure that the correct number of affiliates are read from the file
        $this->assertCount(5, $matchingAffiliates);
    }

    public function testMissingNameField()
    {
        $filePath = storage_path('app/test_affiliates.txt');
        $fileContent = <<<"EOD"
                        {"affiliate_id": 1, "latitude": "53.3340285", "longitude": "-6.2535495"}
                        {"affiliate_id": 2, "latitude": "53.3440285", "longitude": "-6.2535495"}
                        {"affiliate_id": 3, "latitude": "53.4340285", "longitude": "-6.2535495"}
                        {"affiliate_id": 4, "latitude": "54.3340285", "longitude": "-6.2535495"}
                        EOD;
        file_put_contents($filePath, $fileContent);

        $service = new AffiliateService();

        // Ensure that the log receives the error message
        \Log::shouldReceive('error')->with("Exception while processing JSON data: Missing required fields in JSON data");

        $matchingAffiliates = $service->getMatchingAffiliates($filePath);

        // Ensure that the result is an array
        $this->assertIsArray($matchingAffiliates);

        // Ensure that the function skipped the JSON data with missing 'name'
        $this->assertCount(0, $matchingAffiliates);
    }

    public function testInvalidJsonData()
    {
        $filePath = storage_path('app/test_affiliates.txt');
        $fileContent = <<<"EOD"
                            test
                        EOD;
        file_put_contents($filePath, $fileContent);
        $affiliateService = new AffiliateService();

        // Ensure that the log receives the error message
        \Log::shouldReceive('error')->with("Exception while processing JSON data: Invalid JSON data");

        $matchingAffiliates = $affiliateService->getMatchingAffiliates($filePath);

        // Ensure that the result is an array
        $this->assertIsArray($matchingAffiliates);

        $this->assertCount(0, $matchingAffiliates);
    }
}
