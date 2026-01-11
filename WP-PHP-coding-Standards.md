# Conversation
- conversationId: 1461e4ad-6509-48fe-b9bf-e2d1507ff2c0
- endpoint: openAI
- title: New Chat
- exportAt: 15:49:34 GMT+0100 (Central European Standard Time)

## Options
- presetId: null
- model: gpt-5.1
- resendFiles: true
- endpoint: openAI
- endpointType: undefined
- title: New Chat

## History
**User**
Can you please summarize the php coding standards for wordpress as outlined here: https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/


**GPT-5.1**
Here’s a concise summary of the WordPress PHP Coding Standards from the linked guide:

---

### 1. General Philosophy
- Prioritize readability and consistency over personal style.
- Match existing style in core or the project you’re contributing to.
- Write defensive, secure, and backward-compatible code.

---

### 2. Files & Organization
- Use `<?php` and `<?=` only (no short open tags like `<?`).
- One class per file is preferred; file name should reflect purpose (e.g., `class-wp-example.php`).
- Use `require`, `include`, `require_once`, `include_once` only when needed, and avoid conditional includes where possible.

---

### 3. Indentation & Spacing
- Use tabs for indentation; spaces for alignment (“Tabs for indent, spaces for align”).
- Indent code blocks one tab inside braces.
- Put one space:
  - Around most operators: `$foo = $bar + $baz;`
  - After commas in arrays/argument lists.
  - After control structure keywords: `if ( $condition ) {`.
- No space before semicolons or parentheses in function calls.

---

### 4. Braces & Control Structures
- Braces on the same line (K&R-style):
  ```php
  if ( condition ) {
      // ...
  } elseif ( other_condition ) {
      // ...
  } else {
      // ...
  }
  ```
- Always use braces, even for single-line `if`, `else`, `foreach`, etc.
- Put `elseif` as one word (not `else if`) in control structures.

---

### 5. Naming Conventions
- Functions: `snake_case()`, all lowercase with underscores (`get_post_meta()`).
- Variables: `snake_case`, descriptive (`$post_id`, `$user_email`).
- Class names: `StudlyCaps` or `WordPress_Style` with underscores (`WP_Query`).
- Class methods: `snake_case`.
- Constants: `UPPERCASE_WITH_UNDERSCORES`.
- Prefix everything in plugins/themes to avoid collisions (e.g., `myplugin_get_option()`).

---

### 6. Functions & Methods
- Function definitions:
  ```php
  function my_function_name( $arg1, $arg2 ) {
      // ...
  }
  ```
- Single space between `function` name and opening parenthesis in declarations.
- In calls, no space between function name and `(`: `my_function_name( $arg1 );`.
- Default param values: no spaces around `=` in definitions: `function foo( $bar = null )`.

---

### 7. Arrays
- Prefer short array syntax: `[ ... ]`.
- For multi-line arrays:
  ```php
  $array = array(
      'key1' => 'value1',
      'key2' => 'value2',
  );
  ```
  or
  ```php
  $array = [
      'key1' => 'value1',
      'key2' => 'value2',
  ];
  ```
- Trailing comma on the last item is recommended in multi-line arrays.
- Align `=>` only if it improves readability; otherwise, just one space on each side.

---

### 8. Strings
- Use single quotes unless you need variable interpolation or special escape sequences.
- Concatenate with a space around the dot:
  ```php
  $string = 'Hello ' . $name . '!';
  ```
- Avoid unnecessary concatenation and complex interpolation; keep it clear.

---

### 9. Comparisons & Type Safety
- Strict comparisons when type matters: `===` and `!==`.
- Use `===`/`!==` with `true`, `false`, `null`.
- Be explicit in conditionals; avoid relying on truthiness when it can be ambiguous.
- When checking for empty values, consider `isset()`, `empty()` carefully and contextually.

---

### 10. Control Structure Details
- `switch`:
  ```php
  switch ( $value ) {
      case 'foo':
          // ...
          break;
      default:
          // ...
          break;
  }
  ```
- Indent `case` blocks one tab from `switch`.
- Always include `break` (or `return`, `continue`) unless intentional fall-through is clearly documented.

---

### 11. Documentation & Comments
- Use PHPDoc for functions, methods, classes, hooks:
  ```php
  /**
   * Short description.
   *
   * Longer description.
   *
   * @since 1.0.0
   *
   * @param int    $post_id Post ID.
   * @param string $status  Post status.
   * @return bool True on success, false on failure.
   */
  function my_plugin_update_post_status( $post_id, $status ) {
      // ...
  }
  ```
- Document all parameters, return values, and side effects.
- Use inline comments sparingly but clearly when logic is non-obvious.

---

### 12. Security & Data Handling
- Always validate, sanitize, and escape:
  - Validate/sanitize on input (`sanitize_text_field()`, etc.).
  - Escape on output (`esc_html()`, `esc_attr()`, `esc_url()`, etc.).
- Use nonces for form and action verification.
- Avoid direct access to superglobals (`$_GET`, `$_POST`, etc.) without checks and sanitization.
- Avoid `eval()` and dangerous constructs.

---

### 13. Internationalization (i18n)
- Wrap user-facing strings in translation functions:
  - `__( 'Text', 'text-domain' )` – return translated.
  - `_e( 'Text', 'text-domain' )` – echo translated.
  - `_x()`, `_nx()` for context/plural forms.
- Use proper text domains (usually your plugin or theme slug).

---

### 14. Error Handling & Debugging
- Use `WP_DEBUG` and related constants in development; don’t leave debug code in production.
- Don’t use `die()`/`exit()` arbitrarily; in plugins/themes prefer WordPress APIs where appropriate.
- Avoid exposing sensitive information in error messages.

---

### 15. Performance & Compatibility
- Be mindful of queries and loops; use caching APIs (transients, object cache) where appropriate.
- Maintain backward compatibility where possible; don’t change function signatures or behavior without care.
- Avoid using PHP features not supported by the minimum WordPress PHP version.

---

If you want, I can turn this into a short checklist you can keep next to your editor or help you configure PHPCS with the WordPress ruleset.


