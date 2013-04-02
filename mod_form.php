<?php
require_once ($CFG->dirroot.'/course/moodleform_mod.php');
require_once ($CFG->dirroot.'/mod/donationsingle/locallib.php');

class mod_donationsingle_mod_form extends moodleform_mod {

    function definition() {

        global $CFG, $COURSE;
        $mform    =& $this->_form; 
//-------------------------------------------------------------------------------
        $mform->addElement('header', 'general', get_string('general', 'donationsingle'), array('size'=>'64'));

		$mform->addElement('text', 'name', get_string('campaignname', 'donationsingle'), array('size'=>'64'));

        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEANHTML);
        }
        $mform->addRule('name', null, 'required', null, 'client');

        $this->add_intro_editor(true, get_string('donationintro', 'donationsingle'));

        $this->addElement('editor', 'dismisstext', get_string('dismisstext', 'donationsingle'));

        $this->addElement('editor', 'payinstructions', get_string('payinstructions', 'donationsingle'));

		$mform->addElement('text','amountneeded', get_string('amountneeded', 'donationsingle'), array('size'=>'20'));
		$currency_options = array(
								0 => get_string('dollars', 'donationsingle'),
								1 => get_string('euros', 'donationsingle'),
								2 => get_string('pounds', 'donationsingle')
								 );		
		$mform->addElement('select','currency', get_string('currency', 'donationsingle'), $currency_options);
//------------------------------------------------------------------------------
		$mform->addElement('header', 'access', get_string('access', 'donationsingle'), array('size'=>'64'));
		$mform->addElement('date_time_selector', 'openingdate', get_string('available', 'donationsingle'), array('optional'=>false));
        $mform->setDefault('available', 0);

        $mform->addElement('date_time_selector', 'deadline', get_string('deadline', 'donationsingle'), array('optional'=>false));
        $mform->setDefault('deadline', 0);

//-------------------------------------------------------------------------------

		$mform->addElement('header', 'options', get_string('options', 'donationsingle'));
		$ynoptions = array	(
							0 => get_string('no', 'donationsingle'),
							1 => get_string('yes', 'donationsingle')
							);
		$privacyoptions = array	(
								PUBLICITY_PUBLIC_FORCED => get_string('publicforced', 'donationsingle'),
								PUBLICITY_PUBLIC => get_string('public', 'donationsingle'),
								PUBLICITY_PRIVATE => get_string('private', 'donationsingle')
								);
		$mform->addElement('select', 'globalview', get_string('globalview', 'donationsingle'), $ynoptions);
		$mform->addElement('select', 'publicity', get_string('publicity', 'donationsingle'), $privacyoptions);

//-------------------------------------------------------------------------------
		$mform->addElement('header', 'notifications', get_string('notifications', 'donationsingle'));

		$mform->addElement('checkbox', 'managernotification', get_string('managernotification', 'donationsingle'));
		$mform->addElement('checkbox', 'reminder', get_string('reminder', 'donationsingle'));
//-------------------------------------------------------------------------------
		$this->standard_coursemodule_elements(array('groups'=>true, 'groupings'=>true, 'groupmembersonly'=>true));

//-------------------------------------------------------------------------------
        // buttons
        $this->add_action_buttons();
    }
}
?>

