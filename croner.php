<?php

	date_default_timezone_set('Europe/Moscow');
	define ("CONST_TIME",1);// in second
	
	class croner 
	{

		function __construct()
		{
			$this->start_on = time();
		}
		
		public function GetTime($i)
		{
			if(is_numeric($i)){
				$time = $i + $this->task->$i->last;
				if(time() >= $time)
				{
					$this->task->$i->last = time();
					return TRUE;
				}
			}
			if(strpos($i,':') !== false){ // and is_numeric(str_replace(':','',$i))
				// 
				if($this->task->$i->next == 0){
					if(date('H:i') <= $i){
						$this->task->$i->last = time();
						$this->task->$i->next = time() + GetTimeAtr('d');
						return TRUE;
					}
				}else{
					if($this->task->$i->next >= time()){
						$this->task->$i->last = time();
						$this->task->$i->next = time() + GetTimeAtr('d');
						return TRUE;
					}
				}

			}
			return FALSE;
		}
		
		public function GetTimeStart()
		{
			return $this->start_on;
		}
		
		public function AddTaskClock($time,$f,$p,$name)
		{
			$this->task->$time->start = time();// время начала,старта таска
			$this->task->$time->last = time(); // время последнего вызова таска
			$this->task->$time->func = $f;     // функция вызова 
			$this->task->$time->param = $p;    // параметры для ф. вызова
			$this->task->$time->taskname = $name;// имя таска
			$this->task->$time->next = 0;		// время следующего выполнения
			return true;
		}
		
		public function FindTask()
		{
			foreach($this->task as $key => $value)
			{
				if($this->GetTime($key)){
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
				case 'm' :$r = 60; break;
				case 'h' :$r = 3600; break;
				case 'd' :$r = 86400; break;
				case 'w' :$r = 604800; break;
				case 'M' :$r = 2629743; break;
				case 'y' :$r = 31556926; break;
			}
			return $r;
		}
		
		public function StartCron(){
			while( TRUE ) {
				$this->FindTask();
				sleep(CONST_TIME);
			}
		}
	}
?>
