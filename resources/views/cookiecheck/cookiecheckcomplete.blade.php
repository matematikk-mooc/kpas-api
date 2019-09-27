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