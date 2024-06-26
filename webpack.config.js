// webpack.config.js
const Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('login', './assets/styles/login.css')
    .addEntry('register', './assets/styles/register.css')
    .addEntry('confirmation_email', './assets/styles/confirmation_email.css')
    .addEntry('home', './assets/styles/home.css')
    .addEntry('structure', './assets/styles/structure.css')
    .addStyleEntry('bootstrap', './node_modules/bootstrap/dist/css/bootstrap.min.css')
    .addEntry('bootstrap-js', './node_modules/bootstrap/dist/js/bootstrap.bundle.min.js')
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .enablePostCssLoader()
;

module.exports = Encore.getWebpackConfig();
