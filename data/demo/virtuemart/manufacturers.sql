-- VirtueMart Manufacturers/Brands Sample Data
-- Version: 1.0.0

-- Sample Manufacturers
INSERT INTO `#__virtuemart_manufacturers` (`virtuemart_manufacturer_id`, `virtuemart_vendor_id`, `mf_name`, `slug`, `mf_email`, `mf_url`, `published`, `ordering`, `created_on`, `modified_on`) VALUES
(1, 1, 'TechBrand Inc.', 'techbrand-inc', 'info@techbrand.example', 'https://techbrand.example', 1, 1, '2026-01-29 00:00:00', '2026-01-29 00:00:00'),
(2, 1, 'StyleWear Co.', 'stylewear-co', 'contact@stylewear.example', 'https://stylewear.example', 1, 2, '2026-01-29 00:00:00', '2026-01-29 00:00:00'),
(3, 1, 'HomeComfort', 'homecomfort', 'sales@homecomfort.example', 'https://homecomfort.example', 1, 3, '2026-01-29 00:00:00', '2026-01-29 00:00:00'),
(4, 1, 'SportsPro', 'sportspro', 'info@sportspro.example', 'https://sportspro.example', 1, 4, '2026-01-29 00:00:00', '2026-01-29 00:00:00'),
(5, 1, 'AudioTech', 'audiotech', 'support@audiotech.example', 'https://audiotech.example', 1, 5, '2026-01-29 00:00:00', '2026-01-29 00:00:00');

-- Manufacturer descriptions
INSERT INTO `#__virtuemart_manufacturers_en_gb` (`virtuemart_manufacturer_id`, `mf_desc`, `metadesc`, `metakey`) VALUES
(1, '<p>Leading technology manufacturer providing cutting-edge electronics and computer equipment.</p>', 'Technology manufacturer, electronics, computers', 'technology, electronics, computers'),
(2, '<p>Fashion brand offering stylish and comfortable clothing for men, women, and children.</p>', 'Fashion brand, clothing, apparel', 'fashion, clothing, style'),
(3, '<p>Home furniture and decor solutions for modern living spaces.</p>', 'Home furniture, decor, modern living', 'furniture, home, decor'),
(4, '<p>Professional sports equipment and athletic gear for all levels.</p>', 'Sports equipment, athletic gear', 'sports, fitness, equipment'),
(5, '<p>Premium audio equipment manufacturer specializing in headphones and speakers.</p>', 'Audio equipment, headphones, speakers', 'audio, headphones, sound');

-- Link Products to Manufacturers
INSERT INTO `#__virtuemart_product_manufacturers` (`virtuemart_product_id`, `virtuemart_manufacturer_id`) VALUES
-- Electronics
(100, 1), -- Laptop by TechBrand
(101, 1),
(102, 1), -- Smartphone by TechBrand
(103, 1),
(104, 5), -- Headphones by AudioTech
(105, 5),
-- Clothing
(200, 2), -- Shirt by StyleWear
(201, 2),
(202, 2),
(203, 2),
(204, 4), -- Shoes by SportsPro
(205, 2),
-- Home & Garden
(300, 3), -- Furniture by HomeComfort
(301, 3),
(302, 3),
(303, 3);
