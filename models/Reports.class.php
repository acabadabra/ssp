<?php

/** Class to manage reports */
class Reports {
	/** DB connection */
	private static $db;

	/** DB connection contructor */
	function __construct()
	{
    $dsn = 'sqlite:db/reports.sqlite3';
    try{
			self::$db=new PDO($dsn);
			# We can now log any exceptions on Fatal error.
			self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			# Disable emulation of prepared statements, use REAL prepared statements instead.
			self::$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			self::$db->exec('PRAGMA foreign_keys = ON;');
    }
    catch(PDOException $e){
      printf("Échec de la connexion : %s\n", $e->getMessage());
      $this->db = NULL;
    }
	}

	/** Get last 5 entries */
	function Get_public()
	{
		$sql="SELECT REPORTS.DATE, REPORTS.TITLE, REPORTS.MAINDESCRIPTION, REPORTS.STATE,
						GROUP_CONCAT(REPORT_DETAILS.DATE,'|') AS MSGDATE,
						GROUP_CONCAT(REPORT_DETAILS.DESCRIPTION,'|') AS MSGDESCRIPTION
					FROM REPORTS
					LEFT JOIN REPORT_DETAILS ON REPORT_ID = REPORTS.ID
					GROUP BY REPORTS.ID
					ORDER BY REPORTS.DATE DESC
					LIMIT 5";
	  $data=self::$db->query($sql);
	  return $data;
	}

	/** Get last 10 entries */
	function Get_rss()
	{
		$sql="SELECT REPORTS.ID, REPORTS.DATE, REPORTS.TITLE, REPORTS.MAINDESCRIPTION, REPORTS.STATE,
						GROUP_CONCAT(REPORT_DETAILS.ID,'|') AS MSGID,
						GROUP_CONCAT(REPORT_DETAILS.DATE,'|') AS MSGDATE,
						GROUP_CONCAT(REPORT_DETAILS.DESCRIPTION,'|') AS MSGDESCRIPTION
					FROM REPORTS
					LEFT JOIN REPORT_DETAILS ON REPORT_ID = REPORTS.ID
					GROUP BY REPORTS.ID
					ORDER BY REPORTS.DATE DESC
					LIMIT 10";
	  $data=self::$db->query($sql);
	  return $data;
	}

	/** Get last 5 entries */
	function Get_last_reports()
	{
		$sql="SELECT REPORTS.ID, REPORTS.DATE, REPORTS.TITLE, REPORTS.MAINDESCRIPTION, REPORTS.STATE,
						GROUP_CONCAT(REPORT_DETAILS.ID,'|') AS MSGID,
						GROUP_CONCAT(REPORT_DETAILS.DATE,'|') AS MSGDATE,
						GROUP_CONCAT(REPORT_DETAILS.DESCRIPTION,'|') AS MSGDESCRIPTION
					FROM REPORTS
					LEFT JOIN REPORT_DETAILS ON REPORT_ID = REPORTS.ID
					GROUP BY REPORTS.ID
					ORDER BY REPORTS.DATE DESC
					LIMIT 5";
	  $data=self::$db->query($sql);
	  return $data;
	}

	/** Get all DB entries */
	function Get_all_reports()
	{
		$sql="SELECT REPORTS.ID, REPORTS.DATE, REPORTS.TITLE, REPORTS.MAINDESCRIPTION, REPORTS.STATE,
						GROUP_CONCAT(REPORT_DETAILS.ID,'|') AS MSGID,
						GROUP_CONCAT(REPORT_DETAILS.DATE,'|') AS MSGDATE,
						GROUP_CONCAT(REPORT_DETAILS.DESCRIPTION,'|') AS MSGDESCRIPTION
					FROM REPORTS
					LEFT JOIN REPORT_DETAILS ON REPORT_ID = REPORTS.ID
					GROUP BY REPORTS.ID
					ORDER BY REPORTS.DATE DESC";
	  $data=self::$db->query($sql);
	  return $data;
	}

	/** Add report */
	function Add_report($data)
	{
	  $sql = "INSERT INTO REPORTS(DATE,TITLE,MAINDESCRIPTION) values (?,?,?)";
	  $stmt = self::$db->prepare($sql);
	  return $stmt->execute(array($data['date'], $data['title'], $data['maindescription']));
	}
	/** Add report description */
	function Add_description_report($data)
	{
		$sql = "INSERT INTO REPORT_DETAILS(REPORT_ID,DATE,DESCRIPTION) values (?,?,?)";
		$stmt = self::$db->prepare($sql);
		return $stmt->execute(array($data['report_id'], $data['date'], $data['description']));
	}

	/** Get report by ID */
	function Get_report_by_id($id)
	{
	  $sql="SELECT * FROM REPORTS WHERE ID=:id";
	  $stmt=self::$db->prepare($sql);
	  $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	  $stmt->execute();
	  return $stmt->fetch(PDO::FETCH_OBJ);
	 }

	/** Remove report by ID */
	function Delete_report_by_id($id)
  {
  	$sql="DELETE FROM REPORTS WHERE REPORTS.ID=:id";
  	$stmt=self::$db->prepare($sql);
  	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
  	return $stmt->execute();
  }
	/** Remove report description by ID */
	function Delete_description_by_id($id)
  {
  	$sql="DELETE FROM REPORT_DETAILS WHERE ID=:id";
  	$stmt=self::$db->prepare($sql);
  	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
  	return $stmt->execute();
  }

	/** Update report by ID */
	function Update($id, $date, $title, $maindescription, $state)
	{
		$sql = "UPDATE `REPORTS`
						SET `DATE` = :date,
						`TITLE` = :title,
						`MAINDESCRIPTION` = :maindescription,
						`STATE` = :state
						WHERE `REPORTS`.`ID` = :id";
		$stmt = self::$db->prepare($sql);
		$stmt->bindParam(':id', $id);
		$stmt->bindParam(':date', $date);
		$stmt->bindParam(':title', $title);
		$stmt->bindParam(':maindescription', $maindescription);
		$stmt->bindParam(':state', $state);
		return $stmt->execute();
	}
}
