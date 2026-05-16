<?php

// Test basic TCP connectivity first
$host = 'ac-pgmmpou-shard-00-00.yvxdmsk.mongodb.net';
$port = 27017;

echo "Testing TCP connection to $host:$port...\n";
$conn = @fsockopen($host, $port, $errno, $errstr, 5);
if ($conn) {
    echo "TCP Connection: SUCCESS\n";
    fclose($conn);
} else {
    echo "TCP Connection: FAILED - $errno: $errstr\n";
}

// Test TLS connection  
echo "\nTesting TLS connection...\n";
$ctx = stream_context_create([
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
    ]
]);
$fp = @stream_socket_client("tls://$host:$port", $errno, $errstr, 5, STREAM_CLIENT_CONNECT, $ctx);
if ($fp) {
    echo "TLS Connection: SUCCESS\n";
    fclose($fp);
} else {
    echo "TLS Connection: FAILED - $errno: $errstr\n";
}

echo "\nOpenSSL Version: " . OPENSSL_VERSION_TEXT . "\n";
echo "PHP Version: " . PHP_VERSION . "\n";
