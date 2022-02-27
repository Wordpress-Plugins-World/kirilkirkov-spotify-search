<?php 
    $help_svg = '<svg version="1.1" fill="#1ed760" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="17px" height="17px" viewBox="0 0 400 400" style="enable-background:new 0 0 400 400;" xml:space="preserve"><g><g><path d="M199.996,0C89.719,0,0,89.72,0,200c0,110.279,89.719,200,199.996,200C310.281,400,400,310.279,400,200C400,89.72,310.281,0,199.996,0z M199.996,373.77C104.187,373.77,26.23,295.816,26.23,200c0-95.817,77.957-173.769,173.766-173.769c95.816,0,173.772,77.953,173.772,173.769C373.769,295.816,295.812,373.77,199.996,373.77z"/><path d="M199.996,91.382c-35.176,0-63.789,28.616-63.789,63.793c0,7.243,5.871,13.115,13.113,13.115c7.246,0,13.117-5.873,13.117-13.115c0-20.71,16.848-37.562,37.559-37.562c20.719,0,37.566,16.852,37.566,37.562c0,20.714-16.849,37.566-37.566,37.566c-7.242,0-13.113,5.873-13.113,13.114v45.684c0,7.243,5.871,13.115,13.113,13.115s13.117-5.872,13.117-13.115v-33.938c28.905-6.064,50.68-31.746,50.68-62.427C263.793,119.998,235.176,91.382,199.996,91.382z"/><path d="M200.004,273.738c-9.086,0-16.465,7.371-16.465,16.462s7.379,16.465,16.465,16.465c9.094,0,16.457-7.374,16.457-16.465S209.098,273.738,200.004,273.738z"/></g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>';
?>

<div id="kirilkirkov" class="wrap">
    <form method="post" action="options.php">
        <div class="header p-4 flex items-center space-between">
            <div class="flex items-center">
                <svg class="mr-4" xmlns="http://www.w3.org/2000/svg" height="40px" width="40px" version="1.1" viewBox="0 0 168 168"><path fill="#1ED760" d="m83.996 0.277c-46.249 0-83.743 37.493-83.743 83.742 0 46.251 37.494 83.741 83.743 83.741 46.254 0 83.744-37.49 83.744-83.741 0-46.246-37.49-83.738-83.745-83.738l0.001-0.004zm38.404 120.78c-1.5 2.46-4.72 3.24-7.18 1.73-19.662-12.01-44.414-14.73-73.564-8.07-2.809 0.64-5.609-1.12-6.249-3.93-0.643-2.81 1.11-5.61 3.926-6.25 31.9-7.291 59.263-4.15 81.337 9.34 2.46 1.51 3.24 4.72 1.73 7.18zm10.25-22.805c-1.89 3.075-5.91 4.045-8.98 2.155-22.51-13.839-56.823-17.846-83.448-9.764-3.453 1.043-7.1-0.903-8.148-4.35-1.04-3.453 0.907-7.093 4.354-8.143 30.413-9.228 68.222-4.758 94.072 11.127 3.07 1.89 4.04 5.91 2.15 8.976v-0.001zm0.88-23.744c-26.99-16.031-71.52-17.505-97.289-9.684-4.138 1.255-8.514-1.081-9.768-5.219-1.254-4.14 1.08-8.513 5.221-9.771 29.581-8.98 78.756-7.245 109.83 11.202 3.73 2.209 4.95 7.016 2.74 10.733-2.2 3.722-7.02 4.949-10.73 2.739z"/></svg>
                <h2>Spotify Search — <?php _e( 'Page: Settings', SS_TEXT_DOMAIN ) ?></h2>    
            </div>
            
            <button type="submit" class="button-primary"><?php _e( 'Save' ) ?></button>
        </div>
    
        <div class="flex flex-wrap">
            <div class="w-full md:w-3/4">
                <div class="section-header p-4">
                    <strong><?php _e( 'Spotify Search', SS_TEXT_DOMAIN ) ?></strong>
                    <p>
                        <?php _e('
                        Spotify Search plugin provides a way to find any Track, Album, Playlist or Artist in 
                        spotify.com throught their API. Spotify is one of the largest music streaming service 
                        providers with over 406 million monthly active users, including 180 million paying 
                        subscribers, as of December 2021.
                        <br>
                        Enjoy!
                        ', SS_TEXT_DOMAIN ) ?>
                    </p>
                </div>

                <div class="section-body">
                    <div class="p-4">
                        <?php if(is_string($this->spotify_api_error))  { ?>
                            <p class="alert alert-danger"><strong><?php _e( 'Spotify API Error!', SS_TEXT_DOMAIN ); ?></strong> <?php echo $this->spotify_api_error; ?></p>
                        <?php } ?>
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row" class="align-middle">
                                    <div class="th-div">
                                        <span class="mr-5"><?php _e( 'Spotify API Credentials', SS_TEXT_DOMAIN ); ?></span>
                                        <a class="show-info" data-info="<?php _e( 'To get your spotify API keys go to <a href=\'https://developer.spotify.com/dashboard\' target=\'_blank\'>https://developer.spotify.com</a> and register yours application. <br> In the application overview you will find the client id and secret id, copy and paste them here. <br> Click Edit Settings in your app and in Redirect URIs enter <b>' . $this->settings_url . '</b> <br> Thats It!', SS_TEXT_DOMAIN ) ?>" href="javascript:;">
                                            <?php echo $help_svg; ?>
                                        </a>
                                    </div>
                                </th>
                                <td>
                                    <label><?php _e( 'Client ID', SS_TEXT_DOMAIN ); ?></label>
                                    <input type="text" name="<?php echo SS_INPUTS_PREFIX; ?>spotify_search_client_id" value="<?php echo get_option( SS_INPUTS_PREFIX.'spotify_search_client_id' ); ?>" />
                                </td>
                                <td>
                                    <label><?php _e( 'Client Secret', SS_TEXT_DOMAIN ); ?></label>
                                    <input type="text" name="<?php echo SS_INPUTS_PREFIX; ?>spotify_search_client_secret" value="<?php echo get_option( SS_INPUTS_PREFIX.'spotify_search_client_secret' ); ?>" />
                                </td>
                            </tr>
                            
                            <!-- Generate tokens for the api -->
                            <?php if(!is_null($this->spotify_redirect_url)) { ?>
                            <tr>
                                <td colspan="3" class="p-0">
                                    <div>
                                        <?php if(get_option(SS_INPUTS_PREFIX.'spotify_search_refresh_token') && trim(get_option(SS_INPUTS_PREFIX.'spotify_search_refresh_token')) != '') { ?>
                                            <div class="flex justify-center mb-5">
                                                <p class="alert alert-success"><strong><?php _e( 'Congratulations!', SS_TEXT_DOMAIN ); ?></strong> <?php _e( 'You are ready, you has access tokens.', SS_TEXT_DOMAIN ); ?></p>
                                            </div>
                                        <?php } else { ?>
                                            <div class="flex justify-center mb-5">
                                                <p class="alert alert-info"><strong><?php _e( 'Mostly finished!', SS_TEXT_DOMAIN ); ?></strong> <?php _e( 'Click the button "Get token" to generate tokens.', SS_TEXT_DOMAIN ); ?></p>
                                            </div>
                                        <?php } ?>
                                        <div class="flex justify-center p-4">
                                            <a class="default-btn" href="<?php echo $this->spotify_redirect_url; ?>">
                                                <?php if(get_option(SS_INPUTS_PREFIX.'spotify_search_refresh_token') && trim(get_option(SS_INPUTS_PREFIX.'spotify_search_refresh_token')) != '') { ?>
                                                    <?php _e( 'Get New Tokens', SS_TEXT_DOMAIN ); ?>
                                                <?php } else { ?>
                                                    <?php _e( 'Get Token', SS_TEXT_DOMAIN ); ?>
                                                <?php } ?>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>

                            <!-- Options for the api -->
                            <?php if($this->has_public_permission) { ?>
                                <!-- Search type -->
                                <tr valign="top">
                                    <th scope="row" class="align-middle">
                                        <div class="th-div">
                                            <span class="mr-5"><?php _e( 'Search type', SS_TEXT_DOMAIN ); ?></span>
                                            <a class="show-info" data-info="<?php _e( 'Which type of results wants to search, eg. Artists, Tracks, Playlists or Albums. <br> They should be separated with comma. <br> Example for all types: <b>track,artist,album,playlist</b><br>Empty field will search in all types', SS_TEXT_DOMAIN ); ?>" href="javascript:;">
                                                <?php echo $help_svg; ?>
                                            </a>
                                        </div>
                                    </th>
                                    <td colspan="2">
                                        <label><?php _e( 'Assets', SS_TEXT_DOMAIN ); ?></label>
                                        <input type="text" name="<?php echo SS_INPUTS_PREFIX; ?>spotify_search_search_type" placeholder="track,artist,album,playlist" value="<?php echo get_option( SS_INPUTS_PREFIX.'spotify_search_search_type' ); ?>" />
                                    </td>
                                </tr>
                                <!-- Results limit -->
                                <tr valign="top">
                                    <th scope="row" class="align-middle">
                                        <div class="th-div">
                                            <span class="mr-5"><?php _e( 'Results limit', SS_TEXT_DOMAIN ); ?></span>
                                            <a class="show-info" data-info="<?php _e( 'Count of the results which wants to show<br>Results limit is per asset, so if you search in albums and tracks you will get 4 results total.<br>Results limit can not me more that 20 (this is Spotify restriction per page)', SS_TEXT_DOMAIN ); ?>" href="javascript:;">
                                                <?php echo $help_svg; ?>
                                            </a>
                                        </div>
                                    </th>
                                    <td colspan="2">
                                        <label><?php _e( 'Count', SS_TEXT_DOMAIN ); ?></label>
                                        <input type="number" max="20" name="<?php echo SS_INPUTS_PREFIX; ?>spotify_search_limit" placeholder="20" value="<?php echo get_option( SS_INPUTS_PREFIX.'spotify_search_limit' ); ?>" />
                                    </td>
                                </tr>
                                <!-- Styles -->
                                <tr valign="top">
                                    <th scope="row" class="align-middle">
                                        <div class="th-div">
                                            <span class="mr-5"><?php _e( 'Styles', SS_TEXT_DOMAIN ); ?></span>
                                            <a class="show-info" data-info="<?php _e( 'Here you can define you own styles. <br>You can exclude all default styles and add your own or to modify the existing<br>Keep in mine that these styles can override another tags in the website! You should use the #spotify-search-container wrapper to prevent override.<br>  Absolute positioned results means that results will not push down the elements of your page.', SS_TEXT_DOMAIN ); ?>" href="javascript:;">
                                                <?php echo $help_svg; ?>
                                            </a>
                                        </div>
                                    </th>
                                    <td>
                                        <label>
                                            <input type="hidden" name="<?php echo SS_INPUTS_PREFIX; ?>spotify_search_default_styles" value="0">
                                            <input type="checkbox" class="spotify_search_default_styles" name="<?php echo SS_INPUTS_PREFIX; ?>spotify_search_default_styles" value="1" <?php echo get_option(SS_INPUTS_PREFIX.'spotify_search_default_styles') === false || trim(get_option(SS_INPUTS_PREFIX.'spotify_search_default_styles')) === '' || get_option(SS_INPUTS_PREFIX.'spotify_search_default_styles') === '1' ? 'checked' : ''; ?> />
                                            <?php _e( 'Default styles', SS_TEXT_DOMAIN ); ?>
                                        </label>

                                        <div>
                                            <label>
                                                <input type="hidden" name="<?php echo SS_INPUTS_PREFIX; ?>spotify_search_absolute_results" value="0">
                                                <input type="checkbox" class="spotify_search_default_styles_features" name="<?php echo SS_INPUTS_PREFIX; ?>spotify_search_absolute_results" value="1" <?php echo get_option(SS_INPUTS_PREFIX.'spotify_search_absolute_results') !== false && trim(get_option(SS_INPUTS_PREFIX.'spotify_search_absolute_results')) !== '' && get_option(SS_INPUTS_PREFIX.'spotify_search_absolute_results') === '1' ? 'checked' : ''; ?> />
                                                <?php _e( 'Absolute positioned results', SS_TEXT_DOMAIN ); ?>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <label>
                                                <?php _e( 'My styles', SS_TEXT_DOMAIN ); ?>
                                                <a class="show-info" data-info="<?php _e( 'Write only css without &lt;style&gt;&lt;/style&gt; tags', SS_TEXT_DOMAIN ); ?>" href="javascript:;">
                                                    <?php echo $help_svg; ?>
                                                </a>
                                            </label>
                                        </div>
                                        <textarea name="<?php echo SS_INPUTS_PREFIX; ?>spotify_search_styles"><?php echo get_option( SS_INPUTS_PREFIX.'spotify_search_styles' ); ?></textarea>
                                    </td>
                                </tr>
                                <!-- Shortcode -->
                                <tr valign="top">
                                    <td colspan="3">
                                        <div class="flex justify-center">
                                            <div id="shortcode" class="shortcode">
                                                <span>shortcode</span>
                                                <div>[<?php echo SS_PLUGIN_SHORTCODE; ?>]</div>
                                            </div>
                                            <a class="show-info ml-4" data-info="<?php _e( 'Copy and Paste this shortcode to any page or post where wants to show the spotify search', SS_TEXT_DOMAIN ); ?>" href="javascript:;">
                                                <?php echo $help_svg; ?>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>

                            <input type="hidden" name="action" value="update" />
                            <input type="hidden" name="page_options" value="ss_primary_setting" />

                            <?php settings_fields( 'ss-update-options' ); ?>
                        </table>
                        <div class="flex justify-end">
                            <button type="submit" class="button-primary"><?php _e( 'Save' ) ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full md:w-1/4 ad-col">
                <div class="p-4">
                    <div class="ad-box p-4 flex flex-wrap items-center justify-between">
                        <img src="<?php echo plugins_url('GitHub-Mark-64px.png', __FILE__ ); ?>" width="30px" height="30px" alt="GitHub">
                        <a href="https://github.com/Wordpress-Plugins-World" class="accent-button" target="_blank"><?php _e( 'Find Us', SS_TEXT_DOMAIN ); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </form>
 
    <!-- Start Modal -->
    <div class="ft-modal" id="spotify-search-info">
        <div class="ft-modal-content">
            <div class="ft-modal-header">
                <div class="header">
                    <h3 class="text-center"><?php _e( 'Helpful information', SS_TEXT_DOMAIN ); ?></h3>
                </div>
            </div>	
            <div class="ft-modal-body">
                <p id="info-box"></p>
                <hr>			
            </div>
            <div class="ft-modal-footer">
                <a class="ft-modal-close" onclick="closeModal()" href="#">[&#10006;] <?php _e( 'Close Modal', SS_TEXT_DOMAIN ); ?></a>
            </div>
        </div>
    </div>
    <!-- End Modal -->
</div>