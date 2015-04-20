<?php
/** Role based permission management
 *  Needs more refinement
 *
 * Copyright (C) 2007  Ajay Pal Singh Atwal
 */
function checkPermission($type)
{
    global $template, $db;

    $type = addslashes($type);

    if($db->getOne("select `$type` from roles where id='".(int)$_SESSION['role']."'"))
    {
        $template->assign('_permission_'.$type, '1');
        return true;
    }
    else
    {
        $template->assign('_permission_'.$type, '0');
        return false;
    }
}
?>