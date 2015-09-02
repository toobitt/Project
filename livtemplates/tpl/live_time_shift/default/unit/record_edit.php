<div id="record-edit">
	<div class="record-edit">
		<div class="record-edit-btn-area clear">
				{{if status == 1}}
					{{if is_mobile_phone == 1}}
						<a href="./run.php?mid={$_INPUT['mid']}&a=load_time_shift&channel_id=${id}&infrm=1">直播时移</a>
					{{/if}}
				{{/if}}
				<a href="./run.php?mid={$_INPUT['mid']}&a=look_up_shift&channel_id=${id}&infrm=1">查看时移</a>
		</div>
		<div class="record-edit-line mt20"></div>
		<span class="record-edit-close"></span>
	</div>
</div>