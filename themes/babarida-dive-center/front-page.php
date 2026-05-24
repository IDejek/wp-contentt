<?php
/**
 * Front Page Template (Homepage)
 * @package Babarida_Dive_Center
 */
defined('ABSPATH') || exit;
get_header();

// Get hero settings
 $hero_video  = get_theme_mod('bbr_hero_video', '');
 $hero_title  = get_theme_mod('bbr_hero_title', 'Babarida Dive Center');
 $hero_slogan = get_theme_mod('bbr_hero_slogan', 'The quality of your dive adventure depends on who guides you!');

// Get destinations
 $destinations = get_posts(array(
    'post_type'      => 'destination',
    'posts_per_page' => 4,
    'post_status'    => 'publish',
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
));

// Get liveaboards
 $liveaboards = get_posts(array(
    'post_type'      => 'liveaboard',
    'posts_per_page' => 6,
    'post_status'    => 'publish',
));

// Get testimonials
 $testimonials = get_posts(array(
    'post_type'      => 'testimonial',
    'posts_per_page' => 10,
    'post_status'    => 'publish',
));

// Get partners
 $partners = get_posts(array(
    'post_type'      => 'partner',
    'posts_per_page' => 20,
    'post_status'    => 'publish',
));

// Get hotels
 $hotels = get_posts(array(
    'post_type'      => 'hotel',
    'posts_per_page' => 6,
    'post_status'    => 'publish',
));
?>

<!-- HERO SECTION -->
<section class="bbr-hero" id="hero">
    <div class="bbr-hero-video">
        <?php if (!empty($hero_video)) : ?>
            <?php
            $is_video = preg_match('/\.(mp4|webm|ogg)$/i', $hero_video);
            if ($is_video) :
            ?>
                <video autoplay muted loop playsinline preload="auto" poster="<?php echo esc_url(BBR_URI . '/assets/images/hero-fallback.jpg'); ?>">
                    <source src="<?php echo esc_url($hero_video); ?>" type="video/mp4">
                </video>
            <?php else : ?>
                <img src="<?php echo esc_url($hero_video); ?>" alt="<?php echo esc_attr($hero_title); ?>" loading="eager">
            <?php endif; ?>
        <?php else : ?>
            <img src="<?php echo esc_url(BBR_URI . '/assets/images/hero-fallback.jpg'); ?>" alt="<?php echo esc_attr($hero_title); ?>" loading="eager">
        <?php endif; ?>
    </div>
    <div class="bbr-hero-overlay"></div>
    <div class="bbr-hero-content">
        <h1 class="bbr-hero-title"><?php echo esc_html($hero_title); ?></h1>
        <p class="bbr-hero-slogan"><?php echo esc_html($hero_slogan); ?></p>
    </div>
    <div class="bbr-hero-scroll">
        <span class="bbr-hero-scroll-text"><?php esc_html_e('Scroll', 'babarida-dive'); ?></span>
        <div class="bbr-hero-scroll-line"></div>
    </div>
    <div class="bbr-bubbles"></div>
</section>

<!-- WELCOME SECTION -->
<section class="bbr-welcome bbr-section" id="welcome">
    <div class="bbr-container">
        <div class="bbr-welcome-grid">
            <div class="bbr-welcome-img bbr-reveal">
                <?php
                $welcome_img_id = get_theme_mod('bbr_welcome_image', 0);
                if ($welcome_img_id && wp_get_attachment_url($welcome_img_id)) {
                    echo wp_get_attachment_image($welcome_img_id, 'bbr-hero', false, array('loading' => 'lazy'));
                } else {
                    echo '<img src="' . esc_url(BBR_URI . '/assets/images/welcome-fallback.jpg') . '" alt="Diving in Bunaken" loading="lazy">';
                }
                ?>
                <div class="bbr-welcome-img-badge">
                    <span>25+</span>
                    <small><?php esc_html_e('Years of\nExperience', 'babarida-dive'); ?></small>
                </div>
            </div>
            <div class="bbr-reveal bbr-reveal-delay-2">
                <div class="bbr-welcome-label"><?php esc_html_e('Welcome', 'babarida-dive'); ?></div>
                <h2 class="bbr-welcome-title"><?php esc_html_e('Welcome to Babarida Dive Center', 'babarida-dive'); ?></h2>
                <blockquote class="bbr-welcome-quote"><?php echo esc_html($hero_slogan); ?></blockquote>
                <p class="bbr-welcome-text"><?php esc_html_e('Our team is intimately familiar with Bunaken, Siladen, Bangka, and Lembeh and has worked together for years, creating safe, smooth, and unforgettable experiences for divers of all levels.', 'babarida-dive'); ?></p>
                <ul class="bbr-welcome-list">
                    <li><span class="check">✓</span> <?php esc_html_e('Liveaboard cruises', 'babarida-dive'); ?></li>
                    <li><span class="check">✓</span> <?php esc_html_e('Dive safaris', 'babarida-dive'); ?></li>
                    <li><span class="check">✓</span> <?php esc_html_e('Water sports', 'babarida-dive'); ?></li>
                    <li><span class="check">✓</span> <?php esc_html_e('Day trips', 'babarida-dive'); ?></li>
                    <li><span class="check">✓</span> <?php esc_html_e('SSI courses', 'babarida-dive'); ?></li>
                </ul>
                <p class="bbr-welcome-text"><?php esc_html_e('in two of the most biodiverse marine areas on the planet. Choose your destination and start planning your next trip.', 'babarida-dive'); ?></p>
                <div style="display:flex;gap:1rem;flex-wrap:wrap;margin-top:1.5rem">
                    <a href="#destinations" class="bbr-btn bbr-btn-primary"><?php esc_html_e('Explore Destinations', 'babarida-dive'); ?></a>
                    <a href="<?php echo esc_url(home_url('/book-now/')); ?>" class="bbr-btn bbr-btn-yellow"><?php esc_html_e('Book Now', 'babarida-dive'); ?></a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- DESTINATIONS SECTION -->
<section class="bbr-section bbr-section-ocean" id="destinations">
    <div class="bbr-container">
        <div class="bbr-section-header bbr-reveal">
            <span class="bbr-section-label"><?php esc_html_e('Explore', 'babarida-dive'); ?></span>
            <h2 class="bbr-section-title"><?php esc_html_e('Our Destinations', 'babarida-dive'); ?></h2>
            <p class="bbr-section-desc"><?php esc_html_e('Discover the most biodiverse marine environments in the heart of the Coral Triangle.', 'babarida-dive'); ?></p>
        </div>

        <?php if (!empty($destinations)) : ?>
        <div class="bbr-dest-grid">
            <?php foreach ($destinations as $i => $dest) :
                $subtitle = bbr_get_meta($dest->ID, 'destination', 'dest_subtitle', '');
                $desc     = wp_trim_words(get_the_excerpt($dest->ID), 18);
            ?>
            <a href="<?php echo esc_url(get_permalink($dest->ID)); ?>" class="bbr-dest-card bbr-reveal bbr-reveal-delay-<?php echo $i + 1; ?>">
                <?php if (has_post_thumbnail($dest->ID)) : ?>
                    <?php echo get_the_post_thumbnail($dest->ID, 'bbr-card', array('loading' => 'lazy')); ?>
                <?php else : ?>
                    <img src="<?php echo esc_url(BBR_URI . '/assets/images/dest-' . sanitize_file_name($dest->post_name) . '.jpg'); ?>" alt="<?php echo esc_attr($dest->post_title); ?>" loading="lazy" onerror="this.src='<?php echo esc_url(BBR_URI . '/assets/images/dest-default.jpg'); ?>'">
                <?php endif; ?>
                <div class="bbr-dest-card-overlay">
                    <span class="bbr-dest-card-label"><?php echo esc_html($subtitle); ?></span>
                    <h3 class="bbr-dest-card-name"><?php echo esc_html($dest->post_title); ?></h3>
                    <p class="bbr-dest-card-desc"><?php echo esc_html($desc); ?></p>
                </div>
                <div class="bbr-dest-card-arrow">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/></svg>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php else : ?>
        <!-- Default destinations when no CPT posts exist yet -->
        <div class="bbr-dest-grid">
            <?php
            $default_dests = array(
                array('name' => 'Bunaken', 'sub' => 'Wall Diving Paradise', 'slug' => 'bunaken'),
                array('name' => 'Siladen', 'sub' => 'Coral Garden', 'slug' => 'siladen'),
                array('name' => 'Bangka', 'sub' => 'Hidden Gem', 'slug' => 'bangka'),
                array('name' => 'Lembeh', 'sub' => 'Muck Diving Capital', 'slug' => 'lembeh'),
            );
            foreach ($default_dests as $i => $d) :
            ?>
            <div class="bbr-dest-card bbr-reveal bbr-reveal-delay-<?php echo $i + 1; ?>">
                <img src="<?php echo esc_url(BBR_URI . '/assets/images/dest-' . $d['slug'] . '.jpg'); ?>" alt="<?php echo esc_attr($d['name']); ?>" loading="lazy" onerror="this.src='<?php echo esc_url(BBR_URI . '/assets/images/dest-default.jpg'); ?>'">
                <div class="bbr-dest-card-overlay">
                    <span class="bbr-dest-card-label"><?php echo esc_html($d['sub']); ?></span>
                    <h3 class="bbr-dest-card-name"><?php echo esc_html($d['name']); ?></h3>
                </div>
                <div class="bbr-dest-card-arrow">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/></svg>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- SEARCH SECTION -->
<section class="bbr-section" id="search">
    <div class="bbr-container">
        <div class="bbr-section-header bbr-reveal">
            <span class="bbr-section-label"><?php esc_html_e('Find', 'babarida-dive'); ?></span>
            <h2 class="bbr-section-title"><?php esc_html_e('Find Your Adventure', 'babarida-dive'); ?></h2>
        </div>
        <div class="bbr-search-bar bbr-reveal">
            <div class="bbr-search-field">
                <label class="bbr-form-label"><?php esc_html_e('Destination', 'babarida-dive'); ?></label>
                <select name="destination" class="bbr-form-select">
                    <option value=""><?php esc_html_e('All Destinations', 'babarida-dive'); ?></option>
                    <option value="Bunaken"><?php esc_html_e('Bunaken', 'babarida-dive'); ?></option>
                    <option value="Siladen"><?php esc_html_e('Siladen', 'babarida-dive'); ?></option>
                    <option value="Bangka"><?php esc_html_e('Bangka', 'babarida-dive'); ?></option>
                    <option value="Lembeh"><?php esc_html_e('Lembeh', 'babarida-dive'); ?></option>
                </select>
            </div>
            <div class="bbr-search-field">
                <label class="bbr-form-label"><?php esc_html_e('Date', 'babarida-dive'); ?></label>
                <input type="date" name="date" class="bbr-form-input">
            </div>
            <div class="bbr-search-field">
                <label class="bbr-form-label"><?php esc_html_e('Activity', 'babarida-dive'); ?></label>
                <select name="type" class="bbr-form-select">
                    <option value=""><?php esc_html_e('All Activities', 'babarida-dive'); ?></option>
                    <option value="diving"><?php esc_html_e('Diving', 'babarida-dive'); ?></option>
                    <option value="snorkeling"><?php esc_html_e('Snorkeling', 'babarida-dive'); ?></option>
                    <option value="liveaboard"><?php esc_html_e('Liveaboard', 'babarida-dive'); ?></option>
                    <option value="course"><?php esc_html_e('Dive Course', 'babarida-dive'); ?></option>
                    <option value="water-sport"><?php esc_html_e('Water Sport', 'babarida-dive'); ?></option>
                </select>
            </div>
            <div class="bbr-search-field">
                <label class="bbr-form-label"><?php esc_html_e('Certification', 'babarida-dive'); ?></label>
                <select name="certification" class="bbr-form-select">
                    <option value=""><?php esc_html_e('Any Level', 'babarida-dive'); ?></option>
                    <option value="none"><?php esc_html_e('Non-Diver', 'babarida-dive'); ?></option>
                    <option value="owd"><?php esc_html_e('Open Water', 'babarida-dive'); ?></option>
                    <option value="aowd"><?php esc_html_e('Advanced OW', 'babarida-dive'); ?></option>
                    <option value="rescue"><?php esc_html_e('Rescue Diver', 'babarida-dive'); ?></option>
                    <option value="dm"><?php esc_html_e('Divemaster+', 'babarida-dive'); ?></option>
                </select>
            </div>
            <div class="bbr-search-field">
                <label class="bbr-form-label"><?php esc_html_e('Price Range (USD)', 'babarida-dive'); ?></label>
                <div style="display:flex;gap:.5rem">
                    <input type="number" name="min_price" class="bbr-form-input" placeholder="Min" min="0">
                    <input type="number" name="max_price" class="bbr-form-input" placeholder="Max" min="0">
                </div>
            </div>
            <div class="bbr-search-field" style="display:flex;align-items:flex-end">
                <button type="submit" class="bbr-btn bbr-btn-primary" style="width:100%;justify-content:center">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <?php esc_html_e('Search', 'babarida-dive'); ?>
                </button>
            </div>
        </div>
        <form id="bbr-search-form" style="display:none"></form>
        <div id="bbr-search-results" style="margin-top:2rem"></div>
    </div>
</section>

<!-- LIVEABOARDS SECTION -->
<?php if (!empty($liveaboards)) : ?>
<section class="bbr-section bbr-section-alt" id="liveaboards">
    <div class="bbr-container">
        <div class="bbr-section-header bbr-reveal">
            <span class="bbr-section-label"><?php esc_html_e('Sail', 'babarida-dive'); ?></span>
            <h2 class="bbr-section-title"><?php esc_html_e('Our Liveaboards', 'babarida-dive'); ?></h2>
            <p class="bbr-section-desc"><?php esc_html_e('Luxury liveaboard cruises through the best dive sites of North Sulawesi.', 'babarida-dive'); ?></p>
        </div>
        <div class="bbr-liveaboard-grid">
            <?php foreach ($liveaboards as $i => $boat) :
                $cabins     = bbr_get_meta($boat->ID, 'liveaboard', 'cabins', '—');
                $max_guests = bbr_get_meta($boat->ID, 'liveaboard', 'max_guests', '—');
                $length     = bbr_get_meta($boat->ID, 'liveaboard', 'boat_length', '—');
                $price      = bbr_get_meta($boat->ID, 'liveaboard', 'price_usd', '');
                $currency   = bbr_get_current_currency();
                $display_price = $price ? bbr_get_dynamic_price($boat->ID, '', $currency) : '';
            ?>
            <div class="boat-card bbr-reveal bbr-reveal-delay-<?php echo ($i % 4) + 1; ?>">
                <div class="bbr-boat-card">
                    <div class="bbr-boat-card-img">
                        <?php if (has_post_thumbnail($boat->ID)) : ?>
                            <?php echo get_the_post_thumbnail($boat->ID, 'bbr-card', array('loading' => 'lazy')); ?>
                        <?php else : ?>
                            <img src="<?php echo esc_url(BBR_URI . '/assets/images/boat-default.jpg'); ?>" alt="<?php echo esc_attr($boat->post_title); ?>" loading="lazy">
                        <?php endif; ?>
                        <span class="bbr-boat-card-badge"><?php esc_html_e('Liveaboard', 'babarida-dive'); ?></span>
                    </div>
                    <div class="bbr-boat-card-body">
                        <h3 class="bbr-boat-card-name"><?php echo esc_html($boat->post_title); ?></h3>
                        <div class="bbr-boat-card-specs">
                            <div class="bbr-boat-spec">
                                <div class="bbr-boat-spec-val"><?php echo esc_html($length); ?>m</div>
                                <div class="bbr-boat-spec-label"><?php esc_html_e('Length', 'babarida-dive'); ?></div>
                            </div>
                            <div class="bbr-boat-spec">
                                <div class="bbr-boat-spec-val"><?php echo esc_html($cabins); ?></div>
                                <div class="bbr-boat-spec-label"><?php esc_html_e('Cabins', 'babarida-dive'); ?></div>
                            </div>
                            <div class="bbr-boat-spec">
                                <div class="bbr-boat-spec-val"><?php echo esc_html($max_guests); ?></div>
                                <div class="bbr-boat-spec-label"><?php esc_html_e('Guests', 'babarida-dive'); ?></div>
                            </div>
                        </div>
                        <div class="bbr-boat-card-footer">
                            <div class="bbr-boat-price">
                                <?php esc_html_e('From', 'babarida-dive'); ?>
                                <?php if ($display_price) : ?>
                                    <strong><?php echo bbr_format_price($display_price, $currency); ?></strong>/<?php esc_html_e('night', 'babarida-dive'); ?>
                                <?php else : ?>
                                    <strong><?php esc_html_e('Contact Us', 'babarida-dive'); ?></strong>
                                <?php endif; ?>
                            </div>
                            <a href="<?php echo esc_url(get_permalink($boat->ID)); ?>" class="bbr-btn bbr-btn-primary" style="padding:.5rem 1.2rem;font-size:.78rem"><?php esc_html_e('View Details', 'babarida-dive'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div style="text-align:center;margin-top:2.5rem" class="bbr-reveal">
            <a href="<?php echo esc_url(get_post_type_archive_link('liveaboard')); ?>" class="bbr-btn bbr-btn-outline"><?php esc_html_e('View All Liveaboards', 'babarida-dive'); ?></a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- HOTEL PARTNERS SECTION -->
<?php if (!empty($hotels)) : ?>
<section class="bbr-section" id="hotels">
    <div class="bbr-container">
        <div class="bbr-section-header bbr-reveal">
            <span class="bbr-section-label"><?php esc_html_e('Stay', 'babarida-dive'); ?></span>
            <h2 class="bbr-section-title"><?php esc_html_e('Recommended Hotels in Manado', 'babarida-dive'); ?></h2>
            <p class="bbr-section-desc"><?php esc_html_e('Partner hotels with special rates for our diving guests.', 'babarida-dive'); ?></p>
        </div>
        <div class="bbr-hotel-grid">
            <?php foreach ($hotels as $i => $hotel) :
                $location = bbr_get_meta($hotel->ID, 'hotel', 'hotel_location', 'Manado');
                $stars    = bbr_get_meta($hotel->ID, 'hotel', 'hotel_stars', '');
                $price    = bbr_get_meta($hotel->ID, 'hotel', 'price_from_usd', '');
            ?>
            <div class="bbr-hotel-card bbr-reveal bbr-reveal-delay-<?php echo ($i % 3) + 1; ?>">
                <div class="bbr-hotel-card-img">
                    <?php if (has_post_thumbnail($hotel->ID)) : ?>
                        <?php echo get_the_post_thumbnail($hotel->ID, 'bbr-card', array('loading' => 'lazy')); ?>
                    <?php else : ?>
                        <img src="<?php echo esc_url(BBR_URI . '/assets/images/hotel-default.jpg'); ?>" alt="<?php echo esc_attr($hotel->post_title); ?>" loading="lazy">
                    <?php endif; ?>
                </div>
                <div class="bbr-hotel-card-body">
                    <h3 class="bbr-hotel-card-name"><?php echo esc_html($hotel->post_title); ?><?php if ($stars) echo ' <span style="color:#FFD60A">' . str_repeat('★', (int)$stars) . '</span>'; ?></h3>
                    <p class="bbr-hotel-card-loc">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <?php echo esc_html($location); ?>
                    </p>
                    <div class="bbr-hotel-card-price">
                        <?php esc_html_e('From', 'babarida-dive'); ?>
                        <?php if ($price) : ?>
                            <strong><?php echo bbr_format_price($price); ?></strong>/<?php esc_html_e('night', 'babarida-dive'); ?>
                        <?php else : ?>
                            <strong><?php esc_html_e('Contact Us', 'babarida-dive'); ?></strong>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- INTERACTIVE MAP SECTION -->
<section class="bbr-section bbr-section-blue" id="map">
    <div class="bbr-container">
        <div class="bbr-section-header bbr-reveal">
            <span class="bbr-section-label"><?php esc_html_e('Location', 'babarida-dive'); ?></span>
            <h2 class="bbr-section-title"><?php esc_html_e('Explore North Sulawesi', 'babarida-dive'); ?></h2>
        </div>
        <div class="bbr-map-container bbr-reveal">
            <svg class="bbr-map-svg" viewBox="0 0 900 680" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="North Sulawesi dive map">
                <!-- Ocean background -->
                <rect width="900" height="680" fill="rgba(0,180,216,0.15)" rx="20"/>

                <!-- Simplified North Sulawesi landmass -->
                <path d="M350,200 Q400,150 450,180 Q500,160 520,200 Q540,250 510,300 Q520,350 500,400 Q480,430 440,420 Q400,440 370,410 Q340,380 330,340 Q310,290 330,250 Z" fill="rgba(255,255,255,0.12)" stroke="rgba(255,255,255,0.2)" stroke-width="1.5"/>

                <!-- Manado label -->
                <text x="420" y="240" fill="rgba(255,255,255,0.5)" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">MANADO</text>

                <!-- Bunaken Island -->
                <g class="bbr-map-pin" data-dest="bunaken">
                    <circle class="bbr-map-pin-circle" cx="300" cy="200" r="8" fill="rgba(255,214,10,0.4)" stroke="#FFD60A" stroke-width="1.5"/>
                    <circle cx="300" cy="200" r="4" fill="#FFD60A"/>
                    <text x="300" y="185" fill="#FFD60A" font-size="11" font-weight="600" font-family="Inter,sans-serif" text-anchor="middle">Bunaken</text>
                </g>

                <!-- Siladen -->
                <g class="bbr-map-pin" data-dest="siladen">
                    <circle class="bbr-map-pin-circle" cx="340" cy="170" r="8" fill="rgba(255,214,10,0.4)" stroke="#FFD60A" stroke-width="1.5"/>
                    <circle cx="340" cy="170" r="4" fill="#FFD60A"/>
                    <text x="340" y="155" fill="#FFD60A" font-size="11" font-weight="600" font-family="Inter,sans-serif" text-anchor="middle">Siladen</text>
                </g>

                <!-- Bangka Island -->
                <g class="bbr-map-pin" data-dest="bangka">
                    <circle class="bbr-map-pin-circle" cx="580" cy="280" r="8" fill="rgba(255,214,10,0.4)" stroke="#FFD60A" stroke-width="1.5"/>
                    <circle cx="580" cy="280" r="4" fill="#FFD60A"/>
                    <text x="580" y="265" fill="#FFD60A" font-size="11" font-weight="600" font-family="Inter,sans-serif" text-anchor="middle">Bangka</text>
                </g>

                <!-- Lembeh Strait -->
                <g class="bbr-map-pin" data-dest="lembeh">
                    <circle class="bbr-map-pin-circle" cx="480" cy="480" r="8" fill="rgba(255,214,10,0.4)" stroke="#FFD60A" stroke-width="1.5"/>
                    <circle cx="480" cy="480" r="4" fill="#FFD60A"/>
                    <text x="480" y="465" fill="#FFD60A" font-size="11" font-weight="600" font-family="Inter,sans-serif" text-anchor="middle">Lembeh</text>
                </g>

                <!-- Dotted route lines -->
                <line x1="300" y1="200" x2="340" y2="170" stroke="rgba(255,214,10,0.3)" stroke-width="1" stroke-dasharray="4,4"/>
                <line x1="340" y1="170" x2="580" y2="280" stroke="rgba(255,214,10,0.2)" stroke-width="1" stroke-dasharray="4,4"/>
                <line x1="580" y1="280" x2="480" y2="480" stroke="rgba(255,214,10,0.2)" stroke-width="1" stroke-dasharray="4,4"/>
            </svg>
        </div>
    </div>
    <div class="bbr-bubbles"></div>
</section>

<!-- TESTIMONIALS SECTION -->
<?php if (!empty($testimonials)) : ?>
<section class="bbr-section" id="testimonials">
    <div class="bbr-container">
        <div class="bbr-section-header bbr-reveal">
            <span class="bbr-section-label"><?php esc_html_e('Reviews', 'babarida-dive'); ?></span>
            <h2 class="bbr-section-title"><?php esc_html_e('What Our Divers Say', 'babarida-dive'); ?></h2>
        </div>
        <div class="bbr-testi-slider bbr-reveal">
            <div class="bbr-testi-track">
                <?php foreach ($testimonials as $testi) :
                    $name  = bbr_get_meta($testi->ID, 'testimonial', 'customer_name', get_the_title($testi->ID));
                    $loc   = bbr_get_meta($testi->ID, 'testimonial', 'customer_loc', '');
                    $rating= absint(bbr_get_meta($testi->ID, 'testimonial', 'rating', 5));
                ?>
                <div class="bbr-testi-slide">
                    <div class="bbr-testi-card">
                        <div class="bbr-testi-stars">
                            <?php for ($s = 1; $s <= 5; $s++) : ?>
                                <?php echo $s <= $rating ? '★' : '☆'; ?>
                            <?php endfor; ?>
                        </div>
                        <p class="bbr-testi-text"><?php echo esc_html(wp_trim_words(get_the_content($testi->ID), 40)); ?></p>
                        <div class="bbr-testi-author">
                            <?php if (has_post_thumbnail($testi->ID)) : ?>
                                <?php echo get_the_post_thumbnail($testi->ID, 'bbr-avatar', array('class' => 'bbr-testi-avatar')); ?>
                            <?php else : ?>
                                <div class="bbr-testi-avatar" style="background:linear-gradient(135deg,#0077B6,#00B4D8);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1.1rem"><?php echo esc_html(mb_substr($name, 0, 1)); ?></div>
                            <?php endif; ?>
                            <div>
                                <div class="bbr-testi-name"><?php echo esc_html($name); ?></div>
                                <?php if ($loc) : ?><div class="bbr-testi-loc"><?php echo esc_html($loc); ?></div><?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="bbr-testi-dots">
                <?php for ($d = 0; $d < count($testimonials); $d++) : ?>
                    <span class="bbr-testi-dot <?php echo $d === 0 ? 'active' : ''; ?>" data-index="<?php echo $d; ?>"></span>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- PARTNERS SECTION -->
<?php if (!empty($partners)) : ?>
<section class="bbr-section bbr-section-alt" id="partners">
    <div class="bbr-container">
        <div class="bbr-section-header bbr-reveal">
            <span class="bbr-section-label"><?php esc_html_e('Trusted By', 'babarida-dive'); ?></span>
            <h2 class="bbr-section-title"><?php esc_html_e('Our Partners', 'babarida-dive'); ?></h2>
        </div>
        <div style="overflow:hidden" class="bbr-reveal">
            <div class="bbr-partner-track">
                <?php foreach ($partners as $partner) :
                    $url = bbr_get_meta($partner->ID, 'partner', 'partner_url', '');
                    $link_start = $url ? '<a href="' . esc_url($url) . '" target="_blank" rel="noopener">' : '';
                    $link_end   = $url ? '</a>' : '';
                ?>
                    <?php echo $link_start; ?>
                    <?php if (has_post_thumbnail($partner->ID)) : ?>
                        <?php echo get_the_post_thumbnail($partner->ID, 'full', array('class' => 'bbr-partner-logo', 'loading' => 'lazy', 'style' => 'height:50px;width:auto;object-fit:contain')); ?>
                    <?php else : ?>
                        <span class="bbr-partner-logo" style="height:50px;display:flex;align-items:center;font-size:.85rem;color:#6b7280;font-weight:600;padding:0 1rem"><?php echo esc_html($partner->post_title); ?></span>
                    <?php endif; ?>
                    <?php echo $link_end; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- NEWSLETTER SECTION -->
<section class="bbr-section bbr-section-blue" id="newsletter">
    <div class="bbr-container" style="text-align:center;max-width:600px">
        <div class="bbr-reveal">
            <span class="bbr-section-label"><?php esc_html_e('Stay Updated', 'babarida-dive'); ?></span>
            <h2 class="bbr-section-title" style="font-size:clamp(1.5rem,3vw,2.5rem)"><?php esc_html_e('Get Dive Deals & Updates', 'babarida-dive'); ?></h2>
            <p style="color:rgba(255,255,255,.7);margin-bottom:2rem"><?php esc_html_e('Subscribe for exclusive offers, dive reports, and travel inspiration.', 'babarida-dive'); ?></p>
            <form class="bbr-newsletter-form" style="display:flex;gap:.5rem;max-width:440px;margin:0 auto">
                <input type="email" name="email" required placeholder="<?php esc_attr_e('Your email address', 'babarida-dive'); ?>" style="flex:1;padding:.75rem 1.25rem;border-radius:var(--radius-full);border:2px solid rgba(255,255,255,.2);background:rgba(255,255,255,.1);color:#fff;font-size:.9rem" aria-label="Email for newsletter">
                <button type="submit" class="bbr-btn bbr-btn-yellow"><?php esc_html_e('Subscribe', 'babarida-dive'); ?></button>
            </form>
            <span class="bbr-newsletter-msg" style="display:block;margin-top:.75rem;font-size:.82rem"></span>
        </div>
    </div>
    <div class="bbr-bubbles"></div>
</section>

<?php get_footer(); ?>
