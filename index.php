<?php
declare(strict_types=1);

function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function loadRestaurants(string $path): array
{
    if (!is_file($path)) {
        return [];
    }

    $json = file_get_contents($path);
    if ($json === false) {
        return [];
    }

    $decoded = json_decode($json, true);
    return is_array($decoded) ? $decoded : [];
}

function titleFromSlug(string $slug): string
{
    $slug = trim($slug);
    if ($slug === '') {
        return 'Restaurant Demo';
    }

    $parts = preg_split('/[-_]+/', $slug) ?: [];
    $parts = array_map(static function ($part): string {
        return ucfirst(strtolower($part));
    }, array_filter($parts));

    return $parts ? implode(' ', $parts) : 'Restaurant Demo';
}

function buildFallbackRestaurant(string $slug): array
{
    $name = titleFromSlug($slug);

    return [
        'restaurant_name' => $name,
        'tagline' => 'A personalized restaurant website sample designed from your brand name.',
        'description' => 'This demo shows how ' . $name . ' can have branded direct orders, WhatsApp leads, and a better online presence from one simple website.',
        'phone' => '+910000000000',
        'email' => '',
        'whatsapp' => '910000000000',
        'address' => 'Your restaurant address here',
        'maps_url' => '',
        'zomato_url' => 'https://www.zomato.com/',
        'swiggy_url' => 'https://www.swiggy.com/',
        'cta_text' => 'Order Now',
        'hero_badge' => 'This is a sample website created for your restaurant',
        'menu' => [
            [
                'name' => 'Chef Special Burger',
                'description' => 'A sample best-seller that can be replaced with your actual menu.',
                'price' => 249,
                'image' => '/swigo.dexignzone.com/xhtml/assets/images/shop/pic2.jpg',
            ],
            [
                'name' => 'Signature Pasta',
                'description' => 'Creamy pasta made for demo purposes with your branding.',
                'price' => 299,
                'image' => '/swigo.dexignzone.com/xhtml/assets/images/gallery/grid2/pic2.jpg',
            ],
            [
                'name' => 'House Pizza',
                'description' => 'A direct-order friendly hero item for local promotion campaigns.',
                'price' => 349,
                'image' => '/swigo.dexignzone.com/xhtml/assets/images/gallery/grid2/pic6.jpg',
            ],
            [
                'name' => 'Cold Coffee',
                'description' => 'A sample beverage item to round out the showcase menu.',
                'price' => 159,
                'image' => '/swigo.dexignzone.com/xhtml/assets/images/gallery/grid2/pic5.jpg',
            ],
        ],
    ];
}

$slug = isset($_GET['slug']) ? strtolower(trim((string) $_GET['slug'])) : '';
$slug = preg_replace('/[^a-z0-9-]/', '', $slug ?? '') ?? '';

$restaurants = loadRestaurants(__DIR__ . '/data/restaurants.json');
$restaurant = $slug !== '' && isset($restaurants[$slug]) && is_array($restaurants[$slug])
    ? $restaurants[$slug]
    : ($slug !== '' ? buildFallbackRestaurant($slug) : null);

if ($restaurant === null) {
    http_response_code(200);
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restaurant Demo Generator</title>
    <link rel="icon" type="image/png" href="/swigo.dexignzone.com/xhtml/assets/images/favicon.png">
    <link href="/swigo.dexignzone.com/xhtml/assets/vendor/animate/animate.css" rel="stylesheet">
    <link href="/swigo.dexignzone.com/xhtml/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link href="/swigo.dexignzone.com/xhtml/assets/vendor/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="/swigo.dexignzone.com/xhtml/assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="page-wraper">
    <section class="content-inner-1 overflow-hidden" style="background-image:url('/swigo.dexignzone.com/xhtml/assets/images/background/pic1.png'); background-size:cover; min-height:100vh;">
        <div class="container" style="padding-top:100px; padding-bottom:100px;">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="section-head">
                        <span class="badge" style="background:#ffede1; color:#f54900; padding:12px 18px; border-radius:30px;">Dynamic Restaurant Demo Links</span>
                        <h1 class="title" style="max-width:700px;">One template. Unlimited restaurant demo pages.</h1>
                        <p class="m-b30">Open any slug like <strong>/myhubrestaurant</strong> or <strong>/royal-biryani-house</strong> and the page can automatically change the restaurant name, text logo, menu, CTA, phone, and WhatsApp button.</p>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="/myhubrestaurant" class="btn btn-primary btn-hover-1"><span>Open Demo 1</span></a>
                            <a href="/royal-biryani-house" class="btn btn-outline-primary btn-hover-1"><span>Open Demo 2</span></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="p-4 bg-white rounded-4 shadow-sm">
                        <h4 class="m-b15">How to scale this</h4>
                        <p class="m-b10">1. Add restaurant data in <code>data/restaurants.json</code></p>
                        <p class="m-b10">2. Share links like <code>/restaurant-name</code></p>
                        <p class="m-b0">3. Use these in cold outreach to show a personalized sample website</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
</body>
</html>
    <?php
    exit;
}

$restaurantName = (string) ($restaurant['restaurant_name'] ?? titleFromSlug($slug));
$tagline = (string) ($restaurant['tagline'] ?? '');
$description = (string) ($restaurant['description'] ?? '');
$ctaText = (string) ($restaurant['cta_text'] ?? 'Order Now');
$heroBadge = (string) ($restaurant['hero_badge'] ?? 'This is a sample website created for your restaurant');
$menu = isset($restaurant['menu']) && is_array($restaurant['menu']) ? $restaurant['menu'] : [];
$zomatoUrl = (string) ($restaurant['zomato_url'] ?? 'https://www.zomato.com/');
$swiggyUrl = (string) ($restaurant['swiggy_url'] ?? 'https://www.swiggy.com/');
$logoText = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $restaurantName) ?: 'R', 0, 2));
$contactPlaceholder = '[Not disclosed yet]';
$phoneRaw = preg_replace('/[^0-9+]/', '', (string) ($restaurant['phone'] ?? ''));
$emailRaw = trim((string) ($restaurant['email'] ?? ''));
$addressRaw = trim((string) ($restaurant['address'] ?? ''));
$mapsUrlRaw = trim((string) ($restaurant['maps_url'] ?? ''));
$whatsappRaw = preg_replace('/[^0-9]/', '', (string) ($restaurant['whatsapp'] ?? ''));

$phone = $phoneRaw !== '' ? $phoneRaw : $contactPlaceholder;
$email = $emailRaw !== '' ? $emailRaw : $contactPlaceholder;
$address = $addressRaw !== '' ? $addressRaw : $contactPlaceholder;
$phoneHref = $phoneRaw !== '' ? 'tel:' . $phoneRaw : '#contact';
$whatsAppHref = $whatsappRaw !== '' ? 'https://wa.me/' . $whatsappRaw . '?text=' . rawurlencode('Hi, I want to order from ' . $restaurantName) : '#contact';
$mapQuery = trim($restaurantName . ' ' . $addressRaw);
$mapUrl = $mapsUrlRaw !== '' ? $mapsUrlRaw : ($mapQuery !== '' ? 'https://www.google.com/maps?q=' . rawurlencode($mapQuery) : 'https://www.google.com/maps?q=Chandigarh');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo h($restaurantName); ?> | Direct Order Demo</title>
    <meta name="description" content="<?php echo h($description); ?>">
    <link rel="icon" type="image/png" href="/swigo.dexignzone.com/xhtml/assets/images/favicon.png">
    <link href="/swigo.dexignzone.com/xhtml/assets/vendor/animate/animate.css" rel="stylesheet">
    <link href="/swigo.dexignzone.com/xhtml/assets/vendor/magnific-popup/magnific-popup.min.css" rel="stylesheet">
    <link href="/swigo.dexignzone.com/xhtml/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link href="/swigo.dexignzone.com/xhtml/assets/vendor/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="/swigo.dexignzone.com/xhtml/assets/vendor/tempus-dominus/css/tempus-dominus.min.css" rel="stylesheet">
    <link href="/swigo.dexignzone.com/xhtml/assets/css/style.css" rel="stylesheet">
    <style>
        .dynamic-logo {
            width: 54px;
            height: 54px;
            border-radius: 16px;
            background: linear-gradient(135deg, #f54900, #ffb347);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            letter-spacing: 1px;
            box-shadow: 0 12px 30px rgba(245, 73, 0, 0.22);
        }
        .dynamic-logo-wrap {
            display: inline-flex;
            align-items: center;
            gap: 12px;
        }
        .dynamic-logo-name {
            font-size: 20px;
            font-weight: 700;
            color: #1f1f1f;
            line-height: 1.1;
        }
        .hero-note {
            display: inline-block;
            padding: 10px 16px;
            border-radius: 999px;
            background: #fff1e8;
            color: #f54900;
            font-weight: 600;
            margin-bottom: 18px;
        }
        .comparison-card {
            padding: 28px;
            border-radius: 24px;
            background: #fff;
            box-shadow: 0 18px 50px rgba(31, 31, 31, 0.08);
            height: 100%;
        }
        .comparison-card h4 {
            margin-bottom: 14px;
        }
        .comparison-card ul {
            margin: 0;
            padding-left: 18px;
        }
        .comparison-card li {
            margin-bottom: 8px;
        }
        .cta-strip {
            background: linear-gradient(135deg, rgba(245, 73, 0, 0.95), rgba(255, 179, 71, 0.95));
            border-radius: 28px;
            padding: 42px;
        }
        .mini-pill {
            display: inline-block;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(255,255,255,0.14);
            color: #fff;
            font-size: 14px;
            margin-bottom: 12px;
        }
    </style>
</head>
<body id="bg">
<div class="page-wraper">
    <header class="site-header header-transparent transparent-white style-1">
        <div class="main-bar-wraper sticky-header">
            <div class="main-bar clearfix">
                <div class="container inner-bar clearfix">
                    <div class="dynamic-logo-wrap">
                        <span class="dynamic-logo"><?php echo h($logoText); ?></span>
                        <div class="dynamic-logo-name"><?php echo h($restaurantName); ?></div>
                    </div>
                    <div class="extra-nav">
                        <div class="extra-cell">
                            <ul>
                                <li><a href="<?php echo h($mapUrl); ?>" target="_blank" class="btn btn-white btn-shadow"><span>View Map</span></a></li>
                                <li><a href="<?php echo h($phoneHref); ?>" class="btn btn-primary btn-hover-1"><span><?php echo h($phoneRaw !== '' ? 'Call Now' : 'Contact Soon'); ?></span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="content-inner-1 overflow-hidden" style="background-image:url('/swigo.dexignzone.com/xhtml/assets/images/background/pic1.png'); background-size:cover; padding-top:170px;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <span class="hero-note"><?php echo h($heroBadge); ?></span>
                    <h1 class="title"><?php echo h($restaurantName); ?></h1>
                    <h4 class="text-primary m-b20"><?php echo h($tagline); ?></h4>
                    <p class="m-b30"><?php echo h($description); ?></p>
                    <div class="d-flex flex-wrap gap-3 m-b20">
                        <a href="<?php echo h($whatsAppHref); ?>" target="_blank" class="btn btn-primary btn-hover-1"><span><?php echo h($ctaText); ?></span></a>
                        <a href="<?php echo h($phoneHref); ?>" class="btn btn-outline-primary btn-hover-1"><span><?php echo h($phone); ?></span></a>
                    </div>
                    <p class="m-b0"><strong>Demo URL:</strong> <?php echo h('/' . $slug); ?></p>
                </div>
                <div class="col-lg-6">
                    <img src="/swigo.dexignzone.com/xhtml/assets/images/main-slider/slider1/pic1.png" alt="<?php echo h($restaurantName); ?>">
                </div>
            </div>
        </div>
    </section>

    <section class="content-inner-2">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="comparison-card">
                        <h4>Currently: Only on Zomato / Swiggy</h4>
                        <ul>
                            <li>Customers order through third-party apps</li>
                            <li>Your brand competes beside many other restaurants</li>
                            <li>Limited direct customer relationship</li>
                            <li>Harder to capture repeat buyers outside marketplaces</li>
                        </ul>
                        <div class="m-t20 d-flex flex-wrap gap-3">
                            <a href="<?php echo h($zomatoUrl); ?>" target="_blank" class="btn btn-outline-primary btn-hover-1"><span>Zomato</span></a>
                            <a href="<?php echo h($swiggyUrl); ?>" target="_blank" class="btn btn-outline-primary btn-hover-1"><span>Swiggy</span></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="comparison-card">
                        <h4>With website: Direct orders + branding</h4>
                        <ul>
                            <li>Branded landing page for your restaurant name</li>
                            <li>Direct CTA to call or WhatsApp for orders</li>
                            <li>Search and ad campaigns can point to your own link</li>
                            <li>Easy to personalize for each outlet or client</li>
                        </ul>
                        <div class="m-t20">
                            <a href="<?php echo h($whatsAppHref); ?>" target="_blank" class="btn btn-primary btn-hover-1"><span>Get Direct Orders</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content-inner-1 section-wrapper-3 overflow-hidden">
        <div class="container">
            <div class="section-head text-center">
                <h2 class="title">Today's Menu</h2>
                <p>Dynamic menu cards can be changed from JSON for every restaurant slug.</p>
            </div>
            <div class="row inner-section-wrapper">
                <?php foreach ($menu as $item): ?>
                    <?php
                    $itemName = (string) ($item['name'] ?? 'Menu Item');
                    $itemDescription = (string) ($item['description'] ?? '');
                    $itemPrice = (float) ($item['price'] ?? 0);
                    $itemImage = (string) ($item['image'] ?? '/swigo.dexignzone.com/xhtml/assets/images/shop/pic2.jpg');
                    ?>
                    <div class="col-lg-3 col-md-6 col-sm-6 m-b30">
                        <div class="dz-img-box style-3 box-hover">
                            <div class="dz-media">
                                <img src="<?php echo h($itemImage); ?>" alt="<?php echo h($itemName); ?>">
                            </div>
                            <span class="dz-tag">TOP PICK</span>
                            <div class="dz-content">
                                <h5 class="dz-title"><?php echo h($itemName); ?></h5>
                                <p><?php echo h($itemDescription); ?></p>
                            </div>
                            <div class="dz-hover-content">
                                <div class="dz-info">
                                    <h5 class="dz-title mb-0"><?php echo h($itemName); ?></h5>
                                    <span class="dz-price">Rs. <?php echo h((string) number_format($itemPrice, 0)); ?></span>
                                </div>
                                <a href="<?php echo h($whatsAppHref); ?>" target="_blank" class="btn btn-cart btn-white text-primary btn-square"><i class="flaticon-shopping-cart"></i></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="content-inner-2">
        <div class="container">
            <div class="cta-strip text-white">
                <span class="mini-pill">Personalized Outreach Ready</span>
                <h2 class="text-white">Send this demo link in outreach and make every prospect feel it was built for them.</h2>
                <p class="m-b25">Use URLs like <strong><?php echo h('/myhubrestaurant'); ?></strong>, <strong><?php echo h('/royal-biryani-house'); ?></strong>, or any new slug you add in the JSON file. The same template can power 100+ demo pages.</p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="<?php echo h($whatsAppHref); ?>" target="_blank" class="btn btn-white btn-hover-1"><span><?php echo h($ctaText); ?></span></a>
                    <a href="/" class="btn btn-outline-light"><span>Open Generator Home</span></a>
                </div>
            </div>
        </div>
    </section>

    <footer id="contact" class="site-footer style-1 bg-dark">
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5 m-b30">
                        <div class="widget">
                            <div class="dynamic-logo-wrap m-b20">
                                <span class="dynamic-logo"><?php echo h($logoText); ?></span>
                                <div class="dynamic-logo-name text-white"><?php echo h($restaurantName); ?></div>
                            </div>
                            <p><?php echo h($description); ?></p>
                        </div>
                    </div>
                    <div class="col-lg-3 m-b30">
                        <div class="widget widget_getintuch">
                            <h5 class="footer-title">Contact</h5>
                            <ul>
                                <li><i class="flaticon-placeholder"></i><p><a href="<?php echo h($mapUrl); ?>" target="_blank"><?php echo h($address); ?></a></p></li>
                                <li><i class="flaticon-telephone"></i><p><a href="<?php echo h($phoneHref); ?>"><?php echo h($phone); ?></a></p></li>
                                <li><i class="flaticon-email-1"></i><p><?php if ($emailRaw !== ''): ?><a href="mailto:<?php echo h($emailRaw); ?>"><?php echo h($email); ?></a><?php else: ?><?php echo h($email); ?><?php endif; ?></p></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 m-b30">
                        <div class="widget widget_services">
                            <h5 class="footer-title">Why This Works</h5>
                            <ul>
                                <li><a href="<?php echo h($whatsAppHref); ?>" target="_blank"><span>WhatsApp Orders</span></a></li>
                                <li><a href="<?php echo h($phoneHref); ?>"><span>Phone Orders</span></a></li>
                                <li><a href="/"><span>One Template, Many Demos</span></a></li>
                                <li><a href="/<?php echo h($slug); ?>"><span>Share This Personalized Link</span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>
</body>
</html>
