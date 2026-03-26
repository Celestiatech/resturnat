<?php
declare(strict_types=1);

function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function titleFromSlug(string $slug): string
{
    $parts = preg_split('/[-_]+/', trim($slug)) ?: [];
    $parts = array_map(static function ($part): string {
        return ucfirst(strtolower($part));
    }, array_filter($parts));

    return $parts ? implode(' ', $parts) : 'Restaurant Demo';
}

function acronymFromName(string $name, int $maxLetters = 3): string
{
    $words = preg_split('/[^A-Za-z0-9]+/', trim($name)) ?: [];
    $words = array_values(array_filter($words, static function ($word): bool {
        return $word !== '';
    }));

    if ($words === []) {
        return 'R';
    }

    $letters = '';
    foreach ($words as $word) {
        $letters .= strtoupper(substr($word, 0, 1));
        if (strlen($letters) >= $maxLetters) {
            break;
        }
    }

    if ($letters === '') {
        $compact = preg_replace('/[^A-Za-z0-9]/', '', $name) ?: 'R';
        return strtoupper(substr($compact, 0, $maxLetters));
    }

    return strtoupper(substr($letters, 0, $maxLetters));
}

function shortenBrandName(string $name, int $maxLength = 18): string
{
    $name = trim($name);
    if ($name === '') {
        return 'Restaurant';
    }

    if (strlen($name) <= $maxLength) {
        return $name;
    }

    $words = preg_split('/\s+/', $name) ?: [];
    if (count($words) >= 2) {
        $first = $words[0];
        $second = $words[1];
        $candidate = $first . ' ' . $second;
        if (strlen($candidate) <= $maxLength) {
            return $candidate;
        }
    }

    return acronymFromName($name, 3);
}

function fallbackRestaurant(string $slug): array
{
    $name = titleFromSlug($slug);

    return [
        'restaurant_name' => $name,
        'logo_text' => acronymFromName($name, 3),
        'logo_label' => shortenBrandName($name),
        'tagline' => 'A personalized restaurant website created from your brand name.',
        'hero_badge' => 'This is a sample website created for your restaurant',
        'hero_title' => 'A direct-order website concept for ' . $name,
        'hero_text' => 'This sample shows how ' . $name . ' can look on a premium restaurant website while keeping direct orders, WhatsApp conversion, and stronger branding in focus.',
        'site_button_text' => 'Watch Your Site',
        'phone' => '+910000000000',
        'whatsapp' => '910000000000',
        'email' => 'hello@example.com',
        'address' => 'Your location here',
        'map_url' => 'https://maps.google.com/',
        'cta_text' => 'Order Now',
        'blogs' => [
            [
                'title' => 'How ' . $name . ' Can Get More Direct Orders',
                'excerpt' => 'A personalized restaurant website helps turn visitors into repeat customers.',
            ],
            [
                'title' => 'Why WhatsApp Ordering Works for Restaurants',
                'excerpt' => 'Fast ordering and low friction can make direct sales easier for local food brands.',
            ],
            [
                'title' => 'Turn Marketplace Traffic Into Brand Loyalty',
                'excerpt' => 'Use your own website to keep your restaurant brand in front of customers.',
            ],
            [
                'title' => 'A Better Online Presence for ' . $name,
                'excerpt' => 'Premium presentation and direct CTA buttons help your restaurant look more trustworthy.',
            ],
        ],
        'menu' => [
            ['name' => 'Chef Special Burger', 'description' => 'Sample bestseller item for the demo.', 'price' => 249],
            ['name' => 'House Pasta', 'description' => 'Creamy pasta featured as a direct-order favorite.', 'price' => 299],
            ['name' => 'Loaded Pizza', 'description' => 'Popular pizza choice to make the site feel real.', 'price' => 349],
            ['name' => 'Signature Shake', 'description' => 'A simple add-on item to improve order value.', 'price' => 179],
        ],
    ];
}

function normalizeTemplatePage(?string $page): string
{
    $page = trim((string) $page);
    $page = $page === '' ? 'index.html' : basename($page);

    if (!preg_match('/^[A-Za-z0-9_-]+\.html$/', $page)) {
        return 'index.html';
    }

    return $page;
}

function buildPageUrl(string $slug, string $page = 'index.html'): string
{
    $page = normalizeTemplatePage($page);
    $url = '/vishal/resturnat/swigo.dexignzone.com/?page=' . rawurlencode($page);

    if ($slug !== '') {
        $url .= '&slug=' . rawurlencode($slug);
    }

    return $url;
}

$slug = isset($_GET['slug']) ? strtolower(trim((string) $_GET['slug'])) : '';
$slug = preg_replace('/[^a-z0-9-]/', '', $slug ?? '') ?? '';
$page = normalizeTemplatePage(isset($_GET['page']) ? (string) $_GET['page'] : 'index.html');

$dataPath = __DIR__ . '/data/restaurants.json';
$restaurants = [];
if (is_file($dataPath)) {
    $decoded = json_decode((string) file_get_contents($dataPath), true);
    if (is_array($decoded)) {
        $restaurants = $decoded;
    }
}

$restaurant = $slug !== '' && isset($restaurants[$slug]) && is_array($restaurants[$slug])
    ? $restaurants[$slug]
    : fallbackRestaurant($slug !== '' ? $slug : 'restaurant-demo');

$restaurantName = (string) ($restaurant['restaurant_name'] ?? 'Restaurant Demo');
$defaultLogoText = acronymFromName($restaurantName, 3);
$logoName = strlen($restaurantName) > 20 ? shortenBrandName($restaurantName) : $restaurantName;
$subLogoPath = 'xhtml/assets/icons/logo.png';
$tagline = (string) ($restaurant['tagline'] ?? '');
$heroBadge = (string) ($restaurant['hero_badge'] ?? '');
$heroTitle = (string) ($restaurant['hero_title'] ?? '');
$heroText = (string) ($restaurant['hero_text'] ?? '');
$siteButtonText = (string) ($restaurant['site_button_text'] ?? 'Watch Your Site');
$phone = preg_replace('/[^0-9+]/', '', (string) ($restaurant['phone'] ?? ''));
$whatsapp = preg_replace('/[^0-9]/', '', (string) ($restaurant['whatsapp'] ?? ''));
$email = (string) ($restaurant['email'] ?? 'hello@example.com');
$address = (string) ($restaurant['address'] ?? '');
$mapUrl = (string) ($restaurant['map_url'] ?? 'https://maps.google.com/');
$ctaText = (string) ($restaurant['cta_text'] ?? 'Order Now');
$blogs = isset($restaurant['blogs']) && is_array($restaurant['blogs']) ? array_values($restaurant['blogs']) : [];
$menu = isset($restaurant['menu']) && is_array($restaurant['menu']) ? array_values($restaurant['menu']) : [];
$homeUrl = buildPageUrl($slug, 'index.html');
$currentUrl = buildPageUrl($slug, $page);
$whatsappHref = $whatsapp !== '' ? 'https://wa.me/' . $whatsapp . '?text=' . rawurlencode('Hi, I want to order from ' . $restaurantName) : '#';
$emailHref = 'mailto:' . $email;
$supportPhone = '9805559015';
$supportDialHref = 'tel:' . $supportPhone;
$supportWhatsappHref = 'https://wa.me/91' . $supportPhone . '?text=' . rawurlencode('Hi, I want to know more about the website.');

$templatePath = __DIR__ . '/xhtml/' . $page;
$pageExists = is_file($templatePath);
$templatePath = $pageExists ? $templatePath : (__DIR__ . '/xhtml/index.html');
$html = is_file($templatePath) ? (string) file_get_contents($templatePath) : '';

if ($html === '') {
    http_response_code(500);
    echo 'Main template not found.';
    exit;
}

$headInsert = <<<HTML
<style>
.dynamic-logo-wrap{
    display:inline-flex;
    align-items:center;
    gap:10px;
    max-width:240px;
}
.dynamic-sub-logo{
    width:46px;
    height:46px;
    min-width:46px;
    display:block;
    object-fit:contain;
}
.dynamic-logo-copy{
    display:flex;
    align-items:center;
    justify-content:center;
    min-width:0;
}
.dynamic-logo-name{
    font-size:16px;
    font-weight:700;
    color:#000;
    font-style:italic;
    letter-spacing:0 !important;
    text-transform:none;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}
.logo-header .dynamic-logo-wrap span{
    letter-spacing:0 !important;
}
.logo-header .dynamic-logo-name{
    letter-spacing:0 !important;
}
.site-header .dynamic-logo-name,
.is-fixed .dynamic-logo-name,
.header-nav .dynamic-logo-name{
    color:#000;
}
.logo-header.mostion{
    width:auto;
    min-width:0;
    max-width:240px;
}
.logo-header .dynamic-brand-logo{
    display:flex;
    align-items:center;
    height:100%;
    padding-top:0;
}
.dynamic-brand-logo:after{
    display:none !important;
}
@media only screen and (max-width: 991px) {
    .logo-header.mostion{
        max-width:200px;
    }
    .dynamic-logo-name{
        font-size:15px;
    }
}
@media only screen and (max-width: 575px) {
    .logo-header.mostion{
        max-width:170px;
    }
    .dynamic-sub-logo{
        width:40px;
        height:40px;
        min-width:40px;
    }
}
.sample-note{
    display:inline-block;
    padding:10px 18px;
    border-radius:999px;
    background:rgba(255,255,255,.14);
    color:#fff;
    font-weight:600;
    margin-bottom:18px;
}
.loading-page-3{
    display:none !important;
}
</style>
HTML;

$logoMarkup = '<div class="dynamic-logo-wrap"><img class="dynamic-sub-logo" src="' . h($subLogoPath) . '" alt="' . h($restaurantName) . ' logo"><div class="dynamic-logo-copy"><span class="dynamic-logo-name">' . h($logoName) . '</span></div></div>';
$sampleNote = '<span class="sample-note">' . h($heroBadge) . '</span>';

$html = str_replace('</head>', $headInsert . "\n</head>", $html);
$html = str_replace('<title>Restaurant Website Templates | Swigo - Empowering Your Food Business | DexignZone</title>', '<title>' . h($restaurantName) . ' | Sample Restaurant Website</title>', $html);
$html = str_replace('href="assets/', 'href="xhtml/assets/', $html);
$html = str_replace('src="assets/', 'src="xhtml/assets/', $html);

$html = str_replace('<a href="index.html" class="anim-logo"><img src="xhtml/assets/images/logo.png" alt="/"></a>', '<a href="' . h($homeUrl) . '" class="anim-logo dynamic-brand-logo">' . $logoMarkup . '</a>', $html);
$html = str_replace('<a href="index.html" class="anim-logo"><img src="assets/images/logo.png" alt="/"></a>', '<a href="' . h($homeUrl) . '" class="anim-logo dynamic-brand-logo">' . $logoMarkup . '</a>', $html);
$html = str_replace('<a href="index.html" class="anim-logo-white"><img src="xhtml/assets/images/logo2.png" alt="/"></a>', '<a href="' . h($homeUrl) . '" class="anim-logo-white dynamic-brand-logo">' . $logoMarkup . '</a>', $html);
$html = str_replace('<a href="index.html" class="anim-logo-white"><img src="assets/images/logo2.png" alt="/"></a>', '<a href="' . h($homeUrl) . '" class="anim-logo-white dynamic-brand-logo">' . $logoMarkup . '</a>', $html);

$html = preg_replace_callback(
    '/href="([^"]+)"/i',
    static function (array $matches) use ($slug): string {
        $href = html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');

        if ($href === '' || preg_match('/^(?:https?:|mailto:|tel:|javascript:|#)/i', $href)) {
            return $matches[0];
        }

        $path = parse_url($href, PHP_URL_PATH);
        if (!is_string($path) || $path === '') {
            return $matches[0];
        }

        $pageName = basename($path);
        if (!preg_match('/^[A-Za-z0-9_-]+\.html$/', $pageName)) {
            return $matches[0];
        }

        return 'href="' . h(buildPageUrl($slug, $pageName)) . '"';
    },
    $html
) ?? $html;

$headerActions = '<div class="extra-nav"><div class="extra-cell"><ul><li><a href="' . h($supportDialHref) . '" class="btn btn-white btn-shadow btn-hover-1"><span>Support</span></a></li><li><a href="' . h($supportWhatsappHref) . '" target="_blank" class="btn btn-primary btn-hover-1"><span>Buy Now</span></a></li></ul></div></div>';
$html = preg_replace('/<div class="extra-nav">.*?<\/div>\s*<\/div>\s*<\/div>\s*<\/div>\s*<!-- Main Header End -->/s', $headerActions . "\n\t\t\t\t\t</div>\n\t\t\t\t</div>\n\t\t\t</div>\n\t\t\t<!-- Main Header End -->", $html, 1) ?? $html;

$html = preg_replace('/<div class="banner-content">/', '<div class="banner-content">' . $sampleNote, $html, 1) ?? $html;

$heroReplacements = [
    'High Quality Test Station ' => h($tagline),
    'The Best Food Stations' => h($tagline),
    'Exploring the Delicious World' => h($tagline),
    'Choosing The<br> Best <span class="text-primary">Quality Food</span>' => h($heroTitle),
    'Where Food <br> Meets<span class="text-primary"> Best Passion</span>' => h($heroTitle),
    'Delicious Eats <br> And  <span class="text-primary">Tasty Drinks</span>' => h($heroTitle),
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.' => h($heroText),
    'Quality Services' => 'Why Guests Choose ' . h($logoName),
    'Lorem ipsum dolor sit amet, dipiscing elit, sed' => 'Prepared fresh, served fast, and built to keep guests coming back.',
    'From Our Menu' => 'Popular Favorites',
    'Reservation' => 'Reserve A Table',
    'Customer\'s Comment' => 'What Guests Say',
    'Master Chefs' => 'Kitchen Highlights',
    'News & blog' => 'Latest Stories',
    'Read More' => 'View Story',
];
$html = strtr($html, $heroReplacements);

$html = str_replace('Lorem ipsum dolor sit amet, consectetur adipiscing elit', 'Crafted to look premium, feel welcoming, and make ordering easier', $html);
$html = str_replace('Lorem ipsum dolor sit amet consectetur adipiscing.', 'A bestseller presentation designed to feel ready for real customers.', $html);
$html = str_replace('<h5 class="dz-title">Restaurant</h5>', '<h5 class="dz-title">Fresh Kitchen</h5>', $html);
$html = str_replace('<h5 class="dz-title">Bar</h5>', '<h5 class="dz-title">Fast Ordering</h5>', $html);
$html = str_replace('<h5 class="dz-title">Cafe</h5>', '<h5 class="dz-title">Signature Drinks</h5>', $html);
$html = str_replace('<h5 class="dz-title">Dessert</h5>', '<h5 class="dz-title">Sweet Finish</h5>', $html);
$html = str_replace('My Hub Fresh Kitchen', h($restaurantName), $html);
$html = str_replace('Fresh Kitchen Website', 'Restaurant Website', $html);
$html = str_replace('Fresh Kitchen Website Templates', 'Restaurant Website Templates', $html);
$html = str_replace('Side Fast Ordering', 'Side Bar', $html);

$testimonialMap = [
    'John Doe' => 'Aarav Mehta',
    'Jolly Roy' => 'Priya Soni',
    'Thomas Hed' => 'Rahul Verma',
    'Kally Mint' => 'Sneha Kapoor',
    'Ronny joy' => 'Manav Shah',
    'Dolly kom' => 'Ritika Jain',
    'Food Expert' => 'Regular Guest',
    'Food Tester' => 'Online Customer',
    'Assistant' => 'Weekend Diner',
];
$html = strtr($html, $testimonialMap);

$html = str_replace(
    'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text.',
    'Loved the food presentation, simple ordering flow, and the overall premium feel of the website. It makes the restaurant look trustworthy and ready for direct orders.',
    $html
);

$html = str_replace('<span>Book a Table</span>', '<span>' . h($siteButtonText) . '</span>', $html);
$html = str_replace('href="contact-us.html"', 'href="' . h($currentUrl) . '"', $html);
$html = str_replace('<span>View More</span>', '<span>' . h($ctaText) . '</span>', $html);
$html = str_replace('href="about-us.html"', 'href="' . h($whatsappHref) . '" target="_blank"', $html);

if (count($menu) >= 4) {
    $defaults = [
        ['Pasta', '$35.00', '$35.0', 'Lorem ipsum dolor sit amet, dipiscing elit, sed', 'Lorem ipsum dolor sit amet consectetur adipiscing.'],
        ['Oreo Shake', '$55.00', '$55.00', 'Lorem ipsum dolor sit amet, dipiscing elit, sed', 'Lorem ipsum dolor sit amet, dipiscing elit, sed'],
        ['Dal Fry', '$25.00', '$25.00', 'Lorem ipsum dolor sit amet, dipiscing elit, sed', 'Lorem ipsum dolor sit amet, dipiscing elit, sed'],
        ['Pizza', '$90.00', '$90.00', 'Lorem ipsum dolor sit amet, dipiscing elit, sed', 'Lorem ipsum dolor sit amet, dipiscing elit, sed'],
    ];

    foreach ($defaults as $index => $default) {
        $item = $menu[$index] ?? null;
        if (!is_array($item)) {
            continue;
        }
        $newName = h((string) ($item['name'] ?? $default[0]));
        $newDescription = h((string) ($item['description'] ?? $default[3]));
        $newPrice = 'Rs. ' . number_format((float) ($item['price'] ?? 0), 0);

        $html = preg_replace('/' . preg_quote($default[0], '/') . '/', $newName, $html, 2) ?? $html;
        $html = preg_replace('/' . preg_quote($default[1], '/') . '/', $newPrice, $html, 1) ?? $html;
        if ($default[2] !== $default[1]) {
            $html = preg_replace('/' . preg_quote($default[2], '/') . '/', $newPrice, $html, 1) ?? $html;
        } else {
            $html = preg_replace('/' . preg_quote($default[2], '/') . '/', $newPrice, $html, 1) ?? $html;
        }
        $html = preg_replace('/' . preg_quote($default[3], '/') . '/', $newDescription, $html, 1) ?? $html;
        $html = preg_replace('/' . preg_quote($default[4], '/') . '/', $newDescription, $html, 1) ?? $html;
    }
}

$html = str_replace('<h2 class="title wow flipInX" data-wow-delay="0.2s">Today\'s Menu</h2>', '<h2 class="title wow flipInX" data-wow-delay="0.2s">' . h($restaurantName) . ' Menu</h2>', $html);
$html = str_replace('<a href="our-menu-2.html" class="btn btn-md btn-primary btn-hover-1"><span>See All Dishes</span></a>', '<a href="' . h($whatsappHref) . '" target="_blank" class="btn btn-md btn-primary btn-hover-1"><span>' . h($ctaText) . '</span></a>', $html);

if (count($blogs) >= 4) {
    $blogDefaults = [
        ['Taste of Paradise Dishes', 'There are many variations of passages of Lorem Ipsum available, but the majority have.'],
        ['The Spices Route Taste', 'There are many variations of passages of Lorem Ipsum available, but the majority have.'],
        ['The Fork & Knife', 'There are many variations of passages of Lorem Ipsum available, but the majority have.'],
        ['Flavors Of The World', 'There are many variations of passages of Lorem Ipsum available, but the majority have.'],
    ];

    foreach ($blogDefaults as $index => $defaultBlog) {
        $blog = $blogs[$index] ?? null;
        if (!is_array($blog)) {
            continue;
        }

        $blogTitle = h((string) ($blog['title'] ?? $defaultBlog[0]));
        $blogExcerpt = h((string) ($blog['excerpt'] ?? $defaultBlog[1]));

        $html = preg_replace('/' . preg_quote($defaultBlog[0], '/') . '/', $blogTitle, $html, 2) ?? $html;
        $html = preg_replace('/' . preg_quote($defaultBlog[1], '/') . '/', $blogExcerpt, $html, 2) ?? $html;
    }
}

$html = str_replace('[Not discloused yet]', h($address), $html);
$html = str_replace("[Not discloused yet]<br>\n\t\t\t\t\t\t\t\t\t\t[Not discloused yet]", h($phone), $html);
$html = str_replace('<h5 class="footer-title">Contact</h5>', '<h5 class="footer-title">' . h($restaurantName) . ' Contact</h5>', $html);
$html = str_replace('<p>' . h($address) . '</p>', '<p><a href="' . h($mapUrl) . '" target="_blank">' . h($address) . '</a></p>', $html);
$html = preg_replace('/<p><a href="https:\/\/swigo\.dexignzone\.com\/cdn-cgi\/l\/email-protection".*?<\/p>/s', '<p><a href="' . h($emailHref) . '">' . h($email) . '</a></p>', $html, 1) ?? $html;
$html = str_replace('<li><a href="' . h($currentUrl) . '"><span>Contact Us</span></a></li>', '<li><a href="' . h($mapUrl) . '" target="_blank"><span>Open Map</span></a></li>', $html);

$html = str_replace('href="index.html#" target="_blank" class="btn btn-primary btn-rounded DZBuyNowBtn btn-hover-1"><span>Buy Now</span></a>', 'href="' . h($supportWhatsappHref) . '" target="_blank" class="btn btn-primary btn-rounded DZBuyNowBtn btn-hover-1"><span>Buy Now</span></a>', $html);
$html = str_replace('href="index.html#" target="_blank" class="btn btn-secondary  btn-lg btn-rounded DZBuyNowBtn btn-hover-1"><span>Buy Now</span></a>', 'href="' . h($supportWhatsappHref) . '" target="_blank" class="btn btn-secondary  btn-lg btn-rounded DZBuyNowBtn btn-hover-1"><span>Buy Now</span></a>', $html);
$html = str_replace('href="index.html#" target="_blank" class="btn btn-primary btn-rounded m-r10 button-md DZBuyNowBtn btn-hover-1"><span>Buy Now</span></a>', 'href="' . h($supportWhatsappHref) . '" target="_blank" class="btn btn-primary btn-rounded m-r10 button-md DZBuyNowBtn btn-hover-1"><span>Buy Now</span></a>', $html);
$html = str_replace('href="https://support.w3itexperts.com" target="_blank" class="btn btn-secondary btn-rounded btn-hover-1"><span><i class="fa fa-envelope-o m-r5"></i> Support</span></a>', 'href="' . h($supportDialHref) . '" class="btn btn-secondary btn-rounded btn-hover-1"><span><i class="fa fa-phone m-r5"></i> Support</span></a>', $html);

echo $html;
