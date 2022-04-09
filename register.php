<?php

//login.php

include('header.php');

?>

<div class="container">
	<div class="row justify-content-md-center">
		<div class="col col-md-6">
			<span id="message"></span>
			<div class="card">
				<div class="card-header">Register</div>
				<div class="card-body">
					<form method="post" id="student_register_form">
						<div class="form-group">
							<label>Student Email Address<span class="text-danger">*</span></label>
							<input type="text" name="student_email_address" id="student_email_address" class="form-control" required autofocus data-parsley-type="email" data-parsley-trigger="keyup" />
						</div>
						<div class="form-group">
							<label>Student Password<span class="text-danger">*</span></label>
							<input type="password" name="student_password" id="student_password" class="form-control" required  data-parsley-trigger="keyup" />
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Student First Name<span class="text-danger">*</span></label>
									<input type="text" name="student_first_name" id="student_first_name" class="form-control" required  data-parsley-trigger="keyup" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Student Last Name<span class="text-danger">*</span></label>
									<input type="text" name="student_last_name" id="student_last_name" class="form-control" required  data-parsley-trigger="keyup" />
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Student Date of Birth<span class="text-danger">*</span></label>
									<input type="text" name="student_date_of_birth" id="student_date_of_birth" class="form-control" required  data-parsley-trigger="keyup" readonly />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Student Gender<span class="text-danger">*</span></label>
									<select name="student_gender" id="student_gender" class="form-control">
										<option value="Male">Male</option>
										<option value="Female">Female</option>
										<option value="Other">Other</option>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Student ID.<span class="text-danger">*</span></label>
									<input type="text" name="studentID" id="studentID" class="form-control" required  data-parsley-trigger="keyup" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Year of student<span class="text-danger">*</span></label>
									<select name="student_year" id="student_year" class="form-control">
										<option value="Year 1">Year 1</option>
										<option value="Year 2">Year 2</option>
										<option value="Year 3">Year 3</option>
										<option value="Year 4">Year 4</option>
										<option value="Post Graduate">Post Graduate</option>
									</select>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Student Major<span class="text-danger">*</span></label>
							<textarea name="student_major" id="student_major" class="form-control" required data-parsley-trigger="keyup"></textarea>
						</div>
						<div class="form-group text-center">
							<input type="hidden" name="action" value="student_register" />
							<input type="submit" name="student_register_button" id="student_register_button" class="btn btn-primary" value="Register" />
						</div>

						<div class="form-group text-center">
							<p><a href="login.php">Login</a></p>
						</div>
					</form>
				</div>
			</div>
			<br />
			<br />
		</div>
	</div>
</div>

<?php

include('footer.php');

?>

<script>

$(document).ready(function(){

	$('#student_date_of_birth').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true
    });

	$('#student_register_form').parsley();

	$('#student_register_form').on('submit', function(event){

		event.preventDefault();

		if($('#student_register_form').parsley().isValid())
		{
			$.ajax({
				url:"action.php",
				method:"POST",
				data:$(this).serialize(),
				dataType:'json',
				beforeSend:function(){
					$('#student_register_button').attr('disabled', 'disabled');
				},
				success:function(data)
				{
					$('#student_register_button').attr('disabled', false);
					$('#student_register_form')[0].reset();
					if(data.error !== '')
					{
						$('#message').html(data.error);
					}
					if(data.success != '')
					{
						$('#message').html(data.success);
						setTimeout(function () {
                            window.location.href="login.php"
                        },2000)
					}
				}
			});
		}

	});

});

</script>