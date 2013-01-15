<?php // $Id: index.php,v 1.2 2011-05-15 11:22:03 vf Exp $
/**
 * This page lists all the instances of donationsingle in a particular course
 *
 * @author 
 * @version $Id: index.php,v 1.2 2011-05-15 11:22:03 vf Exp $
 * @package donationsingle
 **/

/// Replace donationsingle with the name of your module

    require_once("../../config.php");
    require_once("lib.php");

    $id = required_param('id', PARAM_INT);   // course

    if (! $course = $DB->get_record('course', array('id' => $id))) {
        print_error('invalidcourseid');
    }

    require_login($course->id);

    add_to_log($course->id, 'donationsingle', 'view all', "index.php?id=$course->id", '');


/// Get all required strings

    $strdonationsingles = get_string('modulenameplural', 'donationsingle');
    $strdonationsingle = get_string('modulename', 'donationsingle');


/// Print the header

    /// Output the page
    $navlinks = array();
    $navlinks[] = array('name' => $strdonationsingles, 'link' => '', 'type' => 'activity');

    $PAGE->set_title("$course->shortname: $strdonationsingles");
    $PAGE->set_heading("$course->fullname");
    $PAGE->set_focuscontrol('');
    $PAGE->set_cacheable(true);
    $PAGE->set_button('');
    $PAGE->set_headingmenu(navmenu($course));
    echo $OUTPUT->header();

/// Get all the appropriate data

    if (! $donationsingless = get_all_instances_in_course("donationsingle", $course)) {
        notice("There are no donations", "../../course/view.php?id=$course->id");
        die;
    }

/// Print the list of instances (your module will probably extend this)

    $timenow = time();
    $strname  = get_string("name");
    $strweek  = get_string("week");
    $strtopic  = get_string("topic");

    if ($course->format == "weeks") {
        $table->head  = array ($strweek, $strname);
        $table->align = array ("center", "left");
    } else if ($course->format == "topics") {
        $table->head  = array ($strtopic, $strname);
        $table->align = array ("center", "left", "left", "left");
    } else {
        $table->head  = array ($strname);
        $table->align = array ("left", "left", "left");
    }

    foreach ($donationsingles as $donationsingle) {
        if (!$donationsingle->visible) {
            //Show dimmed if the mod is hidden
            $link = "<a class=\"dimmed\" href=\"view.php?id=$donationsingle->coursemodule\">$donationsingle->name</a>";
        } else {
            //Show normal if the mod is visible
            $link = "<a href=\"view.php?id=$donationsingle->coursemodule\">$donationsingle->name</a>";
        }

        if ($course->format == "weeks" or $course->format == "topics") {
            $table->data[] = array ($donationsingle->section, $link);
        } else {
            $table->data[] = array ($link);
        }
    }

    echo "<br />";

    echo html_writer::table($table);

/// Finish the page

    echo $OUTPUT->footer($course);

?>
