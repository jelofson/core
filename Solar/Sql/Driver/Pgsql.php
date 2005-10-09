<?php

/**
* 
* Class for connecting to PostgreSQL databases.
* 
* @category Solar
* 
* @package Solar
* 
* @subpackage Solar_Sql
* 
* @author Paul M. Jones <pmjones@solarphp.com>
* 
* @license LGPL
* 
* @version $Id$
* 
*/

/**
* 
* Class for connecting to PostgreSQL databases.
* 
* @category Solar
* 
* @package Solar
* 
* @subpackage Solar_Sql
* 
*/

class Solar_Sql_Driver_Pgsql extends Solar_Sql_Driver {
	
	
	/**
	* 
	* Map of Solar generic column types to RDBMS native declarations.
	* 
	* @access protected
	* 
	* @var array
	* 
	*/
	
	protected $native = array(
		'bool'      => 'BOOLEAN',
		'char'      => 'CHAR(:size)',
		'varchar'   => 'VARCHAR(:size)',
		'smallint'  => 'SMALLINT',
		'int'       => 'INTEGER',
		'bigint'    => 'BIGINT',
		'numeric'   => 'NUMERIC(:size,:scope)',
		'float'     => 'DOUBLE PRECISION',
		'clob'      => 'TEXT',
		'date'      => 'CHAR(10)',
		'time'      => 'CHAR(8)',
		'timestamp' => 'CHAR(19)'
	);
	
	
	/**
	* 
	* The PDO driver type.
	* 
	* @access protected
	* 
	* @var string
	* 
	*/
	
	protected $pdo_type = 'pgsql';
	
	
	/**
	* 
	* Creates a PDO-style DSN.
	* 
	* Per http://php.net/manual/en/ref.pdo-pgsql.connection.php
	* 
	* @access protected
	* 
	* @return string A PDO-style DSN.
	* 
	*/
	
	protected function dsn()
	{
		$dsn = array();
		
		if (! empty($this->config['host'])) {
			$dsn[] = 'host=' . $this->config['host'];
		}
		
		if (! empty($this->config['port'])) {
			$dsn[] = 'port=' . $this->config['port'];
		}
		
		if (! empty($this->config['name'])) {
			$dsn[] = 'dbname=' . $this->config['name'];
		}
		
		return $this->pdo_type . ':' . implode(' ', $dsn);
	}
	
	
	
	/**
	* 
	* Builds a SELECT statement from its component parts.
	* 
	* Adds LIMIT clause.
	* 
	* @access public
	* 
	* @param array $parts The component parts of the statement.
	* 
	* @return void
	* 
	*/
	
	public function buildSelect($parts)
	{
		// build the baseline statement
		$stmt = parent::buildSelect($parts);
		
		// determine count
		$count = ! empty($parts['limit']['count'])
			? (int) $parts['limit']['count']
			: 0;
		
		// determine offset
		$offset = ! empty($parts['limit']['offset'])
			? (int) $parts['limit']['offset']
			: 0;
			
		// add the count and offset
		if ($count > 0) {
			$stmt .= " LIMIT $count";
			if ($offset > 0) {
				$stmt .= " OFFSET $offset";
			}
		}
		
		// done!
		return $stmt;
	}
	
	
	/**
	* 
	* Returns the SQL statement to get a list of database tables.
	* 
	* @access public
	* 
	* @return string The SQL statement.
	* 
	*/
	
	public function listTables()
	{
		// copied from PEAR DB
		$cmd = "SELECT c.relname AS table_name " .
			"FROM pg_class c, pg_user u " .
			"WHERE c.relowner = u.usesysid AND c.relkind = 'r' " .
			"AND NOT EXISTS (SELECT 1 FROM pg_views WHERE viewname = c.relname) " .
			"AND c.relname !~ '^(pg_|sql_)' " .
			"UNION " .
			"SELECT c.relname AS table_name " .
			"FROM pg_class c " .
			"WHERE c.relkind = 'r' " .
			"AND NOT EXISTS (SELECT 1 FROM pg_views WHERE viewname = c.relname) " .
			"AND NOT EXISTS (SELECT 1 FROM pg_user WHERE usesysid = c.relowner) " .
			"AND c.relname !~ '^pg_'";
		
		$result = $this->exec($cmd);
		$list = $result->fetchAll(PDO_FETCH_COLUMN, 0);
		return $list;
	}
	
	
	/**
	* 
	* Creates a sequence, optionally starting at a certain number.
	* 
	* @access public
	* 
	* @param string $name The sequence name to create.
	* 
	* @param int $start The first sequence number to return.
	* 
	* @return void
	* 
	*/
	
	public function createSequence($name, $start = 1)
	{
		$this->exec("CREATE SEQUENCE $name START $start");
	}
	
	
	/**
	* 
	* Drops a sequence.
	* 
	* @access public
	* 
	* @param string $name The sequence name to drop.
	* 
	* @return void
	* 
	*/
	
	public function dropSequence($name)
	{
		$this->exec("DROP SEQUENCE $name");
	}
	
	
	/**
	* 
	* Gets a sequence number; creates the sequence if it does not exist.
	* 
	* @access public
	* 
	* @param string $name The sequence name.
	* 
	* @return int The next sequence number.
	* 
	*/
	
	public function nextSequence($name)
	{
		// first, try to get the next sequence number, assuming
		// the sequence exists.
		$cmd = "SELECT NEXTVAL($name)";
		
		// first, try to increment the sequence number, assuming
		// the table exists.
		try {
			$stmt = $this->pdo->prepare($cmd);
			$stmt->execute();
		} catch (Exception $e) {
			// error when updating the sequence.
			// assume we need to create it.
			$this->createSequence($name);
			
			// now try to increment again.
			$stmt = $this->pdo->prepare($cmd);
			$stmt->execute();
		}
		
		// get the sequence number
		return $this->pdo->lastInsertID($name);
	}
}
?>