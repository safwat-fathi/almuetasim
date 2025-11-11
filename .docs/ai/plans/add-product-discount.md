# Add Product Discount (percentage)

## Goal
Add a `discount` percentage (0–100) to products and expose it in the admin create/edit modal.

## Scope
- Database: add `discount` column to `products` (unsigned tinyint, default 0).
- Model: include `discount` in `$fillable` and `casts()`.
- Controller: validate `discount` in `store` and `update` (nullable|integer|min:0|max:100).
- Admin UI: add a numeric input for `discount` (%) to the product modal; wire it into Alpine form state and submit logic.

## Steps
1. Migration: add `discount` column with default 0 and proper rollback.
2. Model update: add cast and fillable entry for `discount`.
3. Controller update: accept and validate `discount` on create/update.
4. Admin modal: add input field, bind to `formData.discount`, client-side validate 0–100, include in FormData for both add/update, and prefill on edit.
5. Quick manual verification in browser (create/edit with discount, ensure persisted).

## Notes
- Keep UI Arabic labels consistent: label as "الخصم (%)".
- No price display computations are changed in listings; discount is persisted for future use on storefront.
