<?php

class cetipoproducto
{
	
	private $_cCodigoTipoProducto;
	private $_cDescripcion;
	private $_cConcepto;	

	public function setCodigoTipoProducto($cCodigoTipoProducto)
	{
		$this->_cCodigoTipoProducto = $cCodigoTipoProducto;
	}
	public function getCodigoTipoProducto()
	{
		return $this->_cCodigoTipoProducto;
	}
	
	public function setDescripcion($cDescripcion)
	{
		$this->_cDescripcion = $cDescripcion;
	}
	public function getDescripcion()
	{
		return $this->_cDescripcion;
	}

	public function setTipoProducto($cConcepto)
	{
		$this->_cConcepto = $cConcepto;
	}
	public function getTipoProducto()
	{
		return $this->_cConcepto;
	}


}

?>
