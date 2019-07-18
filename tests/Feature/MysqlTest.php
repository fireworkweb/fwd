<?php

namespace Tests\Feature;

use Tests\TestCase;

class MysqlTest extends TestCase
{
    public function testMysql()
    {
        $this->artisan('mysql')->assertExitCode(0);

        $this->assertDockerComposeExec("-e MYSQL_PWD='secret' mysql mysql -u root docker");
    }

    public function testMysqlExecution()
    {
        $this->artisan('mysql -e "show databases"')->assertExitCode(0);

        $this->assertDockerComposeExec("-e MYSQL_PWD='secret' mysql mysql -u root docker -e 'show databases'");
    }
}
