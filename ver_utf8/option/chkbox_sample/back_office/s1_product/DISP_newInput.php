
  <!-- 管理画面でチェックボックスを使用する項目の新規追加画面のサンプル
	  configで作った配列を使用し、値は数値で管理して下さい。 -->
<tr>
	<th width="15%" nowrap class="tdcolored">アイコン表示：</th>
	<td class="other-td">
		<?php foreach ($ICON_MST as $v): //from common/config_S1?>
			<label><input type="checkbox" name="icons[]" value="<?php echo $v['id'];?>"><?php echo $v['name'];?></label>
		<?php endforeach;?>
	</td>
</tr>
