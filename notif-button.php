<div id="notification" class="notification">
    <div class="notifier new">
        <i class="bell fa-regular fa-bell"></i>
        <div class="badge" id="notif_count"></div>
    </div>

    <div id="notificationList">
        <!-- Notifications will load here -->
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#notification .bell').on('click', function() {
            $('#notificationList').toggle(); // Toggle visibility of the list div itself
        });

        $(document).on('click', function(event) {
            if (!$(event.target).closest('#notification').length) {
                $('#notificationList').hide(); // Hide the notification list
            }
        });

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
</script>