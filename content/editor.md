Title: Editor
Description: prueba codigo.
Template:editor
----

#### Probando Token

    [Php]
        use Token\Token as Token;
        $token = Token::generate('test');
        $checkToken = Token::check($token,'test');
        if($checkToken){
            echo "El token es bueno 🐲 ".$token;
        }else{
            echo 'El token es malo 👽';
        }
    [/Php]


#### Probando la Api

[//]: # (Añadimos css minificado en el head.)
[Styles minify=true]
    .wait{
        opacity:1;
        animation: fade 500ms infinite ease-in-out;
    }
    @keyframes fade {
        from {opacity:0;}
    }
    pre{
        color: var(--bs-light);
        background: var(--bs-primary);
        border-color: var(--bs-primary);
    }
[/Styles]


[Php]
    $pages = File\File::scan(CONTENT.'/blog','md',false);
    $html = '<select id="test_api" class="form-control">';
    $html .= '<option> Seleciona el articulo </option>';
    foreach($pages as $filename){   
        // api=file&data=page&name=filename
        $name = str_replace('.md','',$filename);
        $html .= '<option value="'.$name.'">'.$name.'</option>';
    }
    $html .= '</select>';
    $html .= '<pre id="test_output"></pre>';
    echo $html;
[/Php]


[//]: # (Añadimos js minificado en el footer.)
[Scripts minify=true]
    const api = window.test_api;
    const out = window.test_output;
    const getData = async (url) => {
        const resp = await window.fetch(url);
        const result = await resp.json();
        return result;
    };
    api.addEventListener('change',evt => {
        let filename = evt.target.value;
        let url = `${site_url}/index.php?api=file&data=page&name=blog/${filename}`;
        out.textContent = '....';
        out.className = 'wait';
        if(filename) { 
            let w = setTimeout(() => {
                const data = getData(url);
                data.then(r => {
                    out.textContent = JSON.stringify(r,null,2);
                    out.className = '';
                });
                clearTimeout(w);
            },800);
        };
    });
[/Scripts]