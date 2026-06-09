-- ============================================================================
-- CAROUSEL SLIDES TABLE
-- Stores hero banner slides for the public pages carousel
-- ============================================================================

CREATE TABLE IF NOT EXISTS carousel_slides (
    slide_id        CHAR(36)        NOT NULL                                    COMMENT 'PK - UUID v4',
    property_id     CHAR(36)        NULL                                        COMMENT 'NULL = global slide (all properties)',
    image_url       VARCHAR(500)    NOT NULL                                    COMMENT 'Background image path',
    mobile_image_url VARCHAR(500)   NULL                                        COMMENT 'Optional mobile-optimized image',
    headline        VARCHAR(150)    NOT NULL                                    COMMENT 'Main heading text',
    subheadline     VARCHAR(255)    NULL                                        COMMENT 'Secondary text/subheading',
    category_label  VARCHAR(100)    NULL                                        COMMENT 'Small label above headline (e.g., M&M PARAISO)',
    cta_text        VARCHAR(50)     NULL                                        COMMENT 'Call-to-action button text',
    cta_link        VARCHAR(500)    NULL                                        COMMENT 'Button link URL',
    sort_order      SMALLINT        NOT NULL DEFAULT 0                          COMMENT 'Display order (0 = first)',
    is_active       TINYINT(1)      NOT NULL DEFAULT 1                          COMMENT '1 = visible, 0 = hidden',
    valid_from      DATE            NULL                                        COMMENT 'Optional start date',
    valid_until     DATE            NULL                                        COMMENT 'Optional end date',
    view_count      INT             NOT NULL DEFAULT 0                          COMMENT 'Analytics: times displayed',
    click_count     INT             NOT NULL DEFAULT 0                          COMMENT 'Analytics: CTA clicks',
    created_by      CHAR(36)        NULL,
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (slide_id),
    KEY idx_property_id (property_id),
    KEY idx_is_active (is_active),
    KEY idx_sort_order (sort_order),
    KEY idx_valid_range (valid_from, valid_until),
    KEY idx_active_sort (is_active, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Hero banner carousel slides for public pages';

-- ============================================================================
-- SEED DATA: Default slides matching current static carousel
-- ============================================================================

INSERT INTO carousel_slides (slide_id, image_url, headline, subheadline, category_label, cta_text, cta_link, sort_order, is_active) VALUES
(
    UUID(),
    'pages/assets/images/view1.jpg',
    'Experience<br>Paradise Awaits',
    NULL,
    'M&M <em>PARAISO</em>',
    'Book Now',
    'properties.php',
    0,
    1
),
(
    UUID(),
    'pages/assets/images/view2.jpg',
    'Luxury Stays<br>Unforgettable Memories',
    NULL,
    'Your <em>Escape</em>',
    'Explore Rooms',
    'properties.php',
    1,
    1
),
(
    UUID(),
    'pages/assets/images/view3.jpg',
    'Reserve Now<br>Exclusive Villas & Rooms',
    NULL,
    'Book <em>Today</em>',
    'View Properties',
    'properties.php',
    2,
    1
);

SELECT 'add_carousel_slides_table.sql — OK' AS migration_status;
