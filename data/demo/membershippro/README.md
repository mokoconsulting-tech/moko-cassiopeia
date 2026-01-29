# MembershipPro Sample Data

This directory contains sample membership plans, groups, and configuration for MembershipPro (also known as Membership Pro or J2Store Membership).

## Contents

- `sample-data.sql` - Membership plans, groups, and sample configuration
- `README.md` - This file

## Prerequisites

1. **MembershipPro 3.x or 4.x** installed and configured
2. **Database backup** - Always backup before importing
3. **Joomla 4.x or 5.x** with Moko-Cassiopeia template

## What Gets Imported

### Membership Plans (3 tiers)
1. **Basic Membership** - $9.99/month
   - Access to basic resources
   - Community forum
   - Monthly newsletter

2. **Professional Membership** - $24.99/month
   - Everything in Basic
   - Premium resources
   - Monthly webinars
   - Priority support

3. **Enterprise Membership** - $49.99/month
   - Everything in Professional
   - 1-on-1 consulting
   - Custom training
   - VIP events
   - API access

### User Groups
- Basic Members
- Professional Members
- Enterprise Members
- Free Trial Members

### Configuration
- Payment methods (PayPal, Stripe)
- Email templates
- Access levels
- Subscription rules

## Installation

### Method 1: phpMyAdmin
```
1. Login to phpMyAdmin
2. Select your Joomla database
3. Import sample-data.sql
4. Verify in MembershipPro dashboard
```

### Method 2: Command Line
```bash
mysql -u username -p database_name < sample-data.sql
```

### Method 3: MembershipPro Admin
Some data can be configured through the MembershipPro admin interface:
1. Components > MembershipPro
2. Plans > Add New
3. Configure settings manually

## Post-Import Steps

1. **Configure Payment Gateway**
   - Setup PayPal/Stripe credentials
   - Test payment processing
   - Configure currency

2. **Email Templates**
   - Review welcome emails
   - Customize branding
   - Test email delivery

3. **Access Levels**
   - Verify Joomla user groups
   - Check article access
   - Test member-only content

4. **Subscription Rules**
   - Review renewal settings
   - Configure grace periods
   - Setup upgrade paths

5. **Integration**
   - Link to articles
   - Setup member dashboard
   - Configure menus

## Membership Features

- Recurring subscriptions
- Trial periods
- Proration on upgrades
- Automatic renewals
- Email notifications
- Member dashboard
- Coupon codes
- Gift subscriptions
- Group subscriptions
- Access control integration

## Customization

All plans can be customized:
- Adjust pricing
- Modify features
- Add/remove tiers
- Change billing cycles
- Update descriptions
- Configure trials

## Database Tables

This data affects these MembershipPro tables:
- `#__osmembership_plans`
- `#__osmembership_categories`
- `#__osmembership_fields`
- `#__osmembership_emailtemplates`
- `#__osmembership_coupons`

## Troubleshooting

**Plans not showing?**
- Check plan publish status
- Verify category assignment
- Clear Joomla cache

**Payment issues?**
- Configure payment plugin
- Test in sandbox mode
- Check gateway credentials

**Access not working?**
- Verify user group mapping
- Check access level configuration
- Test with different plan levels

## Support

For MembershipPro issues:
- Documentation: https://docs.joomdonation.com/membership-pro
- Support forum: https://joomdonation.com/forum
- Moko Consulting: hello@mokoconsulting.tech

## Warning

⚠️ **IMPORTANT**: This is sample data. Review and customize all plans, pricing, and features before going live. Always backup before importing.

## Version

Version: 1.0.0
Last Updated: 2026-01-29
Compatible with: MembershipPro 3.x/4.x, Joomla 4.x/5.x
