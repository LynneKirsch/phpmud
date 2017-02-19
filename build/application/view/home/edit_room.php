<br>
<div class="container">
	<div class="row">
		<div class="col l12">
			<h2>Editing Room: <?php echo $room->name; ?></h2>
		</div>
	</div>
	<div class="row">
		<div class="col l12">
			  <div class="row">
    <form class="col s12">
      <div class="row">
        <div class="input-field col s6">
			<input id="room_name" type="text" class="validate" value="<?php echo $room->name; ?>">
          <label for="room_name">Room Name</label>
        </div>
        <div class="input-field col s6">
          <input id="last_name" type="text" class="validate">
          <label for="last_name">Last Name</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <textarea id="textarea1" class="materialize-textarea"></textarea>
          <label for="textarea1">Textarea</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <input id="password" type="password" class="validate">
          <label for="password">Password</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <input id="email" type="email" class="validate">
          <label for="email">Email</label>
        </div>
      </div>
      <div class="row">
        <div class="col s12">
          This is an inline input field:
          <div class="input-field inline">
            <input id="email" type="email" class="validate">
            <label for="email" data-error="wrong" data-success="right">Email</label>
          </div>
        </div>
      </div>
    </form>
  </div>
        
		</div>
	</div>
</div>