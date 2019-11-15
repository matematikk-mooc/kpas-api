<!DOCTYPE html>
<html>
<head>
<meta http-equiv="refresh" content="600"/>
</head>
<body>
<center>
<h1>Kompetansepakke fagfornyelsen</h1>
<i><font color="red">Statistikk - beta</font></i>
<h2>Antall brukere </h2>
<p style="font-size:72px">
{{ $antallBrukere }}
</p>
<h2>Antall grupper</h2>
<table>
    <tr><th>Gruppekategori</th><th>Antall grupper</th></tr>
@foreach ($groups as $key => $value)
    <tr><td>{{ $key }}</td><td> {{ $value }}</td></th>
@endforeach
</table>
<p>
Antall grupper viser hvor mange grupper det er i hver gruppekategori.
</p>
<p>
    <i><font color="red">Dette er en side under utvikling... vi jobber med det grafiske designet...</font></i>
</p>

</center>
</body>
</html>