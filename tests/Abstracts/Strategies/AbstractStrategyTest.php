<?php
namespace Rocketeer\Abstracts\Strategies;

use Mockery\MockInterface;
use Rocketeer\TestCases\RocketeerTestCase;

class AbstractStrategyTest extends RocketeerTestCase
{
    public function testCanCheckForManifestWithoutServer()
    {
        $this->app['path.base'] = realpath(__DIR__.'/../../..');
        $this->swapConfig(array(
            'rocketeer::paths.app' => realpath(__DIR__.'/../../..'),
        ));

        $this->usesComposer(false);
        $strategy = $this->builder->buildStrategy('Dependencies', 'Composer');
        $this->assertTrue($strategy->isExecutable());
    }

    public function testCanDisplayStatus()
    {
        $this->expectOutputRegex('#<fg=cyan>\w+</fg=cyan> \| <info>Deploy/Clone</info> <comment>\(.+\)</comment>#');

        $this->mock('rocketeer.command', 'Command', function (MockInterface $mock) {
            return $mock->shouldReceive('line')->andReturnUsing(function ($input) {
                echo $input;
            });
        });

        $strategy = $this->builder->buildStrategy('Deploy', 'Clone');
        $strategy->displayStatus();
    }
}
