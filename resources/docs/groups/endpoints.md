# Endpoints


## _debugbar/open




> Example request:

```bash
curl -X GET \
    -G "https://template.test/_debugbar/open" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/_debugbar/open"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (404):

```json
{
    "message": ""
}
```
<div id="execution-results-GET_debugbar-open" hidden>
    <blockquote>Received response<span id="execution-response-status-GET_debugbar-open"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GET_debugbar-open"></code></pre>
</div>
<div id="execution-error-GET_debugbar-open" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GET_debugbar-open"></code></pre>
</div>
<form id="form-GET_debugbar-open" data-method="GET" data-path="_debugbar/open" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GET_debugbar-open', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GET_debugbar-open" onclick="tryItOut('GET_debugbar-open');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GET_debugbar-open" onclick="cancelTryOut('GET_debugbar-open');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GET_debugbar-open" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>_debugbar/open</code></b>
</p>
</form>


## Return Clockwork output




> Example request:

```bash
curl -X GET \
    -G "https://template.test/_debugbar/clockwork/est" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/_debugbar/clockwork/est"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (404):

```json
{
    "message": ""
}
```
<div id="execution-results-GET_debugbar-clockwork--id-" hidden>
    <blockquote>Received response<span id="execution-response-status-GET_debugbar-clockwork--id-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GET_debugbar-clockwork--id-"></code></pre>
</div>
<div id="execution-error-GET_debugbar-clockwork--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GET_debugbar-clockwork--id-"></code></pre>
</div>
<form id="form-GET_debugbar-clockwork--id-" data-method="GET" data-path="_debugbar/clockwork/{id}" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GET_debugbar-clockwork--id-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GET_debugbar-clockwork--id-" onclick="tryItOut('GET_debugbar-clockwork--id-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GET_debugbar-clockwork--id-" onclick="cancelTryOut('GET_debugbar-clockwork--id-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GET_debugbar-clockwork--id-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>_debugbar/clockwork/{id}</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="id" data-endpoint="GET_debugbar-clockwork--id-" data-component="url" required  hidden>
<br>

</p>
</form>


## Return the stylesheets for the Debugbar




> Example request:

```bash
curl -X GET \
    -G "https://template.test/_debugbar/assets/stylesheets" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/_debugbar/assets/stylesheets"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (404):

```json
{
    "message": ""
}
```
<div id="execution-results-GET_debugbar-assets-stylesheets" hidden>
    <blockquote>Received response<span id="execution-response-status-GET_debugbar-assets-stylesheets"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GET_debugbar-assets-stylesheets"></code></pre>
</div>
<div id="execution-error-GET_debugbar-assets-stylesheets" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GET_debugbar-assets-stylesheets"></code></pre>
</div>
<form id="form-GET_debugbar-assets-stylesheets" data-method="GET" data-path="_debugbar/assets/stylesheets" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GET_debugbar-assets-stylesheets', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GET_debugbar-assets-stylesheets" onclick="tryItOut('GET_debugbar-assets-stylesheets');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GET_debugbar-assets-stylesheets" onclick="cancelTryOut('GET_debugbar-assets-stylesheets');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GET_debugbar-assets-stylesheets" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>_debugbar/assets/stylesheets</code></b>
</p>
</form>


## Return the javascript for the Debugbar




> Example request:

```bash
curl -X GET \
    -G "https://template.test/_debugbar/assets/javascript" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/_debugbar/assets/javascript"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (404):

```json
{
    "message": ""
}
```
<div id="execution-results-GET_debugbar-assets-javascript" hidden>
    <blockquote>Received response<span id="execution-response-status-GET_debugbar-assets-javascript"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GET_debugbar-assets-javascript"></code></pre>
</div>
<div id="execution-error-GET_debugbar-assets-javascript" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GET_debugbar-assets-javascript"></code></pre>
</div>
<form id="form-GET_debugbar-assets-javascript" data-method="GET" data-path="_debugbar/assets/javascript" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GET_debugbar-assets-javascript', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GET_debugbar-assets-javascript" onclick="tryItOut('GET_debugbar-assets-javascript');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GET_debugbar-assets-javascript" onclick="cancelTryOut('GET_debugbar-assets-javascript');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GET_debugbar-assets-javascript" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>_debugbar/assets/javascript</code></b>
</p>
</form>


## Forget a cache key




> Example request:

```bash
curl -X DELETE \
    "https://template.test/_debugbar/cache/omnis/nisi" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/_debugbar/cache/omnis/nisi"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response => response.json());
```


<div id="execution-results-DELETE_debugbar-cache--key---tags--" hidden>
    <blockquote>Received response<span id="execution-response-status-DELETE_debugbar-cache--key---tags--"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-DELETE_debugbar-cache--key---tags--"></code></pre>
</div>
<div id="execution-error-DELETE_debugbar-cache--key---tags--" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETE_debugbar-cache--key---tags--"></code></pre>
</div>
<form id="form-DELETE_debugbar-cache--key---tags--" data-method="DELETE" data-path="_debugbar/cache/{key}/{tags?}" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('DELETE_debugbar-cache--key---tags--', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETE_debugbar-cache--key---tags--" onclick="tryItOut('DELETE_debugbar-cache--key---tags--');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETE_debugbar-cache--key---tags--" onclick="cancelTryOut('DELETE_debugbar-cache--key---tags--');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETE_debugbar-cache--key---tags--" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-red">DELETE</small>
 <b><code>_debugbar/cache/{key}/{tags?}</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>key</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="key" data-endpoint="DELETE_debugbar-cache--key---tags--" data-component="url" required  hidden>
<br>

</p>
<p>
<b><code>tags</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="tags" data-endpoint="DELETE_debugbar-cache--key---tags--" data-component="url"  hidden>
<br>

</p>
</form>


## api/institution




> Example request:

```bash
curl -X POST \
    "https://template.test/api/institution" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/api/institution"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response => response.json());
```


<div id="execution-results-POSTapi-institution" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-institution"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-institution"></code></pre>
</div>
<div id="execution-error-POSTapi-institution" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-institution"></code></pre>
</div>
<form id="form-POSTapi-institution" data-method="POST" data-path="api/institution" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-institution', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-institution" onclick="tryItOut('POSTapi-institution');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-institution" onclick="cancelTryOut('POSTapi-institution');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-institution" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/institution</code></b>
</p>
</form>


## api/settings




> Example request:

```bash
curl -X GET \
    -G "https://template.test/api/settings" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/api/settings"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (403):

```json

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
Invalid or missing lti_message_type parameter.
</p>
</div>
</body>
</html>

```
<div id="execution-results-GETapi-settings" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-settings"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-settings"></code></pre>
</div>
<div id="execution-error-GETapi-settings" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-settings"></code></pre>
</div>
<form id="form-GETapi-settings" data-method="GET" data-path="api/settings" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-settings', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-settings" onclick="tryItOut('GETapi-settings');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-settings" onclick="cancelTryOut('GETapi-settings');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-settings" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/settings</code></b>
</p>
</form>


## api/diploma/pdf




> Example request:

```bash
curl -X GET \
    -G "https://template.test/api/diploma/pdf" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/api/diploma/pdf"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (403):

```json

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
Invalid or missing lti_message_type parameter.
</p>
</div>
</body>
</html>

```
<div id="execution-results-GETapi-diploma-pdf" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-diploma-pdf"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-diploma-pdf"></code></pre>
</div>
<div id="execution-error-GETapi-diploma-pdf" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-diploma-pdf"></code></pre>
</div>
<form id="form-GETapi-diploma-pdf" data-method="GET" data-path="api/diploma/pdf" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-diploma-pdf', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-diploma-pdf" onclick="tryItOut('GETapi-diploma-pdf');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-diploma-pdf" onclick="cancelTryOut('GETapi-diploma-pdf');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-diploma-pdf" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/diploma/pdf</code></b>
</p>
</form>


## api/diploma/logolist




> Example request:

```bash
curl -X GET \
    -G "https://template.test/api/diploma/logolist" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/api/diploma/logolist"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (500):

```json
{
    "message": "Server Error"
}
```
<div id="execution-results-GETapi-diploma-logolist" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-diploma-logolist"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-diploma-logolist"></code></pre>
</div>
<div id="execution-error-GETapi-diploma-logolist" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-diploma-logolist"></code></pre>
</div>
<form id="form-GETapi-diploma-logolist" data-method="GET" data-path="api/diploma/logolist" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-diploma-logolist', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-diploma-logolist" onclick="tryItOut('GETapi-diploma-logolist');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-diploma-logolist" onclick="cancelTryOut('GETapi-diploma-logolist');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-diploma-logolist" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/diploma/logolist</code></b>
</p>
</form>


## api/run_scheduler




> Example request:

```bash
curl -X POST \
    "https://template.test/api/run_scheduler" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/api/run_scheduler"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response => response.json());
```


<div id="execution-results-POSTapi-run_scheduler" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-run_scheduler"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-run_scheduler"></code></pre>
</div>
<div id="execution-error-POSTapi-run_scheduler" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-run_scheduler"></code></pre>
</div>
<form id="form-POSTapi-run_scheduler" data-method="POST" data-path="api/run_scheduler" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-run_scheduler', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-run_scheduler" onclick="tryItOut('POSTapi-run_scheduler');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-run_scheduler" onclick="cancelTryOut('POSTapi-run_scheduler');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-run_scheduler" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/run_scheduler</code></b>
</p>
</form>


## api/nsr/counties




> Example request:

```bash
curl -X GET \
    -G "https://template.test/api/nsr/counties" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/api/nsr/counties"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "status": 200,
    "status_message": "Success",
    "result": []
}
```
<div id="execution-results-GETapi-nsr-counties" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-nsr-counties"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-nsr-counties"></code></pre>
</div>
<div id="execution-error-GETapi-nsr-counties" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-nsr-counties"></code></pre>
</div>
<form id="form-GETapi-nsr-counties" data-method="GET" data-path="api/nsr/counties" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-nsr-counties', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-nsr-counties" onclick="tryItOut('GETapi-nsr-counties');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-nsr-counties" onclick="cancelTryOut('GETapi-nsr-counties');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-nsr-counties" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/nsr/counties</code></b>
</p>
</form>


## api/nsr/schools




> Example request:

```bash
curl -X GET \
    -G "https://template.test/api/nsr/schools" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/api/nsr/schools"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "status": 200,
    "status_message": "Success",
    "result": []
}
```
<div id="execution-results-GETapi-nsr-schools" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-nsr-schools"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-nsr-schools"></code></pre>
</div>
<div id="execution-error-GETapi-nsr-schools" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-nsr-schools"></code></pre>
</div>
<form id="form-GETapi-nsr-schools" data-method="GET" data-path="api/nsr/schools" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-nsr-schools', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-nsr-schools" onclick="tryItOut('GETapi-nsr-schools');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-nsr-schools" onclick="cancelTryOut('GETapi-nsr-schools');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-nsr-schools" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/nsr/schools</code></b>
</p>
</form>


## Fylke




> Example request:

```bash
curl -X GET \
    -G "https://template.test/api/nsr/counties/illo" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/api/nsr/counties/illo"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "status": 200,
    "status_message": "Success",
    "result": null
}
```
<div id="execution-results-GETapi-nsr-counties--fylkesnr-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-nsr-counties--fylkesnr-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-nsr-counties--fylkesnr-"></code></pre>
</div>
<div id="execution-error-GETapi-nsr-counties--fylkesnr-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-nsr-counties--fylkesnr-"></code></pre>
</div>
<form id="form-GETapi-nsr-counties--fylkesnr-" data-method="GET" data-path="api/nsr/counties/{fylkesnr}" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-nsr-counties--fylkesnr-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-nsr-counties--fylkesnr-" onclick="tryItOut('GETapi-nsr-counties--fylkesnr-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-nsr-counties--fylkesnr-" onclick="cancelTryOut('GETapi-nsr-counties--fylkesnr-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-nsr-counties--fylkesnr-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/nsr/counties/{fylkesnr}</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>fylkesnr</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="fylkesnr" data-endpoint="GETapi-nsr-counties--fylkesnr-" data-component="url" required  hidden>
<br>

</p>
</form>


## Kommune




> Example request:

```bash
curl -X GET \
    -G "https://template.test/api/nsr/communities/tempora" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/api/nsr/communities/tempora"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "status": 200,
    "status_message": "Success",
    "result": null
}
```
<div id="execution-results-GETapi-nsr-communities--kommunenr-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-nsr-communities--kommunenr-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-nsr-communities--kommunenr-"></code></pre>
</div>
<div id="execution-error-GETapi-nsr-communities--kommunenr-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-nsr-communities--kommunenr-"></code></pre>
</div>
<form id="form-GETapi-nsr-communities--kommunenr-" data-method="GET" data-path="api/nsr/communities/{kommunenr}" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-nsr-communities--kommunenr-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-nsr-communities--kommunenr-" onclick="tryItOut('GETapi-nsr-communities--kommunenr-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-nsr-communities--kommunenr-" onclick="cancelTryOut('GETapi-nsr-communities--kommunenr-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-nsr-communities--kommunenr-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/nsr/communities/{kommunenr}</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>kommunenr</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="kommunenr" data-endpoint="GETapi-nsr-communities--kommunenr-" data-component="url" required  hidden>
<br>

</p>
</form>


## Kommuner i Fylke




> Example request:

```bash
curl -X GET \
    -G "https://template.test/api/nsr/counties/enim/communities" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/api/nsr/counties/enim/communities"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "status": 200,
    "status_message": "Success",
    "result": []
}
```
<div id="execution-results-GETapi-nsr-counties--fylkesnr--communities" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-nsr-counties--fylkesnr--communities"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-nsr-counties--fylkesnr--communities"></code></pre>
</div>
<div id="execution-error-GETapi-nsr-counties--fylkesnr--communities" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-nsr-counties--fylkesnr--communities"></code></pre>
</div>
<form id="form-GETapi-nsr-counties--fylkesnr--communities" data-method="GET" data-path="api/nsr/counties/{fylkesnr}/communities" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-nsr-counties--fylkesnr--communities', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-nsr-counties--fylkesnr--communities" onclick="tryItOut('GETapi-nsr-counties--fylkesnr--communities');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-nsr-counties--fylkesnr--communities" onclick="cancelTryOut('GETapi-nsr-counties--fylkesnr--communities');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-nsr-counties--fylkesnr--communities" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/nsr/counties/{fylkesnr}/communities</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>fylkesnr</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="fylkesnr" data-endpoint="GETapi-nsr-counties--fylkesnr--communities" data-component="url" required  hidden>
<br>

</p>
</form>


## skoler i fylke




> Example request:

```bash
curl -X GET \
    -G "https://template.test/api/nsr/counties/sed/schools" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/api/nsr/counties/sed/schools"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "status": 200,
    "status_message": "Success",
    "result": []
}
```
<div id="execution-results-GETapi-nsr-counties--fylkesnr--schools" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-nsr-counties--fylkesnr--schools"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-nsr-counties--fylkesnr--schools"></code></pre>
</div>
<div id="execution-error-GETapi-nsr-counties--fylkesnr--schools" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-nsr-counties--fylkesnr--schools"></code></pre>
</div>
<form id="form-GETapi-nsr-counties--fylkesnr--schools" data-method="GET" data-path="api/nsr/counties/{fylkesnr}/schools" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-nsr-counties--fylkesnr--schools', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-nsr-counties--fylkesnr--schools" onclick="tryItOut('GETapi-nsr-counties--fylkesnr--schools');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-nsr-counties--fylkesnr--schools" onclick="cancelTryOut('GETapi-nsr-counties--fylkesnr--schools');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-nsr-counties--fylkesnr--schools" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/nsr/counties/{fylkesnr}/schools</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>fylkesnr</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="fylkesnr" data-endpoint="GETapi-nsr-counties--fylkesnr--schools" data-component="url" required  hidden>
<br>

</p>
</form>


## skoler i kommune




> Example request:

```bash
curl -X GET \
    -G "https://template.test/api/nsr/communities/odit/schools" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/api/nsr/communities/odit/schools"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "status": 200,
    "status_message": "Success",
    "result": []
}
```
<div id="execution-results-GETapi-nsr-communities--kommunenr--schools" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-nsr-communities--kommunenr--schools"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-nsr-communities--kommunenr--schools"></code></pre>
</div>
<div id="execution-error-GETapi-nsr-communities--kommunenr--schools" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-nsr-communities--kommunenr--schools"></code></pre>
</div>
<form id="form-GETapi-nsr-communities--kommunenr--schools" data-method="GET" data-path="api/nsr/communities/{kommunenr}/schools" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-nsr-communities--kommunenr--schools', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-nsr-communities--kommunenr--schools" onclick="tryItOut('GETapi-nsr-communities--kommunenr--schools');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-nsr-communities--kommunenr--schools" onclick="cancelTryOut('GETapi-nsr-communities--kommunenr--schools');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-nsr-communities--kommunenr--schools" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/nsr/communities/{kommunenr}/schools</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>kommunenr</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="kommunenr" data-endpoint="GETapi-nsr-communities--kommunenr--schools" data-component="url" required  hidden>
<br>

</p>
</form>


## api/kindergartens




> Example request:

```bash
curl -X GET \
    -G "https://template.test/api/kindergartens" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/api/kindergartens"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "status": 200,
    "status_message": "Success",
    "result": []
}
```
<div id="execution-results-GETapi-kindergartens" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-kindergartens"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-kindergartens"></code></pre>
</div>
<div id="execution-error-GETapi-kindergartens" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-kindergartens"></code></pre>
</div>
<form id="form-GETapi-kindergartens" data-method="GET" data-path="api/kindergartens" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-kindergartens', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-kindergartens" onclick="tryItOut('GETapi-kindergartens');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-kindergartens" onclick="cancelTryOut('GETapi-kindergartens');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-kindergartens" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/kindergartens</code></b>
</p>
</form>


## Barnehager i kommune




> Example request:

```bash
curl -X GET \
    -G "https://template.test/api/kindergartens/tempore" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/api/kindergartens/tempore"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "status": 200,
    "status_message": "Success",
    "result": []
}
```
<div id="execution-results-GETapi-kindergartens--kommunenr-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-kindergartens--kommunenr-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-kindergartens--kommunenr-"></code></pre>
</div>
<div id="execution-error-GETapi-kindergartens--kommunenr-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-kindergartens--kommunenr-"></code></pre>
</div>
<form id="form-GETapi-kindergartens--kommunenr-" data-method="GET" data-path="api/kindergartens/{kommunenr}" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-kindergartens--kommunenr-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-kindergartens--kommunenr-" onclick="tryItOut('GETapi-kindergartens--kommunenr-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-kindergartens--kommunenr-" onclick="cancelTryOut('GETapi-kindergartens--kommunenr-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-kindergartens--kommunenr-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/kindergartens/{kommunenr}</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>kommunenr</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="kommunenr" data-endpoint="GETapi-kindergartens--kommunenr-" data-component="url" required  hidden>
<br>

</p>
</form>


## api/user_activity




> Example request:

```bash
curl -X GET \
    -G "https://template.test/api/user_activity" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/api/user_activity"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json

[]
```
<div id="execution-results-GETapi-user_activity" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-user_activity"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-user_activity"></code></pre>
</div>
<div id="execution-error-GETapi-user_activity" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-user_activity"></code></pre>
</div>
<form id="form-GETapi-user_activity" data-method="GET" data-path="api/user_activity" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-user_activity', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-user_activity" onclick="tryItOut('GETapi-user_activity');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-user_activity" onclick="cancelTryOut('GETapi-user_activity');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-user_activity" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/user_activity</code></b>
</p>
</form>


## api/user_activity/{course_id}




> Example request:

```bash
curl -X GET \
    -G "https://template.test/api/user_activity/animi" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/api/user_activity/animi"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (500):

```json
{
    "message": "Server Error"
}
```
<div id="execution-results-GETapi-user_activity--course_id-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-user_activity--course_id-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-user_activity--course_id-"></code></pre>
</div>
<div id="execution-error-GETapi-user_activity--course_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-user_activity--course_id-"></code></pre>
</div>
<form id="form-GETapi-user_activity--course_id-" data-method="GET" data-path="api/user_activity/{course_id}" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-user_activity--course_id-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-user_activity--course_id-" onclick="tryItOut('GETapi-user_activity--course_id-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-user_activity--course_id-" onclick="cancelTryOut('GETapi-user_activity--course_id-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-user_activity--course_id-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/user_activity/{course_id}</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>course_id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="course_id" data-endpoint="GETapi-user_activity--course_id-" data-component="url" required  hidden>
<br>

</p>
</form>


## api/user_activity




> Example request:

```bash
curl -X POST \
    "https://template.test/api/user_activity" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/api/user_activity"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response => response.json());
```


<div id="execution-results-POSTapi-user_activity" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-user_activity"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-user_activity"></code></pre>
</div>
<div id="execution-error-POSTapi-user_activity" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-user_activity"></code></pre>
</div>
<form id="form-POSTapi-user_activity" data-method="POST" data-path="api/user_activity" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-user_activity', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-user_activity" onclick="tryItOut('POSTapi-user_activity');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-user_activity" onclick="cancelTryOut('POSTapi-user_activity');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-user_activity" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/user_activity</code></b>
</p>
</form>


## api/group/user




> Example request:

```bash
curl -X GET \
    -G "https://template.test/api/group/user" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/api/group/user"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (403):

```json

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
Invalid or missing lti_message_type parameter.
</p>
</div>
</body>
</html>

```
<div id="execution-results-GETapi-group-user" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-group-user"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-group-user"></code></pre>
</div>
<div id="execution-error-GETapi-group-user" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-group-user"></code></pre>
</div>
<form id="form-GETapi-group-user" data-method="GET" data-path="api/group/user" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-group-user', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-group-user" onclick="tryItOut('GETapi-group-user');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-group-user" onclick="cancelTryOut('GETapi-group-user');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-group-user" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/group/user</code></b>
</p>
</form>


## api/group/user




> Example request:

```bash
curl -X POST \
    "https://template.test/api/group/user" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"role":"iste","group":{"name":"voluptas","description":"reiciendis","membership":"officiis","category_id":0.644358641,"course_id":727.30834},"unenrollFrom":{"unenrollmentIds":[394309708.5194628,4550699.075]}}'

```

```javascript
const url = new URL(
    "https://template.test/api/group/user"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "role": "iste",
    "group": {
        "name": "voluptas",
        "description": "reiciendis",
        "membership": "officiis",
        "category_id": 0.644358641,
        "course_id": 727.30834
    },
    "unenrollFrom": {
        "unenrollmentIds": [
            394309708.5194628,
            4550699.075
        ]
    }
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


<div id="execution-results-POSTapi-group-user" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-group-user"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-group-user"></code></pre>
</div>
<div id="execution-error-POSTapi-group-user" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-group-user"></code></pre>
</div>
<form id="form-POSTapi-group-user" data-method="POST" data-path="api/group/user" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-group-user', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-group-user" onclick="tryItOut('POSTapi-group-user');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-group-user" onclick="cancelTryOut('POSTapi-group-user');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-group-user" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/group/user</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>role</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="role" data-endpoint="POSTapi-group-user" data-component="body" required  hidden>
<br>

</p>
<p>
<details>
<summary>
<b><code>group</code></b>&nbsp;&nbsp;<small>object</small>     <i>optional</i> &nbsp;
<br>

</summary>
<br>
<p>
<b><code>group.name</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="group.name" data-endpoint="POSTapi-group-user" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>group.description</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="group.description" data-endpoint="POSTapi-group-user" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>group.membership</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="group.membership" data-endpoint="POSTapi-group-user" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>group.category_id</code></b>&nbsp;&nbsp;<small>number</small>  &nbsp;
<input type="number" name="group.category_id" data-endpoint="POSTapi-group-user" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>group.course_id</code></b>&nbsp;&nbsp;<small>number</small>  &nbsp;
<input type="number" name="group.course_id" data-endpoint="POSTapi-group-user" data-component="body" required  hidden>
<br>

</p>
</details>
</p>
<p>
<details>
<summary>
<b><code>unenrollFrom</code></b>&nbsp;&nbsp;<small>object</small>     <i>optional</i> &nbsp;
<br>

</summary>
<br>
<p>
<b><code>unenrollFrom.unenrollmentIds</code></b>&nbsp;&nbsp;<small>number[]</small>     <i>optional</i> &nbsp;
<input type="number" name="unenrollFrom.unenrollmentIds.0" data-endpoint="POSTapi-group-user" data-component="body"  hidden>
<input type="number" name="unenrollFrom.unenrollmentIds.1" data-endpoint="POSTapi-group-user" data-component="body" hidden>
<br>

</p>
</details>
</p>

</form>


## api/group/{groupId}/category




> Example request:

```bash
curl -X GET \
    -G "https://template.test/api/group/tenetur/category" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/api/group/tenetur/category"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (500):

```json
{
    "message": "Server Error"
}
```
<div id="execution-results-GETapi-group--groupId--category" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-group--groupId--category"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-group--groupId--category"></code></pre>
</div>
<div id="execution-error-GETapi-group--groupId--category" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-group--groupId--category"></code></pre>
</div>
<form id="form-GETapi-group--groupId--category" data-method="GET" data-path="api/group/{groupId}/category" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-group--groupId--category', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-group--groupId--category" onclick="tryItOut('GETapi-group--groupId--category');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-group--groupId--category" onclick="cancelTryOut('GETapi-group--groupId--category');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-group--groupId--category" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/group/{groupId}/category</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>groupId</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="groupId" data-endpoint="GETapi-group--groupId--category" data-component="url" required  hidden>
<br>

</p>
</form>


## api/group/user/bulk




> Example request:

```bash
curl -X POST \
    "https://template.test/api/group/user/bulk" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"county":{"name":"enim","description":"quia"},"community":{"name":"eius","description":"voluptatibus"},"faculty":"dolorem"}'

```

```javascript
const url = new URL(
    "https://template.test/api/group/user/bulk"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "county": {
        "name": "enim",
        "description": "quia"
    },
    "community": {
        "name": "eius",
        "description": "voluptatibus"
    },
    "faculty": "dolorem"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


<div id="execution-results-POSTapi-group-user-bulk" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-group-user-bulk"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-group-user-bulk"></code></pre>
</div>
<div id="execution-error-POSTapi-group-user-bulk" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-group-user-bulk"></code></pre>
</div>
<form id="form-POSTapi-group-user-bulk" data-method="POST" data-path="api/group/user/bulk" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-group-user-bulk', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-group-user-bulk" onclick="tryItOut('POSTapi-group-user-bulk');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-group-user-bulk" onclick="cancelTryOut('POSTapi-group-user-bulk');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-group-user-bulk" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/group/user/bulk</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<details>
<summary>
<b><code>county</code></b>&nbsp;&nbsp;<small>object</small>     <i>optional</i> &nbsp;
<br>

</summary>
<br>
<p>
<b><code>county.name</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="county.name" data-endpoint="POSTapi-group-user-bulk" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>county.description</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="county.description" data-endpoint="POSTapi-group-user-bulk" data-component="body" required  hidden>
<br>

</p>
</details>
</p>
<p>
<details>
<summary>
<b><code>community</code></b>&nbsp;&nbsp;<small>object</small>     <i>optional</i> &nbsp;
<br>

</summary>
<br>
<p>
<b><code>community.name</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="community.name" data-endpoint="POSTapi-group-user-bulk" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>community.description</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="community.description" data-endpoint="POSTapi-group-user-bulk" data-component="body" required  hidden>
<br>

</p>
</details>
</p>
<p>
<b><code>faculty</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="faculty" data-endpoint="POSTapi-group-user-bulk" data-component="body"  hidden>
<br>

</p>

</form>


## api/statistics/{courseId}




> Example request:

```bash
curl -X GET \
    -G "https://template.test/api/statistics/asperiores" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/api/statistics/asperiores"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (500):

```json
{
    "message": "Server Error"
}
```
<div id="execution-results-GETapi-statistics--courseId-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-statistics--courseId-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-statistics--courseId-"></code></pre>
</div>
<div id="execution-error-GETapi-statistics--courseId-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-statistics--courseId-"></code></pre>
</div>
<form id="form-GETapi-statistics--courseId-" data-method="GET" data-path="api/statistics/{courseId}" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-statistics--courseId-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-statistics--courseId-" onclick="tryItOut('GETapi-statistics--courseId-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-statistics--courseId-" onclick="cancelTryOut('GETapi-statistics--courseId-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-statistics--courseId-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/statistics/{courseId}</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>courseId</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="courseId" data-endpoint="GETapi-statistics--courseId-" data-component="url" required  hidden>
<br>

</p>
</form>


## api/statistics/{courseId}/count




> Example request:

```bash
curl -X GET \
    -G "https://template.test/api/statistics/id/count" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/api/statistics/id/count"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (500):

```json
{
    "message": "Server Error"
}
```
<div id="execution-results-GETapi-statistics--courseId--count" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-statistics--courseId--count"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-statistics--courseId--count"></code></pre>
</div>
<div id="execution-error-GETapi-statistics--courseId--count" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-statistics--courseId--count"></code></pre>
</div>
<form id="form-GETapi-statistics--courseId--count" data-method="GET" data-path="api/statistics/{courseId}/count" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-statistics--courseId--count', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-statistics--courseId--count" onclick="tryItOut('GETapi-statistics--courseId--count');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-statistics--courseId--count" onclick="cancelTryOut('GETapi-statistics--courseId--count');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-statistics--courseId--count" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/statistics/{courseId}/count</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>courseId</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="courseId" data-endpoint="GETapi-statistics--courseId--count" data-component="url" required  hidden>
<br>

</p>
</form>


## api/statistics/{courseId}/user_activity




> Example request:

```bash
curl -X GET \
    -G "https://template.test/api/statistics/et/user_activity" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/api/statistics/et/user_activity"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (500):

```json
{
    "message": "Server Error"
}
```
<div id="execution-results-GETapi-statistics--courseId--user_activity" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-statistics--courseId--user_activity"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-statistics--courseId--user_activity"></code></pre>
</div>
<div id="execution-error-GETapi-statistics--courseId--user_activity" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-statistics--courseId--user_activity"></code></pre>
</div>
<form id="form-GETapi-statistics--courseId--user_activity" data-method="GET" data-path="api/statistics/{courseId}/user_activity" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-statistics--courseId--user_activity', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-statistics--courseId--user_activity" onclick="tryItOut('GETapi-statistics--courseId--user_activity');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-statistics--courseId--user_activity" onclick="cancelTryOut('GETapi-statistics--courseId--user_activity');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-statistics--courseId--user_activity" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/statistics/{courseId}/user_activity</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>courseId</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="courseId" data-endpoint="GETapi-statistics--courseId--user_activity" data-component="url" required  hidden>
<br>

</p>
</form>


## api/statistics/groupCategory/{categoryId}




> Example request:

```bash
curl -X GET \
    -G "https://template.test/api/statistics/groupCategory/eius" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/api/statistics/groupCategory/eius"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (500):

```json
{
    "message": "Server Error"
}
```
<div id="execution-results-GETapi-statistics-groupCategory--categoryId-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-statistics-groupCategory--categoryId-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-statistics-groupCategory--categoryId-"></code></pre>
</div>
<div id="execution-error-GETapi-statistics-groupCategory--categoryId-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-statistics-groupCategory--categoryId-"></code></pre>
</div>
<form id="form-GETapi-statistics-groupCategory--categoryId-" data-method="GET" data-path="api/statistics/groupCategory/{categoryId}" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-statistics-groupCategory--categoryId-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-statistics-groupCategory--categoryId-" onclick="tryItOut('GETapi-statistics-groupCategory--categoryId-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-statistics-groupCategory--categoryId-" onclick="cancelTryOut('GETapi-statistics-groupCategory--categoryId-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-statistics-groupCategory--categoryId-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/statistics/groupCategory/{categoryId}</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>categoryId</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="categoryId" data-endpoint="GETapi-statistics-groupCategory--categoryId-" data-component="url" required  hidden>
<br>

</p>
</form>


## api/statistics/groupCategory/{categoryId}/count




> Example request:

```bash
curl -X GET \
    -G "https://template.test/api/statistics/groupCategory/consequatur/count" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/api/statistics/groupCategory/consequatur/count"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (500):

```json
{
    "message": "Server Error"
}
```
<div id="execution-results-GETapi-statistics-groupCategory--categoryId--count" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-statistics-groupCategory--categoryId--count"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-statistics-groupCategory--categoryId--count"></code></pre>
</div>
<div id="execution-error-GETapi-statistics-groupCategory--categoryId--count" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-statistics-groupCategory--categoryId--count"></code></pre>
</div>
<form id="form-GETapi-statistics-groupCategory--categoryId--count" data-method="GET" data-path="api/statistics/groupCategory/{categoryId}/count" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-statistics-groupCategory--categoryId--count', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-statistics-groupCategory--categoryId--count" onclick="tryItOut('GETapi-statistics-groupCategory--categoryId--count');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-statistics-groupCategory--categoryId--count" onclick="cancelTryOut('GETapi-statistics-groupCategory--categoryId--count');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-statistics-groupCategory--categoryId--count" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/statistics/groupCategory/{categoryId}/count</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>categoryId</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="categoryId" data-endpoint="GETapi-statistics-groupCategory--categoryId--count" data-component="url" required  hidden>
<br>

</p>
</form>


## api/vimeo/{vimeoId}




> Example request:

```bash
curl -X GET \
    -G "https://template.test/api/vimeo/aspernatur" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/api/vimeo/aspernatur"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (500):

```json
{
    "message": "Server Error"
}
```
<div id="execution-results-GETapi-vimeo--vimeoId-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-vimeo--vimeoId-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-vimeo--vimeoId-"></code></pre>
</div>
<div id="execution-error-GETapi-vimeo--vimeoId-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-vimeo--vimeoId-"></code></pre>
</div>
<form id="form-GETapi-vimeo--vimeoId-" data-method="GET" data-path="api/vimeo/{vimeoId}" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-vimeo--vimeoId-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-vimeo--vimeoId-" onclick="tryItOut('GETapi-vimeo--vimeoId-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-vimeo--vimeoId-" onclick="cancelTryOut('GETapi-vimeo--vimeoId-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-vimeo--vimeoId-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/vimeo/{vimeoId}</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>vimeoId</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="vimeoId" data-endpoint="GETapi-vimeo--vimeoId-" data-component="url" required  hidden>
<br>

</p>
</form>


## api/vimeo/{vimeoId}/reset




> Example request:

```bash
curl -X GET \
    -G "https://template.test/api/vimeo/magni/reset" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/api/vimeo/magni/reset"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (500):

```json
{
    "message": "Server Error"
}
```
<div id="execution-results-GETapi-vimeo--vimeoId--reset" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-vimeo--vimeoId--reset"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-vimeo--vimeoId--reset"></code></pre>
</div>
<div id="execution-error-GETapi-vimeo--vimeoId--reset" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-vimeo--vimeoId--reset"></code></pre>
</div>
<form id="form-GETapi-vimeo--vimeoId--reset" data-method="GET" data-path="api/vimeo/{vimeoId}/reset" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-vimeo--vimeoId--reset', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-vimeo--vimeoId--reset" onclick="tryItOut('GETapi-vimeo--vimeoId--reset');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-vimeo--vimeoId--reset" onclick="cancelTryOut('GETapi-vimeo--vimeoId--reset');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-vimeo--vimeoId--reset" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/vimeo/{vimeoId}/reset</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>vimeoId</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="vimeoId" data-endpoint="GETapi-vimeo--vimeoId--reset" data-component="url" required  hidden>
<br>

</p>
</form>


## api/kpasinfo




> Example request:

```bash
curl -X GET \
    -G "https://template.test/api/kpasinfo" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/api/kpasinfo"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "status": 200,
    "status_message": "Success",
    "result": {
        "privacyPolicyVersion": "1.0"
    }
}
```
<div id="execution-results-GETapi-kpasinfo" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-kpasinfo"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-kpasinfo"></code></pre>
</div>
<div id="execution-error-GETapi-kpasinfo" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-kpasinfo"></code></pre>
</div>
<form id="form-GETapi-kpasinfo" data-method="GET" data-path="api/kpasinfo" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-kpasinfo', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-kpasinfo" onclick="tryItOut('GETapi-kpasinfo');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-kpasinfo" onclick="cancelTryOut('GETapi-kpasinfo');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-kpasinfo" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/kpasinfo</code></b>
</p>
</form>


## api/enrollment




> Example request:

```bash
curl -X GET \
    -G "https://template.test/api/enrollment" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/api/enrollment"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (403):

```json

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
Invalid or missing lti_message_type parameter.
</p>
</div>
</body>
</html>

```
<div id="execution-results-GETapi-enrollment" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-enrollment"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-enrollment"></code></pre>
</div>
<div id="execution-error-GETapi-enrollment" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-enrollment"></code></pre>
</div>
<form id="form-GETapi-enrollment" data-method="GET" data-path="api/enrollment" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-enrollment', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-enrollment" onclick="tryItOut('GETapi-enrollment');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-enrollment" onclick="cancelTryOut('GETapi-enrollment');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-enrollment" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/enrollment</code></b>
</p>
</form>


## api/enrollment




> Example request:

```bash
curl -X POST \
    "https://template.test/api/enrollment" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"role":"fugit"}'

```

```javascript
const url = new URL(
    "https://template.test/api/enrollment"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "role": "fugit"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


<div id="execution-results-POSTapi-enrollment" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-enrollment"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-enrollment"></code></pre>
</div>
<div id="execution-error-POSTapi-enrollment" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-enrollment"></code></pre>
</div>
<form id="form-POSTapi-enrollment" data-method="POST" data-path="api/enrollment" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-enrollment', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-enrollment" onclick="tryItOut('POSTapi-enrollment');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-enrollment" onclick="cancelTryOut('POSTapi-enrollment');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-enrollment" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/enrollment</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>role</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="role" data-endpoint="POSTapi-enrollment" data-component="body" required  hidden>
<br>

</p>

</form>


## api/faculties




> Example request:

```bash
curl -X GET \
    -G "https://template.test/api/faculties" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/api/faculties"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (403):

```json

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
Invalid or missing lti_message_type parameter.
</p>
</div>
</body>
</html>

```
<div id="execution-results-GETapi-faculties" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-faculties"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-faculties"></code></pre>
</div>
<div id="execution-error-GETapi-faculties" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-faculties"></code></pre>
</div>
<form id="form-GETapi-faculties" data-method="GET" data-path="api/faculties" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-faculties', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-faculties" onclick="tryItOut('GETapi-faculties');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-faculties" onclick="cancelTryOut('GETapi-faculties');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-faculties" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/faculties</code></b>
</p>
</form>


## api/command/migrate




> Example request:

```bash
curl -X GET \
    -G "https://template.test/api/command/migrate" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/api/command/migrate"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json

Migration completed!
```
<div id="execution-results-GETapi-command-migrate" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-command-migrate"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-command-migrate"></code></pre>
</div>
<div id="execution-error-GETapi-command-migrate" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-command-migrate"></code></pre>
</div>
<form id="form-GETapi-command-migrate" data-method="GET" data-path="api/command/migrate" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-command-migrate', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-command-migrate" onclick="tryItOut('GETapi-command-migrate');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-command-migrate" onclick="cancelTryOut('GETapi-command-migrate');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-command-migrate" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/command/migrate</code></b>
</p>
</form>


## api/user/merge/token




> Example request:

```bash
curl -X GET \
    -G "https://template.test/api/user/merge/token" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/api/user/merge/token"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (403):

```json

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
Invalid or missing lti_message_type parameter.
</p>
</div>
</body>
</html>

```
<div id="execution-results-GETapi-user-merge-token" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-user-merge-token"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-user-merge-token"></code></pre>
</div>
<div id="execution-error-GETapi-user-merge-token" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-user-merge-token"></code></pre>
</div>
<form id="form-GETapi-user-merge-token" data-method="GET" data-path="api/user/merge/token" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-user-merge-token', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-user-merge-token" onclick="tryItOut('GETapi-user-merge-token');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-user-merge-token" onclick="cancelTryOut('GETapi-user-merge-token');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-user-merge-token" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/user/merge/token</code></b>
</p>
</form>


## api/user/merge/intersection




> Example request:

```bash
curl -X GET \
    -G "https://template.test/api/user/merge/intersection" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/api/user/merge/intersection"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (403):

```json

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
Invalid or missing lti_message_type parameter.
</p>
</div>
</body>
</html>

```
<div id="execution-results-GETapi-user-merge-intersection" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-user-merge-intersection"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-user-merge-intersection"></code></pre>
</div>
<div id="execution-error-GETapi-user-merge-intersection" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-user-merge-intersection"></code></pre>
</div>
<form id="form-GETapi-user-merge-intersection" data-method="GET" data-path="api/user/merge/intersection" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-user-merge-intersection', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-user-merge-intersection" onclick="tryItOut('GETapi-user-merge-intersection');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-user-merge-intersection" onclick="cancelTryOut('GETapi-user-merge-intersection');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-user-merge-intersection" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/user/merge/intersection</code></b>
</p>
</form>


## api/user/merge/perform




> Example request:

```bash
curl -X GET \
    -G "https://template.test/api/user/merge/perform" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/api/user/merge/perform"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (403):

```json

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
Invalid or missing lti_message_type parameter.
</p>
</div>
</body>
</html>

```
<div id="execution-results-GETapi-user-merge-perform" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-user-merge-perform"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-user-merge-perform"></code></pre>
</div>
<div id="execution-error-GETapi-user-merge-perform" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-user-merge-perform"></code></pre>
</div>
<form id="form-GETapi-user-merge-perform" data-method="GET" data-path="api/user/merge/perform" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-user-merge-perform', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-user-merge-perform" onclick="tryItOut('GETapi-user-merge-perform');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-user-merge-perform" onclick="cancelTryOut('GETapi-user-merge-perform');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-user-merge-perform" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/user/merge/perform</code></b>
</p>
</form>


## Check if the requesting platform is registered in our DB.




> Example request:

```bash
curl -X POST \
    "https://template.test/lti3" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/lti3"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response => response.json());
```


<div id="execution-results-POSTlti3" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTlti3"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTlti3"></code></pre>
</div>
<div id="execution-error-POSTlti3" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTlti3"></code></pre>
</div>
<form id="form-POSTlti3" data-method="POST" data-path="lti3" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTlti3', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTlti3" onclick="tryItOut('POSTlti3');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTlti3" onclick="cancelTryOut('POSTlti3');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTlti3" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>lti3</code></b>
</p>
</form>


## Launch requested link


[ Authenticate and Redirect to the requested link ]

> Example request:

```bash
curl -X POST \
    "https://template.test/launch" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/launch"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response => response.json());
```


<div id="execution-results-POSTlaunch" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTlaunch"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTlaunch"></code></pre>
</div>
<div id="execution-error-POSTlaunch" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTlaunch"></code></pre>
</div>
<form id="form-POSTlaunch" data-method="POST" data-path="launch" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTlaunch', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTlaunch" onclick="tryItOut('POSTlaunch');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTlaunch" onclick="cancelTryOut('POSTlaunch');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTlaunch" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>launch</code></b>
</p>
</form>


## /




> Example request:

```bash
curl -X POST \
    "https://template.test/" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response => response.json());
```


<div id="execution-results-POST-" hidden>
    <blockquote>Received response<span id="execution-response-status-POST-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POST-"></code></pre>
</div>
<div id="execution-error-POST-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POST-"></code></pre>
</div>
<form id="form-POST-" data-method="POST" data-path="/" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POST-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POST-" onclick="tryItOut('POST-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POST-" onclick="cancelTryOut('POST-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POST-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>/</code></b>
</p>
</form>


## statistics/{courseId}




> Example request:

```bash
curl -X GET \
    -G "https://template.test/statistics/qui" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/statistics/qui"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (500):

```json
{
    "message": "Server Error"
}
```
<div id="execution-results-GETstatistics--courseId-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETstatistics--courseId-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETstatistics--courseId-"></code></pre>
</div>
<div id="execution-error-GETstatistics--courseId-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETstatistics--courseId-"></code></pre>
</div>
<form id="form-GETstatistics--courseId-" data-method="GET" data-path="statistics/{courseId}" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETstatistics--courseId-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETstatistics--courseId-" onclick="tryItOut('GETstatistics--courseId-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETstatistics--courseId-" onclick="cancelTryOut('GETstatistics--courseId-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETstatistics--courseId-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>statistics/{courseId}</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>courseId</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="courseId" data-endpoint="GETstatistics--courseId-" data-component="url" required  hidden>
<br>

</p>
</form>


## grep/gf5




> Example request:

```bash
curl -X GET \
    -G "https://template.test/grep/gf5" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/grep/gf5"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (500):

```json
{
    "message": "Server Error"
}
```
<div id="execution-results-GETgrep-gf5" hidden>
    <blockquote>Received response<span id="execution-response-status-GETgrep-gf5"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETgrep-gf5"></code></pre>
</div>
<div id="execution-error-GETgrep-gf5" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETgrep-gf5"></code></pre>
</div>
<form id="form-GETgrep-gf5" data-method="GET" data-path="grep/gf5" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETgrep-gf5', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETgrep-gf5" onclick="tryItOut('GETgrep-gf5');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETgrep-gf5" onclick="cancelTryOut('GETgrep-gf5');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETgrep-gf5" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>grep/gf5</code></b>
</p>
</form>


## deep




> Example request:

```bash
curl -X GET \
    -G "https://template.test/deep" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/deep"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (500):

```json
{
    "message": "Server Error"
}
```
<div id="execution-results-GETdeep" hidden>
    <blockquote>Received response<span id="execution-response-status-GETdeep"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETdeep"></code></pre>
</div>
<div id="execution-error-GETdeep" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETdeep"></code></pre>
</div>
<form id="form-GETdeep" data-method="GET" data-path="deep" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETdeep', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETdeep" onclick="tryItOut('GETdeep');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETdeep" onclick="cancelTryOut('GETdeep');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETdeep" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>deep</code></b>
</p>
</form>


## Invoke the controller method.




> Example request:

```bash
curl -X GET \
    -G "https://template.test/cookiecheck" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/cookiecheck"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json

<html>
<body>
<script>
	document.cookie="thirdparty=yes";
	document.location="cookiecheckcomplete";
</script>
</body>
</html>
```
<div id="execution-results-GETcookiecheck" hidden>
    <blockquote>Received response<span id="execution-response-status-GETcookiecheck"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETcookiecheck"></code></pre>
</div>
<div id="execution-error-GETcookiecheck" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETcookiecheck"></code></pre>
</div>
<form id="form-GETcookiecheck" data-method="GET" data-path="cookiecheck" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETcookiecheck', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETcookiecheck" onclick="tryItOut('GETcookiecheck');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETcookiecheck" onclick="cancelTryOut('GETcookiecheck');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETcookiecheck" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>cookiecheck</code></b>
</p>
</form>


## Invoke the controller method.




> Example request:

```bash
curl -X GET \
    -G "https://template.test/cookiecheckcomplete" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/cookiecheckcomplete"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json

<html>
<body>
<script>
 if (window.parent) {
	if (/thirdparty=yes/.test(document.cookie)) {
        const cookiesupportedmsg = {
            subject: 'kpas-lti.3pcookiesupported'
        }
		window.parent.postMessage(JSON.stringify(cookiesupportedmsg), '*');
	} else {
        const cookienotsupportedmsg = {
            subject: 'kpas-lti.3pcookienotsupported'
        }
		window.parent.postMessage(JSON.stringify(cookienotsupportedmsg), '*');
	}
 }
</script>
</body>
</html>
```
<div id="execution-results-GETcookiecheckcomplete" hidden>
    <blockquote>Received response<span id="execution-response-status-GETcookiecheckcomplete"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETcookiecheckcomplete"></code></pre>
</div>
<div id="execution-error-GETcookiecheckcomplete" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETcookiecheckcomplete"></code></pre>
</div>
<form id="form-GETcookiecheckcomplete" data-method="GET" data-path="cookiecheckcomplete" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETcookiecheckcomplete', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETcookiecheckcomplete" onclick="tryItOut('GETcookiecheckcomplete');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETcookiecheckcomplete" onclick="cancelTryOut('GETcookiecheckcomplete');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETcookiecheckcomplete" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>cookiecheckcomplete</code></b>
</p>
</form>


## /




> Example request:

```bash
curl -X GET \
    -G "https://template.test/" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (302):

```json

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="refresh" content="0;url='http://template.test/page/logout'" />

        <title>Redirecting to http://template.test/page/logout</title>
    </head>
    <body>
        Redirecting to <a href="http://template.test/page/logout">http://template.test/page/logout</a>.
    </body>
</html>
```
<div id="execution-results-GET-" hidden>
    <blockquote>Received response<span id="execution-response-status-GET-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GET-"></code></pre>
</div>
<div id="execution-error-GET-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GET-"></code></pre>
</div>
<form id="form-GET-" data-method="GET" data-path="/" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GET-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GET-" onclick="tryItOut('GET-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GET-" onclick="cancelTryOut('GET-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GET-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>/</code></b>
</p>
</form>


## logout




> Example request:

```bash
curl -X GET \
    -G "https://template.test/logout" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/logout"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (302):

```json

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="refresh" content="0;url='https://api.dataporten.no/logout'" />

        <title>Redirecting to https://api.dataporten.no/logout</title>
    </head>
    <body>
        Redirecting to <a href="https://api.dataporten.no/logout">https://api.dataporten.no/logout</a>.
    </body>
</html>
```
<div id="execution-results-GETlogout" hidden>
    <blockquote>Received response<span id="execution-response-status-GETlogout"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETlogout"></code></pre>
</div>
<div id="execution-error-GETlogout" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETlogout"></code></pre>
</div>
<form id="form-GETlogout" data-method="GET" data-path="logout" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETlogout', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETlogout" onclick="tryItOut('GETlogout');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETlogout" onclick="cancelTryOut('GETlogout');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETlogout" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>logout</code></b>
</p>
</form>


## page/logout




> Example request:

```bash
curl -X GET \
    -G "https://template.test/page/logout" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/page/logout"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KPAS (Kompetanseplattform Administrativt System)</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <link href="https://template.test/css/app.css" rel="stylesheet">
</head>
<body>
    <div class="container" id="app">
        <div class="alert alert-info mb-4">
    Mangler course_id parameter.
</div>
<a href="http://template.test/logout" class="btn btn-primary">Logout</a>
    </div>
        <script src="https://template.test/js/app.js"></script>
</body>
</html>

```
<div id="execution-results-GETpage-logout" hidden>
    <blockquote>Received response<span id="execution-response-status-GETpage-logout"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETpage-logout"></code></pre>
</div>
<div id="execution-error-GETpage-logout" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETpage-logout"></code></pre>
</div>
<form id="form-GETpage-logout" data-method="GET" data-path="page/logout" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETpage-logout', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETpage-logout" onclick="tryItOut('GETpage-logout');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETpage-logout" onclick="cancelTryOut('GETpage-logout');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETpage-logout" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>page/logout</code></b>
</p>
</form>


## minegrupper




> Example request:

```bash
curl -X GET \
    -G "https://template.test/minegrupper" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/minegrupper"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (302):

```json

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="refresh" content="0;url='http://template.test'" />

        <title>Redirecting to http://template.test</title>
    </head>
    <body>
        Redirecting to <a href="http://template.test">http://template.test</a>.
    </body>
</html>
```
<div id="execution-results-GETminegrupper" hidden>
    <blockquote>Received response<span id="execution-response-status-GETminegrupper"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETminegrupper"></code></pre>
</div>
<div id="execution-error-GETminegrupper" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETminegrupper"></code></pre>
</div>
<form id="form-GETminegrupper" data-method="GET" data-path="minegrupper" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETminegrupper', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETminegrupper" onclick="tryItOut('GETminegrupper');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETminegrupper" onclick="cancelTryOut('GETminegrupper');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETminegrupper" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>minegrupper</code></b>
</p>
</form>


## worker




> Example request:

```bash
curl -X POST \
    "https://template.test/worker" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"role":"ut","group":{"name":"id","description":"sint","membership":"laudantium","category_id":14,"course_id":8.57814337},"unenrollFrom":{"unenrollmentIds":[45600499.64,1200797.859408332]}}'

```

```javascript
const url = new URL(
    "https://template.test/worker"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "role": "ut",
    "group": {
        "name": "id",
        "description": "sint",
        "membership": "laudantium",
        "category_id": 14,
        "course_id": 8.57814337
    },
    "unenrollFrom": {
        "unenrollmentIds": [
            45600499.64,
            1200797.859408332
        ]
    }
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


<div id="execution-results-POSTworker" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTworker"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTworker"></code></pre>
</div>
<div id="execution-error-POSTworker" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTworker"></code></pre>
</div>
<form id="form-POSTworker" data-method="POST" data-path="worker" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTworker', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTworker" onclick="tryItOut('POSTworker');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTworker" onclick="cancelTryOut('POSTworker');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTworker" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>worker</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>role</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="role" data-endpoint="POSTworker" data-component="body" required  hidden>
<br>

</p>
<p>
<details>
<summary>
<b><code>group</code></b>&nbsp;&nbsp;<small>object</small>     <i>optional</i> &nbsp;
<br>

</summary>
<br>
<p>
<b><code>group.name</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="group.name" data-endpoint="POSTworker" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>group.description</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="group.description" data-endpoint="POSTworker" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>group.membership</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="group.membership" data-endpoint="POSTworker" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>group.category_id</code></b>&nbsp;&nbsp;<small>number</small>  &nbsp;
<input type="number" name="group.category_id" data-endpoint="POSTworker" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>group.course_id</code></b>&nbsp;&nbsp;<small>number</small>  &nbsp;
<input type="number" name="group.course_id" data-endpoint="POSTworker" data-component="body" required  hidden>
<br>

</p>
</details>
</p>
<p>
<details>
<summary>
<b><code>unenrollFrom</code></b>&nbsp;&nbsp;<small>object</small>     <i>optional</i> &nbsp;
<br>

</summary>
<br>
<p>
<b><code>unenrollFrom.unenrollmentIds</code></b>&nbsp;&nbsp;<small>number[]</small>     <i>optional</i> &nbsp;
<input type="number" name="unenrollFrom.unenrollmentIds.0" data-endpoint="POSTworker" data-component="body"  hidden>
<input type="number" name="unenrollFrom.unenrollmentIds.1" data-endpoint="POSTworker" data-component="body" hidden>
<br>

</p>
</details>
</p>

</form>


## worker




> Example request:

```bash
curl -X GET \
    -G "https://template.test/worker" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://template.test/worker"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (302):

```json

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="refresh" content="0;url='http://template.test'" />

        <title>Redirecting to http://template.test</title>
    </head>
    <body>
        Redirecting to <a href="http://template.test">http://template.test</a>.
    </body>
</html>
```
<div id="execution-results-GETworker" hidden>
    <blockquote>Received response<span id="execution-response-status-GETworker"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETworker"></code></pre>
</div>
<div id="execution-error-GETworker" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETworker"></code></pre>
</div>
<form id="form-GETworker" data-method="GET" data-path="worker" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETworker', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETworker" onclick="tryItOut('GETworker');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETworker" onclick="cancelTryOut('GETworker');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETworker" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>worker</code></b>
</p>
</form>



