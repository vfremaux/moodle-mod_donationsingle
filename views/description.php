<?php

/**
* @package mod-donationsingle
* @category mod
* @author Valery Fremaux, Donovan Rooks 
* @date 23/02/2009
*
* HTML form
* Print DonationSingle Description
*/
?>
<center>
<?php
echo $OUTPUT->box_start('center', '80%', '', '', 'generalbox');
?>
<!-- Print Donation Form -->
<?php
	
	global $CFG;
	
	$currency_options = array(
					0 => get_string('dollars', 'donationsingle'),
					1 => get_string('euros', 'donationsingle'),
					2 => get_string('pounds', 'donationsingle')
					);
	$amountneeded = $donationsingle->amountneeded;
	$currency = $currency_options[$donationsingle->currency];
	$amountraised = $donationsingle->amountraised;
	$length = 300;
	if ($amountneeded != 0){
		$leftsize = (int)(($amountraised/$amountneeded)*$length);
		$rightsize = (int)((1 - $amountraised/$amountneeded)*$length);
		$percentage = (int)(($amountraised*100)/$amountneeded);
	} else {
		$leftsize = 0;
		$rightsize = $length;
		$percentage = 0;
	}

	$privacyoptions = array	(
						0 => get_string('publicstrict', 'donationsingle'),
						1 => get_string('public', 'donationsingle'),
						2 => get_string('private', 'donationsingle')
						);
	$privacy = $privacyoptions[$donationsingle->idview];
	$opening = $donationsingle->openingdate;
	$deadlinedate = $donationsingle->deadline;
?>

<table class="noextraspace">
	
		<td><img src=<?php echo "\"{$CFG->wwwroot}/mod/donationsingle/pics/left_end.gif\" "?> height="20" width="5" ></td>
		<td><img src=<?php echo "\"{$CFG->wwwroot}/mod/donationsingle/pics/left.gif\" "?> height="20" width=<?php echo "\"".$leftsize."\"" ?>></td>
		<td><img src=<?php echo "\"{$CFG->wwwroot}/mod/donationsingle/pics/cursor_simple.gif\" "?> height="30" width="5"></td>
		<td><img src=<?php echo "\"{$CFG->wwwroot}/mod/donationsingle/pics/right.gif\" "?> height="20" width=<?php echo "\"".$rightsize."\" " ?>></td>
		<td><img src=<?php echo "\"{$CFG->wwwroot}/mod/donationsingle/pics/right_end.gif\" "?> height="20" width="5"></td>
</table>
<?php
	echo ' '.$percentage.' '.'%';
?>
<table border="0" cellpadding="10" width="100%">
	<tr>
		<td width="30%"><b><?php print_string('name','donationsingle') ?>:</b> </td>
		<td width="70%"><?php echo format_string($donationsingle->name) ?></td>
	</tr>
	<tr>
		<td><b><?php print_string('description','donationsingle') ?>:</b> </td>
		<td><?php echo format_text($donationsingle->intro) ?></td>
	</tr>
	<tr>
		<td><b><?php print_string('amountneeded','donationsingle') ?>:</b> </td>
		<td><?php echo $amountneeded.'   '.$currency ?></td>
	</tr>
	<tr>
		<td><b><?php print_string('amountraised','donationsingle') ?>:</b> </td>
		<td><?php echo $amountraised.' '.$currency ?></td>
	</tr>
	<tr>
		<td><b><?php print_string('privacystatus','donationsingle') ?>:</b> </td>
		<td><?php echo $privacy ?></td>
	</tr>
	<tr>
		<td><b><?php print_string('available','donationsingle') ?>:</b> </td>
		<td><?php echo userdate($opening) ?></td>
	</tr>
	<tr>
		<td><b><?php print_string('deadline','donationsingle') ?>:</b> </td>
		<td><?php echo userdate($deadlinedate) ?></td>
	</tr>
</table>
<?php
echo $OUTPUT->box_end();
?>

</center>
















