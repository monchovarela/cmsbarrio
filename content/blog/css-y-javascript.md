Title: Css y Javascript
Description: Incluye el Css y el Javascript para crear paginas personalizadas.
Template: post

----

{Style}

input {
	display: block;
	margin: 0.8em 0;
	width: 100%;
	padding: 1em;
	transition: background 500ms ease;
	color: var(--nc-tx-2);
	background: var(--nc-bg-3);
	border-color: var(--nc-tx-2)
}
input:hover, input:focus {
	transition: background 500ms ease;
}

ul {
	padding: 0;
	margin: 0;
}
ul li {
	display: block;
	width: 100%;
	margin: 0;
	margin-bottom: 0.5em;
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
	background: var(--nc-bg-2);
	color: var(--nc-tx-1);
}
ul li a:hover, ul li a:focus {
	color: var(--nc-lk-tx);
	background: var(--nc-lk-1);
  	transition: background 500ms ease;
}

#output{
	height:20rem;
	overflow-y:scroll;
}
{/Style}

<div id="root1">
  <input id="search" type="text" value="classless">
  <ul id="output"></ul>
</div>


{Script}

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
			html += `<li id="${item.objectID}"><a target="_blank" href="${item.url}" title="${item.title}">${item.title}</a></li>`;
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

{/Script}