<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class AboutController extends Controller
{
    /**
     * Display the about page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch settings from the database
        $settings = DB::table('settings')->pluck('value', 'key')->all();
        
        // Define default values for settings that don't exist in the database
        $defaultSettings = [
            'social_facebook' => '',
            'social_twitter' => '',
            'social_instagram' => '',
            'social_linkedin' => '',
            'contact_email' => '',
            'contact_phone' => '',
            'contact_address' => '',
            'about_us_content' => '',
            'store_name' => '',
            'business_type' => '',
            'opening_date' => '',
            'location_link' => '',
        ];
        
        // Merge default settings with existing settings
        $settings = array_merge($defaultSettings, $settings);

        // Prepare embed link for map iframe
        $rawLink = $settings['location_link'] ?? '';
        $embedLink = $rawLink;

        // Convert Google Maps share URLs to embed URLs
        if (!empty($rawLink)) {
            // If it's already an embed link, keep it
            if (strpos($embedLink, '/embed') !== false || strpos($embedLink, 'output=embed') !== false) {
                // keep as-is
            }
            // Handle goo.gl short URLs - these are Google Maps short links
            elseif (strpos($embedLink, 'goo.gl') !== false) {
                try {
                    // Try to resolve the goo.gl URL to get the actual Google Maps URL
                    $response = Http::timeout(5)->get($rawLink);
                    if ($response->successful()) {
                        $resolvedUrl = $response->effectiveUri();
                        // Now process the resolved URL
                        if (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+),([0-9\.]+)z?/', $resolvedUrl, $m)) {
                            $lat = $m[1];
                            $lng = $m[2];
                            $zoom = (int) $m[3] ?: 15;
                            $embedLink = "https://maps.google.com/maps?q={$lat},{$lng}&z={$zoom}&output=embed";
                        } elseif (preg_match('/[?&]q=([^&]+)/', $resolvedUrl, $m2)) {
                            $q = $m2[1];
                            $embedLink = "https://maps.google.com/maps?q={$q}&output=embed";
                        } else {
                            // Fallback to generic embed
                            $embedLink = "https://maps.google.com/maps?output=embed";
                        }
                    } else {
                        // If resolution fails, use generic embed
                        $embedLink = "https://maps.google.com/maps?output=embed";
                    }
                } catch (\Exception $e) {
                    // If HTTP request fails, use generic embed
                    $embedLink = "https://maps.google.com/maps?output=embed";
                }
            }
            // If URL contains @lat,lng,zoom pattern (common Google Maps URLs), extract coords
            elseif (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+),([0-9\.]+)z?/', $rawLink, $m)) {
                $lat = $m[1];
                $lng = $m[2];
                $zoom = (int) $m[3] ?: 15;
                $embedLink = "https://maps.google.com/maps?q={$lat},{$lng}&z={$zoom}&output=embed";
            }
            // If URL already has a q= query (search query), reuse it and request embed output
            elseif (preg_match('/[?&]q=([^&]+)/', $rawLink, $m2)) {
                $q = $m2[1];
                $embedLink = "https://maps.google.com/maps?q={$q}&output=embed";
            }
            // Fallback: try to use the path (place name) as query
            else {
                $parts = parse_url($rawLink);
                $path = $parts['path'] ?? '';
                $host = $parts['host'] ?? '';
                if (str_contains($host, 'google') && !empty($path)) {
                    $place = trim($path, "/");
                    // remove leading 'maps' or 'maps/place'
                    $place = preg_replace('#^maps(/place)?/#', '', $place);
                    $place = rawurlencode($place ?: $host);
                    $embedLink = "https://maps.google.com/maps?q={$place}&output=embed";
                } else {
                    // If not Google Maps, try OpenStreetMap as fallback
                    if (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $rawLink, $m3)) {
                        $lat = $m3[1];
                        $lng = $m3[2];
                        $delta = 0.01; // small bbox around the marker
                        $minLng = $lng - $delta;
                        $minLat = $lat - $delta;
                        $maxLng = $lng + $delta;
                        $maxLat = $lat + $delta;
                        $embedLink = "https://www.openstreetmap.org/export/embed.html?bbox={$minLng},{$minLat},{$maxLng},{$maxLat}&layer=mapnik&marker={$lat},{$lng}";
                    } else {
                        // leave embedLink as original; the browser will likely refuse if it's not embeddable
                        $embedLink = $rawLink;
                    }
                }
            }
        }

        return view('about', compact('settings', 'embedLink'));
    }
}
