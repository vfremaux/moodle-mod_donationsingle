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
    $page   = optional_param('page', '', PARAM_ALPHA);
    $view   = optional_param('view', '', PARAM_ALPHA);


    if ($id) {
        if (! $cm = get_coursemodule_from_id('donationsingle', $id)) {
            error("Course Module ID was incorrect");
        }
    
        if (! $course = get_record('course', 'id', $cm->course)) {
            error("Course is misconfigured");
        }
    
        if (! $donationsingle = get_record('donationsingle', 'id', $cm->instance)) {
            error("Course module is incorrect");
        }

    } else {
        if (! $donationsingle = get_record('donationsingle', 'id', $a)) {
            error("Course module is incorrect");
        }
        if (! $course = get_record('course', 'id', $donationsingle->course)) {
            error("Course is misconfigured");
        }
        if (! $cm = get_coursemodule_from_instance('donationsingle', $donationsingle->id, $course->id)) {
            error("Course Module ID was incorrect");
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

    $navigation = build_navigation('', $cm);
    print_header_simple(format_string($donationsingle->name), '',
                  $navigation, '', '', true,
                  update_module_button($cm->id, $course->id, $strdonationsingle), navmenu($course, $cm));

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
        }
    elseif(empty($_SESSION['currentview'])){
        $_SESSION['currentview']='description';
        }
    $view = $_SESSION['currentview'];

///memorize current page - typical session switch

    if(!empty($page)){
        $_SESSION['currentpage']=$page;
    }else{
        $_SESSION['currentpage']= 'description';
    }
    $page = $_SESSION['currentpage'];
?>
<center>
<table width="100%">
    <tr>
        <td align="center">
<?php 

/// Print tabs with options for user

    $rows[0][] = new tabobject('viewcampaign', "view.php?id={$cm->id}&amp;view=view&amp;page=description", get_string('viewcampaign', 'donationsingle'));
    if(donationsingle_has_already_made_donation($donationsingle->id, $USER->id)){
    	$rows[0][] = new tabobject('viewmydonation', "view.php?id={$cm->id}&amp;view=view&amp;page=mydonation", get_string('mydonation', 'donationsingle'));
    } else {
    	$rows[0][] = new tabobject('makedonation', "view.php?id={$cm->id}&amp;view=donation", get_string('makedonation', 'donationsingle'));
    }
    $context = get_context_instance(CONTEXT_MODULE, $cm->id); 
    if(!donationsingle_isprivate($donationsingle->id) || has_capability('mod/donationsingle:manage', $context)){
    	$rows[0][] = new tabobject('details', "view.php?id={$cm->id}&amp;view=view&amp;page=manager", get_string('details', 'donationsingle')); 
    }

    $selected = null;
    $activated = null;
    if (!empty($page)){
        $selected = $page;
        $activated = array($view);
    }

    print_tabs($rows, $selected, '', $activated);

?>

		</td>
    </tr>	
	<tr>
		<td>
<?php
/// Print the main part of the page
if ($view == 'donation'){
	
		$page = '';
        include "views/makedonation.html";

}
else if ($view == 'view'){
	$result = 0 ;
    if ($action != ''){
        $result = include "views/view.controller.php";
    }
	if($result != -1){
		switch($page){
			case 'description':
				include "views/viewcampaign.html";	
			break;
			case 'mydonation':
				include "views/viewmydonation.html";
			break;
			case 'editmydonation':
				include "views/editmydonation.html";
			break;
			case 'manager':
				include "views/listdonators.php";
			break;
			case 'history':
				include "views/listhistory.php";
			break;
			case 'confirmation':
				include "views/confirmation.html";
			break;
			case 'invalid':
				include "views/invalid.html";
			break;
			case 'delete':
				include "views/delete.php";
			break;
		}
	}
}
?>
	</td>
</tr>
</table>
</center>

<?php
/// Finish the page
    print_footer($course);

?>
