# Currency Formatting Plan (ar-EG, EGP)

## Objective
Ensure all price displays render as localized currency using Arabic (Egypt) locale and Egyptian Pound (EGP), both on server-rendered Blade templates and client-side JavaScript UI.

## Constraints & Conventions
- Laravel 12 structure; avoid new base folders and follow existing conventions.
- Prefer reusable solutions over one-offs; minimal surface-area changes.
- Do not change dependencies.
- Use PHP Intl NumberFormatter with locale `ar_EG` and currency `EGP`.
- Provide JS-side formatting using `Intl.NumberFormat('ar-EG', { style: 'currency', currency: 'EGP' })`.
- Run `vendor/bin/pint --dirty` before finalizing.

## Scope
Replace all ad-hoc price formatting (e.g., `number_format(..., 2)` with appended `ج.م`, `$`, or `EGP`) with locale-aware formatting.

Primary affected files (from search):
- `resources/views/components/product-card.blade.php`
- `resources/views/product.blade.php`
- `resources/views/products/public-listing.blade.php`
- `resources/views/admin/products/index.blade.php`
- `resources/views/admin/products/search-results.blade.php`
- `resources/views/components/layouts/partials/app/navbar.blade.php` (JS price rendering)

Non-functional strings (sorting labels, etc.) are out of scope.

## Approach
1) Server-side: Add a Blade directive for currency output.
- Name: `@money($amount)`; defaults to ar-EG + EGP.
- Implement in `AppServiceProvider::boot()` to avoid new files.
- Internally use a cached `NumberFormatter` instance for performance.
- Fallback: If `NumberFormatter` is unavailable, fallback to `number_format($amount, 2).' ج.م'`.

2) Refactor Blade templates to use `@money()`
- Replace constructs like `{{ number_format($price, 2) }} ج.م` with `@money($price)`.
- Replace hardcoded currency text like `99.99 EGP` with `@money(99.99)` where appropriate.
- Ensure all price-related computed values (discounts/savings) use `@money()` as well.

3) Client-side: Use Intl.NumberFormat
- Replace occurrences of `toFixed(2)` with manual suffixes (e.g., `+ ' ج.م'`) to:
  `new Intl.NumberFormat('ar-EG', { style: 'currency', currency: 'EGP' }).format(Number(value))`.
- Centralize a small formatter instance in the relevant script block(s) to avoid repetition.

## Detailed Implementation Steps
- Add Blade directive in `app/Providers/AppServiceProvider.php`:
  - Import `Illuminate\Support\Facades\Blade`.
  - In `boot()`, register:
    ```php
    Blade::directive('money', function ($expression) {
        return <<<'PHP'
<?php
static $___egpFmt;
if (!isset($___egpFmt)) {
    $___egpFmt = class_exists(NumberFormatter::class)
        ? new NumberFormatter('ar_EG', NumberFormatter::CURRENCY)
        : null;
}
echo $___egpFmt
    ? $___egpFmt->formatCurrency($expression, 'EGP')
    : (number_format((float) ($expression), 2).' ج.م');
?>
PHP;
    });
    ```
  - Note: The directive returns a literal PHP block; it caches the formatter and falls back gracefully.

- Update Blade files:
  - `resources/views/components/product-card.blade.php`
    - Replace `{{ number_format($price, 2) }} ج.م` with `@money($price)`.
    - Replace original-price line with `@money($displayOriginalPrice)`.
  - `resources/views/product.blade.php`
    - Replace primary price and savings displays to use `@money()`.
    - Replace hardcoded examples `99.99 EGP`, `199.99 EGP` with `@money(99.99)` / `@money(199.99)` if user-facing.
  - `resources/views/products/public-listing.blade.php`
    - Replace product price display with `@money($product->price)`.
  - `resources/views/admin/products/index.blade.php`
    - Replace `$ {{ number_format(...) }}` with `@money($product->price)`.
  - `resources/views/admin/products/search-results.blade.php`
    - Replace `$ {{ number_format(...) }}` with `@money($product->price)` if shown; leave form inputs unchanged.

- Update JS (navbar cart rendering):
  - In `resources/views/components/layouts/partials/app/navbar.blade.php`, define once per script:
    ```js
    const egpFormatter = new Intl.NumberFormat('ar-EG', { style: 'currency', currency: 'EGP' });
    ```
  - Replace `Number(x).toFixed(2)` plus suffix with `egpFormatter.format(Number(x))` in all occurrences where values are displayed to users.

## Validation
- Manual checks (no network):
  - Home page cards show Arabic digits and EGP currency symbol/format.
  - Product page price and savings display localized values.
  - Public listing cards show localized currency.
  - Admin product listing table shows localized currency.
  - Navbar/cart UI shows localized currency for items and totals (client-side).
- Run `vendor/bin/pint --dirty` to apply formatting.

## Rollback Plan
- The changes are localized to a directive and Blade/JS formatting; revert by removing the directive and restoring prior `number_format` + suffix patterns if needed.

## Open Questions
- Should we enforce application locale `app()->setLocale('ar')` globally or keep formatting independent? (Plan keeps it independent.)
- Any additional currency contexts (e.g., invoices/PDFs) to include now?

## Next Steps
- Await approval to implement per this plan.

