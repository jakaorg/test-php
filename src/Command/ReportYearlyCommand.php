<?php
namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


class ReportYearlyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Page views report')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $db Connection */
        $io = new SymfonyStyle($input,$output);
        $db = $this->getContainer()->get('database_connection');


        $d_from = $db->query('SELECT MIN(date) AS first FROM views')->fetchAll();
        $d_to = $db->query('SELECT MAX(date) AS last FROM views')->fetchAll();
        $totalv = $db->query('SELECT SUM(views) AS tv FROM views')->fetchAll();

        $first_year = $d_from[0]['first'];
        $last_year = $d_to[0]['last'];
        $total_views = $totalv[0]['tv'];

        $first_year = substr($first_year,0,4);
        $last_year = substr($last_year,0,4);
        $current_year =  date('Y');

        $report_year = $io->choice("Chose the year of report", ['2015', '2016', '2017'], '2017'); // --------------- !!!
        $io->title("Report for $report_year");
        //$io->newLine(1);

        $report = UserProfile::make_report_by_year($db, $report_year);

        $io->table(['ID', 'Profile', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', "$report_year"], $report);
    }
}
?>
