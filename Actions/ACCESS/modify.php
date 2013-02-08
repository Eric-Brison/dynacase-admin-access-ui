<?php
/*
 * @author Anakeen
 * @license http://www.fsf.org/licensing/licenses/agpl-3.0.html GNU Affero General Public License
 * @package FDL
*/
/**
 * Generated Header (not documented yet)
 *
 * @author Anakeen
 * @version $Id: modify.php,v 1.7 2007/02/14 15:13:16 eric Exp $
 * @license http://www.fsf.org/licensing/licenses/agpl-3.0.html GNU Affero General Public License
 * @package FDL
 * @subpackage ACCESS
 */
/**
 */
// ---------------------------------------------------------------
// $Id: modify.php,v 1.7 2007/02/14 15:13:16 eric Exp $
// $Source: /home/cvsroot/anakeen/freedom/core/Action/Access/modify.php,v $
// ---------------------------------------------------------------
// -----------------------------------
function modify(Action & $action)
{
    // -----------------------------------
    $coid = $action->getArgument("oid"); // oid for controlled object
    if ($coid > 0) modify_oid($action);
    else modify_app($action);
}
// -----------------------------------
function modify_app(Action & $action)
{
    // -----------------------------------
    // get all parameters
    $userId = $action->getArgument("userid");
    $appId = $action->getArgument("appid");
    $aclp = $action->getArgument("aclup"); // ACL + (more access)
    $acln = $action->getArgument("aclun"); // ACL - (less access)
    // modif permission for a uncontrolled object
    $p = new Permission($action->dbaccess, array(
        $userId,
        $appId
    ));
    if (!$p->IsAffected()) {
        $p->Affect(array(
            "id_user" => $userId,
            "id_application" => $appId
        ));
    }
    // delete old permissions
    $p->deletePermission($userId, $appId, null, null);
    $p->deletePermission(null, $appId, null, true);
    
    if (is_array($aclp)) {
        // create new permissions
        foreach ($aclp as $v) {
            $p->id_acl = $v;
            $p->computed = false;
            $p->Add();
        }
    }
    
    if (is_array($acln)) {
        // create new permissions
        foreach ($acln as $v) {
            $p->id_acl = - $v;
            $p->computed = false;
            $p->Add();
        }
    }
    
    $action->parent->session->closeAll();
    $action->parent->session->set(""); // reset session to save current
    $action->lay->template = json_encode(array(
        "success" => true
    ));
    $action->lay->noparse = true;
    
    header('Content-type: application/json');
}
// -----------------------------------
function modify_oid(Action & $action)
{
    // -----------------------------------
    // get all parameters
    $userId = $action->getArgument("userid");
    $appId = $action->getArgument("appid");
    $aclp = $action->getArgument("aclup"); // ACL + (more access)
    $acln = $action->getArgument("aclun"); // ACL - (less access)
    $coid = $action->getArgument("oid"); // oid for controlled object
    $returnact = $action->getArgument("returnact");
    // test if current user could modify ACL
    $p = new ObjectPermission($action->dbaccess, array(
        $action->parent->user->id,
        $coid,
        $appId
    ));
    if (($err = $p->ControlOid($appId, "modifyacl")) != "") {
        $action->ExitError($err);
    }
    // modif permission for a particular object
    $p = new ObjectPermission($action->dbaccess, array(
        $userId,
        $coid,
        $appId
    ));
    // delete old permissions
    $p->Delete();
    
    if (is_array($aclp)) {
        // create new permissions
        while (list($k, $v) = each($aclp)) {
            $p->AddAcl($v);
        }
    }
    
    if (is_array($acln)) {
        // create new permissions
        while (list($k, $v) = each($acln)) {
            $p->AddAcl(-$v);
        }
    }
    
    $p->Add();
    
    if ($returnact == "") exit(0);
    redirect($action, "ACCESS", $returnact);
}
?>
