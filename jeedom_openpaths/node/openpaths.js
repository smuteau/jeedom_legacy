var request = require('request');

var urlJeedom = '';
var key = '';
var secret = '';
var id = '';

process.env.NODE_TLS_REJECT_UNAUTHORIZED = "0";

// print process.argv
process.argv.forEach(function(val, index, array) {

	switch ( index ) {
		case 2 : urlJeedom = val; break;
		case 3 : key = val; break;
		case 4 : secret = val; break;
		case 5 : id = val; break;
	}

});

var OpenPathsAPI = require( './openpaths-api.js' ),
    openPaths = new OpenPathsAPI({
        key: key,
        secret: secret
    }),
    params = {
        num_points: 1,
        start_time: 0
    };

openPaths.getPoints( params, function( error, response, points ) {
    if ( error ) {
        throw new Error( JSON.stringify(error) );
    }

    //console.log( points );
		url = urlJeedom + "&type=openpaths&id=" + id;

		request({
			url: url,
			method: 'PUT',
			json: {"data": points.toString()},
		},

		function (error, response, body) {
			  if (!error && response.statusCode == 200) {
				//console.log( response.statusCode);
			  }else{
			  	console.log( error );
			  }
			});
});
