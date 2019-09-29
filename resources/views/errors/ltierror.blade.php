<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body>
<div class="alert alert-danger">
<p>
Kunne ikke starte rolle og gruppeverktøyet.</p>
<p>For at det skal virke må din nettleser tillate 
informasjonskapsler fra tredjeparter fordi verktøyet vårt ligger på en egen server: https://kpas-lti.azurewebsites.net
</p>
<p>Kontakt din IT-avdeling eller les om hvordan du
<a class='alert-link' target='_blank' href='https://nettvett.no/slik-administrer-du-informasjonskapsler/'>
aktiverer informasjonskapsler fra tredjeparter.</a>
</p>
<p>
{{$message}}
</p>
</div>
</body>
</html>
