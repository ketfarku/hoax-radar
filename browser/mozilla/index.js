var self = require("sdk/self");
var tabs = require("sdk/tabs");

tabs.on('ready', function(tab) {
  tab.attach({
    contentScriptFile: './content.js'
  });
});
