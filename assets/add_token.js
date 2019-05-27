window.addEventListener("load", function() {
  const token = localStorage.getItem('mddToken');
  const cdiSite = localStorage.getItem('cdiSite') || '#';
  const links = document.querySelectorAll('a');
  links.forEach(function(a) {
    if(a.href.startsWith(cdiSite)) {
        a.href = cdiSite + '#access_token=' + token;
    }
  })
});
