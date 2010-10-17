<?php

class SFSResult /*implements ArrayAccess*/
{
	protected $sfs;

	protected $raw_data = array();

	public function __construct(SFS $sfs, array $data)
	{
		$this->sfs = $sfs;

		$this->raw_data = $data;
	}

	public function toArray()
	{
		return $this->raw_data;
	}
}
