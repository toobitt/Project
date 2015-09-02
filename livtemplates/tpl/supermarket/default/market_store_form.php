{code}
    $formdata = $formdata[0];
{/code}
{foreach $formdata as $key=>$vv}
    {code}
        $$key = $vv;
    {/code}
{/foreach}
<div class="editor-middle">
		      <form method="post" action="run.php?mid={$_INPUT['mid']}&market_id={$_INPUT['market_id']}" name="vod_sort_listform" class="market-form">
					<aside class="market-edit">
						<div class="market-item market-mode">
							<label>编辑门店</label>
							<input name="name" type="text" value="{$name}"/>
							<div class="market-figure">
							{if $logo}
								<span>
									<img src="{$logo}"/>
								</span>
							{/if}	
							</div>
						</div>
						<div class="market-item">
							<label>地址：</label>
							<input name="address" type="text" value="{$address}"/>
						</div>
						<div class="market-item">
							<label>时间：</label>
							<input name="opening_time" type="text" value="{$opening_time}"/>
						</div>
						<div class="market-item market-tel">
							<label>电话：</label>
							<input name="tel" type="text" value="{$tel}"/><em></em>
						</div>
						<div class="market-item">
							<label>车位：</label>
							<input name="parking_num" type="text" value="{$parking_num}"/>
						</div>
						<div class="market-item">
							<label>简介：</label>
							<textarea name='brief' cols="120" rows="4" placeholder="商家超市简介">{$brief}
							</textarea>
						</div>
						<div class="market-item">
							<label>交通：</label>
							<textarea name='traffic' cols="120" rows="4" placeholder="交通线路">{$traffic}
							</textarea>
						</div>
						<div class="market-item market-time">
							<label></label>
							<input type="text" value="Restar 2013-05-20 17:12:42"/>
						</div>
						<input type="hidden" name="a" value="update" />
						<input type="hidden" name="id" value="{$id}" />
		                <input type="hidden" name="order_ids"  class="order-ids" />	
						<div class="market-save">
						<input type="submit" value="保存" class="save-pink"/>
						</div>
					</aside>
					</form>
</div>