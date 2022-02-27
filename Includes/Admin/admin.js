const info_btns = document.querySelectorAll('.show-info');
let info_box = document.getElementById('spotify-search-info');

// listen click info modal
info_btns.forEach(el => el.addEventListener('click', event => {
    info_box.style.visibility = 'visible';
    info_box.style.opacity = 1;
    document.getElementById('info-box').innerHTML = el.getAttribute("data-info");
}));

// close modal
function closeModal() {
    info_box.style.visibility = 'hidden';
    info_box.style.opacity = 0;
}

// shortcode select
jQuery("#shortcode div").on('mouseup', function() {
	var sel, range;
	var el = jQuery(this)[0];
	if (window.getSelection && document.createRange) { //Browser compatibility
	  sel = window.getSelection();
	  if(sel.toString() == ''){ //no text selection
		 window.setTimeout(function(){
			range = document.createRange(); //range object
			range.selectNodeContents(el); //sets Range
			sel.removeAllRanges(); //remove all ranges from selection
			sel.addRange(range);//add Range to a Selection.
		},1);
	  }
	}else if (document.selection) { //older ie
		sel = document.selection.createRange();
		if(sel.text == ''){ //no text selection
			range = document.body.createTextRange();//Creates TextRange object
			range.moveToElementText(el);//sets Range
			range.select(); //make selection.
		}
	}
});

// if uncheck default styles remove features to it
jQuery('.spotify_search_default_styles').change(function(e) {
	if(!jQuery(this).is(":checked")) {
		jQuery('.spotify_search_default_styles_features').removeAttr('checked');
	}
});