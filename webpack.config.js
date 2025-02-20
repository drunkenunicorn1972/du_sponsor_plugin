const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');
let path = require('path');

// change these variables to fit your project
const jsPublicPath = './assets/public/js';
const cssPublicPath = './assets/public/scss';
const outputPublicPath = 'public';
const jsAdminPath = './assets/admin/js';
const cssAdminPath = './assets/admin/scss';
const outputAdminPath = 'admin';

const localDomain = 'https://ovh.local';
const entryPointsPublic = {
    // 'app' is the output name, people commonly use 'bundle'
    // you can have more than 1 entry point
    'app': jsPublicPath + '/app.js',
    'style': cssPublicPath + '/style.scss',
    'app_admin': jsAdminPath + '/du-sponsors-admin.js',
    'style_admin': cssAdminPath + '/style.scss'

};

module.exports = {
    entry: entryPointsPublic,
    output: {
        path: path.resolve(__dirname, outputPublicPath),
        filename: 'js/du-sponsors-[name].js'
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: 'css/du-sponsors-[name].css',
        }),

        // Uncomment this if you want to use CSS Live reload
        new BrowserSyncPlugin({
          proxy: localDomain,
          files: 'dist/css/*.css',
          injectCss: true,
        }, { reload: false, }),
    ],
    module: {
        rules: [
            {
                test: /\.s?[c]ss$/i,
                use: [
                    MiniCssExtractPlugin.loader,
                    'css-loader',
                    'sass-loader',
                ],
            },
            {
                test: /\.sass$/i,
                use: [
                    MiniCssExtractPlugin.loader,
                    'css-loader',
                    {
                        loader: 'sass-loader',
                        options: {
                            sassOptions: {indentedSyntax: true},
                        },
                    },
                ],
            },
            {
                test: /\.(jpg|jpeg|png|gif|svg)$/i,
                type: 'asset/resource',
                generator: {
                    filename: 'images/[hash][ext][query]'
                }
            },
            {
                test: /\.(eot|woff|woff2|ttf)$/,
                type: 'asset/resource',
                generator: {
                    filename: 'fonts/[hash][ext][query]'
                }
            },
        ]
    },
};