<?php

namespace App\Application\Command;

use App\Domain\Interfaces\PushNotificationSender;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendPushNotificationCommand extends Command
{
    /**
     * @var PushNotificationSender
     */
    private $pushNotificationSender;

    public function __construct(
        PushNotificationSender $pushNotificationSender
    ) {
        $this->pushNotificationSender = $pushNotificationSender;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:send-push-notification')
            ->setDescription('Send push notification.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $token = '';

        $res = $this->pushNotificationSender->sendPushNotification(
            $token,
            [
                'k1' => 'v1',
                'k2' => 'v2',
            ]
        );

        $output->writeln($res ? 'suc' : 'fai');
    }
}