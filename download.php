<?php

//download.php

include('class/Appointment.php');

$object = new Appointment;

require_once('class/pdf.php');

if(isset($_GET["id"]))
{
	$html = '<table border="0" cellpadding="5" cellspacing="5" width="100%">';

	$object->query = "
	SELECT school_name, school_address, school_contact_no, school_logo 
	FROM admin_table
	";

	$hospital_data = $object->get_result();

	foreach($hospital_data as $hospital_row)
	{
		$html .= '<tr><td align="center">';
		if($hospital_row['school_logo'] != '')
		{
			$html .= '<img src="'.substr($hospital_row['school_logo'], 3).'" /><br />';
		}
		$html .= '<h2 align="center">'.$hospital_row['school_name'].'</h2>
		<p align="center">'.$hospital_row['school_address'].'</p>
		<p align="center"><b>Student ID. - </b>'.$hospital_row['school_contact_no'].'</p></td></tr>
		';
	}

	$html .= "
	<tr><td><hr /></td></tr>
	<tr><td>
	";

	$object->query = "
	SELECT * FROM appointment_table 
	WHERE appointment_id = '".$_GET["id"]."'
	";

	$appointment_data = $object->get_result();

	foreach($appointment_data as $appointment_row)
	{

		$object->query = "
		SELECT * FROM student_table 
		WHERE student_id = '".$appointment_row["student_id"]."'
		";

		$student_data = $object->get_result();

		$object->query = "
		SELECT * FROM teacher_schedule_table 
		INNER JOIN teacher_table 
		ON teacher_table.teacher_id = teacher_schedule_table.teacher_id 
		WHERE teacher_schedule_table.teacher_schedule_id = '".$appointment_row["teacher_schedule_id"]."'
		";

		$teacher_schedule_data = $object->get_result();
		
		$html .= '
		<h4 align="center">Student Details</h4>
		<table border="0" cellpadding="5" cellspacing="5" width="100%">';

		foreach($student_data as $student_row)
		{
			$html .= '<tr><th width="50%" align="right">Student Name</th><td>'.$student_row["student_first_name"].' '.$student_row["student_last_name"].'</td></tr>
			<tr><th width="50%" align="right">Student ID.</th><td>'.$student_row["studentID"].'</td></tr>
			<tr><th width="50%" align="right">Student Major</th><td>'.$student_row["student_major"].'</td></tr>';
		}

		$html .= '</table><br /><hr />
		<h4 align="center">Appointment Details</h4>
		<table border="0" cellpadding="5" cellspacing="5" width="100%">
			<tr>
				<th width="50%" align="right">Appointment No.</th>
				<td>'.$appointment_row["appointment_number"].'</td>
			</tr>
		';
		foreach($teacher_schedule_data as $teacher_schedule_row)
		{
			$html .= '
			<tr>
				<th width="50%" align="right">Teacher Name</th>
				<td>'.$teacher_schedule_row["teacher_name"].'</td>
			</tr>
			<tr>
				<th width="50%" align="right">Appointment Date</th>
				<td>'.$teacher_schedule_row["teacher_schedule_date"].'</td>
			</tr>
			<tr>
				<th width="50%" align="right">Appointment Day</th>
				<td>'.$teacher_schedule_row["teacher_schedule_day"].'</td>
			</tr>
				
			';
		}

		$html .= '
			<tr>
				<th width="50%" align="right">Appointment Time</th>
				<td>'.$appointment_row["start_time"].'</td>
			</tr>
			<tr>
				<th width="50%" align="right">Reason for Appointment</th>
				<td>'.$appointment_row["reason_for_appointment"].'</td>
			</tr>
			<tr>
				<th width="50%" align="right">Student come into School</th>
				<td>'.$appointment_row["student_come_into_school"].'</td>
			</tr>
			<tr>
				<th width="50%" align="right">Teacher Comment</th>
				<td>'.$appointment_row["teacher_comment"].'</td>
			</tr>
		</table>
			';
	}

	$html .= '
			</td>
		</tr>
	</table>';

	echo $html;

	$pdf = new Pdf();

	$pdf->loadHtml($html, 'UTF-8');
	$pdf->render();
	ob_end_clean();
	//$pdf->stream($_GET["id"] . '.pdf', array( 'Attachment'=>1 ));
	$pdf->stream($_GET["id"] . '.pdf', array( 'Attachment'=>false ));
	exit(0);

}

?>