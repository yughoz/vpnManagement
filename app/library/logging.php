<?php 

namespace App\library ;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

    class logging {
        public $request = "";
    	private $startTime;
        protected $hiddenReq = [
            'password','password_confirm','columns'
        ];
        protected $hidden = [
            'password', 'remember_token','desc','status','success',
        ];
        public function __construct()
        {
            // parent::__construct();
            $this->startTime = microtime(true);
        }
        public function is_ok() {
            return 'myFunction is OK';
        }

        public function apiLog($nameLog = "generalLog", $paramSave = "",$custom = 'api') {
            if ($custom == "api") {
                foreach ($this->hiddenReq as $key => $value) {
                    if (isset($this->request[$value])) {
                        unset($this->request[$value]);
                    }
                }
                foreach ($this->hidden as $key => $value) {
                    if (isset($paramSave[$value])) {
                        unset($paramSave[$value]);
                    }
                }

            	$fileContents = [
                                    "timeProcces" => microtime(true)-$this->startTime,
                                    "time" => time(),
                                    "request" => $this->request,
                                    // "requestPath" => $this->request->path(),
                                    "paramSave" => $paramSave,
            					];
            } else {
                $fileContents = [
                                    "time" => time(),
                                    "datas" => $paramSave,
                                ];
            }
        	Storage::append('log/'.date("Y")."/".date("m")."/".date("d")."/".$nameLog."_".date("Ymd"), json_encode($fileContents));
            return true;
        }
    }