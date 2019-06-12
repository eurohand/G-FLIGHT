<?PHP
if (!defined('_GLOBAL_NAYANA')) exit;

function get_pn_uid($pn_key, $pm_id){
    Global $TBL;
    
    $uid = dbfetch(" select uid from {$TBL['PN_MEMBER']} where pn_key='{$pn_key}' and pm_id='{$pm_id}' ");
    $return = "";
    if($uid['uid']){
        $return = $uid['uid'];
    }
    
    return $return;
}

function get_member_uid($uid, $fields='*'){
    Global $TBL;
    return dbfetch(" select $fields from {$TBL['PN_MEMBER']} where uid='{$uid}' ");
}

function get_pn_info($pn_key, $fields='*'){
    Global $TBL;
    return dbfetch(" select $fields from {$TBL['PARTNER']} where pn_key='{$pn_key}' ");
}

function get_pn_dinfo($pn_key, $fields='*'){
    Global $TBL;
    $pn = get_pn_info($pn_key);
    return dbfetch(" select $fields from {$TBL['PARTNER_DETAIL']} where uid='{$pn['uid']}' ");
}
?>