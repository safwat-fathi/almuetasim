# Plan: Fix pagination Arabic, theme color, and alignment

## Goal
Improve the app’s pagination UI to:
- Use Arabic labels and summary text.
- Match the site’s primary theme color for controls.
- Fix alignment for RTL and center the controls cleanly on all breakpoints.

## Context
- Current view: `resources/views/vendor/pagination/tailwind.blade.php` uses default Tailwind classes and English strings.
- The app uses DaisyUI/Tailwind classes elsewhere (e.g., `btn`, `join`, badges). We will reuse similar conventions for consistency.

## Changes
1. Replace English text with Arabic equivalents:
   - Previous → "السابق"
   - Next → "التالي"
   - Summary: "عرض {first} إلى {last} من {total} نتائج" (hidden on small if needed).
2. Theme color and styles:
   - Use `btn btn-primary` for active items, `btn` for normal, `btn-disabled` for disabled.
   - Wrap numeric page links with `join` for compact grouping.
3. Alignment / RTL:
   - Center controls with `justify-center` and add `rtl:flex-row-reverse` behavior where needed.
   - Place the summary text below the controls and center it with subdued color.
4. Accessibility:
   - Maintain `aria-label` and `aria-current` where applicable.

## Validation
- Verify on `/products` and when navigating pages.
- Check small screens (previous/next only) and larger screens (joined number buttons).

## Rollback
- Revert `tailwind.blade.php` to the previous version if any issues arise.

