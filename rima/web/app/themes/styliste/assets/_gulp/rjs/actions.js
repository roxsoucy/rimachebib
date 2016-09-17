//
//
// CUSTOM TOOLS FOR MANIPULATING DEPEDENCIES


var fs = require('fs'),
    bower = require('./bower.json'),
    requirements = require('../requireJS.json'),
    deps = {},
    paths = {},
    shims = {};


for(var key in requirements){
  req = requirements[key];
  // push bower dependencies according to requireJS.json file
  if(req.bower){
    deps[req.bower] = req.version ? req.version : "";
  }
  // push requirejs paths according to requireJS.json file
  if(req.path){
    paths[key] = req.path;
  }
  // push requirejs shims according to requireJS.json file
  if(req.shim){
    shims[key] = req.shim;
  }
}

// save new bower dependencies
console.log('Updating bower.json...');
bower.dependencies = deps;
fs.writeFile('./bower.json', JSON.stringify(bower, null, '  '));
console.log('Done.');

// save rjs paths
console.log('Writing new rjs-paths.json...');
fs.writeFile('./rjs-paths.json', JSON.stringify(paths, null, '  '));
console.log('Done.');


// save rjs shims
console.log('Writing new rjs-shims.json...');
fs.writeFile('./rjs-shims.json', JSON.stringify(shims, null, '  '));
console.log('Done.');
