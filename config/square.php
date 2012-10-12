<?php
/*
den här är mitt försökt att instansiera en globala 
instans av klassen Databas som skulle vara 
en Singleton klass från början.
Ska lämna det här som en "to do" reminder

global $db;
$db = Database_Model::getInstance();
*/

// Få reda på servern begäran
$request = $_SERVER['QUERY_STRING'];

// Tolka sidan begäran 
$parsed = explode('&', $request);

// Sidan är elementetet som kommer efter första '&' symbolen
$page = array_shift($parsed);

// Tolka alla andra elementer som finns i begäran och spara de i en array
$getVars = array();
foreach($parsed as $argument)
{
	// dela variablerna i variabel värde för varje '=' symbol så att
	// jag kan bygga arrayen med $variable som key och $ value som value
	list($variable, $value) = explode('=', $argument);
	$getVars[$variable] = $value;
}
// implementera funktionen __autoload som ska automatisera klassen sökningen. Det är möjligt för att jag fölier "name convention" så att mina klasser heter samma som "sidan" + model
function __autoload($className)
{
	//få reda på filnamn där klassen skulle hittas
	list($filename, $suffix) = explode('_', $className);
	
	//skapa string med fil namn position
	$file = SERVER_ROOT . 'models/' . strtolower($filename) . '.php';
	
	//plocka up file
	if(file_exists($file))
	{
		include_once($file);
	}
	else
	{
		die("Fil " . $filename . "som använder klass " . $className . "kan inte hittas");
	}
} 

// skapa string med fil position
$target = SERVER_ROOT . 'controllers/' . $page . ".php";

//jag kollar om filen existera, därmed hämta det, annars meddela användare att sidan inte existera
if(file_exists($target))
{
	include_once($target);
	
	//nu ska jag använda sida namn för att skapa class namn som ska styrra sidan
	$class = ucfirst($page) . '_Controller';
	//kollar om klassen existerar då instansiera en ny objekt av klasset, annar meddela användare att klassen existerar inte
	if(class_exists($class))
	{
		$controller = new $class;
	} 
	else
	{
		die('klass finns inte');
	}
}
else
{
	//sidan finns inte i 'controllers'
	die('Sidan finns inte');
}
$controller->main($getVars);
