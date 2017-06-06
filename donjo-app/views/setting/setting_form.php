<div id="pageC">
	<table class="inner">
	<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;">
<div class="content-header">
</div>
<div id="contentpane">
<div class="ui-layout-north panel"><h3>Setting Aplikasi</h3>
</div>
    <form action="<?php echo site_url('setting/update')?>" method="POST">
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
        <table class="list">
            <tr>
                <th width="150px">Setting</th>
                <th>Nilai Setting</th>
            </tr>
            <?php foreach($this->setting as $key => $value) : ?>
                <tr>
                    <td><strong><?php echo $key?></strong></th>
                    <td><input name="<?php echo $key?>" value="<?php echo $value?>"></td>
                </tr>
            <?php endforeach; ?>

        </table>
    </div>

    <div class="ui-layout-south panel bottom">
        <div class="left">
        </div>
        <div class="right">
            <div class="uibutton-group">
                <button class="uibutton" type="reset">Clear</button>
                <button class="uibutton confirm" type="submit">Simpan</button>
            </div>
        </div>
    </div>
    </form>
</div>
</td></tr></table>
</div>
