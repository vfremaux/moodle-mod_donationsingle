<?php

/**
* @package mod-donationsingle
* @category mod
* @author Valery Fremaux, Donovan Rooks 
* @date 23/02/2009
*
* HTML form
* Print form for asking confirmation about donation removal
*/
echo $OUTPUT->box_start('center', '60%', '', '', 'generalbox');
?>
<center>
	<table border="0" cellpadding="5">
	<tr>
		<td><?php print_string('confirmation', 'donationsingle'); ?></td>
		<td><a href = <?php echo "\"view.php?id={$cm->id}&amp;view=view&amp;page=delete\""?>><input type="submit" value="<?php print_string('yes', 'donationsingle') ?>"></a></td>
		<td><a href = <?php echo "\"view.php?id={$cm->id}&amp;view=view \""?>><input type="submit" value="<?php print_string('no', 'donationsingle') ?>"></td>
	</tr>
	</table>
</center>
<?php
echo $OUTPUT->box_end();

?>




