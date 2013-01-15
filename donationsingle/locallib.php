<?php

define ('PUBLICITY_PUBLIC_FORCED', 0);
define ('PUBLICITY_PUBLIC', 1);
define ('PUBLICITY_PRIVATE', 2);

/**
 * Submits a donation by inserting a new record
 * in both the donationsingle_donator table first and
 * then the donationsingle table.
 */
function donationsingle_submitdonation(&$donationsingle){
	global $DB, $CFG, $cm;

	$amountraised = $DB->get_field('donationsingle', 'amountraised', array('id' => $donationsingle->id));
	$amountneeded = $DB->get_field('donationsingle', 'amountneeded', array('id' => $donationsingle->id));
	$maximum = $amountneeded - $amountraised;

	$amount = required_param('amount', PARAM_INT);
	//check if amount is acceptable
	if($amount > $maximum || $amount < 1){
		redirect("view.php?id={$cm->id}view=view&amp;page=invalid", "", $delay = 0);
	}
	$userid = required_param('userid', PARAM_INT);

	$donation = new StdClass;

	if(!donationsingle_isprivate($donationsingle->id)){
		$visible = optional_param('visible', 0, PARAM_INT); 
		if($visible == 1){
			$donation->visible = 1;
		} else { 
			$donation->visible = 0;
		}
	}
	if (empty($donation->reminder)){
		$donation->reminder = 0;
	}
	
	// register donator

	$donator->datereported = time();
	$donator->donationid = $donationsingle->id;
	$donator->userid = $userid;
	$donator->id = $DB->insert_record('donationsingle_donator', $donator);
	
	// register donation update
	

	$donation->managerid = $DB->get_field('donationsingle', 'managerid', array('id' => $donationsingle->id));
	$donation->name = $DB->get_field('donationsingle', 'name', array('id' => $donationsingle->id));

	if(donationsingle_managerwantsnotifications($donationsingle->id)){
		donationsingle_notify_donation($donation, $cm);
	}
	
	$donation2 = new StdClass();
	$donation2->datereported = $datereported;
	$donation2->amount = $amount;
	$donation2->donationid = $donationsingle->id;
	$donation2->userid = $userid;
	$donation2->reminder = 0;
	$donation2->id = $DB->insert_record('donationsingle_history', $donation2);

	if($donationsingle->id){
		/**
		 * Here we insert the new amount raised
		 * in the table donationsingle in the field amountraised.
		 */
		$oldamountraised = $DB->get_field('donationsingle', 'amountraised', array('id' => $donationsingle->id));
		$donationsingle->amountraised = $oldamountraised + $amount;
		$DB->set_field('donationsingle', 'amountraised', $donationsingle->amountraised, array('id' => $donationsingle->id));
	} else {
		print_error('errorsubmission', 'donationsingle');
	}

	return -1;
}

/**
 * Edit a donation by updating the new value
 * in both donationsingle_donator and
 * donationsingle tables.
 */
function donationsingle_editdonation($donationid){
	global $DB, $CFG, $cm;

	$amountraised = $DB->get_field('donationsingle', 'amountraised', array('id' => $donationid));
	$amountneeded = $DB->get_field('donationsingle', 'amountneeded', array('id' => $donationid));
	$maximum = $amountneeded - $amountraised;

	$amount = required_param('amount', PARAM_INT);
	//check if amount is acceptable
	if($amount < 1) redirect("view.php?id={$cm->id}view=view&amp;page=confirmation", "", $delay=0);
	if($amount > $maximum){
		redirect("view.php?id={$cm->id}view=view&amp;page=invalid", "", $delay=0);
	}else{
		$userid = required_param('userid', PARAM_INT);
		$datereported = required_param('datereported', PARAM_INT);
		$neededid = $DB->get_field('donationsingle_donator', 'id', array('donationid' => $donationid, 'userid' => $userid));
		$donation->id = $neededid;
		$donation->amount = $amount;
		$donation->donationid = $donationid;
		$donation->userid = $userid;
		$oldamountgiven = $DB->get_field('donationsingle_donator', 'amount', array('donationid' => $donationid, 'userid' => $userid));
		$DB->update_record('donationsingle_donator', $donation, true);

		$donation->managerid = $DB->get_field('donationsingle', 'managerid', array('id' => $donationid));
		$donation->name = $DB->get_field('donationsingle', 'name', array('id' => $donationid));

		if(donationsingle_managerwantsnotifications($donationid)){
			donationsingle_notify_donation($donation, $cm);
		}

		$donation2->datereported = $datereported;
		$donation2->amount = $amount;
		$donation2->donationid = $donationid;
		$donation2->userid = required_param('userid', PARAM_INT);
		$donation2->id = $DB->insert_record('donationsingle_history', $donation2);

		/**
		 * Here we insert insert the new amountraised
		 * in the table donationsingle in the field amountraised.
		 */
		$donationsd->id = $donationid;
		$oldamountraised = $DB->get_field('donationsingle', 'amountraised', array('id' => $donationid));
		$amountraised = $oldamountraised + $amount - $oldamountgiven;
		$donationsd->amountraised = $amountraised;
		$DB->update_record('donationsingle', $donationsd, true);
		return $donationsd;
	}

}

/**
 * this is a function to know if a user 
 * already made a donation.
 */
function donationsingle_has_already_made_donation($donationid, $userid){
	global $DB;
	
	$result = $DB->count_records('donationsingle_donator', array('donationid' => $donationid, 'userid' => $userid));
	return $result;
}
/**
 * tells if a user is a campaign manager
 */
function donationsingle_ismanager($donationid, $userid){
	global $DB;
	
	$managerid = $DB->get_field('donationsingle', 'managerid', array('id' => $donationid));
	return $managerid==$userid;
}

/**
 * tells if this campaign is private
 * @param object $donationid
 */
function donationsingle_isprivate($donationid){
	global $DB;
	
	$result = $DB->get_field('donationsingle', 'idview', array('id' => $donationid));
	if ($result == 2) return true;
	return false;
}

/**
 * tells if this campaign is public strict
 * @param object $donationid
 */
function donationsingle_ispublicstrict($donationid){
	global $DB;
	
	$result = $DB->get_field('donationsingle', 'idview', array('id' => $donationid));
	if ($result == 0) return true;
	return false;
}

/**
 * tells if a user is an anonymous donator for this campaign
 * @param object $donationid
 */
function donationsingle_isanonymoususer($userid){
	global $DB;
	
	$result = $DB->get_field('donationsingle_donator', 'visible', array('userid' => $userid));
	if($result == 0) return true;
	return false;
}

/**
 * tells if the manager wants to receive notifications
 * @param object $donation
 */
function donationsingle_managerwantsnotifications($donationid){
	global $DB;
	
	$result = $DB->get_field('donationsingle', 'managernotification', array('id' => $donationid));
	if($result == 0) return false;
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
    global $COURSE, $SITE, $CFG, $USER, $DB;

    if (empty($donation)){ // database access optimization in case we have a donation from somewhere else
        $donation = $DB->get_record('donationsingle', array('id' => $donation->id));
    }
    $context = context_module::instance($cm->id);
    $managers = get_users_by_capability($context, 'mod/tracker:manage', 'u.id,firstname,lastname,email,emailstop,mailformat', 'lastname');

	$currency_options = array(
					0 => get_string('dollarsign', 'donationsingle'),
					1 => get_string('eurossign', 'donationsingle'),
					2 => get_string('poundsign', 'donationsingle')
					);
	$i = $DB->get_field('donationsingle', 'currency', array('id' => $donation->donationid));
	$currency = $currency_options[$i] ;

    $by = $DB->get_record('user', array('id' => $USER->id));
    if (!empty($managers)){
        $vars = array('COURSE_SHORT' => $COURSE->shortname, 
                      'COURSENAME' => format_string($COURSE->fullname), 
                      'DONATIONNAME' => format_string($donation->name), 
					  'AMOUNT' => format_string($donation->amount),
					  'CURRENCY' => format_string($currency),
                      'DONATOR' => fullname($by)
					 );
        include_once("$CFG->dirroot/mod/donationsingle/mailtemplatelib.php");
        $manager = $DB->get_record('user', array('id' => $donation->managerid));
        $notification = compile_mail_template('submission', $vars);
        $notification_html = compile_mail_template('submission_html', $vars);
        if ($CFG->debugsmtp) echo "Sending Submission Mail Notification to " . fullname($manager) . '<br/>'.$notification_html;
        email_to_user($manager, $USER, get_string('submission', 'donationsingle', $SITE->shortname.':'.format_string($donation->name)), $notification, $notification_html);
    }
}

function donationsingle_print_arrow($donator){
	global $CFG, $DB;

	$sql = "
        SELECT   
        	i.id,
			i.amount
        FROM 
            {donationsingle_history} as i 
        WHERE 
            i.donationid = {$donator->donationid} AND
			i.userid = $donator->id
		ORDER BY
			i.datereported DESC
    ";
	$donations = $DB->get_records_sql($sql);

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

