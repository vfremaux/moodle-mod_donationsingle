<?php

	//get useful data
	$amount = get_field('donationsingle_donator', 'amount', 'donationid', $donationsingle->id, 'userid', $USER->id);
	$amountraised = get_field('donationsingle', 'amountraised', 'id', $donationsingle->id);

	//update the records 
	$donation->id = $donationsingle->id;
	$donation->amountraised = $amountraised - $amount;
	update_record('donationsingle', $donation);

	//delete the records 
	delete_records('donationsingle_donator', 'donationid', $donationsingle->id, 'userid', $USER->id);
	delete_records('donationsingle_history', 'donationid', $donationsingle->id, 'userid', $USER->id);

?>
