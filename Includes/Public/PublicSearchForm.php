<div id="kirilkirkov-spotify-search-container">
  <form class="spotify-search-form" action="" onsubmit="return false">
    <div class="inputs">
        <input type="text" placeholder="Arists, songs, or playlists" name="spotify_search_input" value="">
    </div>
    <button type="submit">
        <span class="ready">
          <svg fill="#000000" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30" width="25px" height="25px"><path d="M 13 3 C 7.4889971 3 3 7.4889971 3 13 C 3 18.511003 7.4889971 23 13 23 C 15.396508 23 17.597385 22.148986 19.322266 20.736328 L 25.292969 26.707031 A 1.0001 1.0001 0 1 0 26.707031 25.292969 L 20.736328 19.322266 C 22.148986 17.597385 23 15.396508 23 13 C 23 7.4889971 18.511003 3 13 3 z M 13 5 C 17.430123 5 21 8.5698774 21 13 C 21 17.430123 17.430123 21 13 21 C 8.5698774 21 5 17.430123 5 13 C 5 8.5698774 8.5698774 5 13 5 z"/></svg>
        </span>
        <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
        <a class="spotify-search-clear" href="javascript:;">
            <svg width="10px" height="10px" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><g data-name="Lager 20" transform="translate(-6 -6)"><path data-name="Path 23" d="M18.695,16l6.752-6.752a1.886,1.886,0,0,0,0-2.668l-.027-.027a1.886,1.886,0,0,0-2.668,0L16,13.305,9.248,6.553a1.886,1.886,0,0,0-2.668,0l-.027.027a1.886,1.886,0,0,0,0,2.668L13.305,16,6.553,22.752a1.886,1.886,0,0,0,0,2.668l.027.027a1.886,1.886,0,0,0,2.668,0L16,18.695l6.752,6.752a1.886,1.886,0,0,0,2.668,0l.027-.027a1.886,1.886,0,0,0,0-2.668Z" fill="#040505"/></g></svg>
        </a>
    </button>
  </form>

  <div class="spotify-table-wrapper">
      <ul class="spotify-search-results"></ul>
  </div>
</div>

<?php if(get_option(KIRILKIRKOV_SPOTIFY_SEARCH_INPUTS_PREFIX.'spotify_search_absolute_results') !== false && trim(get_option(KIRILKIRKOV_SPOTIFY_SEARCH_INPUTS_PREFIX.'spotify_search_absolute_results')) != '' && get_option(KIRILKIRKOV_SPOTIFY_SEARCH_INPUTS_PREFIX.'spotify_search_absolute_results') === '1') { ?>
<style>
#kirilkirkov-spotify-search-container {
  position: relative;
}
#kirilkirkov-spotify-search-container .spotify-table-wrapper {
  position: absolute;
  overflow-y: scroll;
  max-height: 300px;
  z-index: 999999999999;
  margin-top: 5px;
  -webkit-box-shadow: 6px 5px 15px -4px #000000; 
  box-shadow: 6px 5px 15px -4px #000000;
}
</style>
<?php } ?>

<?php if(get_option(KIRILKIRKOV_SPOTIFY_SEARCH_INPUTS_PREFIX.'spotify_search_styles') && trim(get_option(KIRILKIRKOV_SPOTIFY_SEARCH_INPUTS_PREFIX.'spotify_search_styles')) != '') { ?>
<style>
  <?php echo get_option(KIRILKIRKOV_SPOTIFY_SEARCH_INPUTS_PREFIX.'spotify_search_styles'); ?>
</style>
<?php } ?>