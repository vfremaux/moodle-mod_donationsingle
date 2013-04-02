<?php
/**
 * This is a set of information on a donation made by a user.
 * @package mod-donationsingle
 * @category mod
 * @author Valery Fremaux, Donovan Rooks
 *
 * HTML table
 */
?>

<center>
<?php 
echo $OUTPUT->box_start('center', '80%', '', '', 'generalbox');

$name = $USER->username;
$firstname = $USER->firstname;
$amount = 0;
$currency_options = array(
					0 => get_string('dollars', 'donationsingle'),
					1 => get_string('euros', 'donationsingle'),
					2 => get_string('pounds', 'donationsingle')
					);
$currency = $currency_options[$donationsingle->currency] ;
if($result = donationsingle_has_already_made_donation($donationsingle->id, $USER->id)){
	$lastrecord = $DB->get_field('donationsingle_donator', 'MAX(datereported)', array('donationid' => $donationsingle->id, 'userid' => $USER->id));
	$amount = $DB->get_field('donationsingle_donator', 'amount', array('donationid' => $donationsingle->id, 'userid' => $USER->id, 'datereported' => $lastrecord));
}
?>

<table border="0" cellpadding="10" class="generaltable">

	<tr>
		<td><?php print_string('name', 'donationsingle') ?>:</td>
		<td><?php echo $name ?></td>
	</tr>
	<tr>
		<td><?php print_string('firstname', 'donationsingle') ?>:</td>
		<td><?php echo $firstname ?></td>
	</tr>
	<tr>
		<td><?php print_string('amountgifted', 'donationsingle') ?>:</td>
		<td><?php echo $amount ?></td>
	</tr>
	<tr>
		<td><?php print_string('currency', 'donationsingle') ?>:</td>
		<td><?php echo $currency ?></td>
	</tr>

</table>

<?php 
	if($result){
?>
<center>
	<input type="hidden" name="id" value="<?php p($cm->id) ?>" />
	<input type="hidden" name="view" value="view" />
	<input type="hidden" name="page" value="editmydonation" />
	<a href="view.php?id=<?php p($cm->id) ?>&amp;view=view&amp;page=editmydonation"><input type="submit" value=<?php print_string('modify', 'donationsingle') ?>></a>
</center>
<?php
}
echo $OUTPUT->box_end();
?>

