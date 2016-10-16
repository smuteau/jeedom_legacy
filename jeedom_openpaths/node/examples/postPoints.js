var OpenPathsAPI = require( '../openpaths-api.js' ),
    openPaths = new OpenPathsAPI({
        key: 'YOUR KEY',
        secret: 'YOUR SECRET'
    }),
    points = [
        {
            lat: 40.678238,
            lon: -73.945789,
            alt: 18.914,
            t: 1410213632
        }
    ];

openPaths.postPoints( points, function( error, response, data ) {
    if ( error ) {
        throw new Error( JSON.stringify(error) );
    }

    console.log( data );// { success: 'true' }
});