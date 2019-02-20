var path = require('path')
// var projectRoot = path.resolve(__dirname, '../')
var Elixir = require('laravel-elixir')
var utils = require('./utils/utils.js')
var config = require('./utils/config')
var merge = require('webpack-merge')

var loaders = merge({
// var loaders =  {
        resolve: {
            extensions: ['', '.js', '.vue'],
            fallback: [path.join(__dirname, '../node_modules')],
            alias: {
                'masonry': 'masonry-layout',
                'isotope': 'isotope-layout'
            //     'src': path.resolve(__dirname, '../src'),
            //     'assets': path.resolve(__dirname, '../src/assets'),
            //     'components': path.resolve(__dirname, '../src/components')
            }
        },
        resolveLoader: {
            fallback: [path.join(__dirname, '../node_modules')]
        },
        module: {
            loaders: [
                // {
                //     test: /\.vue$/,
                //     loader: 'vue'
                // },
                // {
                //     test: /\.js$/,
                //     loader: 'babel',
                //     include: projectRoot,
                //     exclude: /node_modules/
                // },
                // {
                //     test: /\.json$/,
                //     loader: 'json'
                // },
                {
                    test: /\.html$/,
                    loader: 'vue-html'
                },
                {
                    test: /\.(png|jpe?g|gif|svg)(\?.*)?$/,
                    loader: 'url',
                    query: {
                        limit: 10000,
                        name: utils.assetsPath('img/[name].[hash:7].[ext]')
                    }
                },
                // {
                //     test: /\.(woff2?|eot|ttf|otf)(\?.*)?$/,
                //     loader: 'url',
                //     query: {
                //         limit: 10000,
                //         name: utils.assetsPath('fonts/[name].[hash:7].[ext]')
                //     }
                // }
            ]

        },
        // module: {
        //     loaders: utils.styleLoaders({ sourceMap: config.dev.cssSourceMap })
        // },

        vue: {
            loaders: utils.cssLoaders()
        }
    }
    ,
    {
        module: {
            loaders: utils.styleLoaders({ sourceMap: config.dev.cssSourceMap })
        }
    });

Elixir.webpack.mergeConfig(loaders);