# Moko-Cassiopeia Demo Data

This directory contains sample/demo data for the Moko-Cassiopeia Joomla template, designed to help you quickly set up different types of websites.

## Overview

The demo data is organized into modular sets that can be imported separately based on your needs:

- **Articles**: Sample Joomla content in j2xml format
- **VirtueMart**: E-commerce sample data (products, categories, etc.)
- **MembershipPro**: Membership management sample data
- **Dolibarr**: ERP/CRM integration sample data

## Directory Structure

```
/data/demo/
├── README.md (this file)
├── articles/
│   ├── basic-website/      # Standard website content
│   ├── ecommerce/          # E-commerce focused content
│   ├── membership/         # Membership site content
│   └── community/          # Community/forum site content
├── virtuemart/             # VirtueMart sample products & settings
├── membershippro/          # MembershipPro sample data
└── dolibarr/               # Dolibarr integration configuration
```

## Prerequisites

Before importing demo data, ensure you have:

1. **Joomla 4.x or 5.x** installed
2. **Moko-Cassiopeia template** installed and activated
3. Required extensions installed:
   - **j2xml** component (for importing article content)
   - **VirtueMart** (if using e-commerce data)
   - **MembershipPro** (if using membership data)
   - **Dolibarr connector** (if using ERP integration)

## Installation Order

For best results, import data in this order:

1. **Articles** - Choose one or more article sets based on your site type
2. **VirtueMart** - If building an e-commerce site
3. **MembershipPro** - If building a membership site
4. **Dolibarr** - If integrating with Dolibarr ERP/CRM

## Quick Start

### 1. Import Articles

Articles are provided in j2xml format for easy import:

```bash
# Install j2xml component first
# Then import via Joomla admin: Components > j2xml > Import
```

Choose the article set that matches your needs:
- `articles/basic-website/` - Standard informational website
- `articles/ecommerce/` - Product pages, shopping guides, policies
- `articles/membership/` - Member resources, restricted content
- `articles/community/` - Forums, user profiles, community pages

See individual README files in each directory for detailed instructions.

### 2. Import VirtueMart Data (Optional)

If you're building an e-commerce site:

```bash
# Import via phpMyAdmin or MySQL command line
mysql -u username -p database_name < virtuemart/categories.sql
mysql -u username -p database_name < virtuemart/products.sql
# ... continue with other files
```

See `virtuemart/README.md` for detailed instructions.

### 3. Import MembershipPro Data (Optional)

For membership sites:

```bash
mysql -u username -p database_name < membershippro/sample-data.sql
```

See `membershippro/README.md` for detailed instructions.

### 4. Configure Dolibarr Integration (Optional)

For ERP integration:

See `dolibarr/README.md` for configuration instructions.

## Customization

All demo data is meant to be a starting point. You can:

- Edit content to match your brand
- Add/remove products
- Modify categories and structures
- Adjust pricing and stock levels
- Customize field configurations

## Data Contents Summary

### Articles
- **Basic Website**: About, Services, Contact, Privacy Policy, Terms
- **E-commerce**: Product Guides, Shipping Info, Returns Policy, FAQ
- **Membership**: Member Benefits, Subscription Tiers, Member Resources
- **Community**: Forum Guidelines, User Profiles, Community Events

### VirtueMart
- 50+ sample products across multiple categories
- Product variants (sizes, colors)
- Custom product fields
- Featured products configuration
- Stock management examples
- Category hierarchy

### MembershipPro
- Sample membership plans
- Member groups
- Access levels
- Sample member profiles

### Dolibarr
- API connection configuration
- Product synchronization settings
- Customer data mapping

## Support

For issues or questions:
- Template issues: https://github.com/mokoconsulting-tech/moko-cassiopeia/issues
- Documentation: See README files in each subdirectory
- Moko Consulting: hello@mokoconsulting.tech

## License

This demo data is provided under the same GPL-3.0-or-later license as the Moko-Cassiopeia template.

## Version

Demo Data Version: 03.06.01
Compatible with: Moko-Cassiopeia 03.06.x

---

**Note**: This is sample data for demonstration purposes. Always backup your database before importing any data.
