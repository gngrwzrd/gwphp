<?php

/*
Usage:
$uploader = new Uploader();
$uploader->setRequest($this->request);
$uploader->setResponse($this->response);
$uploader->setFileFieldName("upload");
$uploader->setUploadDir("../uploads/");
$uploader->setExtension(".png");
$uploader->upload();
echo $uploader->filename;
echo $uploader->rawFilename;
*/

require_once("phplib/base.php");
require_once("phplib/uuid.php");

class Uploader extends RequestAction {
	
	public $filename;
	public $rawFilename;
	private $extension;
	private $fileFieldName;
	private $udir;
	
	function post() {
		$this->upload();
	}
	
	function get() {
		$this->upload();
	}
	
	function cmd() {
		$this->upload();
	}
	
	function upload() {
		if(!$this->udir || !$this->fileFieldName ||
			!$this->extension) return false;
		$this->rawFilename = UUID::_uuid().$this->extension;
		$this->filename=$this->udir.$this->rawFilename;
		move_uploaded_file(
			$this->request->files[$this->fileFieldName]["tmp_name"],
			$this->filename
		);
		return true;
	}
	
	function unupload() {
		unlink($this->filename);
	}
	
	function setUploadDir($dir) {
		$this->udir = $dir;
	}
	
	function setFileFieldName($name) {
		$this->fileFieldName = $name;
	}
	
	function setExtension($ext) {
		$this->extension = $ext;
	}
}

?>
