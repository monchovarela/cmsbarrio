Title: Test extensions
Description: Prueba de extensiones y metodos.
Template: post

----

#### Probando Css

    :root {
        --nc-tx-1: #E8EAF6;
        --nc-tx-2: #C5CAE9;
        --nc-bg-1: #1A237E;
        --nc-bg-2: #3F51B5;
        --nc-bg-3: #3F51B5;
        --nc-lk-1: #FFEB3B;
        --nc-lk-2: #F57F17;
        --nc-lk-tx: #3F51B5;
        --nc-ac-1: #009688;
        --nc-ac-tx: #E0F2F1;
    }

#### Probando Token

    {Php}
        $token = Barrio::run()->generateToken('test');
        $checkToken = Barrio::run()->checkToken($token,'test');
        if($checkToken){
            echo "El token es bueno 🐲\n\t".$token;
        }else{
            echo 'El token es malo 👽';
        }   
    {/Php}


#### Probando la Api



[//]: # (Shortcode css add style on Head.)
{Styles minify=true}
    :root {
        --nc-tx-1: #E8EAF6;
        --nc-tx-2: #C5CAE9;
        --nc-bg-1: #1A237E;
        --nc-bg-2: #3F51B5;
        --nc-bg-3: #3F51B5;
        --nc-lk-1: #FFEB3B;
        --nc-lk-2: #F57F17;
        --nc-lk-tx: #3F51B5;
        --nc-ac-1: #009688;
        --nc-ac-tx: #E0F2F1;
    }
    .wait{
        opacity:1;
        animation: fade 500ms infinite ease-in-out;
    }
    @keyframes fade {
        from {opacity:0;}
    }

    pre{color: var(--nc-lk-1);}

{/Styles}


[//]: # (You can add Php code on markdown.)
{Php}
    $pages = Barrio::scanFiles(CONTENT.'/blog','md',false);
    $html = '<select id="test_api">';
    $html .= '<option> Seleciona el articulo </option>';
    foreach($pages as $filename){   
        // api=file&data=page&name=filename
        $name = str_replace('.md','',$filename);
        $html .= '<option value="'.$name.'">'.$name.'</option>';
    }
    $html .= '</select>';
    $html .= '<pre id="test_output"></pre>';
    echo $html;
{/Php}

[//]: # (Add Javascript, this render on footer.)
{Scripts minify=true}
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
{/Scripts}