/*
Navicat MySQL Data Transfer

Source Server         : 自用-阿里云
Source Server Version : 50720
Source Host           : 39.108.175.233:3306
Source Database       : community

Target Server Type    : MYSQL
Target Server Version : 50720
File Encoding         : 65001

Date: 2019-07-15 06:36:34
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for com_admin_auth_access
-- ----------------------------
DROP TABLE IF EXISTS `com_admin_auth_access`;
CREATE TABLE `com_admin_auth_access` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL COMMENT '角色',
  `rule_name` varchar(100) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识,全小写',
  `type` varchar(30) NOT NULL DEFAULT '' COMMENT '权限规则分类,请加应用前缀,如admin_',
  PRIMARY KEY (`id`),
  UNIQUE KEY `rule` (`role_id`,`rule_name`,`type`) USING BTREE,
  KEY `role_id` (`role_id`),
  KEY `rule_name` (`rule_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8 COMMENT='权限授权表';

-- ----------------------------
-- Records of com_admin_auth_access
-- ----------------------------
INSERT INTO `com_admin_auth_access` VALUES ('9', '2', 'admin/adminindex/default', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('4', '2', 'admin/menu/add', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('5', '2', 'admin/menu/addpost', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('8', '2', 'admin/menu/delete', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('6', '2', 'admin/menu/edit', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('7', '2', 'admin/menu/editpost', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('2', '2', 'admin/menu/index', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('3', '2', 'admin/menu/lists', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('35', '2', 'admin/rbac/authorize', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('36', '2', 'admin/rbac/authorizepost', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('29', '2', 'admin/rbac/role', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('30', '2', 'admin/rbac/roleadd', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('31', '2', 'admin/rbac/roleaddpost', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('34', '2', 'admin/rbac/roledelete', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('32', '2', 'admin/rbac/roleedit', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('33', '2', 'admin/rbac/roleeditpost', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('1', '2', 'admin/setting/default', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('20', '2', 'admin/user/add', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('21', '2', 'admin/user/addpost', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('27', '2', 'admin/user/ban', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('28', '2', 'admin/user/cancelban', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('10', '2', 'admin/user/default', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('26', '2', 'admin/user/delete', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('22', '2', 'admin/user/edit', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('23', '2', 'admin/user/editpost', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('19', '2', 'admin/user/index', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('24', '2', 'admin/user/userinfo', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('25', '2', 'admin/user/userinfopost', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('37', '4', 'admin/adminindex/default', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('49', '4', 'admin/rbac/authorize', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('50', '4', 'admin/rbac/authorizepost', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('43', '4', 'admin/rbac/role', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('44', '4', 'admin/rbac/roleadd', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('45', '4', 'admin/rbac/roleaddpost', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('48', '4', 'admin/rbac/roledelete', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('46', '4', 'admin/rbac/roleedit', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('47', '4', 'admin/rbac/roleeditpost', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('51', '4', 'admin/user/add', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('52', '4', 'admin/user/addpost', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('58', '4', 'admin/user/ban', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('40', '4', 'admin/user/cancelban', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('38', '4', 'admin/user/default', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('57', '4', 'admin/user/delete', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('53', '4', 'admin/user/edit', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('54', '4', 'admin/user/editpost', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('39', '4', 'admin/user/index', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('55', '4', 'admin/user/userinfo', 'admin_');
INSERT INTO `com_admin_auth_access` VALUES ('56', '4', 'admin/user/userinfopost', 'admin_');

-- ----------------------------
-- Table structure for com_admin_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `com_admin_auth_rule`;
CREATE TABLE `com_admin_auth_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否有效(0:无效,1:有效)',
  `app` varchar(15) NOT NULL DEFAULT '' COMMENT '规则所属module',
  `type` varchar(30) NOT NULL DEFAULT '' COMMENT '权限规则分类，请加应用前缀,如admin_',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识,全小写',
  `param` varchar(100) NOT NULL DEFAULT '' COMMENT '额外url参数',
  `title` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '规则描述',
  `condition` varchar(200) NOT NULL DEFAULT '' COMMENT '规则附加条件',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`) USING BTREE,
  KEY `module` (`app`,`status`,`type`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COMMENT='权限规则表';

-- ----------------------------
-- Records of com_admin_auth_rule
-- ----------------------------
INSERT INTO `com_admin_auth_rule` VALUES ('1', '1', 'admin', 'admin_url', 'admin/setting/default', '', '系统设置', '');
INSERT INTO `com_admin_auth_rule` VALUES ('2', '1', 'admin', 'admin_url', 'admin/adminindex/default', '', '用户管理', '');
INSERT INTO `com_admin_auth_rule` VALUES ('3', '1', 'admin', 'admin_url', 'admin/user/default', '', '管理组', '');
INSERT INTO `com_admin_auth_rule` VALUES ('4', '1', 'admin', 'admin_url', 'admin/rbac/role', '', '角色管理', '');
INSERT INTO `com_admin_auth_rule` VALUES ('5', '1', 'admin', 'admin_url', 'admin/rbac/roleadd', '', '添加角色', '');
INSERT INTO `com_admin_auth_rule` VALUES ('6', '1', 'admin', 'admin_url', 'admin/rbac/roleaddpost', '', '添加角色提交', '');
INSERT INTO `com_admin_auth_rule` VALUES ('7', '1', 'admin', 'admin_url', 'admin/rbac/roleedit', '', '编辑角色', '');
INSERT INTO `com_admin_auth_rule` VALUES ('8', '1', 'admin', 'admin_url', 'admin/rbac/roleeditpost', '', '编辑角色提交', '');
INSERT INTO `com_admin_auth_rule` VALUES ('9', '1', 'admin', 'admin_url', 'admin/rbac/roledelete', '', '删除角色', '');
INSERT INTO `com_admin_auth_rule` VALUES ('10', '1', 'admin', 'admin_url', 'admin/rbac/authorize', '', '设置角色权限', '');
INSERT INTO `com_admin_auth_rule` VALUES ('11', '1', 'admin', 'admin_url', 'admin/rbac/authorizepost', '', '角色授权提交', '');
INSERT INTO `com_admin_auth_rule` VALUES ('12', '1', 'admin', 'admin_url', 'admin/user/index', '', '管理员', '');
INSERT INTO `com_admin_auth_rule` VALUES ('13', '1', 'admin', 'admin_url', 'admin/user/add', '', '管理员添加', '');
INSERT INTO `com_admin_auth_rule` VALUES ('14', '1', 'admin', 'admin_url', 'admin/user/addpost', '', '管理员添加提交', '');
INSERT INTO `com_admin_auth_rule` VALUES ('15', '1', 'admin', 'admin_url', 'admin/user/edit', '', '管理员编辑', '');
INSERT INTO `com_admin_auth_rule` VALUES ('16', '1', 'admin', 'admin_url', 'admin/user/editpost', '', '管理员编辑提交', '');
INSERT INTO `com_admin_auth_rule` VALUES ('17', '1', 'admin', 'admin_url', 'admin/user/userinfo', '', '个人信息', '');
INSERT INTO `com_admin_auth_rule` VALUES ('18', '1', 'admin', 'admin_url', 'admin/user/userinfopost', '', '管理员个人信息修改提交', '');
INSERT INTO `com_admin_auth_rule` VALUES ('19', '1', 'admin', 'admin_url', 'admin/user/delete', '', '管理员删除', '');
INSERT INTO `com_admin_auth_rule` VALUES ('20', '1', 'admin', 'admin_url', 'admin/user/ban', '', '停用管理员', '');
INSERT INTO `com_admin_auth_rule` VALUES ('21', '1', 'admin', 'admin_url', 'admin/user/cancelban', '', '启用管理员', '');
INSERT INTO `com_admin_auth_rule` VALUES ('22', '1', 'admin', 'admin_url', 'admin/menu/index', '', '系统菜单', '');
INSERT INTO `com_admin_auth_rule` VALUES ('23', '1', 'admin', 'admin_url', 'admin/menu/lists', '', '菜单列表', '');
INSERT INTO `com_admin_auth_rule` VALUES ('24', '1', 'admin', 'admin_url', 'admin/menu/add', '', '后台菜单添加', '');
INSERT INTO `com_admin_auth_rule` VALUES ('25', '1', 'admin', 'admin_url', 'admin/menu/addpost', '', '后台菜单添加提交保存', '');
INSERT INTO `com_admin_auth_rule` VALUES ('26', '1', 'admin', 'admin_url', 'admin/menu/edit', '', '后台菜单编辑', '');
INSERT INTO `com_admin_auth_rule` VALUES ('27', '1', 'admin', 'admin_url', 'admin/menu/editpost', '', '后台菜单编辑提交保存', '');
INSERT INTO `com_admin_auth_rule` VALUES ('28', '1', 'admin', 'admin_url', 'admin/menu/delete', '', '后台菜单删除', '');
INSERT INTO `com_admin_auth_rule` VALUES ('29', '1', 'admin', 'admin_url', 'admin/article/default', '', '文章管理', '');
INSERT INTO `com_admin_auth_rule` VALUES ('30', '1', 'admin', 'admin_url', 'admin/article/index', '', '文章列表', '');
INSERT INTO `com_admin_auth_rule` VALUES ('31', '1', 'admin', 'admin_url', 'admin/article/add', '', '文章添加', '');
INSERT INTO `com_admin_auth_rule` VALUES ('32', '1', 'admin', 'admin_url', 'admin/article/edit', '', '文章编辑', '');
INSERT INTO `com_admin_auth_rule` VALUES ('33', '1', 'admin', 'admin_url', 'admin/acticle/editpost', '', '文章编辑提交', '');
INSERT INTO `com_admin_auth_rule` VALUES ('34', '1', 'admin', 'admin_url', 'admin/article/operate', '', '文章操作', '');
INSERT INTO `com_admin_auth_rule` VALUES ('35', '1', 'admin', 'admin_url', 'admin/article/addpost', '', '文章添加提交', '');
INSERT INTO `com_admin_auth_rule` VALUES ('36', '1', 'admin', 'admin_url', 'admin/article/delete', '', '文章删除', '');
INSERT INTO `com_admin_auth_rule` VALUES ('37', '1', 'admin', 'admin_url', 'admin/category/default', '', '文章类别', '');
INSERT INTO `com_admin_auth_rule` VALUES ('38', '1', 'admin', 'admin_url', 'admin/category/index', '', '文章分类', '');
INSERT INTO `com_admin_auth_rule` VALUES ('40', '1', 'admin', 'admin_url', 'admin/category/add', '', '文章分类添加', '');
INSERT INTO `com_admin_auth_rule` VALUES ('41', '1', 'admin', 'admin_url', 'admin/category/addpost', '', '文章分类添加提交', '');
INSERT INTO `com_admin_auth_rule` VALUES ('42', '1', 'admin', 'admin_url', 'admin/category/edit', '', '文章分类编辑', '');
INSERT INTO `com_admin_auth_rule` VALUES ('43', '1', 'admin', 'admin_url', 'admin/category/editpost', '', '文章分类编辑提交', '');
INSERT INTO `com_admin_auth_rule` VALUES ('44', '1', 'admin', 'admin_url', 'admin/category/delete', '', '文章分类删除', '');
INSERT INTO `com_admin_auth_rule` VALUES ('47', '1', 'admin', 'admin_url', 'test/test/test', '', '测试', '');
INSERT INTO `com_admin_auth_rule` VALUES ('48', '1', 'admin', 'admin_url', 'test/test/son', '', '测试子菜单', '');
INSERT INTO `com_admin_auth_rule` VALUES ('49', '1', 'admin', 'admin_url', 'admin/article/config', '', '文章配置', '');
INSERT INTO `com_admin_auth_rule` VALUES ('50', '1', 'admin', 'admin_url', 'admin/article/configpost', '', '文章配置提交', '');

-- ----------------------------
-- Table structure for com_admin_menu
-- ----------------------------
DROP TABLE IF EXISTS `com_admin_menu`;
CREATE TABLE `com_admin_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父菜单id',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '菜单类型;1:有界面可访问菜单,2:无界面可访问菜单,0:只作为菜单',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态;1:显示,0:不显示',
  `list_order` float NOT NULL DEFAULT '10000' COMMENT '排序',
  `level_path` varchar(100) NOT NULL DEFAULT '0' COMMENT '父子级关系路径',
  `app` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '应用名',
  `controller` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '控制器名',
  `action` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '操作名称',
  `param` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '额外参数',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '菜单名称',
  `icon` varchar(20) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '菜单图标',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `parentid` (`parent_id`),
  KEY `model` (`controller`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COMMENT='后台菜单表';

-- ----------------------------
-- Records of com_admin_menu
-- ----------------------------
INSERT INTO `com_admin_menu` VALUES ('1', '0', '0', '1', '10000', '0', 'admin', 'Setting', 'default', '', '系统设置', 'system', '系统设置');
INSERT INTO `com_admin_menu` VALUES ('2', '0', '0', '1', '9999', '0', 'admin', 'AdminIndex', 'default', '', '用户管理', 'user', '用户管理');
INSERT INTO `com_admin_menu` VALUES ('3', '2', '0', '1', '10000', '0-2', 'admin', 'User', 'default', '', '管理组', '', '管理组');
INSERT INTO `com_admin_menu` VALUES ('4', '3', '1', '1', '10000', '0-2-3', 'admin', 'Rbac', 'role', '', '角色管理', '', '角色管理');
INSERT INTO `com_admin_menu` VALUES ('5', '4', '1', '0', '10000', '0-2-3-4', 'admin', 'Rbac', 'roleAdd', '', '添加角色', '', '添加角色');
INSERT INTO `com_admin_menu` VALUES ('6', '4', '2', '0', '10000', '0-2-3-4', 'admin', 'Rbac', 'roleAddPost', '', '添加角色提交', '', '添加角色提交');
INSERT INTO `com_admin_menu` VALUES ('7', '4', '1', '0', '10000', '0-2-3-4', 'admin', 'Rbac', 'roleEdit', '', '编辑角色', '', '编辑角色');
INSERT INTO `com_admin_menu` VALUES ('8', '4', '2', '0', '10000', '0-2-3-4', 'admin', 'Rbac', 'roleEditPost', '', '编辑角色提交', '', '编辑角色提交');
INSERT INTO `com_admin_menu` VALUES ('9', '4', '2', '0', '10000', '0-2-3-4', 'admin', 'Rbac', 'roleDelete', '', '删除角色', '', '删除角色');
INSERT INTO `com_admin_menu` VALUES ('10', '4', '1', '0', '10000', '0-2-3-4', 'admin', 'Rbac', 'authorize', '', '设置角色权限', '', '设置角色权限');
INSERT INTO `com_admin_menu` VALUES ('11', '4', '2', '0', '10000', '0-2-3-4', 'admin', 'Rbac', 'authorizePost', '', '角色授权提交', '', '角色授权提交');
INSERT INTO `com_admin_menu` VALUES ('12', '3', '1', '1', '10000', '0-2-3', 'admin', 'User', 'index', '', '管理员', '', '管理员管理');
INSERT INTO `com_admin_menu` VALUES ('13', '12', '1', '0', '10000', '0-2-3-12', 'admin', 'User', 'add', '', '管理员添加', '', '管理员添加');
INSERT INTO `com_admin_menu` VALUES ('14', '12', '2', '0', '10000', '0-2-3-12', 'admin', 'User', 'addPost', '', '管理员添加提交', '', '管理员添加提交');
INSERT INTO `com_admin_menu` VALUES ('15', '12', '1', '0', '10000', '0-2-3-12', 'admin', 'User', 'edit', '', '管理员编辑', '', '管理员编辑');
INSERT INTO `com_admin_menu` VALUES ('16', '12', '2', '0', '10000', '0-2-3-12', 'admin', 'User', 'editPost', '', '管理员编辑提交', '', '管理员编辑提交');
INSERT INTO `com_admin_menu` VALUES ('17', '12', '1', '0', '10000', '0-2-3-12', 'admin', 'User', 'userInfo', '', '个人信息', '', '管理员个人信息修改');
INSERT INTO `com_admin_menu` VALUES ('18', '12', '2', '0', '10000', '0-2-3-12', 'admin', 'User', 'userInfoPost', '', '管理员个人信息修改提交', '', '管理员个人信息修改提交');
INSERT INTO `com_admin_menu` VALUES ('19', '12', '2', '0', '10000', '0-2-3-12', 'admin', 'User', 'delete', '', '管理员删除', '', '管理员删除');
INSERT INTO `com_admin_menu` VALUES ('20', '12', '2', '0', '10000', '0-2-3-12', 'admin', 'User', 'ban', '', '停用管理员', '', '停用管理员');
INSERT INTO `com_admin_menu` VALUES ('21', '12', '2', '0', '10000', '0-2-3-12', 'admin', 'User', 'cancelBan', '', '启用管理员', '', '启用管理员');
INSERT INTO `com_admin_menu` VALUES ('30', '1', '0', '1', '10000', '0-1', 'admin', 'Menu', 'index', '', '系统菜单', '', '系统菜单');
INSERT INTO `com_admin_menu` VALUES ('31', '30', '1', '1', '10000', '0-1-30', 'admin', 'Menu', 'lists', '', '菜单列表', '', '菜单列表');
INSERT INTO `com_admin_menu` VALUES ('32', '30', '1', '1', '10000', '0-1-30', 'admin', 'Menu', 'add', '', '菜单添加', '', '后台菜单添加');
INSERT INTO `com_admin_menu` VALUES ('33', '30', '2', '0', '10000', '0-1-30', 'admin', 'Menu', 'addPost', '', '后台菜单添加提交保存', '', '后台菜单添加提交保存');
INSERT INTO `com_admin_menu` VALUES ('34', '30', '1', '0', '10000', '0-1-30', 'admin', 'Menu', 'edit', '', '后台菜单编辑', '', '后台菜单编辑');
INSERT INTO `com_admin_menu` VALUES ('35', '30', '2', '0', '10000', '0-1-30', 'admin', 'Menu', 'editPost', '', '后台菜单编辑提交保存', '', '后台菜单编辑提交保存');
INSERT INTO `com_admin_menu` VALUES ('36', '30', '2', '0', '10000', '0-1-30', 'admin', 'Menu', 'delete', '', '后台菜单删除', '', '后台菜单删除');
INSERT INTO `com_admin_menu` VALUES ('37', '0', '0', '1', '9000', '0', 'admin', 'Article', 'default', '', '文章管理', 'news', '文章管理');
INSERT INTO `com_admin_menu` VALUES ('38', '37', '1', '1', '10000', '0-37', 'admin', 'Article', 'index', '', '文章列表', '', '文章列表');
INSERT INTO `com_admin_menu` VALUES ('39', '37', '1', '1', '10000', '0-37', 'admin', 'Article', 'add', '', '文章添加', '', '文章添加');
INSERT INTO `com_admin_menu` VALUES ('40', '38', '2', '0', '10000', '0-37-38', 'admin', 'Article', 'addPost', '', '文章添加提交', '', '文章添加提交');
INSERT INTO `com_admin_menu` VALUES ('41', '38', '1', '0', '10000', '0-37-38', 'admin', 'Article', 'edit', '', '文章编辑', '', '文章编辑');
INSERT INTO `com_admin_menu` VALUES ('42', '38', '2', '0', '10000', '0-37-38', 'admin', 'Acticle', 'editPost', '', '文章编辑提交', '', '文章编辑提交');
INSERT INTO `com_admin_menu` VALUES ('43', '38', '2', '0', '10000', '0-37-38', 'admin', 'Article', 'operate', '', '文章操作', '', '文章操作');
INSERT INTO `com_admin_menu` VALUES ('44', '38', '2', '0', '10000', '0-37-38', 'admin', 'Article', 'delete', '', '文章删除', '', '文章删除');
INSERT INTO `com_admin_menu` VALUES ('45', '37', '0', '1', '10000', '0-37', 'admin', 'Category', 'default', '', '文章类别', '', '文章类别');
INSERT INTO `com_admin_menu` VALUES ('46', '45', '1', '1', '900', '0-37-45', 'admin', 'Category', 'index', '', '文章分类', '', '文章分类');
INSERT INTO `com_admin_menu` VALUES ('47', '46', '1', '0', '10000', '0-37-45-46', 'admin', 'Category', 'add', '', '文章分类添加', '', '文章分类添加');
INSERT INTO `com_admin_menu` VALUES ('48', '46', '2', '0', '10000', '0-37-45-46', 'admin', 'Category', 'addPost', '', '文章分类添加提交', '', '文章分类添加提交');
INSERT INTO `com_admin_menu` VALUES ('49', '46', '1', '0', '10000', '0-37-45-46', 'admin', 'Category', 'edit', '', '文章分类编辑', '', '文章分类编辑');
INSERT INTO `com_admin_menu` VALUES ('50', '46', '2', '0', '10000', '0-37-45-46', 'admin', 'Category', 'editPost', '', '文章分类编辑提交', '', '文章分类编辑提交');
INSERT INTO `com_admin_menu` VALUES ('51', '46', '2', '0', '10000', '0-37-45-46', 'admin', 'Category', 'delete', '', '文章分类删除', '', '文章分类删除');
INSERT INTO `com_admin_menu` VALUES ('52', '0', '1', '0', '10000', '0', 'test', 'test', 'test', '', '测试', 'home', '测试');
INSERT INTO `com_admin_menu` VALUES ('53', '52', '1', '1', '10000', '0-52', 'test', 'test', 'son', '', '测试子菜单', 'home', '测试子菜单');
INSERT INTO `com_admin_menu` VALUES ('54', '37', '1', '1', '10000', '0-37', 'admin', 'Article', 'config', '', '文章配置', 'home', '文章配置');
INSERT INTO `com_admin_menu` VALUES ('55', '54', '2', '0', '10000', '0-37-54', 'admin', 'Article', 'configPost', '', '文章配置提交', 'home', '文章配置提交');

-- ----------------------------
-- Table structure for com_admin_role
-- ----------------------------
DROP TABLE IF EXISTS `com_admin_role`;
CREATE TABLE `com_admin_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父角色ID',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态;0:禁用;1:正常',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `list_order` float NOT NULL DEFAULT '0' COMMENT '排序',
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '角色名称',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`id`),
  KEY `parentId` (`parent_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='角色表';

-- ----------------------------
-- Records of com_admin_role
-- ----------------------------
INSERT INTO `com_admin_role` VALUES ('1', '0', '1', '1329633709', '1329633709', '0', '超级管理员', '拥有网站最高管理员权限！');
INSERT INTO `com_admin_role` VALUES ('2', '0', '1', '1515286578', '0', '10', '普通管理员', '拥有所有权限');

-- ----------------------------
-- Table structure for com_admin_role_user
-- ----------------------------
DROP TABLE IF EXISTS `com_admin_role_user`;
CREATE TABLE `com_admin_role_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '角色 id',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`) USING BTREE,
  KEY `group_id` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COMMENT='用户角色对应表';

-- ----------------------------
-- Records of com_admin_role_user
-- ----------------------------
INSERT INTO `com_admin_role_user` VALUES ('1', '1', '1');
INSERT INTO `com_admin_role_user` VALUES ('2', '2', '2');
INSERT INTO `com_admin_role_user` VALUES ('3', '2', '3');
INSERT INTO `com_admin_role_user` VALUES ('4', '2', '4');
INSERT INTO `com_admin_role_user` VALUES ('5', '2', '5');
INSERT INTO `com_admin_role_user` VALUES ('6', '2', '6');
INSERT INTO `com_admin_role_user` VALUES ('7', '2', '7');
INSERT INTO `com_admin_role_user` VALUES ('8', '2', '8');
INSERT INTO `com_admin_role_user` VALUES ('9', '2', '9');
INSERT INTO `com_admin_role_user` VALUES ('10', '2', '10');
INSERT INTO `com_admin_role_user` VALUES ('11', '2', '11');
INSERT INTO `com_admin_role_user` VALUES ('12', '2', '12');
INSERT INTO `com_admin_role_user` VALUES ('13', '3', '13');
INSERT INTO `com_admin_role_user` VALUES ('14', '2', '14');
INSERT INTO `com_admin_role_user` VALUES ('15', '2', '15');
INSERT INTO `com_admin_role_user` VALUES ('16', '2', '16');
INSERT INTO `com_admin_role_user` VALUES ('17', '3', '17');
INSERT INTO `com_admin_role_user` VALUES ('18', '2', '18');
INSERT INTO `com_admin_role_user` VALUES ('19', '2', '19');
INSERT INTO `com_admin_role_user` VALUES ('20', '3', '20');
INSERT INTO `com_admin_role_user` VALUES ('21', '2', '21');
INSERT INTO `com_admin_role_user` VALUES ('22', '2', '23');
INSERT INTO `com_admin_role_user` VALUES ('23', '2', '0');
INSERT INTO `com_admin_role_user` VALUES ('24', '2', '22');

-- ----------------------------
-- Table structure for com_admin_user
-- ----------------------------
DROP TABLE IF EXISTS `com_admin_user`;
CREATE TABLE `com_admin_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `user_pass` varchar(64) NOT NULL DEFAULT '' COMMENT '登录密码;cmf_password加密',
  `user_nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户昵称',
  `user_mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '用户手机号',
  `user_email` varchar(100) NOT NULL DEFAULT '' COMMENT '用户登录邮箱',
  `last_login_ip` varchar(15) NOT NULL DEFAULT '' COMMENT '最后登录ip',
  `last_login_add` varchar(100) NOT NULL DEFAULT '' COMMENT '最后登录地址',
  `last_login_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '注册时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '用户状态;0:禁用,1:正常,2:未验证',
  `more` text COMMENT '扩展属性',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_login` (`user_login`) USING BTREE,
  KEY `user_nickname` (`user_nickname`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COMMENT='用户表';

-- ----------------------------
-- Records of com_admin_user
-- ----------------------------
INSERT INTO `com_admin_user` VALUES ('1', 'admin', '###dcb8f761266df76974916e893a7b8295', '知鱼', '13922453491', '1206293024@qq.com', '119.129.204.199', '127.0.0.1', '1563141562', '1513409733', '1', null);
INSERT INTO `com_admin_user` VALUES ('2', 'test1', '###cce0fd00175e384269b691367c1b10b6', '大寒', '18011720837', '02713074171@qq.com', '', '', '1515219623', '1513409733', '1', '');
INSERT INTO `com_admin_user` VALUES ('3', 'test2', '###dcb8f761266df76974916e893a7b8295', '小寒', '13811111111', '1206293024@qq.com', '', '', '1515219623', '1513409733', '1', '');
INSERT INTO `com_admin_user` VALUES ('4', 'test3', '###a06c1c380f7dfaf5df975099bd41ae44', '夏至', '13811111111', '1206293024@qq.com', '', '', '1515219623', '1513409733', '1', '');
INSERT INTO `com_admin_user` VALUES ('5', 'test4', '###dcb8f761266df76974916e893a7b8295', '冬至', '13811111111', '1206293024@qq.com', '', '', '1515219623', '1513409733', '1', '');
INSERT INTO `com_admin_user` VALUES ('6', 'test5', '###dcb8f761266df76974916e893a7b8295', '春分', '18011720837', '2713074171@qq.com', '', '', '1515219623', '1513409733', '1', '');
INSERT INTO `com_admin_user` VALUES ('7', 'test6', '###dcb8f761266df76974916e893a7b8295', '秋分', '13811111111', '1206293024@qq.com', '', '', '1515219623', '1513409733', '1', '');
INSERT INTO `com_admin_user` VALUES ('8', 'test7', '###dcb8f761266df76974916e893a7b8295', '惊蛰', '13811111111', '1206293024@qq.com', '', '', '1515219623', '1513009733', '1', '');
INSERT INTO `com_admin_user` VALUES ('9', 'test8', '###dcb8f761266df76974916e893a7b8295', '小明', '13811111111', '1206293024@qq.com', '', '', '1515219623', '1513009733', '0', '');
INSERT INTO `com_admin_user` VALUES ('10', 'test9', '###dcb8f761266df76974916e893a7b8295', '小红', '13811111113', '1206293024@qq.com', '', '', '1515219623', '1513009733', '1', '');
INSERT INTO `com_admin_user` VALUES ('11', 'test10', '###dcb8f761266df76974916e893a7b8295', '小米', '13811111111', '1206293024@qq.com', '', '', '1515219623', '1513009733', '1', '');
INSERT INTO `com_admin_user` VALUES ('12', 'test11', '###dcb8f761266df76974916e893a7b8295', '麦克', '13922453491', '1206293024@qq.com', '219.137.143.231', '广东省广州市', '1517187188', '1513009733', '1', '');
INSERT INTO `com_admin_user` VALUES ('13', 'test12', '###dcb8f761266df76974916e893a7b8295', '麦克斯', '18011720837', '2713074171@qq.com', '', '', '1515219623', '1513409733', '1', '');
INSERT INTO `com_admin_user` VALUES ('14', 'test13', '###dcb8f761266df76974916e893a7b8295', '贾克斯', '13811111111', '1206293024@qq.com', '', '', '1515219623', '1513409733', '0', '');
INSERT INTO `com_admin_user` VALUES ('15', 'test14', '###dcb8f761266df76974916e893a7b8295', '德玛', '13811111111', '1206293024@qq.com', '', '', '1515219623', '1513409733', '1', '');
INSERT INTO `com_admin_user` VALUES ('16', 'test15', '###dcb8f761266df76974916e893a7b8295', '潘舍', '13811111111', '1206293024@qq.com', '', '', '1515219623', '1513409733', '1', '');
INSERT INTO `com_admin_user` VALUES ('17', 'test16', '###dcb8f761266df76974916e893a7b8295', '寒冰', '18011720837', '2713074171@qq.com', '', '', '1515219623', '1513409733', '1', '');
INSERT INTO `com_admin_user` VALUES ('18', 'test17', '###dcb8f761266df76974916e893a7b8295', '女皇', '13811111111', '1206293024@qq.com', '', '', '1515219623', '1513409733', '1', '');
INSERT INTO `com_admin_user` VALUES ('19', 'test18', '###dcb8f761266df76974916e893a7b8295', '亚瑟', '13811111111', '1206293024@qq.com', '', '', '1515219623', '1513409733', '1', '');
INSERT INTO `com_admin_user` VALUES ('20', 'test19', '###dcb8f761266df76974916e893a7b8295', '赵云', '13811111111', '1206293024@qq.com', '', '', '1515219623', '1513409733', '1', '');
INSERT INTO `com_admin_user` VALUES ('21', 'test20', '###dcb8f761266df76974916e893a7b8295', '关于', '13922232223', '1206293024@qq.com', '', '', '1515219623', '1513409733', '1', '');
INSERT INTO `com_admin_user` VALUES ('22', 'test21', '###dcb8f761266df76974916e893a7b8295', '貂蝉', '13811111112', '1206293024@qq.com', '', '', '1515219623', '1513409733', '1', '');
INSERT INTO `com_admin_user` VALUES ('23', 'test22', '###dcb8f761266df76974916e893a7b8295', '猪八戒', '13811111112', '1206293024@qq.com', '', '', '1515219623', '1513409733', '1', '');

-- ----------------------------
-- Table structure for com_article
-- ----------------------------
DROP TABLE IF EXISTS `com_article`;
CREATE TABLE `com_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '文章标题',
  `brief_title` varchar(255) NOT NULL DEFAULT '' COMMENT '简略标题',
  `cate_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '分类栏目id',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '文章类型',
  `keyword` varchar(255) NOT NULL DEFAULT '' COMMENT '关键词，逗号连接',
  `summary` varchar(512) NOT NULL DEFAULT '' COMMENT '文章摘要',
  `summary_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '摘要是否显示文章之前，1显示 0不显示',
  `author` int(11) NOT NULL DEFAULT '0' COMMENT '作者',
  `source` varchar(512) NOT NULL DEFAULT '' COMMENT '文章来源',
  `comment_auth` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否允许评论， 1允许 0不允许',
  `comment_start` int(11) unsigned DEFAULT NULL COMMENT '评论开始日期',
  `comment_end` int(11) DEFAULT NULL COMMENT '评论介绍日期',
  `thumbnail` varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图',
  `content` text COMMENT '文章内容',
  `content_basic` text COMMENT '文章内容（不带html标签）',
  `view_times` int(11) NOT NULL DEFAULT '0' COMMENT '文章访问次数',
  `create_time` int(11) DEFAULT NULL COMMENT '文章创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '文章更新时间',
  `open_time` int(11) DEFAULT NULL COMMENT '文章发布时间',
  `close_time` int(11) DEFAULT NULL COMMENT '文章关闭时间（为0表示不关闭）',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '文章状态，0草稿 1普通文章（已发布、发布中、已结束） 2私密文章（未公开）',
  `auto_hold` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1系统自动存档 0用户已保存/发布',
  PRIMARY KEY (`id`),
  KEY `title` (`title`) USING BTREE,
  KEY `cate_id` (`cate_id`) USING BTREE,
  KEY `author` (`author`) USING BTREE,
  KEY `open_time` (`open_time`) USING BTREE,
  KEY `close_time` (`close_time`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of com_article
-- ----------------------------
INSERT INTO `com_article` VALUES ('1', '欢迎访问sudophp', 'welcome-to-sudophp', '4', '0', '', '', '0', '1', '', '1', '0', '0', '/static/images/thumb/3.jpg', '<p>欢迎来到本博客。</p><p>本博客基于thinkphp开发，风格简约，以文章模块为主，易于二次开发扩展，seo友好的文章链接，多套前端模版，功能持续完善中</p>', '欢迎来到本博客。本博客基于thinkphp开发，风格简约，以文章模块为主，易于二次开发扩展，seo友好的文章链接，多套前端模版，功能持续完善中', '246', '1563071859', '1563071948', '0', '0', '1', '0');

-- ----------------------------
-- Table structure for com_article_category
-- ----------------------------
DROP TABLE IF EXISTS `com_article_category`;
CREATE TABLE `com_article_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '父级栏目id',
  `cate_name` varchar(100) NOT NULL DEFAULT '' COMMENT '分类栏目名',
  `cate_alias` varchar(50) NOT NULL DEFAULT '' COMMENT '分类栏目别名（英文）',
  `seo_title` varchar(100) DEFAULT '' COMMENT 'seo标题',
  `seo_keyword` varchar(100) DEFAULT '' COMMENT 'seo关键字',
  `seo_description` varchar(200) DEFAULT '' COMMENT 'seo描述',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `list_order` int(11) unsigned NOT NULL DEFAULT '10000' COMMENT '排序',
  `status` tinyint(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of com_article_category
-- ----------------------------
INSERT INTO `com_article_category` VALUES ('4', '0', '个人杂谈', 'tattle', '', '', '', '0', '10000', '1');

-- ----------------------------
-- Table structure for com_article_todo
-- ----------------------------
DROP TABLE IF EXISTS `com_article_todo`;
CREATE TABLE `com_article_todo` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of com_article_todo
-- ----------------------------

-- ----------------------------
-- Table structure for com_log
-- ----------------------------
DROP TABLE IF EXISTS `com_log`;
CREATE TABLE `com_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `from` varchar(1024) DEFAULT '' COMMENT '来源',
  `msg` varchar(1024) DEFAULT '' COMMENT '信息',
  `addtime` int(11) DEFAULT '0' COMMENT '时间戳',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of com_log
-- ----------------------------

-- ----------------------------
-- Table structure for com_setting
-- ----------------------------
DROP TABLE IF EXISTS `com_setting`;
CREATE TABLE `com_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(100) NOT NULL DEFAULT '' COMMENT '模块',
  `class` varchar(100) NOT NULL DEFAULT '' COMMENT '配置的分类标签',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '注释',
  `key` varchar(100) NOT NULL DEFAULT '' COMMENT 'key',
  `value` varchar(1024) NOT NULL DEFAULT '' COMMENT 'value',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0普通string 1json 2switch 3integer 4select',
  `param` varchar(512) NOT NULL DEFAULT '' COMMENT '参数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of com_setting
-- ----------------------------
INSERT INTO `com_setting` VALUES ('1', 'admin', 'menu', '后台菜单标签', 'menu_label', '{\"1\":\"平台\",\"2\":\"文章\",\"3\":\"用户\",\"40\":\"系统\"}', '1', '');
INSERT INTO `com_setting` VALUES ('2', 'admin', 'article', '文章列表每页条数', 'article_per_count', '10', '4', '[5,10,20,50,100]');
INSERT INTO `com_setting` VALUES ('3', 'admin', 'article', '文章编辑是否自动存档', 'is_auto_hold', '1', '2', '');
INSERT INTO `com_setting` VALUES ('4', 'admin', 'article', '每n分钟自动存档', 'duration_auto_hold', '1', '3', '[1,60]');
INSERT INTO `com_setting` VALUES ('5', 'admin', 'basic', '单页条数', 'page_per_count', '23', '4', '[5,10,20,50,100]');

-- ----------------------------
-- Table structure for com_user
-- ----------------------------
DROP TABLE IF EXISTS `com_user`;
CREATE TABLE `com_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '用户类型;1:admin;2:会员',
  `sex` tinyint(2) NOT NULL DEFAULT '0' COMMENT '性别;0:保密,1:男,2:女',
  `birthday` int(11) NOT NULL DEFAULT '0' COMMENT '生日',
  `last_login_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '用户积分',
  `coin` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '金币',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '注册时间',
  `user_status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '用户状态;0:禁用,1:正常,2:未验证',
  `user_login` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `user_pass` varchar(64) NOT NULL DEFAULT '' COMMENT '登录密码;cmf_password加密',
  `user_nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户昵称',
  `user_email` varchar(100) NOT NULL DEFAULT '' COMMENT '用户登录邮箱',
  `user_url` varchar(100) NOT NULL DEFAULT '' COMMENT '用户个人网址',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '用户头像',
  `signature` varchar(255) NOT NULL DEFAULT '' COMMENT '个性签名',
  `last_login_ip` varchar(15) NOT NULL DEFAULT '' COMMENT '最后登录ip',
  `user_activation_key` varchar(60) NOT NULL DEFAULT '' COMMENT '激活码',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '用户手机号',
  `more` text COMMENT '扩展属性',
  PRIMARY KEY (`id`),
  KEY `user_login` (`user_login`),
  KEY `user_nickname` (`user_nickname`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='用户表';

-- ----------------------------
-- Records of com_user
-- ----------------------------
INSERT INTO `com_user` VALUES ('1', '1', '0', '0', '1515219623', '0', '0', '1513409733', '1', 'admin', '###dcb8f761266df76974916e893a7b8295', 'admin', '1206293024@qq.com', '', '', '', '219.137.142.142', '', '', null);
INSERT INTO `com_user` VALUES ('2', '1', '0', '0', '1515219623', '0', '0', '1513409733', '1', 'test', '###dcb8f761266df76974916e893a7b8295', 'admin', '1206293024@qq.com', '', '', '', '219.137.142.142', '', '', '');
