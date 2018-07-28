var webpack = require('webpack');
var path = require('path');

var BUILD_DIR = path.resolve(__dirname, 'public');
var APP_DIR = path.resolve(__dirname, 'app');

var config = {
    entry: ['babel-polyfill', APP_DIR + '/index.jsx'],
    output: {
        path: BUILD_DIR,
        filename: 'bundle.js'
    },
    module : {
        rules : [
            {
                test : /\.jsx?/,
                include : APP_DIR,
                loader : 'babel-loader',
                options: {
                    presets: ['react']
                }
            },
            {
                test : /\.css$/,
                use: ['style-loader', 'css-loader']
            },
        ]
    },
};

module.exports = config;