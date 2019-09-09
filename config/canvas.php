<?php
return [
    'domain' => env('CANVAS_DOMAIN', 'https://localhost:8080/api'),
    'access_key' => env('CANVAS_ACCESS_KEY', ''),
    'account_id' => env('CANVAS_ACCOUNT_ID', ''),
    'debug' => env('CANVAS_DEBUG', false),
    'county_name' => env('CANVAS_COUNTY_GROUP_CATEGORY_NAME', 'Fylke'),
    'community_name' => env('CANVAS_COMMUNITY_GROUP_CATEGORY_NAME', 'Kommune'),
    'school_name' => env('CANVAS_SCHOOL_GROUP_CATEGORY_NAME', 'Skole'),
    'county_leaders_name' => env('CANVAS_SCHOOL_LEADER_COUNTY_GROUP_CATEGORY_NAME', 'Skole'),
    'community_leaders_name' => env('CANVAS_SCHOOL_LEADER_COMMUNITY_GROUP_CATEGORY_NAME', 'Skole'),
    'principal_role' => env('CANVAS_PRINCIPAL_ROLE_TYPE', 'Skoleleder'),
];
