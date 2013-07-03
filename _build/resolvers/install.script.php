<?php

if ($object->xpdo) {
    $modx =& $object->xpdo;
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
 
        $obj = $modx->getObject('modPlugin', array('name' => 'fastField'));
        if ($obj) {
            $obj->remove();
        }
    }
}
 
return true;