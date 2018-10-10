//app.js
import config from './etc/config';
const wxCommon = require('./utils/common.js');
App({
  onLaunch: function () {
  },
  globalData: {
    userInfo: null
  },
  config: config,
  httpPost: wxCommon.postRequest
})