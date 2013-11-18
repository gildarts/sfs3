<?php
require_once 'class.ConnectionInfo.php';

function get_sfs_connection_info(){
    global $mysql_host, $mysql_user, $mysql_pass, $mysql_db;
    return new ConnectionInfo($mysql_host, $mysql_db, $mysql_user, $mysql_pass);
}

function is_module_installed(){
    global $CONN;
    $records = $CONN -> Execute("select showname from sfs_module where dirname = 'ischool_integration' and islive= 1;");
    if($records -> EOF)
        return false;
    else
        return true;
}

function do_account_link($role, $userid, $useruuid, $log_id, $log_pass){
    global $CONN;

    $sql = '';
    if ($role == "teacher") {
        $sql = "select teacher_sn target_sn from teacher_base where teach_condition = 0 and teach_id=? and login_pass=? and teach_id<>''";
    } else if ($role == 'student') {
        $sql = "select student_sn target_sn from stud_base where stud_id=? and stud_study_cond in (0,15) and email_pass=? and stud_id <>''";
    } else {
        throw_error(ERR_ROLE_ERROR, 'The role "'.$role.'" is not support.');
    }

    $log_pass = pass_operate(addslashes($log_pass));
    $records = $CONN -> Execute($sql, array($log_id, $log_pass)) or throw_error(ERR_SQL_ERROR, $CONN->ErrorMsg());

    if(!$CONN -> ErrorNo()) {
        list($target_sn) = $records -> FetchRow();

        if(isset($target_sn)){
            $linksql = 'insert into ischool_account(ref_target_sn, ref_target_role, account, uuid) values(?,?,?,?);';
                //throw_error(0, $target_sn.'|'.$role.'|'.$userid.'|'.$useruuid);

            $CONN -> Execute($linksql, array($target_sn, $role, $userid, $useruuid)) or throw_error(ERR_SQL_ERROR, $CONN->ErrorMsg());
        } else {
            throw_error(ERR_CREDENTIAL_INVALID, 'The credential is invalid.');
        }
    }
}
?>