<script>
  window.addEventListener("load", function() {
    const url = new URL('<?php echo wp_login_url(); ?>');
    const redirectURL = url.origin + url.pathname;
    window.location = redirectURL + window.location.search;
  });
</script>

