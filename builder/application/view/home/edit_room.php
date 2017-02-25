<div class="container">
	<div class="row">
		<div class="col l12">
			<h2>Editing Room: <?php echo $room->name; ?></h2>
		</div>
	</div>
	<div class="row">
		<div class="col l3">
			Room Name: 
			<input type="text" value="<?php echo $room->name; ?>">
		</div>
		<div class="col l3">
			Room Area: 
			<input type="text" value="<?php echo $room->area->name; ?>">
		</div>
	</div>
	<div class="row">
		<div class="col l12">
			Room Description: 
			<textarea style="height: 200px"><?php echo $room->description; ?></textarea>
		</div>
	</div>
	<div class="row">
		<div class="col l2">
			North 
			<input type="text" value="<?php echo $room->north_to; ?>">
		</div>
		<div class="col l2">
			South 
			<input type="text" value="<?php echo $room->south_to; ?>">
		</div>
		<div class="col l2">
			East 
			<input type="text" value="<?php echo $room->east_to; ?>">
		</div>
		<div class="col l2">
			West 
			<input type="text" value="<?php echo $room->west_to; ?>">
		</div>
		<div class="col l2">
			Up 
			<input type="text" value="<?php echo $room->up_to; ?>">
		</div>
		<div class="col l2">
			Down 
			<input type="text" value="<?php echo $room->down_to; ?>">
		</div>
	</div>
	
	<div class="row">
		<div class="col l8">
			<h4>Doors <a class="waves-effect waves-light btn"><i class="material-icons">add</i></a></h4> 
			
		</div>
	</div>
	<div class="row">
		<div class="col l2">
			Exit 
			<input type="text" value="">
		</div>
		<div class="col l2">
			Name 
			<input type="text" value="">
		</div>
		<div class="col l2">
			Locked 
			<input type="text" value="">
		</div>
		<div class="col l2">
			Lock Difficulty (1 - 5)
			<input type="text" value="">
		</div>
	</div>
	
	
	<div class="row">
		<div class="col l8">
			<h4>Mobiles <a class="waves-effect waves-light btn"><i class="material-icons">add</i></a></h4> 
		</div>
	</div>
	<div class="row">
		<div class="col l2">
			ID 
			<input type="text" value="">
		</div>
		<div class="col l2">
			Quantity 
			<input type="text" value="">
		</div>
	</div>
	
	<div class="row">
		<div class="col l8">
			<h4>Objects <a class="waves-effect waves-light btn"><i class="material-icons">add</i></a></h4> 
		</div>
	</div>
	<div class="row">
		<div class="col l2">
			ID 
			<input type="text" value="">
		</div>
		<div class="col l2">
			Quantity 
			<input type="text" value="">
		</div>
		<div class="col l2">
			Where 
			<input type="text" value="">
		</div>
	</div>

</div>