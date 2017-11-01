<?php
namespace BOF\Command;

class UserProfile
{
  private static $profile_id;
  private static $profile_name;
  private static $jan;
  private static $feb;
  private static $mar;
  private static $apr;
  private static $may;
  private static $jun;
  private static $jul;
  private static $aug;
  private static $sep;
  private static $oct;
  private static $nov;
  private static $dec;
  private static $all;


  public static function getAllUsers($db){
    $users_array = $db->query('SELECT profile_id, profile_name FROM profiles ORDER BY profile_name ASC')->fetchAll();
    $object_array = array();

      foreach($users_array as $arr){
        $object_array[] = self::instantiate($arr);
      }

    return $object_array;
  }


  public static function make_report_by_year($db, $year){
    $users = self::getAllUsers($db);
    $report = array();
    $report_profile = array();
		  foreach($users as $user){
        $views = self::get_views_by_ID($db, $user->profile_id, $year, $user);
        $report_profile = array($user->profile_id, $user->profile_name, $user->jan, $user->feb, $user->mar, $user->apr, $user->may, $user->jun, $user->jul, $user->aug, $user->sep, $user->oct, $user->nov, $user->dec, $user->all);
        array_push($report, $report_profile);
		  }

    return $report;
	}


  private static function get_views_by_ID($db, $id, $year = 2017, $object){
    $views = $db->query("SELECT
    (SELECT SUM(views) FROM views WHERE YEAR(date) = $year AND MONTH(date) = 1 AND profile_id = $id) AS 'JAN',
    (SELECT SUM(views) FROM views WHERE YEAR(date) = $year AND MONTH(date) = 2 AND profile_id = $id) AS 'FEB',
    (SELECT SUM(views) FROM views WHERE YEAR(date) = $year AND MONTH(date) = 3 AND profile_id = $id) AS 'MAR',
    (SELECT SUM(views) FROM views WHERE YEAR(date) = $year AND MONTH(date) = 4 AND profile_id = $id) AS 'APR',
    (SELECT SUM(views) FROM views WHERE YEAR(date) = $year AND MONTH(date) = 5 AND profile_id = $id) AS 'MAY',
    (SELECT SUM(views) FROM views WHERE YEAR(date) = $year AND MONTH(date) = 6 AND profile_id = $id) AS 'JUN',
    (SELECT SUM(views) FROM views WHERE YEAR(date) = $year AND MONTH(date) = 7 AND profile_id = $id) AS 'JUL',
    (SELECT SUM(views) FROM views WHERE YEAR(date) = $year AND MONTH(date) = 8 AND profile_id = $id) AS 'AVG',
    (SELECT SUM(views) FROM views WHERE YEAR(date) = $year AND MONTH(date) = 9 AND profile_id = $id) AS 'SEP',
    (SELECT SUM(views) FROM views WHERE YEAR(date) = $year AND MONTH(date) = 10 AND profile_id = $id) AS 'OCT',
    (SELECT SUM(views) FROM views WHERE YEAR(date) = $year AND MONTH(date) = 11 AND profile_id = $id) AS 'NOV',
    (SELECT SUM(views) FROM views WHERE YEAR(date) = $year AND MONTH(date) = 12 AND profile_id = $id) AS 'DEC',
    (SELECT SUM(views) FROM views WHERE YEAR(date) = $year AND profile_id = $id) AS 'ALL'
    FROM views
    WHERE profile_id = $id
    LIMIT 1;")->fetchAll();

    $object->jan = self::is_null($views[0]['JAN']);
    $object->feb = self::is_null($views[0]['FEB']);
    $object->mar = self::is_null($views[0]['MAR']);
    $object->apr = self::is_null($views[0]['APR']);
    $object->may = self::is_null($views[0]['MAY']);
    $object->jun = self::is_null($views[0]['JUN']);
    $object->jul = self::is_null($views[0]['JUL']);
    $object->aug = self::is_null($views[0]['AUG']);
    $object->sep = self::is_null($views[0]['SEP']);
    $object->oct = self::is_null($views[0]['OCT']);
    $object->nov = self::is_null($views[0]['NOV']);
    $object->dec = self::is_null($views[0]['DEC']);
    $object->all = self::is_null($views[0]['ALL']);

    return true;
  }


  private static function is_null($value){
    return ($value === null) ? 'n/a' : $value;
  }


  private static function instantiate($record) {
    $object = new self;

	   foreach($record as $attribute=>$value) {
       $object->$attribute = $value;

/*		      if($object->has_attribute($attribute)) {
		          $object->$attribute = $value;
	        }
*/
     }

		return $object;
	}


	public function has_attribute($attribute) {
	  //$object_vars = get_object_vars($this); // private static ...
    //$object_vars = array('profile_id', 'profile_name', 'Jan', 'Mar', 'Feb', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Sum');
    //echo "HAS ATTR\n";
    //var_dump($object_vars);
    //echo "\n////HAS ATTR\n\n";
	  return true; //array_key_exists($attribute, $object_vars);
	}

}
?>
