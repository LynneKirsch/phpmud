<br>
<div class="container">
	<ul class="collapsible z-depth-0" data-collapsible="accordion">
		<li>
			<div class="collapsible-header">Thesden</div>
			<div class="collapsible-body">
				<ul class="collapsible z-depth-0" data-collapsible="expandable">
					<li>
						<div class="collapsible-header">Rooms</div>
						<div class="collapsible-body">
							<a href="<?php echo URL; ?>home/editRoom/<?php echo $room->id; ?>" class="waves-effect waves-light btn">Add New</a>
							<ul class="collapsible z-depth-0" data-collapsible="expandable">
								<li>
									<div class="collapsible-header">Devon Square Center</div>
									<div class="collapsible-body">
										<div class="row">
											<div class="col l2">
												Room Name: 
											</div>
											<div class="col l10">
												<input id="room_name" type="text" value="Devon Square Center">
											</div>
										</div>
										<a href="<?php echo URL; ?>home/editRoom/<?php echo $room->id; ?>" class="waves-effect waves-light btn">Save Room</a>
									</div>
								</li>
								<li>
									<div class="collapsible-header">Thesden Town Hall</div>
									<div class="collapsible-body"><span>Lorem ipsum dolor sit amet.</span></div>
								</li>
								<li>
									<div class="collapsible-header">North Devon Square</div>
									<div class="collapsible-body"><span>Lorem ipsum dolor sit amet.</span></div>
								</li>
							</ul>
						</div>
					</li>
					<li>
						<div class="collapsible-header">Objects</div>
						<div class="collapsible-body"><span>Lorem ipsum dolor sit amet.</span></div>
					</li>
					<li>
						<div class="collapsible-header">Mobiles</div>
						<div class="collapsible-body"><span>Lorem ipsum dolor sit amet.</span></div>
					</li>
				</ul>
			</div>
		</li>
		<li>
			<div class="collapsible-header">Second</div>
			<div class="collapsible-body"><span>Lorem ipsum dolor sit amet.</span></div>
		</li>
		<li>
			<div class="collapsible-header">Third</div>
			<div class="collapsible-body"><span>Lorem ipsum dolor sit amet.</span></div>
		</li>
	</ul>
	<div class="row">
		<div class="col l1">
			Room ID
		</div>
		<div class="col l5">
			Room Name
		</div>
		<div class="col l5">
			Room Area
		</div>
	</div>
	<?php foreach($rooms as $room): ?>
		<div class="row">
			<div class="col l1">
				<?php echo $room->id; ?>
			</div>
			<div class="col l5">
				<?php echo $room->name; ?>
			</div>
			<div class="col l5">
				<?php echo $room->area->name; ?>
			</div>
			<div class="col l1">
				<a href="<?php echo URL; ?>home/editRoom/<?php echo $room->id; ?>" class="waves-effect waves-light btn">edit</a>
			</div>
		</div>
	<?php endforeach; ?>
</div>