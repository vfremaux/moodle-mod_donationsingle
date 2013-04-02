<?php

		print_string('updated', 'donationsingle');

		echo $OUTPUT->box_start('center', '80%', '', '', 'generalbox');
		print_string('thanks', 'donationsingle');
		echo $OUTPUT->box_end();
		echo $OUTPUT->continue_button("view.php?id={$cm->id}view=view&amp;page=description");

?>