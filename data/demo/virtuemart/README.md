# VirtueMart Sample Data

This directory contains comprehensive sample data for VirtueMart, including products, categories, custom fields, and more.

## Contents

- `categories.sql` - Product categories with hierarchy
- `products.sql` - 50+ sample products
- `product-variants.sql` - Product variations (sizes, colors, etc.)
- `custom-fields.sql` - Custom product fields
- `featured-products.sql` - Featured product configuration
- `stock-management.sql` - Stock levels and tracking
- `manufacturers.sql` - Sample manufacturers/brands
- `product-images.sql` - Product image references (requires actual image files)

## Prerequisites

1. **VirtueMart 4.x** installed and configured
2. **Database backup** - ALWAYS backup before importing
3. **Joomla 4.x or 5.x** with Moko-Cassiopeia template
4. **MySQL/MariaDB** database access

## Installation Order

Import files in this specific order to maintain referential integrity:

```bash
1. manufacturers.sql
2. categories.sql
3. products.sql
4. custom-fields.sql
5. product-variants.sql
6. featured-products.sql
7. stock-management.sql
8. product-images.sql (optional - requires image files)
```

## Import Methods

### Method 1: phpMyAdmin
1. Log into phpMyAdmin
2. Select your Joomla database
3. Click "Import" tab
4. Choose SQL file
5. Click "Go"
6. Repeat for each file in order

### Method 2: Command Line
```bash
mysql -u username -p database_name < manufacturers.sql
mysql -u username -p database_name < categories.sql
mysql -u username -p database_name < products.sql
# ... continue with other files
```

### Method 3: Joomla Admin
1. Components > VirtueMart > Tools
2. Use import/export tools for specific data types

## What Gets Imported

### Categories (20+)
- Electronics (Computers, Phones, Accessories)
- Clothing (Men's, Women's, Kids')
- Home & Garden (Furniture, Decor, Tools)
- Sports & Outdoors (Equipment, Apparel)
- Books & Media

### Products (50+)
- Various price points ($9.99 - $999.99)
- Multiple variations (sizes, colors)
- Detailed descriptions
- Product specifications
- SKUs and barcodes
- Stock levels
- Featured products
- Sale items

### Custom Fields
- Size selector (XS, S, M, L, XL, XXL)
- Color picker
- Material type
- Warranty information
- Delivery time estimates
- Gift wrap options

### Manufacturers
- TechBrand Inc.
- StyleWear Co.
- HomeComfort
- SportsPro
- And more...

## Post-Import Steps

1. **Verify Import**
   - Check VirtueMart dashboard
   - View product listings
   - Test category navigation
   - Verify custom fields display

2. **Update Images**
   - Upload actual product images
   - Link images to products
   - Set featured images
   - Configure image sizes

3. **Configure Prices**
   - Review and adjust pricing
   - Set up tax rules
   - Configure shipping costs
   - Add promotional pricing

4. **Setup Payment**
   - Configure payment methods
   - Test checkout process
   - Setup order notifications

5. **Inventory Management**
   - Review stock levels
   - Configure low stock alerts
   - Setup stock tracking

## Customization

All sample data can be customized:

- Edit product descriptions
- Adjust pricing
- Change category structure
- Modify custom fields
- Update stock levels
- Add/remove variants

## Database Tables

This data affects these VirtueMart tables:

- `#__virtuemart_categories`
- `#__virtuemart_products`
- `#__virtuemart_product_customfields`
- `#__virtuemart_manufacturers`
- `#__virtuemart_product_prices`
- `#__virtuemart_product_medias`
- And related tables

**Note:** `#__` represents your Joomla table prefix (usually `jos_` or similar)

## Data Structure

### Sample Product Structure
```
Product
├── Basic Info (name, SKU, price)
├── Description (short & full)
├── Categories
├── Custom Fields (size, color, etc.)
├── Variants (if applicable)
├── Images (multiple)
├── Stock Level
├── Pricing Rules
└── Featured Status
```

## Troubleshooting

**Import errors?**
- Check table prefix in SQL files
- Verify VirtueMart version compatibility
- Ensure proper file order
- Check database permissions

**Missing products?**
- Clear VirtueMart cache
- Rebuild product indexes
- Check category visibility
- Verify publish status

**Images not showing?**
- Check file paths
- Upload images to correct directory
- Verify image permissions
- Clear image cache

## Support

For issues with sample data:
- Check VirtueMart documentation
- Visit VirtueMart forums
- Contact Moko Consulting support

## Warning

⚠️ **IMPORTANT**: This is sample data for demonstration purposes. Do NOT import into a production site with existing products unless you understand the implications. Always backup your database first!

## Version

Version: 1.0.0
Last Updated: 2026-01-29
Compatible with: VirtueMart 4.x, Joomla 4.x/5.x
