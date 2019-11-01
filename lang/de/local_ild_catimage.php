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
 */

$string['pluginname'] = 'Katzen-Profilbilder';
$string['crontask'] = 'Profilbilder aktualisieren';
$string['max_users_per_cron'] = 'Nutzeranzahl pro cronjob';
$string['max_users_per_cron_desc'] = 'Maximale Anzahl von Nutzer/innen, die pro cronjob aktualisiert werden dürfen.
                                      Default ist 1000, damit der cronjob pro Durchlauf nicht zuviel Zeit benötigt';
$string['active'] = 'Aktiv';
$string['active_desc'] = 'Nutzerbilder werden nur automatisch aktualisiert, wenn das Häkchen gesetzt ist';