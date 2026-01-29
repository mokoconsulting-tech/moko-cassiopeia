-- Dolibarr Integration Configuration
-- Version: 1.0.0
-- Sample configuration for Joomla-Dolibarr integration
-- NOTE: This is reference data - actual integration requires custom plugin/development

-- Configuration table (example - actual implementation varies)
CREATE TABLE IF NOT EXISTS `#__dolibarr_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `config_key` varchar(255) NOT NULL,
  `config_value` text,
  `description` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `config_key` (`config_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- API Connection Settings
INSERT INTO `#__dolibarr_config` (`config_key`, `config_value`, `description`) VALUES
('dolibarr_url', 'https://erp.example.com', 'Base URL of Dolibarr installation'),
('dolibarr_api_key', '', 'API key for authentication - SET THIS'),
('api_enabled', '1', 'Enable/disable API integration'),
('api_timeout', '30', 'API request timeout in seconds'),
('verify_ssl', '1', 'Verify SSL certificates'),
('sync_enabled', '1', 'Enable automatic synchronization'),
('sync_direction', 'bidirectional', 'Sync direction: joomla_to_dolibarr, dolibarr_to_joomla, bidirectional'),
('sync_interval', '300', 'Sync interval in seconds (5 minutes)'),
('log_enabled', '1', 'Enable sync logging'),
('log_level', 'info', 'Log level: debug, info, warning, error');

-- Customer/Contact Field Mapping
INSERT INTO `#__dolibarr_config` (`config_key`, `config_value`, `description`) VALUES
('map_customer_enabled', '1', 'Enable customer sync'),
('map_customer_name', 'name|nom', 'Map Joomla name to Dolibarr nom'),
('map_customer_email', 'email|email', 'Map email field'),
('map_customer_phone', 'phone|phone', 'Map phone field'),
('map_customer_company', 'company|client.nom', 'Map company name'),
('map_customer_address', 'address_1|address', 'Map address field'),
('map_customer_city', 'city|town', 'Map city field'),
('map_customer_state', 'state|state', 'Map state/province'),
('map_customer_zip', 'zip|zip', 'Map postal code'),
('map_customer_country', 'country_code|country_code', 'Map country code'),
('map_customer_ref', 'id|ref_ext', 'Map Joomla user ID to Dolibarr external reference');

-- Product Field Mapping
INSERT INTO `#__dolibarr_config` (`config_key`, `config_value`, `description`) VALUES
('map_product_enabled', '1', 'Enable product sync'),
('map_product_name', 'product_name|label', 'Map product name'),
('map_product_sku', 'product_sku|ref', 'Map product SKU/reference'),
('map_product_desc', 'product_desc|description', 'Map product description'),
('map_product_price', 'product_price|price', 'Map product price'),
('map_product_weight', 'product_weight|weight', 'Map product weight'),
('map_product_stock', 'product_in_stock|stock_reel', 'Map stock quantity'),
('map_product_category', 'category|fk_product_type', 'Map product category'),
('map_product_ref', 'virtuemart_product_id|ref_ext', 'Map product ID to external reference');

-- Order Field Mapping
INSERT INTO `#__dolibarr_config` (`config_key`, `config_value`, `description`) VALUES
('map_order_enabled', '1', 'Enable order sync'),
('map_order_number', 'order_number|ref', 'Map order number'),
('map_order_date', 'created_on|date_commande', 'Map order date'),
('map_order_status', 'order_status|fk_statut', 'Map order status'),
('map_order_total', 'order_total|total_ttc', 'Map order total with tax'),
('map_order_subtotal', 'order_subtotal|total_ht', 'Map order subtotal without tax'),
('map_order_tax', 'order_tax|total_tva', 'Map tax amount'),
('map_order_shipping', 'order_shipment|total_port', 'Map shipping cost'),
('map_order_ref', 'virtuemart_order_id|ref_ext', 'Map order ID to external reference');

-- Order Status Mapping
INSERT INTO `#__dolibarr_config` (`config_key`, `config_value`, `description`) VALUES
('status_pending', 'P|0', 'VirtueMart Pending to Dolibarr Draft'),
('status_confirmed', 'C|1', 'VirtueMart Confirmed to Dolibarr Validated'),
('status_shipped', 'S|3', 'VirtueMart Shipped to Dolibarr Closed/Delivered'),
('status_cancelled', 'X|-1', 'VirtueMart Cancelled to Dolibarr Cancelled'),
('status_refunded', 'R|-1', 'VirtueMart Refunded to Dolibarr Cancelled');

-- Payment Method Mapping
INSERT INTO `#__dolibarr_config` (`config_key`, `config_value`, `description`) VALUES
('payment_cash', 'Cash|LIQ', 'Cash payment'),
('payment_check', 'Check|CHQ', 'Check payment'),
('payment_transfer', 'Bank Transfer|VIR', 'Bank transfer'),
('payment_creditcard', 'Credit Card|CB', 'Credit card'),
('payment_paypal', 'PayPal|PRE', 'PayPal'),
('payment_stripe', 'Stripe|PRE', 'Stripe');

-- Sync Log Table
CREATE TABLE IF NOT EXISTS `#__dolibarr_sync_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sync_type` varchar(50) NOT NULL COMMENT 'customer, product, order',
  `sync_direction` varchar(50) NOT NULL,
  `joomla_id` int(11) DEFAULT NULL,
  `dolibarr_id` int(11) DEFAULT NULL,
  `status` varchar(20) NOT NULL COMMENT 'success, error, pending',
  `message` text,
  `sync_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sync_type` (`sync_type`),
  KEY `status` (`status`),
  KEY `sync_date` (`sync_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sync Queue Table (for batch processing)
CREATE TABLE IF NOT EXISTS `#__dolibarr_sync_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sync_type` varchar(50) NOT NULL,
  `sync_direction` varchar(50) NOT NULL,
  `joomla_id` int(11) DEFAULT NULL,
  `priority` int(11) DEFAULT 5,
  `attempts` int(11) DEFAULT 0,
  `max_attempts` int(11) DEFAULT 3,
  `created_date` datetime NOT NULL,
  `scheduled_date` datetime NOT NULL,
  `processed_date` datetime DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `scheduled_date` (`scheduled_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Field Mapping Cache (stores last synced values)
CREATE TABLE IF NOT EXISTS `#__dolibarr_field_cache` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entity_type` varchar(50) NOT NULL COMMENT 'customer, product, order',
  `joomla_id` int(11) NOT NULL,
  `dolibarr_id` int(11) NOT NULL,
  `last_sync` datetime NOT NULL,
  `joomla_hash` varchar(64) DEFAULT NULL COMMENT 'MD5 of Joomla data',
  `dolibarr_hash` varchar(64) DEFAULT NULL COMMENT 'MD5 of Dolibarr data',
  PRIMARY KEY (`id`),
  UNIQUE KEY `entity_mapping` (`entity_type`, `joomla_id`),
  KEY `dolibarr_id` (`dolibarr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Webhook Configuration (for real-time sync)
INSERT INTO `#__dolibarr_config` (`config_key`, `config_value`, `description`) VALUES
('webhook_enabled', '0', 'Enable webhook notifications'),
('webhook_url_customer', '', 'Webhook URL for customer events'),
('webhook_url_product', '', 'Webhook URL for product events'),
('webhook_url_order', '', 'Webhook URL for order events'),
('webhook_secret', '', 'Webhook secret for verification');

-- Sample sync schedule (for cron jobs)
INSERT INTO `#__dolibarr_config` (`config_key`, `config_value`, `description`) VALUES
('cron_customer_sync', '0 */6 * * *', 'Sync customers every 6 hours'),
('cron_product_sync', '0 */4 * * *', 'Sync products every 4 hours'),
('cron_order_sync', '*/15 * * * *', 'Sync orders every 15 minutes'),
('cron_cleanup_logs', '0 2 * * 0', 'Cleanup old logs weekly');

-- Error Handling Configuration
INSERT INTO `#__dolibarr_config` (`config_key`, `config_value`, `description`) VALUES
('error_retry_enabled', '1', 'Enable automatic retry on errors'),
('error_retry_max', '3', 'Maximum retry attempts'),
('error_retry_delay', '300', 'Delay between retries in seconds'),
('error_notification_enabled', '1', 'Send email on persistent errors'),
('error_notification_email', 'admin@example.com', 'Email for error notifications');
