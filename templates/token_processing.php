<h1>JWT token</h1>
<div id="data">
Token is being processed...
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/hmac-sha256.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/enc-base64.min.js"></script>
<script>
let getSearchVariable = (search, variable) => {
  var query = search.substring(1);
  var vars = query.split("&");
  for (var i=0; i < vars.length; i++) {
    var pair = vars[i].split("=");
    if(pair[0] == variable) { return pair[1]; }
  }
  return(false);
}
  window.addEventListener("load", function() {
    var token = getSearchVariable(window.location.search, 'access_token');
    var unsignedPart = token.split(".")[0] + "." + token.split(".")[1];
    var signature = token.split(".")[2].replace(/-/g, '+').replace(/_/g, '/');
    
    var hashed = CryptoJS.HmacSHA256(unsignedPart, '<?php echo esc_attr(get_option('client_secret')); ?>');
    var hashInBase64 = CryptoJS.enc.Base64.stringify(hashed);
    var response = parseJwt(token);
    var tableRows = "";
    Object.keys(response).forEach(function(key) {
      tableRows += "<tr><td>" + key + "</td><td>" + response[key] + "</td></tr>"
    });
    document.querySelector("#data").innerHTML = "<table border='1'><tr><th>Key</th><th>Value</th></tr>" + tableRows + "</table>";
    if (hashInBase64.includes(signature)) {
      document.querySelector("#data").innerHTML += "<br>TOKEN IS <span style='color:green'>VALID</span>";
    } else {
      document.querySelector("#data").innerHTML += "<br>TOKEN IS <span style='color:red'>INVALID</span>";
    }
  
    document.querySelector("#data").innerHTML += '<br/><a href="<?php echo \Inc\Base\URLRegistry::getParseTokenUrl(); ?>?access_token=' + token + '">Create User<a/>';
  });

let b64DecodeUnicode = str =>
  decodeURIComponent(
    Array.prototype.map.call(atob(str), c =>
      '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2)
    ).join(''))

let parseJwt = token =>
JSON.parse(
  b64DecodeUnicode(
    token.split('.')[1].replace('-', '+').replace('_', '/')
  )
)
</script>

