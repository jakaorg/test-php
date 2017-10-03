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

        // if connection fails ...


        // prvi in zadnji datum dostopnih podatkov
        $d_from = $db->query('SELECT MIN(date) AS first FROM views')->fetchAll();
        $d_to = $db->query('SELECT MAX(date) AS last FROM views')->fetchAll();
        $totalv = $db->query('SELECT SUM(views) AS tv FROM views')->fetchAll();

        // if any query fails ...

        $first = $d_from[0]['first'];
        $last= $d_to[0]['last'];
        $totalv = $totalv[0]['tv'];

        $first_year = substr($first,0,4);
        $last_year = substr($last,0,4);
        $current_year =  date('Y');

        // if data is missing or corupted (date after current date ...)


        // izpiše obdobje dostopnih podatkov
        $io->newLine();
        $io->writeln(' Yearly Views report');
        $io->writeln(' ===================');
        $io->writeln(' Period: ' . $first . ' - ' . $last);
        $io->writeln(' Total views in the period: ' . $totalv);
        $io->newLine();


        // get users
        $users = array();
        $sql ="SELECT profile_id, profile_name FROM profiles ORDER BY profile_name ASC";
        $report = $db->query($sql);
          while ($row = $report->fetch())
          {
            //$io->writeln($row['profile_id'] . " /// " . $row['profile_name']);
            $users[$row['profile_id']] = $row['profile_name'];
          }

//$vv = var_dump($users);
//$io->writeln(":: " . $vv);

//$first_year = 2017;
          // Izpiše podatke
          // za vsako leto
          for ($i = $first_year; $i <= $last_year; $i++)
          {
            $io->newLine();$io->newLine();$io->newLine();
            $io->writeln($i);
            $io->writeln('===============================================================================================================================================');$io->newLine();
            $io->writeln('         Profile Name |     JAN |     FEB |     MAR |     APR |     MAY |     JUN |     JUL |     AUG |     SEP |     OKT |     NOV |     DEC |');
            $io->writeln('----------------------------------------------------------------------------------------------------------------------------------------------+');

            // Za vsakega uporabnika
            foreach ($users as $key => $value)
            {

              $uid = $key; // user ID
              $izpis = str_pad($value, 21, ' ', STR_PAD_LEFT) . ' |'; // user name
              // $uid . " + " .
                // za vse mesece
                for($m = 1; $m < 13; $m++)
                {

                  $sql = "SELECT SUM(views) FROM views WHERE YEAR(date) = $i AND MONTH(date) = $m AND profile_id = $uid;";
                  $report = $db->query($sql);

                    while ($row = $report->fetch())
                    {
                      //$vv = var_dump($row);
                      //$io->writeln(":: " . $vv);
                      //$io->writeln($row['SUM(views)']);
                      $total_visits = $row['SUM(views)'];
                        if(empty($total_visits)){$total_visits = 'n/a';}
                      $izpis .= str_pad($total_visits, 8, ' ', STR_PAD_LEFT) . ' |';
                    }
                } // meseci

              $io->writeln($izpis);

            } // userji

            $io->newLine();

        } // leto
    } // function
}
