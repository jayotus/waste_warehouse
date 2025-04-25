<?php 
    include('dbcon.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Include CSS -->
    <link rel="stylesheet" href="style.css">

    <!-- jQuery & Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Datatables button -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.2/css/buttons.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css">
</head>
    <body>
        <div class="search_container">
            <form class="search">
            <label for="supplies">WAREHOUSE RECORD</label><br>
                <input type="text" id="filter" required onkeypress="return (event.charCode != 13);" autocomplete="off"> 
            </form>
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
        <script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.print.min.js"></script>\

        <script>
            $(document).ready(function () { 
                let debounceTimer;
                const currentFilter = $('#filter').val();
                
                viewDefaultTableDebounced(currentFilter);

                setInterval(function () {
                    viewDefaultTableDebounced();
                }, 30000);

                $('#filter').on('keyup', function() {
                    var filter = $(this).val();
                    // console.log(filter);
                    
                    viewDefaultTableDebounced(filter);
                });

                function viewDefaultTableDebounced(filter) {
                    clearTimeout(debounceTimer);

                    debounceTimer = setTimeout(function () {
                        $.ajax({
                            type: "GET",
                            url: "tableHistory.php",
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