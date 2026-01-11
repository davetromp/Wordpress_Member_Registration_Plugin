# Wordpress coding standards

Summury of https://developer.wordpress.org/coding-standards/wordpress-coding-standards/

## 1. General Principles

- Be consistent with existing WordPress core code.
- Aim for readability and maintainability over cleverness.
- Follow standards even on small projects so code is easier to share and reuse.
- Use clear, descriptive names and helpful inline documentation.

---

## 2. PHP Coding Standards

**Files & Organization**
- PHP closing `?>` tag is omitted in pure-PHP files.
- One class per file; filename mirrors class name where possible.
- Use lowercase-dashed filenames for general PHP files.

**Style & Formatting**
- Indent with tabs; use spaces for alignment.
- Maximum line length ~80–100 characters where reasonable.
- Opening braces:
  - Functions, classes, methods: brace on the next line.
  - Control structures: brace on the same line.
- Space after control keywords: `if ( $var ) {`.

**Naming**
- Functions: `snake_case` with a unique prefix, e.g., `myplugin_do_thing()`.
- Class names: `WordPress-style_CamelCase` with prefix.
- Variables: `snake_case`.
- Constants: `UPPERCASE_WITH_UNDERSCORES`.

**Control Structures & Logic**
- Always use braces with `if`, `else`, `foreach`, etc., even for one line.
- Yoda conditions: put constants on the left:  
  `if ( true === $is_valid ) { … }`
- Strict comparisons (`===`, `!==`) when appropriate.

**Security & Data Handling**
- Always escape output (`esc_html()`, `esc_attr()`, etc.).
- Validate, sanitize, and escape data at the right times:
  - Sanitize on input, escape on output.
- Use prepared statements with `$wpdb` for database queries.

**Internationalization**
- Wrap user-facing text in translation functions:  
  `__( 'Text', 'text-domain' )`, `_e()`, `_x()`, etc.

---

## 3. HTML & CSS Standards

**HTML**
- Use valid, semantic HTML5.
- Use lowercase tags and attributes.
- Quote attribute values: `class="button"`.
- Use proper indentation, two spaces for HTML (tabs are still accepted in some contexts but docs favor spaces).

**CSS**
- Indent with tabs (in core); each rule on its own line.
- One selector per line; one property per line.
- Order properties logically and consistently.
- Use lowercase hex, shorthand where appropriate: `#fff`, not `#FFFFFF`.
- Avoid overly specific selectors and inline styles.
- Prefix CSS for plugins/themes to avoid conflicts (classes, IDs).

---

## 4. JavaScript Standards

**Style**
- Indent with tabs; spaces for alignment only.
- Use semicolons; avoid implied insertion.
- Single quotes for strings where possible: `'string'`.
- Space after keywords: `if ( condition ) {`.
- Use strict equality (`===`, `!==`).

**Variables & Scope**
- Use `let` and `const` in modern code; avoid `var` unless needed.
- Use descriptive variable and function names in `camelCase`.
- Avoid polluting the global namespace; use closures or namespaces.

**Coding Practices**
- Prefer feature detection over browser sniffing.
- Use the built-in WordPress JavaScript APIs (e.g., jQuery as loaded by WordPress, wp.* APIs).
- Follow WordPress’ structured patterns for enqueueing scripts (`wp_enqueue_script`).

---

## 5. Accessibility Standards

- Follow WCAG 2.0/2.1 AA guidelines.
- Ensure keyboard navigability and visible focus states.
- Provide proper labels and ARIA attributes where needed.
- Use semantic HTML for structure (headings, lists, landmarks).
- Provide sufficient color contrast and meaningful link text.

---

## 6. Documentation Standards

**PHPDoc**
- Docblocks for:
  - Functions/methods (purpose, params, return, since).
  - Classes, hooks (actions/filters), and complex logic.
- Use `@since`, `@param`, `@return`, `@see`, `@link`, etc.
- Short summary line, optional longer description, then tags.

**Inline Comments**
- Explain “why” more than “what”.
- Use line comments for complex logic, conditions, and edge cases.

---

## 7. Files, Naming, and Structure

- Use lowercase, hyphen-separated filenames: `class-wp-widget.php`, `my-plugin.php`.
- Use meaningful directory structures (`includes`, `assets`, `templates`, etc.).
- Maintain consistency within a project and follow core conventions where applicable.

---

## 8. CSS/JS in Themes and Plugins

- Enqueue assets with `wp_enqueue_style()` and `wp_enqueue_script()`.
- Don’t hardcode core libraries; rely on WordPress-registered handles.
- Respect dependency and versioning parameters for cache-busting.

