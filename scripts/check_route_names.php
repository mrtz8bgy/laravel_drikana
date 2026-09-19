<?php
$base = __DIR__ . '/..';
chdir($base);
$routes = [];
exec('php artisan route:list --json', $out, $code);
if ($code !== 0) {
    fwrite(STDERR, "route:list failed\n");
    exit($code);
}
$data = json_decode(implode("\n", $out), true);
if (!is_array($data)) {
    fwrite(STDERR, "route:list output not valid json\n");
    exit(1);
}
foreach ($data as $route) {
    if (!empty($route['name'])) {
        $routes[$route['name']] = true;
    }
}
$names = [];
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base . '/resources'));
foreach ($iterator as $file) {
    if (!$file->isFile()) continue;
    $path = $file->getPathname();
    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    if (!in_array($ext, ['php', 'blade'], true)) continue;
    $content = file_get_contents($path);
    if ($content === false) continue;
    preg_match_all('/route\s*\(\s*[\'\"]([^\'\"]+)[\'\"]/s', $content, $matches);
    foreach ($matches[1] as $name) {
        $names[$name] = true;
    }
}
$missing = [];
foreach ($names as $name => $_) {
    if (!isset($routes[$name])) {
        $missing[] = $name;
    }
}
sort($missing);
if ($missing) {
    echo "MISSING_ROUTE_NAMES\n";
    foreach ($missing as $name) {
        echo $name, "\n";
    }
    echo "COUNT=" . count($missing) . "\n";
} else {
    echo "NO_MISSING_ROUTE_NAMES\n";
    echo "TOTAL=" . count($names) . "\n";
}
