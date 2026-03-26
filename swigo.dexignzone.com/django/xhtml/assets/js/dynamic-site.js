(function () {
    function normalizeSlug(value) {
        return String(value || '')
            .trim()
            .toLowerCase()
            .replace(/[^a-z0-9-]/g, '');
    }

    function titleFromSlug(slug) {
        return slug
            .split(/[-_]+/)
            .filter(Boolean)
            .map(function (part) {
                return part.charAt(0).toUpperCase() + part.slice(1);
            })
            .join(' ');
    }

    function initialsFromName(name) {
        var parts = String(name || '').trim().split(/[^A-Za-z0-9]+/).filter(Boolean);
        if (!parts.length) {
            return 'R';
        }

        return parts.slice(0, 3).map(function (part) {
            return part.charAt(0).toUpperCase();
        }).join('');
    }

    function getSlugFromLocation() {
        var url = new URL(window.location.href);
        var querySlug = normalizeSlug(url.searchParams.get('slug'));
        if (querySlug) {
            return querySlug;
        }

        var parts = window.location.pathname.split('/').filter(Boolean);
        if (!parts.length) {
            return '';
        }

        var last = parts[parts.length - 1];
        var prev = parts[parts.length - 2] || '';

        if (/\.html?$/i.test(last) && /^[a-z0-9-]+$/.test(prev) && ['xhtml', 'django', 'tailwind'].indexOf(prev) === -1) {
            return normalizeSlug(prev);
        }

        if (!/\.html?$/i.test(last) && /^[a-z0-9-]+$/.test(last) && ['xhtml', 'django', 'tailwind'].indexOf(last) === -1) {
            return normalizeSlug(last);
        }

        return '';
    }

    function buildPageUrl(slug, pageName) {
        var normalizedSlug = normalizeSlug(slug);
        var normalizedPage = /^[A-Za-z0-9_-]+\.html$/.test(pageName) ? pageName : 'index.html';
        return '/vishal/resturnat/swigo.dexignzone.com/?page=' + encodeURIComponent(normalizedPage) + '&slug=' + encodeURIComponent(normalizedSlug);
    }

    function injectStyles() {
        if (document.getElementById('dynamic-site-styles')) {
            return;
        }

        var style = document.createElement('style');
        style.id = 'dynamic-site-styles';
        style.textContent = [
            '.dynamic-brand-logo-js{display:inline-flex !important;align-items:center;gap:10px;text-decoration:none;}',
            '.dynamic-brand-logo-js .dynamic-brand-badge{width:46px;height:46px;border-radius:14px;background:linear-gradient(135deg,#f54900,#ffb347);color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:16px;font-weight:700;letter-spacing:1px;box-shadow:0 10px 28px rgba(245,73,0,.2);}',
            '.dynamic-brand-logo-js .dynamic-brand-name{font-size:16px;font-weight:700;color:#000;font-style:italic;letter-spacing:0 !important;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:180px;}',
            '.site-header .anim-logo-white .dynamic-brand-name,.header-nav .dynamic-brand-name,.is-fixed .dynamic-brand-name{color:#000;}'
        ].join('');
        document.head.appendChild(style);
    }

    function createLogoMarkup(restaurant) {
        var name = restaurant.logo_label || restaurant.restaurant_name || titleFromSlug(restaurant.slug || '');
        var initials = restaurant.logo_text || initialsFromName(restaurant.restaurant_name || name);
        return '<span class="dynamic-brand-badge">' + initials + '</span><span class="dynamic-brand-name">' + name + '</span>';
    }

    function replaceLogo(anchor, restaurant, slug) {
        if (!anchor) {
            return;
        }

        anchor.classList.add('dynamic-brand-logo-js');
        anchor.innerHTML = createLogoMarkup(restaurant);
        anchor.setAttribute('href', buildPageUrl(slug, 'index.html'));
    }

    function updateLogos(restaurant, slug) {
        var selectors = [
            '.logo-header.mostion a.anim-logo',
            '.logo-header.mostion a.anim-logo-white',
            '.header-nav .logo-header a.anim-logo',
            '.header-nav .logo-header a.anim-logo-white'
        ];

        selectors.forEach(function (selector) {
            document.querySelectorAll(selector).forEach(function (anchor) {
                replaceLogo(anchor, restaurant, slug);
            });
        });
    }

    function rewriteInternalLinks(slug) {
        document.querySelectorAll('a[href]').forEach(function (anchor) {
            var href = anchor.getAttribute('href') || '';

            if (!href || /^(https?:|mailto:|tel:|javascript:|#)/i.test(href)) {
                return;
            }

            var cleanHref = href.split('#')[0];
            var pageName = cleanHref.split('?')[0].split('/').pop();
            if (!/^[A-Za-z0-9_-]+\.html$/.test(pageName)) {
                return;
            }

            anchor.setAttribute('href', buildPageUrl(slug, pageName));
        });
    }

    function updateTitle(restaurant) {
        if (!restaurant.restaurant_name) {
            return;
        }

        document.title = document.title.replace(/Swigo.*DexignZone/i, restaurant.restaurant_name + ' | Sample Restaurant Website');
    }

    function fetchRestaurantData(slug) {
        var paths = [
            'data/restaurants.json',
            '../data/restaurants.json',
            '../../data/restaurants.json'
        ];

        return paths.reduce(function (promise, path) {
            return promise.catch(function () {
                return fetch(path, { cache: 'no-store' }).then(function (response) {
                    if (!response.ok) {
                        throw new Error('Missing ' + path);
                    }
                    return response.json();
                }).then(function (data) {
                    if (!data || !data[slug]) {
                        throw new Error('Missing restaurant for slug ' + slug);
                    }
                    return data[slug];
                });
            });
        }, Promise.reject(new Error('No data loaded')));
    }

    function init() {
        var slug = getSlugFromLocation();
        if (!slug) {
            return;
        }

        fetchRestaurantData(slug).then(function (restaurant) {
            restaurant.slug = slug;
            injectStyles();
            updateLogos(restaurant, slug);
            rewriteInternalLinks(slug);
            updateTitle(restaurant);
        }).catch(function () {
            return null;
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
}());
