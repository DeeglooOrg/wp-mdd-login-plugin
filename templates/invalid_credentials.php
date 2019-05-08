<h1>Invalid credentials</h1>
<div>
Wrong password/username used to login with MDD SSO. Redirecting to Wordpress login page in 3 seconds...
</div>
<script>
  window.addEventListener("load", function() {
    setTimeout(() => {
      window.location = '<?php echo wp_login_url(); ?>';
    }, 3000);
  });
</script>

