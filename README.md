# Cat Profile Images

The local plugin Cat Profile Images will set a default cat image as
profile picture in case the user didn't uploaded one.

## Installation

Just clone or download the latest version from the master branch to 
your Moodle installation and walk through the site update process or
run the upgrade.php from command line:

    $ php admin/cli/upgrade.php --non-interactive
    
## Usage

After the installation no further action is needed. 

The cron job will run in the background and check for profiles without
a picture and set a cat image.

## Support

support@oncampus.de

