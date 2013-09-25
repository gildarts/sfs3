#
# 資料表格式： ``
#
# 請將您的資料表 CREATE TABLE 語法置於下。
# 若無，則請將本檔 module.sql 刪除。

CREATE TABLE  `ischool_account` (
 `uid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT  '自動編號',
 `ref_target_sn` INT NOT NULL COMMENT  '參考 teacher_base、stud_base、parent_auth 的識別欄位',
 `ref_target_role` VARCHAR( 50 ) CHARACTER SET big5 COLLATE big5_chinese_ci NOT NULL COMMENT  '參考角色：teacher,student,parent',
 `account` VARCHAR( 150 ) CHARACTER SET big5 COLLATE big5_chinese_ci NOT NULL COMMENT  'ischool 帳號名稱',
 `uuid` VARCHAR( 100 ) CHARACTER SET big5 COLLATE big5_chinese_ci NOT NULL COMMENT  'ischool 帳號的全域唯一識別碼',
UNIQUE (`ref_target_sn`,`ref_target_role` ),
UNIQUE(`account`),
UNIQUE(`uuid`)
) ENGINE = MYISAM ;
