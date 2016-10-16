var OpenPathsAPI = require( '../openpaths-api.js' ),
    openPaths = new OpenPathsAPI({
        key: 'YOUR_KEY',
        secret: 'YOUR_SECRET'
    }),
    params = {
        num_points: 1,
        start_time: 0
    };

openPaths.getPoints( params, function( error, response, points ) {
    if ( error ) {
        throw new Error( JSON.stringify(error) );
    }

    console.log( points );
});