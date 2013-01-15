
<?php

/**
* @package mod-donationsingle
* @category mod
* @author Valery Fremaux, Donovan Rooks 
* @date 23/02/2009
*
* HTML form
* Print form for invalid amount input
*/

    echo $OUTPUT->box_start('center', '60%', '', '', 'generalbox');
?>

<center>
	<table border="0" cellpading="10">
		<tr><td><?php print_string('invalid', 'donationsingle'); ?></td></tr>
	</table>
</center>

<?php
    echo $OUTPUT->box_end();
    echo $OUTPUT->continue_button("view.php?id={$cm->id}view=donation"); 
    $nohtmleditorneeded = true;
?>
