<?php

/**
 * Submits a donation by inserting a new record
 * in both the donationsingle_donator table first and
 * then the donationsingle table.
 */
function donationsingle_submitdonation($donationid){


	global $db, $CFG, $cm;

	$amountraised = get_field('donationsingle', 'amountraised', 'id', $donationid);
	$amountneeded = get_field('donationsingle', 'amountneeded', 'id', $donationid);
	$maximum = $amountneeded - $amountraised;

	$amount = required_param('amount', PARAM_INT);
	//check if amount is acceptable
	if($amount > $maximum || $amount < 1){
		redirect("view.php?id={$cm->id}view=view&amp;page=invalid", "", $delay=0);
	}
	$datereported = required_param('datereported', PARAM_INT);
	$userid = required_param('userid', PARAM_INT);
	if(!donationsingle_isprivate($donationid)){
		$visible = optional_param('visible', PARAM_INT); 
		if($visible==1) $donation->visible = 1;
		else $donation->visible = 0;
	}
	$donation->datereported = $datereported;
	$donation->amount = $amount;
	$donation->donationid = $donationid;
	$donation->userid = $userid;
	$donation->id = insert_record('donationsingle_donator', $donation, true);

	$donation->managerid = get_field('donationsingle', 'managerid', 'id', $donationid);
	$donation->name = get_field('donationsingle', 'name', 'id', $donationid);

	if(donationsingle_managerwantsnotifications($donationid)){
		donationsingle_notify_donation($donation, &$cm);
	}
	$donation2->datereported = $datereported;
	$donation2->amount = $amount;
	$donation2->donationid = $donationid;
	$donation2->userid = $userid;
	$donation2->id = insert_record('donationsingle_history', $donation2, true);

	if($donation->id){
		/**
		 * Here we insert the new amountraised
		 * in the table donationsingle in the field amountraised.
		 */
		$donationsd->id = $donationid;
		$oldamountraised = get_field('donationsingle', 'amountraised', 'id', $donationid);
		$donationsd->amountraised = $oldamountraised + $amount;
		update_record('donationsingle', $donationsd, true);
		return $donationsd;
	}
	else{
		error("Could not submit donation");
	}

	return -1;
}

/**
 * Edit a donation by updating the new value
 * in both donationsingle_donator and
 * donationsingle tables.
 */
function donationsingle_editdonation($donationid){


	global $db, $CFG, $cm;

	$amountraised = get_field('donationsingle', 'amountraised', 'id', $donationid);
	$amountneeded = get_field('donationsingle', 'amountneeded', 'id', $donationid);
	$maximum = $amountneeded - $amountraised;

	$amount = required_param('amount', PARAM_INT);
	//check if amount is acceptable
	if($amount < 1) redirect("view.php?id={$cm->id}view=view&amp;page=confirmation", "", $delay=0);
	if($amount > $maximum){
		redirect("view.php?id={$cm->id}view=view&amp;page=invalid", "", $delay=0);
	}else{
		$userid = required_param('userid', PARAM_INT);
		$datereported = required_param('datereported', PARAM_INT);
		$neededid = get_field('donationsingle_donator', 'id', 'donationid', $donationid, 'userid', $userid);
		$donation->id = $neededid;
		$donation->amount = $amount;
		$donation->donationid = $donationid;
		$donation->userid = $userid;
		$oldamountgiven = get_field('donationsingle_donator', 'amount', 'donationid', $donationid, 'userid', $userid);
		update_record('donationsingle_donator', $donation, true);

		$donation->managerid = get_field('donationsingle', 'managerid', 'id', $donationid);
		$donation->name = get_field('donationsingle', 'name', 'id', $donationid);

		if(donationsingle_managerwantsnotifications($donationid)){
			donationsingle_notify_donation($donation, &$cm);
		}

		$donation2->datereported = $datereported;
		$donation2->amount = $amount;
		$donation2->donationid = $donationid;
		$donation2->userid = required_param('userid', PARAM_INT);
		$donation2->id = insert_record('donationsingle_history', $donation2, true);

		/**
		 * Here we insert insert the new amountraised
		 * in the table donationsingle in the field amountraised.
		 */
		$donationsd->id = $donationid;
		$oldamountraised = get_field('donationsingle', 'amountraised', 'id', $donationid);
		$amountraised = $oldamountraised + $amount - $oldamountgiven;
		$donationsd->amountraised = $amountraised;
		update_record('donationsingle', $donationsd, true);
	
		return $donationsd;
	}

}

/**
 * this is a function to know if a user 
 * already made a donation.
 */
function donationsingle_has_already_made_donation($donationid, $userid){
	
	$result = count_records('donationsingle_donator', 'donationid', $donationid, 'userid', $userid);
	return $result;
}
/**
 * tells if a user is a campaign manager
 */
function donationsingle_ismanager($donationid, $userid){
	$managerid = get_field('donationsingle', 'managerid', 'id', $donationid);
	return $managerid==$userid;
}

/**
 * tells if this campaign is private
 * @param object $donationid
 */
function donationsingle_isprivate($donationid){
	$result = get_field('donationsingle', 'idview', 'id', $donationid);
	if($result==2) return true;
	return false;
}

/**
 * tells if this campaign is public strict
 * @param object $donationid
 */
function donationsingle_ispublicstrict($donationid){
	$result = get_field('donationsingle', 'idview', 'id', $donationid);
	if($result==0) return true;
	return false;
}

/**
 * tells if a user is an anonymous donator for this campaign
 * @param object $donationid
 */
function donationsingle_isanonymoususer($userid){
	$result = get_field('donationsingle_donator', 'visible', 'userid', $userid);
	if($result==0) return true;
	return false;
}

/**
 * tells if the manager wants to receive notifications
 * @param object $donation
 */
function donationsingle_managerwantsnotifications($donationid){
	$result = get_field('donationsingle', 'managernotification', 'id', $donationid);
	if($result==0) return false;
	return true;
}

/**
* sends required notifications by the watchers when first submit
* @uses COURSE
* @param object $issue
* @param object $cm
* @param object $donationsingle
*/
function donationsingle_notify_donation($donation, &$cm){
    global $COURSE, $SITE, $CFG, $USER;

    if (empty($donation)){ // database access optimization in case we have a donation from somewhere else
        $donation = get_record('donationsingle', 'id', $donation->id);
    }
    $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    $managers = get_users_by_capability($context, 'mod/tracker:manage', 'u.id,firstname,lastname,email,emailstop,mailformat', 'lastname');

	$currency_options = array(
					0 => get_string('dollarsign', 'donationsingle'),
					1 => get_string('eurossign', 'donationsingle'),
					2 => get_string('poundsign', 'donationsingle')
					);
	$i = get_field('donationsingle', 'currency', 'id', $donation->donationid);
	$currency = $currency_options[$i] ;

    $by = get_record('user', 'id', $USER->id);
    if (!empty($managers)){
        $vars = array('COURSE_SHORT' => $COURSE->shortname, 
                      'COURSENAME' => format_string($COURSE->fullname), 
                      'DONATIONNAME' => format_string($donation->name), 
					  'AMOUNT' => format_string($donation->amount),
					  'CURRENCY' => format_string($currency),
                      'DONATOR' => fullname($by)
					 );
        include_once("$CFG->dirroot/mod/donationsingle/mailtemplatelib.php");
        $manager = get_record('user', 'id', $donation->managerid);
        $notification = compile_mail_template('submission', $vars);
        $notification_html = compile_mail_template('submission_html', $vars);
        if ($CFG->debugsmtp) echo "Sending Submission Mail Notification to " . fullname($manager) . '<br/>'.$notification_html;
        email_to_user($manager, $USER, get_string('submission', 'donationsingle', $SITE->shortname.':'.format_string($donation->name)), $notification, $notification_html);
        
    }
}

function donationsingle_print_arrow($donator){

	global $CFG;

	$sql = "
        SELECT   
			i.amount
        FROM 
            {$CFG->prefix}donationsingle_history as i 
        WHERE 
            i.donationid = {$donator->donationid} AND
			i.userid = $donator->userid
		ORDER BY
			i.datereported DESC
    ";
		
	$donations = get_records_sql($sql);

	$north_east_arrow = "<img src=\"{$CFG->wwwroot}/mod/donationsingle/pics/north_east_arrow.gif\" height=\"15\" width=\"15\" >";
	$south_east_arrow = "<img src=\"{$CFG->wwwroot}/mod/donationsingle/pics/south_east_arrow.gif\" height=\"15\" width=\"15\" >";

	$amount = 0;
	$i = 0;
	foreach($donations as $donation){
		if($i>0){
			if($donation->amount < $amount) return $north_east_arrow;
			return $south_east_arrow;
		}
		$amount = $donation->amount;
		$i++;
	}
	return;

}	
?>

