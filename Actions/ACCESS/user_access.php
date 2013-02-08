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
 * @version $Id: user_access.php,v 1.11 2007/02/16 08:32:08 eric Exp $
 * @license http://www.fsf.org/licensing/licenses/agpl-3.0.html GNU Affero General Public License
 * @package FDL
 * @subpackage ACCESS
 */
/**
 */
// ---------------------------------------------------------------
// $Id: user_access.php,v 1.11 2007/02/16 08:32:08 eric Exp $
// $Source: /home/cvsroot/anakeen/freedom/core/Action/Access/user_access.php,v $
// ---------------------------------------------------------------
include_once ("FDL/editutil.php");
// -----------------------------------
function user_access(Action & $action, $accountType = "U")
{
    // -----------------------------------
    $filteruser = $action->getArgument("userfilter");
    
    $user_id = $action->getArgument("uid");
    $action->lay->set("userfilter", $filteruser);
    
    $packUrl = $action->parent->getJsLink("ACCESS:access.js", true, "USER_ACCESS");
    $action->parent->getJsLink("ACCESS:user_access.js", true, "USER_ACCESS");
    $action->parent->getJsLink("ACCESS/Layout/edit.js", false, "USER_ACCESS");
    
    $jslinks = array(
        array(
            "src" => $action->parent->getJsLink("lib/jquery-ui/js/jquery.js")
        ) ,
        array(
            "src" => $action->parent->getJsLink("lib/jquery-ui/js/jquery-ui.js")
        ) ,
        array(
            "src" => $action->parent->getJsLink("lib/jquery-dataTables/js/jquery.dataTables.js")
        ) ,
        array(
            "src" => $packUrl
        )
    );
    $csslinks = array(
        array(
            "src" => $action->parent->getCssLink("lib/jquery-ui/css/smoothness/jquery-ui.css")
        ) ,
        array(
            "src" => $action->parent->getCssLink("lib/jquery-dataTables/css/jquery.dataTables.css")
        ) ,
        array(
            "src" => $action->parent->getCssLink("ACCESS/Layout/user_access.css")
        ) ,
        array(
            "src" => $action->parent->getCssLink("WHAT/Layout/size-normal.css")
        ) ,
        array(
            "src" => $action->parent->getCssLink("ACCESS/Layout/edit.css")
        )
    );
    $action->lay->setBlockData("CSS_LINKS", $csslinks);
    $action->lay->setBlockData("JS_LINKS", $jslinks);
    $action->lay->set("idAURG", "iduser");
    // Set the edit form element
    $action->lay->set("shortname", _($action->text("appname")));
    $action->lay->set("desc", _($action->text("appdesc")));
    $action->lay->set("permission", $action->text("permissions"));
    $action->lay->set("placeholder", _("Application filter"));
    
    $action->lay->set("maxreach", false);
    $action->lay->set("usefilter", false);
    $action->lay->set("accountType", $accountType);
    $action->lay->set("URG", true);
    // affect the select form elements
    $u = new Account();
    if ($accountType == "G") {
        $action->lay->set("changeLabel", _("Select Group Access"));
    } elseif ($accountType == "R") {
        $action->lay->set("changeLabel", _("Select Role Access"));
    } else {
        $action->lay->set("changeLabel", _("Select User Access"));
    }
    // select the first user if not set
    if ($user_id == "") {
        simpleQuery($action->dbaccess, sprintf("select id from users where accounttype='%s' order by id limit 1", pg_escape_string($accountType)) , $user_id, true, true);
    }
    
    $u->select($user_id);
    $action->lay->set("valueAURG", $u->getDisplayName($user_id));
    $action->lay->set("valueidAURG", $user_id);
    
    $action->lay->set("hasuser", $u->id ? true : false);
}
?>
