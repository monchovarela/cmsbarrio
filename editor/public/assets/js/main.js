var maxWidth = 768;


Split(['#code', '#preview'], {

    minSize: [400, 400],

    elementStyle: (dimension, size, gutterSize) => ({
        'flex-basis': `calc(${size}% - ${gutterSize}px)`,
    }),
    gutterStyle: (dimension, gutterSize) => ({
        'flex-basis': `${gutterSize}px`,
    }),
})


var render = document.querySelector('#render'),
    save = document.querySelector('#save'),
    cancel = document.querySelector('#cancel'),
    content = document.querySelector('#editor-area'),
    templates = document.querySelector('#templates'),
    preview = document.querySelector('#preview > .container'),
    logo = site_url + '/public/assets/img/logo.png',
    renderCode = function() {
        if (window.innerWidth > maxWidth) {
            screenLog.init({
                bgColor: '#000',
                logColor: '#fff',
                css: 'top: 5em;',
            });
            screenLog.log('Vaciando');
            preview.innerHTML = '';
            preview.innerHTML = '<img class="loading" src="' + logo + '"/>';
            screenLog.log('Cargando');
            var wait = setTimeout(() => {
                fetch(site_url + '/preview', {
                    method: 'post',
                    body: JSON.stringify({
                        code: btoa(content.value)
                    })
                }).then(function(response) {
                    screenLog.log('Recibiendo respuesta');
                    return response.text();
                }).then(function(string) {
                    preview.innerHTML = atob(string);
                    clearTimeout(wait);
                    var wait2 = setTimeout(function() {
                        screenLog.destroy();
                        clearTimeout(wait2);
                    }, 1000);
                });
            }, 1000);
        }
    },

    loadDemo = function(name) {
        if (window.innerWidth > maxWidth) {
            fetch(site_url + '/load/' + name)
                .then(function(response) {
                    return response.text();
                }).then(function(string) {
                    content.value = string;
                });
        }
    },

    // fake prompt
    modal = function(label, type, value, isRequired, callback) {
        var overlay = document.createElement('div');
        overlay.className = 'custom-modal-overlay';
        var modal = document.createElement('div');
        modal.className = 'custom-modal custom-modal-prompt';

        var modal_tmpl = [
            '<div class="custom-modal-header">', label, '</div>',
            '<div class="custom-modal-content"></div>',
            '<div class="custom-modal-action"></div>'
        ].join(' ');

        modal.innerHTML = modal_tmpl;

        var onSuccess = function onSuccess(value) {
            modal.classList.add('custom-modal-hide');
            overlay.classList.add('custom-modal-hide');
            var w = setTimeout(function() {
                overlay.parentNode.removeChild(overlay);
                modal.parentNode.removeChild(modal);
                clearTimeout(w);
            }, 1000);
            if (typeof callback == "function") callback(value);
        };


        var buttonOK = document.createElement('button');
        buttonOK.className = 'btn btn-primary';
        buttonOK.innerHTML = 'Ok';

        var buttonCANCEL = document.createElement('button');
        buttonCANCEL.className = 'btn btn-danger';
        buttonCANCEL.innerHTML = 'Cancel';
        buttonCANCEL.addEventListener('click', function() {
            modal.classList.add('custom-modal-hide');
            overlay.classList.add('custom-modal-hide');
            var w = setTimeout(function() {
                overlay.parentNode.removeChild(overlay);
                modal.parentNode.removeChild(modal);
                clearTimeout(w);
            }, 1000);
        }, false);


        switch (type) {
            case 'confirm':
                modal.querySelector('.custom-modal-content').innerHTML = '<p>' + value + '</p>';
                document.body.appendChild(overlay);
                document.body.appendChild(modal);
                modal.children[2].appendChild(buttonOK);
                modal.children[2].appendChild(buttonCANCEL);
                buttonOK.addEventListener('click', function() {
                    onSuccess(true);
                }, false);
                break;
            case 'dialog':

                var input = document.createElement('input');
                input.className = 'form-control';
                input.type = 'text';

                document.body.appendChild(overlay);
                document.body.appendChild(modal);
                modal.children[1].appendChild(input);
                modal.children[2].appendChild(buttonOK);
                modal.children[2].appendChild(buttonCANCEL);
                input.focus();

                input.value = value;
                input.addEventListener('keyup', function(e) {
                    if (isRequired) {
                        if (e.keyCode == 13 && this.value !== "" && this.value !== value) {
                            onSuccess(this.value);
                        }
                    } else {
                        if (e.keyCode == 13) {
                            onSuccess(this.value == value ? "" : this.value);
                        }
                    }
                }, false);

                buttonOK.addEventListener('click', function() {
                    if (isRequired) {
                        if (input.value !== "" && input.value !== value) {
                            onSuccess(input.value);
                        }
                    } else {
                        onSuccess(input.value == value ? "" : input.value);
                    }
                }, false);

                break;

        }
    },

    saveData = (function() {
        var a = document.createElement("a");
        document.body.appendChild(a);
        a.style = "display: none";
        return function(data, fileName) {
            var header = ['Title: '+fileName.replace('.md',''),'\nTemplate: index\n','----\n'].join(''),
            content = header + data,
            blob = new Blob([content], {
                    type: "octet/stream"
                }),
                url = window.URL.createObjectURL(blob);
            a.href = url;
            a.download = fileName;
            a.click();
            window.URL.revokeObjectURL(url);
        };
    }());

save.addEventListener('click', function(e) {
    e.preventDefault();
    var data = content.value,
        fileName = "name-of-file.md";

    modal('Guardar como:', 'dialog', fileName, true, function(val) {
        if (val !== '') {
            saveData(data, val);
        }
    });

})

renderCode();
render.addEventListener('click', function(e) {
    e.preventDefault();
    renderCode();
});


templates.addEventListener('change', function(e) {
    e.preventDefault();
    if (e.target.value) {
        if (content.value !== '') {
            modal('Aviso:', 'confirm', 'Desea cargar la demo de ' + e.target.value, null, function(val) {
                if (val === true) {
                    loadDemo(e.target.value);
                    renderCode();
                    templates.selectedIndex = 0;
                }
            });
        }
    }
    return false;
});

content.addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.keyCode == 13) {
        renderCode();
    }
});