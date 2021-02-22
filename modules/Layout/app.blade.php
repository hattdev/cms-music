@includeIf("Layout::parts.header")
<div id="app" class="wrapper d-flex align-items-stretch js-fullheight h-100">
	@if(Auth::check())
		<hatt-menu-mobile></hatt-menu-mobile>
	@endif
	<div class="w-100 h-100">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12 my-4">
					@if(Auth::check())
						<hatt-menu></hatt-menu>
					@endif
				</div>
			
			</div>
		
		</div>
		@yield("content")
	</div>
</div>

@includeIf("Layout::parts.footer")
