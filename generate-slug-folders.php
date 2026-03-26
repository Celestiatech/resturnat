<?php
declare(strict_types=1);

$dataPath = __DIR__ . '/data/restaurants.json';
if (!is_file($dataPath)) {
    fwrite(STDERR, "Missing restaurants.json\n");
    exit(1);
}

$data = json_decode((string) file_get_contents($dataPath), true);
if (!is_array($data)) {
    fwrite(STDERR, "Invalid restaurants.json\n");
    exit(1);
}

foreach (array_keys($data) as $slug) {
    if (!is_string($slug) || $slug === '' || preg_match('/[^a-z0-9-]/', $slug)) {
        continue;
    }

    $dir = __DIR__ . '/' . $slug;
    if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir)) {
        fwrite(STDERR, "Failed to create directory for {$slug}\n");
        continue;
    }

    $content = "<?php\n\$_GET['slug'] = '" . addslashes($slug) . "';\nrequire dirname(__DIR__) . '/index.php';\n";
    file_put_contents($dir . '/index.php', $content);
    echo "Generated {$slug}/index.php\n";
}
