-- VirtueMart Featured Products Configuration
-- Version: 1.0.0
-- Mark specific products as featured for homepage display

-- Featured Products
-- Set product_special = 1 for featured items
UPDATE `#__virtuemart_products` SET `product_special` = 1 WHERE `virtuemart_product_id` IN (
    102, -- Smartphone Pro 12
    104, -- Wireless Headphones Pro
    201, -- Slim Fit Jeans (on sale)
    203, -- Women's Casual Blouse
    301  -- Ergonomic Office Chair (on sale)
);

-- Alternative: Create a featured products relation table if your VirtueMart version uses it
-- INSERT INTO `#__virtuemart_product_featured` (`virtuemart_product_id`, `ordering`, `published`) VALUES
-- (102, 1, 1),
-- (104, 2, 1),
-- (201, 3, 1),
-- (203, 4, 1),
-- (301, 5, 1);
