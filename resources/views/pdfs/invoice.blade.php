<!DOCTYPE html>
<html>

<head>
	<title>Recibo</title>
	<meta charset="utf-8" />
	<meta name="description" content="Ejemplo práctico de aplicación del posicionamiento fijo" />
	<meta name="author" content="francesc ricart" />
</head>

<body>
	<style>
		html {
			font-family: sans-serif;
			-ms-text-size-adjust: 100%;
			-webkit-text-size-adjust: 100%
		}

		body {
			margin: 0
		}

		article,
		aside,
		details,
		figcaption,
		figure,
		footer,
		header,
		hgroup,
		main,
		menu,
		nav,
		section,
		summary {
			display: block
		}

		audio,
		canvas,
		progress,
		video {
			display: inline-block;
			vertical-align: baseline
		}

		audio:not([controls]) {
			display: none;
			height: 0
		}

		[hidden],
		template {
			display: none
		}

		a {
			background-color: transparent
		}

		a:active,
		a:hover {
			outline: 0
		}

		abbr[title] {
			border-bottom: 1px dotted
		}

		b,
		strong {
			font-weight: bold
		}

		dfn {
			font-style: italic
		}

		h1 {
			font-size: 2em;
			margin: 0.67em 0
		}

		mark {
			background: #ff0;
			color: #000
		}

		small {
			font-size: 80%
		}

		sub,
		sup {
			font-size: 75%;
			line-height: 0;
			position: relative;
			vertical-align: baseline
		}

		sup {
			top: -0.5em
		}

		sub {
			bottom: -0.25em
		}

		img {
			border: 0
		}

		svg:not(:root) {
			overflow: hidden
		}

		figure {
			margin: 1em 40px
		}

		hr {
			-moz-box-sizing: content-box;
			box-sizing: content-box;
			height: 0
		}

		pre {
			overflow: auto
		}

		code,
		kbd,
		pre,
		samp {
			font-family: monospace, monospace;
			font-size: 1em
		}

		button,
		input,
		optgroup,
		select,
		textarea {
			color: inherit;
			font: inherit;
			margin: 0
		}

		button {
			overflow: visible
		}

		button,
		select {
			text-transform: none
		}

		button,
		html input[type="button"],
		input[type="reset"],
		input[type="submit"] {
			-webkit-appearance: button;
			cursor: pointer
		}

		button[disabled],
		html input[disabled] {
			cursor: default
		}

		button::-moz-focus-inner,
		input::-moz-focus-inner {
			border: 0;
			padding: 0
		}

		input {
			line-height: normal
		}

		input[type="checkbox"],
		input[type="radio"] {
			-moz-box-sizing: border-box;
			box-sizing: border-box;
			padding: 0
		}

		input[type="number"]::-webkit-inner-spin-button,
		input[type="number"]::-webkit-outer-spin-button {
			height: auto
		}

		input[type="search"] {
			-webkit-appearance: textfield;
			-moz-box-sizing: content-box;
			box-sizing: content-box
		}

		input[type="search"]::-webkit-search-cancel-button,
		input[type="search"]::-webkit-search-decoration {
			-webkit-appearance: none
		}

		fieldset {
			border: 1px solid #c0c0c0;
			margin: 0 2px;
			padding: 0.35em 0.625em 0.75em
		}

		legend {
			border: 0;
			padding: 0
		}

		textarea {
			overflow: auto
		}

		optgroup {
			font-weight: bold
		}

		table {
			border-collapse: collapse;
			border-spacing: 0
		}

		td,
		th {
			padding: 0
		}

		html {
			font-size: 12px;
			line-height: 1.5;
			color: #000;
			background: #ddd;
			-moz-box-sizing: border-box;
			box-sizing: border-box
		}

		body {
			font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
			/* margin: 6rem auto 0; */
			max-width: 800px;
			background: white;
			border: 1px solid #aaa;
			padding: 2rem
		}

		.container {
			max-width: 800px;
			margin-left: auto;
			margin-right: auto;
			padding-left: 1rem;
			padding-right: 1rem
		}

		*,
		*:before,
		*:after {
			-moz-box-sizing: inherit;
			box-sizing: inherit
		}

		[contenteditable]:hover,
		[contenteditable]:focus,
		input:hover,
		input:focus {
			background: rgba(241, 76, 76, 0.1);
			outline: 1px solid #2497e4
		}

		.group:after,
		.row:after,
		.invoicelist-footer:after {
			content: "";
			display: table;
			clear: both
		}

		a {
			color: #2497e4;
			text-decoration: none
		}

		p {
			margin: 0
		}

		.row {
			position: relative;
			display: block;
			width: 100%;
			font-size: 0
		}

		.col,
		.logoholder,
		.me,
		.info,
		.bank,
		[class*="col-"] {
			vertical-align: top;
			display: inline-block;
			font-size: 1rem;
			padding: 0 1rem;
			min-height: 1px
		}

		.col-4 {
			width: 25%
		}

		.col-3 {
			width: 33.33%
		}

		.col-2 {
			width: 50%
		}

		.col-2-4 {
			width: 75%
		}

		.col-1 {
			width: 100%
		}

		.text-center {
			text-align: center
		}

		.text-right {
			text-align: right
		}

		.control-bar {
			position: fixed;
			top: 0;
			left: 0;
			right: 0;
			z-index: 100;
			background: #2497e4;
			color: white;
			line-height: 4rem;
			height: 4rem
		}

		.control-bar .slogan {
			font-weight: bold;
			font-size: 1.2rem;
			display: inline-block;
			margin-right: 2rem
		}

		.control-bar label {
			margin-right: 1rem
		}

		.control-bar a {
			margin: 0;
			padding: .5em 1em;
			background: rgba(255, 255, 255, 0.8)
		}

		.control-bar a:hover {
			background: #fff
		}

		.control-bar input {
			border: none;
			background: rgba(255, 255, 255, 0.2);
			padding: .5rem 0;
			max-width: 30px;
			text-align: center
		}

		.control-bar input:hover {
			background: rgba(255, 255, 255, 0.3)
		}

		.hidetax .taxrelated {
			display: none
		}

		.showtax .notaxrelated {
			display: none
		}

		.hidedate .daterelated {
			display: none
		}

		.showdate .notdaterelated {
			display: none
		}

		header {
			margin: 1rem 0 0;
			padding: 0 0 2rem 0;
			border-bottom: 3pt solid #2497e4
		}

		header p {
			font-size: .9rem
		}

		header a {
			color: #000
		}

		.logo {
			margin: 0 auto;
			width: auto;
			height: auto;
			border: none;
			fill: #2497e4
		}

		.logoholder {
			width: 14%
		}

		.me {
			width: 30%
		}

		.info {
			width: 30%
		}

		.bank {
			width: 26%
		}

		.section {
			margin: 2rem 0 0
		}

		.smallme {
			display: inline-block;
			text-transform: uppercase;
			margin: 0 0 2rem 0;
			font-size: .9rem
		}

		.client {
			margin: 0 0 3rem 0
		}

		h1 {
			margin: 0;
			padding: 0;
			font-size: 2rem;
			color: #2497e4
		}

		.details input {
			display: inline;
			margin: 0 0 0 .5rem;
			border: none;
			width: 50px;
			min-width: 0;
			background: transparent;
			text-align: left
		}


		.invoice_detail {
			border: solid 1px #2497e4;
			padding: 10px;
			height: 25px;
			text-align: center
		}

		.invoicelist-body {
			margin: 1rem
		}

		.invoicelist-body table {
			width: 100%
		}

		.invoicelist-body thead {
			text-align: left;
			border-bottom: 1pt solid #666
		}

		.invoicelist-body td,
		.invoicelist-body th {
			position: relative;
			padding: 1rem
		}

		.invoicelist-body tr:nth-child(even) {
			background: #ccc
		}

		.invoicelist-body tr:hover .removeRow {
			display: block
		}

		.invoicelist-body input {
			display: inline;
			margin: 0;
			border: none;
			width: 80%;
			min-width: 0;
			background: transparent;
			text-align: left
		}

		.invoicelist-body .control {
			display: inline-block;
			color: white;
			background: #2497e4;
			padding: 3px 7px;
			font-size: .9rem;
			text-transform: uppercase;
			cursor: pointer
		}

		.invoicelist-body .control:hover {
			background: #f36464
		}

		.invoicelist-body .newRow {
			margin: .5rem 0;
			float: left
		}

		.invoicelist-body .removeRow {
			display: none;
			position: absolute;
			top: .1rem;
			bottom: .1rem;
			left: -1.3rem;
			font-size: .7rem;
			border-radius: 3px 0 0 3px;
			padding: .5rem
		}

		.invoicelist-footer {
			margin: 1rem
		}

		.invoicelist-footer table {
			float: right;
			width: 25%
		}

		.invoicelist-footer table td {
			padding: 1rem 2rem 0 1rem;
			text-align: right
		}

		.invoicelist-footer table tr:nth-child(2) td {
			padding-top: 0
		}

		.invoicelist-footer table #total_price {
			font-size: 1rem;
			color: #2497e4
		}

		.note {
			margin: 1rem
		}

		.hidenote .note {
			display: none
		}

		.note h2 {
			margin: 0;
			font-size: 1rem;
			font-weight: bold
		}

		footer {
			display: block;
			margin: 1rem 0;
			padding: 1rem 0 0
		}

		footer p {
			font-size: .8rem
		}

		@media print {
			html {
				margin: 0;
				padding: 0;
				background: #fff
			}

			body {
				width: 100%;
				border: none;
				background: #fff;
				color: #000;
				margin: 0;
				padding: 0
			}

			.control,
			.control-bar {
				display: none !important
			}

			[contenteditable]:hover,
			[contenteditable]:focus {
				outline: none
			}
		}
	</style>


	<div class=" col-md-7 ml-4 ">

		<img class="text-center mb-3 " style="display: block;" src="{{public_path('imgs/logo.png')}}" alt="">
		<span class=" text-left" style="font-size: 0.7em; font-weight: 700; display: block;">HYM</span>
		<span class=" text-left" style="font-size: 0.7em; font-weight: 700; display: block;">NIT
			123456789</span>
		<span class=" text-left" style="font-size: 0.7em; font-weight: 700; display: block;">
			Calle sur </span>
		<span class=" text-left" style="font-size: 0.7em; font-weight: 700; display: block;">Tel
			000000</span>
		<span class=" text-left" style="font-size: 0.7em; font-weight: 700; display: block;">Cel
			3172603125</span>
	</div>

	<div class="col-md-3 ">
		<span class="text-center" style="font-size: 1em; font-weight: 700; display: block;">Recibo de Cambio
		</span>
		<span class="text-center" style="font-size: 0.7em; font-weight: bolder; display: block;">Cam00235</span>

		<span class="text-center" style="font-size: 0.7em; font-weight: bolder; display: block;">Fecha:
			2020/12/01</span>
	</div>

	<br>

	<h3>Información de vendedor</h3>
	<table class="mb-2 mt-4 card py-3" style="width:100%">
		<thead>
			<tr>
				<th class="custom-thead">Identificacion</th>
				<th class="custom-thead">Nombre</th>
				<th class="custom-thead">Contacto</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="text-center"><span style="font-size: 0.8em;">1096216530</span></td>
				<td class="text-center"><span style="font-size: 0.8em;">Danilo Grisalez</span></td>
				<td class="text-center"><span style="font-size: 0.8em;">31725601234</span></td>
			</tr>
		</tbody>
	</table>

	<br>
	<br>
	<br>
	<br>

	<table class="mb-2 mt-4 card py-3" style="width:100%">
		<thead>
			<tr>
				<th class="custom-thead">Cod</th>
				<th class="custom-thead">Cliente</th>
				<th class="custom-thead">Documento</th>
				<th class="custom-thead">Forma de pago</th>
				<th class="custom-thead">Tipo</th>
				<!-- <th class="custom-thead">Tasa</th> -->
				<!-- <th class="custom-thead">Valor cambiado</th> -->
				<!-- <th class="custom-thead">Valor en pesos</th> -->
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="text-center">{{$cambio->Codigo}}</td>
				<td class="text-center">{{ ($cambio['tercero'] != '') ? $cambio['tercero']['Nombre']  : 'Anonimo'}}</td>
				<td class="text-center">{{ ($cambio['tercero'] != '') ? $cambio['tercero']['Id_Tercero']  : '0000000'}}</td>
				<td class="text-center">{{$cambio->fomapago_id }}</td>
				<td class="text-center">{{$cambio->Tipo }}</td>
				<!-- <td class="text-center">{{$cambio->Tasa }}</td> -->
				<!-- <td class="text-center">{{$cambio->Valor_Destino}}</td> -->
				<!-- <td class="text-center">{{$cambio->Valor_Origen }}</td> -->
			</tr>
		</tbody>
	</table>


	<br>
	<br>
	<br>
	<br>


	<div class="invoicelist-footer">

		<table style="width: 50%;">
			<tr class="taxrelated">
				<td>Recibido: </td>
				<td>{{$cambio->Valor_Origen}} </td>
			</tr>
			<tr class="taxrelated">
				<td>Tasa: </td>
				<td>{{$cambio->Tasa}}</td>
			</tr>
			<tr>
				<td><strong>Cambiado:</strong></td>
				<td id="total_price" style="font-weight: bolder; font-size: 1em;">
					{{$cambio->Valor_Destino}}
				</td>
			</tr>
		</table>

	</div>

</body>

</html>