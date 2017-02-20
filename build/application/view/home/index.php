<div class="builder_side_nav">
	<ul class="collapsible z-depth-0" data-collapsible="expandable" style="margin-top: 0;">
		<li>
			<div class="collapsible-header">Rooms</div>
			<div class="collapsible-body">
				<ul class="collapsible z-depth-0" data-collapsible="expandable" style="margin-top: 0;">
					<?php foreach($areas as $area): ?>
					<li>
						<div class="collapsible-header">
							<?php echo $area->name; ?>
							<span><i class="material-icons right" onclick="editArea(<?php echo $area->id; ?>)">mode_edit</i></span>
						</div>
						<div class="collapsible-body">
							<?php foreach($area->rooms as $room): ?>
								<ul class="collapsible z-depth-0" data-collapsible="expandable" style="margin-top: 0;">
									<li onclick="editRoom(<?php echo $room->id; ?>)">
										<a class="collapsible-header"><?php echo $room->name; ?></a>
									</li>
								</ul>
							<?php endforeach; ?>
						</div>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</li>
		<li>
			<div class="collapsible-header">Objects</div>
			<div class="collapsible-body">
				<ul class="collapsible z-depth-0" data-collapsible="expandable" style="margin-top: 0;">
					<li>
						<div class="collapsible-header">Weapon</div>
						<div class="collapsible-body">
							<ul class="collapsible z-depth-0" data-collapsible="expandable" style="margin-top: 0;">
								<li>
									<div class="collapsible-header">Great Sword of Thesden</div>
								</li>
							</ul>
						</div>
					</li>
					<li>
						<div class="collapsible-header">Armor</div>
						<div class="collapsible-body">
							<ul class="collapsible z-depth-0" data-collapsible="expandable" style="margin-top: 0;">
								<li>
									<div class="collapsible-header">Head</div>
									<div class="collapsible-body">
										<ul class="collapsible z-depth-0" data-collapsible="expandable" style="margin-top: 0;">
											<li>
												<div class="collapsible-header">Helmet</div>
											</li>
										</ul>
									</div>
								</li>
							</ul>
						</div>
					</li>
				</ul>
			</div>
		</li>
		<li>
			<div class="collapsible-header">Mobiles</div>
			<div class="collapsible-body">
				<ul class="collapsible z-depth-0" data-collapsible="expandable" style="margin-top: 0;">
					<li>
						<div class="collapsible-header">Thesden</div>
						<div class="collapsible-body">
							<ul class="collapsible z-depth-0" data-collapsible="expandable" style="margin-top: 0;">
								<li>
									<div class="collapsible-header">Devon Square Center</div>
								</li>
							</ul>
						</div>
					</li>
					<li>
						<div class="collapsible-header">The Abbey</div>
						<div class="collapsible-body">
							<ul class="collapsible z-depth-0" data-collapsible="expandable" style="margin-top: 0;">
								<li>
									<div class="collapsible-header">Devon Square Center</div>
								</li>
							</ul>
						</div>
					</li>
				</ul>
			</div>
		</li>
	</ul>
</div>
<div id="main_content">fds</div>