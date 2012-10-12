<?php

class Database_Model
{
	//variabelna som ska behålla den enda instatiation av klassen
	private $_db;
	static $_instance;
	
	//deklarera variabelrna för kopplingen till min databas
	private $_host;
	private $_username;
	private $_password;
	private $_database;
	
	//konstruktoren som har modifier "private" så att det bli inte möjlight att 
	//instantiera klassen från utanför
	public function __construct()
	{
		$this->_host 		= "blu-ray.student.bth.se";
		$this->_username 	= "mimi11";
		$this->_password 	= "m@6scemo";
		$this->_database	= "mimi11";
		$this->db = mysql_connect($this->_host, $this->_username, $this->_password);
		//eftersom jag kommer att använda bara en tabell på databas
		//i den här moment, det är lika bra att select tabellen just nu
		$this->db = mysql_select_db ($this->_database);
	}
	
	//skapa en privat metod för att göra omöjligt att kopiera objektet 
	//från utanför klassen. Det är kravet för att skapa en singleton instans.
	private function __clone() {}

	//skapa en publik funktion för att skapa ett objekt av klassen
	public static function getInstance()
	{
		//kontrollera om variablen $_instance redan håller en referens till en objekt av klass Database
		if(!(self::$_instance instanceof self))
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	//jag ska skapa ett par metoder som jag kommer att använda i min webbapplicationen.
	//jag kan sen anropa på metoden genom att använda self:$_instance-
	
	//en metod för att lägga en rad i databas
	public function Insert($body, $author)
	{
		//förberädda queryn genom att använda variablerna passerade som argumnet
		//ska köra lite skidd emot sql injection
		$query = "INSERT into `blog` VALUES ('', '" . mysql_real_escape_string($body) . "','" . mysql_real_escape_string($author) ."', NOW())";
		//kör query och använda variablerna passerade som arguments för att skapa en insert query
		if ($queryRun = mysql_query($query))
			{
				//om query lyckas returnera true
				//det gör jag för att veta vilken meddelande ska jag skicka till view/användare
				return true;
			}
	}
	
	//en metod för att visa databas innehållet
	public function Get()
	{
		//förberädd querin
		$query = "SELECT * FROM `blog` ORDER BY 'date' DESC";
		//return arrayen med alla rader
		$results = mysql_query($query);
		$data = array(); 
		while($row = mysql_fetch_assoc($results)) 
		{ 
		   $data[] = $row; 
		} 
		
		return $data;
	}
}
