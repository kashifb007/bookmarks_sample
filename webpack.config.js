const path = require('path');
const TerserPlugin = require('terser-webpack-plugin');
const VueLoaderPlugin = require('vue-loader/lib/plugin');
const autoprefixer = require('autoprefixer');
const source = path.resolve(__dirname, 'resources');
const glob = require('glob');

// use this to load inline styles in javascript
function externalForMaterialUi(context, request, callback) {
    if (/@material-ui.+/.test(request)) {
        const name = request.replace(/^.*[\\\/]/, '');
        return callback(null, 'root MaterialUI.' + name);
    }
    callback();
}

module.exports = {
    entry: {
        app: `${source}/js/app.js`,
        dropdown_js: `${source}/js/dist/dropdown.js`,
        transition_js: `${source}/js/dist/transition.js`,
        css: `${source}/sass/app.scss`,
        dropdown: `${source}/sass/dist/dropdown.scss`,
        transition: `${source}/sass/dist/transition.scss`,
    },
    output: {
        path: path.resolve(__dirname, 'public/js'),
        filename: '[name].min.js',
    },
    mode: 'production',
    module: {
        rules: [
            {
                test: /\.vue$/,
                loader: 'vue-loader',
            },
            {
                test: /\.s[ac]ss$/i,
                use: [
                    {
                        loader: 'file-loader',
                        options: {
                            publicPath: 'public',
                            name: '../css/[name].min.css',
                        },
                    },
                    { loader: 'extract-loader' },
                    { loader: 'css-loader' },
                    {
                        loader: 'postcss-loader',
                        options: {
                            plugins: () => [autoprefixer()]
                        }
                    },
                    {
                        loader: 'sass-loader',
                        options: {
                            implementation: require('sass'),
                            webpackImporter: false,
                            sassOptions: {
                                includePaths: ['./node_modules']
                            },
                        },
                    },
                ],
            },
        ],
    },
    watchOptions: {
        aggregateTimeout: 200,
        poll: 1000,
        ignored: [
            path.resolve(__dirname, 'node_modules'),
        ],
    },
    optimization: {
        minimizer: [
            new TerserPlugin({
                cache: true,
                parallel: true,
                sourceMap: true, // Must be set to true if using source-maps in production
                terserOptions: {
                    output: {
                        comments: false,
                    },
                },
            }),
        ],
        nodeEnv: 'production',
    },
    plugins: [
        new VueLoaderPlugin(),
    ],
    externals: [
        externalForMaterialUi, // use this to load inline styles in javascript
    ],
};
