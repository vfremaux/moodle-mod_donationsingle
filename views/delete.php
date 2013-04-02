<?php

	//get useful data
	$amount = $DB->get_field('donationsingle_donator', 'amount', array('donationid' => $donationsingle->id, 'userid' => $USER->id));
	$amountraised = $DB->get_field('donationsingle', 'amountraised', array('id' => $donationsingle->id));

	//update the records 
	$DB->set_field('donationsingle', 'amountraised', $amountraised - $amount, array('id' => $donationsingle->id));

	//delete the records 
	$DB->delete_records('donationsingle_donator', array('donationid' => $donationsingle->id, 'userid' => $USER->id));
	$DB->delete_records('donationsingle_history', array('donationid' => $donationsingle->id, 'userid' => $USER->id));
	
	echo format_text($donationsingle->dismisstext){
	}

?>
