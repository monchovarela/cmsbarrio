Title: Css y Javascript
Description: Incluye el Css y el Javascript para crear paginas personalizadas.
Template: blank

----

{Styles minify=true}

:root{
    --bg-1: #1A237E;
    --bg-2: #303F9F;
    --bg-3: #3F51B5;
    --tx-1: #E8EAF6;
    --tx-2: #FDD835;
    --tx-3: #F9A825;
	--fs:large;
	--ff: sans-serif;
	--fw: 400;
	--mw: 960px;
	--mg: 1em;
}

@media (max-width:900px){
	:root{
		--bg-1: #222;
		--bg-2: #333;
		--bg-3: #555;
		--tx-1: #fff;
		--tx-2: #00BCD4;
		--tx-3: #80DEEA;
		--fs: large;
		--ff: Consolas,monospace;
		--fw: 400;
		--mw: 100%;
		--mg: 1em;
	}
}

html,body{
    position:relative;
    height:100%;
}
body {
    margin: 0;
    padding: 0;
    font-family: var(--ff);
    font-size: var(--fs);
    font-weight: var(--fw);
    background: var(--bg-3);
    color: var(--tx-1);
}

div#root {
	max-width: var(--mw);
	margin: auto;
	height: calc(100% - 10em);
	border-bottom: 3px solid var(--bg-1);
	box-shadow: 0px 10px 5px -5px var(--bg-2);
	border-radius: 5px;
	position: absolute;
	top: 3em;
	left: 0;
	right: 0;
	overflow: hidden;
}
#root input {
	display: block;
	margin: 0.1em;
	width: calc(100% - 2.3em);
	padding: 1em;
	font-family: var(--ff);
	font-size: var(--fs);
	font-weight: var(--fw);
	background: var(--bg-1);
	color: var(--tx-1);
	border: 1px solid var(--bg-1);
	transition: background 500ms ease;
}
#root input:hover, input:focus {
	background:var(--bg-2);
	color:var(--tx-2);
	border:1px solid var(--tx-2);
	transition: background 500ms ease;
}

#output{
	overflow-y: scroll;
	height: calc(100% - 3em);
	background: var(--bg-2);
	border: 3px solid var(--bg-1);
}

ul {
	padding: 0;
	margin: 0;
}
ul li {
	display: block;
    margin: .5em;
    width: calc(100% - 1em);
	list-style: none;
}
ul li:first-child {
	margin-top: 0.5em;
}
ul li a {
	display: block;
	text-decoration: none;
	padding: 1em;
	transition: background 500ms ease;
    background: var(--bg-3);
    color: var(--tx-1);
}
ul li a:hover, ul li a:focus {
    background: var(--bg-2);
    color: var(--tx-2);
  	transition: background 500ms ease;
}
@media (max-width:900px){
	:root{
		--bg-1: #222;
		--bg-2: #333;
		--bg-3: #555;
		--tx-1: #fff;
		--tx-2: #00BCD4;
		--tx-3: #80DEEA;
		--fs: large;
		--ff: Consolas,monospace;
		--fw: 400;
		--mw: 100%;
		--mg: 1em;
	}
	div#root,#output{
		position: relative;
		top: 0;
    	height: auto;
		overflow: auto;
	}
}
{/Styles}

<a href="{Url}" style="margin:1em;color:var(--txt-1)">&#x21A9; Volver a {Config name=title"}</a>
<div id="root" class="mt-2 mb-2">
  <input id="search" type="text" value="classless">
  <ul id="output"></ul>
</div>

{Scripts minify=true}
!(function () {
	let hits = [],
		url = "https://hn.algolia.com/api/v1/search?query=",
		query = "classless",
		search = document.querySelector("#search"),
		output = document.querySelector("#output");

	// fetchData
	const fetchData = async (query) => {
		const result = await fetch(url + query);
		const response = await result.json();
		return response;
	};
	// render output
	const renderData = (data) => {
		let html = "";
		data.map((item) => {
			if(item.title.length > 0 || typeof item.title !== null) {
				html += `<li id="${item.objectID}"><a target="_blank" href="${item.url}" title="${item.title}">${item.title}</a></li>`;
			}
		});
		output.innerHTML = html;
	};
	// get data
	fetchData(query).then((r) => renderData(r.hits));
	// on key up get data
	search.addEventListener("keyup", (evt) => {
		evt.preventDefault();
		let val = evt.target.value;
		if (val) fetchData(val).then((r) => renderData(r.hits));
	});
})();
{/Scripts}