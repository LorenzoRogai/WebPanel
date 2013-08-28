<?php
//Connect to WebService
$client = new SoapClient("http://localhost:8000/WebPanel/?wsdl",array(
'login' => "admin", 'password' => "password"));

function Invoke($methodname)
{
    try 
    {         
		global $client;
        $response = $client->__soapCall($methodname,array());
        return $response;
    } 
    catch (SoapFault $exception)
    {
        trigger_error("SOAP Fault: (faultcode: {$exception->faultcode}, faultstring:
        {$exception->faultstring})");
    }
}
function InvokeWithParameters($methodname, $parameters)
{
    try 
    {         
		global $client;		
	
		$temp = explode (',',$parameters,-1);
		foreach ($temp as $pair) 
		{
			list ($k,$v) = explode ('|',$pair);
			$pairs[$k] = $v;
		}		
	
		$array = array("parameters" => $pairs);
		
        $response = $client->__soapCall($methodname, $array);
        return $response;
    } 
    catch (SoapFault $exception)
    {
        trigger_error("SOAP Fault: (faultcode: {$exception->faultcode}, faultstring:
        {$exception->faultstring})");
    }
}
function objectToArray($d) {
    if (is_object($d)) {
        // Gets the properties of the given object
        // with get_object_vars function
        $d = get_object_vars($d);
    }

    if (is_array($d)) {
        /*
        * Return array converted to object
        * Using __FUNCTION__ (Magic constant)
        * for recursive call
        */
        return array_change_key_case(array_map(__FUNCTION__, $d), CASE_LOWER);
    }
    else {
        // Return array
        return $d;
    }
}
?>