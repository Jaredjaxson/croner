<?php
/*
Обычная дата (ЧеловекоЧитаемая дата) 	Секунды
	1 минута	60 секунд
	1 час		3600 секунд
	1 день		86400 секунд
	1 неделя	604800 секунд
	1 месяц  	2629743 секунд
	1 год		31556926 секунд

 Аттрибуты для заданий.
	 m - минуты
	 h - часы
	 d - день
	 w - неделя
	 M - месяц
	 Y - год

	 - перед буквой,значит нужно получить последнюю секунду,минуту,час,дня,недели,месяца

*/
	date_default_timezone_set('Europe/Moscow');
	define ("CONST_TIME",1);
	
	class croner 
	{
		private $task;
		
		function __construct()
		{
			$this->start_on = time();
		}
		
		public function ChekTime($i)
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
				if($this->TimeToUnix($i) == time()){
					$this->task->$i->last = time();
					return TRUE;
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
				if($this->ChekTime($key)){
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
		
		private function TimeToUnix($in)
		{
			$c = substr_count($in,':');
			if($c == 0)return FALSE;
			if(strlen($in) == 5 and $c == 1)$in.':00';
			return strtotime($in);
		}
	}
?>
