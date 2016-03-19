<?php

class cdtipoproducto extends cetipoproducto
{
	private $_dFecha;

	public function setFecha($dFecha)
	{
		$this->_dFecha = $dFecha;
	}
	public function getFecha()
	{
		return $this->_dFecha;
	}
	
	
}


