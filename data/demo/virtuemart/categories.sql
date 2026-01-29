-- VirtueMart Sample Categories
-- Version: 1.0.0
-- Compatible with: VirtueMart 4.x
-- NOTE: Replace #__ with your actual Joomla table prefix (e.g., jos_)

-- Root Categories
INSERT INTO `#__virtuemart_categories_en_gb` (`virtuemart_category_id`, `category_name`, `category_description`, `metadesc`, `metakey`, `slug`) VALUES
(1, 'Electronics', '<p>Shop the latest electronics, computers, phones, and accessories.</p>', 'Electronics, computers, phones, tablets, accessories', 'electronics, technology, gadgets', 'electronics'),
(2, 'Clothing & Apparel', '<p>Fashion for everyone - mens, womens, and kids clothing.</p>', 'Clothing, fashion, apparel, mens, womens, kids', 'clothing, fashion, apparel', 'clothing-apparel'),
(3, 'Home & Garden', '<p>Everything for your home - furniture, decor, garden tools, and more.</p>', 'Home, garden, furniture, decor, tools', 'home, garden, furniture', 'home-garden'),
(4, 'Sports & Outdoors', '<p>Gear up for adventure with sports equipment and outdoor essentials.</p>', 'Sports, outdoors, equipment, camping, fitness', 'sports, outdoors, fitness', 'sports-outdoors'),
(5, 'Books & Media', '<p>Books, ebooks, audiobooks, and entertainment media.</p>', 'Books, ebooks, audiobooks, media, entertainment', 'books, media, reading', 'books-media');

-- Electronics Subcategories
INSERT INTO `#__virtuemart_categories_en_gb` (`virtuemart_category_id`, `category_name`, `category_description`, `slug`) VALUES
(10, 'Computers & Laptops', '<p>Desktop computers, laptops, and computer accessories.</p>', 'computers-laptops'),
(11, 'Smartphones & Tablets', '<p>Latest smartphones, tablets, and mobile devices.</p>', 'smartphones-tablets'),
(12, 'Audio & Headphones', '<p>Headphones, earbuds, speakers, and audio equipment.</p>', 'audio-headphones'),
(13, 'Camera & Photography', '<p>Digital cameras, lenses, and photography accessories.</p>', 'camera-photography'),
(14, 'TV & Video', '<p>Televisions, streaming devices, and video equipment.</p>', 'tv-video');

-- Clothing Subcategories
INSERT INTO `#__virtuemart_categories_en_gb` (`virtuemart_category_id`, `category_name`, `category_description`, `slug`) VALUES
(20, 'Mens Clothing', '<p>Mens shirts, pants, jackets, and accessories.</p>', 'mens-clothing'),
(21, 'Womens Clothing', '<p>Womens dresses, tops, bottoms, and accessories.</p>', 'womens-clothing'),
(22, 'Kids Clothing', '<p>Clothing for boys and girls of all ages.</p>', 'kids-clothing'),
(23, 'Shoes & Footwear', '<p>Shoes, boots, sandals, and athletic footwear.</p>', 'shoes-footwear'),
(24, 'Accessories', '<p>Bags, hats, scarves, jewelry, and fashion accessories.</p>', 'accessories');

-- Home & Garden Subcategories
INSERT INTO `#__virtuemart_categories_en_gb` (`virtuemart_category_id`, `category_name`, `category_description`, `slug`) VALUES
(30, 'Furniture', '<p>Living room, bedroom, dining, and office furniture.</p>', 'furniture'),
(31, 'Home Decor', '<p>Wall art, lighting, rugs, and decorative items.</p>', 'home-decor'),
(32, 'Kitchen & Dining', '<p>Cookware, dinnerware, and kitchen appliances.</p>', 'kitchen-dining'),
(33, 'Garden & Outdoor', '<p>Garden tools, plants, patio furniture, and outdoor living.</p>', 'garden-outdoor'),
(34, 'Home Improvement', '<p>Tools, hardware, and home improvement supplies.</p>', 'home-improvement');

-- Sports Subcategories
INSERT INTO `#__virtuemart_categories_en_gb` (`virtuemart_category_id`, `category_name`, `category_description`, `slug`) VALUES
(40, 'Fitness Equipment', '<p>Home gym equipment, weights, and fitness accessories.</p>', 'fitness-equipment'),
(41, 'Team Sports', '<p>Equipment for football, basketball, baseball, and more.</p>', 'team-sports'),
(42, 'Outdoor Recreation', '<p>Camping, hiking, and outdoor adventure gear.</p>', 'outdoor-recreation'),
(43, 'Athletic Apparel', '<p>Sports clothing, activewear, and athletic shoes.</p>', 'athletic-apparel'),
(44, 'Water Sports', '<p>Swimming, surfing, diving, and water sports equipment.</p>', 'water-sports');

-- Books Subcategories
INSERT INTO `#__virtuemart_categories_en_gb` (`virtuemart_category_id`, `category_name`, `category_description`, `slug`) VALUES
(50, 'Fiction', '<p>Novels, short stories, and literary fiction.</p>', 'fiction'),
(51, 'Non-Fiction', '<p>Biographies, history, science, and educational books.</p>', 'non-fiction'),
(52, 'Childrens Books', '<p>Books for babies, toddlers, kids, and young adults.</p>', 'childrens-books'),
(53, 'Ebooks & Audiobooks', '<p>Digital books and audio formats.</p>', 'ebooks-audiobooks'),
(54, 'Magazines & Journals', '<p>Periodicals, magazines, and academic journals.</p>', 'magazines-journals');

-- Category Hierarchy (parent-child relationships)
INSERT INTO `#__virtuemart_categories` (`virtuemart_category_id`, `virtuemart_vendor_id`, `category_parent_id`, `ordering`, `published`) VALUES
-- Root categories
(1, 1, 0, 1, 1),
(2, 1, 0, 2, 1),
(3, 1, 0, 3, 1),
(4, 1, 0, 4, 1),
(5, 1, 0, 5, 1),
-- Electronics subcategories
(10, 1, 1, 1, 1),
(11, 1, 1, 2, 1),
(12, 1, 1, 3, 1),
(13, 1, 1, 4, 1),
(14, 1, 1, 5, 1),
-- Clothing subcategories
(20, 1, 2, 1, 1),
(21, 1, 2, 2, 1),
(22, 1, 2, 3, 1),
(23, 1, 2, 4, 1),
(24, 1, 2, 5, 1),
-- Home & Garden subcategories
(30, 1, 3, 1, 1),
(31, 1, 3, 2, 1),
(32, 1, 3, 3, 1),
(33, 1, 3, 4, 1),
(34, 1, 3, 5, 1),
-- Sports subcategories
(40, 1, 4, 1, 1),
(41, 1, 4, 2, 1),
(42, 1, 4, 3, 1),
(43, 1, 4, 4, 1),
(44, 1, 4, 5, 1),
-- Books subcategories
(50, 1, 5, 1, 1),
(51, 1, 5, 2, 1),
(52, 1, 5, 3, 1),
(53, 1, 5, 4, 1),
(54, 1, 5, 5, 1);
