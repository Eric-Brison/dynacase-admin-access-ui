<?php
/*
 * @author Anakeen
 * @license http://www.fsf.org/licensing/licenses/agpl-3.0.html GNU Affero General Public License
 * @package FDL
*/

function admin_actions_list(Action & $action)
{
    $return = array(
        "success" => true,
        "error" => array() ,
        "body" => array()
    );
    
    try {
        $appId = $action->parent->id;
        if (!is_numeric($appId)) {
            throw new Exception(sprintf("unexpected application id: %s", var_export($appId, true)));
        }
        
        $appName = $action->parent->name;
        $body = array();
        $adminActions = array();
        
        $query = <<< "SQL"
SELECT
    action.name,
    action.short_name,
    action.long_name
FROM action,
    (VALUES
        ('APPL_ACCESS',   1),
        ('ROLE_ACCESS',   2),
        ('GROUP_ACCESS',  3),
        ('USER_ACCESS',   4),
        ('IMPORT_EXPORT', 5)
    ) AS toc(name, position)
WHERE
    action.id_application = $appId AND
    action.name = toc.name
ORDER BY toc.position
;
SQL;
        /*
            'USER_ACCESS',
            'GROUP_ACCESS',
            'ROLE_ACCESS',
            'APPL_ACCESS',
            'IMPORT_EXPORT'
        */
        
        simpleQuery('', $query, $adminActions, false, false, true);
        
        foreach ($adminActions as $adminAction) {
            if (!$action->canExecute($adminAction["name"], $appId)) {
                $actionUrl = "?app=$appName&action=" . $adminAction["name"];
                $body[] = array(
                    "url" => $actionUrl,
                    "label" => $action->text($adminAction['short_name']) ,
                    "title" => (empty($adminAction["long_name"]) ? $action->text($adminAction['short_name']) : $action->text($adminAction['long_name']))
                );
            }
        }
        
        $return["body"] = $body;
    }
    catch(Exception $e) {
        $return["success"] = false;
        $return["error"][] = $e->getMessage();
        unset($return["body"]);
    }
    
    $action->lay->template = json_encode($return);
    $action->lay->noparse = true;
    header('Content-type: application/json');
}
