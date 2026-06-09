<?php
/**
 * Dynamic Carousel Component
 * Loads slides from database with fallback to static content
 */

$carouselSlides = [];
$useDynamicCarousel = false;

try {
    require_once __DIR__ . '/../config/config.php';

    // Fetch active slides from database
    $stmt = $pdo->query("
        SELECT
            slide_id,
            image_url,
            headline,
            subheadline,
            category_label,
            cta_text,
            cta_link
        FROM carousel_slides
        WHERE is_active = 1
        AND (valid_from IS NULL OR valid_from <= CURDATE())
        AND (valid_until IS NULL OR valid_until >= CURDATE())
        ORDER BY sort_order ASC
    ");
    $carouselSlides = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $useDynamicCarousel = !empty($carouselSlides);
} catch (Exception $e) {
    // Silently fall back to static content if database fails
    error_log("Carousel database error: " . $e->getMessage());
}

// Fallback slides if no database slides exist
$fallbackSlides = [
    [
        'slide_id' => 'static-1',
        'image_url' => 'assets/images/view1.jpg',
        'category_label' => 'M&M <em>PARAISO</em>',
        'headline' => 'Experience<br>Paradise Awaits',
        'cta_link' => 'properties.php'
    ],
    [
        'slide_id' => 'static-2',
        'image_url' => 'assets/images/view2.jpg',
        'category_label' => 'Your <em>Escape</em>',
        'headline' => 'Luxury Stays<br>Unforgettable Memories',
        'cta_link' => 'properties.php'
    ],
    [
        'slide_id' => 'static-3',
        'image_url' => 'assets/images/view3.jpg',
        'category_label' => 'Book <em>Today</em>',
        'headline' => 'Reserve Now<br>Exclusive Villas & Rooms',
        'cta_link' => 'properties.php'
    ]
];

$slides = $useDynamicCarousel ? $carouselSlides : $fallbackSlides;

// Adjust image paths for public pages
foreach ($slides as &$slide) {
    if (isset($slide['image_url'])) {
        // Remove 'pages/' prefix if present (from old data)
        if (strpos($slide['image_url'], 'pages/') === 0) {
            $slide['image_url'] = substr($slide['image_url'], 6);
        }
        // Add '../' prefix for uploads folder (uploads is at root, not in pages/)
        if (strpos($slide['image_url'], 'uploads/') === 0) {
            $slide['image_url'] = '../' . $slide['image_url'];
        }
    }
}
unset($slide);
?>

<div class="main-banner">
    <div class="owl-carousel owl-banner" data-dynamic="<?php echo $useDynamicCarousel ? 'true' : 'false'; ?>">
        <?php foreach ($slides as $index => $slide): ?>
        <div class="item item-<?php echo $index + 1; ?><?php echo $useDynamicCarousel ? ' dynamic-slide' : ''; ?>"
             style="<?php echo $useDynamicCarousel ? 'background-image: url(' . htmlspecialchars($slide['image_url']) . ');' : ''; ?>"
             data-slide-id="<?php echo htmlspecialchars($slide['slide_id']); ?>">
            <div class="header-text">
                <?php if (!empty($slide['category_label'])): ?>
                <span class="category"><?php echo $slide['category_label']; ?></span>
                <?php endif; ?>
                <?php if (!empty($slide['headline'])): ?>
                <h2><?php echo $slide['headline']; ?></h2>
                <?php endif; ?>
                <?php if (!empty($slide['cta_text']) && !empty($slide['cta_link'])): ?>
                <div class="button-place" style="margin-top: 20px;">
                    <a href="<?php echo htmlspecialchars($slide['cta_link']); ?>" class="button-carousel"
                       onclick="trackCarouselClick('<?php echo htmlspecialchars($slide['slide_id']); ?>')">
                        <?php echo htmlspecialchars($slide['cta_text']); ?>
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
.button-carousel {
    display: inline-block;
    padding: 12px 28px;
    background: #f5a425;
    color: #fff;
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
    border-radius: 4px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.button-carousel:hover {
    background: #e09420;
    color: #fff;
}

/* Dynamic slide styles */
.main-banner .item.dynamic-slide {
    background-position: center center;
    background-repeat: no-repeat;
    background-size: cover;
    height: 800px;
    max-height: 800px;
}

.main-banner .owl-carousel {
    height: 800px;
}

.main-banner {
    max-width: 1600px;
    margin: 0 auto;
}
</style>

<script>
    // Track carousel views and clicks for analytics
    function trackCarouselView(slideId) {
        fetch('api/carousel-slides.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'track_view', slide_id: slideId })
        }).catch(() => {}); // Silent fail
    }

    function trackCarouselClick(slideId) {
        fetch('api/carousel-slides.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'track_click', slide_id: slideId })
        }).catch(() => {}); // Silent fail
    }

    // Track views when slides are shown (if using dynamic carousel)
    document.addEventListener('DOMContentLoaded', function() {
        try {
            const carousel = document.querySelector('.owl-carousel');
            if (carousel && carousel.dataset.dynamic === 'true') {
                // Track initial visible slide
                const firstSlide = carousel.querySelector('.item[data-slide-id]');
                if (firstSlide) {
                    trackCarouselView(firstSlide.dataset.slideId);
                }

                // Track on slide change if Owl Carousel events are available
                if (typeof $ !== 'undefined' && $('.owl-carousel').data('owl.carousel')) {
                    $('.owl-carousel').on('changed.owl.carousel', function(event) {
                        const currentItem = event.item.index;
                        const slides = carousel.querySelectorAll('.item[data-slide-id]');
                        if (slides[currentItem]) {
                            trackCarouselView(slides[currentItem].dataset.slideId);
                        }
                    });
                }
            }
        } catch (e) {
            console.error('Carousel tracking error:', e);
        }
    });
</script>