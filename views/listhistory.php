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
			i.amount,
			i.datereported
        FROM 
            {$CFG->prefix}donationsingle_history as i 
        WHERE 
            i.donationid = {$donationsingle->id} and i.userid = {$userid}
		AND 
			i.userid = $userid
        GROUP BY 
			i.amount,
			i.datereported
		ORDER BY 
			i.datereported DESC
    ";

    $donators = get_records_sql($sql); 

    $currency_options = array(
    					0 => get_string('dollars', 'donationsingle'),
    					1 => get_string('euros', 'donationsingle'),
    					2 => get_string('pounds', 'donationsingle')
    					);
    $currency = $currency_options[$donationsingle->currency] ;
    $name = get_field('user', 'username', 'id', $userid);
    $firstname = get_field('user', 'firstname', 'id', $userid);
    print_simple_box_start('center', '80%', '', '', 'generalbox');
?>
<center>
	<table border="0" cellpadding="10">
		<tr>
			<td><?php print_string('name', 'donationsingle') ?>:</td>
			<td><?php p($name) ?></td>
		</tr>
		<tr>
			<td><?php print_string('firstname', 'donationsingle') ?>:</td>
			<td><?php p($firstname) ?></td>
		</tr>
		<tr>
			<td><?php print_string('history', 'donationsingle') ?>:</td>
		</tr>
		<tr>
			<?php
			if(!empty($donators)){
				$i = 0;
				foreach($donators as $donator){
			?>
					<tr>
					<td>
					<?php
						$str = date('Y/m/d h:i', $donator->datereported);
						echo $str;
					?>
					</td>
					<td>
					<?php
						$str = $donator->amount;
						echo $str;
					?>
					</td>
					<td>
					<?php
						echo $currency;
					?>
					</td>
					</tr>
			<?php
				$i++;
				if($i>2) break;
				}
			}else{
			?>
				<td>
			<?php
				print_string('sorryhistory', 'donationsingle');
			?>
				</td>
			<?php
			}
			?>
		</tr>
	</table>
</center>
<?php
print_simple_box_end();
$nohtmleditorneeded = true;
?>


