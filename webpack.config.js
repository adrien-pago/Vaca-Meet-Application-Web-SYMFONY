// webpack.config.js

const Encore = require('@symfony/webpack-encore');

Encore
    // Configure les entrées (les fichiers JS et CSS à inclure dans votre application)
    .addEntry('app', './assets/app.js')
    .addStyleEntry('css/app', './assets/css/app.css')

    // Configure les sorties (les fichiers compilés JS et CSS)
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .enableSingleRuntimeChunk()

    // Configure les loaders pour gérer les différents types de fichiers
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()

    // Ajoute les fichiers CSS et JS de Bootstrap à votre build
    .addStyleEntry('bootstrap', './node_modules/bootstrap/dist/css/bootstrap.min.css')
    .addEntry('bootstrap_js', './node_modules/bootstrap/dist/js/bootstrap.bundle.min.js')

    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
;

module.exports = Encore.getWebpackConfig();
