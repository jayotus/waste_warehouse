<?php
include('dbcon.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
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
    if (isset($_GET['filter'])) {

        $filter = htmlspecialchars($_GET['filter']);

        if (empty($filter) || $filter == "") {
            $sql = "SELECT * FROM delivery_out ORDER BY date DESC";
        } else {
            $sql = "SELECT * FROM delivery_out WHERE model LIKE '%$filter%' OR description LIKE '%$filter%' OR code LIKE '%$filter%' OR owner LIKE '%$filter%' OR date_of_delivery LIKE '%$filter%' OR barcode LIKE '%$filter%' OR client LIKE '%$filter%' OR machine_model LIKE '%$filter%' OR stock_transfer LIKE '%$filter%' OR returned_by LIKE '%$filter%' OR tech_name LIKE '%$filter%' ORDER BY date DESC";
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
                            <th class="h6 fw-bold">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
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
                                <td id="return_quantity"><?php echo htmlspecialchars($row['return_quantity']) ?></td>
                                <td><button id="return-button" class="out" data-bs-toggle="modal" data-bs-target="#staticBackdrop_out" data-id="<?php echo htmlspecialchars($row['id']) ?>">RETURN</button></td>
                            </tr>
                        <?php } ?>

                        <div class="modal fade" id="staticBackdrop_out" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel_out" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel_out">Return Waste Supply </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div id="out-result"></div>
                                        <form class="form_modal">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label> Model</label>
                                                        <input type="text" name="Model" id="model_out" class="form-control custom-input" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label> Description</label>
                                                        <input type="text" name="Description" id="description_out" class="form-control custom-input" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label> Code</label>
                                                        <input type="text" name="Code" id="code_out" class="form-control custom-input" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label> Owner</label>
                                                        <input type="text" name="Owner" id="owner_out" class="form-control custom-input" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Date Of Delivery</label>
                                                        <input type="text" name="Date of Delivery" id="date_of_delivery_out" class="form-control custom-input" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Invoice or Stock Transfer</label>
                                                        <input type="text" name="invoice or stock transfer" id="invoice_or_stock_transfer_out" class="form-control custom-input" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Client</label>
                                                        <input type="text" name="cleint" id="client_out" class="form-control custom-input" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Tech Name</label>
                                                        <input type="text" name="tech name" id="tech_name_out" class="form-control custom-input" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Machine Model</label>
                                                        <input type="text" name="machine model" id="machine_model_out" class="form-control custom-input" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Machine Serial</label>
                                                        <input type="text" name="machine serial" id="machine_serial_out" class="form-control custom-input" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Barcode</label>
                                                        <input type="text" name="barcode" id="barcode_out" class="form-control custom-input" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Quantity</label>
                                                        <input type="text" name="quantity" id="quantity_out" class="form-control custom-input" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="space">

                                                <!-- ENTRY START -->
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label> Return Delivery Date</label><br>
                                                        <input type="date" class="return_date_of_delivery_out form-control" id="return_date_of_delivery_out" value="<?php echo date('Y-m-d'); ?>" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label> Quantity Returned</label>
                                                        <input type="number" name="quantity returned" id="returned_quantity_out" class="quantity form-control custom-input" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Returned By</label>
                                                        <input type="text" name="return by" id="returned_by_out" class="form-control custom-input" require>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Barcode Returned</label>
                                                        <input type="text" name="barcode-returned" id="returned_barcode" class="form-control custom-input" require>
                                                    </div>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-danger btn_out">OUT</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </tbody>
                </table>
            </div>
    <?php } else {
            echo ('<p class="no-record">NO RECORD</p>');
        }
    }
    ?>

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

    <script>
        $(document).ready(function() {
            // highlightReturnedRows();
            let lastClickedOutButton = null;

            var table = $('#example').DataTable({
                searching: false,
                // paging: false,
                retrieve: true,
                "order": [
                    [0, "asc"]
                ],
                "ordering": true,
                "columnDefs": [{
                        "orderSequence": ["asc", "desc"],
                        "targets": "_all"
                    } // Only asc & desc
                ],
                drawCallback: function() {
                    highlightReturnedRows(this.api()); // Pass the DataTable API to your function
                }
            });

            // OUT BUTTON HANDLER
            $('.table').on('click', 'button.out', function() {
                lastClickedOutButton = this;

                returnButtonWhenClick(this);
            });

            // Handle OUT REMOVE SUPPLIES
            $('.btn_out').click(function() {
                var id = $('#staticBackdrop_out').data('id');
                var quantity = $('#quantity_out').val();
                var deliveryDate = $('#date_of_delivery_out').val();
                var model = $('#model_out').val();
                var description = $('#description_out').val();
                var owner = $('#owner_out').val();
                var code = $('#code_out').val();
                var client = $('#client_out').val();
                var stockTransfer = $('#invoice_or_stock_transfer_out').val();
                var machineModel = $('#machine_model_out').val();
                var machineSerial = $('#machine_serial_out').val();
                var techName = $('#tech_name_out').val();
                var barcode = $('#barcode_out').val();

                var returnedQuantity = $('#returned_quantity_out').val();
                var returnedBy = $('#returned_by_out').val();
                var returnedDeliveryDate = $('#return_date_of_delivery_out').val();
                var returnedBarcode = $("#returned_barcode").val();

                var row = $('tr[data-id="' + id + '"]');

                // Error Handler
                var errors = [];

                if (!returnedQuantity || parseInt(returnedQuantity) <= 0) errors.push("Invalid Quantity, Please enter a valid quantity.")
                if (!returnedBy || returnedBy == "") errors.push("Invalid Name, Enter a name.");
                if (!returnedDeliveryDate) errors.push("Invalid Date. " + returnedDeliveryDate);
                if (returnedBarcode == "") errors.push("Invalid Barcode, Please enter a valid barcode.");

                if (errors.length > 0) {
                    $('#out-result').html('<div class="alert alert-warning">' + errors.join("<br>") + '</div>');
                    return;
                }

                console.log("THE DATA IS: " + id + ", " + quantity + ", " + deliveryDate + ", " + model + ", " + description + ", " + owner + ", " + code + ", " + client + ", " + returnedQuantity + ", " + returnedBy + ", " + returnedDeliveryDate + ", " + barcode + ", " + techName + ", " + client + ", " + machineModel + ", " + machineSerial + ", " +
                    returnedBarcode);

                if (confirm("Press ok to confirm your changes.")) {
                    $.ajax({
                        type: "POST",
                        url: "returned.php",
                        data: {
                            id,
                            quantity,
                            barcode,
                            code,
                            client,
                            stockTransfer,
                            machineModel,
                            machineSerial,
                            techName,
                            owner,
                            model,
                            description,
                            date_of_delivery: deliveryDate,
                            returnedQuantity,
                            returnedBy,
                            returnedDeliveryDate,
                            returnedBarcode
                        },
                        success: function(response) {
                            var result = JSON.parse(response);
                            if (result.status === "success") {

                                $('#out-result')
                                    .stop(true, true)
                                    .css('display', 'block')
                                    .html(
                                        '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                                        '<strong>Success!</strong> ' + result.message +
                                        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                                        '</div>'
                                    );

                                setTimeout(function() {
                                    $('.alert-success').fadeOut();
                                }, 3000);

                                var table = $('#example').DataTable();
                                var rowIndex = table.row(row).index();
                                var rowData = table.row(row).data();
                                var currentReturned = parseFloat(rowData[13]) || 0;
                                var newReturned = parseFloat(returnedQuantity) || 0;
                                rowData[13] = currentReturned + newReturned;
                                table.row(rowIndex).data(rowData).draw(false); // update only that row

                                // Update display inside modal
                                row.find("#return_quantity").text(rowData[13]);

                                // Clear modal inputs
                                $("#returned_by_out").val('');
                                $("#returned_quantity_out").val('');
                                $("#return_date_of_delivery_out").val('<?php echo date('Y-m-d'); ?>');
                                $("#returned_barcode").val('');

                                // Optional: call your button callback
                                if (lastClickedOutButton) {
                                    returnButtonWhenClick(lastClickedOutButton);
                                }

                                // table.ajax.reload();
                            } else {
                                $('#out-result').html(
                                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                                    '<strong>Error: </strong> ' + result.message +
                                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                                    '</div>'
                                );
                                setTimeout(function() {
                                    $('.alert-danger').fadeOut();
                                }, 3000);
                            }
                        }
                    });
                }
            });

            function highlightReturnedRows(api) {
                api.rows().every(function() {
                    var data = this.data();
                    var quantity = parseInt(data[12], 10);
                    var returnedQuantity = parseInt(data[13], 10);

                    var rowNode = this.node();
                    var cell = $(rowNode).find('td').eq(13); // Returned quantity column

                    // Clear previous color
                    cell.css('background-color', '');

                    if (returnedQuantity > 0 && returnedQuantity < quantity) {
                        cell.css('background-color', 'khaki'); // partially returned
                    } else if (returnedQuantity >= quantity) {
                        cell.css('background-color', '#d4edda'); // fully returned
                    }
                });
            }

            // Updated function
            function returnButtonWhenClick(button) {

                var row = $(button).closest('tr');
                var rowData = table.row(row).data();

                $('#model_out').val(rowData[1]);
                $('#description_out').val(rowData[2]);
                $('#code_out').val(rowData[3]);
                $('#owner_out').val(rowData[4]);
                $('#date_of_delivery_out').val(rowData[5]);
                $('#invoice_or_stock_transfer_out').val(rowData[6]);
                $('#client_out').val(rowData[7]);
                $('#tech_name_out').val(rowData[8]);
                $('#machine_model_out').val(rowData[9]);
                $('#machine_serial_out').val(rowData[10]);
                $('#barcode_out').val(rowData[11]);
                $('#quantity_out').val(rowData[12]);

                var quantity = parseInt(rowData[12], 10);
                var returnedQuantity = parseInt(rowData[13], 10);


                $('#staticBackdrop_out').data('id', row.attr('data-id'));

                if (returnedQuantity >= quantity) {
                    var message = "The item is already returned, cannot return the item again";

                    $('.btn_out').prop('disabled', true);
                    $("#returned_by_out").prop('disabled', true);
                    $("#returned_quantity_out").prop('disabled', true);
                    $("#return_date_of_delivery_out").prop('disabled', true);
                    $("#returned_barcode").prop('disabled', true);

                    $('#out-result').html(
                        '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                        '<strong>Success:</strong> ' + message +
                        '</div>'
                    );
                } else {
                    $('.btn_out').prop('disabled', false);
                    $("#returned_by_out").prop('disabled', false);
                    $("#returned_quantity_out").prop('disabled', false);
                    $("#return_date_of_delivery_out").prop('disabled', false);
                    $('#out-result').html('');
                }
            }
        });
    </script>
</body>

</html>