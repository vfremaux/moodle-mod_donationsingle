<?php

/**
* @package mod-donationsingle
* @category mod
* @author Valery Fremaux, Donovan Rooks 
* @date 23/02/2009
*
* HTML form
* Print form for making a new donation
*/
?>
<center>
<?php
	$alert = get_string('alert', 'donationsingle');

    echo $OUTPUT->box_start('center', '80%', '', '', 'generalbox');
    $currency_options = array(
    					0 => get_string('dollars', 'donationsingle'),
    					1 => get_string('euros', 'donationsingle'),
    					2 => get_string('pounds', 'donationsingle')
    					);
    $currency = $currency_options[$donationsingle->currency] ;
    $currencyint = $donationsingle->currency;
    $currenttimestamp = time();
    if($currenttimestamp < $donationsingle->openingdate){
        print_string('tooearly', 'donationsingle');
    } elseif ($result = donationsingle_has_already_made_donation($donationsingle->id, $USER->id)){
        print_string('sorrystring', 'donationsingle');
    } else {
	
?>
<form name="donation" action="view.php" method="post">
<input type="hidden" name="id" value="<?php p($cm->id) ?>" />
<input type="hidden" name="view" value="view" />
<input type="hidden" name="page" value="description" />
<input type="hidden" name="what" value="makeadonation" />
<input type="hidden" name="datereported" value="<?php p($currenttimestamp) ?>" />
<input type="hidden" name="userid" value="<?php p($USER->id) ?>" />
<table border="0" cellpadding="10">
	<tr width="200">
		<td align="left"><?php print_string('amountgiven','donationsingle')?>:</td>
		<td align="right"><input type="text" name="amount" size="10"></td>
		<td><?php echo $currency ?></td>
	</tr>
	<?php
		if(!donationsingle_isprivate($donationsingle->id)){
	?>
			<tr width="200">
				<td align="left"><?php print_string('uservisible', 'donationsingle')?></td>
			<?php 
				if(!donationsingle_ispublicstrict($donationsingle->id)){
			?>
				<td align="right"><input type="checkbox" value="1" name="visible" id="amount" checked="checked" /></td>
			<?php 
				}else{
			?>
				<td align="right"><input type="checkbox" value="1" name="visible" id="amount" checked="checked" disabled /></td>
			<?php 
				}
			?>
				<td align="left"><?php print_string('enable', 'donationsingle') ?></td>
			</tr>
	<?php 
		}else{
	?>
			<tr>
				<td align="left"><?php print_string('uservisible', 'donationsingle')?></td>
				<td align="right"><input type="checkbox" value="0" name="visible" checked="checked" disabled /></td>
				<td align="left"><?php print_string('disable', 'donationsingle') ?></td>
			</tr>
	<?php
		}
	?>
	<tr>
		<center>
			<td align=center colspan="10"><input type="submit" value="<?php print_string('submit','donationsingle')?>"  ></td>
		</center>
	</tr>
</table>
</form>

<?php
    }
    echo $OUTPUT->box_end();
?>

</center>