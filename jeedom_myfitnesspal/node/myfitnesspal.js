var mfp = require('mfp');
var request = require('request');

var urlJeedom = '';
var user = '';

process.env.NODE_TLS_REJECT_UNAUTHORIZED = "0";

// print process.argv
process.argv.forEach(function(val, index, array) {

	switch ( index ) {
		case 2 : urlJeedom = val; break;
		case 3 : user = val; break;
	}

});

var date=new Date();
var day=date.getDate();
day = (day < 10 ? "0" : "") + day;
var month=date.getMonth() + 1;
month = (month < 10 ? "0" : "") + month;
var year=date.getFullYear();

			mfp.diaryStatusCheck(user, function(status) {
					if(status=="public"){
							mfp.fetchSingleDate(''+user, ''+year+'-'+month+'-'+day, 'all', function(data){
									if(data['calories']!=undefined){

									url = urlJeedom + "&type=myfitnesspal&user=" + user + "&calories=" + data['calories'] + "&carbs=" + data['carbs'] + "&fat=" + data['fat'] + "&protein=" + data['protein'] + "&cholesterol=" + data['cholesterol'] + "&sodium=" + data['sodium'] + "&sugar=" + data['sugar'] + "&fiber=" + data['fiber'];
									console.log( url );
									request(url, function (error, response, body) {
								  if (!error && response.statusCode == 200) {
									console.log("Got response from Jeedom : " + response.statusCode);
								  }
									});
								}
								else{
								console.log( "No data for " + user );
								}
							});
					}
					else{
						console.log( "Data not public for " + user );
					}
			});
