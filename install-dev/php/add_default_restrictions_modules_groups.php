<?php
/*
* 2007-2011 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2011 PrestaShop SA
*  @version  Release: $Revision: 6844 $
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
include(dirname(__FILE__).'/../../config/config.inc.php');
add_default_restrictions_modules_groups();

function add_default_restrictions_modules_groups()
{
	$groups = getGroups();
	$modules = getModulesInstalled();
	foreach ($groups as $group)
		addModulesRestrictions($group['id_group'], $modules);
}

function getGroups()
{
	return Db::getInstance()->ExecuteS('
		SELECT `id_group`
		FROM `'._DB_PREFIX_.'group`');
}

function getModulesInstalled()
{
	return Db::getInstance()->ExecuteS('
		SELECT m.*
		FROM `'._DB_PREFIX_.'module` m');
}

function addModulesRestrictions($id_group, $modules)
{
	if (!is_array($modules))
		return false;
	else
	{
		$sql = 'INSERT INTO `'._DB_PREFIX_.'group_module_restriction` (`id_group`, `id_module`, `authorized`) VALUES ';
		foreach ($modules as $mod)
			$sql .= '("'.(int)$id_group.'", "'.(int)$mod['id_module'].'", "1"),';
		// removing last comma to avoid SQL error
		$sql = substr($sql, 0, strlen($sql) - 1);
		Db::getInstance()->Execute($sql);
	}
}