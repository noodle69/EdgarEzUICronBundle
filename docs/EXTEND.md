# EdgarEzUICronBundle

## Extend

This bundle offers possibility to transform Command into Cron or simply create Cron like a Command

### Define your Cron class (as Command class)

In your bundle (AcmeBundle), create a **Cron** folder.

In this folder, create a new class.

```php
namespace AcmeBundle\Cron;

use Edgar\Cron\Cron\AbstractCron;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FooCron extends AbstractCron
{
    protected function configure()
    {
        $this
            ->setName('edgar:cron:foo')
            ->addArgument('foo', InputArgument::REQUIRED, 'foo argument')
            ->setDescription('Edgar cron foo');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('start foo');
        $output->writeln('foo argument : ' . $this->getArgument($input, 'foo'));
        $output->writeln('end foo');
    }
}
```

* AbstractCron: replace "Command" or "ContainerWareCommand" with this abstract class
* getArgument: replace "$input->getArgument(...)" with "$this->getArgument(...)"

You can override "CronAbstract" properties to define customer cron expression by default "* * * * *" :
* minute
* hour
* dayOfMonth
* month
* dayOfWeek

### Register this new Cron as service

in your Resources/config/services.yml, add

```yaml
services:
    AcmeBundle\Cron\FooCron:
        tags:
          - { name: edgar.cron, alias: foo, priority: 200, arguments: 'foo:fooarg' }
```

* Your cron is tagged with "edgar.cron".
* You should define an alias
* priority is not mandatory
* arguments is not mandatory
* arguments format is : arg1:value1 arg2:value2 ...
