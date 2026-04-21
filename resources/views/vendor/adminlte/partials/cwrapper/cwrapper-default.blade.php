@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')
@inject('preloaderHelper', 'JeroenNoten\LaravelAdminLte\Helpers\preloaderHelper')

@if($layoutHelper->isLayoutTopnavEnabled())
    @php( $def_container_class = 'container' )
@else
    @php( $def_container_class = 'container-fluid' )
@endif

{{-- Default Content Wrapper --}}
<div class="{{ $layoutHelper->makeContentWrapperClasses() }}">

    {{-- Preloader Animation (cwrapper mode) --}}
    @if($preloaderHelper->isPreloaderEnabled('cwrapper'))
        @include('adminlte::partials.common.preloader')
    @endif

    {{-- Content Header --}}
    @hasSection('content_header')
        <div class="content-header">
            <div class="{{ config('adminlte.classes_content_header') ?: $def_container_class }}">
                @yield('content_header')
            </div>
        </div>
    @endif

    {{-- Main Content --}}
    <div class="content">
        <div class="{{ config('adminlte.classes_content') ?: $def_container_class }}">
				<div class="mt-2">

					@if(session('success'))
						<div class="alert alert-success alert-dismissible fade show">
							{{ session('success') }}
							<button type="button" class="close" data-dismiss="alert">&times;</button>
						</div>
					@endif

					@if(session('error'))
						<div class="alert alert-danger alert-dismissible fade show">
							{{ session('error') }}
							<button type="button" class="close" data-dismiss="alert">&times;</button>
						</div>
					@endif

				</div>
            @stack('content')
            @yield('content')
        </div>
    </div>

</div>
