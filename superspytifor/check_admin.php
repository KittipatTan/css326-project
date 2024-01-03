<?php

function check_privilege($role_id, $su) {
    switch ($su) {
        case 0: 
            if (!($role_id === 1 || $role_id === 2)) {
                // not admin
                // stop from accessing
                // bruh
                exit("Unlock this for $50");
                return false;
            }
            break;
            
        case 1:
            if ($role_id !== 1) {
                // not superadmin
                // stop from accessing
                exit("We live We love We lied");
                return false;
            }
            break;
        
        default:
            break;
    }

    return true;
}

?>