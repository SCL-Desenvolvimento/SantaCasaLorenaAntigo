<?php

class Upload{

	private $File;
	private $Name;
	private $Send;
	private $Width;
	private $Image;
	private $Result;
	private $Error;
	private $Folder;
	private static $BaseDir;

	function __construct($BaseDir = null){
		self::$BaseDir = ((string) $BaseDir ? $BaseDir : 'Uploads/');
		if(!file_exists(self::$BaseDir) && !is_dir(self::$BaseDir)):
			mkdir(self::$BaseDir, 0777);
			//echo self::$BaseDir." - Sucesso <hr>";
	    else:
    		//echo self::$BaseDir." - Erro <hr>";
		endif;
	}

	public function Image(array $Image, $Name = NULL, $Width = null, $Folder = null){
		$this->File = $Image;
		$this->Name = ((string) $Name ? $Name : substr($Image['name'], 0, strpos($Image['name'], '.')));
		$this->Width = ((int) $Width ? $Width : 1024 );
		$this->Folder = ((string) $Folder ? $Folder : '' );
																									//Images - Retirei o IMAGES do Folder aspas simples, para enviar os anexos

		$this->CheckFolder($this->Folder);
		$this->setFileName();
		$this->UploadImagem();
	}

	public function File(array $File, $Name = null, $Folder = null, $MaxFileSize = null){

		$this->File = $File;
		$this->Name = ((string) $Name ? $Name : substr($File['name'], 0, strpos($File['name'], '.')));
		$this->Folder = ((string) $Folder ? $Folder : 'Files' );
		$MaxFileSize = ((int) $MaxFileSize ? $MaxFileSize : 10 );

		$FileAccept = [
				'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
				'application/pdf',
				'image/jpeg',
				'image/png',
				'audio/mp3'
		];

		if($this->File['size'] > ($MaxFileSize *(1024 * 1024))):
			$this->Result = false;
			$this->Error = "Arquivo muito grande, tamanho máximo permitido de {$MaxFileSize}mb";
		elseif(!in_array($this->File['type'], $FileAccept)):
			$this->Result = false;
			$this->Error = "Tipo de arquivo não suportado. Envie PDF ou DOCX";
		else:
			$this->CheckFolder($this->Folder);
			$this->setFileName();
			$this->MoveFiles();
		endif;
	}

	public function Media(array $Media, $Name = null, $Folder = null, $MaxFileSize = null){

		$this->File = $Media;
		$this->Name = ((string) $Name ? $Name : substr($Media['name'], 0, strpos($Media['name'], '.')));
		$this->Folder = ((string) $Folder ? $Folder : 'Medias' );
		$MaxFileSize = ((int) $MaxFileSize ? $MaxFileSize : 200 );

		$FileAccept = [
				'audio/mp3',
				'produto/mp4',
				''
		];

		if($this->File['size'] > ($MaxFileSize *(1024 * 1024))):
			$this->Result = false;
			$this->Error = "Arquivo muito grande, tamanho máximo permitido de {$MaxFileSize}mb";
		elseif(!in_array($this->File['type'], $FileAccept)):
			$this->Result = false;
			$this->Error = "Tipo de arquivo não suportado. Envie MP3 ou MP4";
		else:
			$this->CheckFolder($this->Folder);
			$this->setFileName();
			$this->MoveFiles();
		endif;
	}

	public function getResult(){
		return $this->Result;
	}
	public function getError(){
		return $this->Error;
	}

	private function CheckFolder($Folder){
		list($y, $m) = explode('/', date('Y/m'));
		$this->CreateFolder("{$Folder}");
		$this->CreateFolder("{$Folder}/{$y}");
		$this->CreateFolder("{$Folder}/{$y}/{$m}/");

		$this->Send = "{$Folder}/{$y}/{$m}/";
	}

	private function CreateFolder($Folder){
		if(!file_exists(DIR.self::$BaseDir.$Folder) && !is_dir(self::$BaseDir.$Folder)):
		mkdir(DIR.self::$BaseDir.$Folder, 0777);
		//echo self::$BaseDir." - Sucesso <hr>";
		else:
		//echo self::$BaseDir." - Erro <hr>";
		endif;
	}

	private function setFileName(){
		$FileName = Check::urlAmigavel($this->Name) . strchr($this->File['name'], '.');
		//echo $FileName;
		if(file_exists(DIR.self::$BaseDir.$this->Send.$FileName)):
			//$FileName = Check::urlAmigavel($this->Name).'-'.time().strrchr($this->File['name'], '.');
			//$this->Name = $FileName;
			unlink(DIR.self::$BaseDir.$this->Send.$FileName);
			$this->Name = $FileName;
		else:
			$this->Name = $FileName;
		endif;
	}

	private function UploadImagem(){
		switch ($this->File['type']):
			case 'image/jpg':
			case 'image/jpeg':
			case 'image/pjpeg':
				$this->Image = imagecreatefromjpeg($this->File['tmp_name']);
				break;
			case 'image/png':
			case 'image/x-png':
				$this->Image = imagecreatefrompng($this->File['tmp_name']);
				break;
		endswitch;

		if (!$this->Image):
			$this->Result = false;
			$this->Error = 'Tipo de arquivo inválido, envie imagens JPG ou PNG!';
		else:
			$x = imagesx($this->Image);
			$y = imagesy($this->Image);
			$ImageX = ($this->Width < $x ? $this->Width : $x);
			$ImageH = ($ImageX * $y) / $x;

			$NewImage = imagecreatetruecolor($ImageX, $ImageH);
			imagealphablending($NewImage, false);
			imagesavealpha($NewImage, true);
			imagecopyresampled($NewImage, $this->Image, 0, 0, 0, 0, $ImageX, $ImageH, $x, $y);

			switch ($this->File['type']):
				case 'image/jpg':
				case 'image/jpeg':
				case 'image/pjpeg':
					imagejpeg($NewImage, DIR.self::$BaseDir.$this->Send.$this->Name, 100);
					break;
				case 'image/png':
				case 'image/x-png':
					imagepng($NewImage, DIR.self::$BaseDir.$this->Send.$this->Name, 9);
					break;
			endswitch;

			if (!$NewImage):
				$this->Result = false;
				$this->Error = "Tipo de arquivo inválido, envie imagens JPG ou PNG";
			else:
				$this->Result = self::$BaseDir.$this->Send.$this->Name;
				$this->Error = null;
			endif;

			imagedestroy($this->Image);
			imagedestroy($NewImage);

		endif;
	}

	private function MoveFiles(){
		if(move_uploaded_file($this->File['tmp_name'], DIR.self::$BaseDir.$this->Send.$this->Name)):
			$this->Result = self::$BaseDir.$this->Send.$this->Name;
			//$this->Result;
			//$this->Error = null;
		else:
			$this->Result = false;
			$this->Error = "Erro ao enviar o arquivo, tente mais tarde";
		endif;
	}
}
