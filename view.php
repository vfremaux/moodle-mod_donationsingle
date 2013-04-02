<?php  // $Id: view.php,v 1.2 2012-12-15 14:17:17 vf Exp $
/**
 * This page prints a particular instance of donation
 * 
 * @author 
 * @version $Id: view.php,v 1.2 2012-12-15 14:17:17 vf Exp $
 * 
 **/

	require_once("../../config.php");
	require_once($CFG->dirroot."/mod/donationsingle/lib.php");
	require_once($CFG->dirroot."/mod/donationsingle/locallib.php");

	$usehtmleditor = false;
	$editorfields = '';

/// Check for required parameters - Course Module Id, donationID, 
    $id     = optional_param('id', 0, PARAM_INT); // Course Module ID
    $a      = optional_param('a', 0, PARAM_INT);  // donationsingle ID
    $userid = optional_param('userid', 0, PARAM_INT); // User ID

// PART OF MVC Implementation

    $action = optional_param('what', '', PARAM_ALPHA);
    $view   = optional_param('view', '', PARAM_ALPHA);


    if ($id) {
        if (! $cm = get_coursemodule_from_id('donationsingle', $id)) {
            print_error('invalidcoursemodule');
        }
        if (! $course = $DB->get_record("course", array("id" => $cm->course))) {
            print_error('coursemisconf');
        }
        if (! $donationsingle = $DB->get_record("donationsingle", array("id" => $cm->instance))) {
            print_error('invaliddonationsingleid', 'donationsingle');
        }

    } else {
        if (! $donationsingle = $DB->get_record('donationsingle', array('id' => $a))) {
            print_error('invaliddonationsingleid', 'donationsingle');
        }
        if (! $course = $DB->get_record('course', array('id' => $donationsingle->course))) {
            print_error('coursemisconf');
        }
        if (! $cm = get_coursemodule_from_instance('donationsingle', $donationsingle->id, $course->id)) {
            print_error('invalidcoursemodule');
        }
    }

    require_login($course->id);

    add_to_log($course->id, 'donationsingle', 'view', "view.php?id=$cm->id", "$donationsingle->id");

/// Print the page header

    if ($course->category) {
        $navigation = "<a href=\"../../course/view.php?id=$course->id\">$course->shortname</a> ->";
    } else {
        $navigation = '';
    }

    $strdonationsingles = get_string('modulenameplural', 'donationsingle');
    $strdonationsingle  = get_string('modulename', 'donationsingle');

	$url = $CFG->wwwroot.'/mod/donationsingle/view.php';
    $PAGE->set_title(format_string($donationsingle->name));
    $PAGE->set_heading("$course->fullname");
    $PAGE->set_url($url);
    /* SCANMSG: may be additional work required for $navigation variable */
    $PAGE->set_focuscontrol('');
    $PAGE->set_cacheable(true);
    $PAGE->set_headingmenu(navmenu($course, $cm));
    $PAGE->set_button($OUTPUT->update_module_button($cm->id, 'donationsingle'));

	$currency_options = array(
        					0 => get_string('dollars', 'donationsingle'),
        					1 => get_string('euros', 'donationsingle'),
        					2 => get_string('pounds', 'donationsingle')
					    );
	$currency = $currency_options[$donationsingle->currency] ;

	$privacyoptions = array	(
							0 => get_string('publicstrict', 'donationsingle'),
							1 => get_string('public', 'donationsingle'),
							2 => get_string('private', 'donationsingle')
					  );
	$privacy = $privacyoptions[$donationsingle->idview];

//PART OF MVC IMPLEMENTATION

///memorize current view - typical session switch

    if (!empty($view)){
        $_SESSION['currentview'] = $view;
    } elseif(empty($_SESSION['currentview'])) {
        $_SESSION['currentview'] = 'description';
    }
    $view = $_SESSION['currentview'];

/// Print tabs with options for user

    $rows[0][] = new tabobject('description', "view.php?id={$cm->id}&amp;view=description", get_string('viewcampaign', 'donationsingle'));
    if(donationsingle_has_already_made_donation($donationsingle->id, $USER->id)){
    	$rows[0][] = new tabobject('viewmydonation', "view.php?id={$cm->id}&amp;view=mydonation", get_string('mydonation', 'donationsingle'));
    } else {
    	$rows[0][] = new tabobject('donate', "view.php?id={$cm->id}&amp;view=donate", get_string('makedonation', 'donationsingle'));
    }
    $context = context_module::instance($cm->id); 
    if(!donationsingle_isprivate($donationsingle->id) || has_capability('mod/donationsingle:manage', $context)){
    	$rows[0][] = new tabobject('listdonators', "view.php?id={$cm->id}&amp;view=listdonators", get_string('details', 'donationsingle')); 
    }
    if (has_capability('mod/donationsingle:manage', $context)){
    	$rows[0][] = new tabobject('history', "view.php?id={$cm->id}&amp;view=history", get_string('history', 'donationsingle')); 
    }

    $selected = null;
    $activated = null;
    if (!empty($page)){
        $selected = $page;
        $activated = array($view);
    }

    if ($action != ''){
        include "views/view.controller.php";
    }

	echo $OUTPUT->header();
	echo '<center>';
	echo '<table width="100%"><tr><td align="center">';
	print_tabs($rows, $selected, '', $activated);
	echo '</td></tr><tr><td>';

	include 'views/'.$view.'.php';
	
	echo '</td></tr></table></center>';

/// Finish the page
    echo $OUTPUT->footer($course);

?>
