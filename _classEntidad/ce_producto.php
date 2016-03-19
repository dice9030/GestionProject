<?php

class ceproducto{
	
	private $_cCodigoProducto;
	private $_cDescripcion;
	private $_nTipoProducto;	

	public function setCodigoProducto($cCodigoProducto)
	{
		$this->_cCodigoProducto = $cCodigoProducto;
	}
	public function getCodigoProducto()
	{
		return $this->_cCodigoProducto;
	}
	
	public function setDescripcion($cDescripcion)
	{
		$this->_cDescripcion = $cDescripcion;
	}
	public function getDescripcion()
	{
		return $this->_cDescripcion;
	}

	public function setTipoProducto($nTipoProducto)
	{
		$this->_nTipoProducto = $nTipoProducto;
	}
	public function getTipoProducto()
	{
		return $this->_nTipoProducto;
	}


}

?>
