<div class="row">
<div class="col-md-12">
<div class="nav"><?echo $data["nav"];?></div>
<h1><?echo $data["title"];?></h1>
		<img src="<?echo $data["filename"];?>" alt='<?echo $data["title"];?>' title='<?echo $data["title"];?>'>
		
		<form class="form_shop_el">			
			<div class="order">
				<div class="cost-block">
					<span id="price" class="cost"><?echo number_format($data["price"],0,' ',' ');?></span><span class="cost-ruble"> <span class="rur">₽</span>
				</span></div>
				<button class="form-item btn j-minus minus" type="button">-</button>
				<input name="count" type="text" class="form-item j-result result" value="1" pattern="[1-9][0-9]*" min="1" maxlength="2" autocomplete="off" title="Число больше 0">
				<button class="form-item btn j-plus plus" type="button">+</button>				
				<br><br><select name="options[size]" class="input-sm form-control" id="option_size">
					<option value="100">100</option>
					<option value="50">50</option>
				</select><br>
				<div class="additions">
				<?$i=1;
				foreach($data['options'] as $row)
				{?>
				<input class="checkbox" id="checkbox<?echo $row["row_addition_id"];?>" type="checkbox" value="<?echo $row["row_price"];?>" name="options[<?echo $row["row_addition_id"];?>]"><label for="checkbox<?echo $row["row_addition_id"];?>"><?echo $row["row_name"];?> - <?echo $row["row_price"];?> <span class="rur">₽</span></label><br>				
				<?$i++;
				}?>	
				</div>
				<input type="hidden" name="id" value="<? echo $data["id"];?>">
				<br>
				<button type="submit" class="form-item buy" name="addCart" value="cart/add">в корзину</button>
			</div>
		</form>
		
		
		<div style="clear: both;"></div>
		<br>
		<?echo $data["text"];?>
</div>
</div>