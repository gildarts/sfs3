#
# ��ƪ�榡�G ``
#
# �бN�z����ƪ� CREATE TABLE �y�k�m��U�C
# �Y�L�A�h�бN���� module.sql �R���C

CREATE TABLE  `ischool_account` (
 `uid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT  '�۰ʽs��',
 `ref_target_sn` INT NOT NULL COMMENT  '�Ѧ� teacher_base�Bstud_base�Bparent_auth ���ѧO���',
 `ref_target_role` VARCHAR( 50 ) CHARACTER SET big5 COLLATE big5_chinese_ci NOT NULL COMMENT  '�ѦҨ���Gteacher,student,parent',
 `account` VARCHAR( 150 ) CHARACTER SET big5 COLLATE big5_chinese_ci NOT NULL COMMENT  'ischool �b���W��',
 `uuid` VARCHAR( 100 ) CHARACTER SET big5 COLLATE big5_chinese_ci NOT NULL COMMENT  'ischool �b��������ߤ@�ѧO�X',
UNIQUE (`ref_target_sn`,`ref_target_role` ),
UNIQUE(`account`),
UNIQUE(`uuid`)
) ENGINE = MYISAM ;
