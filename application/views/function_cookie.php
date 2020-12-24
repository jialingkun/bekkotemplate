<script>
  function setCookie(cname, cvalue, callback){
    $.ajax({
      url: "<?php echo base_url() ?>main/create_cookie",
      type: 'POST',
      data: {name: cname, value: cvalue},
      success: function (response) {
        callback(response);
      },
      error: function (xhr, status, error) {
        alert(status + '- ' + xhr.status + ': ' + xhr.statusText);
      }
    });
  }

  function getCookie(cname, callback) {
    $.ajax({
      url: "<?php echo base_url() ?>main/get_cookie/" + cname,
      dataType: 'text',
      success: function (response) {
        callback(response);
      },
      error: function (xhr, status, error) {
        alert(status + '- ' + xhr.status + ': ' + xhr.statusText);
      }
    });
  }

  function getLoginCookie(cname, callback) {
    $.ajax({
      url: "<?php echo base_url() ?>main/get_cookie_decrypt/" + cname,
      dataType: 'text',
      success: function (response) {
        callback(response);
      },
      error: function (xhr, status, error) {
        alert(status + '- ' + xhr.status + ': ' + xhr.statusText);
      }
    });
  }
</script>