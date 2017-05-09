<script>
	function calon_pria(asal){
		if(asal == 1){
			$('#pria_desa').show();
			$('#pria_luar_desa').hide();
		} else {
			$('#pria_desa').hide();
			$('#pria_luar_desa').show();
			$('#'+'main').submit();
		}
	}
	function nomor_surat(nomor){
		$('#nomor').val(nomor);
		$('#nomor_main').val(nomor);
	}

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
span.judul_tengah {
	font-weight: bold;
	padding-left: 10px;
	padding-right: 5px;
}
.grey {
	background-color: lightgrey;
}
</style>
<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">

<td style="background:#fff;padding:5px;">
<div class="content-header"></div>
<div id="contentpane">
	<div class="ui-layout-north panel">
		<h3>Surat Keterangan Untuk Nikah Pria</h3>
	</div>

	<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
		<table class="form">
			<tr>
				<th>Nomor Surat</th>
				<td>
					<input type="text" class="inputbox required" size="12" value="<?php echo $nomor; ?>" onchange="nomor_surat(this.value);"/> <span>Terakhir: <?php echo $surat_terakhir['no_surat'];?> (tgl: <?php echo $surat_terakhir['tanggal']?>)</span>
				</td>
			</tr>
			<tr>
				<th class="grey">CALON PASANGAN PRIA</th>
			  <td class="grey">
			    <div class="uiradio">
			      <input type="radio" id="calon_pria_1" name="calon_pria" value="1" <?php if(!empty($individu)){echo 'checked';}?> onchange="calon_pria(this.value);">
			      <label for="calon_pria_1">Warga Desa</label>
			      <input type="radio" id="calon_pria_2" name="calon_pria" value="2" <?php if(empty($individu)){echo 'checked';}?> onchange="calon_pria(this.value);">
			      <label for="calon_pria_2">Warga Luar Desa</label>
			    </div>
			  </td>
			</tr>
			<tr id="pria_desa" <?php if (empty($individu)) echo 'style="display: none;"'; ?>>
				<th>NIK / Nama</th>
				<td>
					<form action="" id="main" name="main" method="POST">
						<input id="nomor_main" name="nomor_main" type="hidden" value="<?php echo $nomor; ?>"/>
						<div id="nik" name="nik"></div>
					</form>
					<?php if($individu){ //bagian info setelah terpilih?>
						  <?php include("donjo-app/views/surat/form/konfirmasi_pemohon.php"); ?>
					<?php }?>
				</td>
			</tr>

			<form id="validasi" action="<?php echo $form_action?>" method="POST" target="_blank">
				<input id="nomor" name="nomor" type="hidden" value=""/>
				<input type="hidden" name="nik" value="<?php echo $individu['id']?>">

				<?php if (empty($individu)) : ?>
					<tr id="pria_luar_desa">
						<th colspan="2">DATA CALON PASANGAN PRIA LUAR DESA</th>
					</tr>
					<tr>
					<tr>
						<th>Nama Lengkap</th>
						<td>
							<input name="nama_pasangan_pria" type="text" class="inputbox required" size="30"/>
						</td>
					</tr>
					<tr>
						<th>Tempat Tanggal Lahir</th>
						<td>
							<input name="tempatlahir_pasangan_pria" type="text" class="inputbox required" size="30"/>
							<input name="tanggallahir_pasangan_pria" type="text" class="inputbox required datepicker" size="20"/>
						</td>
					</tr>
					<tr>
						<th>Warganegara</th>
						<td colspan="5">
					    <select name="wn_pasangan_pria">
					      <option value="">Pilih warganegara</option>
					      <?php foreach($warganegara as $data){?>
					        <option value="<?php echo $data['nama']?>"><?php echo strtoupper($data['nama'])?></option>
					      <?php }?>
						  </select>
							<span class="judul_tengah">Agama</span>
					    <select name="agama_pasangan_pria">
					      <option value="">Pilih Agama</option>
					      <?php foreach($agama as $data){?>
					        <option value="<?php echo $data['nama']?>"><?php echo strtoupper($data['nama'])?></option>
					      <?php }?>
					    </select>
							<span class="judul_tengah">Pekerjaan</span>
					    <select name="pekerjaan_pasangan_pria">
					      <option value="">Pilih Pekerjaan</option>
					      <?php  foreach($pekerjaan as $data){?>
					        <option value="<?php echo $data['nama']?>"><?php echo strtoupper($data['nama'])?></option>
					      <?php }?>
					    </select>
						</td>
					</tr>
					<tr>
						<th>Tempat Tinggal</th>
						<td>
							<input name="alamat_pasangan_pria" type="text" class="inputbox required" size="40"/>
						</td>
					</tr>
					<tr>
						<th>Jika pria, terangkan jejaka, duda atau beristri</th>
						<td>
							<input name="status_kawin_pria" type="text" class="inputbox " size="40"/>
						</td>
					</tr>
					<tr>
						<th>Jika beristri, berapa istrinya</th>
						<td>
							<input name="jumlah_istri" type="text" class="inputbox " size="10" value=""/>
						</td>
					</tr>
				<?php endif; ?>

				<?php if($individu) : ?>
					<?php if($individu['sex_id']==1) : ?>
						<tr>
							<th>Jika pria, terangkan jejaka, duda atau beristri</th>
							<td>
								<input name="status_kawin_pria" type="text" class="inputbox " size="40" value="<?php echo $individu['status_kawin_pria']?>"/>
								<span>(Status kawin: <?php echo $individu['status_kawin']?>)</span>
							</td>
						</tr>
						<?php if($individu['status_kawin']=="KAWIN") : ?>
							<tr>
								<th>Jika beristri, berapa istrinya</th>
								<td>
									<input name="jumlah_istri" type="text" class="inputbox " size="10" value="1"/>
								</td>
							</tr>
						<?php else:?>
							<input name="jumlah_istri" type="hidden" value=""/>
						<?php endif; ?>
					<?php endif; ?>
				<?php endif; ?>
				<?php if ($ayah) : ?>
					<tr>
						<th colspan="2">DATA AYAH</th>
					</tr>
					<tr>
						<th>Nama</th>
						<td><?php echo $ayah['nama']?></td>
					</tr>
					<tr>
						<th>Tempat Tanggal Lahir</th>
						<td>
							<?php echo $ayah['tempatlahir']." / ".tgl_indo_out($ayah['tanggallahir'])?>
						</td>
					</tr>
					<tr>
						<th>Warganegara</th>
						<td>
							<?php echo $ayah['wn']?>
							<span class="judul_tengah">Agama : </span>
							<?php echo $ayah['agama']?>
							<span class="judul_tengah">Pekerjaan : </span>
							<?php echo $ayah['pek']?>
						</td>
					</tr>
					<tr>
						<th>Tempat Tinggal</th>
						<td><?php echo $ayah['alamat_wilayah']?></td>
					</tr>
				<?php else: ?>
					<tr>
						<th colspan="2">DATA AYAH (Isi jika ayah bukan warga <?php echo strtolower(config_item('sebutan_desa'))?> ini)</th>
					</tr>
					<tr>
						<th>Nama</th>
						<td><input name="nama_ayah" type="text" class="inputbox " size="30"/></td>
					</tr>
					<tr>
						<th>Tempat Tanggal Lahir</th>
						<td>
							<input name="tempatlahir_ayah" type="text" class="inputbox " size="30"/>
							<input name="tanggallahir_ayah" type="text" class="inputbox  datepicker" size="20"/>
						</td>
					</tr>
					<tr>
						<th>Warganegara</th>
						<td colspan="5">
					    <select name="wn_ayah">
					      <option value="">Pilih warganegara</option>
					      <?php foreach($warganegara as $data){?>
					        <option value="<?php echo $data['nama']?>"><?php echo strtoupper($data['nama'])?></option>
					      <?php }?>
						  </select>
							<span class="judul_tengah">Agama</span>
					    <select name="agama_ayah">
					      <option value="">Pilih Agama</option>
					      <?php foreach($agama as $data){?>
					        <option value="<?php echo $data['nama']?>"><?php echo strtoupper($data['nama'])?></option>
					      <?php }?>
					    </select>
							<span class="judul_tengah">Pekerjaan</span>
					    <select name="pekerjaan_ayah">
					      <option value="">Pilih Pekerjaan</option>
					      <?php  foreach($pekerjaan as $data){?>
					        <option value="<?php echo $data['nama']?>"><?php echo strtoupper($data['nama'])?></option>
					      <?php }?>
					    </select>
						</td>
					</tr>
					<tr>
						<th>Tempat Tinggal</th>
						<td><input name="alamat_ayah" type="text" class="inputbox " size="80"/></td>
					</tr>
				<?php endif; ?>

				<?php if ($ibu) : ?>
					<tr>
						<th colspan="2">DATA IBU</th>
					</tr>
					<tr>
						<th>Nama</th>
						<td><?php echo $ibu['nama']?></td>
					</tr>
					<tr>
						<th>Tempat Tanggal Lahir</th>
						<td>
							<?php echo $ibu['tempatlahir']." / ".tgl_indo_out($ibu['tanggallahir'])?>
						</td>
					</tr>
					<tr>
						<th>Warganegara</th>
						<td>
							<?php echo $ibu['wn']?>
							<span class="judul_tengah">Agama : </span>
							<?php echo $ibu['agama']?>
							<span class="judul_tengah">Pekerjaan : </span>
							<?php echo $ibu['pek']?>
						</td>
					</tr>
					<tr>
						<th>Tempat Tinggal</th>
						<td><?php echo $ibu['alamat_wilayah']?></td>
					</tr>
				<?php else: ?>
					<tr>
						<th colspan="2">DATA IBU (Isi jika ibu bukan warga <?php echo strtolower(config_item('sebutan_desa'))?> ini)</th>
					</tr>
					<tr>
						<th>Nama</th>
						<td><input name="nama_ibu" type="text" class="inputbox " size="30"/></td>
					</tr>
					<tr>
						<th>Tempat Tanggal Lahir</th>
						<td>
							<input name="tempatlahir_ibu" type="text" class="inputbox " size="30"/>
							<input name="tanggallahir_ibu" type="text" class="inputbox  datepicker" size="20"/>
						</td>
					</tr>
					<tr>
						<th>Warganegara</th>
						<td colspan="5">
					    <select name="wn_ibu">
					      <option value="">Pilih warganegara</option>
					      <?php foreach($warganegara as $data){?>
					        <option value="<?php echo $data['nama']?>"><?php echo strtoupper($data['nama'])?></option>
					      <?php }?>
						  </select>
							<span class="judul_tengah">Agama</span>
					    <select name="agama_ibu">
					      <option value="">Pilih Agama</option>
					      <?php foreach($agama as $data){?>
					        <option value="<?php echo $data['nama']?>"><?php echo strtoupper($data['nama'])?></option>
					      <?php }?>
					    </select>
							<span class="judul_tengah">Pekerjaan</span>
					    <select name="pekerjaan_ibu">
					      <option value="">Pilih Pekerjaan</option>
					      <?php  foreach($pekerjaan as $data){?>
					        <option value="<?php echo $data['nama']?>"><?php echo strtoupper($data['nama'])?></option>
					      <?php }?>
					    </select>
						</td>
					</tr>
					<tr>
						<th>Tempat Tinggal</th>
						<td><input name="alamat_ibu" type="text" class="inputbox " size="80"/></td>
					</tr>
				<?php endif; ?>

				<tr>
					<th colspan="2">DATA CALON PASANGAN</th>
				</tr>
				<tr>
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
				</tr>
				<tr>
					<th>Binti</th>
					<td><input name="ayah_pasangan" type="text" class="inputbox required" size="15"/></td>
				</tr>
				<tr>
					<th>Warganegara</th>
					<td colspan="5">
				    <select name="wn_pasangan">
				      <option value="">Pilih warganegara</option>
				      <?php foreach($warganegara as $data){?>
				        <option value="<?php echo $data['nama']?>"><?php echo strtoupper($data['nama'])?></option>
				      <?php }?>
					  </select>
						<span class="judul_tengah">Agama</span>
				    <select name="agama_pasangan">
				      <option value="">Pilih Agama</option>
				      <?php foreach($agama as $data){?>
				        <option value="<?php echo $data['nama']?>"><?php echo strtoupper($data['nama'])?></option>
				      <?php }?>
				    </select>
						<span class="judul_tengah">Pekerjaan</span>
				    <select name="pekerjaan_pasangan">
				      <option value="">Pilih Pekerjaan</option>
				      <?php  foreach($pekerjaan as $data){?>
				        <option value="<?php echo $data['nama']?>"><?php echo strtoupper($data['nama'])?></option>
				      <?php }?>
				    </select>
					</td>
				</tr>
				<tr>
					<th>Tempat Tinggal</th>
					<td>
						<input name="alamat_pasangan" type="text" class="inputbox required" size="40"/>
					</td>
				</tr>
				<tr>
					<th colspan="2">DATA PASANGAN TERDAHULU </th>
				</tr>
				<tr>
					<th>Nama <?php echo ucwords($jenis_pasangan)?> Terdahulu</th>
					<td>
						<input name="pasangan_dulu" type="text" class="inputbox " size="40"/>
						<span class="judul_tengah">Binti :</span>
						<input name="binti" type="text" class="inputbox " size="40"/>
					</td>
				</tr>
				<tr>
					<th>Tempat Tanggal Lahir</th>
					<td>
						<input name="tmptlahir_istri_dulu" type="text" class="inputbox " size="30"/>
						<input name="tgllahir_istri_dulu" type="text" class="inputbox  datepicker" size="20"/>
					</td>
				</tr>
				<tr>
					<th>Warganegara</th>
					<td colspan="5">
				    <select name="wn_istri_dulu">
				      <option value="">Pilih warganegara</option>
				      <?php foreach($warganegara as $data){?>
				        <option value="<?php echo $data['nama']?>"><?php echo strtoupper($data['nama'])?></option>
				      <?php }?>
					  </select>
						<span class="judul_tengah">Agama</span>
				    <select name="agama_istri_dulu">
				      <option value="">Pilih Agama</option>
				      <?php foreach($agama as $data){?>
				        <option value="<?php echo $data['nama']?>"><?php echo strtoupper($data['nama'])?></option>
				      <?php }?>
				    </select>
						<span class="judul_tengah">Pekerjaan</span>
				    <select name="pek_istri_dulu">
				      <option value="">Pilih Pekerjaan</option>
				      <?php  foreach($pekerjaan as $data){?>
				        <option value="<?php echo $data['nama']?>"><?php echo strtoupper($data['nama'])?></option>
				      <?php }?>
				    </select>
					</td>
				</tr>
				<tr>
					<th>Tempat Tinggal</th>
					<td><input name="alamat_istri_dulu" type="text" class="inputbox " size="80"/></td>
				</tr>
				<tr>
					<th>Keterangan <?php echo ucwords($jenis_pasangan)?> Dulu</th>
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
			</form>
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
			<?php if (SuratExport($url)) { ?>
				<button type="button" onclick="$('#'+'validasi').attr('action','<?php echo $form_action2?>');$('#'+'validasi').submit();" class="uibutton confirm"><span class="ui-icon ui-icon-document">&nbsp;</span>Export Doc</button>
			<?php } ?>
		</div>
	</div>

</div>
</div>
</td></tr></table>
</div>
