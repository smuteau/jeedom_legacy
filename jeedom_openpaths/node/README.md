# OpenPaths-API

A Node.js module for the [OpenPaths API](https://openpaths.cc/api).

This module was inspired by [node-openpaths](https://github.com/christophercliff/node-openpaths), but this one has posting functionality, and works with parameters.

## Pre-requisites

You need [Node.js](http://nodejs.org/) and [npm](https://www.npmjs.org/).

You also need an [OpenPaths](https://openpaths.cc) account.

## Installation

`$ npm install openpaths-api`

## Usage

View the examples on the `examples/` directory for getting and posting geo points, or the examples below.

## API Documentation

You can find more information over at [OpenPaths' official API documentation](https://openpaths.cc/api). Since it's still pretty simple, what you need to know:

### Initializing

```js
var OpenPathsAPI = require( 'openpaths-api' ),
    openPaths = new OpenPathsAPI({
        key: 'YOUR_KEY',
        secret: 'YOUR_SECRET'
    });
```

### Getting geo points

```js
// Params are optional, you can call .getPoints with the callback as the first argument
var params = {
    start_time: 0, // Unix timestamp
    end_time: Math.round( new Date().getTime() / 1000 ), // Unix timestamp
    num_points: 100 // Default, allowed 0 - 2000
};

openPaths.getPoints( params, function( error, response, points ) {
    if ( error ) {
        throw new Error( JSON.stringify(error) );
    }

    console.log( points );
});
```

### Posting geo points

```js
var points = [
    {
        lat: 40.678238, // Latitude
        lon: -73.945789, // Longitude
        alt: 18.914, // Altitude (in meters)
        t: 1410213632 // Unix timestamp
    }
];

openPaths.postPoints( points, function( error, response, data ) {
    if ( error ) {
        throw new Error( JSON.stringify(error) );
    }

    console.log( data );// { success: 'true' }
});
```