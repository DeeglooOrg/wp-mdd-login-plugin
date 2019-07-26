<h1>Unauthorized access</h1>
<div>
You dont have access to members center. Redirecting to <span id="location"></span>. Redirecting to login page in 3 seconds...
</div>
<script>
  window.addEventListener("load", function() {
    var urlParams = new URLSearchParams(window.location.search);
    const redirect_url = urlParams.has('provider_uri') ? urlParams.get('provider_uri') : 'https://app.mydairydashboard.com';
    const token = urlParams.has('access_token') ? urlParams.get('access_token') : '';
    document.getElementById("location").innerHTML = redirect_url;
    setTimeout(() => {
      window.location = redirect_url + '/#access_token=' + token;
    }, 3000);
  });
</script>