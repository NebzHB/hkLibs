"use strict";
const os = require('os');

const ifaces = os.networkInterfaces();
const ipList = Object.values(ifaces).flat().map((iface) => iface.address);

exports.ipList = ipList;
