<?php 
include('dbcon.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Include CSS -->
    <link rel="stylesheet" href="style.css">
    
    <!-- jQuery & Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="//cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css"> -->

    <!-- Datatables button -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.2/css/buttons.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css">
</head>
<body>
    <div id="notification" class="notification">
        <div class="notifier new">
            <i class="bell fa-regular fa-bell"></i>
            <div class="badge" id="notif_count"></div>
        </div>
        
        <div id="notificationList" > 
            <!-- Notifications will load here -->
        </div>
    </div> 
        
    <div class="search_container">
        <form class="search">
        <label for="supplies">WAREHOUSE</label><br>
            <input type="text" id="filter" required onkeypress="return (event.charCode != 13);" autocomplete="off"> 
        </form>
    </div>
    
    <div class="add_supplies">
        <a href="history.php" target="_blank">
            <button type="button" class="btn btn-info">View Returned History</button>
        </a>    
    </div>

    <div class="result" id="table-result"></div>

    <!-- Data Tables BuiltIn Buttons-->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.print.min.js"></script>
    <!-- Push Notification -->
    <script src="push_notification.js"></script>
    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/cdd64a3c17.js" crossorigin="anonymous"></script><script src="https://kit.fontawesome.com/cdd64a3c17.js" crossorigin="anonymous"></script>

    <script>

    $(document).ready(function() {
        let debounceTimer;
        viewDefaultTableDebounced("");

        $('#filter').on('keyup', function() {
            var filter = $(this).val();                                           
            console.log(filter);
            
            viewDefaultTableDebounced(filter);
        });

        $('#notification .bell').on('click', function() {
            $('#notificationList').toggle(); // Toggle visibility of the list div itself
        });
        
        $(document).on('click', function(event) {
            if (!$(event.target).closest('#notification').length) {
                $('#notificationList').hide(); // Hide the notification list
            }
        });

        function viewDefaultTableDebounced(filter) {
            clearTimeout(debounceTimer);

            debounceTimer = setTimeout(function () {
                $.ajax({
                    type: "GET",
                    url: "tableIndex.php",
                    data: { filter: filter },
                    success: function (response) {
                        $("#table-result").html(response);
                    }
                });
            }, 300); // adjust delay as needed
        }
    });
    </script>

</body>
</html>
