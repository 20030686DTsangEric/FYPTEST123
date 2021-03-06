<?php

//teacher.php

include('../class/Appointment.php');

$object = new Appointment;

if (!$object->is_login()) {
    header("location:" . $object->base_url . "admin");
}

if ($_SESSION['type'] != 'Admin') {
    header("location:" . $object->base_url . "");
}

include('header.php');

?>

<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">Teacher Management</h1>

<!-- DataTales Example -->
<span id="message"></span>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="row">
            <div class="col">
                <h6 class="m-0 font-weight-bold text-primary">Teacher List</h6>
            </div>
            <div class="col" align="right">
                <button type="button" name="add_teacher" id="add_teacher" class="btn btn-success btn-circle btn-sm"><i
                            class="fas fa-plus"></i></button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="teacher_table" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>Image</th>
                    <th>Email Address</th>
                    <th>Password</th>
                    <th>Teacher Name</th>
                    <th>Teacher Phone No.</th>
                    <th>Teacher Speciality</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include('footer.php');
?>

<div id="teacherModal" class="modal fade">
    <div class="modal-dialog">
        <form method="post" id="teacher_form">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Add Teacher</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <span id="form_message"></span>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Teacher Email Address <span class="text-danger">*</span></label>
                                <input type="text" name="teacher_email_address" id="teacher_email_address"
                                       class="form-control" required data-parsley-type="email"
                                       data-parsley-trigger="keyup"/>
                            </div>
                            <div class="col-md-6">
                                <label>Teacher Password <span class="text-danger">*</span></label>
                                <input type="password" name="teacher_password" id="teacher_password"
                                       class="form-control" required data-parsley-trigger="keyup"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Teacher Name <span class="text-danger">*</span></label>
                                <input type="text" name="teacher_name" id="teacher_name" class="form-control" required
                                       data-parsley-trigger="keyup"/>
                            </div>
                            <div class="col-md-6">
                                <label>Teacher Phone No. <span class="text-danger">*</span></label>
                                <input type="text" name="teacher_phone_no" id="teacher_phone_no" class="form-control"
                                       required data-parsley-trigger="keyup"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Teacher Address </label>
                                <input type="text" name="teacher_address" id="teacher_address" class="form-control"/>
                            </div>
                            <div class="col-md-6">
                                <label>Teacher Date of Birth </label>
                                <input type="text" name="teacher_date_of_birth" id="teacher_date_of_birth" readonly
                                       class="form-control"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Teacher Degree <span class="text-danger">*</span></label>
                                <input type="text" name="teacher_degree" id="teacher_degree" class="form-control"
                                       required data-parsley-trigger="keyup"/>
                            </div>
                            <div class="col-md-6">
                                <label>Teacher Speciality <span class="text-danger">*</span></label>
                                <input type="text" name="teacher_expert_in" id="teacher_expert_in" class="form-control"
                                       required data-parsley-trigger="keyup"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Teacher Image <span class="text-danger">*</span></label>
                        <br/>
                        <input type="file" name="teacher_profile_image" id="teacher_profile_image"/>
                        <div id="uploaded_image"></div>
                        <input type="hidden" name="hidden_teacher_profile_image" id="hidden_teacher_profile_image"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="hidden_id" id="hidden_id"/>
                    <input type="hidden" name="action" id="action" value="Add"/>
                    <input type="submit" name="submit" id="submit_button" class="btn btn-success" value="Add"/>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="viewModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal_title">View Teacher Details</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="teacher_details">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        var dataTable = $('#teacher_table').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                url: "teacher_action.php",
                type: "POST",
                data: {action: 'fetch'}
            },
            "columnDefs": [
                {
                    "targets": [0, 1, 2, 4, 5, 6, 7],
                    "orderable": false,
                },
            ],
        });

        $('#teacher_date_of_birth').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true
        });

        $('#add_teacher').click(function () {

            $('#teacher_form')[0].reset();

            $('#teacher_form').parsley().reset();

            $('#modal_title').text('Add Teacher');

            $('#action').val('Add');

            $('#submit_button').val('Add');

            $('#teacherModal').modal('show');

            $('#form_message').html('');

        });

        $('#teacher_form').parsley();

        $('#teacher_form').on('submit', function (event) {
            event.preventDefault();
            if ($('#teacher_form').parsley().isValid()) {
                $.ajax({
                    url: "teacher_action.php",
                    method: "POST",
                    data: new FormData(this),
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function () {
                        $('#submit_button').attr('disabled', 'disabled');
                        $('#submit_button').val('wait...');
                    },
                    success: function (data) {
                        $('#submit_button').attr('disabled', false);
                        if (data.error != '') {
                            $('#form_message').html(data.error);
                            $('#submit_button').val('Add');
                        } else {
                            $('#teacherModal').modal('hide');
                            $('#message').html(data.success);
                            dataTable.ajax.reload();

                            setTimeout(function () {

                                $('#message').html('');

                            }, 5000);
                        }
                    }
                })
            }
        });

        $(document).on('click', '.edit_button', function () {

            var teacher_id = $(this).data('id');

            $('#teacher_form').parsley().reset();

            $('#form_message').html('');

            $.ajax({

                url: "teacher_action.php",

                method: "POST",

                data: {teacher_id: teacher_id, action: 'fetch_single'},

                dataType: 'JSON',

                success: function (data) {

                    $('#teacher_email_address').val(data.teacher_email_address);

                    $('#teacher_email_address').val(data.teacher_email_address);
                    $('#teacher_password').val(data.teacher_password);
                    $('#teacher_name').val(data.teacher_name);
                    $('#uploaded_image').html('<img src="' + data.teacher_profile_image + '" class="img-fluid img-thumbnail" width="150" />')
                    $('#hidden_teacher_profile_image').val(data.teacher_profile_image);
                    $('#teacher_phone_no').val(data.teacher_phone_no);
                    $('#teacher_address').val(data.teacher_address);
                    $('#teacher_date_of_birth').val(data.teacher_date_of_birth);
                    $('#teacher_degree').val(data.teacher_degree);
                    $('#teacher_expert_in').val(data.teacher_expert_in);

                    $('#modal_title').text('Edit Teacher');

                    $('#action').val('Edit');

                    $('#submit_button').val('Edit');

                    $('#teacherModal').modal('show');

                    $('#hidden_id').val(teacher_id);

                }

            })

        });

        $(document).on('click', '.status_button', function () {
            var id = $(this).data('id');
            var status = $(this).data('status');
            var next_status = 'Active';
            if (status == 'Active') {
                next_status = 'Inactive';
            }
            if (confirm("Are you sure you want to " + next_status + " it?")) {

                $.ajax({

                    url: "teacher_action.php",

                    method: "POST",

                    data: {id: id, action: 'change_status', status: status, next_status: next_status},

                    success: function (data) {

                        $('#message').html(data);

                        dataTable.ajax.reload();

                        setTimeout(function () {

                            $('#message').html('');

                        }, 5000);

                    }

                })

            }
        });

        $(document).on('click', '.view_button', function () {
            var teacher_id = $(this).data('id');

            $.ajax({

                url: "teacher_action.php",

                method: "POST",

                data: {teacher_id: teacher_id, action: 'fetch_single'},

                dataType: 'JSON',

                success: function (data) {
                    var html = '<div class="table-responsive">';
                    html += '<table class="table">';

                    html += '<tr><td colspan="2" class="text-center"><img src="' + data.teacher_profile_image + '" class="img-fluid img-thumbnail" width="150" /></td></tr>';

                    html += '<tr><th width="40%" class="text-right">Teacher Email Address</th><td width="60%">' + data.teacher_email_address + '</td></tr>';

                    html += '<tr><th width="40%" class="text-right">Teacher Password</th><td width="60%">' + data.teacher_password + '</td></tr>';

                    html += '<tr><th width="40%" class="text-right">Teacher Name</th><td width="60%">' + data.teacher_name + '</td></tr>';

                    html += '<tr><th width="40%" class="text-right">Teacher Phone No.</th><td width="60%">' + data.teacher_phone_no + '</td></tr>';

                    html += '<tr><th width="40%" class="text-right">Teacher Address</th><td width="60%">' + data.teacher_address + '</td></tr>';

                    html += '<tr><th width="40%" class="text-right">Teacher Date of Birth</th><td width="60%">' + data.teacher_date_of_birth + '</td></tr>';
                    html += '<tr><th width="40%" class="text-right">Teacher Qualification</th><td width="60%">' + data.teacher_degree + '</td></tr>';

                    html += '<tr><th width="40%" class="text-right">Teacher Speciality</th><td width="60%">' + data.teacher_expert_in + '</td></tr>';

                    html += '</table></div>';

                    $('#viewModal').modal('show');

                    $('#teacher_details').html(html);

                }

            })
        });

        $(document).on('click', '.delete_button', function () {

            var id = $(this).data('id');

            if (confirm("Are you sure you want to remove it?")) {

                $.ajax({

                    url: "teacher_action.php",

                    method: "POST",

                    data: {id: id, action: 'delete'},

                    success: function (data) {

                        $('#message').html(data);

                        dataTable.ajax.reload();

                        setTimeout(function () {

                            $('#message').html('');

                        }, 5000);

                    }

                })

            }

        });


    });
</script>