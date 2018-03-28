var Encore = require('@symfony/webpack-encore');

Encore
    .enableSassLoader(function(sassOptions) {}, {
        resolveUrlLoader: false
     })
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .addEntry('js/app', './assets/js/app.js')
    .addStyleEntry('css/app', './assets/scss/app.scss')
    .enableSassLoader()
;

module.exports = Encore.getWebpackConfig();
