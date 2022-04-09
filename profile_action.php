<?php

include('../class/Appointment.php');

$object = new Appointment;

if($_POST["action"] == 'teacher_profile')
{
	sleep(2);

	$error = '';

	$success = '';

	$teacher_profile_image = '';

	$data = array(
		':teacher_email_address'	=>	$_POST["teacher_email_address"],
		':teacher_id'			=>	$_POST['hidden_id']
	);

	$object->query = "
	SELECT * FROM teacher_table 
	WHERE teacher_email_address = :teacher_email_address 
	AND teacher_id != :teacher_id
	";

	$object->execute($data);

	if($object->row_count() > 0)
	{
		$error = '<div class="alert alert-danger">Email Address Already Exists</div>';
	}
	else
	{
		$teacher_profile_image = $_POST["hidden_teacher_profile_image"];

		if($_FILES['teacher_profile_image']['name'] != '')
		{
			$allowed_file_format = array("jpg", "png");

	    	$file_extension = pathinfo($_FILES["teacher_profile_image"]["name"], PATHINFO_EXTENSION);

	    	if(!in_array($file_extension, $allowed_file_format))
		    {
		        $error = "<div class='alert alert-danger'>Upload valiid file. jpg, png</div>";
		    }
		    else if (($_FILES["teacher_profile_image"]["size"] > 2000000))
		    {
		       $error = "<div class='alert alert-danger'>File size exceeds 2MB</div>";
		    }
		    else
		    {
		    	$new_name = rand() . '.' . $file_extension;

				$destination = '../images/' . $new_name;

				move_uploaded_file($_FILES['teacher_profile_image']['tmp_name'], $destination);

				$teacher_profile_image = $destination;
		    }
		}

		if($error == '')
		{
			$data = array(
				':teacher_email_address'			=>	$object->clean_input($_POST["teacher_email_address"]),
				':teacher_password'				=>	$_POST["teacher_password"],
				':teacher_name'					=>	$object->clean_input($_POST["teacher_name"]),
				':teacher_profile_image'			=>	$teacher_profile_image,
				':teacher_phone_no'				=>	$object->clean_input($_POST["teacher_phone_no"]),
				':teacher_address'				=>	$object->clean_input($_POST["teacher_address"]),
				':teacher_date_of_birth'			=>	$object->clean_input($_POST["teacher_date_of_birth"]),
				':teacher_degree'				=>	$object->clean_input($_POST["teacher_degree"]),
				':teacher_expert_in'				=>	$object->clean_input($_POST["teacher_expert_in"])
			);

			$object->query = "
			UPDATE teacher_table  
			SET teacher_email_address = :teacher_email_address, 
			teacher_password = :teacher_password, 
			teacher_name = :teacher_name, 
			teacher_profile_image = :teacher_profile_image, 
			teacher_phone_no = :teacher_phone_no, 
			teacher_address = :teacher_address, 
			teacher_date_of_birth = :teacher_date_of_birth, 
			teacher_degree = :teacher_degree,  
			teacher_expert_in = :teacher_expert_in 
			WHERE teacher_id = '".$_POST['hidden_id']."'
			";
			$object->execute($data);

			$success = '<div class="alert alert-success">Teacher Data Updated</div>';
		}			
	}

	$output = array(
		'error'					=>	$error,
		'success'				=>	$success,
		'teacher_email_address'	=>	$_POST["teacher_email_address"],
		'teacher_password'		=>	$_POST["teacher_password"],
		'teacher_name'			=>	$_POST["teacher_name"],
		'teacher_profile_image'	=>	$teacher_profile_image,
		'teacher_phone_no'		=>	$_POST["teacher_phone_no"],
		'teacher_address'		=>	$_POST["teacher_address"],
		'teacher_date_of_birth'	=>	$_POST["teacher_date_of_birth"],
		'teacher_degree'			=>	$_POST["teacher_degree"],
		'teacher_expert_in'		=>	$_POST["teacher_expert_in"],
	);

	echo json_encode($output);
}

if($_POST["action"] == 'admin_profile')
{
	sleep(2);

	$error = '';

	$success = '';

	$school_logo = $_POST['hidden_school_logo'];

	if($_FILES['school_logo']['name'] != '')
	{
		$allowed_file_format = array("jpg", "png");

	    $file_extension = pathinfo($_FILES["school_logo"]["name"], PATHINFO_EXTENSION);

	    if(!in_array($file_extension, $allowed_file_format))
		{
		    $error = "<div class='alert alert-danger'>Upload valiid file. jpg, png</div>";
		}
		else if (($_FILES["school_logo"]["size"] > 2000000))
		{
		   $error = "<div class='alert alert-danger'>File size exceeds 2MB</div>";
	    }
		else
		{
		    $new_name = rand() . '.' . $file_extension;

			$destination = '../images/' . $new_name;

			move_uploaded_file($_FILES['school_logo']['tmp_name'], $destination);

			$school_logo = $destination;
		}
	}

	if($error == '')
	{
		$data = array(
			':admin_email_address'			=>	$object->clean_input($_POST["admin_email_address"]),
			':admin_password'				=>	$_POST["admin_password"],
			':admin_name'					=>	$object->clean_input($_POST["admin_name"]),
			':school_name'				=>	$object->clean_input($_POST["school_name"]),
			':school_address'				=>	$object->clean_input($_POST["school_address"]),
			':school_contact_no'			=>	$object->clean_input($_POST["school_contact_no"]),
			':school_logo'				=>	$school_logo
		);

		$object->query = "
		UPDATE admin_table  
		SET admin_email_address = :admin_email_address, 
		admin_password = :admin_password, 
		admin_name = :admin_name, 
		school_name = :school_name, 
		school_address = :school_address, 
		school_contact_no = :school_contact_no, 
		school_logo = :school_logo 
		WHERE admin_id = '".$_SESSION["admin_id"]."'
		";
		$object->execute($data);

		$success = '<div class="alert alert-success">Admin Data Updated</div>';

		$output = array(
			'error'					=>	$error,
			'success'				=>	$success,
			'admin_email_address'	=>	$_POST["admin_email_address"],
			'admin_password'		=>	$_POST["admin_password"],
			'admin_name'			=>	$_POST["admin_name"], 
			'school_name'			=>	$_POST["school_name"],
			'school_address'		=>	$_POST["school_address"],
			'school_contact_no'	=>	$_POST["school_contact_no"],
			'school_logo'			=>	$school_logo
		);

		echo json_encode($output);
	}
	else
	{
		$output = array(
			'error'					=>	$error,
			'success'				=>	$success
		);
		echo json_encode($output);
	}
}

?>