<?php

function xmldb_donationsingle_upgrade($oldversion=0) {
/// This function does anything necessary to upgrade 
/// older versions to match current functionality 

    global $CFG;
    
    $result = true;

    if ($result && $oldversion < 2009071300) {
    }

    return $result;
}
?>