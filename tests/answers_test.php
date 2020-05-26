<?php
declare(strict_types = 1);

namespace tests\coredna;

use coredna\answers;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the four quick test functions requested by CoreDNA.
 */
class test_answers extends TestCase
{

/**
 * Test array match
 */
public function test_array_match()
{

    $strarr = [
        [5, 2, 7, 12], 
        [3, 7, 4, 1, 85, 17, 2, 4]  
    ];

    $client = new answers();
    $response = $client->array_match_test($strarr);
    $this->assertSame('8-9-11-13-85-17-2-4', $response);

}

/**
 * Test clean HTML
 */
public function test_clean_html()
{

    $html = '<div><p><i>Hello world</i></p></em>';

    $client = new answers();
    $result = $client->clean_html($html);
    $this->assertSame($result, 'div');

}

/**
 * Test JSON cleaning
 */
public function test_json_cleaning()
{

    $client = new answers();
    $result = $client->json_cleaning();
    $this->assertSame('{"name":{"first":"Robert","last":"Smith"},"age":25,"hobbies":["running","coding"],"education":{"college":"Yale"}}', $result);

}

/**
 * Test age count
 */
public function test_age_count()
{

    $client = new answers();
    $num = $client->age_count();
    $this->assertSame(128, $num);

}

}


