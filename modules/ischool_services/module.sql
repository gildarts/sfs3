#
# ��ƪ�榡�G ``
#
# �бN�z����ƪ� CREATE TABLE �y�k�m��U�C
# �Y�L�A�h�бN���� module.sql �R���C

CREATE TABLE  `ischool_session` (
	`uid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT  '�۰ʽs��',
	`ref_account_uid` INT NOT NULL COMMENT  '�b�����',
	`session_id` VARCHAR( 80 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT  'SessionID',
	`expiry_date` DATETIME NOT NULL COMMENT  '�����',
	UNIQUE (
		`session_id`
	)
) ENGINE = MYISAM ;