const paths = require('./paths')
const libraryModifications = require('./libraryModifications')

const PRODUCTION = false
const SERVER = false

let config = require('./webpack.js')(PRODUCTION, SERVER)

config = require('./webpack/browserDevTools.js')(config, PRODUCTION, SERVER)
config = require('./webpack/namedModules.js')(config, PRODUCTION, SERVER)
config = require('./webpack/developmenPerformance.js')(config, PRODUCTION, SERVER)
config = require('./webpack/compileScss.js')(config, PRODUCTION, SERVER)
config = require('./webpack/linkDependencies.js')(config, PRODUCTION, SERVER)

config.output.filename = 'webpack/js/bundle.js'

try {
    let projectWebpack = require(paths.appSrc + '/../config/webpack.browser.development.js')
    config = projectWebpack(config, PRODUCTION, SERVER)
} catch (e) {
    console.info('No build specific project webpack extension found in config/webpack.browser.development.js – skip: ' + e.message)
}

config = libraryModifications(config, PRODUCTION, SERVER)

try {
    let webpackPostProcessing = require(paths.appSrc + '/../config/webpack.post.js')
    config = webpackPostProcessing(config, PRODUCTION, SERVER)
} catch (e) {
    console.info('No project webpack post processing extension found in config/webpack.post.js – skip: ' + e.message)
}

module.exports = config
