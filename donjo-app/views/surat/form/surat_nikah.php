<script language="javascript" type="text/javascript">
	function calon_wanita_asal(asal){
		$('#calon_wanita').val(asal);
		if(asal == 1){
			$('.wanita_desa').show();
			$('.wanita_luar_desa').hide();
			// Mungkin bug di jquery? Terpaksa hapus class radio button
			$('#label_calon_wanita_2').removeClass('ui-state-active');
		} else {
			$('.wanita_desa').hide();
			$('.wanita_luar_desa').show();
			$('#id_wanita_copy').val('');
			$('#'+'main').submit();
		}
	}
	function calon_pria(asal){
		$('#calon_pria').val(asal);
		if(asal == 1){
			$('.pria_desa').show();
			$('.pria_luar_desa').hide();
		} else {
			$('.pria_desa').hide();
			$('.pria_luar_desa').show();
			$('#id_wanita_copy').val($('#id_wanita_hidden').val());
			$('#'+'main').submit();
		}
	}
	function nomor_surat(nomor){
		$('#nomor').val(nomor);
		$('#nomor_main').val(nomor);
	}

$(function(){
var pria = {};
pria.results = [
<?php foreach($laki as $data){?>
{id:'<?php echo $data['id']?>',name:"<?php echo $data['nik']." - ".($data['nama'])?>",info:"<?php echo ($data['alamat'])?>"},
<?php }?>
];

$('#id_pria').flexbox(pria, {
resultTemplate: '<div><label>No nik : </label>{name}</div><div>{info}</div>',
watermark: <?php if($pria){?>'<?php echo $pria['nik']?> - <?php echo spaceunpenetration($pria['nama'])?>'<?php }else{?>'Ketik no nik di sini..'<?php }?>,
width: 260,
noResultsText :'Tidak ada no nik yang sesuai..',
onSelect: function() {
	$('#id_wanita_copy').val($('#id_wanita_hidden').val());
	$('#'+'main').submit();
}
});

var wanita = {};
wanita.results = [
<?php foreach($perempuan as $data){?>
{id:'<?php echo $data['id']?>',name:"<?php echo $data['nik']." - ".($data['nama'])?>",info:"<?php echo ($data['alamat'])?>"},
<?php }?>
];

$('#id_wanita').flexbox(wanita, {
resultTemplate: '<div><label>No nik : </label>{name}</div><div>{info}</div>',
watermark: <?php if($wanita){?>'<?php echo $wanita['nik']?> - <?php echo spaceunpenetration($wanita['nama'])?>'<?php }else{?>'Ketik no nik di sini..'<?php }?>,
width: 260,
noResultsText :'Tidak ada no nik yang sesuai..',
onSelect: function() {
	$('#id_wanita_copy').val($('#id_wanita_hidden').val());
	// $('#'+'main').submit();
	$('#id_wanita_validasi').val($('#id_wanita_hidden').val());
	$('#'+'validasi').attr('action','')
	$('#'+'validasi').attr('target','')
	$('#'+'validasi').submit();
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
table.form th.indent{
	padding-left: 30px;
}
table.form th.konfirmasi{
	padding-left: 30px;
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

			<?php $jenis_pasangan = "Istri"; ?>
			<tr>
				<th class="grey">CALON PASANGAN PRIA</th>
			  <td class="grey">
			    <div class="uiradio">
			      <input type="radio" id="calon_pria_1" name="calon_pria" value="1" <?php if(!empty($pria)){echo 'checked';}?> onchange="calon_pria(this.value);">
			      <label for="calon_pria_1">Warga Desa</label>
			      <input type="radio" id="calon_pria_2" name="calon_pria" value="2" <?php if(empty($pria)){echo 'checked';}?> onchange="calon_pria(this.value);">
			      <label for="calon_pria_2">Warga Luar Desa</label>
			    </div>
			  </td>
			</tr>

			<tr class="pria_desa" <?php if (empty($pria)) echo 'style="display: none;"'; ?>>
				<th colspan="2">DATA CALON PASANGAN PRIA WARGA DESA</th>
			</tr>
			<tr class="pria_desa" <?php if (empty($pria)) echo 'style="display: none;"'; ?>>
				<th class="indent">NIK / Nama</th>
				<td>
					<form action="" id="main" name="main" method="POST">
						<input id="nomor_main" name="nomor_main" type="hidden" value="<?php echo $nomor; ?>"/>
						<input id="calon_pria" name="calon_pria" type="hidden" value=""/>
						<div id="id_pria" name="id_pria"></div>
						<input id="calon_wanita" name="calon_wanita" type="hidden" value=""/>
						<!-- Diisi oleh script flexbox wanita -->
						<input id="id_wanita_copy" name="id_wanita" type="hidden" value="kosong"/>
					</form>
					<?php if($pria){ //bagian info setelah terpilih
							$individu = $pria;
						  include("donjo-app/views/surat/form/konfirmasi_pemohon.php");
					}?>
				</td>
			</tr>

			<form id="validasi" action="<?php echo $form_action?>" method="POST" target="_blank">
				<input id="nomor" name="nomor" type="hidden" value=""/>
				<input type="hidden" name="id_pria" value="<?php echo $pria['id']?>">
				<input id="id_wanita_validasi" name="id_wanita" type="hidden" value="kosong"/>

				<?php if (empty($pria)) : ?>
					<tr class="pria_luar_desa">
						<th colspan="2">DATA CALON PASANGAN PRIA LUAR DESA</th>
					</tr>
					<tr class="pria_luar_desa">
						<th class="indent">Nama Lengkap</th>
						<td>
							<input name="nama_pria" type="text" class="inputbox" size="30" value="<?php echo $_SESSION['post']['nama_pria']?>"/>
						</td>
					</tr>
					<tr class="pria_luar_desa">
						<th class="indent">Tempat Tanggal Lahir</th>
						<td>
							<input name="tempatlahir_pria" type="text" class="inputbox" size="30"/>
							<input name="tanggallahir_pria" type="text" class="inputbox datepicker" size="20"/>
						</td>
					</tr>
					<tr class="pria_luar_desa">
						<th class="indent">Warganegara</th>
						<td colspan="5">
					    <select name="wn_pria">
					      <option value="">Pilih warganegara</option>
					      <?php foreach($warganegara as $data){?>
					        <option value="<?php echo $data['nama']?>"><?php echo strtoupper($data['nama'])?></option>
					      <?php }?>
						  </select>
							<span class="judul_tengah">Agama</span>
					    <select name="agama_pria">
					      <option value="">Pilih Agama</option>
					      <?php foreach($agama as $data){?>
					        <option value="<?php echo $data['nama']?>"><?php echo strtoupper($data['nama'])?></option>
					      <?php }?>
					    </select>
							<span class="judul_tengah">Pekerjaan</span>
					    <select name="pekerjaan_pria">
					      <option value="">Pilih Pekerjaan</option>
					      <?php  foreach($pekerjaan as $data){?>
					        <option value="<?php echo $data['nama']?>"><?php echo strtoupper($data['nama'])?></option>
					      <?php }?>
					    </select>
						</td>
					</tr>
					<tr class="pria_luar_desa">
						<th class="indent">Tempat Tinggal</th>
						<td>
							<input name="alamat_pria" type="text" class="inputbox" size="40"/>
						</td>
					</tr>
					<tr class="pria_luar_desa">
						<th class="indent">Jika pria, terangkan jejaka, duda atau beristri</th>
						<td>
					    <select name="status_kawin_pria">
					      <option value="">Pilih Status Kawin</option>
					      <?php  foreach($kode['status_kawin_pria'] as $data){?>
					        <option value="<?php echo $data?>"><?php echo ucwords($data)?></option>
					      <?php }?>
					    </select>
						</td>
					</tr>
					<tr class="pria_luar_desa">
						<th class="indent">Jika beristri, berapa istrinya</th>
						<td>
							<input name="jumlah_istri" type="text" class="inputbox " size="10" value=""/>
						</td>
					</tr>
				<?php endif; ?>

				<?php if($pria) : ?>
					<?php if($pria['sex_id']==1) : ?>
						<tr>
							<th class="indent">Jika pria, terangkan jejaka, duda atau beristri</th>
							<td>
						    <select name="status_kawin_pria">
						      <option value="">Pilih Status Kawin</option>
						      <?php  foreach($kode['status_kawin_pria'] as $data){?>
						        <option value="<?php echo $data?>" <?php if($pria['status_kawin_pria']==$data) echo 'selected';?>><?php echo ucwords($data)?></option>
						      <?php }?>
						    </select>
								<span>(Status kawin: <?php echo $pria['status_kawin']?>)</span>
							</td>
						</tr>
						<?php if($pria['status_kawin']=="KAWIN") : ?>
							<tr>
								<th class="indent">Jika beristri, berapa istrinya</th>
								<td>
									<input name="jumlah_istri" type="text" class="inputbox " size="10" value="1"/>
								</td>
							</tr>
						<?php else:?>
							<input name="jumlah_istri" type="hidden" value=""/>
						<?php endif; ?>
					<?php endif; ?>
				<?php endif; ?>
				<?php if ($ayah_pria) : ?>
					<tr>
						<th colspan="2">DATA AYAH PASANGAN PRIA</th>
					</tr>
					<tr>
						<th class="indent">Nama</th>
						<td><?php echo $ayah_pria['nama']?></td>
					</tr>
					<tr>
						<th class="indent">Tempat Tanggal Lahir</th>
						<td>
							<?php echo $ayah_pria['tempatlahir']." / ".tgl_indo_out($ayah['tanggallahir'])?>
						</td>
					</tr>
					<tr>
						<th class="indent">Warganegara</th>
						<td>
							<?php echo $ayah_pria['wn']?>
							<span class="judul_tengah">Agama : </span>
							<?php echo $ayah_pria['agama']?>
							<span class="judul_tengah">Pekerjaan : </span>
							<?php echo $ayah_pria['pek']?>
						</td>
					</tr>
					<tr>
						<th class="indent">Tempat Tinggal</th>
						<td><?php echo $ayah_pria['alamat_wilayah']?></td>
					</tr>
				<?php else: ?>
					<tr>
						<th colspan="2">DATA AYAH PASANGAN PRIA (Isi jika ayah bukan warga <?php echo strtolower(config_item('sebutan_desa'))?> ini)</th>
					</tr>
					<tr>
						<th class="indent">Nama</th>
						<td><input name="nama_ayah_pria" type="text" class="inputbox " size="30"/></td>
					</tr>
					<tr>
						<th class="indent">Tempat Tanggal Lahir</th>
						<td>
							<input name="tempatlahir_ayah_pria" type="text" class="inputbox " size="30"/>
							<input name="tanggallahir_ayah_pria" type="text" class="inputbox  datepicker" size="20"/>
						</td>
					</tr>
					<tr>
						<th class="indent">Warganegara</th>
						<td colspan="5">
					    <select name="wn_ayah_pria">
					      <option value="">Pilih warganegara</option>
					      <?php foreach($warganegara as $data){?>
					        <option value="<?php echo $data['nama']?>"><?php echo strtoupper($data['nama'])?></option>
					      <?php }?>
						  </select>
							<span class="judul_tengah">Agama</span>
					    <select name="agama_ayah_pria">
					      <option value="">Pilih Agama</option>
					      <?php foreach($agama as $data){?>
					        <option value="<?php echo $data['nama']?>"><?php echo strtoupper($data['nama'])?></option>
					      <?php }?>
					    </select>
							<span class="judul_tengah">Pekerjaan</span>
					    <select name="pekerjaan_ayah_pria">
					      <option value="">Pilih Pekerjaan</option>
					      <?php  foreach($pekerjaan as $data){?>
					        <option value="<?php echo $data['nama']?>"><?php echo strtoupper($data['nama'])?></option>
					      <?php }?>
					    </select>
						</td>
					</tr>
					<tr>
						<th class="indent">Tempat Tinggal</th>
						<td><input name="alamat_ayah_pria" type="text" class="inputbox " size="80"/></td>
					</tr>
				<?php endif; ?>

				<?php if ($ibu_pria) : ?>
					<tr>
						<th colspan="2">DATA IBU PASANGAN PRIA</th>
					</tr>
					<tr>
						<th class="indent">Nama</th>
						<td><?php echo $ibu_pria['nama']?></td>
					</tr>
					<tr>
						<th class="indent">Tempat Tanggal Lahir</th>
						<td>
							<?php echo $ibu_pria['tempatlahir']." / ".tgl_indo_out($ibu['tanggallahir'])?>
						</td>
					</tr>
					<tr>
						<th class="indent">Warganegara</th>
						<td>
							<?php echo $ibu_pria['wn']?>
							<span class="judul_tengah">Agama : </span>
							<?php echo $ibu_pria['agama']?>
							<span class="judul_tengah">Pekerjaan : </span>
							<?php echo $ibu_pria['pek']?>
						</td>
					</tr>
					<tr>
						<th class="indent">Tempat Tinggal</th>
						<td><?php echo $ibu_pria['alamat_wilayah']?></td>
					</tr>
				<?php else: ?>
					<tr>
						<th colspan="2">DATA IBU PASANGAN PRIA (Isi jika ibu bukan warga <?php echo strtolower(config_item('sebutan_desa'))?> ini)</th>
					</tr>
					<tr>
						<th class="indent">Nama</th>
						<td><input name="nama_ibu_pria" type="text" class="inputbox " size="30"/></td>
					</tr>
					<tr>
						<th class="indent">Tempat Tanggal Lahir</th>
						<td>
							<input name="tempatlahir_ibu_pria" type="text" class="inputbox " size="30"/>
							<input name="tanggallahir_ibu_pria" type="text" class="inputbox  datepicker" size="20"/>
						</td>
					</tr>
					<tr>
						<th class="indent">Warganegara</th>
						<td colspan="5">
					    <select name="wn_ibu_pria">
					      <option value="">Pilih warganegara</option>
					      <?php foreach($warganegara as $data){?>
					        <option value="<?php echo $data['nama']?>"><?php echo strtoupper($data['nama'])?></option>
					      <?php }?>
						  </select>
							<span class="judul_tengah">Agama</span>
					    <select name="agama_ibu_pria">
					      <option value="">Pilih Agama</option>
					      <?php foreach($agama as $data){?>
					        <option value="<?php echo $data['nama']?>"><?php echo strtoupper($data['nama'])?></option>
					      <?php }?>
					    </select>
							<span class="judul_tengah">Pekerjaan</span>
					    <select name="pekerjaan_ibu_pria">
					      <option value="">Pilih Pekerjaan</option>
					      <?php  foreach($pekerjaan as $data){?>
					        <option value="<?php echo $data['nama']?>"><?php echo strtoupper($data['nama'])?></option>
					      <?php }?>
					    </select>
						</td>
					</tr>
					<tr>
						<th class="indent">Tempat Tinggal</th>
						<td><input name="alamat_ibu_pria" type="text" class="inputbox " size="80"/></td>
					</tr>
				<?php endif; ?>

				<?php if(empty($pria) OR $pria['status_kawin']=="CERAI MATI") : ?>
					<tr>
						<th colspan="2">DATA ISTRI TERDAHULU </th>
					</tr>
					<tr>
						<th class="indent">Nama <?php echo ucwords($jenis_pasangan)?> Terdahulu</th>
						<td>
							<input name="istri_dulu" type="text" class="inputbox " size="40"/>
							<span class="judul_tengah">Binti :</span>
							<input name="binti" type="text" class="inputbox " size="40"/>
						</td>
					</tr>
					<tr>
						<th class="indent">Tempat Tanggal Lahir</th>
						<td>
							<input name="tmptlahir_istri_dulu" type="text" class="inputbox " size="30"/>
							<input name="tgllahir_istri_dulu" type="text" class="inputbox  datepicker" size="20"/>
						</td>
					</tr>
					<tr>
						<th class="indent">Warganegara</th>
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
						<th class="indent">Tempat Tinggal</th>
						<td><input name="alamat_istri_dulu" type="text" class="inputbox " size="80"/></td>
					</tr>
					<tr>
						<th class="indent">Keterangan <?php echo ucwords($jenis_pasangan)?> Dulu</th>
						<td><input name="ket_istri_dulu" type="text" class="inputbox " size="80"/></td>
					</tr>
				<?php endif; ?>

				<!-- CALON PASANGAN WANITA -->
				<tr>
					<th class="grey">CALON PASANGAN WANITA</th>
				  <td class="grey">
				    <div class="uiradio">
				      <input type="radio" id="calon_wanita_1" name="calon_wanita" value="1" <?php if(!empty($wanita)){echo 'checked';}?> onchange="calon_wanita_asal(this.value);">
				      <label for="calon_wanita_1">Warga Desa</label>
				      <input type="radio" id="calon_wanita_2" name="calon_wanita" value="2" <?php if(empty($wanita)){echo 'checked';}?> onchange="calon_wanita_asal(this.value);"">
				      <label id="label_calon_wanita_2" for="calon_wanita_2">Warga Luar Desa</label>
				    </div>
				  </td>
				</tr>

				<tr class="wanita_desa" <?php if (empty($wanita)) echo 'style="display: none;"'; ?>>
					<th colspan="2">DATA CALON PASANGAN WANITA WARGA DESA</th>
				</tr>
				<tr class="wanita_desa" <?php if (empty($wanita)) echo 'style="display: none;"'; ?>>
					<th class="indent">NIK / Nama</th>
					<td>
						<div id="id_wanita" name="id_wanita"></div>
						<?php if($wanita){ //bagian info setelah terpilih
								$individu = $wanita;
							  include("donjo-app/views/surat/form/konfirmasi_pemohon.php");
						}?>
					</td>
				</tr>

				<?php if (empty($wanita)) : ?>
					<tr class="wanita_luar_desa">
						<th colspan="2">DATA CALON PASANGAN WANITA LUAR DESA</th>
					</tr>
					<tr class="wanita_luar_desa">
						<th class="indent">Nama Lengkap</th>
						<td>
							<input name="nama_wanita" type="text" class="inputbox" size="30"/>
						</td>
					</tr>
					<tr class="wanita_luar_desa">
						<th class="indent">Tempat Tanggal Lahir</th>
						<td>
							<input name="tempatlahir_wanita" type="text" class="inputbox" size="30"/>
							<input name="tanggallahir_wanita" type="text" class="inputbox datepicker" size="20"/>
						</td>
					</tr>
					<tr class="wanita_luar_desa">
						<th class="indent">Warganegara</th>
						<td colspan="5">
					    <select name="wn_wanita">
					      <option value="">Pilih warganegara</option>
					      <?php foreach($warganegara as $data){?>
					        <option value="<?php echo $data['nama']?>"><?php echo strtoupper($data['nama'])?></option>
					      <?php }?>
						  </select>
							<span class="judul_tengah">Agama</span>
					    <select name="agama_wanita">
					      <option value="">Pilih Agama</option>
					      <?php foreach($agama as $data){?>
					        <option value="<?php echo $data['nama']?>"><?php echo strtoupper($data['nama'])?></option>
					      <?php }?>
					    </select>
							<span class="judul_tengah">Pekerjaan</span>
					    <select name="pekerjaan_wanita">
					      <option value="">Pilih Pekerjaan</option>
					      <?php  foreach($pekerjaan as $data){?>
					        <option value="<?php echo $data['nama']?>"><?php echo strtoupper($data['nama'])?></option>
					      <?php }?>
					    </select>
						</td>
					</tr>
					<tr class="wanita_luar_desa">
						<th class="indent">Tempat Tinggal</th>
						<td>
							<input name="alamat_wanita" type="text" class="inputbox" size="40"/>
						</td>
					</tr>
					<tr class="wanita_luar_desa">
						<th class="indent">Jika pria, terangkan jejaka, duda atau beristri</th>
						<td>
							<input name="status_kawin_wanita" type="text" class="inputbox " size="40"/>
						</td>
					</tr>
				<?php endif; ?>

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
								<option <?php if($data['jabatan']==$_SESSION['post']['jabatan']) echo 'selected'?>><?php echo unpenetration($data['jabatan'])?></option>
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
