<?php // $Id: version.php,v 1.2 2011-05-15 11:22:03 vf Exp $
/**
 * Code fragment to define the version of NEWMODULE
 * This fragment is called by moodle_needs_upgrading() and /admin/index.php
 *
 * @author 
 * @version $Id: version.php,v 1.2 2011-05-15 11:22:03 vf Exp $
 * @package NEWMODULE
 **/

$module->version  = 2013011002;  // The current module version (Date: YYYYMMDDXX)
$module->requires = 2012061700;
$module->component = 'mod_donationsingle';   // Full name of the plugin (used for diagnostics)
$module->cron     = 0;           // Period for cron to check this module (secs)
$module->maturity = MATURITY_BETA;           // Maturity 
$module->release = "2.3.0 (Build 2013011002)"; // Release


