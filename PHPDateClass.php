<?php
/*
 * Copyright 2011 Andrew ( http://4ndr3w.me )
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 * 	http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
*/

class Date
{
	private $years;
	function __construct()
	{
		$this->years = array();
	}
	
	function getYear($year)
	{
		if ( !array_key_exists($year, $this->years) )
			$this->years[$year] = new Year($year);
		return $this->years[$year];
	}
	
	function getCurrentYear()
	{
		return $this->getYear(intval(date("o")));
	}
}

class Year
{
	public $months;		// Month Objects
	public $isCurrentYear; // If this is the current year
	public $year; // Year as a number
	public $numberOfMonths; // Number of months in a year ( always 12 )
	function __construct($year)
	{
		$this->year = $year;
		$numberOfMonths = 12;
		if ( $year == date("o") )
			$isCurrentYear = true;
		
		$this->months = array();
		for ( $i=1; $i < 13; $i++ )
			$this->months[$i] = new Month($i, $this);
	}
	
	function getMonthByNumber($id)
	{
		return (( $id <= 0 || $id > 12 ) ? false : $this->months[$id]);
	}
	
	function getCurrentMonth()
	{
		foreach ( $this->months as $month )
			if ( $month->isCurrentMonth )
				return $month;
	}
}

class Month
{
	public $name; // Name as string
	public $days; 		// day Objects
	public $daysInMonth; // Number of days in month
	public $isCurrentMonth; // This the current month
	public $month; // Month as a int
	public $year; // Parent Year Object
	public $weeks;
	function __construct($month, $year)
	{
		$this->name = date("F", mktime(0,0,0,$month));
		$this->year = $year;
		$this->month = $month;
		$this->isCurrentMonth = ($month == date('n'));
		$this->daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year->year);
		for ( $i = 0; $i < $this->daysInMonth; $i++ )
		{
			$this->days[$i] = new Day($i, $this);
			$currentweek->days[] = $this->days[$i];
		}
	}
	
	function getDay($num)
	{
		return (( $num <= 0 || $num > $this->daysInMonth ) ? false:$this->days[$num]);
	}
	
	function getCurrentDay()
	{
		foreach ( $this->days as $day )
			if ( $day->isCurrentDay )	return $day;
		return false;
	}	
}

class Day
{
	public $name; // Name of the day
	public $month; // Parent Month
	public $day; // Day number in month
	public $isCurrentDay; // is this the current day
	function __construct($num, $month)
	{
		$this->month = $month;
		$this->day = $num;
		$this->isCurrentDay = (date("j") == $num && $this->month->isCurrentMonth);
		$this->name = date("l", mktime(0,0,0,$month->month, $num));
	}
}
$d = new Date();
$y = $d->getCurrentYear();
$m = $y->getCurrentMonth();
?>
