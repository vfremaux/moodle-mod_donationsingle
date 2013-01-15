<?php

class Donation_Form extends moodleform{
	
	var $donationsingle;

	/**
	* @param object ref $donationsingle a donationsingle instance
	* @param string $action (donate or update)
	*/	
	function _construct(&$donationsingle, $action){
		
		$this->donationsingle = $donationsingle;
		$this->action = $action;
		
	}
	
	function definition(){
		
		$mform = $this->_form;

		$mform->addElement('hidden', 'id');
		$mform->addElement('hidden', 'view', 'description');
		$mform->addElement('hidden', 'what', 'donate');

		$mform->addElement('text', 'amount', get_string('amounttogive', 'donationsingle', $this->donationsingle->currency);

		$mform->addElement('html', 'visibility', get_string('publicity'.$donationsingle->publicity, 'donationsingle');
		if ($donationsingle->publicity == PUBLICITY_PUBLIC){
			$mform->addElement('checkbox', 'visible', get_string('uservisible', 'donationsingle');
		}

		$this->add_action_buttons(true);
	}
	
}