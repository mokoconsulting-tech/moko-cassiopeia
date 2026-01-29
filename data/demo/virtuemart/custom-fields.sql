-- VirtueMart Custom Product Fields
-- Version: 1.0.0
-- These custom fields add additional product options like size, color, warranty, etc.

-- Custom Field Types
INSERT INTO `#__virtuemart_customs` (`virtuemart_custom_id`, `custom_parent_id`, `virtuemart_vendor_id`, `custom_jplugin_id`, `custom_element`, `admin_only`, `custom_title`, `custom_tip`, `custom_value`, `custom_field_type`, `is_input`, `is_cart_attribute`, `is_list`, `custom_params`, `published`, `ordering`, `shared`, `created_on`, `modified_on`) VALUES
-- Size selector
(1, 0, 1, 0, '', 0, 'Size', 'Select your size', '', 'S', 1, 1, 1, '', 1, 1, 0, '2026-01-29 00:00:00', '2026-01-29 00:00:00'),
-- Color selector
(2, 0, 1, 0, '', 0, 'Color', 'Choose your preferred color', '', 'S', 1, 1, 1, '', 1, 2, 0, '2026-01-29 00:00:00', '2026-01-29 00:00:00'),
-- Material type
(3, 0, 1, 0, '', 0, 'Material', 'Product material', '', 'S', 0, 0, 0, '', 1, 3, 0, '2026-01-29 00:00:00', '2026-01-29 00:00:00'),
-- Warranty
(4, 0, 1, 0, '', 0, 'Warranty', 'Warranty period', '', 'S', 0, 0, 0, '', 1, 4, 0, '2026-01-29 00:00:00', '2026-01-29 00:00:00'),
-- Delivery time
(5, 0, 1, 0, '', 0, 'Delivery Time', 'Expected delivery timeframe', '', 'S', 0, 0, 0, '', 1, 5, 0, '2026-01-29 00:00:00', '2026-01-29 00:00:00'),
-- Gift wrap option
(6, 0, 1, 0, '', 0, 'Gift Wrap', 'Add gift wrapping', '', 'B', 1, 1, 0, '', 1, 6, 0, '2026-01-29 00:00:00', '2026-01-29 00:00:00'),
-- Memory/Storage (for electronics)
(7, 0, 1, 0, '', 0, 'Storage', 'Storage capacity', '', 'S', 1, 1, 1, '', 1, 7, 0, '2026-01-29 00:00:00', '2026-01-29 00:00:00');

-- Size Options
INSERT INTO `#__virtuemart_customs` (`virtuemart_custom_id`, `custom_parent_id`, `virtuemart_vendor_id`, `custom_value`, `custom_price`, `published`, `ordering`) VALUES
(10, 1, 1, 'XS', 0.00, 1, 1),
(11, 1, 1, 'S', 0.00, 1, 2),
(12, 1, 1, 'M', 0.00, 1, 3),
(13, 1, 1, 'L', 0.00, 1, 4),
(14, 1, 1, 'XL', 0.00, 1, 5),
(15, 1, 1, 'XXL', 0.00, 1, 6);

-- Color Options
INSERT INTO `#__virtuemart_customs` (`virtuemart_custom_id`, `custom_parent_id`, `virtuemart_vendor_id`, `custom_value`, `custom_price`, `published`, `ordering`) VALUES
(20, 2, 1, 'Black', 0.00, 1, 1),
(21, 2, 1, 'White', 0.00, 1, 2),
(22, 2, 1, 'Blue', 0.00, 1, 3),
(23, 2, 1, 'Red', 0.00, 1, 4),
(24, 2, 1, 'Green', 0.00, 1, 5),
(25, 2, 1, 'Gray', 0.00, 1, 6),
(26, 2, 1, 'Navy', 0.00, 1, 7),
(27, 2, 1, 'Brown', 0.00, 1, 8);

-- Storage Options (Electronics)
INSERT INTO `#__virtuemart_customs` (`virtuemart_custom_id`, `custom_parent_id`, `virtuemart_vendor_id`, `custom_value`, `custom_price`, `published`, `ordering`) VALUES
(30, 7, 1, '128GB', 0.00, 1, 1),
(31, 7, 1, '256GB', 100.00, 1, 2),
(32, 7, 1, '512GB', 200.00, 1, 3),
(33, 7, 1, '1TB', 300.00, 1, 4);

-- Assign Custom Fields to Products
-- Clothing products get Size and Color
INSERT INTO `#__virtuemart_product_customfields` (`virtuemart_product_id`, `virtuemart_custom_id`, `ordering`, `published`) VALUES
-- Men's Shirt
(200, 1, 1, 1), -- Size
(200, 2, 2, 1), -- Color
-- Men's Jeans
(201, 1, 1, 1), -- Size
(201, 2, 2, 1), -- Color
-- Women's Dress
(202, 1, 1, 1), -- Size
(202, 2, 2, 1), -- Color
-- Women's Blouse
(203, 1, 1, 1), -- Size
(203, 2, 2, 1), -- Color
-- Running Shoes
(204, 1, 1, 1), -- Size
(204, 2, 2, 1), -- Color
-- Casual Shoes
(205, 1, 1, 1), -- Size
(205, 2, 2, 1); -- Color

-- Electronics products get Storage and Warranty
INSERT INTO `#__virtuemart_product_customfields` (`virtuemart_product_id`, `virtuemart_custom_id`, `custom_value`, `ordering`, `published`) VALUES
-- Laptops
(100, 7, '512GB', 1, 1), -- Storage
(100, 4, '2 Year Manufacturer Warranty', 2, 1), -- Warranty
(101, 7, '256GB', 1, 1),
(101, 4, '1 Year Manufacturer Warranty', 2, 1),
-- Smartphones
(102, 7, '128GB', 1, 1),
(102, 2, '', 2, 1), -- Color option
(102, 4, '1 Year Manufacturer Warranty', 3, 1),
(103, 7, '256GB', 1, 1),
(103, 2, '', 2, 1), -- Color option
(103, 4, '1 Year Manufacturer Warranty', 3, 1);

-- Add Gift Wrap option to all products (optional)
INSERT INTO `#__virtuemart_product_customfields` (`virtuemart_product_id`, `virtuemart_custom_id`, `custom_price`, `ordering`, `published`) VALUES
(100, 6, 5.99, 99, 1),
(101, 6, 5.99, 99, 1),
(102, 6, 5.99, 99, 1),
(103, 6, 5.99, 99, 1),
(104, 6, 5.99, 99, 1),
(105, 6, 5.99, 99, 1),
(200, 6, 5.99, 99, 1),
(201, 6, 5.99, 99, 1),
(202, 6, 5.99, 99, 1),
(203, 6, 5.99, 99, 1),
(204, 6, 5.99, 99, 1),
(205, 6, 5.99, 99, 1),
(300, 6, 9.99, 99, 1),
(301, 6, 5.99, 99, 1),
(302, 6, 5.99, 99, 1),
(303, 6, 5.99, 99, 1);
