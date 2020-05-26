<?php
declare(strict_types = 1);

namespace coredna;

/**
 * Class that contains the four test functions requested by 
 * CoreDNA team.
 */
class answers
{


/**
 * Array matching test.
 *
 * @param array $strarr The input array containg two elements, each of which is a one-dimensional array of integer.
 *
 * @return string Resulting string.
 */
public function array_match_test(array $strarr):string
{
    // Go through array
    $output = [];
    foreach ($strarr[0] as $num) { 
        $num2 = count($strarr[1]) > 0 ? array_shift($strarr[1]) : 0;
        $output[] = ($num + $num2);
    }

    // Add remaining elements, if needed
    if (count($strarr[1]) > 0) { 
        array_push($output, ...$strarr[1]);
    }

    // Return
    return implode('-', $output);

}

/**
 * Clean HTML tags
 *
 * @param string $html The input HTML string
 *
 * @return mixed The first element not matching correctly, otherwise true if all is well in the world.
 */
function clean_html(string $html)
{

    // Go through tags
    $tags = [];
    preg_match_all("/<(.+?)>/s", $html, $tag_match, PREG_SET_ORDER);
    foreach ($tag_match as $match) { 

        // Check for closing tag
        if (preg_match("/^\/(.+)/", $match[1], $end_match)) { 

            // Check
            $chk_tag = array_shift($tags);
            if ($end_match[1] != $chk_tag) { 
                return $chk_tag;
            }

        // Opening tag
        } else { 
            $tags[] = $match[1];
        }
    }

    // Return
    return true;

}

/**
 * JSON cleaning
 *
 * @return string The resulting string.
 */
function json_cleaning():string
{

    // Get JSON
    if (!$text = file_get_contents('https://coderbyte.com/api/challenges/json/json-cleaning')) { 
        throw new \Exception('Unable to retrieve JSON data from server');
    }

    // Decode JSON
    if (!$vars = @json_decode($text, true)) { 
        throw new \Exception('Unable to decode JSON');
    }

    // Clean JSOn
    $output = $this->clean_json_array($vars);

    // Return
    return json_encode($output);

}

/**
 * Clean JSOn array
 *
 * @param array $vars The JSOn array
 *
 * @return array The resulting array
 */
protected function clean_json_array(array $vars):array
{

    // Go through array
    $output = [];
    foreach ($vars as $key => $value) { 

        if (is_array($value)) { 
            $output[$key] = $this->clean_json_array($value);
        } elseif (empty($value) || $value == '-' || strtolower((string) $value) == 'n/a') { 
            continue;
        } else { 
            $output[$key] = $value;
        }
    }

    // Return
    return $output;

}

/**
 * Age counting
 *
 * @return int Number of >= 50 ages.
 */
function age_count():int
{

    // Get JSON
    if (!$text = file_get_contents('https://coderbyte.com/api/challenges/json/age-counting')) { 
        throw new \Exception('Unable to retrieve JSON from server');
    } elseif (!$vars = @json_decode($text, true)) { 
        throw new \Exception('Unable to decode JSON');
    }

    // Go through ages
    $num=0;
    preg_match_all("/age=(\d+)/si", $vars['data'], $age_match, PREG_SET_ORDER);
    foreach ($age_match as $match) { 
        if ((int) $match[1] < 50) { continue; }
        $num++;
    }

    // Return
    return $num;

}

}



