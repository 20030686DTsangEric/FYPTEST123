<?php

include('../class/Appointment.php');

$object = new Appointment;

if (!$object->is_login()) {
    header("location:" . $object->base_url . "");
}

if ($_SESSION['type'] != 'Teacher') {
    header("location:" . $object->base_url . "");
}

$object->query = "
    SELECT * FROM teacher_table
    WHERE teacher_id = '" . $_SESSION["admin_id"] . "'
    ";

$result = $object->get_result();

include('header.php');

?>

<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">Profile</h1>

<!-- DataTales Example -->

<form method="post" id="profile_form" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-10"><span id="message"></span>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="row">
                        <div class="col">
                            <h6 class="m-0 font-weight-bold text-primary">Profile</h6>
                        </div>
                        <div clas="col" align="right">
                            <input type="hidden" name="action" value="teacher_profile"/>
                            <input type="hidden" name="hidden_id" id="hidden_id"/>
                            <button type="submit" name="edit_button" id="edit_button" class="btn btn-primary btn-sm"><i
                                        class="fas fa-edit"></i> Edit
                            </button>
                            &nbsp;&nbsp;
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!--<div class="row">
                        <div class="col-md-6">!-->
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
                    <!--</div>
                </div>!-->
                </div>
            </div>
        </div>
    </div>
</form>
<?php
include('footer.php');
?>

<script>
    $(document).ready(function () {

        $('#teacher_date_of_birth').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true
        });

        <?php
        foreach($result as $row)
        {
        ?>
        $('#hidden_id').val("<?php echo $row['teacher_id']; ?>");
        $('#teacher_email_address').val("<?php echo $row['teacher_email_address']; ?>");
        $('#teacher_password').val("<?php echo $row['teacher_password']; ?>");
        $('#teacher_name').val("<?php echo $row['teacher_name']; ?>");
        $('#teacher_phone_no').val("<?php echo $row['teacher_phone_no']; ?>");
        $('#teacher_address').val("<?php echo $row['teacher_address']; ?>");
        $('#teacher_date_of_birth').val("<?php echo $row['teacher_date_of_birth']; ?>");
        $('#teacher_degree').val("<?php echo $row['teacher_degree']; ?>");
        $('#teacher_expert_in').val("<?php echo $row['teacher_expert_in']; ?>");

        $('#uploaded_image').html('<img src="<?php echo $row["teacher_profile_image"]; ?>" class="img-thumbnail" width="100" /><input type="hidden" name="hidden_teacher_profile_image" value="<?php echo $row["teacher_profile_image"]; ?>" />');

        $('#hidden_teacher_profile_image').val("<?php echo $row['teacher_profile_image']; ?>");
        <?php
        }
        ?>

        $('#teacher_profile_image').change(function () {
            var extension = $('#teacher_profile_image').val().split('.').pop().toLowerCase();
            if (extension != '') {
                if (jQuery.inArray(extension, ['png', 'jpg']) == -1) {
                    alert("Invalid Image File");
                    $('#teacher_profile_image').val('');
                    return false;
                }
            }
        });

        $('#profile_form').parsley();

        $('#profile_form').on('submit', function (event) {
            event.preventDefault();
            if ($('#profile_form').parsley().isValid()) {
                $.ajax({
                    url: "profile_action.php",
                    method: "POST",
                    data: new FormData(this),
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $('#edit_button').attr('disabled', 'disabled');
                        $('#edit_button').html('wait...');
                    },
                    success: function (data) {
                        $('#edit_button').attr('disabled', false);
                        $('#edit_button').html('<i class="fas fa-edit"></i> Edit');

                        $('#teacher_email_address').val(data.teacher_email_address);
                        $('#teacher_password').val(data.teacher_password);
                        $('#teacher_name').val(data.teacher_name);
                        $('#teacher_phone_no').val(data.teacher_phone_no);
                        $('#teacher_address').text(data.teacher_address);
                        $('#teacher_date_of_birth').text(data.teacher_date_of_birth);
                        $('#teacher_degree').text(data.teacher_degree);
                        $('#teacher_expert_in').text(data.teacher_expert_in);
                        if (data.teacher_profile_image != '') {
                            $('#uploaded_image').html('<img src="' + data.teacher_profile_image + '" class="img-thumbnail" width="100" />');

                            $('#user_profile_image').attr('src', data.teacher_profile_image);
                        }

                        $('#hidden_teacher_profile_image').val(data.teacher_profile_image);

                        $('#message').html(data.success);

                        setTimeout(function () {

                            $('#message').html('');

                        }, 5000);
                    }
                })
            }
        });

    });
</script>