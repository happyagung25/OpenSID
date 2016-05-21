<?php

class import_Model extends CI_Model{

	function __construct(){
		parent::__construct();
	}
		
	function import_excel(){
		$gagal=0;
		$baris2="";
			$a="DROP TABLE impor";
			$b = mysql_query($a);

		$data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);

		// membaca jumlah baris dari data excel
		$baris = $data->rowcount($sheet_index=0);

		// nilai awal counter untuk jumlah data yang sukses dan yang gagal diimport
		$sukses = 0;
		$gagal = 0;

		$a="ALTER TABLE tweb_penduduk ENGINE = MyISAM ROW_FORMAT = COMPACT;";
		$b = mysql_query($a);

		$a="ALTER TABLE tweb_keluarga ENGINE = MyISAM ROW_FORMAT = COMPACT;";
		$b = mysql_query($a);

		//buat tabel impor
		$a="CREATE TABLE IF NOT EXISTS impor (   
		dusun varchar(50) NOT NULL DEFAULT 0,
		rw varchar(10) NOT NULL DEFAULT 0, 
		rt varchar(10) NOT NULL DEFAULT 0,  
		nama varchar(50) NOT NULL,  
		nik varchar(16) NOT NULL,   
		sex tinyint(1) unsigned DEFAULT NULL,  
		tempatlahir varchar(50) NOT NULL,
		tanggallahir date NOT NULL,  
		agama_id int(1) unsigned NOT NULL,  
		pendidikan_kk_id int(1) unsigned NOT NULL,
		pendidikan_id int(1) unsigned NOT NULL,
		pendidikan_sedang_id int(1) unsigned NOT NULL,
		pekerjaan_id int(1) unsigned NOT NULL,  
		status_kawin tinyint(1) unsigned NOT NULL,  
		kk_level tinyint(1) NOT NULL DEFAULT 0,
		warganegara_id int(1) unsigned NOT NULL,  
		nama_ayah varchar(50) NOT NULL,  
		nama_ibu varchar(50) NOT NULL,
		golongan_darah_id int(1) NOT NULL,  
		jamkesmas int(1) NOT NULL DEFAULT 2, 
		id_kk varchar(16) NOT NULL DEFAULT '0') ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;";
		$b = mysql_query($a);

		$a="TRUNCATE tweb_wil_clusterdesa";
		$b = mysql_query($a);

		$a="TRUNCATE tweb_keluarga";
		$b = mysql_query($a);

		$a="TRUNCATE tweb_penduduk";
		$b = mysql_query($a);

		// import data excel mulai baris ke-2 (karena baris pertama adalah nama kolom)
		$baris2 ="";
		for ($i=2; $i<=$baris; $i++){
			 // membaca data nim (kolom ke-n)

			$dusun = $data->val($i, 1);
			$rw = $data->val($i, 2);
			$rt = $data->val($i, 3);
			$nama = $data->val($i, 4);
			if($nama!=""){
				$nama = '"'.$nama.'"';
			}
			$id_kk= $data->val($i, 5);
			$nik = $data->val($i, 6);
			$sex = $data->val($i, 7);
			$tempatlahir= $data->val($i, 8);
			$tanggallahir= $data->val($i, 9);
			//$tanggallahir = '"'.$tanggallahir.'"';

			if($tanggallahir[2] == "/" OR $tanggallahir[4] == "/"){
				$tanggallahir = str_replace('/','-', $tanggallahir);
			}
			
			if($tanggallahir[2] == "-"){
				$tanggallahir = rev_tgl($tanggallahir);
			}
			
			//echo $tanggallahir. "<br>";
			$agama_id= $data->val($i, 10);
			$pendidikan_kk_id= $data->val($i, 11);
			$pendidikan_sedang_id= $data->val($i, 12);
			$pekerjaan_id= $data->val($i, 13);
			$status_kawin= $data->val($i, 14);
			$kk_level= $data->val($i, 15);
			$warganegara_id= 1;
			$nama_ayah= '"'.$data->val($i, 17).'"';
			$nama_ibu= '"'.$data->val($i, 18).'"';
			$golongan_darah_id= $data->val($i, 19);
			$jamkesmas= $data->val($i, 20);
			

			 // masukin ke tabel impor
			$query="INSERT INTO impor(dusun, rw,rt, nama, nik, sex, tempatlahir, tanggallahir, agama_id, pendidikan_kk_id,  pendidikan_sedang_id, pekerjaan_id, status_kawin, kk_level, warganegara_id, nama_ayah, nama_ibu, golongan_darah_id, jamkesmas,id_kk) VALUES ('$dusun','$rw','$rt',$nama,'$nik' ,'$sex','$tempatlahir','$tanggallahir','$agama_id','$pendidikan_kk_id','$pendidikan_sedang_id','$pekerjaan_id','$status_kawin','$kk_level','$warganegara_id',$nama_ayah,$nama_ibu,'$golongan_darah_id','$jamkesmas','$id_kk')";
			
			if($nama!="" AND $nik!="" AND $id_kk!="" AND $dusun!=""){
				$hasil = mysql_query($query);
			}
				if($hasil){
					$sukses++;
				}else{
					$gagal++;
					$baris2 .=$i.",";
				}
				$hasil = null;
			}

			if($gagal==0)
				$baris2 ="tidak ada data yang gagal di import.";
				
			// masukin ke tabel tweb_wil_clusterdesa
				$query="INSERT INTO tweb_wil_clusterdesa(rt,rw,dusun) select * from (SELECT rt, rw, dusun from impor GROUP BY rt,rw,dusun
						union SELECT '0' as rt, '0' as rw, dusun from impor GROUP BY dusun
						union SELECT '0' as rt, '-' as rw, dusun from impor GROUP BY dusun
						union SELECT '-' as rt, '-' as rw, dusun from impor GROUP BY dusun
						union SELECT '-' as rt, rw as rw, dusun from impor GROUP BY dusun,rw
						union SELECT '0' as rt, rw as rw, dusun from impor GROUP BY dusun,rw) as tb";
				$hasil = mysql_query($query);

			// masukin ke tabel tweb_penduduk
				$query="INSERT INTO tweb_keluarga(no_kk) SELECT * FROM (SELECT a.id_kk FROM impor a GROUP BY id_kk) as tb";
				$hasil = mysql_query($query);

			// masukin ke tabel tweb_penduduk
				$query="INSERT INTO tweb_penduduk(nama, nik, id_kk, kk_level, sex, tempatlahir, tanggallahir, agama_id, pendidikan_kk_id, pendidikan_id, pendidikan_sedang_id, pekerjaan_id, status_kawin, warganegara_id, nama_ayah, nama_ibu, golongan_darah_id, jamkesmas, id_cluster,status) SELECT * FROM (SELECT nama, nik, (SELECT id FROM tweb_keluarga WHERE no_kk=a.id_kk) as id_kk, kk_level, sex, tempatlahir, tanggallahir, agama_id, pendidikan_kk_id, pendidikan_id, pendidikan_sedang_id, pekerjaan_id, status_kawin, warganegara_id, nama_ayah, nama_ibu, golongan_darah_id, jamkesmas, (SELECT id FROM tweb_wil_clusterdesa where dusun=a.dusun AND rw=a.rw AND rt=a.rt) as id_cluster,'1' as status from impor a) as tb";
				$hasil = mysql_query($query);

			// masukin ke tabel tweb_penduduk
				$sql="SELECT id FROM tweb_keluarga";
				if ($a=mysql_query($sql)){
						while ($hsl=mysql_fetch_array($a)){
							$idnya=($hsl['id']);
							$kirim = "UPDATE tweb_keluarga SET nik_kepala=(SELECT id FROM tweb_penduduk where kk_level='1' AND id_kk=$idnya) WHERE id=$idnya";
							$query=mysql_query($kirim);
						}
					}

			$a="DROP TABLE impor";
			$b = mysql_query($a);

			$a="DELETE FROM tweb_wil_clusterdesa WHERE dusun = '' OR rt = '' OR rw='';";
			$b = mysql_query($a);

			$a="DELETE FROM tweb_keluarga WHERE nik_kepala = '' OR nik_kepala is null;";
			$b = mysql_query($a);

			$a="DELETE FROM  tweb_penduduk WHERE nama = '' AND nik = '';";
			$b = mysql_query($a);

			$a="ALTER TABLE tweb_penduduk ENGINE = InnoDB ROW_FORMAT = DYNAMIC;";
			$b = mysql_query($a);

			$a="ALTER TABLE tweb_keluarga ENGINE = InnoDB ROW_FORMAT = DYNAMIC;";
			$b = mysql_query($a);
			
			$_SESSION['gagal']=$gagal;
			$_SESSION['sukses']=$sukses;
			$_SESSION['baris']=$baris2;
			
			if($gagal==0) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
			
			//return $main;
		}
	
	function import_dasar(){

		$data = "";
		$in = "";
		$outp = "";
		$filename = $_FILES['userfile']['tmp_name'];
		if ($filename!=''){	
			$lines = file($filename);
			foreach ($lines as $line){$data .= $line;}
			$penduduk=Parse_Data($data,"<penduduk>","</penduduk>");
			$keluarga=Parse_Data($data,"<keluarga>","</keluarga>");
			$cluster=Parse_Data($data,"<cluster>","</cluster>");
			//echo $cluster;
			$penduduk=explode("\r\n",$penduduk);
			$keluarga=explode("\r\n",$keluarga);
			$cluster=explode("\r\n",$cluster);
			
			$inset = "INSERT INTO tweb_penduduk VALUES ";
			for($a=1;$a<(count($penduduk)-1);$a++){
				$p = preg_split("/\+/", $penduduk[$a]);
				$in .= "(";
				for($j=0;$j<(count($p));$j++){
					$in .= ',"'.$p[$j].'"';
				}
				$in .= "),";
			}
			$x = strlen($in);
			$in[$x-1] =";";
			$outp = mysql_query($inset.$in);
			//echo $inset.$in;
			
			$in = "";
			$inset = "INSERT INTO tweb_wil_clusterdesa VALUES ";
			for($a=1;$a<(count($cluster)-1);$a++){
				$p = preg_split("/\+/", $cluster[$a]);
				$in .= "(";
				for($j=0;$j<(count($p));$j++){
					$in .= ',"'.$p[$j].'"';
				}
				$in .= "),";
			}
			$x = strlen($in);
			$in[$x-1] =";";
			$outp = mysql_query($inset.$in);
			
			$in = "";
			$inset = "INSERT INTO tweb_keluarga VALUES ";
			for($a=1;$a<(count($keluarga)-1);$a++){
				$p = preg_split("/\+/", $keluarga[$a]);
				$in .= "(";
				for($j=0;$j<(count($p));$j++){
					$in .= ',"'.$p[$j].'"';
				}
				$in .= "),";
			}
			$x = strlen($in);
			$in[$x-1] =";";
			$outp = mysql_query($inset.$in);
		}
		if($outp) $_SESSION['success']=1;
		else $_SESSION['success']=-1;
	}
	
	function import_akp(){
		$id_desa = $_SESSION['user'];
		$data = "";
		$in = "";
		$outp = "";
		$filename = $_FILES['userfile']['tmp_name'];
		if ($filename!=''){	
			$lines = file($filename);
			foreach ($lines as $line){$data .= $line;}
			$penduduk=Parse_Data($data,"<akpkeluarga>","</akpkeluarga>");
			//echo $cluster;
			$penduduk=explode("\r\n",$penduduk);
			
			$inset = "INSERT INTO analisis_keluarga VALUES ";
			for($a=1;$a<(count($penduduk)-1);$a++){
				$p = preg_split("/\+/", $penduduk[$a]);
				$in .= "(".$id_desa;
				for($j=0;$j<(count($p));$j++){
					$in .= ',"'.$p[$j].'"';
				}
				$in .= "),";
			}
			$x = strlen($in);
			$in[$x-1] =";";
			$outp = mysql_query($inset.$in);
			
		}
		if($outp) $_SESSION['success']=1;
		else $_SESSION['success']=-1;
	}
}

define('NUM_BIG_BLOCK_DEPOT_BLOCKS_POS', 0x2c);
define('SMALL_BLOCK_DEPOT_BLOCK_POS', 0x3c);
define('ROOT_START_BLOCK_POS', 0x30);
define('BIG_BLOCK_SIZE', 0x200);
define('SMALL_BLOCK_SIZE', 0x40);
define('EXTENSION_BLOCK_POS', 0x44);
define('NUM_EXTENSION_BLOCK_POS', 0x48);
define('PROPERTY_STORAGE_BLOCK_SIZE', 0x80);
define('BIG_BLOCK_DEPOT_BLOCKS_POS', 0x4c);
define('SMALL_BLOCK_THRESHOLD', 0x1000);
// property storage offsets
define('SIZE_OF_NAME_POS', 0x40);
define('TYPE_POS', 0x42);
define('START_BLOCK_POS', 0x74);
define('SIZE_POS', 0x78);
define('IDENTIFIER_OLE', pack("CCCCCCCC",0xd0,0xcf,0x11,0xe0,0xa1,0xb1,0x1a,0xe1));


function GetInt4d($data, $pos) {
	$value = ord($data[$pos]) | (ord($data[$pos+1])	<< 8) | (ord($data[$pos+2]) << 16) | (ord($data[$pos+3]) << 24);
	if ($value>=4294967294) {
		$value=-2;
	}
	return $value;
}

// http://uk.php.net/manual/en/function.getdate.php
function gmgetdate($ts = null){
	$k = array('seconds','minutes','hours','mday','wday','mon','year','yday','weekday','month',0);
	return(array_comb($k,explode(":",gmdate('s:i:G:j:w:n:Y:z:l:F:U',is_null($ts)?time():$ts))));
	} 

// Added for PHP4 compatibility
function array_comb($array1, $array2) {
	$out = array();
	foreach ($array1 as $key => $value) {
		$out[$value] = $array2[$key];
	}
	return $out;
}

function v($data,$pos) {
	return ord($data[$pos]) | ord($data[$pos+1])<<8;
}

class OLERead {
	var $data = '';
	function OLERead(){	}

	function read($sFileName){
		// check if file exist and is readable (Darko Miljanovic)
		if(!is_readable($sFileName)) {
			$this->error = 1;
			return false;
		}
		$this->data = @file_get_contents($sFileName);
		if (!$this->data) {
			$this->error = 1;
			return false;
   		}
   		if (substr($this->data, 0, 8) != IDENTIFIER_OLE) {
			$this->error = 1;
			return false;
   		}
		$this->numBigBlockDepotBlocks = GetInt4d($this->data, NUM_BIG_BLOCK_DEPOT_BLOCKS_POS);
		$this->sbdStartBlock = GetInt4d($this->data, SMALL_BLOCK_DEPOT_BLOCK_POS);
		$this->rootStartBlock = GetInt4d($this->data, ROOT_START_BLOCK_POS);
		$this->extensionBlock = GetInt4d($this->data, EXTENSION_BLOCK_POS);
		$this->numExtensionBlocks = GetInt4d($this->data, NUM_EXTENSION_BLOCK_POS);

		$bigBlockDepotBlocks = array();
		$pos = BIG_BLOCK_DEPOT_BLOCKS_POS;
		$bbdBlocks = $this->numBigBlockDepotBlocks;
		if ($this->numExtensionBlocks != 0) {
			$bbdBlocks = (BIG_BLOCK_SIZE - BIG_BLOCK_DEPOT_BLOCKS_POS)/4;
		}

		for ($i = 0; $i < $bbdBlocks; $i++) {
			$bigBlockDepotBlocks[$i] = GetInt4d($this->data, $pos);
			$pos += 4;
		}


		for ($j = 0; $j < $this->numExtensionBlocks; $j++) {
			$pos = ($this->extensionBlock + 1) * BIG_BLOCK_SIZE;
			$blocksToRead = min($this->numBigBlockDepotBlocks - $bbdBlocks, BIG_BLOCK_SIZE / 4 - 1);

			for ($i = $bbdBlocks; $i < $bbdBlocks + $blocksToRead; $i++) {
				$bigBlockDepotBlocks[$i] = GetInt4d($this->data, $pos);
				$pos += 4;
			}

			$bbdBlocks += $blocksToRead;
			if ($bbdBlocks < $this->numBigBlockDepotBlocks) {
				$this->extensionBlock = GetInt4d($this->data, $pos);
			}
		}

		// readBigBlockDepot
		$pos = 0;
		$index = 0;
		$this->bigBlockChain = array();

		for ($i = 0; $i < $this->numBigBlockDepotBlocks; $i++) {
			$pos = ($bigBlockDepotBlocks[$i] + 1) * BIG_BLOCK_SIZE;
			//echo "pos = $pos";
			for ($j = 0 ; $j < BIG_BLOCK_SIZE / 4; $j++) {
				$this->bigBlockChain[$index] = GetInt4d($this->data, $pos);
				$pos += 4 ;
				$index++;
			}
		}

		// readSmallBlockDepot();
		$pos = 0;
		$index = 0;
		$sbdBlock = $this->sbdStartBlock;
		$this->smallBlockChain = array();

		while ($sbdBlock != -2) {
		  $pos = ($sbdBlock + 1) * BIG_BLOCK_SIZE;
		  for ($j = 0; $j < BIG_BLOCK_SIZE / 4; $j++) {
			$this->smallBlockChain[$index] = GetInt4d($this->data, $pos);
			$pos += 4;
			$index++;
		  }
		  $sbdBlock = $this->bigBlockChain[$sbdBlock];
		}


		// readData(rootStartBlock)
		$block = $this->rootStartBlock;
		$pos = 0;
		$this->entry = $this->__readData($block);
		$this->__readPropertySets();
	}

	function __readData($bl) {
		$block = $bl;
		$pos = 0;
		$data = '';
		while ($block != -2)  {
			$pos = ($block + 1) * BIG_BLOCK_SIZE;
			$data = $data.substr($this->data, $pos, BIG_BLOCK_SIZE);
			$block = $this->bigBlockChain[$block];
		}
		return $data;
	 }

	function __readPropertySets(){
		$offset = 0;
		while ($offset < strlen($this->entry)) {
			$d = substr($this->entry, $offset, PROPERTY_STORAGE_BLOCK_SIZE);
			$nameSize = ord($d[SIZE_OF_NAME_POS]) | (ord($d[SIZE_OF_NAME_POS+1]) << 8);
			$type = ord($d[TYPE_POS]);
			$startBlock = GetInt4d($d, START_BLOCK_POS);
			$size = GetInt4d($d, SIZE_POS);
			$name = '';
			for ($i = 0; $i < $nameSize ; $i++) {
				$name .= $d[$i];
			}
			$name = str_replace("\x00", "", $name);
			$this->props[] = array (
				'name' => $name,
				'type' => $type,
				'startBlock' => $startBlock,
				'size' => $size);
			if ((strtolower($name) == "workbook") || ( strtolower($name) == "book")) {
				$this->wrkbook = count($this->props) - 1;
			}
			if ($name == "Root Entry") {
				$this->rootentry = count($this->props) - 1;
			}
			$offset += PROPERTY_STORAGE_BLOCK_SIZE;
		}

	}


	function getWorkBook(){
		if ($this->props[$this->wrkbook]['size'] < SMALL_BLOCK_THRESHOLD){
			$rootdata = $this->__readData($this->props[$this->rootentry]['startBlock']);
			$streamData = '';
			$block = $this->props[$this->wrkbook]['startBlock'];
			$pos = 0;
			while ($block != -2) {
	  			  $pos = $block * SMALL_BLOCK_SIZE;
				  $streamData .= substr($rootdata, $pos, SMALL_BLOCK_SIZE);
				  $block = $this->smallBlockChain[$block];
			}
			return $streamData;
		}else{
			$numBlocks = $this->props[$this->wrkbook]['size'] / BIG_BLOCK_SIZE;
			if ($this->props[$this->wrkbook]['size'] % BIG_BLOCK_SIZE != 0) {
				$numBlocks++;
			}

			if ($numBlocks == 0) return '';
			$streamData = '';
			$block = $this->props[$this->wrkbook]['startBlock'];
			$pos = 0;
			while ($block != -2) {
			  $pos = ($block + 1) * BIG_BLOCK_SIZE;
			  $streamData .= substr($this->data, $pos, BIG_BLOCK_SIZE);
			  $block = $this->bigBlockChain[$block];
			}
			return $streamData;
		}
	}

}

define('SPREADSHEET_EXCEL_READER_BIFF8',			 0x600);
define('SPREADSHEET_EXCEL_READER_BIFF7',			 0x500);
define('SPREADSHEET_EXCEL_READER_WORKBOOKGLOBALS',   0x5);
define('SPREADSHEET_EXCEL_READER_WORKSHEET',		 0x10);
define('SPREADSHEET_EXCEL_READER_TYPE_BOF',		  0x809);
define('SPREADSHEET_EXCEL_READER_TYPE_EOF',		  0x0a);
define('SPREADSHEET_EXCEL_READER_TYPE_BOUNDSHEET',   0x85);
define('SPREADSHEET_EXCEL_READER_TYPE_DIMENSION',	0x200);
define('SPREADSHEET_EXCEL_READER_TYPE_ROW',		  0x208);
define('SPREADSHEET_EXCEL_READER_TYPE_DBCELL',	   0xd7);
define('SPREADSHEET_EXCEL_READER_TYPE_FILEPASS',	 0x2f);
define('SPREADSHEET_EXCEL_READER_TYPE_NOTE',		 0x1c);
define('SPREADSHEET_EXCEL_READER_TYPE_TXO',		  0x1b6);
define('SPREADSHEET_EXCEL_READER_TYPE_RK',		   0x7e);
define('SPREADSHEET_EXCEL_READER_TYPE_RK2',		  0x27e);
define('SPREADSHEET_EXCEL_READER_TYPE_MULRK',		0xbd);
define('SPREADSHEET_EXCEL_READER_TYPE_MULBLANK',	 0xbe);
define('SPREADSHEET_EXCEL_READER_TYPE_INDEX',		0x20b);
define('SPREADSHEET_EXCEL_READER_TYPE_SST',		  0xfc);
define('SPREADSHEET_EXCEL_READER_TYPE_EXTSST',	   0xff);
define('SPREADSHEET_EXCEL_READER_TYPE_CONTINUE',	 0x3c);
define('SPREADSHEET_EXCEL_READER_TYPE_LABEL',		0x204);
define('SPREADSHEET_EXCEL_READER_TYPE_LABELSST',	 0xfd);
define('SPREADSHEET_EXCEL_READER_TYPE_NUMBER',	   0x203);
define('SPREADSHEET_EXCEL_READER_TYPE_NAME',		 0x18);
define('SPREADSHEET_EXCEL_READER_TYPE_ARRAY',		0x221);
define('SPREADSHEET_EXCEL_READER_TYPE_STRING',	   0x207);
define('SPREADSHEET_EXCEL_READER_TYPE_FORMULA',	  0x406);
define('SPREADSHEET_EXCEL_READER_TYPE_FORMULA2',	 0x6);
define('SPREADSHEET_EXCEL_READER_TYPE_FORMAT',	   0x41e);
define('SPREADSHEET_EXCEL_READER_TYPE_XF',		   0xe0);
define('SPREADSHEET_EXCEL_READER_TYPE_BOOLERR',	  0x205);
define('SPREADSHEET_EXCEL_READER_TYPE_FONT',	  0x0031);
define('SPREADSHEET_EXCEL_READER_TYPE_PALETTE',	  0x0092);
define('SPREADSHEET_EXCEL_READER_TYPE_UNKNOWN',	  0xffff);
define('SPREADSHEET_EXCEL_READER_TYPE_NINETEENFOUR', 0x22);
define('SPREADSHEET_EXCEL_READER_TYPE_MERGEDCELLS',  0xE5);
define('SPREADSHEET_EXCEL_READER_UTCOFFSETDAYS' ,	25569);
define('SPREADSHEET_EXCEL_READER_UTCOFFSETDAYS1904', 24107);
define('SPREADSHEET_EXCEL_READER_MSINADAY',		  86400);
define('SPREADSHEET_EXCEL_READER_TYPE_HYPER',	     0x01b8);
define('SPREADSHEET_EXCEL_READER_TYPE_COLINFO',	     0x7d);
define('SPREADSHEET_EXCEL_READER_TYPE_DEFCOLWIDTH',  0x55);
define('SPREADSHEET_EXCEL_READER_TYPE_STANDARDWIDTH', 0x99);
define('SPREADSHEET_EXCEL_READER_DEF_NUM_FORMAT',	"%s");


/*
* Main Class
*/
class Spreadsheet_Excel_Reader {

	// MK: Added to make data retrieval easier
	var $colnames = array();
	var $colindexes = array();
	var $standardColWidth = 0;
	var $defaultColWidth = 0;

	function myHex($d) {
		if ($d < 16) return "0" . dechex($d);
		return dechex($d);
	}
	
	function dumpHexData($data, $pos, $length) {
		$info = "";
		for ($i = 0; $i <= $length; $i++) {
			$info .= ($i==0?"":" ") . $this->myHex(ord($data[$pos + $i])) . (ord($data[$pos + $i])>31? "[" . $data[$pos + $i] . "]":'');
		}
		return $info;
	}

	function getCol($col) {
		if (is_string($col)) {
			$col = strtolower($col);
			if (array_key_exists($col,$this->colnames)) {
				$col = $this->colnames[$col];
			}
		}
		return $col;
	}

	// PUBLIC API FUNCTIONS
	// --------------------

	function val($row,$col,$sheet=0) {
		$col = $this->getCol($col);
		if (array_key_exists($row,$this->sheets[$sheet]['cells']) && array_key_exists($col,$this->sheets[$sheet]['cells'][$row])) {
			return $this->sheets[$sheet]['cells'][$row][$col];
		}
		return "";
	}
	function value($row,$col,$sheet=0) {
		return $this->val($row,$col,$sheet);
	}

	function type($row,$col,$sheet=0) {
		return $this->info($row,$col,'type',$sheet);
	}
	function raw($row,$col,$sheet=0) {
		return $this->info($row,$col,'raw',$sheet);
	}

	function rowcount($sheet=0) {
		return $this->sheets[$sheet]['numRows'];
	}
	function colcount($sheet=0) {
		return $this->sheets[$sheet]['numCols'];
	}

	// FORMAT PROPERTIES
	// =================
	function format($row,$col,$sheet=0) {
		return $this->info($row,$col,'format',$sheet);
	}
	function formatIndex($row,$col,$sheet=0) {
		return $this->info($row,$col,'formatIndex',$sheet);
	}
	function formatColor($row,$col,$sheet=0) {
		return $this->info($row,$col,'formatColor',$sheet);
	}
	
	// CELL (XF) PROPERTIES
	// ====================
	function xfRecord($row,$col,$sheet=0) {
		$xfIndex = $this->info($row,$col,'xfIndex',$sheet);
		if ($xfIndex!="") {
			return $this->xfRecords[$xfIndex];
		}
		return null;
	}
	function xfProperty($row,$col,$sheet,$prop) {
		$xfRecord = $this->xfRecord($row,$col,$sheet);
		if ($xfRecord!=null) {
			return $xfRecord[$prop];
		}
		return "";
	}
	// DUMP AN HTML TABLE OF THE ENTIRE XLS DATA
	// =========================================

	// --------------
	// END PUBLIC API


	var $boundsheets = array();
	var $formatRecords = array();
	var $fontRecords = array();
	var $xfRecords = array();
	var $colInfo = array();
   	var $rowInfo = array();
	
	var $sst = array();
	var $sheets = array();

	var $data;
	var $_ole;
	var $_defaultEncoding = "UTF-8";
	var $_defaultFormat = SPREADSHEET_EXCEL_READER_DEF_NUM_FORMAT;
	var $_columnsFormat = array();
	var $_rowoffset = 1;
	var $_coloffset = 1;

	/**
	 * List of default date formats used by Excel
	 */
	var $dateFormats = array (
		0xe => "m/d/Y",
		0xf => "M-d-Y",
		0x10 => "d-M",
		0x11 => "M-Y",
		0x12 => "h:i a",
		0x13 => "h:i:s a",
		0x14 => "H:i",
		0x15 => "H:i:s",
		0x16 => "d/m/Y H:i",
		0x2d => "i:s",
		0x2e => "H:i:s",
		0x2f => "i:s.S"
	);

	/**
	 * Default number formats used by Excel
	 */
	var $numberFormats = array(
		0x1 => "0",
		0x2 => "0.00",
		0x3 => "#,##0",
		0x4 => "#,##0.00",
		0x5 => "\$#,##0;(\$#,##0)",
		0x6 => "\$#,##0;[Red](\$#,##0)",
		0x7 => "\$#,##0.00;(\$#,##0.00)",
		0x8 => "\$#,##0.00;[Red](\$#,##0.00)",
		0x9 => "0%",
		0xa => "0.00%",
		0xb => "0.00E+00",
		0x25 => "#,##0;(#,##0)",
		0x26 => "#,##0;[Red](#,##0)",
		0x27 => "#,##0.00;(#,##0.00)",
		0x28 => "#,##0.00;[Red](#,##0.00)",
		0x29 => "#,##0;(#,##0)",  // Not exactly
		0x2a => "\$#,##0;(\$#,##0)",  // Not exactly
		0x2b => "#,##0.00;(#,##0.00)",  // Not exactly
		0x2c => "\$#,##0.00;(\$#,##0.00)",  // Not exactly
		0x30 => "##0.0E+0"
	);

    var $colors = Array(
        0x00 => "#000000",
        0x01 => "#FFFFFF",
        0x02 => "#FF0000",
        0x03 => "#00FF00",
        0x04 => "#0000FF",
        0x05 => "#FFFF00",
        0x06 => "#FF00FF",
        0x07 => "#00FFFF",
        0x08 => "#000000",
        0x09 => "#FFFFFF",
        0x0A => "#FF0000",
        0x0B => "#00FF00",
        0x0C => "#0000FF",
        0x0D => "#FFFF00",
        0x0E => "#FF00FF",
        0x0F => "#00FFFF",
        0x10 => "#800000",
        0x11 => "#008000",
        0x12 => "#000080",
        0x13 => "#808000",
        0x14 => "#800080",
        0x15 => "#008080",
        0x16 => "#C0C0C0",
        0x17 => "#808080",
        0x18 => "#9999FF",
        0x19 => "#993366",
        0x1A => "#FFFFCC",
        0x1B => "#CCFFFF",
        0x1C => "#660066",
        0x1D => "#FF8080",
        0x1E => "#0066CC",
        0x1F => "#CCCCFF",
        0x20 => "#000080",
        0x21 => "#FF00FF",
        0x22 => "#FFFF00",
        0x23 => "#00FFFF",
        0x24 => "#800080",
        0x25 => "#800000",
        0x26 => "#008080",
        0x27 => "#0000FF",
        0x28 => "#00CCFF",
        0x29 => "#CCFFFF",
        0x2A => "#CCFFCC",
        0x2B => "#FFFF99",
        0x2C => "#99CCFF",
        0x2D => "#FF99CC",
        0x2E => "#CC99FF",
        0x2F => "#FFCC99",
        0x30 => "#3366FF",
        0x31 => "#33CCCC",
        0x32 => "#99CC00",
        0x33 => "#FFCC00",
        0x34 => "#FF9900",
        0x35 => "#FF6600",
        0x36 => "#666699",
        0x37 => "#969696",
        0x38 => "#003366",
        0x39 => "#339966",
        0x3A => "#003300",
        0x3B => "#333300",
        0x3C => "#993300",
        0x3D => "#993366",
        0x3E => "#333399",
        0x3F => "#333333",
        0x40 => "#000000",
        0x41 => "#FFFFFF",

        0x43 => "#000000",
        0x4D => "#000000",
        0x4E => "#FFFFFF",
        0x4F => "#000000",
        0x50 => "#FFFFFF",
        0x51 => "#000000",

        0x7FFF => "#000000"
    );

	var $lineStyles = array(
		0x00 => "",
		0x01 => "Thin",
		0x02 => "Medium",
		0x03 => "Dashed",
		0x04 => "Dotted",
		0x05 => "Thick",
		0x06 => "Double",
		0x07 => "Hair",
		0x08 => "Medium dashed",
		0x09 => "Thin dash-dotted",
		0x0A => "Medium dash-dotted",
		0x0B => "Thin dash-dot-dotted",
		0x0C => "Medium dash-dot-dotted",
		0x0D => "Slanted medium dash-dotted"
	);	

	var $lineStylesCss = array(
		"Thin" => "1px solid", 
		"Medium" => "2px solid", 
		"Dashed" => "1px dashed", 
		"Dotted" => "1px dotted", 
		"Thick" => "3px solid", 
		"Double" => "double", 
		"Hair" => "1px solid", 
		"Medium dashed" => "2px dashed", 
		"Thin dash-dotted" => "1px dashed", 
		"Medium dash-dotted" => "2px dashed", 
		"Thin dash-dot-dotted" => "1px dashed", 
		"Medium dash-dot-dotted" => "2px dashed", 
		"Slanted medium dash-dotte" => "2px dashed" 
	);
	
	function read16bitstring($data, $start) {
		$len = 0;
		while (ord($data[$start + $len]) + ord($data[$start + $len + 1]) > 0) $len++;
		return substr($data, $start, $len);
	}
	
	// ADDED by Matt Kruse for better formatting
	function _format_value($format,$num,$f) {
		// 49==TEXT format
		// http://code.google.com/p/php-excel-reader/issues/detail?id=7
		if ( (!$f && $format=="%s") || ($f==49) || ($format=="GENERAL") ) { 
			return array('string'=>$num, 'formatColor'=>null); 
		}

		// Custom pattern can be POSITIVE;NEGATIVE;ZERO
		// The "text" option as 4th parameter is not handled
		$parts = explode(';',$format);
		$pattern = $parts[0];
		// Negative pattern
		if (count($parts)>2 && $num==0) {
			$pattern = $parts[2];
		}
		// Zero pattern
		if (count($parts)>1 && $num<0) {
			$pattern = $parts[1];
			$num = abs($num);
		}

		$color = "";
		$matches = array();
		$color_regex = "/^\[(BLACK|BLUE|CYAN|GREEN|MAGENTA|RED|WHITE|YELLOW)\]/i";
		if (preg_match($color_regex,$pattern,$matches)) {
			$color = strtolower($matches[1]);
			$pattern = preg_replace($color_regex,"",$pattern);
		}
		
		// In Excel formats, "_" is used to add spacing, which we can't do in HTML
		$pattern = preg_replace("/_./","",$pattern);
		
		// Some non-number characters are escaped with \, which we don't need
		$pattern = preg_replace("/\\\/","",$pattern);
		
		// Some non-number strings are quoted, so we'll get rid of the quotes
		$pattern = preg_replace("/\"/","",$pattern);

		// TEMPORARY - Convert # to 0
		$pattern = preg_replace("/\#/","0",$pattern);

		// Find out if we need comma formatting
		$has_commas = preg_match("/,/",$pattern);
		if ($has_commas) {
			$pattern = preg_replace("/,/","",$pattern);
		}

		// Handle Percentages
		if (preg_match("/\d(\%)([^\%]|$)/",$pattern,$matches)) {
			$num = $num * 100;
			$pattern = preg_replace("/(\d)(\%)([^\%]|$)/","$1%$3",$pattern);
		}

		// Handle the number itself
		$number_regex = "/(\d+)(\.?)(\d*)/";
		if (preg_match($number_regex,$pattern,$matches)) {
			$left = $matches[1];
			$dec = $matches[2];
			$right = $matches[3];
			if ($has_commas) {
				$formatted = number_format($num,strlen($right));
			}
			else {
				$sprintf_pattern = "%1.".strlen($right)."f";
				$formatted = sprintf($sprintf_pattern, $num);
			}
			$pattern = preg_replace($number_regex, $formatted, $pattern);
		}

		return array(
			'string'=>$pattern,
			'formatColor'=>$color
		);
	}

	/**
	 * Constructor
	 *
	 * Some basic initialisation
	 */
	function Spreadsheet_Excel_Reader($file='') {
		$this->_ole = new OLERead();
	//	$this->setUTFEncoder('iconv');
		//if ($outputEncoding != '') { 
		//	$this->setOutputEncoding($outputEncoding);
		//}
		for ($i=1; $i<245; $i++) {
			$name = strtolower(( (($i-1)/26>=1)?chr(($i-1)/26+64):'') . chr(($i-1)%26+65));
			$this->colnames[$name] = $i;
			$this->colindexes[$i] = $name;
		}
		//$this->store_extended_info = $store_extended_info;
		if ($file!="") {
			$this->read($file);
		}
	}


	function setRowColOffset($iOffset) {
		$this->_rowoffset = $iOffset;
		$this->_coloffset = $iOffset;
	}

	/**
	 * Set the default number format
	 */
	function setDefaultFormat($sFormat) {
		$this->_defaultFormat = $sFormat;
	}

	/**
	 * Force a column to use a certain format
	 */
	function setColumnFormat($column, $sFormat) {
		$this->_columnsFormat[$column] = $sFormat;
	}

	/**
	 * Read the spreadsheet file using OLE, then parse
	 */
	function read($sFileName) {
		$res = $this->_ole->read($sFileName);
		$this->data = $this->_ole->getWorkBook();
		$this->_parse();
	}


	function _parse() {
		$pos = 0;
		$data = $this->data;

		$code = v($data,$pos);
		$length = v($data,$pos+2);
		$version = v($data,$pos+4);
		$substreamType = v($data,$pos+6);

		$this->version = $version;

		if (($version != SPREADSHEET_EXCEL_READER_BIFF8) &&
			($version != SPREADSHEET_EXCEL_READER_BIFF7)) {
			return false;
		}

		if ($substreamType != SPREADSHEET_EXCEL_READER_WORKBOOKGLOBALS){
			return false;
		}

		$pos += $length + 4;

		$code = v($data,$pos);
		$length = v($data,$pos+2);

		while ($code != SPREADSHEET_EXCEL_READER_TYPE_EOF) {
			switch ($code) {
				case SPREADSHEET_EXCEL_READER_TYPE_SST:
					$spos = $pos + 4;
					$limitpos = $spos + $length;
					$uniqueStrings = $this->_GetInt4d($data, $spos+4);
					$spos += 8;
					for ($i = 0; $i < $uniqueStrings; $i++) {
						// Read in the number of characters
						if ($spos == $limitpos) {
							$opcode = v($data,$spos);
							$conlength = v($data,$spos+2);
							if ($opcode != 0x3c) {
								return -1;
							}
							$spos += 4;
							$limitpos = $spos + $conlength;
						}
						$numChars = ord($data[$spos]) | (ord($data[$spos+1]) << 8);
						$spos += 2;
						$optionFlags = ord($data[$spos]);
						$spos++;
						$asciiEncoding = (($optionFlags & 0x01) == 0) ;
						$extendedString = ( ($optionFlags & 0x04) != 0);

						// See if string contains formatting information
						$richString = ( ($optionFlags & 0x08) != 0);

						if ($richString) {
							// Read in the crun
							$formattingRuns = v($data,$spos);
							$spos += 2;
						}

						if ($extendedString) {
							// Read in cchExtRst
							$extendedRunLength = $this->_GetInt4d($data, $spos);
							$spos += 4;
						}

						$len = ($asciiEncoding)? $numChars : $numChars*2;
						if ($spos + $len < $limitpos) {
							$retstr = substr($data, $spos, $len);
							$spos += $len;
						}
						else{
							// found countinue
							$retstr = substr($data, $spos, $limitpos - $spos);
							$bytesRead = $limitpos - $spos;
							$charsLeft = $numChars - (($asciiEncoding) ? $bytesRead : ($bytesRead / 2));
							$spos = $limitpos;

							while ($charsLeft > 0){
								$opcode = v($data,$spos);
								$conlength = v($data,$spos+2);
								if ($opcode != 0x3c) {
									return -1;
								}
								$spos += 4;
								$limitpos = $spos + $conlength;
								$option = ord($data[$spos]);
								$spos += 1;
								if ($asciiEncoding && ($option == 0)) {
									$len = min($charsLeft, $limitpos - $spos); // min($charsLeft, $conlength);
									$retstr .= substr($data, $spos, $len);
									$charsLeft -= $len;
									$asciiEncoding = true;
								}
								elseif (!$asciiEncoding && ($option != 0)) {
									$len = min($charsLeft * 2, $limitpos - $spos); // min($charsLeft, $conlength);
									$retstr .= substr($data, $spos, $len);
									$charsLeft -= $len/2;
									$asciiEncoding = false;
								}
								elseif (!$asciiEncoding && ($option == 0)) {
									// Bummer - the string starts off as Unicode, but after the
									// continuation it is in straightforward ASCII encoding
									$len = min($charsLeft, $limitpos - $spos); // min($charsLeft, $conlength);
									for ($j = 0; $j < $len; $j++) {
										$retstr .= $data[$spos + $j].chr(0);
									}
									$charsLeft -= $len;
									$asciiEncoding = false;
								}
								else{
									$newstr = '';
									for ($j = 0; $j < strlen($retstr); $j++) {
										$newstr = $retstr[$j].chr(0);
									}
									$retstr = $newstr;
									$len = min($charsLeft * 2, $limitpos - $spos); // min($charsLeft, $conlength);
									$retstr .= substr($data, $spos, $len);
									$charsLeft -= $len/2;
									$asciiEncoding = false;
								}
								$spos += $len;
							}
						}
						$retstr = ($asciiEncoding) ? $retstr : $this->_encodeUTF16($retstr);

						if ($richString){
							$spos += 4 * $formattingRuns;
						}

						// For extended strings, skip over the extended string data
						if ($extendedString) {
							$spos += $extendedRunLength;
						}
						$this->sst[]=$retstr;
					}
					break;
				case SPREADSHEET_EXCEL_READER_TYPE_FILEPASS:
					return false;
					break;
				case SPREADSHEET_EXCEL_READER_TYPE_NAME:
					break;
				case SPREADSHEET_EXCEL_READER_TYPE_FORMAT:
					$indexCode = v($data,$pos+4);
					if ($version == SPREADSHEET_EXCEL_READER_BIFF8) {
						$numchars = v($data,$pos+6);
						if (ord($data[$pos+8]) == 0){
							$formatString = substr($data, $pos+9, $numchars);
						} else {
							$formatString = substr($data, $pos+9, $numchars*2);
						}
					} else {
						$numchars = ord($data[$pos+6]);
						$formatString = substr($data, $pos+7, $numchars*2);
					}
					$this->formatRecords[$indexCode] = $formatString;
					break;
				case SPREADSHEET_EXCEL_READER_TYPE_PALETTE:
						$colors = ord($data[$pos+4]) | ord($data[$pos+5]) << 8;
						for ($coli = 0; $coli < $colors; $coli++) {
						    $colOff = $pos + 2 + ($coli * 4);
  						    $colr = ord($data[$colOff]);
  						    $colg = ord($data[$colOff+1]);
  						    $colb = ord($data[$colOff+2]);
							$this->colors[0x07 + $coli] = '#' . $this->myhex($colr) . $this->myhex($colg) . $this->myhex($colb);
						}
					    break;

				case SPREADSHEET_EXCEL_READER_TYPE_XF:
						$fontIndexCode = (ord($data[$pos+4]) | ord($data[$pos+5]) << 8) - 1;
						$fontIndexCode = max(0,$fontIndexCode);
						$indexCode = ord($data[$pos+6]) | ord($data[$pos+7]) << 8;
						$alignbit = ord($data[$pos+10]) & 3;
						$bgi = (ord($data[$pos+22]) | ord($data[$pos+23]) << 8) & 0x3FFF;
						$bgcolor = ($bgi & 0x7F);
//						$bgcolor = ($bgi & 0x3f80) >> 7;
						$align = "";
						if ($alignbit==3) { $align="right"; }
						if ($alignbit==2) { $align="center"; }

						$fillPattern = (ord($data[$pos+21]) & 0xFC) >> 2;
						if ($fillPattern == 0) {
							$bgcolor = "";
						}

						$xf = array();
						$xf['formatIndex'] = $indexCode;
						$xf['align'] = $align;
						$xf['fontIndex'] = $fontIndexCode;
						$xf['bgColor'] = $bgcolor;
						$xf['fillPattern'] = $fillPattern;

						$border = ord($data[$pos+14]) | (ord($data[$pos+15]) << 8) | (ord($data[$pos+16]) << 16) | (ord($data[$pos+17]) << 24);
						$xf['borderLeft'] = $this->lineStyles[($border & 0xF)];
						$xf['borderRight'] = $this->lineStyles[($border & 0xF0) >> 4];
						$xf['borderTop'] = $this->lineStyles[($border & 0xF00) >> 8];
						$xf['borderBottom'] = $this->lineStyles[($border & 0xF000) >> 12];
						
						$xf['borderLeftColor'] = ($border & 0x7F0000) >> 16;
						$xf['borderRightColor'] = ($border & 0x3F800000) >> 23;
						$border = (ord($data[$pos+18]) | ord($data[$pos+19]) << 8);

						$xf['borderTopColor'] = ($border & 0x7F);
						$xf['borderBottomColor'] = ($border & 0x3F80) >> 7;
												
						if (array_key_exists($indexCode, $this->dateFormats)) {
							$xf['type'] = 'date';
							$xf['format'] = $this->dateFormats[$indexCode];
							if ($align=='') { $xf['align'] = 'right'; }
						}elseif (array_key_exists($indexCode, $this->numberFormats)) {
							$xf['type'] = 'number';
							$xf['format'] = $this->numberFormats[$indexCode];
							if ($align=='') { $xf['align'] = 'right'; }
						}else{
							$isdate = FALSE;
							$formatstr = '';
							if ($indexCode > 0){
								if (isset($this->formatRecords[$indexCode]))
									$formatstr = $this->formatRecords[$indexCode];
								if ($formatstr!="") {
									$tmp = preg_replace("/\;.*/","",$formatstr);
									$tmp = preg_replace("/^\[[^\]]*\]/","",$tmp);
									if (preg_match("/[^hmsday\/\-:\s\\\,AMP]/i", $tmp) == 0) { // found day and time format
										$isdate = TRUE;
										$formatstr = $tmp;
										$formatstr = str_replace(array('AM/PM','mmmm','mmm'), array('a','F','M'), $formatstr);
										// m/mm are used for both minutes and months - oh SNAP!
										// This mess tries to fix for that.
										// 'm' == minutes only if following h/hh or preceding s/ss
										$formatstr = preg_replace("/(h:?)mm?/","$1i", $formatstr);
										$formatstr = preg_replace("/mm?(:?s)/","i$1", $formatstr);
										// A single 'm' = n in PHP
										$formatstr = preg_replace("/(^|[^m])m([^m]|$)/", '$1n$2', $formatstr);
										$formatstr = preg_replace("/(^|[^m])m([^m]|$)/", '$1n$2', $formatstr);
										// else it's months
										$formatstr = str_replace('mm', 'm', $formatstr);
										// Convert single 'd' to 'j'
										$formatstr = preg_replace("/(^|[^d])d([^d]|$)/", '$1j$2', $formatstr);
										$formatstr = str_replace(array('dddd','ddd','dd','yyyy','yy','hh','h'), array('l','D','d','Y','y','H','g'), $formatstr);
										$formatstr = preg_replace("/ss?/", 's', $formatstr);
									}
								}
							}
							if ($isdate){
								$xf['type'] = 'date';
								$xf['format'] = $formatstr;
								if ($align=='') { $xf['align'] = 'right'; }
							}else{
								// If the format string has a 0 or # in it, we'll assume it's a number
								if (preg_match("/[0#]/", $formatstr)) {
									$xf['type'] = 'number';
									if ($align=='') { $xf['align']='right'; }
								}
								else {
								$xf['type'] = 'other';
								}
								$xf['format'] = $formatstr;
								$xf['code'] = $indexCode;
							}
						}
						$this->xfRecords[] = $xf;
					break;
				case SPREADSHEET_EXCEL_READER_TYPE_NINETEENFOUR:
					$this->nineteenFour = (ord($data[$pos+4]) == 1);
					break;
				case SPREADSHEET_EXCEL_READER_TYPE_BOUNDSHEET:
						$rec_offset = $this->_GetInt4d($data, $pos+4);
						$rec_typeFlag = ord($data[$pos+8]);
						$rec_visibilityFlag = ord($data[$pos+9]);
						$rec_length = ord($data[$pos+10]);

						if ($version == SPREADSHEET_EXCEL_READER_BIFF8){
							$chartype =  ord($data[$pos+11]);
							if ($chartype == 0){
								$rec_name	= substr($data, $pos+12, $rec_length);
							} else {
								$rec_name	= $this->_encodeUTF16(substr($data, $pos+12, $rec_length*2));
							}
						}elseif ($version == SPREADSHEET_EXCEL_READER_BIFF7){
								$rec_name	= substr($data, $pos+11, $rec_length);
						}
					$this->boundsheets[] = array('name'=>$rec_name,'offset'=>$rec_offset);
					break;

			}

			$pos += $length + 4;
			$code = ord($data[$pos]) | ord($data[$pos+1])<<8;
			$length = ord($data[$pos+2]) | ord($data[$pos+3])<<8;
		}

		foreach ($this->boundsheets as $key=>$val){
			$this->sn = $key;
			$this->_parsesheet($val['offset']);
		}
		return true;
	}

	/**
	 * Parse a worksheet
	 */
	function _parsesheet($spos) {
		$cont = true;
		$data = $this->data;
		// read BOF
		$code = ord($data[$spos]) | ord($data[$spos+1])<<8;
		$length = ord($data[$spos+2]) | ord($data[$spos+3])<<8;

		$version = ord($data[$spos + 4]) | ord($data[$spos + 5])<<8;
		$substreamType = ord($data[$spos + 6]) | ord($data[$spos + 7])<<8;

		if (($version != SPREADSHEET_EXCEL_READER_BIFF8) && ($version != SPREADSHEET_EXCEL_READER_BIFF7)) {
			return -1;
		}

		if ($substreamType != SPREADSHEET_EXCEL_READER_WORKSHEET){
			return -2;
		}
		$spos += $length + 4;
		while($cont) {
			$lowcode = ord($data[$spos]);
			if ($lowcode == SPREADSHEET_EXCEL_READER_TYPE_EOF) break;
			$code = $lowcode | ord($data[$spos+1])<<8;
			$length = ord($data[$spos+2]) | ord($data[$spos+3])<<8;
			$spos += 4;
			$this->sheets[$this->sn]['maxrow'] = $this->_rowoffset - 1;
			$this->sheets[$this->sn]['maxcol'] = $this->_coloffset - 1;
			unset($this->rectype);
			switch ($code) {
				case SPREADSHEET_EXCEL_READER_TYPE_DIMENSION:
					if (!isset($this->numRows)) {
						if (($length == 10) ||  ($version == SPREADSHEET_EXCEL_READER_BIFF7)){
							$this->sheets[$this->sn]['numRows'] = ord($data[$spos+2]) | ord($data[$spos+3]) << 8;
							$this->sheets[$this->sn]['numCols'] = ord($data[$spos+6]) | ord($data[$spos+7]) << 8;
						} else {
							$this->sheets[$this->sn]['numRows'] = ord($data[$spos+4]) | ord($data[$spos+5]) << 8;
							$this->sheets[$this->sn]['numCols'] = ord($data[$spos+10]) | ord($data[$spos+11]) << 8;
						}
					}
					break;
				case SPREADSHEET_EXCEL_READER_TYPE_MERGEDCELLS:
					$cellRanges = ord($data[$spos]) | ord($data[$spos+1])<<8;
					for ($i = 0; $i < $cellRanges; $i++) {
						$fr =  ord($data[$spos + 8*$i + 2]) | ord($data[$spos + 8*$i + 3])<<8;
						$lr =  ord($data[$spos + 8*$i + 4]) | ord($data[$spos + 8*$i + 5])<<8;
						$fc =  ord($data[$spos + 8*$i + 6]) | ord($data[$spos + 8*$i + 7])<<8;
						$lc =  ord($data[$spos + 8*$i + 8]) | ord($data[$spos + 8*$i + 9])<<8;
						if ($lr - $fr > 0) {
							$this->sheets[$this->sn]['cellsInfo'][$fr+1][$fc+1]['rowspan'] = $lr - $fr + 1;
						}
						if ($lc - $fc > 0) {
							$this->sheets[$this->sn]['cellsInfo'][$fr+1][$fc+1]['colspan'] = $lc - $fc + 1;
						}
					}
					break;
				case SPREADSHEET_EXCEL_READER_TYPE_RK:
				case SPREADSHEET_EXCEL_READER_TYPE_RK2:
					$row = ord($data[$spos]) | ord($data[$spos+1])<<8;
					$column = ord($data[$spos+2]) | ord($data[$spos+3])<<8;
					$rknum = $this->_GetInt4d($data, $spos + 6);
					$numValue = $this->_GetIEEE754($rknum);
					$info = $this->_getCellDetails($spos,$numValue,$column);
					$this->addcell($row, $column, $info['string'],$info);
					break;
				case SPREADSHEET_EXCEL_READER_TYPE_LABELSST:
					$row		= ord($data[$spos]) | ord($data[$spos+1])<<8;
					$column	 = ord($data[$spos+2]) | ord($data[$spos+3])<<8;
					$xfindex	= ord($data[$spos+4]) | ord($data[$spos+5])<<8;
					$index  = $this->_GetInt4d($data, $spos + 6);
					$this->addcell($row, $column, $this->sst[$index], array('xfIndex'=>$xfindex) );
					break;
				case SPREADSHEET_EXCEL_READER_TYPE_MULRK:
					$row		= ord($data[$spos]) | ord($data[$spos+1])<<8;
					$colFirst   = ord($data[$spos+2]) | ord($data[$spos+3])<<8;
					$colLast	= ord($data[$spos + $length - 2]) | ord($data[$spos + $length - 1])<<8;
					$columns	= $colLast - $colFirst + 1;
					$tmppos = $spos+4;
					for ($i = 0; $i < $columns; $i++) {
						$numValue = $this->_GetIEEE754($this->_GetInt4d($data, $tmppos + 2));
						$info = $this->_getCellDetails($tmppos-4,$numValue,$colFirst + $i + 1);
						$tmppos += 6;
						$this->addcell($row, $colFirst + $i, $info['string'], $info);
					}
					break;

                case SPREADSHEET_EXCEL_READER_TYPE_STRING:
					// http://code.google.com/p/php-excel-reader/issues/detail?id=4
					if ($version == SPREADSHEET_EXCEL_READER_BIFF8){
						// Unicode 16 string, like an SST record
						$xpos = $spos;
						$numChars =ord($data[$xpos]) | (ord($data[$xpos+1]) << 8);
						$xpos += 2;
						$optionFlags =ord($data[$xpos]);
						$xpos++;
						$asciiEncoding = (($optionFlags &0x01) == 0) ;
						$extendedString = (($optionFlags & 0x04) != 0);
                        // See if string contains formatting information
						$richString = (($optionFlags & 0x08) != 0);
						if ($richString) {
							// Read in the crun
							$formattingRuns =ord($data[$xpos]) | (ord($data[$xpos+1]) << 8);
							$xpos += 2;
						}
						if ($extendedString) {
							// Read in cchExtRst
							$extendedRunLength =$this->_GetInt4d($this->data, $xpos);
							$xpos += 4;
						}
						$len = ($asciiEncoding)?$numChars : $numChars*2;
						$retstr =substr($data, $xpos, $len);
						$xpos += $len;
						$retstr = ($asciiEncoding)? $retstr : $this->_encodeUTF16($retstr);
					}
					elseif ($version == SPREADSHEET_EXCEL_READER_BIFF7){
						// Simple byte string
						$xpos = $spos;
						$numChars =ord($data[$xpos]) | (ord($data[$xpos+1]) << 8);
						$xpos += 2;
						$retstr =substr($data, $xpos, $numChars);
					}
					$this->addcell($previousRow, $previousCol, $retstr);
					break;
				case SPREADSHEET_EXCEL_READER_TYPE_ROW:
					$row	= ord($data[$spos]) | ord($data[$spos+1])<<8;
					$rowInfo = ord($data[$spos + 6]) | ((ord($data[$spos+7]) << 8) & 0x7FFF);
					if (($rowInfo & 0x8000) > 0) {
						$rowHeight = -1;
					} else {
						$rowHeight = $rowInfo & 0x7FFF;
					}
					$rowHidden = (ord($data[$spos + 12]) & 0x20) >> 5;
					$this->rowInfo[$this->sn][$row+1] = Array('height' => $rowHeight / 20, 'hidden'=>$rowHidden );
					break;
				case SPREADSHEET_EXCEL_READER_TYPE_DBCELL:
					break;
				case SPREADSHEET_EXCEL_READER_TYPE_MULBLANK:
					$row = ord($data[$spos]) | ord($data[$spos+1])<<8;
					$column = ord($data[$spos+2]) | ord($data[$spos+3])<<8;
					$cols = ($length / 2) - 3;
					for ($c = 0; $c < $cols; $c++) {
						$xfindex = ord($data[$spos + 4 + ($c * 2)]) | ord($data[$spos + 5 + ($c * 2)])<<8;
						$this->addcell($row, $column + $c, "", array('xfIndex'=>$xfindex));
					}
					break;
				case SPREADSHEET_EXCEL_READER_TYPE_EOF:
					$cont = false;
					break;

				case SPREADSHEET_EXCEL_READER_TYPE_DEFCOLWIDTH:
					$this->defaultColWidth  = ord($data[$spos+4]) | ord($data[$spos+5]) << 8; 
					break;
				case SPREADSHEET_EXCEL_READER_TYPE_STANDARDWIDTH:
					$this->standardColWidth  = ord($data[$spos+4]) | ord($data[$spos+5]) << 8; 
					break;

				default:
					break;
			}
			$spos += $length;
		}

		if (!isset($this->sheets[$this->sn]['numRows']))
			 $this->sheets[$this->sn]['numRows'] = $this->sheets[$this->sn]['maxrow'];
		if (!isset($this->sheets[$this->sn]['numCols']))
			 $this->sheets[$this->sn]['numCols'] = $this->sheets[$this->sn]['maxcol'];
		}

		function isDate($spos) {
			$xfindex = ord($this->data[$spos+4]) | ord($this->data[$spos+5]) << 8;
			return ($this->xfRecords[$xfindex]['type'] == 'date');
		}

		// Get the details for a particular cell
		function _getCellDetails($spos,$numValue,$column) {
			$xfindex = ord($this->data[$spos+4]) | ord($this->data[$spos+5]) << 8;
			$xfrecord = $this->xfRecords[$xfindex];
			$type = $xfrecord['type'];

			$format = $xfrecord['format'];
			$formatIndex = $xfrecord['formatIndex'];
			$fontIndex = $xfrecord['fontIndex'];
			$formatColor = "";
			$rectype = '';
			$string = '';
			$raw = '';

			if (isset($this->_columnsFormat[$column + 1])){
				$format = $this->_columnsFormat[$column + 1];
			}
			if ($type == 'number') {
				$rectype = 'number';
				$formatted = $this->_format_value($format, $numValue, $formatIndex);
				$string = $formatted['string'];
				$raw = $numValue;
			} else{
				if ($format=="") {
					$format = $this->_defaultFormat;
				}
				$rectype = 'unknown';
				$formatted = $this->_format_value($format, $numValue, $formatIndex);
				$string = $formatted['string'];
				$raw = $numValue;
			}

			return array(
				'string'=>$string,
				'raw'=>$raw,
				'rectype'=>$rectype,
				'format'=>$format,
				'formatIndex'=>$formatIndex,
				'fontIndex'=>$fontIndex,
				'formatColor'=>$formatColor,
				'xfIndex'=>$xfindex
			);

		}



	function addcell($row, $col, $string, $info=null) {
		$this->sheets[$this->sn]['maxrow'] = max($this->sheets[$this->sn]['maxrow'], $row + $this->_rowoffset);
		$this->sheets[$this->sn]['maxcol'] = max($this->sheets[$this->sn]['maxcol'], $col + $this->_coloffset);
		$this->sheets[$this->sn]['cells'][$row + $this->_rowoffset][$col + $this->_coloffset] = $string;
	}


	function _GetIEEE754($rknum) {
		if (($rknum & 0x02) != 0) {
				$value = $rknum >> 2;
		} else {
			$exp = ($rknum & 0x7ff00000) >> 20;
			$mantissa = (0x100000 | ($rknum & 0x000ffffc));
			$value = $mantissa / pow( 2 , (20- ($exp - 1023)));

		}
		if (($rknum & 0x01) != 0) {
			$value /= 100;
		}
		return $value;
	}

	function _encodeUTF16($string) {
		$result = $string;
		return $result;
	}

	function _GetInt4d($data, $pos) {
		$value = ord($data[$pos]) | (ord($data[$pos+1]) << 8) | (ord($data[$pos+2]) << 16) | (ord($data[$pos+3]) << 24);
		if ($value>=4294967294) {
			$value=-2;
		}
		return $value;
	}

}
?>
