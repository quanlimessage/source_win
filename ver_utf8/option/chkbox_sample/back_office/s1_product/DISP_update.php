
	<!-- 管理画面でチェックボックスを使用する項目の既存記事編集画面のサンプル
	configで作った配列を使用し、値は数値で管理して下さい。
	既存記事の編集はcheckedが必要なので毎回for文を使用するのではなく
	前後に区切り文字を追加してstrposで真偽のチェックを行います-->

	<tr>
		<th width="15%" nowrap class="tdcolored">アイコン表示：</th>
		<td class="other-td">
			<?php foreach ($ICON_MST as $v): //from common/config_S1?>
				<label>
				<input type="checkbox" name="icons[]" value="<?php echo $v['id'];?>"<?php echo (strpos(','.$fetch[0]['ICON'].',' , ','.$v['id'].',') !== false)?' checked':'';?>><?php echo $v['name'];?>
				</label>
			<?php endforeach;?>
		</td>
	</tr>
