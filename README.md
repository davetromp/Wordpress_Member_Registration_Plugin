# Member Registration Plugin for WordPress

A comprehensive member registration and management system designed for sports clubs and organizations.

## 🎯 Project Overview

This plugin was developed to provide sports clubs with an easy-to-use member management system that supports:

- **Family accounts**: One user can manage multiple members (e.g., parents managing children)
- **Custom data collection**: Create custom fields for any additional member information
- **Admin-controlled fields**: Some fields can only be edited by administrators
- **Bulk operations**: Import/export members via CSV files
- **Email automation**: Automatic activation and welcome emails

## 🤖 Built with AI Collaboration

This project was developed collaboratively with Claude (Anthropic's AI assistant). The development process involved:

1. **Initial Requirements Gathering**: The project started with a specification for a WordPress plugin for sports club member management.

2. **Iterative Development**: Features were added and refined through conversation:
   - Core registration and login functionality
   - Custom fields system
   - Admin management interface
   - Multiple members per account
   - CSV import/export
   - Translation support

3. **Bug Fixes and Improvements**: Issues discovered during testing were communicated back and fixed, such as:
   - Form submission refresh issues
   - JavaScript alert replacement with custom modals

4. **Documentation**: All code was documented with inline comments and this README was created.

### Development Approach

The AI assistant provided:
- Complete PHP class files following WordPress coding standards
- JavaScript for frontend and admin functionality
- CSS styling for responsive design
- SQL table schemas for data storage
- Translation-ready strings and Dutch translation file

All code was reviewed and tested by the human developer before implementation.

## 📁 File Structure
member-registration-plugin/
├── member-registration-plugin.php    # Main plugin file
├── readme.txt                         # WordPress.org readme
├── README.md                          # This file
├── includes/
│   ├── class-mbrreg-activator.php    # Activation hooks
│   ├── class-mbrreg-deactivator.php  # Deactivation hooks
│   ├── class-mbrreg-database.php     # Database operations
│   ├── class-mbrreg-member.php       # Member business logic
│   ├── class-mbrreg-custom-fields.php # Custom fields handler
│   ├── class-mbrreg-email.php        # Email functionality
│   ├── class-mbrreg-ajax.php         # AJAX handlers
│   ├── class-mbrreg-shortcodes.php   # Shortcode definitions
│   ├── class-mbrreg-admin.php        # Admin functionality
│   └── class-mbrreg-public.php       # Public functionality
├── admin/
│   ├── css/
│   │   └── mbrreg-admin.css          # Admin styles
│   ├── js/
│   │   └── mbrreg-admin.js           # Admin JavaScript
│   └── partials/
│       ├── mbrreg-admin-members.php  # Members list page
│       ├── mbrreg-admin-member-edit.php # Member edit page
│       ├── mbrreg-admin-custom-fields.php # Custom fields page
│       ├── mbrreg-admin-import-export.php # Import/export page
│       └── mbrreg-admin-settings.php # Settings page
├── public/
│   ├── css/
│   │   └── mbrreg-public.css         # Public styles
│   ├── js/
│   │   └── mbrreg-public.js          # Public JavaScript
│   └── partials/
│       ├── mbrreg-login-form.php     # Login form template
│       ├── mbrreg-register-form.php  # Registration form template
│       ├── mbrreg-member-dashboard.php # Dashboard template
│       └── mbrreg-modal.php          # Modal dialogs template
└── languages/
└── member-registration-plugin-nl_NL.po # Dutch translation

## 🚀 Installation

1. Download or clone this repository
2. Upload to `/wp-content/plugins/member-registration-plugin/`
3. Activate the plugin in WordPress admin
4. Go to **Members → Settings** to configure
5. Create a page with the shortcode `[mbrreg_member_area]`
6. Set this page as the "Member Area Page" in settings

## 🔧 Configuration

### Settings

- **Allow Registration**: Enable/disable new registrations
- **Allow Multiple Members**: Allow users to add family members
- **Member Area Page**: Page containing the member area shortcode
- **Login Redirect Page**: Where to redirect after login
- **Required Fields**: Select which fields are mandatory
- **Email Settings**: Customize sender name and address

### Custom Fields

Create custom fields from **Members → Custom Fields**:

- **Field Types**: Text, Textarea, Email, Number, Date, Dropdown, Checkbox, Radio
- **Admin Only**: If checked, only admins can edit (users can view)
- **Required**: Field must be filled out
- **Display Order**: Control the order fields appear

## 📥 Import/Export

### Importing Members

1. Go to **Members → Import / Export**
2. Download the sample CSV to see the required format
3. Prepare your CSV with member data
4. Upload and import
5. Members receive activation emails automatically

### Exporting Members

1. Go to **Members → Import / Export**
2. Select status filter (optional)
3. Click "Export Members"
4. CSV file downloads automatically

## 🌐 Translations

The plugin is translation-ready with a Dutch translation included.

To add a new translation:

1. Copy `languages/member-registration-plugin-nl_NL.po`
2. Rename to your locale (e.g., `member-registration-plugin-de_DE.po`)
3. Translate the strings using a tool like Poedit
4. Generate the `.mo` file

## 📋 Shortcodes

| Shortcode | Description |
|-----------|-------------|
| `[mbrreg_member_area]` | Complete member area with tabs for login/register + dashboard |
| `[mbrreg_login_form]` | Standalone login form |
| `[mbrreg_register_form]` | Standalone registration form |
| `[mbrreg_member_dashboard]` | Member dashboard (logged-in users only) |

## 🔒 Security

- All form submissions use WordPress nonces
- Data is sanitized and validated before database operations
- Prepared statements used for all database queries
- Capability checks for admin functions
- Password hashing handled by WordPress core

## 📄 License

GPL-2.0+ - See LICENSE file for details.

## 🙏 Acknowledgments

- Built collaboratively with Claude AI by Anthropic
- Uses WordPress coding standards and best practices
- Icons from WordPress Dashicons

---

*This plugin was developed as a demonstration of AI-assisted software development. The human developer provided requirements, testing, and feedback, while the AI assistant generated the code and documentation.*