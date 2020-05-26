<?php
declare(strict_types = 1);

namespace coredna;


/**
 * This class handles sending of HTTP requests without 
 * cURL, as requested by Mike over at CoreDNA.
 */
class http_client
{


/**
 * Send HTTP request
 *
 * @param string $url The URL of the request.
 * @param string $method Method of the request, defaults to GET
 * @param array $json_data Associative array of the JSON variables to send.
 * @param array $headers Optional associative array of HTTP headers to send.
 *
 * @return array Associative array of the response with three keys for status, headers, and decoded JSON body.  Returns false on failure.
 */
public function send_http_request(string $url, string $method = 'GET', array $json_data = [], array $headers = [])
{

    // Parse URL
    if (!preg_match("/^http(s?):\/\/(.+)$/", strtolower($url), $match)) { 
        throw new \Exception("Invalid URL supplied, $url");
    }

    // Get host / URI
    if (preg_match("/\//", $match[2])) { 
        list($host, $uri) = explode('/', $match[2], 2);
    } else { 
        list($host, $uri) = array($match[2], '');
    }

    // Check for SSL
    if ($match[1] == 's') { 
        $connect_host = 'ssl://' . $host;
        $port = 443;
    } else { 
        $connect_host = $host;
        $port = 80;
    }

    // Connect to remote host
    if (!$sock = @fsockopen($connect_host, $port, $errno, $errstr, 5)) { 
        throw new \Exception("Unable to connect to remote host, $host on port $port");
    }
    $request = json_encode($json_data);

    // Send HTTP request
    fwrite($sock, "$method /$uri HTTP/1.1\r\n");
    fwrite($sock, "Host: $host\r\n");
    fwrite($sock, "User-agent: CoreDNA/1.0\r\n");
    fwrite($sock, "Connection: close\r\n");

    // Add custom headers
    foreach ($headers as $key => $value) { 
        fwrite($sock, $key . ': ' . $value . "\r\n");
    }

    // Add POST fields, if needed
    if (strtoupper($method) == 'POST') { 
        fwrite($sock, "Content-type: application/json\r\n");
        fwrite($sock, "Content-length: " . strlen($request) . "\r\n\r\n");
        fwrite($sock, "$request\r\n");
    } else { 
        fwrite($sock, "\r\n");
    }

    // Get response
    $response = '';
    while (!feof($sock)) { 
        $response .= fread($sock, 1024); 
    }
    fclose($sock);

    // Check for empty response
    if (empty($response)) { 
        throw new \Exception("Did not receive a response from the remote URL, $url");
    }

    // Split response into header / body
    list($header, $body) = explode("\n\n", str_replace("\r", "", $response), 2);
    $header_lines = explode("\n", $header);
    $status_line = array_shift($header_lines);

    // Check HTTP status
    if (!preg_match("/HTTP\/(.+?)\s(\d+)/i", $status_line, $match)) { 
        throw new \Exception("Unable to recognize status line from HTTP response, $status_line");
    } elseif (preg_match("/^(4|5)/", $match[2])) { 
        throw new \Exception("Invalid HTTP status received from remote server, $match[2]");
    }
    $status = $match[2];

    /**
     * Parse headers, according to PSR7, each value must be an array to allow for 
     * multiple header lines with the same key, and also must be case insensitive.
     */
    $headers = [];
    foreach ($header_lines as $line) { 
        list($key, $value) = explode(':', $line, 2);
        $key = strtolower($key);

        if (!isset($headers[$key])) {
            $headers[$key] = [$value];
        } else { 
            $headers[$key][] = $value;
        }
    }

    // Decode response body, if JSON
    $content_type = isset($headers['content-type']) ? $headers['content-type'][0] : '';
    if ($content_type == 'application/json') { 

        if (!$data = @json_decode($body, true)) { 
            throw new \Exception("Unable to decode JSON response from the URL, $url");
        }
    } else {        $data = $body;
    }

    // Set response
    $response = [
        'status' => $status, 
        'headers' => $headers, 
        'data' => $data
    ];

    // Return
    return $response;

}

}



