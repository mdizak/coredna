<?php
declare(strict_types = 1);

namespace tests\coredna;

use coredna\http_client;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the HTTP client requested by CoreDNA.
 */
class test_http_client extends TestCase
{

/**
 * Test sending
 */
public function test_send()
{

    // Set variables
    $url = 'https://www.coredna.com/assessment-endpoint.php';
    $request = [
        'name' => 'Matt Dizak', 
        'email' => 'matt.dizak@gmail.com', 
        'url' => 'https://github.com/mdizak/coredna'
    ];

    // Send OPTIONS request
    $client = new http_client();
    $response = $client->send_http_request($url, 'OPTIONS');

    // Get bearer token
    $lines = explode("\n", $response['data']);
    if (!isset($lines[1])) { 
        throw new \Exception("Unable to find bearer token");
    }
    $this->assertSame(36, strlen($lines[1]));

    // Set headers
    $headers = [
        'Authorization' => 'Bearer ' . trim($lines[1]), 
        'Cookie' => $response['headers']['set-cookie'][0]
    ];

    // Send request
    $response = $client->send_http_request($url, 'POST', $request, $headers);
    $this->assertSame(202, (int) $response['status']);


}

}



