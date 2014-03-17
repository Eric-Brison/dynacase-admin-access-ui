<?php
/*
 * @author Anakeen
 * @license http://www.fsf.org/licensing/licenses/agpl-3.0.html GNU Affero General Public License
 * @package FDL
 */

global $app_desc, $action_desc, $app_acl;

$app_desc = array(
    "name" => "ACCESS", //Name
    "short_name" => N_("Access") , //Short name
    "description" => N_("What Access Management") , //long description
    "icon" => "access.png", //Icon
    "displayable" => "Y", //Should be displayed on an app list (Y,N)
    "tag" => "ADMIN SYSTEM",
);

$app_acl = array(
    array(
        "name" => "ADMIN",
        "description" => N_("Admin Access") ,
        "admin" => TRUE
    )
);

$action_desc = array(
    array(
        "name" => "ADMIN_ACTIONS_LIST",
        "short_name" => N_("access:ADMIN_ACTIONS_LIST short_name") ,
        "acl" => "ADMIN"
    ) ,
    array(
        "name" => "USER_ACCESS",
        "acl" => "ADMIN",
        "short_name" => N_("User Access") ,
        "root" => "Y"
    ) ,
    array(
        "name" => "GROUP_ACCESS",
        "acl" => "ADMIN",
        "short_name" => N_("Group Access") ,
        "layout" => "user_access.xml"
    ) ,
    
    array(
        "name" => "ROLE_ACCESS",
        "acl" => "ADMIN",
        "short_name" => N_("Role Access") ,
        "layout" => "user_access.xml"
    ) ,
    array(
        "name" => "APPL_ACCESS",
        "acl" => "ADMIN",
        "layout" => "user_access.xml",
        "short_name" => N_("Application Access")
    ) ,
    array(
        "name" => "MODIFY",
        "acl" => "ADMIN",
        "short_name" => N_("Modify any access")
    ) ,
    array(
        "name" => "DOWNLOAD",
        "acl" => "ADMIN"
    ) ,
    array(
        "name" => "UPLOAD",
        "acl" => "ADMIN"
    ) ,
    array(
        "name" => "IMPORT_EXPORT",
        "acl" => "ADMIN",
        "short_name" => N_("Import/Export")
    ) ,
    array(
        "name" => "EDIT",
        "short_name" => N_("Edit any access") ,
        "acl" => "ADMIN"
    ) ,
    array(
        "name" => "GET_ACCOUNT",
        "acl" => "ADMIN",
        "short_name" => N_("Get account") ,
        "function" => "accessGetAccounts",
        "script" => "haccess.php"
    ) ,
    array(
        "name" => "GET_APPS",
        "acl" => "ADMIN",
        "short_name" => N_("Get application") ,
        "function" => "accessGetApps",
        "script" => "haccess.php"
    ) ,
    array(
        "name" => "GET_DATATABLE_INFO",
        "acl" => "ADMIN",
        "short_name" => N_("Get datatable information") ,
        "function" => "accessGetDatatableInfo",
        "script" => "haccess.php"
    ) ,
    array(
        "name" => "USER_GET_DATATABLE_INFO",
        "acl" => "ADMIN",
        "short_name" => N_("Get datatable information for User, group or role") ,
        "function" => "accessUserGetDatatableInfo",
        "script" => "haccess.php"
    ) ,
    array(
        "name" => "GET_ACCOUNTTYPES_IMAGE",
        "acl" => "ADMIN",
        "short_name" => N_("Get accounttypes image") ,
        "function" => "accessGetAccounttypesImage",
        "script" => "haccess.php"
    )
);
