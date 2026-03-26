<?php
declare(strict_types=1);

$dataPath = __DIR__ . '/data/restaurants.json';
if (!is_file($dataPath)) {
    fwrite(STDERR, "Missing restaurants.json\n");
    exit(1);
}

$decoded = json_decode((string) file_get_contents($dataPath), true);
if (!is_array($decoded)) {
    fwrite(STDERR, "Invalid restaurants.json\n");
    exit(1);
}

$templateFiles = glob(__DIR__ . '/xhtml/*.html') ?: [];
$templatePages = [];

foreach ($templateFiles as $templateFile) {
    $page = basename($templateFile);
    if ($page === '' || strpos($page, '?') !== false) {
        continue;
    }

    $templatePages[] = $page;
}

foreach (array_keys($decoded) as $slug) {
    if (!is_string($slug) || $slug === '' || preg_match('/[^a-z0-9-]/', $slug)) {
        continue;
    }

    $dir = __DIR__ . '/' . $slug;
    if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir)) {
        fwrite(STDERR, "Failed to create {$slug}\n");
        continue;
    }

    $content = "<?php\n\$_GET['slug'] = '" . addslashes($slug) . "';\n\$_GET['page'] = 'index.html';\nrequire dirname(__DIR__) . '/index.php';\n";
    file_put_contents($dir . '/index.php', $content);

    foreach ($templatePages as $page) {
        $target = '../index.php?slug=' . rawurlencode($slug) . '&page=' . rawurlencode($page);
        $html = "<!DOCTYPE html>\n<html lang=\"en\">\n<head>\n<meta charset=\"utf-8\">\n<meta http-equiv=\"refresh\" content=\"0; url={$target}\">\n<link rel=\"canonical\" href=\"{$target}\">\n<script>location.replace(" . json_encode($target) . ")</script>\n<title>Redirecting...</title>\n</head>\n<body>\n<p>Redirecting to <a href=\"{$target}\">{$target}</a>...</p>\n</body>\n</html>\n";
        file_put_contents($dir . '/' . $page, $html);
    }

    echo "Generated {$slug}/index.php and " . count($templatePages) . " page wrappers\n";
}
