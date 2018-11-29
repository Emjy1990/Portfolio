// webpack.config.js
var path = require('path');
const Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/')
    .cleanupOutputBeforeBuild()
    .configureFilenames({
        css: 'css/main.css',
        js: 'js/main.js'
    })
    .enableReactPreset()
    .enableVersioning()
    .addEntry('main', './assets/js/app.js')

;


let config = Encore.getWebpackConfig();

config.resolve = {
    extensions: ['.js', 'css'],
    alias: {
        js: path.resolve(__dirname, './assets/js/'),
        css: path.resolve(__dirname, './assets/css/')
    }
};

module.exports = config;
