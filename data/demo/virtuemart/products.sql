-- VirtueMart Sample Products
-- Version: 1.0.0
-- Compatible with: VirtueMart 4.x
-- NOTE: Replace #__ with your actual Joomla table prefix

-- Sample Products (Electronics)
INSERT INTO `#__virtuemart_products` (`virtuemart_product_id`, `virtuemart_vendor_id`, `product_sku`, `product_gtin`, `product_mpn`, `product_weight`, `product_weight_uom`, `product_length`, `product_width`, `product_height`, `product_lwh_uom`, `product_in_stock`, `product_ordered`, `low_stock_notification`, `product_available_date`, `product_availability`, `product_special`, `product_discontinued`, `product_sales`, `product_unit`, `product_packaging`, `product_params`, `published`, `created_on`, `modified_on`) VALUES
-- Laptops
(100, 1, 'LAPTOP-001', '1234567890123', 'LP-PRO-15', 2.5, 'kg', 35, 24, 2, 'cm', 50, 0, 10, '2026-01-01 00:00:00', '', 0, 0, 0, '', 1, '', 1, '2026-01-29 00:00:00', '2026-01-29 00:00:00'),
(101, 1, 'LAPTOP-002', '1234567890124', 'LP-ULT-13', 1.8, 'kg', 30, 20, 1.5, 'cm', 35, 0, 10, '2026-01-01 00:00:00', '', 0, 0, 0, '', 1, '', 1, '2026-01-29 00:00:00', '2026-01-29 00:00:00'),
-- Smartphones
(102, 1, 'PHONE-001', '1234567890125', 'PH-PRO-12', 0.19, 'kg', 15, 7, 0.8, 'cm', 100, 0, 20, '2026-01-01 00:00:00', '', 1, 0, 0, '', 1, '', 1, '2026-01-29 00:00:00', '2026-01-29 00:00:00'),
(103, 1, 'PHONE-002', '1234567890126', 'PH-MAX-14', 0.22, 'kg', 16, 7.5, 0.8, 'cm', 75, 0, 15, '2026-01-01 00:00:00', '', 0, 0, 0, '', 1, '', 1, '2026-01-29 00:00:00', '2026-01-29 00:00:00'),
-- Headphones
(104, 1, 'AUDIO-001', '1234567890127', 'HD-WL-PRO', 0.25, 'kg', 20, 18, 8, 'cm', 60, 0, 10, '2026-01-01 00:00:00', '', 0, 0, 0, '', 1, '', 1, '2026-01-29 00:00:00', '2026-01-29 00:00:00'),
(105, 1, 'AUDIO-002', '1234567890128', 'EB-NC-300', 0.05, 'kg', 5, 5, 3, 'cm', 120, 0, 20, '2026-01-01 00:00:00', '', 0, 0, 0, '', 1, '', 1, '2026-01-29 00:00:00', '2026-01-29 00:00:00');

-- Sample Products (Clothing)
INSERT INTO `#__virtuemart_products` (`virtuemart_product_id`, `virtuemart_vendor_id`, `product_sku`, `product_gtin`, `product_mpn`, `product_weight`, `product_weight_uom`, `product_in_stock`, `product_ordered`, `low_stock_notification`, `product_available_date`, `product_special`, `product_discontinued`, `product_sales`, `published`, `created_on`, `modified_on`) VALUES
-- Men's Clothing
(200, 1, 'MENS-SHIRT-001', '2234567890123', 'MS-CS-BL-M', 0.3, 'kg', 50, 0, 10, '2026-01-01 00:00:00', 0, 0, 0, 1, '2026-01-29 00:00:00', '2026-01-29 00:00:00'),
(201, 1, 'MENS-JEAN-001', '2234567890124', 'MJ-SL-IND-32', 0.6, 'kg', 40, 0, 8, '2026-01-01 00:00:00', 1, 0, 0, 1, '2026-01-29 00:00:00', '2026-01-29 00:00:00'),
-- Women's Clothing
(202, 1, 'WOMENS-DRESS-001', '2234567890125', 'WD-SUM-FL-M', 0.4, 'kg', 30, 0, 5, '2026-01-01 00:00:00', 0, 0, 0, 1, '2026-01-29 00:00:00', '2026-01-29 00:00:00'),
(203, 1, 'WOMENS-TOP-001', '2234567890126', 'WT-BL-WHT-L', 0.2, 'kg', 60, 0, 10, '2026-01-01 00:00:00', 0, 0, 0, 1, '2026-01-29 00:00:00', '2026-01-29 00:00:00'),
-- Shoes
(204, 1, 'SHOE-RUN-001', '2234567890127', 'SR-AT-BLK-10', 0.8, 'kg', 45, 0, 10, '2026-01-01 00:00:00', 0, 0, 0, 1, '2026-01-29 00:00:00', '2026-01-29 00:00:00'),
(205, 1, 'SHOE-CAS-001', '2234567890128', 'SC-WK-BRN-9', 0.9, 'kg', 35, 0, 8, '2026-01-01 00:00:00', 0, 0, 0, 1, '2026-01-29 00:00:00', '2026-01-29 00:00:00');

-- Sample Products (Home & Garden)
INSERT INTO `#__virtuemart_products` (`virtuemart_product_id`, `virtuemart_vendor_id`, `product_sku`, `product_gtin`, `product_weight`, `product_weight_uom`, `product_in_stock`, `product_ordered`, `low_stock_notification`, `product_available_date`, `product_special`, `product_discontinued`, `published`, `created_on`, `modified_on`) VALUES
-- Furniture
(300, 1, 'FURN-SOFA-001', '3334567890123', 45, 'kg', 10, 0, 2, '2026-01-01 00:00:00', 0, 0, 1, '2026-01-29 00:00:00', '2026-01-29 00:00:00'),
(301, 1, 'FURN-CHAIR-001', '3334567890124', 12, 'kg', 25, 0, 5, '2026-01-01 00:00:00', 1, 0, 1, '2026-01-29 00:00:00', '2026-01-29 00:00:00'),
-- Kitchen
(302, 1, 'KITCHEN-BLEND-001', '3334567890125', 3.5, 'kg', 40, 0, 8, '2026-01-01 00:00:00', 0, 0, 1, '2026-01-29 00:00:00', '2026-01-29 00:00:00'),
(303, 1, 'KITCHEN-COFFEE-001', '3334567890126', 5, 'kg', 30, 0, 5, '2026-01-01 00:00:00', 0, 0, 1, '2026-01-29 00:00:00', '2026-01-29 00:00:00');

-- Product Descriptions (English)
INSERT INTO `#__virtuemart_products_en_gb` (`virtuemart_product_id`, `product_name`, `product_s_desc`, `product_desc`, `slug`, `metadesc`, `metakey`) VALUES
-- Electronics
(100, 'Professional Laptop 15"', '<p>High-performance laptop for professionals and creators.</p>', '<p>Experience unmatched performance with our Professional Laptop featuring a 15" display, powerful processor, and all-day battery life. Perfect for work, creative projects, and entertainment.</p><ul><li>15.6" Full HD Display</li><li>16GB RAM</li><li>512GB SSD</li><li>Latest generation processor</li><li>All-day battery life</li><li>Backlit keyboard</li></ul>', 'professional-laptop-15', 'Professional laptop, 15 inch, high performance', 'laptop, computer, professional, work'),
(101, 'Ultralight Laptop 13"', '<p>Portable powerhouse for on-the-go productivity.</p>', '<p>Take your work anywhere with this ultra-portable 13" laptop. Weighing just 1.8kg, it delivers impressive performance without compromising portability.</p><ul><li>13.3" Retina Display</li><li>8GB RAM</li><li>256GB SSD</li><li>Lightweight design</li><li>12-hour battery</li><li>Fast charging</li></ul>', 'ultralight-laptop-13', 'Ultralight laptop, portable, 13 inch', 'laptop, ultralight, portable, travel'),
(102, 'Smartphone Pro 12', '<p>Flagship smartphone with cutting-edge features.</p>', '<p>Capture stunning photos, enjoy smooth performance, and stay connected with our most advanced smartphone yet.</p><ul><li>6.5" OLED Display</li><li>Triple camera system</li><li>128GB Storage</li><li>5G connectivity</li><li>All-day battery</li><li>Water resistant</li></ul>', 'smartphone-pro-12', 'Flagship smartphone, 5G, triple camera', 'smartphone, 5G, mobile, phone'),
(103, 'Smartphone Max 14', '<p>The ultimate smartphone experience.</p>', '<p>Larger display, bigger battery, and enhanced camera system make this our most capable smartphone.</p><ul><li>6.8" ProMotion Display</li><li>256GB Storage</li><li>Pro camera system</li><li>5G Ultra Wideband</li><li>Extended battery life</li></ul>', 'smartphone-max-14', 'Premium smartphone, large display, pro camera', 'smartphone, premium, large, camera'),
(104, 'Wireless Headphones Pro', '<p>Premium wireless headphones with active noise cancellation.</p>', '<p>Immerse yourself in pure sound with industry-leading noise cancellation and premium audio quality.</p><ul><li>Active Noise Cancellation</li><li>30-hour battery life</li><li>Premium sound quality</li><li>Comfortable fit</li><li>Quick charge</li></ul>', 'wireless-headphones-pro', 'Wireless headphones, noise cancellation', 'headphones, wireless, noise cancelling'),
(105, 'Noise Cancelling Earbuds', '<p>Compact earbuds with powerful sound.</p>', '<p>Experience true wireless freedom with these compact earbuds featuring active noise cancellation.</p><ul><li>Active Noise Cancellation</li><li>8-hour battery (24h with case)</li><li>IPX4 water resistance</li><li>Touch controls</li><li>Crystal clear calls</li></ul>', 'noise-cancelling-earbuds', 'Earbuds, wireless, noise cancelling', 'earbuds, wireless, compact'),
-- Clothing
(200, 'Classic Men\'s Shirt - Blue', '<p>Timeless style meets modern comfort.</p>', '<p>Premium cotton shirt perfect for work or casual occasions. Classic fit with modern details.</p><ul><li>100% Premium Cotton</li><li>Wrinkle-resistant</li><li>Button-down collar</li><li>Available in multiple sizes</li><li>Machine washable</li></ul>', 'classic-mens-shirt-blue', 'Mens dress shirt, blue, cotton', 'shirt, mens, dress, casual'),
(201, 'Slim Fit Jeans - Indigo', '<p>Modern slim fit denim for everyday wear.</p>', '<p>Comfortable stretch denim in a modern slim fit. Perfect for any occasion.</p><ul><li>Stretch denim</li><li>Slim fit</li><li>Five-pocket design</li><li>Available sizes 28-40</li><li>Durable construction</li></ul>', 'slim-fit-jeans-indigo', 'Mens jeans, slim fit, indigo', 'jeans, mens, slim fit, denim'),
(202, 'Summer Floral Dress', '<p>Light and breezy summer dress.</p>', '<p>Perfect for warm weather, this floral dress combines style and comfort effortlessly.</p><ul><li>Lightweight fabric</li><li>Floral print</li><li>Comfortable fit</li><li>Available in S-XL</li><li>Easy care</li></ul>', 'summer-floral-dress', 'Womens dress, summer, floral', 'dress, womens, summer, floral'),
(203, 'Women\'s Casual Blouse', '<p>Versatile blouse for any occasion.</p>', '<p>Elegant yet casual, this blouse pairs well with jeans or skirts.</p><ul><li>Soft fabric</li><li>Relaxed fit</li><li>Button front</li><li>Available in S-XL</li><li>Multiple colors</li></ul>', 'womens-casual-blouse', 'Womens blouse, casual, white', 'blouse, womens, top, casual'),
(204, 'Athletic Running Shoes', '<p>Performance running shoes for serious athletes.</p>', '<p>Engineered for speed and comfort with responsive cushioning and breathable upper.</p><ul><li>Lightweight construction</li><li>Responsive cushioning</li><li>Breathable mesh</li><li>Sizes 7-13</li><li>Multiple colorways</li></ul>', 'athletic-running-shoes', 'Running shoes, athletic, performance', 'shoes, running, athletic, sports'),
(205, 'Casual Walking Shoes', '<p>All-day comfort for casual wear.</p>', '<p>Perfect for everyday wear with superior comfort and classic style.</p><ul><li>Comfortable insole</li><li>Durable outsole</li><li>Classic design</li><li>Sizes 7-13</li><li>Easy to clean</li></ul>', 'casual-walking-shoes', 'Casual shoes, walking, comfortable', 'shoes, casual, walking, comfort'),
-- Home & Garden
(300, 'Modern 3-Seater Sofa', '<p>Contemporary comfort for your living room.</p>', '<p>Stylish and comfortable sofa with premium upholstery and sturdy construction.</p><ul><li>Seats 3 people</li><li>Premium fabric</li><li>Solid wood frame</li><li>Available colors</li><li>Easy assembly</li></ul>', 'modern-3-seater-sofa', 'Sofa, 3-seater, modern, living room', 'sofa, furniture, living room'),
(301, 'Ergonomic Office Chair', '<p>Comfort and support for long work days.</p>', '<p>Adjustable ergonomic chair designed for all-day comfort and productivity.</p><ul><li>Lumbar support</li><li>Adjustable height</li><li>Breathable mesh</li><li>360° swivel</li><li>Weight capacity 300lbs</li></ul>', 'ergonomic-office-chair', 'Office chair, ergonomic, adjustable', 'chair, office, ergonomic, desk'),
(302, 'High-Speed Blender', '<p>Professional-grade blending power.</p>', '<p>Powerful blender for smoothies, soups, and more. Variable speed control and durable construction.</p><ul><li>1000W motor</li><li>Variable speed</li><li>64oz pitcher</li><li>Stainless steel blades</li><li>Easy to clean</li></ul>', 'high-speed-blender', 'Blender, high speed, kitchen', 'blender, kitchen, appliance'),
(303, 'Programmable Coffee Maker', '<p>Wake up to fresh brewed coffee.</p>', '<p>Program your perfect cup with this feature-rich coffee maker. Brew up to 12 cups.</p><ul><li>12-cup capacity</li><li>Programmable timer</li><li>Auto shutoff</li><li>Pause and serve</li><li>Permanent filter</li></ul>', 'programmable-coffee-maker', 'Coffee maker, programmable, 12 cup', 'coffee, maker, kitchen, appliance');

-- Product Prices
INSERT INTO `#__virtuemart_product_prices` (`virtuemart_product_price_id`, `virtuemart_product_id`, `virtuemart_shoppergroup_id`, `product_price`, `product_override_price`, `product_tax_id`, `product_discount_id`, `product_currency`, `price_quantity_start`, `price_quantity_end`, `created_on`, `modified_on`) VALUES
-- Electronics Prices
(1, 100, 0, 899.99, 0, 0, 0, 47, 0, 0, '2026-01-29 00:00:00', '2026-01-29 00:00:00'),
(2, 101, 0, 699.99, 0, 0, 0, 47, 0, 0, '2026-01-29 00:00:00', '2026-01-29 00:00:00'),
(3, 102, 0, 799.99, 699.99, 0, 0, 47, 0, 0, '2026-01-29 00:00:00', '2026-01-29 00:00:00'),
(4, 103, 0, 999.99, 0, 0, 0, 47, 0, 0, '2026-01-29 00:00:00', '2026-01-29 00:00:00'),
(5, 104, 0, 349.99, 0, 0, 0, 47, 0, 0, '2026-01-29 00:00:00', '2026-01-29 00:00:00'),
(6, 105, 0, 199.99, 0, 0, 0, 47, 0, 0, '2026-01-29 00:00:00', '2026-01-29 00:00:00'),
-- Clothing Prices
(7, 200, 0, 49.99, 0, 0, 0, 47, 0, 0, '2026-01-29 00:00:00', '2026-01-29 00:00:00'),
(8, 201, 0, 79.99, 59.99, 0, 0, 47, 0, 0, '2026-01-29 00:00:00', '2026-01-29 00:00:00'),
(9, 202, 0, 89.99, 0, 0, 0, 47, 0, 0, '2026-01-29 00:00:00', '2026-01-29 00:00:00'),
(10, 203, 0, 39.99, 0, 0, 0, 47, 0, 0, '2026-01-29 00:00:00', '2026-01-29 00:00:00'),
(11, 204, 0, 129.99, 0, 0, 0, 47, 0, 0, '2026-01-29 00:00:00', '2026-01-29 00:00:00'),
(12, 205, 0, 89.99, 0, 0, 0, 47, 0, 0, '2026-01-29 00:00:00', '2026-01-29 00:00:00'),
-- Home & Garden Prices
(13, 300, 0, 599.99, 0, 0, 0, 47, 0, 0, '2026-01-29 00:00:00', '2026-01-29 00:00:00'),
(14, 301, 0, 249.99, 199.99, 0, 0, 47, 0, 0, '2026-01-29 00:00:00', '2026-01-29 00:00:00'),
(15, 302, 0, 149.99, 0, 0, 0, 47, 0, 0, '2026-01-29 00:00:00', '2026-01-29 00:00:00'),
(16, 303, 0, 79.99, 0, 0, 0, 47, 0, 0, '2026-01-29 00:00:00', '2026-01-29 00:00:00');

-- Link Products to Categories
INSERT INTO `#__virtuemart_product_categories` (`id`, `virtuemart_product_id`, `virtuemart_category_id`, `ordering`) VALUES
-- Electronics
(1, 100, 10, 1),
(2, 101, 10, 2),
(3, 102, 11, 1),
(4, 103, 11, 2),
(5, 104, 12, 1),
(6, 105, 12, 2),
-- Clothing
(7, 200, 20, 1),
(8, 201, 20, 2),
(9, 202, 21, 1),
(10, 203, 21, 2),
(11, 204, 23, 1),
(12, 205, 23, 2),
-- Home & Garden
(13, 300, 30, 1),
(14, 301, 30, 2),
(15, 302, 32, 1),
(16, 303, 32, 2);
