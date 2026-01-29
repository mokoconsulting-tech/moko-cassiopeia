-- MembershipPro Sample Data
-- Version: 1.0.0
-- Compatible with: MembershipPro 3.x/4.x
-- NOTE: Replace #__ with your actual Joomla table prefix

-- Membership Plan Categories
INSERT INTO `#__osmembership_categories` (`id`, `title`, `alias`, `description`, `published`, `ordering`) VALUES
(1, 'Individual Memberships', 'individual-memberships', '<p>Membership plans for individuals.</p>', 1, 1),
(2, 'Team Memberships', 'team-memberships', '<p>Membership plans for teams and organizations.</p>', 1, 2);

-- Membership Plans
INSERT INTO `#__osmembership_plans` (`id`, `category_id`, `title`, `alias`, `short_description`, `description`, `subscription_length`, `subscription_length_unit`, `price`, `trial_duration`, `trial_duration_unit`, `trial_price`, `recurring_subscription`, `lifetime_membership`, `enable_upgrade_downgrade`, `publish_up`, `publish_down`, `published`, `ordering`, `created_date`) VALUES
-- Basic Plan
(1, 1, 'Basic Membership', 'basic-membership', 
'<p>Essential access to our platform and resources.</p>', 
'<h3>Basic Membership Features</h3>
<ul>
<li>Access to basic resources and materials</li>
<li>Community forum participation</li>
<li>Monthly newsletter</li>
<li>Email support</li>
<li>Member directory listing</li>
</ul>
<p>Perfect for individuals getting started with our community.</p>', 
1, 'month', 9.99, 7, 'day', 0.00, 1, 0, 1, '2026-01-01 00:00:00', NULL, 1, 1, '2026-01-29 00:00:00'),

-- Professional Plan
(2, 1, 'Professional Membership', 'professional-membership',
'<p>Premium access with exclusive benefits and priority support.</p>',
'<h3>Professional Membership Features</h3>
<p><strong>Everything in Basic, plus:</strong></p>
<ul>
<li>Access to premium resources library</li>
<li>Monthly live webinars and workshops</li>
<li>Advanced training materials and courses</li>
<li>Priority email and chat support</li>
<li>Quarterly networking events</li>
<li>Exclusive member discounts (15% off store)</li>
<li>Early access to new features</li>
</ul>
<p>Ideal for professionals serious about growth and networking.</p>',
1, 'month', 24.99, 14, 'day', 0.00, 1, 0, 1, '2026-01-01 00:00:00', NULL, 1, 2, '2026-01-29 00:00:00'),

-- Enterprise Plan
(3, 1, 'Enterprise Membership', 'enterprise-membership',
'<p>Complete VIP access with personalized support and benefits.</p>',
'<h3>Enterprise Membership Features</h3>
<p><strong>Everything in Professional, plus:</strong></p>
<ul>
<li>Monthly 1-on-1 consulting sessions</li>
<li>Custom training programs tailored to your needs</li>
<li>Dedicated phone support line</li>
<li>VIP access to all events and conferences</li>
<li>Annual conference pass (value $500)</li>
<li>API access for integrations</li>
<li>Premium member badge and profile highlighting</li>
<li>Exclusive enterprise networking group</li>
<li>Complimentary guest passes (2 per year)</li>
</ul>
<p>Perfect for organizations and power users who need the best.</p>',
1, 'month', 49.99, 0, 'day', 0.00, 1, 0, 1, '2026-01-01 00:00:00', NULL, 1, 3, '2026-01-29 00:00:00'),

-- Annual Basic (discounted)
(4, 1, 'Basic Membership (Annual)', 'basic-membership-annual',
'<p>Save 20% with annual payment!</p>',
'<p>Same great Basic features with annual billing. Save $24 per year!</p>',
12, 'month', 95.99, 7, 'day', 0.00, 0, 0, 1, '2026-01-01 00:00:00', NULL, 1, 4, '2026-01-29 00:00:00'),

-- Team Plan
(5, 2, 'Team Membership (5 seats)', 'team-membership-5',
'<p>Professional membership for teams of up to 5 members.</p>',
'<h3>Team Membership Features</h3>
<ul>
<li>5 Professional-level memberships</li>
<li>Centralized billing and management</li>
<li>Team collaboration tools</li>
<li>Shared resource library</li>
<li>Team training sessions</li>
<li>Volume discount (save 15%)</li>
</ul>',
1, 'month', 106.21, 0, 'day', 0.00, 1, 0, 1, '2026-01-01 00:00:00', NULL, 1, 5, '2026-01-29 00:00:00');

-- Plan - User Group Mapping (determines Joomla access levels)
INSERT INTO `#__osmembership_plans` (`id`, `usergroup_id`) VALUES
(1, 2),  -- Basic -> Registered
(2, 10), -- Professional -> Special (create this group in Joomla)
(3, 11), -- Enterprise -> VIP (create this group in Joomla)
(4, 2),  -- Annual Basic -> Registered
(5, 10); -- Team -> Special

-- Email Templates
INSERT INTO `#__osmembership_emailtemplates` (`id`, `name`, `subject`, `body`, `published`) VALUES
(1, 'subscription_confirmation', 'Welcome to [PLAN_TITLE] Membership!',
'Dear [MEMBER_NAME],

Thank you for subscribing to [PLAN_TITLE]!

Your membership details:
- Plan: [PLAN_TITLE]
- Start Date: [FROM_DATE]
- End Date: [TO_DATE]
- Amount Paid: [AMOUNT]

You can access your member dashboard at: [MEMBER_DASHBOARD_URL]

If you have any questions, please don''t hesitate to contact us.

Best regards,
The Team', 1),

(2, 'subscription_renewal_reminder', 'Your membership renewal is coming up',
'Dear [MEMBER_NAME],

This is a friendly reminder that your [PLAN_TITLE] membership will renew on [TO_DATE].

Renewal Amount: [AMOUNT]

Your membership will automatically renew unless you cancel before the renewal date.

Manage your subscription: [MEMBER_DASHBOARD_URL]

Thank you for being a valued member!

Best regards,
The Team', 1),

(3, 'subscription_expired', 'Your membership has expired',
'Dear [MEMBER_NAME],

Your [PLAN_TITLE] membership expired on [TO_DATE].

We hope you enjoyed your membership benefits. You can renew anytime to regain access to all features.

Renew now: [RENEWAL_URL]

Best regards,
The Team', 1);

-- Custom Fields (optional registration fields)
INSERT INTO `#__osmembership_fields` (`id`, `name`, `title`, `field_type`, `values`, `required`, `published`, `ordering`) VALUES
(1, 'company', 'Company Name', 'text', '', 0, 1, 1),
(2, 'job_title', 'Job Title', 'text', '', 0, 1, 2),
(3, 'phone', 'Phone Number', 'text', '', 1, 1, 3),
(4, 'how_did_you_hear', 'How did you hear about us?', 'list', 'Search Engine\r\nSocial Media\r\nFriend Referral\r\nAdvertisement\r\nOther', 0, 1, 4),
(5, 'interests', 'Areas of Interest', 'checkboxes', 'Technology\r\nBusiness\r\nMarketing\r\nDesign\r\nDevelopment', 0, 1, 5);

-- Sample Coupon Codes
INSERT INTO `#__osmembership_coupons` (`id`, `code`, `discount`, `discount_amount`, `times`, `used`, `valid_from`, `valid_to`, `published`) VALUES
(1, 'WELCOME10', 1, 10.00, 100, 0, '2026-01-01 00:00:00', '2026-12-31 23:59:59', 1),
(2, 'ANNUAL20', 1, 20.00, 50, 0, '2026-01-01 00:00:00', '2026-12-31 23:59:59', 1),
(3, 'TRIAL7DAY', 2, 7.00, 200, 0, '2026-01-01 00:00:00', '2026-06-30 23:59:59', 1);

-- Configuration (these are typically stored in component params, but shown here for reference)
-- You would configure these in: Components > MembershipPro > Configuration
-- - Currency: USD
-- - Payment plugins: PayPal, Stripe
-- - Tax settings: Based on location
-- - Email from name and address
-- - Member dashboard URL
-- - Renewal settings
