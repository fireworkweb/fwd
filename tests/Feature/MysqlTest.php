<?php


namespace Tests\Feature;


use Tests\TestCase;


class MysqlTest extends TestCase
{
    public function testMysql()
    {
        $DB_PASSWORD = env('DB_PASSWORD');
        $DB_DATABASE = env('DB_DATABASE');

        $this->assertDockerComposeExec("-e MYSQL_PWD=$DB_PASSWORD mysql mysql -u root $DB_DATABASE");

        
        $this->artisan('mysql')->assertExitCode(0);
    }

    public function testMysqlExecution()
    {
        $DB_PASSWORD = env('DB_PASSWORD');
        $DB_DATABASE = env('DB_DATABASE');

        $this->assertDockerComposeExec("-e MYSQL_PWD=$DB_PASSWORD mysql mysql -u root $DB_DATABASE -e 'show databases'");

        
        $this->artisan('mysql -e "show databases"')->assertExitCode(0);
    }
}
