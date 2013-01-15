<?php

/**
* @package mod-donationsingle
* @category mod
* @author Valery Fremaux, Donovan Rooks 
* @date 23/02/2009
*
* HTML form
* Print form for editing a donation
*/
?>

<center>
<?php
    echo $OUTPUT->box_start('center', '80%', '', '', 'generalbox');
    $currency_options = array(
    					0 => get_string('dollars', 'donationsingle'),
    					1 => get_string('euros', 'donationsingle'),
    					2 => get_string('pounds', 'donationsingle')
    					);
    $currency = $currency_options[$donationsingle->currency] ;
    $currencyint = $donationsingle->currency;
?>
<form name="donation" action="view.php" method="post">
<input type="hidden" name="id" value="<?php p($cm->id) ?>" />
<input type="hidden" name="view" value="description" />
<input type="hidden" name="what" value="updateadonation" />
<table border="0" cellpadding="10">
	<tr width="200">
		<td align="left"><b><?php print_string('amountgiven','donationsingle')?>:</b></td>
		<td align="right"><input type="text" name="amount" size=10 id="ds"></td>
		<td><?php echo $currency ?></td>
	</tr>
	<tr>
	<center>
		<td align="center" colspan="10"><input type="submit" value="<?php print_string('submit','donationsingle')?>" ></td>
	</center>
	</tr>
</table>
</form>

<?php
echo $OUTPUT->box_end();
?>

</center>