-- VirtueMart Stock Management Sample Data
-- Version: 1.0.0
-- Configure stock levels, tracking, and low stock notifications

-- Update stock levels (already set in products.sql, but here for reference)
-- Low stock products (for testing notifications)
UPDATE `#__virtuemart_products` SET 
    `product_in_stock` = 5,
    `low_stock_notification` = 10
WHERE `virtuemart_product_id` = 300; -- Sofa (low stock)

UPDATE `#__virtuemart_products` SET 
    `product_in_stock` = 8,
    `low_stock_notification` = 15
WHERE `virtuemart_product_id` = 103; -- Smartphone Max

-- Out of stock examples (for testing)
-- UPDATE `#__virtuemart_products` SET 
--     `product_in_stock` = 0,
--     `product_availability` = 'Out of Stock - Ships in 2-3 weeks'
-- WHERE `virtuemart_product_id` = 999;

-- Stock notification settings (global configuration)
-- These would typically be set in VirtueMart configuration, not via SQL
-- But included here for reference

-- Stock history tracking (if enabled)
-- INSERT INTO `#__virtuemart_product_stock_history` (`virtuemart_product_id`, `stock_level`, `stock_change`, `reason`, `user_id`, `created_on`) VALUES
-- (100, 50, 0, 'Initial stock', 1, '2026-01-29 00:00:00'),
-- (101, 35, 0, 'Initial stock', 1, '2026-01-29 00:00:00');

-- Inventory tracking by warehouse (if multi-warehouse is enabled)
-- INSERT INTO `#__virtuemart_product_warehouses` (`virtuemart_product_id`, `virtuemart_warehouse_id`, `stock_level`) VALUES
-- (100, 1, 30), -- Main warehouse
-- (100, 2, 20), -- Secondary warehouse
-- (101, 1, 35);
