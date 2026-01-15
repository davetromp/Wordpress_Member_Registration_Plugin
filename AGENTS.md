# AGENTS.md

This document contains guidelines and instructions for agentic coding agents working on the WordPress Member Registration Plugin codebase.

## Quick Start

This is a WordPress plugin built with PHP 8.2+ following WordPress coding standards. The plugin provides member registration and management functionality for sports clubs.

**Key Characteristics:**
- Class-based architecture with dependency injection
- Strict WordPress standards compliance
- No build tools or testing framework (manual testing only)
- Strong security practices with proper sanitization
- Internationalization-ready with Dutch translation included

---

## Development Environment Setup

### Prerequisites
- WordPress 5.0+ (tested with latest WordPress)
- PHP 8.2+ 
- MySQL 5.7+ or MariaDB 10.2+
- Web server (Apache/Nginx)

### Installation
1. Clone repository to `/wp-content/plugins/member-registration-plugin/`
2. Activate plugin in WordPress admin
3. Go to **Members → Settings** to configure
4. Create a page with shortcode `[mbrreg_member_area]`

---

## Build, Lint, and Test Commands

### ⚠️ Important: No Automated Testing
This codebase has **NO automated testing framework**, **NO linting tools**, and **NO build process**. All quality assurance is manual.

### Manual Testing Workflow
```bash
# Plugin activation testing
# 1. Activate plugin in WordPress admin
# 2. Check for activation errors in debug log
# 3. Verify database tables are created

# Manual testing checklist:
# - Registration form submission
# - Login functionality  
# - Admin member management
# - CSV import/export
# - Custom fields creation/editing
# - Email notifications
```

### Code Quality (Manual)
```bash
# Manual code review checklist:
# - WordPress coding standards compliance
# - Security: sanitize input, escape output
# - Database: use $wpdb->prepare()
# - Internationalization: all user text in __()/_e()
# - Documentation: PHPDoc blocks present
```

### Debug Mode
```bash
# WordPress debugging in wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

---

## Code Style Guidelines

### PHP Standards (WordPress)

**File Structure:**
- One class per file
- Filename: `class-{classname}.php`
- No PHP closing tag `?>` in pure PHP files
- Security check at top: `if (!defined('WPINC')) { die; }`

**Naming Conventions:**
```php
// Classes: StudlyCaps with prefix
class Mbrreg_Database { }

// Functions: snake_case with prefix  
function mbrreg_activate() { }

// Methods: snake_case
public function get_member_by_id() { }

// Variables: snake_case
$member_id = $value;

// Constants: UPPERCASE
define('MBRREG_VERSION', '1.2.1');
```

**Code Formatting:**
```php
// Indentation: Tabs for indent, spaces for alignment
// Braces: K&R style for control structures, next line for functions/classes

if ( $condition ) {  // Space after if
    // Tab indented content
} elseif ( $other_condition ) {
    // Content
} else {
    // Content
}

function my_function( $param1, $param2 = null ) {  // Brace on next line
    // Function body
}

// Arrays: Short syntax, trailing comma in multi-line
$data = [
    'key1' => 'value1',
    'key2' => 'value2',  // Trailing comma
];
```

**Security Requirements:**
```php
// Input sanitization
$safe_text = sanitize_text_field( $_POST['user_input'] );
$safe_email = sanitize_email( $_POST['email'] );

// Output escaping
echo esc_html( $unsafe_text );
echo esc_url( $url );
echo esc_attr( $attribute );

// Database queries
$results = $wpdb->prepare(
    "SELECT * FROM {$wpdb->prefix}mbrreg_members WHERE id = %d",
    $member_id
);

// Nonce verification
if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'mbrreg_action' ) ) {
    wp_die( 'Security check failed' );
}
```

**Internationalization:**
```php
// Text domain: 'member-registration-plugin'
__( 'Register New Member', 'member-registration-plugin' );
_e( 'Member Dashboard', 'member-registration-plugin' );
$x_result = _x( 'Post', 'noun: blog post', 'member-registration-plugin' );
```

### JavaScript Standards

```javascript
// Use IIFE pattern, strict mode
(function($) {
    'use strict';

    // Object pattern for organization
    const MbrregPublic = {
        init: function() {
            this.bindEvents();
        },

        bindEvents: function() {
            // Event delegation
            $(document).on('click', '.mbrreg-button', this.handleClick);
        },

        handleClick: function(e) {
            e.preventDefault();
            // Handle click
        }
    };

    // Initialize when ready
    $(document).ready(function() {
        MbrregPublic.init();
    });

})(jQuery);
```

### CSS Standards

```css
/* Class naming: prefixed with mbrreg- */
.mbrreg-admin-wrap {
    max-width: 1200px;
}

.mbrreg-button {
    /* Component-based organization */
    display: inline-block;
    padding: 8px 16px;
}

.mbrreg-button--primary {
    /* BEM-style modifiers */
    background-color: #0073aa;
}

/* Responsive design */
@media (max-width: 768px) {
    .mbrreg-admin-columns {
        flex-direction: column;
    }
}
```

---

## Architecture and Patterns

### Class Structure

**Core Classes (in includes/):**
- `Mbrreg_Database` - All database operations
- `Mbrreg_Member` - Member business logic  
- `Mbrreg_Custom_Fields` - Dynamic field system
- `Mbrreg_Email` - Email notifications
- `Mbrreg_Ajax` - AJAX handlers
- `Mbrreg_Admin` - Admin functionality
- `Mbrreg_Public` - Frontend functionality
- `Mbrreg_Shortcodes` - WordPress shortcodes

### Dependency Injection Pattern
```php
class Mbrreg_Member {
    private $database;
    
    public function __construct( Mbrreg_Database $database ) {
        $this->database = $database;
    }
}
```

### Hooks System
```php
// Actions
add_action( 'init', [ $this, 'init' ] );
add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );

// Filters  
add_filter( 'plugin_row_meta', [ $this, 'add_plugin_links' ], 10, 2 );
```

### Database Schema

**Three main tables:**
- `wp_mbrreg_members` - Member records
- `wp_mbrreg_custom_fields` - Field definitions  
- `wp_mbrreg_member_meta` - Member field values

---

## Security Checklist

### Before Committing Code
- [ ] All user input sanitized (`sanitize_*` functions)
- [ ] All output escaped (`esc_*` functions)
- [ ] Database queries use `$wpdb->prepare()`
- [ ] Nonce verification for forms/AJAX
- [ ] Capability checks for admin functions
- [ ] SQL injection protection verified
- [ ] XSS protection implemented

### Critical Security Points
- Form submissions: sanitize, validate, nonce
- Database operations: prepared statements only
- File uploads: proper validation and sanitization
- AJAX endpoints: capability checks + nonces
- User capabilities: `current_user_can()` checks

---

## Plugin Development Workflow

### Adding New Features
1. Create class in `includes/` following naming convention
2. Add activation/deactivation hooks if needed
3. Implement admin interface in `admin/partials/`
4. Add public-facing templates in `public/partials/`
5. Update database schema if needed
6. Add internationalization strings
7. Test manually in WordPress environment

### Modifying Database
1. Update `Mbrreg_Database::get_schema()`
2. Add migration logic in activation hook
3. Test with existing data
4. Update uninstall.php to clean up new tables

### Adding Shortcodes
1. Add shortcode method in `Mbrreg_Shortcodes` class
2. Create template in `public/partials/`
3. Register shortcode in class constructor
4. Test with various scenarios (logged in/out, permissions)

---

## File Organization Rules

### New File Placement
- **Classes**: `includes/class-{name}.php`
- **Admin templates**: `admin/partials/{name}.php`
- **Public templates**: `public/partials/{name}.php`
- **Admin CSS**: `admin/css/{name}.css`
- **Admin JS**: `admin/js/{name}.js`
- **Public CSS**: `public/css/{name}.css`
- **Public JS**: `public/js/{name}.js`

### File Header Template
```php
<?php
/**
 * [Brief description]
 *
 * @package Member_Registration_Plugin
 * @subpackage Member_Registration_Plugin/includes
 * @since 1.0.0
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
```

---

## Common Pitfalls to Avoid

### Don't Do This
```php
// Direct database access
$wpdb->query("DELETE FROM table WHERE id = $id");  // SQL injection risk

// Unescaped output
echo $_POST['user_data'];  // XSS risk

// Missing nonce check
if ( isset( $_POST['action'] ) ) {
    // Process form without nonce verification
}

// Hardcoded capability checks
if ( current_user_can( 'administrator' ) ) {
    // Use specific capabilities instead
}
```

### Do This Instead
```php
// Prepared statements
$wpdb->prepare(
    "DELETE FROM table WHERE id = %d",
    $id
);

// Escaped output
echo esc_html( $sanitized_data );

// With nonce verification
if ( isset( $_POST['action'] ) && 
     wp_verify_nonce( $_POST['_wpnonce'], 'my_action' ) ) {
    // Process form
}

// Specific capabilities
if ( current_user_can( 'manage_options' ) ) {
    // Use appropriate capability
}
```

---

## Testing Manual Checklist

### Registration Flow
- [ ] Form validation works
- [ ] Email verification sent
- [ ] Member created in database
- [ ] Activation link works
- [ ] Login successful after activation

### Admin Functions  
- [ ] Members list displays
- [ ] Member editing works
- [ ] Custom fields save correctly
- [ ] CSV import handles errors
- [ ] CSV export produces valid file

### Security Testing
- [ ] Form submissions blocked without nonce
- [ ] SQL injection attempts fail
- [ ] XSS attempts are escaped
- [ ] Unauthorized access blocked

---

## Important Notes

- **No automated tests** - all testing must be manual
- **No CI/CD** - deployment is manual process  
- **No code quality tools** - rely on manual code review
- **WordPress dependency** - plugin tightly coupled to WordPress APIs
- **PHP 8.2+ required** - uses modern PHP features
- **Manual database updates** - schema changes handled in activation hooks

When working on this codebase, always prioritize security, WordPress standards compliance, and backward compatibility. Test thoroughly in a WordPress environment before deploying changes.