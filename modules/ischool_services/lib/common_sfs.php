<?php
require_once 'class.ConnectionInfo.php';

function get_sfs_connection_info(){
    global $mysql_host, $mysql_user, $mysql_pass, $mysql_db;
    return new ConnectionInfo($mysql_host, $mysql_db, $mysql_user, $mysql_pass);
}

function teacher_sn_to_class_name($teacher_sn){
    global $CONN;
    $sql="select class_num from teacher_post where teacher_sn='$teacher_sn'";
    $rs=$CONN->Execute($sql);
    $class_num = $rs->fields["class_num"];
    if($class_num=="") throw_error('101','�z�S������ɮv�C');// trigger_error("�z�S������ɮv�I",E_USER_ERROR);
    $sel_year = curr_year(); //�ثe�Ǧ~
    $sel_seme = curr_seme(); //�ثe�Ǵ�

    $class_id=sprintf("%03d_%d_%02d_%02d",$sel_year,$sel_seme,substr($class_num,0,-2),substr($class_num,-2));
    $class_cname=class_id_to_full_class_name($class_id);
    $class_name[0]=$class_num;//�Ʀr
    $class_name[1]=$class_cname;//����
    $class_name[2]=$class_id;//����
    return $class_name;
}

//��class_id��X�X�~�X�Z
function  class_id_to_full_class_name($class_id){
    global $CONN;
    $class_sql="select * from school_class where class_id='$class_id' and enable=1";
    $rs_class=$CONN->Execute($class_sql);
    $c_year= $rs_class->fields['c_year'];
    $c_name= $rs_class->fields['c_name'];
    $school_kind_name=array("���X��","�@�~","�G�~","�T�~","�|�~","���~","���~","�@�~","�G�~","�T�~","�@�~","�G�~","�T�~");
    $full_year_class_name=$school_kind_name[$c_year];
    $full_year_class_name.=$c_name."�Z";
    return $full_year_class_name;
}

?>