-- ============================================================================
-- RESORT / HOTEL RESERVATION SYSTEM
-- Module 07: Recipe Management
-- Covers: Menu Item Recipes, Recipe Ingredients
-- Database: MySQL 8.0+  |  Engine: InnoDB  |  Charset: utf8mb4
-- ============================================================================

SET NAMES utf8mb4;

-- ============================================================================
-- TABLE: recipes
-- Links menu items to their ingredient lists and preparation instructions
-- ============================================================================
CREATE TABLE IF NOT EXISTS recipes (
    recipe_id           CHAR(36)        NOT NULL                              COMMENT 'PK – UUID v4',
    item_id             CHAR(36)        NOT NULL                              COMMENT 'FK → menu_items.item_id',
    recipe_name         VARCHAR(150)    NOT NULL                              COMMENT 'e.g. "Classic Burger Recipe"',
    description         TEXT            NULL                                  COMMENT 'Preparation instructions',
    portion_size        VARCHAR(50)     NULL                                  COMMENT 'e.g. "1 serving", "for 2 persons"',
    preparation_time    SMALLINT        NULL                                  COMMENT 'in minutes',
    cooking_time        SMALLINT        NULL                                  COMMENT 'in minutes',
    yield_quantity      DECIMAL(10,3)   NOT NULL DEFAULT 1.000                COMMENT 'Number of servings this recipe produces',
    is_active           TINYINT(1)      NOT NULL DEFAULT 1,
    created_by          CHAR(36)        NULL,
    created_at          TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (recipe_id),
    UNIQUE KEY uk_item_id (item_id),
    KEY idx_is_active (is_active),
    CONSTRAINT fk_recipe_item_id FOREIGN KEY (item_id) REFERENCES menu_items(item_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Recipe header linking menu items to ingredients';

-- ============================================================================
-- TABLE: recipe_ingredients
-- Detailed ingredient list per recipe with quantities and unit conversions
-- ============================================================================
CREATE TABLE IF NOT EXISTS recipe_ingredients (
    recipe_ingredient_id   INT             NOT NULL AUTO_INCREMENT               COMMENT 'PK',
    recipe_id               CHAR(36)        NOT NULL                              COMMENT 'FK → recipes.recipe_id',
    product_id             CHAR(36)        NOT NULL                              COMMENT 'FK → products.product_id (inventory item)',
    quantity               DECIMAL(12,3)   NOT NULL                              COMMENT 'Quantity of ingredient needed per recipe yield',
    uom_id                 SMALLINT        NOT NULL                              COMMENT 'FK → units_of_measure.uom_id',
    ingredient_name        VARCHAR(150)    NULL                                  COMMENT 'Snapshot of product name at time of recipe creation',
    cost_per_unit          DECIMAL(12,2)   NULL                                  COMMENT 'Cost per unit at time of recipe creation',
    total_cost             DECIMAL(12,2)   NULL                                  COMMENT 'quantity * cost_per_unit',
    sort_order             SMALLINT        NOT NULL DEFAULT 0,
    is_optional            TINYINT(1)      NOT NULL DEFAULT 0,
    notes                  VARCHAR(255)    NULL,
    created_at             TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (recipe_ingredient_id),
    KEY idx_recipe_id (recipe_id),
    KEY idx_product_id (product_id),
    CONSTRAINT fk_ri_recipe_id FOREIGN KEY (recipe_id) REFERENCES recipes(recipe_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_ri_product_id FOREIGN KEY (product_id) REFERENCES products(product_id) ON UPDATE CASCADE,
    CONSTRAINT fk_ri_uom_id FOREIGN KEY (uom_id) REFERENCES units_of_measure(uom_id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Ingredient list per recipe with quantities and costs';

-- ============================================================================
-- TABLE: recipe_alternatives
-- Alternative ingredients (substitutions) for dietary restrictions
-- ============================================================================
CREATE TABLE IF NOT EXISTS recipe_alternatives (
    alternative_id         INT             NOT NULL AUTO_INCREMENT               COMMENT 'PK',
    recipe_ingredient_id  INT             NOT NULL                              COMMENT 'FK → recipe_ingredients.recipe_ingredient_id',
    product_id            CHAR(36)        NOT NULL                              COMMENT 'FK → products.product_id (alternative ingredient)',
    quantity              DECIMAL(12,3)   NOT NULL,
    uom_id                SMALLINT        NOT NULL                              COMMENT 'FK → units_of_measure.uom_id',
    dietary_restriction   VARCHAR(50)     NULL                                  COMMENT 'e.g. gluten_free, vegan, lactose_free',
    notes                 VARCHAR(255)    NULL,
    PRIMARY KEY (alternative_id),
    KEY idx_recipe_ingredient_id (recipe_ingredient_id),
    KEY idx_product_id (product_id),
    CONSTRAINT fk_ra_recipe_ingredient_id FOREIGN KEY (recipe_ingredient_id) REFERENCES recipe_ingredients(recipe_ingredient_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_ra_product_id FOREIGN KEY (product_id) REFERENCES products(product_id) ON UPDATE CASCADE,
    CONSTRAINT fk_ra_uom_id FOREIGN KEY (uom_id) REFERENCES units_of_measure(uom_id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Alternative ingredient substitutions for dietary needs';

SELECT '07_recipes.sql — OK' AS migration_status;
