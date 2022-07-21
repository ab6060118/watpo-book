const webpack = require('webpack');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const path = require('path');

const settings = [{
  name: 'app',
  target: 'web',
  devtool: 'source-map',
  entry: [
    './resources/src/',
  ],
  output: {
    path: path.join(__dirname, '/public/assets/frontend'),
    publicPath: '/',
    filename: 'bundle.js',
  },
  module: {
    rules: [
      {
        test: /\.js?$/,
        use: ['babel-loader'],
        exclude: /node_modules/,
      },
      {
        test: /\.sass$/,
        use: [
          'style-loader',
          'css-loader',
          'postcss-loader',
          'sass-loader',
        ],
        exclude: /node_modules/,
      },
      {
        test: /\.(png|jpe?g|gif|svg)(\?.*)?$/,
        use: [
          {
            loader: 'url-loader',
            options: {
              limit: 10000, /* file smaller than 10kB would be transformed into base64 */
              name: '/images/book/[name].[ext]',
              publicPath: '../assets',
            },
          },
        ],
      },
      {
        test: /\.(eot|svg|ttf|woff|otf|woff2)$/,
        loader: 'url-loader',
        options: {
          limit: 65000,
          mimetype: 'application/octet-stream',
          name: '/fonts/[name].[ext]',
          publicPath: '../assets',
        },
      },
    ],
  },
  resolve: {
    extensions: ['.js', '.sass', '.jsx'],
  },
  devServer: {
    port: 9000,
    host: '0.0.0.0',
    static: [
      {
        directory: './public/assets',
        publicPath: '/assets',
      },
      {
        directory: './public/locales',
        publicPath: '/locales',
      },
    ],
    proxy: {
      '/api': 'http://localhost:8888',
    },
    historyApiFallback: true,
    hot: true,
  },
  optimization: {
    moduleIds: 'named',
  },
  plugins: [
    new HtmlWebpackPlugin({
      template: path.resolve(__dirname, 'resources', 'views', 'book.blade.php'),
      filename: 'index.html',
    }),
    new webpack.ProvidePlugin({
      React: 'react',
      ReactDOM: 'react-dom',
      ReactBootstrap: 'react-bootstrap',
      axios: 'axios',
    }),
  ],
}];

module.exports = settings;
