// Check if template has jQuery. Include if doesnt
window.onload = function() {
    if (!window.jQuery) {
        let jQuery_script = document.createElement('script');
        document.head.appendChild(jQuery_script);
        jQuery_script.type = 'text/javascript';
        jQuery_script.src = "/wp-includes/js/jquery/jquery.min.js";
        jQuery_script.onload = function() {
            KirilKirkovSpotifySearch();
        };
    } else {
        KirilKirkovSpotifySearch();
    }
}

// main search functionality
function KirilKirkovSpotifySearch() {
    (function($) {
        "use strict";

        let results_container = jQuery('#kirilkirkov-spotify-search-container .spotify-search-results');
        let main_form = document.getElementsByClassName('spotify-search-form')[0];

        let album_open_in = 'https://open.spotify.com/album/';
        let artist_open_in = 'https://open.spotify.com/artist/';
        let track_open_in = 'https://open.spotify.com/track/';
        if(ajax_object.open_in_tunedex == 1) {
            album_open_in = 'https://tunedex.routenote.com/albums/';
            artist_open_in = 'https://tunedex.routenote.com/artists/';
            track_open_in = 'https://tunedex.routenote.com/tracks/';    
        }
        
        /**
        * Execute a function given a delay time - helper debounce
        * 
        * @param {type} func
        * @param {type} wait
        * @param {type} immediate
        * @returns {Function}
        */
        let spotifySearchDebounce = function (func, wait, immediate) {
            let timeout;
            return function() {
                let context = this, args = arguments;
                let later = function() {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                };
                let callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(context, args);
            };
        };

        // on change input - hide/show clear icon, onkey up debounce
        jQuery("#kirilkirkov-spotify-search-container .spotify-search-form input[type=text]" ).on("keyup", spotifySearchDebounce(function() {
            if(jQuery(this).val() == '') {
                spotifySearchClearResults();
            } else {
                spotifySearchSubmit();
            }
        }, 500));

        function spotifySearchSubmit(get_params = null) {
            let ss_formData = new FormData(main_form);
            let ss_formProps = Object.fromEntries(ss_formData);
            if(jQuery.trim(ss_formProps.spotify_search_input) == '') return;
            ss_formProps.action = 'get_spotify_search_results'; // from backend

            results_container.empty();
            jQuery('#kirilkirkov-spotify-search-container .spotify-search-form .lds-ring').show();
            jQuery('#kirilkirkov-spotify-search-container .spotify-search-form .ready').hide();
            jQuery('#kirilkirkov-spotify-search-container .spotify-search-form .spotify-search-clear').hide();
            jQuery('#kirilkirkov-spotify-search-container .spotify-table-wrapper').hide();

            if(get_params !== null) {
                let query_string_to_obj = JSON.parse('{"' + decodeURI(get_params).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}');
                ss_formProps = Object.assign(ss_formProps, query_string_to_obj);
            }
        
            jQuery.ajax({
                url: ajax_object.ajax_url,
                type: 'POST',
                data: ss_formProps,
                dataType: 'json',
                success: function(response) {
                    if(typeof response != 'object' || !response.hasOwnProperty('result')) {
                        return ;
                    }
                    let has_results = false;
                    // loop albums if has
                    if(response.result.hasOwnProperty('albums') && response.result.albums.total > 0) {
                        spotifySearchAddListAlbums('albums', response.result.albums);
                        has_results = true;
                    }
                    // loop artists if has
                    if(response.result.hasOwnProperty('artists') && response.result.artists.total > 0) {
                        spotifySearchAddListArtists('aritsts', response.result.artists);
                        has_results = true;
                    }
                    // loop tracks if has
                    if(response.result.hasOwnProperty('tracks') && response.result.tracks.total > 0) {
                        spotifySearchAddListTracks('tracks', response.result.tracks);
                        has_results = true;
                    }
                    // loop playlists if has
                    if(response.result.hasOwnProperty('playlists') && response.result.playlists.total > 0) {
                        spotifySearchAddListPlaylists('playlists', response.result.playlists);
                        has_results = true;
                    }

                    if(!has_results) {
                        results_container.html('<p>No results found</p>');
                    }
                    
                    jQuery('#kirilkirkov-spotify-search-container .spotify-search-form .lds-ring').hide();
                    jQuery('#kirilkirkov-spotify-search-container .spotify-search-form .spotify-search-clear').show();
                    jQuery('#kirilkirkov-spotify-search-container .spotify-table-wrapper').show();
                },
                error: function(response) {  
                    alert('There was error.')
                }    
            });
        }

        jQuery('#kirilkirkov-spotify-search-container .spotify-search-form .spotify-search-clear').click(function() {
            spotifySearchClearResults();
        });

        function spotifySearchClearResults() {
            results_container.empty();
            jQuery('#kirilkirkov-spotify-search-container .spotify-search-form input[type=text]').val('');
            jQuery('#kirilkirkov-spotify-search-container .spotify-search-form .lds-ring').hide();
            jQuery('#kirilkirkov-spotify-search-container .spotify-search-form .ready').show();
            jQuery('#kirilkirkov-spotify-search-container .spotify-search-form .spotify-search-clear').hide();
            jQuery('#kirilkirkov-spotify-search-container .spotify-table-wrapper').hide();
            jQuery('#kirilkirkov-spotify-search-container .spotify-search-form .ready').show();
        }

        function getPaginationLinks(spotify_items) {
            let pagination_template = '<ul class="pagination">';
            if (spotify_items.previous != null) {
                const paramsPrev = getParamsFromUrl(spotify_items.previous);
                pagination_template += '<li><a href="javascript:;" class="spotify_search_previous_page" data-params="'+paramsPrev+'"> \
                    <svg width="24px" height="24px" fill="#fff" viewBox="-78.5 0 512 512" xmlns="http://www.w3.org/2000/svg" ><title>left</title><path d="M257 64L291 98 128 262 291 426 257 460 61 262 257 64Z" /></svg> \
                </a></li>';
            }
            if (spotify_items.next != null) {
                const paramsNext = getParamsFromUrl(spotify_items.next);
                pagination_template += '<li><a href="javascript:;" class="spotify_search_next_page" data-params="'+paramsNext+'"> \
                    <svg width="24px" height="24px" fill="#fff" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" class="icon"><path d="M765.7 486.8L314.9 134.7A7.97 7.97 0 0 0 302 141v77.3c0 4.9 2.3 9.6 6.1 12.6l360 281.1-360 281.1c-3.9 3-6.1 7.7-6.1 12.6V883c0 6.7 7.7 10.4 12.9 6.3l450.8-352.1a31.96 31.96 0 0 0 0-50.4z"/></svg> \
                </a></li>';
            }
            pagination_template += '</ul>';
            return pagination_template;
        }

        // handle click next/prev page
        jQuery(document).on("click", '.spotify_search_next_page, .spotify_search_previous_page', function() {
            spotifySearchSubmit(jQuery(this).data('params'));
        });

        function getParamsFromUrl(url) {
            let str = '';
            let paramsArray = url.match(/([^?=&]+)(=([^&]*))/g);
            if (paramsArray) {
                paramsArray.forEach(function(q) {
                    let strings = q.split("=");
                    str += '&'+strings[0]+'='+strings[1];
                });
            }
            return str.substring(1);
        }

        function millisToMinutesAndSeconds(millis) {
            let minutes = Math.floor(millis / 60000);
            let seconds = ((millis % 60000) / 1000).toFixed(0);
            return minutes + ":" + (seconds < 10 ? '0' : '') + seconds;
        }

        // Create albums list
        function spotifySearchAddListAlbums(classname, spotify_items) {
            let header = classname.charAt(0).toUpperCase() + classname.slice(1);
            let template = ' \
            <li> \
                <h2>'+header+'</h2> \
                <ul> \
            ';
            for (let [key, value] of Object.entries(spotify_items.items)) {
                let obj = spotify_items.items[key];
                if(obj === null) continue;
                let image = '';
                if(obj.images.length) {
                    image = obj.images[0].url;
                }
                let artists = '';
                if(obj.artists.length) {
                    let i=0;
                    let deliver = '';
                    for (let [key, value] of Object.entries(obj.artists)) {
                        if(i > 0) {
                            deliver = ', ';
                        }
                        artists +=  deliver + obj.artists[key].name;
                        i++;
                    }
                }
                template += ' \
                <li> \
                    <div class="container"> \
                        <div class="cover" style="background-image: url(\''+image+'\')"></div> \
                        <a href="' + album_open_in + obj.id + '" target="_blank"> \
                            '+obj.name+' \
                        </a> \
                        <span><b>Artists:</b> '+artists+'</span> \
                        <span><b>Release date:</b> '+obj.release_date+'</span> \
                        <span><b>Total tracks:</b> '+obj.total_tracks+'</span> \
                        <span><b>Type:</b> '+obj.album_type+'</span> \
                    </div> \
                </li> \
                ';
            }

            template += '</ul>'+getPaginationLinks(spotify_items)+'</li>';
            results_container.append(template);
        }

        function spotifySearchAddListArtists(classname, spotify_items) {
            let header = classname.charAt(0).toUpperCase() + classname.slice(1);
            let template = ' \
            <li> \
                <h2>'+header+'</h2> \
                <ul> \
            ';
            for (let [key, value] of Object.entries(spotify_items.items)) {
                let obj = spotify_items.items[key];
                if(obj === null) continue;
                let image = '';
                if(obj.images.length) {
                    image = obj.images[0].url;
                }
                let genres = '';
                if(obj.genres.length) {
                    let i=0;
                    let deliver = '';
                    for (let [key, value] of Object.entries(obj.genres)) {
                        if(i > 0) {
                            deliver = ', ';
                        }
                        genres += deliver + obj.genres[key];
                        i++;
                    }
                }
                template += ' \
                <li> \
                    <div class="container"> \
                        <div class="cover" style="background-image: url(\''+image+'\')"></div> \
                        <a href="' + artist_open_in + obj.id + '" target="_blank"> \
                            '+obj.name+' \
                        </a> \
                        <span><b>Followers:</b> '+obj.followers.total+'</span> \
                        <span><b>Genres:</b> '+obj.genres+'</span> \
                    </div> \
                </li> \
                ';
            }
            template += '</ul>'+getPaginationLinks(spotify_items)+'</li>';
            results_container.append(template);
        }

        function spotifySearchAddListTracks(classname, spotify_items) {
            let header = classname.charAt(0).toUpperCase() + classname.slice(1);
            let template = ' \
            <li> \
                <h2>'+header+'</h2> \
                <ul> \
            ';
            for (let [key, value] of Object.entries(spotify_items.items)) {
                let obj = spotify_items.items[key];
                let image = '';
                if(obj.album.images.length) {
                    image = obj.album.images[0].url;
                }
                let artists = '';
                if(obj.artists.length) {
                    let i=0;
                    let deliver = '';
                    for (let [key, value] of Object.entries(obj.artists)) {
                        if(i > 0) {
                            deliver = ', ';
                        }
                        artists += deliver + obj.artists[key].name;
                        i++;
                    }
                }
                template += ' \
                <li> \
                    <div class="container"> \
                        <div class="cover" style="background-image: url(\''+image+'\')"></div> \
                        <a href="' + track_open_in + obj.id + '" target="_blank"> \
                            '+obj.name+' \
                        </a> \
                        <span><b>Artists:</b> '+artists+'</span> \
                        <span><b>Disc number:</b> '+obj.disc_number+'</span> \
                        <span><b>Track number:</b> '+obj.track_number+'</span> \
                        <span><b>Duration:</b> '+millisToMinutesAndSeconds(obj.duration_ms)+'</span> \
                    </div> \
                </li> \
                ';
            }
            template += '</ul>'+getPaginationLinks(spotify_items)+'</li>';
            results_container.append(template);
        }

        function spotifySearchAddListPlaylists(classname, spotify_items) {
            let header = classname.charAt(0).toUpperCase() + classname.slice(1);
            let template = ' \
            <li> \
                <h2>'+header+'</h2> \
                <ul> \
            ';
            for (let [key, value] of Object.entries(spotify_items.items)) {
                let obj = spotify_items.items[key];
                if(obj === null) continue;
                let image = '';
                if(obj.images.length) {
                    image = obj.images[0].url;
                }
                template += ' \
                <li> \
                    <div class="container"> \
                        <div class="cover" style="background-image: url(\''+image+'\')"></div> \
                        <div style="background-image: url(\''+image+'\')"></div> \
                        <a href="https://open.spotify.com/track/'+obj.id+'" target="_blank"> \
                            '+obj.name+' \
                        </a> \
                        <span>'+obj.owner.display_name+'</span> \
                    </div> \
                </li> \
                ';
            }
            template += '</ul>'+getPaginationLinks(spotify_items)+'</li>';
            results_container.append(template);
        }
    })(jQuery); 
}