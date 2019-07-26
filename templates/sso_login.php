<div>
Processing authorization token...
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/hmac-sha256.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/enc-base64.min.js"></script>

<script>
  let b64DecodeUnicode = str =>
    decodeURIComponent(
      Array.prototype.map.call(atob(str), c =>
        '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2)
      ).join('')
    );

  let parseJwt = token =>
    JSON.parse(
      b64DecodeUnicode(
        token.split('.')[1].replace('-', '+').replace('_', '/')
      )
    );

  let getHashVariable = (hash, variable) => {
    var query = hash.substring(1);
    var vars = query.split("&");
    for (var i=0; i < vars.length; i++) {
      var pair = vars[i].split("=");
      if(pair[0] == variable) { return pair[1]; }
    }
    return(false);
  }

  let parseHash = (hash) => {
    var token = getHashVariable(hash, 'access_token');
    if (!token) {
      window.location = '<?php echo wp_login_url(); ?>';
    }
    var unsignedPart = token.split(".")[0] + "." + token.split(".")[1];
    var signature = token.split(".")[2].replace(/-/g, '+').replace(/_/g, '/');
    
    var hashed = CryptoJS.HmacSHA256(unsignedPart, '');
    var hashInBase64 = CryptoJS.enc.Base64.stringify(hashed);
    var response = parseJwt(token);
    localStorage.setItem("mddToken", token);
    localStorage.setItem("cdiSite", '<?php echo esc_attr(get_option('authorize_endpoint')); ?>');
    const provider_link = getHashVariable(hash, 'provider_link');
    window.location = '<?php echo \Inc\Base\URLRegistry::getParseTokenUrl(); ?>' + '?access_token=' + token + (provider_link ? ('&provider_link=' + provider_link) : '') + '&state=' + getHashVariable(hash, 'state');
  }
  window.addEventListener("load", function() {
    parseHash(window.location.hash);
  });
</script>

