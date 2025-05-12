<?php

return [
    'ga_measurement_id' => getenv('GA_MEASUREMENT_ID') ?: 'G-XXXXXXXXXX',
    'ga_api_secret' => getenv('GA_API_SECRET') ?: 'your_api_secret',
    'ga_client_id' => getenv('GA_CLIENT_ID') ?: 'your_client_id',
    'ga_property_id' => getenv('GA_PROPERTY_ID') ?: 'your_property_id',
    'ga_debug_mode' => getenv('GA_DEBUG_MODE') ?: false,
    'ga_track_events' => [
        'button_click' => true,
        'form_submit' => true,
        'page_view' => true,
        'scroll_depth' => true
    ]
]; 