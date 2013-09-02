<tr id="price-row-<?php echo $tkt_row; ?>-<?php echo $prc_row; ?>" class="ee-active-price">
	<td>
		<?php echo $price_type_selector; ?>
	</td>
	<td>
		<input type="hidden" name="<?php echo $edit_prices_name; ?>[<?php echo $tkt_row; ?>][<?php echo $prc_row; ?>][PRC_ID]" class="edit-price-PRC_ID" value="<?php echo $PRC_ID; ?>">
		<input type="hidden" name="<?php echo $edit_prices_name; ?>[<?php echo $tkt_row; ?>][<?php echo $prc_row; ?>][PRC_is_default]" class="edit-price-PRC_is_default" value="<?php echo $PRC_is_default; ?>">
		<input type="text" class="edit-price-PRC_name ee-text-inp" name="<?php echo $edit_prices_name; ?>[<?php echo $tkt_row; ?>][<?php echo $prc_row; ?>][PRC_name]" value="<?php echo $PRC_name; ?>"></td>
	<td>
		<span class="ticket-price-info-display ticket-price-plus-minus"<?php echo $show_plus_or_minus; ?>>+/-</span>
		<span class="ticket-price-info-display ticket-price-plus"<?php echo $show_plus; ?>>+</span>
		<span class="ticket-price-info-display ticket-price-minus"<?php echo $show_minus; ?>>-</span>
		<span class="ticket-price-info-display ticket-price-dollar-sign-display"<?php echo $show_currency_symbol; ?>><?php echo $price_currency_symbol; ?></span>
	</td>
	<td>
		<input type="text" size="1" class="edit-price-PRC_amount ee-small-text-inp" name="<?php echo $edit_prices_name; ?>[<?php echo $tkt_row; ?>][<?php echo $prc_row; ?>][PRC_amount]" value="<?php echo $PRC_amount; ?>">
		<span class="ticket-price-info-display ticket-price-percentage-char-display"<?php echo $show_percentage; ?>>%</span>
	</td>
	<td><span class="gear-icon clickable" data-ticket-row="<?php echo $tkt_row; ?>" data-context="price" data-price-row="<?php echo $prc_row; ?>"></span><span class="trash-icon clickable" data-ticket-row="<?php echo $tkt_row; ?>" data-context="price" data-price-row="<?php echo $prc_row; ?>"<?php echo $show_trash_icon; ?>></span><button data-ticket-row="<?php echo $tkt_row; ?>" data-price-row="<?php echo $prc_row; ?>" data-context="price" class="ee-create-button"<?php echo $show_create_button; ?>><strong>+</strong></button></td>
</tr>
<tr id="extra-price-row-<?php echo $tkt_row; ?>-<?php echo $prc_row; ?>">
	<td colspan="5">
		<section class="extra-price-row" style="display:none">
			<textarea name="<?php echo $edit_prices_name; ?>[<?php echo $tkt_row; ?>][<?php echo $prc_row; ?>][PRT_description]" class="edit-price-PRC_desc ee-full-textarea-inp" placeholder="Edit the description for the price here"><?php echo $PRC_desc; ?></textarea>
		</section>
	</td>
</tr>
<?php
/**
 * template args in use
 *
 * $tkt_row
 * $prc_row
 * $price_type_selector;
 * $PRC_ID
 * $PRC_is_default
 * $PRC_name
 * $price_currency_symbol
 * $show_plus_or_minus
 * $show_plus
 * $show_minus
 * $show_currency_symbol
 * $PRC_amount
 * $show_percentage
 * $show_trash_icon
 * $show_create_button
 * $PRC_desc
 */