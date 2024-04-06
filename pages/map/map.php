<head>
	<title>Map Editor</title>
	<link rel="stylesheet" href="style.css"/>
	<link rel="stylesheet" href="pages/map/map.css"/>
</head>

<body>

<div class="map-page-container" id="bootstrap-overrides">
	<div class="map-container">
		<div id="toolbar">
			<button class="btn btn-primary" id="new-connection">New Connection</button>
			<button class="btn btn-primary" style="visibility: hidden">blank</button>
			<button class="btn btn-primary" id="new-door">New Door</button>
			<button class="btn btn-primary" id="new-entrance">New Entrance</button>
			<button class="btn btn-primary" id="new-junction">New Junction</button>
			<button class="btn btn-primary" id="new-corridor">New Corridor</button>
			<button class="btn btn-primary" id="new-destination">New Destination</button>
		</div>
		<div id="canvas-container">
			<canvas id="map">
		
			</canvas>
		</div>
	</div>
		<div class="info-container" id="info-container">
			<div id="node-info-container">
				<div class="title" id="title" style="min-width:fit-content;"></div>
				<div id="visible-name">
					<label>Display Name</label>
					<input type="text" class="form-control" style="min-width:100%";/>
				</div>
			</div>
			<div id="edge-info-container">
				<div id="route-one">
					<div class="title" id="route-title">From a to b</div>
					<div class="image-container">
						<input type="file"  id="image-edge-upload" style="display: none">
						<button class="btn btn-upload btn-primary ">Upload</button>
						<img src="img/edge_1.jpg" width="200" height="200"/>
					</div>
				</div>
				<div id="route-two">
					<div class="title" id="route-title">From b to a</div>
					<div class="image-container">
						<input type="file"  id="image-edge-upload" style="display: none">
						<button class="btn btn-upload btn-primary">Upload</button>
						<img src="img/edge_1.jpg" width="200" height="200"/>
					</div>
				</div>
			</div>
			<div id="connection-info-container">
				<div class="title"></div>
				<div id="from"></div>
				<div id="to"></div>
			</div>
			<div id="adding-info-container">
				<div class="title"></div>
		    </div>
			<div class="button-container" id="button-container">
				<button class="btn btn-primary btn-danger" id="delete">Delete</button>

				<div class="affirmative-buttons">
					<button class="btn btn-primary" id="cancel">Cancel</button>
					<button class="btn btn-primary" id="apply">Apply Changes</button>
				</div>
			</div>
		</div>
	</div>
</div>

</body>

<script src="pages/map/map.js"></script>