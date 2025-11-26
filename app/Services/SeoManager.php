<?php

namespace App\Services;

/**
 * Simple SeoManager service to centralize metadata computation for pages.
 *
 * - Accepts arrays or Eloquent models (arrayable)
 * - Returns normalized title, description, canonical, image and type
 */
class SeoManager
{
    public function for(array|object|null $context = null): array
    {
        $defaults = config('seo');

        // normalize context to array
        $ctx = [];
        if (is_object($context)) {
            if (method_exists($context, 'toArray')) {
                $ctx = $context->toArray();
            } else {
                $ctx = (array) $context;
            }
        } elseif (is_array($context)) {
            $ctx = $context;
        }

        $title = $ctx['meta_title'] ?? $ctx['title'] ?? $ctx['name'] ?? $ctx['name_en'] ?? $defaults['default_title'];
        $description = $ctx['meta_description'] ?? $ctx['description'] ?? $defaults['default_description'];
        $image = $ctx['meta_image'] ?? ($ctx['image'] ?? $defaults['default_social_image']);
        $canonical = $ctx['canonical'] ?? url()->current();
        $type = $ctx['type'] ?? 'website';

        // enforce length limits
        $title = mb_substr($title, 0, $defaults['max_title']);
        $description = mb_substr($description, 0, $defaults['max_description']);

        return [
            'title' => $title,
            'description' => $description,
            'image' => url($image),
            'canonical' => $canonical,
            'type' => $type,
        ];
    }
}
