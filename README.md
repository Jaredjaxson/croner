# croner
cron for php(my 1st script with object programming )

Привет.
Этот скрипт является аналогом cron.
Он был написан мною, для автоматизации некоторых действий,в определенное время.

Код работает так :

После создания обьекта `$cron = new croner();` , нужно добавить Task(задания).
После этого строкой `$cron->StartCron();` запускается бесконечный цикл ,
который каждую секунду прооверяет время UNIX на совпадение со временем указаных в таске.

Функция AddTaskClock имеет 4 параметра.
1 Время старта таска:
	int or string;
	если строка, то разделитель должен быть символ двоеточия `:`
		Хочу подробней обьяснить. Можно передавать количество секунд ,через которое сработает вызов функции.
		В дальнейшем немного изменю и добавлю код.
      
2 Функция вызова:
    string
        строка, имя функции,которую код вызывает.

3 Параметры:
    string
        строка ,параметр ,передаваемый вызываемой функции.Может быть null.
  
4 Имя Таска.
    string
        строка или целое или null.

