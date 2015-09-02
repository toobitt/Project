(function(){
	var base_url = 'http://localhost/livsns/api/mobile/data/wificz_v1/',
		local_url = './json/';
 	return getUrl = function( type ){
		var config = {
			'list' : 'greeting_cards_list.php',
			'form' : 'greeting_cards_form.php',
			'post' : 'greeting_cards_create.php'
		};
		if( !config[ type ] ){
			return false;
		}
		return base_url + config[ type ];
	}
})();
