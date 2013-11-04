#
# 資料表格式： ``
#
# 請將您的資料表 CREATE TABLE 語法置於下。
# 若無，則請將本檔 module.sql 刪除。

CREATE TABLE  `ischool_session` (
	`uid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT  '自動編號',
	`ref_account_uid` INT NOT NULL COMMENT  '帳號資料',
	`session_id` VARCHAR( 80 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT  'SessionID',
	`expiry_date` DATETIME NOT NULL COMMENT  '到期日',
	UNIQUE (
		`session_id`
	)
) ENGINE = MYISAM ;