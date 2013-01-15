<?php
/**
 * this file displays a list of donators from a 
 * donationSingle campaign
 *
 * @author Valery Fremaux, Rooks Donovan
 *
 * @package mod-donationSingle
 * @category mod
 */

    include_once "$CFG->libdir/tablelib.php";

    $sql = "
        SELECT   
			i.userid,
			i.donationid,
			i.amount,
			i.datereported,
			u.firstname,
			u.lastname,
			u.email,
			u.picture,
			u.emailstop
        FROM 
            {$CFG->prefix}donationsingle_donator i,
            {$CFG->prefix}user u 
        WHERE 
            i.userid = u.id AND
            i.donationid = {$donationsingle->id}
        GROUP BY 
			i.userid,
			i.amount,
			i.datereported
    ";
	$sqlcount = "
        SELECT 
            COUNT(*)
        FROM 
            {$CFG->prefix}donationsingle_donator as e
        WHERE 
            e.donationid = {$donationsingle->id} 
        GROUP BY
            e.donationid
    ";
    $numrecords = count_records_sql($sqlcount);
?>
<center>
<?php

    $currency_options = array(
    					0 => get_string('dollarsign', 'donationsingle'),
    					1 => get_string('eurossign', 'donationsingle'),
    					2 => get_string('poundsign', 'donationsingle')
    					);
    $currency = $currency_options[$donationsingle->currency] ;

/// define table object

    $picturestr = get_string('picture', 'donationsingle');
    $fullnamestr = get_string('fullname', 'donationsingle');
    $amountstr = get_string('amountgifted', 'donationsingle');
    $datestr = get_string('date', 'donationsingle');

    $tablecolumns = array('picture', 'firstname', 'amount', 'date');
    $tableheaders = array("<b>$picturestr</b>", "<b>$fullnamestr</b>", "<b>$amountstr</b>", "<b>$datestr</b>");

    $table = new flexible_table('mod-donationsingle-donationlist');
    $table->define_columns($tablecolumns);
    $table->define_headers($tableheaders);

    $table->define_baseurl($CFG->wwwroot.'/mod/donationsingle/view.php?id='.$cm->id);

    $table->sortable(true, 'id', SORT_ASC); //sorted by datereported by default
    $table->collapsible(true);
    $table->initialbars(true);

    $table->set_attribute('cellspacing', '0');
    $table->set_attribute('id', 'donations');
    $table->set_attribute('class', 'generaltable');
    $table->set_attribute('width', '100%');

    $table->column_style('picture', 'width', '10%');
    $table->column_style('fullname', 'width', '60%');
    $table->column_style('amount', 'width', '10%');
    $table->column_style('date', 'width', '20%');
    $table->column_class('picture', 'list_picture');
    $table->column_class('fullname', 'list_fullname');
    $table->column_class('amount','list_amount');
    $table->column_class('date', 'list_date');
    $table->setup();

/// get extra query parameters from flexible_table behaviour

    $where = $table->get_sql_where();
    $sort = $table->get_sql_sort();
    $limit = 20;
    $table->pagesize($limit, $numrecords);

    if (!empty($sort)){
        $sql .= " ORDER BY $sort";
    }

    $donators = get_records_sql($sql, $table->get_page_start(), $table->get_page_size());
    $space = ' ';
    if (!empty($donators)){
        /// product data for table
    	$anonymous = 0;
        foreach ($donators as $donator){
    		$picture = print_user_picture(get_record('user', 'id', $donator->userid), $cm->id, null, 0, true);
    		$fullname = "<a href= \"view.php?id={$cm->id}&amp;view=view&amp;page=history&amp;userid={$donator->userid}\">".fullname($donator)."</a>";
            $datereported = date('Y/m/d h:i', $donator->datereported);
    		$arrow = $space.donationsingle_print_arrow($donator);
    		$amount = format_string($donator->amount).$currency.$arrow;
    		//check for anonymous donators
    		if(donationsingle_ispublicstrict($donationsingle->id)){
    			$dataset = array($picture, $fullname, $amount, $datereported);
    			$table->add_data($dataset);	     
    		} else {
    			if (donationsingle_isanonymoususer($donator->userid)){
    				$anonymous = $anonymous + $donator->amount;
    			} else {
    				$dataset = array($picture, $fullname, $amount, $datereported);
    				$table->add_data($dataset);
    			}	
    		}
        }
    	//now add the eventual anonymous donators
    	if($anonymous > 0){
    		$str = get_string('anonymous', 'donationsingle');
    		$amount = $anonymous;
    		$dataset = array($str, $str, $amount, $str);
    		$table->add_data($dataset);
    	}
        $table->print_html();
    }
?>
</center>
<?php
    $nohtmleditorneeded = true;
?>

