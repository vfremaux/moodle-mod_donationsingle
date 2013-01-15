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
	if(!donationsingle_submitdonation($donationsingle)){
		print_error('errorbadcampainid', 'donationsingle');
	}
	$page = 'thanks';
}
/************************************ edit a donation ******************************/
if ($action == 'updateadonation'){
	if(!$donationsingle = donationsingle_editdonation($donationsingle->id)){
		print_error('errorbadcampainid', 'donationsingle');
	}
	$page = 'updated';
}
?>

