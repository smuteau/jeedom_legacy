var request = require( 'request' ),
    OAuth = require( 'oauth-1.0a' ),
    QueryString = require( 'querystring' );

function OpenPathsAPI( options ) {
    var oauth;

    if ( ! options ) {
        throw new Error( 'No options set for OpenPathsAPI. You need to set at least options.key and options.secret when initializing OpenPathsAPI.' );
    }

    if ( ! options.key ) {
        throw new Error( 'No options.key set for OpenPathsAPI. You need to set at least options.key and options.secret when initializing OpenPathsAPI.' );
    }

    if ( ! options.secret ) {
        throw new Error( 'No options.secret set for OpenPathsAPI. You need to set at least options.key and options.secret when initializing OpenPathsAPI.' );
    }

    if ( ! options.version ) {
        options.version = 1;
    }

    oauth = new OAuth({
        consumer: {
            public: options.key,
            secret: options.secret
        },
        signature_method: 'HMAC-SHA1'
    });

    if ( ! options.apiURL ) {
        options.apiURL = 'https://openpaths.cc/api/' + options.version;
    }
    
    this.getPoints = function( params, callback ) {
        var queryString,
            resourceURL,
            requestData;

        if ( ! oauth ) {
            throw new Error( 'There was an error initializing OAuth. Please make sure your options.key and options.secret are valid.' );
        }

        // Allow calling this method without parameters (callback as first argument)
        if ( typeof params === 'function' ) {
            callback = params;
            params = {};
        }

        // Make sure callback will be a callable function
        if ( typeof callback !== 'function' ) {
            callback = function() {};
        }

        // Parse params into querystring
        queryString = QueryString.stringify( params );

        // Add params to options.apiURL
        resourceURL = options.apiURL + '?' + queryString;

        requestData = {
            url: options.apiURL,// OpenPaths' API has a bug where the URL for signing can't have the params. More info at http://stackoverflow.com/questions/15804729/passing-querystring-parameters-in-openpaths-cc-api-call-with-oauth-not-working
            method: 'GET'
        };

        request({
            url: resourceURL,
            method: requestData.method,
            headers: oauth.toHeader( oauth.authorize(requestData, '') )
        }, callback );
    };

    this.postPoints = function( points, callback ) {
        var requestData;

        if ( ! oauth ) {
            throw new Error( 'There was an error initializing OAuth. Please make sure your options.key and options.secret are valid.' );
        }

        // Make sure callback will be a callable function
        if ( typeof callback !== 'function' ) {
            callback = function() {};
        }

        requestData = {
            url: options.apiURL,
            method: 'POST'
            // Usually the data would go as a data property here, but OpenPaths' API has a bug, mentioned on .getPoints, and we can't send it
        };

        request({
            url: options.apiURL,
            method: requestData.method,
            form: {
                points: JSON.stringify(points)
            },
            headers: oauth.toHeader( oauth.authorize(requestData, '') )
        }, callback );
    };  
}

module.exports = OpenPathsAPI;