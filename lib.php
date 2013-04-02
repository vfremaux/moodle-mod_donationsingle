<?php  // $Id: lib.php,v 1.4 2011-09-02 08:51:05 vf Exp $
/**
 * Library of functions and constants for module donationsingle
 *
 * @author 
 * @version $Id: lib.php,v 1.4 2011-09-02 08:51:05 vf Exp $
 * @package donationsingle
 **/
define("REMIND_SENT_15", "0x10");
define("REMIND_SENT_05", "0x08");
define("REMIND_SENT_03", "0x02");
define("REMIND_SENT_02", "0x01");

/**
 * Given an object containing all the necessary data, 
 * (defined by the form in mod_form.php) this function 
 * will create a new instance and return the id number 
 * of the new instance.
 *
 * @param object $instance An object from the form in mod_form.php
 * @return int The id of the newly inserted donationsingle record
 **/
function donationsingle_add_instance($donationsingle) {
    
    $donationsingle->timemodified = time();
    $donationsingle->datereported = time();

    # May have to add extra stuff in here #
    
    return insert_record("donationsingle", $donationsingle);
}

/**
 * Given an object containing all the necessary data, 
 * (defined by the form in mod_form.php) this function 
 * will update an existing instance with new data.
 *
 * @param object $instance An object from the form in mod.html
 * @return boolean Success/Fail
 **/
function donationsingle_update_instance($donationsingle) {

    $donationsingle->timemodified = time();
    $donationsingle->id = $donationsingle->instance;

    return update_record("donationsingle", $donationsingle);
}

/**
 * Given an ID of an instance of this module, 
 * this function will permanently delete the instance 
 * and any data that depends on it. 
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 **/
function donationsingle_delete_instance($id) {

    if (! $donationsingle = get_record("donationsingle", "id", "$id")) {
        return false;
    }
	
    $result = true;

    # Delete any dependent records here #
	delete_records('donationsingle', 'id', $id);
	delete_records('donationsingle_donator', 'donationid', $id);
	delete_records('donationsingle_history', 'donationid', $id);

    return $result;
}

/**
 * Return a small object with summary information about what a 
 * user has done with a given particular instance of this module
 * Used for user activity reports.
 * $return->time = the time they did it
 * $return->info = a short text description
 *
 * @return null
 * @todo Finish documenting this function
 **/
function donationsingle_user_outline($course, $user, $mod, $donationsingle) {
    return $return;
}

/**
 * Print a detailed representation of what a user has done with 
 * a given particular instance of this module, for user activity reports.
 *
 * @return boolean
 * @todo Finish documenting this function
 **/
function donationsingle_user_complete($course, $user, $mod, $donationsingle) {
    return true;
}

/**
 * Given a course and a time, this module should find recent activity 
 * that has occurred in donationsingle activities and print it out. 
 * Return true if there was output, or false is there was none. 
 *
 * @uses $CFG
 * @return boolean
 * @todo Finish documenting this function
 **/
function donationsingle_print_recent_activity($course, $isteacher, $timestart) {
    global $CFG;

    return false;  //  True if anything was printed, otherwise false 
}

/**
 * Function to be run periodically according to the moodle cron
 * This function searches for things that need to be done, such 
 * as sending out mail, toggling flags etc ... 
 *
 * @uses $CFG
 * @return boolean
 * @todo Finish documenting this function
 **/
function donationsingle_cron () {
    global $CFG;

	if ($donations = get_records('donationsingle', '', '')){

		foreach($donations as $ds){
			if(!($ds->reminder & REMIND_SENT_15)){
				$ds->reminder = $ds->reminder | REMIND_SENT_15;
			}
			elseif(!($ds->reminder & REMIND_SENT_05)){
				$ds->reminder = $ds->reminder | REMIND_SENT_05;
			}
			elseif(!($ds->reminder & REMIND_SENT_03)){
				$ds->reminder = $ds->reminder | REMIND_SENT_03;
			}
			elseif(!($ds->reminder & REMIND_SENT_02)){
				$ds->reminder = $ds->reminder | REMIND_SENT_02;
			}
			update_record('donationsingle', addslashes_recursive($ds));
		}
	}
    return true;
}


/**
 * Must return an array of user records (all data) who are participants
 * for a given instance of donationsingle. Must include every user involved
 * in the instance, independent of his role (student, teacher, admin...)
 * See other modules as example.
 *
 * @param int $donationsingleid ID of an instance of this module
 * @return mixed boolean/array of students
 **/
function donationsingle_get_participants($donationid) {

	$participants = get_records('donationsingle_donator', 'donationid', $donationid);
	if(!empty($participants)) return $participants;
	return false;
}


//////////////////////////////////////////////////////////////////////////////////////
/// Any other donationsingle functions go here.  Each of them must have a name that 
/// starts with donationsingle_


?>
