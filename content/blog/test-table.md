Title: Testeando Tablas
Description: Prueba de table tipo Excel.
Template: blank

----

{Styles minify=true}
#excel input {
    border: none;
    width: 100%;
    font-size: 14px;
    padding: 2px;
}

#excel input:hover {
    background-color: #eee;
}

#excel input:focus {
    background-color: #ccf;
}

#excel input:not(:focus) {
    text-align: right;
}

table#excel  {
    border-collapse: collapse;  
}

#excel td {
    border: 1px solid #999;
    padding: 0;
}

#excel tr:first-child td, td:first-child {
    background-color: #ccc;
    padding: 1px 3px;
    font-weight: bold;
    text-align: center;
}

{/Styles}



<div class="container">
	<div class="row mt-3">
		<div class="col-md-8 m-auto">
			<p><a href="{Site_url}/blog">&#x21A9; Volver al Blog</a></p>
			<p>Esta es una simple tabla que se puede usar como Excel prueba a introducir numberos en la primera linea y en D pon =A1+B1+C1+D1</p>
		</div>
	</div>
	<div class="row mt-3">
		<div class="col-md-8 m-auto">
			<table id="excel"></table>
		</div>
	</div>
</div>

{Scripts minify=true}
var rows = 10;
var columns = 6;

for (var i=0; i<rows; i++) {
    var row = document.querySelector("table").insertRow(-1);
    for (var j=0; j<columns; j++) {
        var letter = String.fromCharCode("A".charCodeAt(0)+j-1);
        row.insertCell(-1).innerHTML = i&&j ? "<input id='"+ letter+i +"'/>" : i||letter;
    }
}
console.log(columns);
var DATA={}, INPUTS=[].slice.call(document.querySelectorAll("input"));
INPUTS.forEach(function(elm) {
    elm.onfocus = function(e) {
        e.target.value = localStorage[e.target.id] || "";
    };
    elm.onblur = function(e) {
        localStorage[e.target.id] = e.target.value;
        computeAll();
    };
    var getter = function() {
        var value = localStorage[elm.id] || "";
        if (value.charAt(0) == "=") {
            with (DATA) return eval(value.substring(1));
        } else { return isNaN(parseFloat(value)) ? value : parseFloat(value); }
    };
    Object.defineProperty(DATA, elm.id, {get:getter});
    Object.defineProperty(DATA, elm.id.toLowerCase(), {get:getter});
});
(window.computeAll = function() {
    INPUTS.forEach(function(elm) { try { elm.value = DATA[elm.id]; } catch(e) {} });
})();
{/Scripts}