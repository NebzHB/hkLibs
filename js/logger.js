"use strict";

const LogType = {
  ERROR: {value: 0, txt: 'Error'},
  WARNING: {value: 1, txt: 'Warning'},
  INFO: {value: 2, txt: 'Info'},
  DEBUG: {value: 3, txt: 'Debug'},
};

let instance = null;

class Logger {
  constructor() {
    this._logLevel = LogType.ERROR;
  }

  setLogLevel(level) {
    this._logLevel = level;
  }

  log(message, level = LogType.INFO) {
    if (level.value > this._logLevel.value) { return; }
    const logString = `[${(new CustomDate()).toString()}][${level.txt.toUpperCase()}] : ${message}`;
    console.log(logString);
  }
}

class CustomDate extends Date {
  constructor() {
    super();
  }

  toString() {
    return this.toLocaleString('fr-FR', {
		year: 'numeric', 
		month: '2-digit', 
		day: '2-digit',
		hour: '2-digit', 
		minute: '2-digit', 
		second: '2-digit',
		hour12: false,
    }).replace(/\//g, '-');
  }
}

exports.LogType = LogType;
exports.getInstance = function() {
  if (!instance) {
    instance = new Logger();
  }
  return instance;
};
