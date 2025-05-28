<!DOCTYPE html>
<html lang="no">

<head>
	<title>LTI Error</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<style>
		@font-face {
			font-family: "Inter";
			font-style: normal;
			src: url("/fonts/Inter/Inter-VariableFont_opsz,wght.ttf") format("truetype");
			font-weight: 100 900;
			font-display: swap;
		}

		body {
			font-family: "Inter", sans-serif;
		}

		p {
			font-size: 14px;
			line-height: 20px;
			margin: 8px;
		}

		.alert {
			padding: 10px;
			border-radius: 4px;
			background-color: rgb(242 222 222);
			color: #721c24;
			border: 2px solid #f5c6cb;
		}

		.alert a {
			color: #721c24;
		}

		.alert-link {
			font-weight: 700;
			text-decoration: none;
		}

		.alert a:hover {
			text-decoration: underline;
		}
	</style>
</head>

<body>
	<div class="alert alert-danger">
		<p>
			Kunne ikke starte rolle og gruppeverktøyet.
		</p>
		<p>For at det skal virke må din nettleser tillate
			informasjonskapsler fra tredjepsarter fordi verktøyet vårt ligger på en egen server: https://kpas-lti.azurewebsites.net
		</p>
		<p>Kontakt din IT-avdeling eller les om hvordan du
			<a class='alert-link' target='_blank' href='https://nettvett.no/slik-administrer-du-informasjonskapsler/'>
				aktiverer informasjonskapsler fra tredjeparter.
			</a>
		</p>
		<p>
			{{$message}}
		</p>
	</div>
</body>

</html>