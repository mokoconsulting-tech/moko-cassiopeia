# Dolibarr Integration Sample Data

This directory contains sample configuration and data mapping for integrating Joomla/VirtueMart with Dolibarr ERP/CRM.

## Contents

- `integration-config.sql` - Sample Dolibarr integration configuration
- `README.md` - This file

## Overview

Dolibarr is an open-source ERP and CRM system. This integration allows you to:
- Sync customers between Joomla and Dolibarr
- Sync products and orders from VirtueMart to Dolibarr
- Manage invoices and accounting
- Track inventory across systems

## Prerequisites

1. **Dolibarr** installed (14.x or later)
2. **Dolibarr API** enabled with API key
3. **VirtueMart** installed in Joomla
4. **Joomla-Dolibarr connector plugin** or custom integration

## Integration Methods

### Option 1: REST API Integration
Connect Joomla to Dolibarr using REST API:
- Real-time synchronization
- Bidirectional data flow
- Event-driven updates

### Option 2: Database Integration
Direct database connection (not recommended for production):
- Fast data sync
- Complex queries
- Security considerations

### Option 3: File-Based Integration
CSV export/import:
- Scheduled batch sync
- Manual control
- No real-time updates

## What Gets Configured

### API Connection
- Dolibarr URL
- API key/token
- Authentication method
- SSL/TLS settings

### Data Mapping
- Customer fields
- Product fields
- Order fields
- Tax codes
- Payment methods
- Shipping methods

### Sync Rules
- Sync direction (one-way or bidirectional)
- Sync frequency
- Conflict resolution
- Error handling

## Installation

### Step 1: Configure Dolibarr API

1. Log into Dolibarr admin
2. Go to Setup > Modules
3. Enable "Web services" module
4. Go to Home > Setup > Security
5. Generate API key for integration user

### Step 2: Import Configuration

```bash
# Import SQL configuration
mysql -u username -p database_name < integration-config.sql
```

### Step 3: Install Connector Plugin

Install a Joomla-Dolibarr connector plugin:
- Search Joomla Extensions Directory
- Or develop custom integration

### Step 4: Configure Mapping

Configure field mapping in plugin settings:
- Map Joomla user fields to Dolibarr contacts
- Map VirtueMart products to Dolibarr products
- Map order statuses

## Data Flow

### Customer Sync
```
Joomla User Registration
    ↓
Create/Update Dolibarr Contact (Third Party)
    ↓
Sync custom fields
    ↓
Assign to customer group
```

### Order Sync
```
VirtueMart Order Placed
    ↓
Create Dolibarr Order
    ↓
Create Invoice (when paid)
    ↓
Update Stock Levels
    ↓
Create Shipment
```

### Product Sync
```
VirtueMart Product Created/Updated
    ↓
Sync to Dolibarr Product
    ↓
Update pricing
    ↓
Sync stock levels
```

## Sample Mappings

### Customer Fields
| Joomla/VirtueMart | Dolibarr |
|-------------------|----------|
| name | nom (lastname) |
| email | email |
| username | ref_ext |
| company | client.nom |
| address | address |
| city | town |
| state | state |
| zip | zip |
| country | country_code |
| phone | phone |

### Product Fields
| VirtueMart | Dolibarr |
|------------|----------|
| product_name | label |
| product_sku | ref |
| product_desc | description |
| product_price | price |
| product_weight | weight |
| product_in_stock | stock_reel |
| virtuemart_product_id | ref_ext |

### Order Status Mapping
| VirtueMart Status | Dolibarr Status |
|-------------------|-----------------|
| Pending | Draft |
| Confirmed | Validated |
| Shipped | Closed (delivered) |
| Cancelled | Cancelled |
| Refunded | Cancelled (refunded) |

## Configuration Options

### API Settings
```php
// Sample configuration
$dolibarr_url = 'https://your-dolibarr-domain.com';
$dolibarr_api_key = 'your-api-key-here';
$sync_enabled = true;
$sync_direction = 'bidirectional'; // or 'joomla_to_dolibarr', 'dolibarr_to_joomla'
$sync_interval = 300; // seconds (5 minutes)
```

### Sync Settings
- Auto-sync on create/update
- Manual sync button
- Scheduled cron sync
- Real-time webhook sync

## Testing Integration

1. **Test Customer Sync**
   - Create test user in Joomla
   - Verify creation in Dolibarr
   - Update user details
   - Verify sync

2. **Test Product Sync**
   - Create test product in VirtueMart
   - Verify in Dolibarr products
   - Update price/stock
   - Verify sync

3. **Test Order Flow**
   - Place test order in VirtueMart
   - Verify order in Dolibarr
   - Update order status
   - Verify invoice creation

## Troubleshooting

**API Connection Fails**
- Verify API is enabled in Dolibarr
- Check API key is valid
- Verify SSL certificate
- Check firewall rules

**Data Not Syncing**
- Check sync logs
- Verify field mapping
- Check API permissions
- Test API endpoints manually

**Duplicate Records**
- Check unique identifier mapping
- Verify conflict resolution rules
- Review sync logs

## Security Considerations

- Use HTTPS/SSL for all API calls
- Rotate API keys regularly
- Limit API permissions to necessary operations
- Log all sync activities
- Monitor for unusual activity
- Backup before major syncs

## Support Resources

- Dolibarr Documentation: https://www.dolibarr.org/documentation
- Dolibarr API Docs: https://wiki.dolibarr.org/index.php/API
- Dolibarr Forum: https://www.dolibarr.org/forum
- Joomla Forums: https://forum.joomla.org
- Moko Consulting: hello@mokoconsulting.tech

## Notes

- This is sample configuration data
- Actual implementation requires custom development or plugin
- Always test in staging environment first
- Monitor sync performance and errors
- Plan for data migration and cleanup

## Version

Version: 1.0.0
Last Updated: 2026-01-29
Compatible with: Dolibarr 14.x+, Joomla 4.x/5.x
