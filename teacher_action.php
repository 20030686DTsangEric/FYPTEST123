<?php

//teacher_action.php

include('../class/Appointment.php');

$object = new Appointment;

if (isset($_POST["action"])) {
    if ($_POST["action"] == 'fetch') {
        $order_column = array('teacher_name', 'teacher_status');

        $output = array();

        $main_query = "
		SELECT * FROM teacher_table ";

        $search_query = '';

        if (isset($_POST["search"]["value"])) {
            $search_query .= 'WHERE teacher_email_address LIKE "%' . $_POST["search"]["value"] . '%" ';
            $search_query .= 'OR teacher_name LIKE "%' . $_POST["search"]["value"] . '%" ';
            $search_query .= 'OR teacher_phone_no LIKE "%' . $_POST["search"]["value"] . '%" ';
            $search_query .= 'OR teacher_date_of_birth LIKE "%' . $_POST["search"]["value"] . '%" ';
            $search_query .= 'OR teacher_degree LIKE "%' . $_POST["search"]["value"] . '%" ';
            $search_query .= 'OR teacher_expert_in LIKE "%' . $_POST["search"]["value"] . '%" ';
            $search_query .= 'OR teacher_status LIKE "%' . $_POST["search"]["value"] . '%" ';
        }

        if (isset($_POST["order"])) {
            $order_query = 'ORDER BY ' . $order_column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
        } else {
            $order_query = 'ORDER BY teacher_id DESC ';
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
            $sub_array[] = '<img src="' . $row["teacher_profile_image"] . '" class="img-thumbnail" width="75" />';
            $sub_array[] = $row["teacher_email_address"];
            $sub_array[] = $row["teacher_password"];
            $sub_array[] = $row["teacher_name"];
            $sub_array[] = $row["teacher_phone_no"];
            $sub_array[] = $row["teacher_expert_in"];
            $status = '';
            if ($row["teacher_status"] == 'Active') {
                $status = '<button type="button" name="status_button" class="btn btn-primary btn-sm status_button" data-id="' . $row["teacher_id"] . '" data-status="' . $row["teacher_status"] . '">Active</button>';
            } else {
                $status = '<button type="button" name="status_button" class="btn btn-danger btn-sm status_button" data-id="' . $row["teacher_id"] . '" data-status="' . $row["teacher_status"] . '">Inactive</button>';
            }
            $sub_array[] = $status;
            $sub_array[] = '
			<div align="center">
			<button type="button" name="view_button" class="btn btn-info btn-circle btn-sm view_button" data-id="' . $row["teacher_id"] . '"><i class="fas fa-eye"></i></button>
			<button type="button" name="edit_button" class="btn btn-warning btn-circle btn-sm edit_button" data-id="' . $row["teacher_id"] . '"><i class="fas fa-edit"></i></button>
			<button type="button" name="delete_button" class="btn btn-danger btn-circle btn-sm delete_button" data-id="' . $row["teacher_id"] . '"><i class="fas fa-times"></i></button>
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

    if ($_POST["action"] == 'Add') {
        $error = '';

        $success = '';

        $data = array(
            ':teacher_email_address' => $_POST["teacher_email_address"]
        );

        $object->query = "
		SELECT * FROM teacher_table 
		WHERE teacher_email_address = :teacher_email_address
		";

        $object->execute($data);

        if ($object->row_count() > 0) {
            $error = '<div class="alert alert-danger">Email Address Already Exists</div>';
        } else {
            $teacher_profile_image = '';
            if ($_FILES['teacher_profile_image']['name'] != '') {
                $allowed_file_format = array("jpg", "png");

                $file_extension = pathinfo($_FILES["teacher_profile_image"]["name"], PATHINFO_EXTENSION);

                if (!in_array($file_extension, $allowed_file_format)) {
                    $error = "<div class='alert alert-danger'>Upload valiid file. jpg, png</div>";
                } else if (($_FILES["teacher_profile_image"]["size"] > 2000000)) {
                    $error = "<div class='alert alert-danger'>File size exceeds 2MB</div>";
                } else {
                    $new_name = rand() . '.' . $file_extension;

                    $destination = '../images/' . $new_name;

                    move_uploaded_file($_FILES['teacher_profile_image']['tmp_name'], $destination);

                    $teacher_profile_image = $destination;
                }
            } else {
                $character = $_POST["teacher_name"][0];
                $path = "../images/" . time() . ".png";
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

            if ($error == '') {
                $data = array(
                    ':teacher_email_address' => $object->clean_input($_POST["teacher_email_address"]),
                    ':teacher_password' => $_POST["teacher_password"],
                    ':teacher_name' => $object->clean_input($_POST["teacher_name"]),
                    ':teacher_profile_image' => $teacher_profile_image,
                    ':teacher_phone_no' => $object->clean_input($_POST["teacher_phone_no"]),
                    ':teacher_address' => $object->clean_input($_POST["teacher_address"]),
                    ':teacher_date_of_birth' => $object->clean_input($_POST["teacher_date_of_birth"]),
                    ':teacher_degree' => $object->clean_input($_POST["teacher_degree"]),
                    ':teacher_expert_in' => $object->clean_input($_POST["teacher_expert_in"]),
                    ':teacher_status' => 'Active',
                    ':teacher_added_on' => $object->now
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
            'error' => $error,
            'success' => $success
        );

        echo json_encode($output);

    }

    if ($_POST["action"] == 'fetch_single') {
        $object->query = "
		SELECT * FROM teacher_table 
		WHERE teacher_id = '" . $_POST["teacher_id"] . "'
		";

        $result = $object->get_result();

        $data = array();

        foreach ($result as $row) {
            $data['teacher_email_address'] = $row['teacher_email_address'];
            $data['teacher_password'] = $row['teacher_password'];
            $data['teacher_name'] = $row['teacher_name'];
            $data['teacher_profile_image'] = $row['teacher_profile_image'];
            $data['teacher_phone_no'] = $row['teacher_phone_no'];
            $data['teacher_address'] = $row['teacher_address'];
            $data['teacher_date_of_birth'] = $row['teacher_date_of_birth'];
            $data['teacher_degree'] = $row['teacher_degree'];
            $data['teacher_expert_in'] = $row['teacher_expert_in'];
        }

        echo json_encode($data);
    }

    if ($_POST["action"] == 'Edit') {
        $error = '';

        $success = '';

        $data = array(
            ':teacher_email_address' => $_POST["teacher_email_address"],
            ':teacher_id' => $_POST['hidden_id']
        );

        $object->query = "
		SELECT * FROM teacher_table 
		WHERE teacher_email_address = :teacher_email_address 
		AND teacher_id != :teacher_id
		";

        $object->execute($data);

        if ($object->row_count() > 0) {
            $error = '<div class="alert alert-danger">Email Address Already Exists</div>';
        } else {
            $teacher_profile_image = $_POST["hidden_teacher_profile_image"];

            if ($_FILES['teacher_profile_image']['name'] != '') {
                $allowed_file_format = array("jpg", "png");

                $file_extension = pathinfo($_FILES["teacher_profile_image"]["name"], PATHINFO_EXTENSION);

                if (!in_array($file_extension, $allowed_file_format)) {
                    $error = "<div class='alert alert-danger'>Upload valiid file. jpg, png</div>";
                } else if (($_FILES["teacher_profile_image"]["size"] > 2000000)) {
                    $error = "<div class='alert alert-danger'>File size exceeds 2MB</div>";
                } else {
                    $new_name = rand() . '.' . $file_extension;

                    $destination = '../images/' . $new_name;

                    move_uploaded_file($_FILES['teacher_profile_image']['tmp_name'], $destination);

                    $teacher_profile_image = $destination;
                }
            }

            if ($error == '') {
                $data = array(
                    ':teacher_email_address' => $object->clean_input($_POST["teacher_email_address"]),
                    ':teacher_password' => $_POST["teacher_password"],
                    ':teacher_name' => $object->clean_input($_POST["teacher_name"]),
                    ':teacher_profile_image' => $teacher_profile_image,
                    ':teacher_phone_no' => $object->clean_input($_POST["teacher_phone_no"]),
                    ':teacher_address' => $object->clean_input($_POST["teacher_address"]),
                    ':teacher_date_of_birth' => $object->clean_input($_POST["teacher_date_of_birth"]),
                    ':teacher_degree' => $object->clean_input($_POST["teacher_degree"]),
                    ':teacher_expert_in' => $object->clean_input($_POST["teacher_expert_in"])
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
				WHERE teacher_id = '" . $_POST['hidden_id'] . "'
				";

                $object->execute($data);

                $success = '<div class="alert alert-success">Teacher Data Updated</div>';
            }
        }

        $output = array(
            'error' => $error,
            'success' => $success
        );

        echo json_encode($output);

    }

    if ($_POST["action"] == 'change_status') {
        $data = array(
            ':teacher_status' => $_POST['next_status']
        );

        $object->query = "
		UPDATE teacher_table 
		SET teacher_status = :teacher_status 
		WHERE teacher_id = '" . $_POST["id"] . "'
		";

        $object->execute($data);

        echo '<div class="alert alert-success">Class Status change to ' . $_POST['next_status'] . '</div>';
    }

    if ($_POST["action"] == 'delete') {
        $object->query = "
		DELETE FROM teacher_table 
		WHERE teacher_id = '" . $_POST["id"] . "'
		";

        $object->execute();

        echo '<div class="alert alert-success">Teacher Data Deleted</div>';
    }
}

?>