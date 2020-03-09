(function () {

    var logContainer,
        logHeader,
        logEl,
        isInitialized = false,
        _console = {}; // backup console obj to contain references of overridden fns.
        _options = {
            bgColor: '#212529',
            logColor: 'lightgreen',
            infoColor: '#00BCD4',
            warnColor: 'orange',
            errorColor: 'red',
            freeConsole: false,
            styleHeader: 'padding: 0.5em 1em;width:100%;text-transform: uppercase;background: #1d2124;color: #28a745;',
            styleContainer: 'z-index: 2147483647;overflow: hidden;position: absolute;bottom: 0em;right: 0em;width: calc(100%/2 - 1.5em);margin: 0.5em;min-height: 50px;padding: 0;border: 2px solid #212529;color: #f0f8ff;line-height: 1.5;font-size: 12px;font-family: monospace;',
            styleLog: 'padding: 1em;width: 100%;max-height: 30em;overflow:auto;',
            css: '',
            autoScroll: false
        };

    var consoleStyle = [_options.styleContainer,'background:',_options.bgColor,';','border-color:',_options.bgColor,';',_options.css].join('');

    function createElement(tag, css) {
        var element = document.createElement(tag);
        element.style.cssText = css;
        return element;
    }

    function createPanel() {
        var div = createElement('div', consoleStyle);
        return div;
    }

    function genericLogger(color) {
        return function() {
            var el = createElement('div', 'min-height:18px;background:' +
                (logEl.children.length % 2 ? '#333' : '') + ';border-boton:2px solid black;0.2em;color:' + color); // zebra lines


            var val = [].slice.call(arguments).reduce(function(prev, arg) {
                return prev + ' ' + (typeof arg === "object" ? JSON.stringify(arg) : arg);
            }, '');
            el.textContent = val;
            logEl.appendChild(el);
            // Scroll to last element, if autoScroll option is set.
            if(_options.autoScroll) {
                logEl.scrollTop = logEl.scrollHeight - logEl.clientHeight;
            }
        };
    }

    function clear() {
        logEl.innerHTML = '';
    }

    function log() {
        return genericLogger(_options.logColor).apply(null, arguments);
    }

    function info() {
        return genericLogger(_options.infoColor).apply(null, arguments);
    }

    function warn() {
        return genericLogger(_options.warnColor).apply(null, arguments);
    }

    function error() {
        return genericLogger(_options.errorColor).apply(null, arguments);
    }

    function setOptions(options) {
        for(var i in options)
            if(options.hasOwnProperty(i) && _options.hasOwnProperty(i)) {
                _options[i] = options[i];
            }
    }

    function init(options) {
        if (isInitialized) { return; }

        isInitialized = true;

        if(options) {
            setOptions(options);
        }

        logContainer = createPanel();
        logHeader = createElement('div',_options.styleHeader);
        logHeader.textContent = 'Info Consola:';
        logEl = createElement('div',_options.styleLog);

        logContainer.appendChild(logHeader);
        logContainer.appendChild(logEl);
        document.body.appendChild(logContainer);


        if (!_options.freeConsole) {
            // Backup actual fns to keep it working together
            _console.log = console.log;
            _console.clear = console.clear;
            _console.info = console.info;
            _console.warn = console.warn;
            _console.error = console.error;
            console.log = originalFnCallDecorator(log, 'log');
            console.clear = originalFnCallDecorator(clear, 'clear');
            console.info = originalFnCallDecorator(info, 'info');
            console.warn = originalFnCallDecorator(warn, 'warn');
            console.error = originalFnCallDecorator(error, 'error');
        }
    }

    function destroy() {
        isInitialized = false;
        console.log = _console.log;
        console.clear = _console.clear;
        console.info = _console.info;
        console.warn = _console.warn;
        console.error = _console.error;
        logContainer.remove();
    }

    /**
     * Checking if isInitialized is set
     */
    function checkInitialized() {
        if (!isInitialized) {
            throw 'You need to call `screenLog.init()` first.';
        }
    }

    /**
     * Decorator for checking if isInitialized is set
     * @param  {Function} fn Fn to decorate
     * @return {Function}      Decorated fn.
     */
    function checkInitDecorator(fn) {
        return function() {
            checkInitialized();
            return fn.apply(this, arguments);
        };
    }

    /**
     * Decorator for calling the original console's fn at the end of
     * our overridden fn definitions.
     * @param  {Function} fn Fn to decorate
     * @param  {string} fn Name of original function
     * @return {Function}      Decorated fn.
     */
    function originalFnCallDecorator(fn, fnName) {
        return function() {
            fn.apply(this, arguments);
            if (typeof _console[fnName] === 'function') {
                _console[fnName].apply(console, arguments);
            }
        };
    }

    // Public API
    window.screenLog = {
        init: init,
        log: originalFnCallDecorator(checkInitDecorator(log), 'log'),
        clear: originalFnCallDecorator(checkInitDecorator(clear), 'clear'),
        info: originalFnCallDecorator(checkInitDecorator(clear), 'info'),
        warn: originalFnCallDecorator(checkInitDecorator(warn), 'warn'),
        error: originalFnCallDecorator(checkInitDecorator(error), 'error'),
        destroy: checkInitDecorator(destroy)
    };
})();