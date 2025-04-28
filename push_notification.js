$(document).ready(function () {

  load_unseen_notification(); // Initial load of notifications

  
  // Set interval to refresh notifications
  setInterval(function() {
    load_unseen_notification(); 
  }, 2000);

  // Mark notifications as seen on dropdown click
  $(document).on('click', '#notification .bell', function() {
    $('#notif_count').html(''); // Reset the notification count
    load_unseen_notification('yes'); // Fetch notifications and mark them as seen
  });

  // Function to load notifications
  function load_unseen_notification(view = '') {
    $.ajax({
      url: "push_notification_function.php",
      data: {
        view: view
      },
      dataType: "json",
      success: function(data) {
        
        $('#notificationList').html(data.notification);
        if (data.count > 0) {
          $('#notif_count').html(data.count);
        }
      }
    });
  }
 
});