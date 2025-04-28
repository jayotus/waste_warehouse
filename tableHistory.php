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

    <!-- Datatables button -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.2/css/buttons.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css">

</head>
    <body>

        <?php
            if(isset($_GET['filter'])){
            
                $filter = htmlspecialchars($_GET['filter']);
    
                if(empty($filter) || $filter == "") {
                    $sql = "SELECT * FROM delivery_out_history ORDER BY date DESC";
    
                }else {
                    $sql = "SELECT * FROM delivery_out_history WHERE model LIKE '%$filter%' OR description LIKE '%$filter%' OR code LIKE '%$filter%' OR owner LIKE '%$filter%' OR date_of_delivery LIKE '%$filter%' OR barcode LIKE '%$filter%' OR client LIKE '%$filter%' OR machine_model LIKE '%$filter%' OR stock_transfer LIKE '%$filter%' OR returned_by LIKE '%$filter%' OR tech_name LIKE '%$filter%' ORDER BY date DESC";
                }
                $result = $con->query($sql);
        
        if ($result && $result->num_rows > 0) { ?>
                <div class="bg-white records">
                <table class="table table-responsive table-hover table-responsive-md mb-0" id="example">
                    <thead>
                        <tr>
                            <th class="h6 fw-bold">TYPE</th>
                            <th class="h6 fw-bold">MODEL</th>
                            <th class="h6 fw-bold">DESCRIPTION</th>
                            <th class="h6 fw-bold">CODE</th>
                            <th class="h6 fw-bold">OWNER</th>
                            <th class="h6 fw-bold">DATE OF DELIVERY</th>
                            <th class="h6 fw-bold">INVOICE or <BR>STOCK TRANSFER</th>
                            <th class="h6 fw-bold">CLIENT</th>
                            <th class="h6 fw-bold">TECH NAME</th>
                            <th class="h6 fw-bold">MACHINE MODEL</th>
                            <th class="h6 fw-bold">MACHINE SERIAL</th>
                            <th class="h6 fw-bold">BARCODE</th>
                            <th class="h6 fw-bold">DELIVERED QUANTITY</th>
                            <th class="h6 fw-bold">RETURNED QUANTITY</th>
                            <th class="h6 fw-bold">RETURNED BY</th>
                            <th class="h6 fw-bold">RETURNED DATE</th>
                        </tr>
                    </thead>
                    <tbody id="content">
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr data-id="<?php echo htmlspecialchars($row['id']); ?>">
                                <td><?php echo htmlspecialchars($row['type'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($row['model']); ?></td>
                                <td><?php echo htmlspecialchars($row['description']); ?></td>
                                <td><?php echo htmlspecialchars($row['code']); ?></td>
                                <td><?php echo htmlspecialchars($row['owner']); ?></td>
                                <td><?php echo htmlspecialchars($row['date_of_delivery']); ?></td>
                                <td><?php echo htmlspecialchars($row['invoice'] ?? $row['stock_transfer'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($row['client'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($row['tech_name'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($row['machine_model'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($row['machine_serial'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($row['barcode'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                                <td id="return_quantity"><?php echo htmlspecialchars($row['return_quantity'])?></td>
                                <td><?php echo htmlspecialchars($row['returned_by']); ?></td>
                                <td><?php echo htmlspecialchars($row['return_date']); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
        <?php 
        }else {
            echo('<p class="no-record">NO RECORD</p>');
        }} ?>
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

        <script>
           $(document).ready(function () {
            var table = $('#example').DataTable({
                dom:'<"d-flex justify-content-between"lfB>' +
                    'rt' +                    // table
                    '<"bottom-controls"ip>',  // bottom bar
                searching: false,
                retrieve: true,
                order: [[0, "asc"]],
                ordering: true,
                columnDefs: [
                    { orderSequence: ["asc", "desc"], targets: "_all" }
                ],
                buttons: [
                    'excelHtml5',
                    'csvHtml5',
                    'print'
                ],
            });
        });
        </script>
    </body>
</html>