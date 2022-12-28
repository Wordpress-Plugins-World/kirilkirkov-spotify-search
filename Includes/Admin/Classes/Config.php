<?php

namespace KirilKirkov\SpotifySearch;

class Config
{
    const SEARCH_CACHE_GROUP = 'KirilKirkovSpotifySearch';
    const SEARCH_CACHE_TIME = 86400; // one day

    const SHORTCODE = 'spotify-search';

    const SETTINGS_GET_PARAM = 'kirilkirkov-spotify-search-settings';

    const INPUTS_PREFIX = 'kkss_'; // kkss -> kirilkirkov spotify search
    const SCRIPTS_PREFIX = self::INPUTS_PREFIX;

    const INPUT_GROUP = 'kkss-spotify-search-update-options';

    // used input fields with group type
    const GROUPS_INPUT_FIELDS = [
        self::INPUT_GROUP => [
            'spotify_search_client_id',
            'spotify_search_client_secret',
            'spotify_search_search_type',
            'spotify_search_limit',
            'spotify_search_default_styles',
            'spotify_search_styles',
            'spotify_search_absolute_results',
            'spotify_search_show_on_tunedex'
        ],
    ];

    public static function get_groups_input_fieds()
    {
        return self::GROUPS_INPUT_FIELDS;
    }
}