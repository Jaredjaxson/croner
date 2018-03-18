<?php
//namespace api\engine;

date_default_timezone_set('Europe/Kiev');
error_reporting( E_ERROR );
	
class croner 
	{
		
		const CONST_TIME = 1;
		
		// public $task;
		// public $times;
		// public $start;
		// public $last;
		// public $func;
		// public $param;
		// public $taskname;
		
		function __construct()
		{
			$this->task = new StdClass();
			$this->start_on = time();
		}
		
		public function ChekTime($name)
		{
			$i = $this->task->$name->times;
            $t = time();
            
 			if(is_numeric($i)){	//if UNIXtime
            
				$time = $this->task->$name->last + $i;
				if($t >= $time)//если сейчас > чем надо
				{
					$this->task->$name->last = $t;
					return TRUE;
				}
			}
			elseif(strpos($i,':') !== false){ // and is_numeric(str_replace(':','',$i))
				// if string time 
                if(date('H:i:s',$t) == $i or $t == $this->task->$name->next){
                    $this->task->$name->last = $t;
                    $this->task->$name->next = $t + $this->GetTimeAtr('d');
                    return TRUE;
                }

			}
            //var_dump($this->task->$name->last);
			return FALSE; 
		}
		
		public function GetTimeStart()
		{
			return $this->start_on;
		}
		
		public function AddTaskClock($time,$f,$p = null,$name = null ,$now = false)
		{
			if(is_null($name))$name = $f;
            
			$x = time();
			
			$this->task->$name = new StdClass();
			$this->task->$name->times = $time;    // Время срабатывания таска(через сколько ,или когда именно)
			$this->task->$name->start = $x;       // время начала,старта таска
			$this->task->$name->last = $x;        // время последнего вызова таска
			$this->task->$name->func = $f;        // функция вызова 
			$this->task->$name->param = $p;       // параметры для ф. вызова
			$this->task->$name->taskname = $name; // имя таск
            $this->task->$name->next = null;  // следующий
			
			if($now){
				//if you need play task at once ,after added in tasklist
				$this->CalledFunc( $name );
				$this->task->$name->last = $x + $this->task->$name->times;
			}
				
			return true;
		}
		
		public function FindTask()
		{
			foreach($this->task as $key=>$value)
			{	
				if($this->ChekTime($key)){
					//file_put_contents('json.txt',json_encode($this->task));
					$this->CalledFunc($key);
				} 
			}
		}

		public function CalledFunc( $i )
		{
			return call_user_func(
						$this->task->$i->func,
						$this->task->$i->param	);
		}

		private function GetTimeAtr( $a )
		{
			switch ($a)
			{
				case 'm' :$r = 60; break;//minut
				case 'h' :$r = 3600; break;//hour
				case 'd' :$r = 86400; break;//day
				case 'w' :$r = 604800; break;//week
				case 'M' :$r = 2629743; break;//mount ну ту как выйдет,всегда по разному
				case 'y' :$r = 31556926; break;//year тоже хер пойми.... не трож просто.
				//партия требуетввести декады
			}
			return $r;
		}
		
		public function StartCron(){
		 //echo 'startCroeeeeeeeeeeeeeeeeen';
			while( TRUE ) {
				$this->FindTask();
				sleep(self::CONST_TIME);
			}
		}
		
		private function TimeToUnix($in)
		{
			$c = substr_count($in,':');
			if($c == 0)return FALSE;
			if(strlen($in) == 5 and $c == 1)$in.':00';
			return strtotime($in);
		}
		
		public function GetCron(){
			return $this->task;
		}
        
        public function setParam($name,$param,$val){
			$this->task->$name->$param = $val;
		}
		
		//public function setTime
        /*
        				//if($this->TimeToUnix($i) <= $t){ // 13.12.2017 0:39:31
                if($this->TimeToUnix($i) == date("H:i:s")){
                    if(!empty($this->task->$name->next)){
                        
                    }
				    $this->task->$name->last = $t;
					$this->task->$name->next = $this->GetTimeAtr('d');
					return TRUE;
				}
        */
	}
?>
