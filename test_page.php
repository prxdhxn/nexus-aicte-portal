<?php
$url = 'http://127.0.0.1:8000/login';
$response = file_get_contents($url, false, stream_context_create([
    'http' => ['ignore_errors' => true]
]));
if ($response === false) {
    echo "ERROR: Could not connect to server\n";
} else {
    echo "HTTP Response Length: " . strlen($response) . " bytes\n";
    // Check for error indicators
    if (strpos($response, 'Whoops') !== false || strpos($response, 'Exception') !== false) {
        echo "PAGE HAS AN ERROR!\n";
        // Extract the error
        preg_match('/<title>(.*?)<\/title>/s', $response, $title);
        echo "Title: " . ($title[1] ?? 'none') . "\n";
        preg_match('/class="exception-message"[^>]*>(.*?)<\/span>/s', $response, $msg);
        echo "Error: " . strip_tags($msg[1] ?? 'not found') . "\n";
    } else {
        echo "Page loaded OK\n";
        preg_match('/<title>(.*?)<\/title>/s', $response, $title);
        echo "Title: " . ($title[1] ?? 'none') . "\n";
    }
    echo "\n--- First 500 chars ---\n";
    echo substr(strip_tags($response), 0, 500);
}
