ALTER TABLE `ps_account`
ADD COLUMN `user_email`  varchar(255) NULL DEFAULT '' AFTER `update_time`;
ALTER TABLE `ps_login_log`
MODIFY COLUMN `request_ip`  int(11) UNSIGNED NOT NULL AFTER `user_uuid`;
ALTER TABLE `ps_account`
MODIFY COLUMN `user_avatar`  varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '/storage/app/images/avatar/default.png'
COMMENT '用户头像' AFTER `admin_password`;
ALTER TABLE `ps_admin_menu`
ADD COLUMN `pid`  int(11) NOT NULL DEFAULT 0 COMMENT '父级id' AFTER `id`;
/*2017-09-26 peijiyang*/
DROP TABLE `ps_admin_menu`
ALTER TABLE `ps_navigate`
MODIFY COLUMN `status`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态 1=>启用 2=>关闭' AFTER `title`,
MODIFY COLUMN `url`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '链接 /admin/account/updateAll' AFTER `status`,
MODIFY COLUMN `type`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '类型 1=>前台 2=>后台' AFTER `url`,
MODIFY COLUMN `create_time`  int(11) NOT NULL AFTER `type`,
ADD COLUMN `edit_admin_id`  int NULL AFTER `update_time`,
ADD COLUMN `pid`  int NULL DEFAULT 0 AFTER `edit_admin_id`,
ADD COLUMN `icon`  varchar(255) NULL DEFAULT 'am-icon-desktop' AFTER `pid`;

ALTER TABLE `ps_article`
ADD COLUMN `image`  varchar(255) NULL COMMENT '封面' AFTER `update_time`;

ALTER TABLE `ps_article`
ADD COLUMN `is_open`  tinyint NOT NULL DEFAULT 1 COMMENT '是否开放浏览 1 开放 2 不开放' AFTER `image`;

ALTER TABLE `ps_article`
ADD COLUMN `markdown`  longtext NULL COMMENT 'maekdown语法的存储数据' AFTER `is_open`;

ALTER TABLE `ps_comments`
ADD COLUMN `at_user_uuid`  char(36) NULL AFTER `user_uuid`;








