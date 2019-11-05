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

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/gdlib.php');

function ildcourseinfo_cron() {
    global $DB, $CFG;

    if (!get_config('local_ild_catimage', 'active')) {
        return;
    }

    mtrace('update catcontent ^^');

    $sql = 'SELECT id, username
              FROM {user}
             WHERE picture = :picture
               AND deleted != :deleted';

    $params = array('picture' => 0,
        'deleted' => 1,
        'usercount' => intval(get_config('local_ild_catimage', 'max_users_per_cron')));

    $users = $DB->get_records_sql($sql, $params);
    mtrace('users found without picture: ' . count($users));

    $dir = $CFG->dirroot . '/local/ild_catimage/catimages/';
    if (!($handle = opendir($dir))) {
        mtrace(get_string('uploadpicture_cannotprocessdir', 'tool_uploaduser'));
        return;
    }
    $files = array();
    while (false !== ($item = readdir($handle))) {
        if ($item != '.' && $item != '..') {
            $files[] = $item;
        }
    }

    $i = 0;
    $max = intval(get_config('local_ild_catimage', 'max_users_per_cron'));
    foreach ($users as $user) {
        update_catimage($CFG->dirroot . '/local/ild_catimage/catimages/' . $files[rand(0, count($files) - 1)], $user);
        $i++;
        if ($i == $max) {
            break;
        }
    }
    mtrace('Updated user pictures: ' . $i);
}

function update_catimage($filepath, $user) {
    global $DB;

    if ($newrev = my_save_profile_image($user->id, $filepath)) {
        $DB->set_field('user', 'picture', $newrev, array('id' => $user->id));
        mtrace(get_string('uploadpicture_userupdated', 'tool_uploaduser', $user->username));
        // Trigger event.
        \core\event\user_updated::create_from_userid($user->id)->trigger();
        return;
    } else {
        mtrace(get_string('uploadpicture_cannotsave', 'tool_uploaduser', $user->username));
        return;
    }
}

/**
 * Try to save the given file (specified by its full path) as the
 * picture for the user with the given id.
 *
 * @param integer $id the internal id of the user to assign the
 *                picture file to.
 * @param string $originalfile the full path of the picture file.
 *
 * @return mixed new unique revision number or false if not saved
 */
function my_save_profile_image($id, $originalfile) {
    $context = context_user::instance($id);
    return process_new_icon($context, 'user', 'icon', 0, $originalfile);
}

/**
 * Given the full path of a file, try to find the user the file
 * corresponds to and assign him/her this file as his/her picture.
 * Make extensive checks to make sure we don't open any security holes
 * and report back any success/error.
 *
 * @param string $file the full path of the file to process
 * @param string $userfield the prefix_user table field to use to
 *               match picture files to users.
 * @param bool $overwrite overwrite existing picture or not.
 *
 * @return integer either PIX_FILE_UPDATED, PIX_FILE_ERROR or
 *                  PIX_FILE_SKIPPED
 */
function process_file($file, $userfield, $overwrite) {
    global $DB;

    // Add additional checks on the filenames, as they are user
    // controlled and we don't want to open any security holes.
    $pathparts = pathinfo(cleardoubleslashes($file));
    $basename = $pathparts['basename'];
    $extension = $pathparts['extension'];

    // The picture file name (without extension) must match the userfield attribute.
    $uservalue = substr($basename, 0,
        strlen($basename) -
        strlen($extension) - 1);

    // Userfield names are safe, so don't quote them.
    if (!($user = $DB->get_record('user', array($userfield => $uservalue, 'deleted' => 0)))) {
        $a = new stdClass();
        $a->userfield = clean_param($userfield, PARAM_CLEANHTML);
        $a->uservalue = clean_param($uservalue, PARAM_CLEANHTML);
        mtrace(get_string('uploadpicture_usernotfound', 'tool_uploaduser', $a));
        return;
    }

    $haspicture = $DB->get_field('user', 'picture', array('id' => $user->id));
    if ($haspicture && !$overwrite) {
        mtrace(get_string('uploadpicture_userskipped', 'tool_uploaduser', $user->username));
        return;
    }

    if ($newrev = my_save_profile_image($user->id, $file)) {
        $DB->set_field('user', 'picture', $newrev, array('id' => $user->id));
        mtrace(get_string('uploadpicture_userupdated', 'tool_uploaduser', $user->username), 'notifysuccess');
        return;
    } else {
        mtrace(get_string('uploadpicture_cannotsave', 'tool_uploaduser', $user->username));
        return;
    }
}