<script>
$(function(){
var nik = {};
nik.results = [
<?php foreach($penduduk as $data){?>
{id:'<?php echo $data['id']?>',name:"<?php echo $data['nik']." - ".($data['nama'])?>",info:"<?php echo ($data['alamat'])?>"},
<?php }?>
];

$('#nik').flexbox(nik, {
resultTemplate: '<div><label>No nik : </label>{name}</div><div>{info}</div>',
watermark: <?php if($individu){?>'<?php echo $individu['nik']?> - <?php echo spaceunpenetration($individu['nama'])?>'<?php }else{?>'Ketik no nik di sini..'<?php }?>,
width: 260,
noResultsText :'Tidak ada no nik yang sesuai..',
onSelect: function() {
$('#'+'main').submit();
}
});

});
</script>


<style>
table.form.detail th{
padding:5px;
background:#fafafa;
border-right:1px solid #eee;
}
table.form.detail td{
padding:5px;
}
</style>
<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">

<td style="background:#fff;padding:5px;">
<div class="content-header">

</div>
<div id="contentpane">
<div class="ui-layout-north panel">
<h3>Surat Keterangan Untuk Nikah Pria</h3>
</div>

<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">
<tr>
<th>NIK / Nama</th>
<td>
<form action="" id="main" name="main" method="POST">
<div id="nik" name="nik"></div>
</form>
</tr>

<form id="validasi" action="<?php echo $form_action?>" method="POST" target="_blank">
<input type="hidden" name="nik" value="<?php echo $individu['id']?>">
<?php if($individu){ //bagian info setelah terpilih?>
  <?php include("donjo-app/views/surat/form/konfirmasi_pemohon.php"); ?>
<?php }?>
<tr>
<th>Jika pria, terangkan jejaka, duda atau beristri dan berapa istrinya</th>
<td>
<input name="jaka" type="text" class="inputbox " size="40"/>
</td>
</tr>
<tr>
<th>Nomor Surat</th>
<td>
<input name="nomor" type="text" class="inputbox required" size="12"/> <span>Terakhir: <?php echo $surat_terakhir['no_surat'];?> (tgl: <?php echo $surat_terakhir['tanggal']?>)</span>
</td>
</tr>
<tr>
</div>
<div id="contentpane">
<div class="ui-layout-north panel">
<th>DATA AYAH (Isi jika ayah bukan warga <?php echo strtolower(config_item('sebutan_desa'))?> ini)</th>
<table class="form">
<tr>
<th>Nama</th>
<td><input name="nama_ayah" type="text" class="inputbox " size="30"/></td>
</tr>
<tr>
<th>Tempat Tanggal Lahir</th>
<td><input name="tempatlahir_ayah" type="text" class="inputbox " size="30"/>
<input name="tanggallahir_ayah" type="text" class="inputbox  datepicker" size="20"/></td>
</tr>
<tr>
	<th>Warganegara</th>
	<td>
    <select name="wn_ayah">
      <option value="">Pilih warganegara</option>
      <?php foreach($warganegara as $data){?>
        <option value="<?php echo $data['nama']?>"><?php echo strtoupper($data['nama'])?></option>
      <?php }?>
	  </select>
	</td>
	<th>Agama</th>
	<td><input name="agama_ayah" type="text" class="inputbox " size="15"/></td>
	<th>Pekerjaan</th>
	<td><input name="pekerjaan_ayah" type="text" class="inputbox " size="30"/></td>
</tr>
<tr>
<th>Tempat Tinggal</th>
<td><input name="alamat_ayah" type="text" class="inputbox " size="80"/></td>
</div>
</tr>
<tr>
</div>
<th>DATA IBU (Isi jika ibu bukan warga <?php echo strtolower(config_item('sebutan_desa'))?> ini)</th>
</tr>
<tr>
<tr>
<th>Nama</th>
<td><input name="nama_ibu" type="text" class="inputbox " size="30"/></td>
</tr>
<tr>
<th>Tempat Tanggal Lahir</th>
<td><input name="tempatlahir_ibu" type="text" class="inputbox " size="30"/>
<input name="tanggallahir_ibu" type="text" class="inputbox  datepicker" size="20"/></td>
</tr>
<tr>
	<th>Warganegara</th>
	<td>
    <select name="wn_ibu">
      <option value="">Pilih warganegara</option>
      <?php foreach($warganegara as $data){?>
        <option value="<?php echo $data['nama']?>"><?php echo strtoupper($data['nama'])?></option>
      <?php }?>
	  </select>
	</td>
	<th>Agama</th>
	<td><input name="agama_ibu" type="text" class="inputbox " size="15"/></td>
	<th>Pekerjaan</th>
	<td><input name="pekerjaan_ibu" type="text" class="inputbox " size="30"/></td>
</tr>
<tr>
<th>Tempat Tinggal</th>
<td><input name="alamat_ibu" type="text" class="inputbox " size="80"/></td>
</tr>
<tr>
<div id="contentpane">
<div class="ui-layout-north panel">
<th>DATA CALON PASANGAN</th>
<td></td>
</tr>
<tr>
</div>
<div class="ui-layout-center" id="maincontent" style="padding: 15px;">
<table class="form">
<tr>
<th>Nama Lengkap</th>
<td>
<input name="nama_pasangan" type="text" class="inputbox required" size="30"/>
</td>
</tr>
<tr>
<th>Tempat Tanggal Lahir</th>
<td>
<input name="tempatlahir_pasangan" type="text" class="inputbox required" size="30"/>
<input name="tanggallahir_pasangan" type="text" class="inputbox required datepicker" size="20"/>
</td>
</tr><tr>
<th>Binti</th>
<td><input name="ayah_pasangan" type="text" class="inputbox required" size="15"/>
</td>
</tr>
<tr>
	<th>Warganegara</th>
	<td>
    <select name="wn_pasangan">
      <option value="">Pilih warganegara</option>
      <?php foreach($warganegara as $data){?>
        <option value="<?php echo $data['nama']?>"><?php echo strtoupper($data['nama'])?></option>
      <?php }?>
	  </select>
	</td>
	<th>Agama</th>
	<td><input name="agama_pasangan" type="text" class="inputbox required" size="15"/>
	<th>Pekerjaan</th>
	<td><input name="pekerjaan_pasangan" type="text" class="inputbox required" size="15"/>
	</td>
</tr>
<tr>
<th>Tempat Tinggal</th>
<td>
<input name="alamat_pasangan" type="text" class="inputbox required" size="40"/>
</td>
</tr>
<tr>
<th>DATA PASANGAN TERDAHULU </th>
<td></td>
</tr>
<tr>
</div>
<div class="ui-layout-center" id="maincontent" style="padding: 10px;">
<table class="form">
<tr>
<th>Nama Istri terdahulu</th>
<td><input name="pasangan_dulu" type="text" class="inputbox " size="40"/></td><th>Binti :</th><td><input name="binti" type="text" class="inputbox " size="40"/></td>
</tr>
<tr>
<th>Tempat Tanggal Lahir</th>
<td><input name="tmptlahir_istri_dulu" type="text" class="inputbox " size="30"/>
<input name="tgllahir_istri_dulu" type="text" class="inputbox  datepicker" size="20"/></td>
</tr>
<tr>
	<th>Warganegara</th>
	<td>
    <select name="wn_istri_dulu">
      <option value="">Pilih warganegara</option>
      <?php foreach($warganegara as $data){?>
        <option value="<?php echo $data['nama']?>"><?php echo strtoupper($data['nama'])?></option>
      <?php }?>
	  </select>
	</td>
	<th>Agama</th>
	<td><input name="agama_istri_dulu" type="text" class="inputbox " size="15"/></td>
	<th>Pekerjaan</th>
	<td><input name="pek_istri_dulu" type="text" class="inputbox " size="30"/></td>
</tr>
<tr>
<th>Tempat Tinggal</th>
<td><input name="alamat_istri_dulu" type="text" class="inputbox " size="80"/></td>
</tr>
<tr>
<th>Keterangan Istri Dulu</th>
<td><input name="ket_istri_dulu" type="text" class="inputbox " size="80"/></td>
</tr>
<tr>

<th>Staf Pemerintah <?php echo ucwords(config_item('sebutan_desa'))?></th>
<td>
<select name="pamong"  class="inputbox required" >
<option value="">Pilih Staf Pemerintah <?php echo ucwords(config_item('sebutan_desa'))?></option>
<?php foreach($pamong AS $data){?>
<option value="<?php echo $data['pamong_nama']?>"><font style="bold"><?php echo unpenetration($data['pamong_nama'])?></font> (<?php echo unpenetration($data['jabatan'])?>)</option>
<?php }?>
</select>
</td>
</tr>
<tr>
<th>Sebagai</th>
<td>
<select name="jabatan"  class="inputbox required">
<option value="">Pilih Jabatan</option>
<?php foreach($pamong AS $data){?>
<option ><?php echo unpenetration($data['jabatan'])?></option>
<?php }?>
</select>
</td>
</tr>
</table>
</div>

<div class="ui-layout-south panel bottom">
<div class="left">
<a href="<?php echo site_url()?>surat" class="uibutton icon prev">Kembali</a>
</div>
<div class="right">
<div class="uibutton-group">
<button class="uibutton" type="reset">Clear</button>

							<button type="button" onclick="$('#'+'validasi').attr('action','<?php echo $form_action?>');$('#'+'validasi').submit();" class="uibutton special"><span class="ui-icon ui-icon-print">&nbsp;</span>Cetak</button>
							<?php if (SuratExport($url)) { ?><button type="button" onclick="$('#'+'validasi').attr('action','<?php echo $form_action2?>');$('#'+'validasi').submit();" class="uibutton confirm"><span class="ui-icon ui-icon-document">&nbsp;</span>Export Doc</button><?php } ?>
</div>
</div>
</div> </form>
</div>
</td></tr></table>
</div>
