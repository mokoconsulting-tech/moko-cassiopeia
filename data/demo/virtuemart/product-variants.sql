-- VirtueMart Product Variants (Child Products)
-- Version: 1.0.0
-- Create product variations for size, color, storage, etc.

-- Example: Smartphone with different storage capacities as child products
-- Parent Product: Smartphone Pro 12 (102)

-- Child Products for Smartphone Pro 12
INSERT INTO `#__virtuemart_products` (`virtuemart_product_id`, `virtuemart_vendor_id`, `product_parent_id`, `product_sku`, `product_gtin`, `product_mpn`, `product_weight`, `product_weight_uom`, `product_in_stock`, `low_stock_notification`, `product_available_date`, `product_special`, `published`, `created_on`) VALUES
-- 128GB variant (this is the default already created as 102)
-- 256GB variant
(102256, 1, 102, 'PHONE-001-256', '1234567890225', 'PH-PRO-12-256', 0.19, 'kg', 75, 15, '2026-01-01 00:00:00', 1, 1, '2026-01-29 00:00:00'),
-- 512GB variant
(102512, 1, 102, 'PHONE-001-512', '1234567890325', 'PH-PRO-12-512', 0.19, 'kg', 60, 15, '2026-01-01 00:00:00', 1, 1, '2026-01-29 00:00:00');

-- Descriptions for variants
INSERT INTO `#__virtuemart_products_en_gb` (`virtuemart_product_id`, `product_name`, `slug`) VALUES
(102256, 'Smartphone Pro 12 - 256GB', 'smartphone-pro-12-256gb'),
(102512, 'Smartphone Pro 12 - 512GB', 'smartphone-pro-12-512gb');

-- Pricing for variants
INSERT INTO `#__virtuemart_product_prices` (`virtuemart_product_price_id`, `virtuemart_product_id`, `virtuemart_shoppergroup_id`, `product_price`, `product_currency`, `created_on`) VALUES
(103, 102256, 0, 899.99, 47, '2026-01-29 00:00:00'),
(104, 102512, 0, 999.99, 47, '2026-01-29 00:00:00');

-- Example: T-Shirt with different sizes and colors as variants
-- Parent Product: Men's Shirt (200)

-- Color variants
INSERT INTO `#__virtuemart_products` (`virtuemart_product_id`, `virtuemart_vendor_id`, `product_parent_id`, `product_sku`, `product_mpn`, `product_weight`, `product_weight_uom`, `product_in_stock`, `low_stock_notification`, `published`, `created_on`) VALUES
-- White variant
(200002, 1, 200, 'MENS-SHIRT-001-WHT', 'MS-CS-WHT-M', 0.3, 'kg', 45, 10, 1, '2026-01-29 00:00:00'),
-- Black variant
(200003, 1, 200, 'MENS-SHIRT-001-BLK', 'MS-CS-BLK-M', 0.3, 'kg', 55, 10, 1, '2026-01-29 00:00:00');

INSERT INTO `#__virtuemart_products_en_gb` (`virtuemart_product_id`, `product_name`, `slug`) VALUES
(200002, 'Classic Men\'s Shirt - White', 'classic-mens-shirt-white'),
(200003, 'Classic Men\'s Shirt - Black', 'classic-mens-shirt-black');

INSERT INTO `#__virtuemart_product_prices` (`virtuemart_product_price_id`, `virtuemart_product_id`, `virtuemart_shoppergroup_id`, `product_price`, `product_currency`, `created_on`) VALUES
(105, 200002, 0, 49.99, 47, '2026-01-29 00:00:00'),
(106, 200003, 0, 49.99, 47, '2026-01-29 00:00:00');

-- Link variants to same categories as parent
INSERT INTO `#__virtuemart_product_categories` (`virtuemart_product_id`, `virtuemart_category_id`, `ordering`) VALUES
(102256, 11, 3),
(102512, 11, 4),
(200002, 20, 3),
(200003, 20, 4);

-- Variant attributes (size, color, etc.)
INSERT INTO `#__virtuemart_product_customfields` (`virtuemart_product_id`, `virtuemart_custom_id`, `custom_value`, `ordering`, `published`) VALUES
-- Smartphone variants get storage specification
(102256, 7, '256GB', 1, 1),
(102512, 7, '512GB', 1, 1),
-- Shirt variants get color specification
(200002, 2, 'White', 1, 1),
(200003, 2, 'Black', 1, 1);
