<?php
/**
 * this file displays a history of donations  
 * from a donator
 *
 * @author Valery Fremaux, Rooks Donovan
 *
 * @package mod-donationSingle
 * @category mod
 */

    $sql = "
        SELECT   
        	i.id,
			i.amount,
			i.datereported
        FROM 
            {donationsingle_amount} as i 
        WHERE 
            i.donatorid = {$userid}
		ORDER BY 
			i.datereported ASC
    ";

    $donations = $DB->get_records_sql($sql); 

    $currency_options = array(
    					0 => get_string('dollars', 'donationsingle'),
    					1 => get_string('euros', 'donationsingle'),
    					2 => get_string('pounds', 'donationsingle')
    					);
    $currency = $currency_options[$donationsingle->currency] ;

	$user = $DB->get_record('user', array('id' => $userid));

    echo $OUTPUT->box_start('center', '80%', '', '', 'generalbox');
	
	// print here history list for user
	$namestr = get_string('name', 'donationsingle');
	$amountstr = get_string('amount', 'donationsingle');
	$datestr = get_string('date', 'donationsingle');
	
	$table = new html_table();
	$table->head = array("<b>$namestr</b>", "<b>$amountstr</b>", "<b>$datestr</b>");
	$table->size = array('60%', '20%', '20%');
	$table->align = array('left', 'left', 'right');

	$i = 0;
	foreach($doantions as $d){
		if ($i == 0){
			$table->data = array(fullname($user), sprintf("0.2f", $d->amount, userdate($d->datereported));
		} else {
			$table->data = array('', sprintf("0.2f", $d->amount, userdate($d->datereported));
		}
	}
		
	echo $OUTPUT->box_end();
?>


