<?php

/**
* @package mod-donationsingle
* @category mod
* @author Valery Fremaux, Donovan Rooks
* @date 13/03/2009
*
* Controller for all "view" related views
*/


/************************************ Submit a donation ******************************/
if ($action == 'makeadonation'){
	if(!$donationsingle = donationsingle_submitdonation($donationsingle->id)){
		error("Bad donation id");
	}else{
		print_simple_box_start('center', '80%', '', '', 'generalbox');
		print_string('thanks', 'donationsingle');
		print_simple_box_end();
		print_continue("view.php?id={$cm->id}view=view&amp;page=description");
	}
	return -1;
}
/************************************ edit a donation ******************************/
if ($action == 'editadonation'){
	if(!$donationsingle = donationsingle_editdonation($donationsingle->id)){
		error("Bad donation id");
	}else{
		print_simple_box_start('center', '80%', '', '', 'generalbox');
		print_string('thanks', 'donationsingle');
		print_simple_box_end();
		print_continue("view.php?id={$cm->id}view=view&amp;page=description");
	 }
	return -1;
}
?>

