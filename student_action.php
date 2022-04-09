<?php

//teacher_action.php

include('../class/Appointment.php');

$object = new Appointment;

if (isset($_POST["action"])) {
    if ($_POST["action"] == 'fetch') {
        $order_column = array('student_first_name', 'student_last_name', 'student_email_address', 'studentID', 'email_verify');

        $output = array();

        $main_query = "
		SELECT * FROM student_table ";

        $search_query = '';

        if (isset($_POST["search"]["value"])) {
            $search_query .= 'WHERE student_first_name LIKE "%' . $_POST["search"]["value"] . '%" ';
            $search_query .= 'OR student_last_name LIKE "%' . $_POST["search"]["value"] . '%" ';
            $search_query .= 'OR student_email_address LIKE "%' . $_POST["search"]["value"] . '%" ';
            $search_query .= 'OR studentID LIKE "%' . $_POST["search"]["value"] . '%" ';
            $search_query .= 'OR email_verify LIKE "%' . $_POST["search"]["value"] . '%" ';
        }

        if (isset($_POST["order"])) {
            $order_query = 'ORDER BY ' . $order_column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
        } else {
            $order_query = 'ORDER BY student_id DESC ';
        }

        $limit_query = '';

        if ($_POST["length"] != -1) {
            $limit_query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }

        $object->query = $main_query . $search_query . $order_query;

        $object->execute();

        $filtered_rows = $object->row_count();

        $object->query .= $limit_query;

        $result = $object->get_result();

        $object->query = $main_query;

        $object->execute();

        $total_rows = $object->row_count();

        $data = array();

        foreach ($result as $row) {
            $sub_array = array();
            $sub_array[] = $row["student_first_name"];
            $sub_array[] = $row["student_last_name"];
            $sub_array[] = $row["student_email_address"];
            $sub_array[] = $row["studentID"];
            $status = '';
            if ($row["email_verify"] == 'Yes') {
                $status = '<span class="badge badge-success">Yes</span>';
            } else {
                $status = '<span class="badge badge-danger">No</span>';
            }
            $sub_array[] = $status;
            $sub_array[] = '
			<div align="center">
			<button type="button" name="view_button" class="btn btn-info btn-circle btn-sm view_button" data-id="' . $row["student_id"] . '"><i class="fas fa-eye"></i></button>
			<button type="button" name="edit_button" class="btn btn-warning btn-circle btn-sm edit_button" data-id="' . $row["student_id"] . '"><i class="fas fa-edit"></i></button>
			<button type="button" name="delete_button" class="btn btn-danger btn-circle btn-sm delete_button" data-id="' . $row["student_id"] . '"><i class="fas fa-times"></i></button>
			</div>
			';
            $data[] = $sub_array;
        }

        $output = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $total_rows,
            "recordsFiltered" => $filtered_rows,
            "data" => $data
        );

        echo json_encode($output);

    }

    /*if($_POST["action"] == 'Add')
    {
        $error = '';

        $success = '';

        $data = array(
            ':teacher_email_address'	=>	$_POST["teacher_email_address"]
        );

        $object->query = "
        SELECT * FROM teacher_table
        WHERE teacher_email_address = :teacher_email_address
        ";

        $object->execute($data);

        if($object->row_count() > 0)
        {
            $error = '<div class="alert alert-danger">Email Address Already Exists</div>';
        }
        else
        {
            $teacher_profile_image = '';
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
            else
            {
                $character = $_POST["teacher_name"][0];
                $path = "../images/". time() . ".png";
                $image = imagecreate(200, 200);
                $red = rand(0, 255);
                $green = rand(0, 255);
                $blue = rand(0, 255);
                imagecolorallocate($image, 230, 230, 230);
                $textcolor = imagecolorallocate($image, $red, $green, $blue);
                imagettftext($image, 100, 0, 55, 150, $textcolor, '../font/arial.ttf', $character);
                imagepng($image, $path);
                imagedestroy($image);
                $teacher_profile_image = $path;
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
                    ':teacher_expert_in'				=>	$object->clean_input($_POST["teacher_expert_in"]),
                    ':teacher_status'				=>	'Active',
                    ':teacher_added_on'				=>	$object->now
                );

                $object->query = "
                INSERT INTO teacher_table
                (teacher_email_address, teacher_password, teacher_name, teacher_profile_image, teacher_phone_no, teacher_address, teacher_date_of_birth, teacher_degree, teacher_expert_in, teacher_status, teacher_added_on)
                VALUES (:teacher_email_address, :teacher_password, :teacher_name, :teacher_profile_image, :teacher_phone_no, :teacher_address, :teacher_date_of_birth, :teacher_degree, :teacher_expert_in, :teacher_status, :teacher_added_on)
                ";

                $object->execute($data);

                $success = '<div class="alert alert-success">Teacher Added</div>';
            }
        }

        $output = array(
            'error'		=>	$error,
            'success'	=>	$success
        );

        echo json_encode($output);

    }*/

    if ($_POST["action"] == 'fetch_single') {
        $object->query = "
		SELECT * FROM student_table 
		WHERE student_id = '" . $_POST["student_id"] . "'
		";

        $result = $object->get_result();

        $data = array();

        foreach ($result as $row) {
            $data['student_email_address'] = $row['student_email_address'];
            $data['student_password'] = $row['student_password'];
            $data['student_first_name'] = $row['student_first_name'];
            $data['student_last_name'] = $row['student_last_name'];
            $data['student_date_of_birth'] = $row['student_date_of_birth'];
            $data['student_gender'] = $row['student_gender'];
            $data['student_major'] = $row['student_major'];
            $data['studentID'] = $row['studentID'];
            $data['student_year'] = $row['student_year'];
            if ($row['email_verify'] == 'Yes') {
                $data['email_verify'] = '<span class="badge badge-success">Yes</span>';
            } else {
                $data['email_verify'] = '<span class="badge badge-danger">No</span>';
            }
        }

        echo json_encode($data);
    }

    /*if($_POST["action"] == 'Edit')
    {
        $error = '';

        $success = '';

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
            'error'		=>	$error,
            'success'	=>	$success
        );

        echo json_encode($output);

    }

    if($_POST["action"] == 'change_status')
    {
        $data = array(
            ':teacher_status'		=>	$_POST['next_status']
        );

        $object->query = "
        UPDATE teacher_table
        SET teacher_status = :teacher_status
        WHERE teacher_id = '".$_POST["id"]."'
        ";

        $object->execute($data);

        echo '<div class="alert alert-success">Class Status change to '.$_POST['next_status'].'</div>';
    }

    if($_POST["action"] == 'delete')
    {
        $object->query = "
        DELETE FROM teacher_table
        WHERE teacher_id = '".$_POST["id"]."'
        ";

        $object->execute();

        echo '<div class="alert alert-success">Teacher Data Deleted</div>';
    }*/
}

?>