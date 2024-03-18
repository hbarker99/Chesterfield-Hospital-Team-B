<head>
	<link rel="stylesheet" href="style.css"/>
	<link rel="stylesheet" href="map.css"/>
</head>

<body>

<div class="map-page-container" id="bootstrap-overrides">
	<div class="map-container">
		<div id="toolbar">
		
		</div>
		<div id="canvas-container">
			<canvas id="map">
		
			</canvas>
		</div>
	</div>
		<div class="info-container" id="info-container">
			<div id="title">Entrance</div>
			<div id="node-info-container">
				<div id="visible-name">
					<label>Display Name</label>
					<input type="text" class="form-control" />
				</div>
			</div>
			<div id="edge-info-container">
				<div id="visible-name"></div>
			</div>
			<div class="button-container">
				<input type="submit" class="btn btn-primary" name="previous" value="Cancel" />
				<input type="submit" class="btn btn-primary" name="apply" value="Apply Changes" />
			</div>
		</div>
	</div>
</div>

</body>

<script src="map.js"></script>