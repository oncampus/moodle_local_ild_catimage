<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Local ild_catimage
 *
 * @package     local_ild_catimage
 * @copyright   2017 oncampus GmbH, <support@oncampus.de>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {
    $settings = new admin_settingpage('local_ild_catimage', get_string('pluginname', 'local_ild_catimage'));
    $ADMIN->add('localplugins', $settings);

    $settings->add(new admin_setting_configcheckbox('local_ild_catimage/active',
            get_string('active', 'local_ild_catimage'),
            get_string('active_desc', 'local_ild_catimage'),
            0)
    );

    $name = 'local_ild_catimage/max_users_per_cron';
    $title = get_string('max_users_per_cron', 'local_ild_catimage');
    $description = get_string('max_users_per_cron_desc', 'local_ild_catimage');
    $setting = new admin_setting_configtext($name, $title, $description, 1000, PARAM_INT);
    $settings->add($setting);

}

