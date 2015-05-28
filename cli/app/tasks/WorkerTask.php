<?php
use Phalcon\CLI\Task as PhTask;
use Phalcon\Queue\Beanstalk\Extended as Beanstalkd;
use Phalcon\Queue\Beanstalk\Job;
use Fly\Helper;

class WorkerTask extends PhTask
{
    protected $bean;
    protected $message = '';
    protected $smug = null;
    protected $url = '';

    public function __construct()
    {
        $this->bean = new Beanstalkd([
            'host' => $this->config->app_beanstalkd->host,
            'port' => $this->config->app_beanstalkd->port,
            'prefix' => $this->config->app_domain . '_'
        ]);
    }

    public function mainAction()
    {

    }

    public function mangaAction()
    {
        $this->bean->addWorker('exampleTask', function (Job $job) {
            // Here we should collect the meta information, make the screenshots, convert the video to the FLV etc.
            $formData = $job->getBody();

            //Write to log
            // $this->loggerDB->name = 'queue';
            // $this->loggerDB->info('Job:<strong>'. $job->getId() .'</strong>,Tube:<code>'. 'exampleTask' .'</code>,<i>'. $this->message .'</i>');

            // It's very important to send the right exit code!
            exit(0);
        });

        // Start processing queues
        $this->bean->doWork();
    }
}
