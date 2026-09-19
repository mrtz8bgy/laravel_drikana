<?php

// Build the target from REQUEST_URI only. SCRIPT_NAME can contain a physical
// Windows path under some XAMPP/FastCGI configurations and must not be used in
// a browser URL.
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$requestPath = '/' . ltrim(str_replace('\\', '/', $requestPath), '/');

if (substr($requestPath, -1) === '/') {
	$basePath = rtrim($requestPath, '/');
} else {
	$basePath = rtrim(str_replace('\\', '/', dirname($requestPath)), '/');
}

// Never allow a filesystem-style path to reach the Location header.
if (preg_match('#^[A-Za-z]:#', $basePath)) {
	$basePath = '';
}

$publicPath = ($basePath === '' ? '' : $basePath) . '/public/';

$query = isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] !== ''
	? '?' . $_SERVER['QUERY_STRING']
	: '';

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Location: ' . $publicPath . $query, true, 302);
exit;
