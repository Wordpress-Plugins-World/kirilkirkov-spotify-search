<?php 
    $help_svg = '<svg version="1.1" fill="#1ed760" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="17px" height="17px" viewBox="0 0 400 400" style="enable-background:new 0 0 400 400;" xml:space="preserve"><g><g><path d="M199.996,0C89.719,0,0,89.72,0,200c0,110.279,89.719,200,199.996,200C310.281,400,400,310.279,400,200C400,89.72,310.281,0,199.996,0z M199.996,373.77C104.187,373.77,26.23,295.816,26.23,200c0-95.817,77.957-173.769,173.766-173.769c95.816,0,173.772,77.953,173.772,173.769C373.769,295.816,295.812,373.77,199.996,373.77z"/><path d="M199.996,91.382c-35.176,0-63.789,28.616-63.789,63.793c0,7.243,5.871,13.115,13.113,13.115c7.246,0,13.117-5.873,13.117-13.115c0-20.71,16.848-37.562,37.559-37.562c20.719,0,37.566,16.852,37.566,37.562c0,20.714-16.849,37.566-37.566,37.566c-7.242,0-13.113,5.873-13.113,13.114v45.684c0,7.243,5.871,13.115,13.113,13.115s13.117-5.872,13.117-13.115v-33.938c28.905-6.064,50.68-31.746,50.68-62.427C263.793,119.998,235.176,91.382,199.996,91.382z"/><path d="M200.004,273.738c-9.086,0-16.465,7.371-16.465,16.462s7.379,16.465,16.465,16.465c9.094,0,16.457-7.374,16.457-16.465S209.098,273.738,200.004,273.738z"/></g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>';
?>

<div id="kirilkirkov" class="wrap">
    <form method="post" action="options.php">
        <div class="header p-4 flex items-center space-between">
            <div class="flex items-center">
                <span class="dashicons dashicons-spotify mr-4"></span>
                <h2><?php esc_html_e('Spotify Search'); ?> — <?php esc_html_e( 'Page: Settings', 'kirilkirkov-spotify-search' ) ?></h2>    
            </div>
            
            <button type="submit" class="button-primary"><?php esc_html_e( 'Save', 'kirilkirkov-spotify-search' ) ?></button>
        </div>
    
        <div class="flex flex-wrap">
            <div class="w-full md:w-3/4">
                <div class="section-header p-4">
                    <strong><?php esc_html_e( 'Spotify Search', 'kirilkirkov-spotify-search' ) ?></strong>
                    <p>
                        <?php esc_html_e('
                        Spotify Search plugin provides a way to find any Track, Album, Playlist or Artist in 
                        spotify.com throught their API. Spotify is one of the largest music streaming service 
                        providers with over 406 million monthly active users, including 180 million paying 
                        subscribers, as of December 2021.
                        Enjoy!
                        ', 'kirilkirkov-spotify-search' ) ?>
                    </p>
                </div>

                <div class="section-body">
                    <div class="p-4">
                        <?php if(is_string($this->spotify_api_error))  { ?>
                            <p class="alert alert-danger"><strong><?php esc_html_e( 'Spotify API Error!', 'kirilkirkov-spotify-search' ); ?></strong> <?php echo $this->spotify_api_error; ?></p>
                        <?php } ?>
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row" class="align-middle">
                                    <div class="th-div">
                                        <span class="mr-5"><?php esc_html_e( 'Spotify API Credentials', 'kirilkirkov-spotify-search' ); ?></span>
                                        <a class="show-info" data-info="<?php esc_attr_e( 'To get your spotify API keys go to <a href=\'https://developer.spotify.com/dashboard\' target=\'_blank\'>https://developer.spotify.com</a> and register yours application. <br> In the application overview you will find the client id and secret id, copy and paste them here. <br> Click Edit Settings in your app and in Redirect URIs enter <b>' . $this->settings_url . '</b> <br> Thats It!', 'kirilkirkov-spotify-search' ) ?>" href="javascript:;">
                                            <?php echo $help_svg; ?>
                                        </a>
                                    </div>
                                </th>
                                <td>
                                    <label><?php esc_html_e( 'Client ID', 'kirilkirkov-spotify-search' ); ?></label>
                                    <input type="text" name="<?php esc_attr_e($Config::INPUTS_PREFIX); ?>spotify_search_client_id" value="<?php echo esc_attr(get_option( $Config::INPUTS_PREFIX.'spotify_search_client_id' )); ?>" />
                                </td>
                                <td>
                                    <label><?php esc_html_e( 'Client Secret', 'kirilkirkov-spotify-search' ); ?></label>
                                    <input type="text" name="<?php esc_attr_e($Config::INPUTS_PREFIX); ?>spotify_search_client_secret" value="<?php echo esc_attr(get_option( $Config::INPUTS_PREFIX.'spotify_search_client_secret' )); ?>" />
                                </td>
                            </tr>
                            
                            <!-- Generate tokens for the api -->
                            <?php if(!is_null($this->spotify_redirect_url)) { ?>
                            <tr>
                                <td colspan="3" class="p-0">
                                    <div>
                                        <?php if(get_option($Config::INPUTS_PREFIX.'spotify_search_refresh_token') && trim(get_option($Config::INPUTS_PREFIX.'spotify_search_refresh_token')) != '') { ?>
                                            <div class="flex justify-center mb-5">
                                                <p class="alert alert-success"><strong><?php esc_html_e( 'Congratulations!', 'kirilkirkov-spotify-search' ); ?></strong> <?php esc_html_e( 'You are ready, you has access tokens.', 'kirilkirkov-spotify-search' ); ?></p>
                                            </div>
                                        <?php } else { ?>
                                            <div class="flex justify-center mb-5">
                                                <p class="alert alert-info"><strong><?php esc_html_e( 'Mostly finished!', 'kirilkirkov-spotify-search' ); ?></strong> <?php esc_html_e( 'Click the button "Get token" to generate tokens.', 'kirilkirkov-spotify-search' ); ?></p>
                                            </div>
                                        <?php } ?>
                                        <div class="flex justify-center p-4">
                                            <a class="default-btn" href="<?php esc_attr_e($this->spotify_redirect_url); ?>">
                                                <?php if(get_option($Config::INPUTS_PREFIX.'spotify_search_refresh_token') && trim(get_option($Config::INPUTS_PREFIX.'spotify_search_refresh_token')) != '') { ?>
                                                    <?php esc_html_e( 'Get New Tokens', 'kirilkirkov-spotify-search' ); ?>
                                                <?php } else { ?>
                                                    <?php esc_html_e( 'Get Token', 'kirilkirkov-spotify-search' ); ?>
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
                                            <span class="mr-5"><?php esc_html_e( 'Search type', 'kirilkirkov-spotify-search' ); ?></span>
                                            <a class="show-info" data-info="<?php esc_attr_e( 'Which type of results wants to search, eg. Artists, Tracks, Playlists or Albums. <br> They should be separated with comma. <br> Example for all types: <b>track,artist,album,playlist</b><br>Empty field will search in all types', 'kirilkirkov-spotify-search' ); ?>" href="javascript:;">
                                                <?php echo $help_svg; ?>
                                            </a>
                                        </div>
                                    </th>
                                    <td colspan="2">
                                        <label><?php esc_html_e( 'Assets', 'kirilkirkov-spotify-search' ); ?></label>
                                        <input type="text" name="<?php esc_attr_e($Config::INPUTS_PREFIX); ?>spotify_search_search_type" placeholder="<?php esc_attr_e('track,artist,album,playlist') ?>" value="<?php echo esc_attr(get_option( $Config::INPUTS_PREFIX.'spotify_search_search_type' )); ?>" />
                                    </td>
                                </tr>
                                <!-- Results limit -->
                                <tr valign="top">
                                    <th scope="row" class="align-middle">
                                        <div class="th-div">
                                            <span class="mr-5"><?php esc_html_e( 'Results limit', 'kirilkirkov-spotify-search' ); ?></span>
                                            <a class="show-info" data-info="<?php esc_attr_e( 'Count of the results which wants to show<br>Results limit is per asset, so if you search in albums and tracks you will get 4 results total.<br>Results limit can not me more that 20 (this is Spotify restriction per page)', 'kirilkirkov-spotify-search' ); ?>" href="javascript:;">
                                                <?php echo $help_svg; ?>
                                            </a>
                                        </div>
                                    </th>
                                    <td colspan="2">
                                        <label><?php esc_html_e( 'Count', 'kirilkirkov-spotify-search' ); ?></label>
                                        <input type="number" max="20" name="<?php esc_attr_e($Config::INPUTS_PREFIX); ?>spotify_search_limit" placeholder="20" value="<?php echo esc_attr(get_option( $Config::INPUTS_PREFIX.'spotify_search_limit' )); ?>" />
                                    </td>
                                </tr>
                                <!-- Styles -->
                                <tr valign="top">
                                    <th scope="row" class="align-middle">
                                        <div class="th-div">
                                            <span class="mr-5"><?php esc_html_e( 'Styles', 'kirilkirkov-spotify-search' ); ?></span>
                                            <a class="show-info" data-info="<?php esc_attr_e( 'Here you can define you own styles. <br>You can exclude all default styles and add your own or to modify the existing<br>Keep in mine that these styles can override another tags in the website! You should use the #spotify-search-container wrapper to prevent override.<br>  Absolute positioned results means that results will not push down the elements of your page.', 'kirilkirkov-spotify-search' ); ?>" href="javascript:;">
                                                <?php echo $help_svg; ?>
                                            </a>
                                        </div>
                                    </th>
                                    <td>
                                        <div class="mb-4">
                                            <label>
                                                <input type="hidden" name="<?php esc_attr_e($Config::INPUTS_PREFIX); ?>spotify_search_default_styles" value="0">
                                                <input type="checkbox" class="spotify_search_default_styles" name="<?php esc_attr_e($Config::INPUTS_PREFIX); ?>spotify_search_default_styles" value="1" <?php echo get_option($Config::INPUTS_PREFIX.'spotify_search_default_styles') === false || trim(get_option($Config::INPUTS_PREFIX.'spotify_search_default_styles')) === '' || get_option($Config::INPUTS_PREFIX.'spotify_search_default_styles') === '1' ? 'checked' : ''; ?> />
                                                <?php esc_html_e( 'Default styles', 'kirilkirkov-spotify-search' ); ?>
                                            </label>
                                        </div>

                                        <div class="mb-4">
                                            <label>
                                                <input type="hidden" name="<?php esc_attr_e($Config::INPUTS_PREFIX); ?>spotify_search_absolute_results" value="0">
                                                <input type="checkbox" class="spotify_search_default_styles_features" name="<?php esc_attr_e($Config::INPUTS_PREFIX); ?>spotify_search_absolute_results" value="1" <?php echo get_option($Config::INPUTS_PREFIX.'spotify_search_absolute_results') !== false && trim(get_option($Config::INPUTS_PREFIX.'spotify_search_absolute_results')) !== '' && get_option($Config::INPUTS_PREFIX.'spotify_search_absolute_results') === '1' ? 'checked' : ''; ?> />
                                                <?php esc_html_e( 'Absolute positioned results', 'kirilkirkov-spotify-search' ); ?>
                                            </label>
                                        </div>

                                        <div>
                                            <label>
                                                <input type="hidden" name="<?php esc_attr_e($Config::INPUTS_PREFIX); ?>spotify_search_show_on_tunedex" value="0">
                                                <input type="checkbox" class="spotify_search_show_on_tunedex" name="<?php esc_attr_e($Config::INPUTS_PREFIX); ?>spotify_search_show_on_tunedex" value="1" <?php echo get_option($Config::INPUTS_PREFIX.'spotify_search_show_on_tunedex') !== false && get_option($Config::INPUTS_PREFIX.'spotify_search_show_on_tunedex') === '1' ? 'checked' : ''; ?> />
                                                <?php esc_html_e( 'Open found results in Tunedex.RouteNote.com', 'kirilkirkov-spotify-search' ); ?>
                                            </label>
                                            <a class="show-info" data-info="<?php esc_attr_e( 'If you check this option, found results will be opened in <a href="https://tunedex.routenote.com" target="_blank">tunedex.routenote.com</a> which is biggest Open Music Database. <br> Benefit is that on Tunedex will show a very big collection of additional information about the artists, albums and tracks with <b>multiple listen and download relations</b> where they are uploaded.', 'kirilkirkov-spotify-search' ); ?>" href="javascript:;">
                                                <?php echo $help_svg; ?>
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <label>
                                                <?php esc_html_e( 'My styles', 'kirilkirkov-spotify-search' ); ?>
                                                <a class="show-info" data-info="<?php esc_attr_e( 'Write only css without &lt;style&gt;&lt;/style&gt; tags', 'kirilkirkov-spotify-search' ); ?>" href="javascript:;">
                                                    <?php echo $help_svg; ?>
                                                </a>
                                            </label>
                                        </div>
                                        <textarea name="<?php esc_attr_e($Config::INPUTS_PREFIX); ?>spotify_search_styles"><?php echo esc_html(get_option( $Config::INPUTS_PREFIX.'spotify_search_styles' )); ?></textarea>
                                    </td>
                                </tr>
                                <!-- Shortcode -->
                                <tr valign="top">
                                    <td colspan="3">
                                        <div class="flex justify-center">
                                            <div class="shortcode">
                                                <span><?php esc_html_e('shortcode'); ?></span>
                                                <div>[<?php echo esc_attr_e($Config::SHORTCODE); ?>]</div>
                                            </div>
                                            <a class="show-info ml-4" data-info="<?php esc_attr_e( 'Copy and Paste this shortcode to any page or post where wants to show the spotify search', 'kirilkirkov-spotify-search' ); ?>" href="javascript:;">
                                                <?php echo $help_svg; ?>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>

                            <input type="hidden" name="action" value="update" />
                            <input type="hidden" name="page_options" value="<?php esc_attr_e($Config::INPUTS_PREFIX); ?>_primary_setting" />

                            <?php settings_fields($Config::INPUT_GROUP); ?>
                        </table>
                        <div class="flex justify-end">
                            <button type="submit" class="button-primary"><?php esc_html_e( 'Save', 'kirilkirkov-spotify-search' ) ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full md:w-1/4 ad-col">
                
                <div class="p-4">
                    <div class="ad-box p-4 mb-4 flex flex-wrap items-center justify-between">
                        <img src="<?php echo plugins_url('GitHub-Mark-64px.png', __FILE__ ); ?>" width="30px" height="30px" alt="GitHub">
                        <a href="https://github.com/Wordpress-Plugins-World" class="accent-button" target="_blank"><?php esc_html_e( 'Find Us', 'kirilkirkov-spotify-search' ); ?></a>
                    </div>


                    <div class="ad-box mb-4 p-4 flex flex-wrap items-center justify-between">
                        <p class="m-0 mb-4 text-center"><?php esc_html_e( 'Get Powerful WordPress Invoices Generator', 'kirilkirkov-spotify-search' ); ?></p>
                        <a href="https://codecanyon.net/item/wp-invoices-pdf-electronic-invoicing-system/36891583" class="w-full" target="_blank">
                            <img class="w-full" src="<?php echo plugins_url('Assets/Img/274x300.png', __FILE__ ); ?>" alt="<?php echo esc_attr( 'WordPress Invoices' ) ?>" />
                        </a>
                    </div>

                    <div class="ad-box p-4 flex flex-wrap items-center justify-between">
                        <p class="m-0 mb-4 text-center"><?php esc_html_e( 'Get Powerful Project Management Ticketing System', 'kirilkirkov-wp-invoices' ); ?></p>
                        <a href="https://codecanyon.net/item/agile-scrum-project-issue-management/36720961" class="w-full" target="_blank">
                            <img class="w-full" src="<?php echo plugins_url('/Assets/Img/banner.jpg', __FILE__ ); ?>" alt="<?php echo esc_attr( 'Agile Scrum' ) ?>" />
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </form>
 
    <!-- Start Modal -->
    <div class="ft-modal spotify-search-info">
        <div class="ft-modal-content">
            <div class="ft-modal-header">
                <div class="header">
                    <h3 class="text-center"><?php esc_html_e( 'Helpful information', 'kirilkirkov-spotify-search' ); ?></h3>
                </div>
            </div>	
            <div class="ft-modal-body">
                <p class="info-box"></p>
                <hr>			
            </div>
            <div class="ft-modal-footer">
                <a class="ft-modal-close" href="javascript:;">[&#10006;] <?php esc_html_e( 'Close Modal', 'kirilkirkov-spotify-search' ); ?></a>
            </div>
        </div>
    </div>
    <!-- End Modal -->
</div>