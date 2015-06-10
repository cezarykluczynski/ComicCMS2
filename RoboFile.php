<?php
/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
class RoboFile extends \Robo\Tasks
{
    public function createadmin($email, $password) {
        $this
            ->taskExec("php public/index.php create-admin $email $password")
            ->run();
    }

    public function getadminsessionid($email, $password) {
        $this
            ->taskExec("php public/index.php get-admin-session-id $email $password")
            ->run();
    }
}