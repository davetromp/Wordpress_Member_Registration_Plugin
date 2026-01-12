=== Member Registration ===
Contributors: dtntmedia
Donate link: https://dtntmedia.com/donate
Tags: members, registration, membership, user management
Requires at least: 5.0
Tested up to: 6.9
Stable tag: 1.2.1
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A comprehensive member registration and management system for sports clubs. Allows users to register and manage multiple members under one account.

== Description ==

Member Registration Plugin is a powerful yet easy-to-use WordPress plugin designed specifically for sports clubs and organizations that need to manage member registrations. 

**Key Features:**

* **Multiple Members Per Account** - Perfect for families! Parents can register multiple children or family members under a single account.
* **Custom Fields** - Create unlimited custom fields to collect any additional information you need.
* **Admin-Only Fields** - Some fields can be marked as "admin only" - visible to members but only editable by administrators. Great for tracking payment status, membership levels, etc.
* **Email Notifications** - Automatic activation emails sent to new members with customizable sender information.
* **CSV Import/Export** - Bulk import members from CSV files or export member data for reporting.
* **Frontend Member Area** - Beautiful, responsive member dashboard where users can view and update their information.
* **Member Admins** - Designate certain members as "member admins" who can help manage other members.
* **Multi-language Support** - Fully translatable with Dutch translation included.

**Perfect For:**

* Sports clubs (football, tennis, swimming, martial arts, etc.)
* Youth organizations
* Community clubs
* Any organization needing family-based member management

**Shortcodes:**

* `[mbrreg_member_area]` - Complete member area with login, registration, and dashboard
* `[mbrreg_login_form]` - Standalone login form
* `[mbrreg_register_form]` - Standalone registration form
* `[mbrreg_member_dashboard]` - Member dashboard (for logged-in users)

== Installation ==

1. Upload the `member-registration-plugin` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to 'Members' > 'Settings' to configure the plugin
4. Create a new page and add the `[mbrreg_member_area]` shortcode
5. Set this page as the "Member Area Page" in the plugin settings

Or

1. Download the plugin .zip file
2. Click add plugin in the Wordpress backend.
3. Click 'upload plugin' and select the downloaded plugin.
4. Click 'install now'
5. Activate the plugin 

== Frequently Asked Questions ==

= Can one user have multiple member profiles? =

Yes! This is one of the key features of the plugin. A single user account (identified by email) can have multiple member profiles. This is perfect for parents who want to register multiple children.

= What happens when I import members via CSV? =

When you import members, the plugin creates WordPress user accounts for each unique email address and sends activation emails. Members must click the activation link to activate their account. If an email already exists in the system, the new member will be added to that existing account.

= Can I create custom fields? =

Yes, you can create unlimited custom fields with various types: text, textarea, email, number, date, dropdown, checkbox, and radio buttons. Fields can be marked as required and/or admin-only.

= What are admin-only fields? =

Admin-only fields are visible to members but can only be edited by administrators. This is useful for tracking information like payment status, membership levels, or internal notes.

= Is the plugin translatable? =

Yes! The plugin is fully translatable and includes a Dutch translation. You can add translations for other languages using the provided POT file.

== Screenshots ==

1. Member dashboard showing multiple members under one account
2. Registration form with custom fields
3. Admin members list
4. Custom fields management
5. Import/Export page
6. Plugin settings

== Changelog ==

= 1.2.2 =
1. Fix: Membership deactivation by user not working, if there are any required fields

= 1.2.1 =
1. Fix 1: Admin Role Deactivation Not Working
2. Fix 2: "My Memberships" Menu Redirect Error

= 1.2.0 =
Adjustments Made:
1. Date Format Setting** - Added a new "Display Settings" section in settings with option to choose between European (DD/MM/YYYY) and US (MM/DD/YYYY) date formats. Helper functions `mbrreg_format_date()`, `mbrreg_parse_date()`, `mbrreg_get_date_format()`, and `mbrreg_get_date_placeholder()` handle date formatting throughout the plugin.
2. Removed Default Fields** - Removed address, telephone, date of birth, and place of birth from the default member fields. Only first_name and last_name remain as default personal details. A migration function was added to convert any existing data in these fields to custom fields automatically.
3. Multilingual Emails** - All email content is now translatable using the standard WordPress translation system. The Dutch translation file has been updated with complete translations for all email templates.
Fixes Made:
1. Modal Centering** - Fixed the CSS for modals to properly center on both desktop and mobile screens. The modal now uses fixed positioning with proper viewport centering. On mobile, the modal expands to cover the full screen.
2. Input Field Sizing** - Fixed input fields to properly fit within their form containers by adding `width: 100%`, `max-width: 100%`, and `box-sizing: border-box` to all input elements.
Files Changed:
- `member-registration-plugin.php` - Updated version, added date helper functions
- `includes/class-mbrreg-activator.php` - Simplified table schema, added migration
- `includes/class-mbrreg-database.php` - Removed old field references
- `includes/class-mbrreg-member.php` - Removed old field handling
- `includes/class-mbrreg-email.php` - Made emails translatable
- `includes/class-mbrreg-admin.php` - Added date format setting registration
- `admin/partials/mbrreg-admin-settings.php` - Added date format option, simplified required fields
- `admin/partials/mbrreg-admin-member-edit.php` - Simplified form
- `admin/partials/mbrreg-admin-members.php` - Updated date display
- `admin/partials/mbrreg-admin-import-export.php` - Updated CSV format
- `public/partials/mbrreg-register-form.php` - Simplified form
- `public/partials/mbrreg-member-dashboard.php` - Simplified form
- `public/css/mbrreg-public.css` - Fixed modal and input styling
- `languages/member-registration-plugin-nl_NL.po` - Added email translations

= 1.1.0 =
* Added: Support for multiple members per user account
* Added: Admin-only custom fields
* Added: CSV import with automatic activation emails
* Added: CSV export functionality
* Added: Custom modal dialogs (replacing JavaScript alerts)
* Added: Dutch translation
* Added: "My Memberships" menu item in WordPress admin for regular users
* Fixed: Form submission now properly refreshes the page after success
* Improved: Better handling of member deactivation when multiple members exist

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.1.0 =
Major update with support for multiple members per account, CSV import/export, and admin-only fields. Database will be automatically updated upon activation.