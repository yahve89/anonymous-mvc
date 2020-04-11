const path = require('path');
const TerserJSPlugin = require('terser-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');

module.exports = {
  /* production */
  mode: 'production',

  /* development */
  // mode: 'development',
  devtool: 'none',     
  entry: {
      main: path.resolve(__dirname, './assets/js/main.js'), 
  },
  output: {
    path: path.resolve(__dirname, 'public/build'),
    filename: '[name].js'
  },
  optimization: {
    minimizer: [new TerserJSPlugin({}), new OptimizeCSSAssetsPlugin({})],
  },
  plugins: [new MiniCssExtractPlugin()],
  module: {
    rules: [
      {
        test: /\.css$/i,
        use: [MiniCssExtractPlugin.loader, 'css-loader'],
      }
    ]
  }
}