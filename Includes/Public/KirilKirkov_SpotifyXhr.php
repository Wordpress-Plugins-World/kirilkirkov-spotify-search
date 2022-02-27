<?php

/**
 * @author Kiril Kirkov
 * https://github.com/kirilkirkov
 */
class KirilKirkov_SpotifyXhr
{
    private $spotifyWebApi;
    private $search_type = 'track,artist,album,playlist'; // More info about the search types - https://github.com/kirilkirkov/Spotify-WebApi-PHP-SDK/wiki/Service-Search

    public function __construct()
    {
        $this->initSpotify();
    }

    /**
     * Load/Init Spotify Library
     */
    private function initSpotify()
    {
        if(!get_option(SS_INPUTS_PREFIX.'spotify_search_refresh_token')) {
            return [];
        }

        $this->spotifyWebApi = new SpotifyWebAPI\SpotifyWebApi([
            'clientId' => get_option(SS_INPUTS_PREFIX.'spotify_search_client_id'),
            'clientSecret' => get_option(SS_INPUTS_PREFIX.'spotify_search_client_secret'),
            'accessToken' => get_option(SS_INPUTS_PREFIX.'spotify_search_token'),
            'refreshToken' => get_option(SS_INPUTS_PREFIX.'spotify_search_refresh_token'),
        ]);
        $this->spotifyWebApi->returnNewTokenIfIsExpired(true);

        $this->getSearchType();
    }

    /**
     * Get search type from administration settings
     * 
     * @return string
     */
    private function getSearchType()
    {
        if(!get_option(SS_INPUTS_PREFIX.'spotify_search_search_type') || trim(get_option(SS_INPUTS_PREFIX.'spotify_search_search_type')) === '') {
            return $this->search_type;
        }

        $search_types_ = explode(',', $this->search_type);
        
        $search_types = explode(',', get_option(SS_INPUTS_PREFIX.'spotify_search_search_type'));
        if(count($search_types)) {
            $stypes = [];
            foreach($search_types as $st) {
                $s = trim(strtolower($st));
                if(!in_array($s, $search_types_)) {
                    continue;
                }
                $stypes[] = $s;
            }
        }
        if(!count($stypes)) {
            return $this->search_type;
        }
        return $stypes;
    }

    /**
     * Return results from spotify to public frontend
     * Using of wp_cache_set/get for queries
     * 
     * @return array
     */
    public function getResults($post)
    {
        if(!isset($post['spotify_search_input']) || trim($post['spotify_search_input']) === '') {
            return [];
        }

        if(get_option(SS_INPUTS_PREFIX.'spotify_search_limit') && (int)get_option(SS_INPUTS_PREFIX.'spotify_search_limit') > 0) {
            $limit = (int)get_option(SS_INPUTS_PREFIX.'spotify_search_limit');
            if($limit > 20) {
                $limit = 20;
            }
            \SpotifyWebAPI\SpotifyPagination::setLimit($limit);
        }

        if(isset($post['offset'])) {
            \SpotifyWebAPI\SpotifyPagination::setOffset((int)$post['offset']);
        }

        // get cache
        $response = wp_cache_get($this->getCacheKey($post, $limit), SS_CACHE_GROUP);
        if($response === false) {
            $response = $this->spotifyWebApi->api()->provider(
                \SpotifyWebAPI\SpotifyServices::search()::search(trim($post['spotify_search_input']), $this->getSearchType())
            )->getResult();
            // if token expired, new token is returned, update and call again
            if(property_exists($response, 'access_token')) {
                update_option(SS_INPUTS_PREFIX.'spotify_search_token', $response->access_token);
                $this->getResults($post);
            }

            // set cache
            wp_cache_set($this->getCacheKey($post, $limit), $response, SS_CACHE_GROUP, SS_CACHE_TIME);
        }

        return $response;
    }

    /**
     * Generate cache key from query params
     * Used params - limit, offset, search param.
     */
    private function getCacheKey($post, $limit)
    {
        $search_param = trim($post['spotify_search_input']);
        $offset = isset($post['offset']) ? (int)$post['offset'] : 0;
        return "{$search_param}_{$limit}_{$offset}";
    }
}