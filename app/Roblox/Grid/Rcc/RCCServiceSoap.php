<?php

namespace App\Roblox\Grid\Rcc;

class RCCServiceSoap
{
	private $client;
	private $classmap = [
		'Status' => \App\Roblox\Grid\Rcc\Status::class,
		'Job' => \App\Roblox\Grid\Rcc\Job::class,
		'ScriptExecution' => \App\Roblox\Grid\Rcc\ScriptExecution::class,
		'LuaValue' => \App\Roblox\Grid\Rcc\LuaValue::class,
		'LuaType' => \App\Roblox\Grid\Rcc\LuaType::class
	];

	public $ip;
	public $port;
	public $url;

    function __construct($wsdl, $ip, $port = 64989) {
		$this->ip = $ip;
		$this->port = $port;
		$this->url = "{$ip}:{$port}";

		$this->client = new \SoapClient($wsdl, 
            [
                'location' => "http://{$ip}:{$port}",
                'uri' => url('/'),
                'classmap' => $this->classmap,
                'exceptions' => config('app.env') != 'production'
            ]
		);
	}

	private function call($name, $arguments = [])
    {
		$result = $this->client->$name($arguments);

        if (!is_soap_fault($result))
        {
            $data = "{$name}Result";
            if (isset($result->$data))
            {
                return $result->$data;
            }
        }

        return null;
	}
	
	private static function parse($value)
    {
		if ($value !== new \stdClass() && isset($value->LuaValue))
        {
			// Our job result isn't empty, so let's deserialize it
			$result = LuaValue::deserializeValue($value->LuaValue);
		}
        else
        {
			// Something went wrong :(
			$result = null;
		}

		return $result;
	}

	function HelloWorld()
    {
		return $this->call(__FUNCTION__);
	}

	function GetVersion()
    {
		return $this->call(__FUNCTION__);
	}

	function OpenJob($job, $script = null)
    {
		return $this->OpenJobEx($job, $script);
	}

	function OpenJobEx($job, $script = null)
    {
		$result = $this->call(__FUNCTION__, ['job' => $job, 'script' => $script]);
		return RCCServiceSoap::parse($result);
	}

	function BatchJob($job, $script)
    {
		return $this->BatchJobEx($job, $script);
	}

	function BatchJobEx($job, $script)
    {
		$result = $this->call(__FUNCTION__, ['job' => $job, 'script' => $script]);
		return RCCServiceSoap::parse($result);
	}

	function RenewLease($jobID, $expirationInSeconds)
    {
		return $this->call(__FUNCTION__, ['jobID' => $jobID, 'expirationInSeconds' => $expirationInSeconds]);
	}

	function Execute($jobID, $script)
    {
		return $this->ExecuteEx($jobID, $script);
	}

	function ExecuteEx($jobID, $script)
    {
		return $this->call(__FUNCTION__, ['jobID' => $jobID, 'script' => $script]);
	}

	function CloseJob($jobID) {
		return $this->call(__FUNCTION__, ['jobID' => $jobID]);
	}

	function GetExpiration($jobID) {
		return $this->call(__FUNCTION__, ['jobID' => $jobID]);
	}

	function Diag($type, $jobID) {
		return $this->DiagEx($type, $jobID);
	}

	function DiagEx($type, $jobID) {
		return $this->call(__FUNCTION__, ['type' => $type, 'jobID' => $jobID]);
	}

	function GetStatus() {
		return $this->call(__FUNCTION__);
	}

	function GetAllJobs() {
		// GetAllJobs is deprecated.
		return $this->GetAllJobsEx();
	}

	function GetAllJobsEx() {
		return $this->call(__FUNCTION__);
	}
	
	function CloseExpiredJobs() {
		return $this->call(__FUNCTION__);
	}
    
	function CloseAllJobs() {
		return $this->call(__FUNCTION__);
	}
}