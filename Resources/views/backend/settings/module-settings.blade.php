@extends('layouts.master')

@section('title')
    {{ trans('setting::setting.title.settings') }}
@endsection
@section('subTitle')
    {{ trans('setting::setting.title.module settings') }}
@endsection

@section('content')
    <div class="ui grid">
        <div class="three wide column">
            <div class="ui vertical fluid tabular menu">
                @foreach ($modules as $module => $settings)
                    <a href="{{ URL::route('backend::setting.settings.edit', [$module]) }}"
                       class="item {{ $module != $currentModule->getLowerName() ?: 'active' }}">
                        {{ ucfirst($module) }}
                    </a>
                @endforeach
            </div>
        </div>
        <div class="thirteen wide stretched column">
            <form class="ui form" role="form" method="POST" action="{{route('backend::setting.settings.store')}}">
                @foreach($currentModuleSettings as $sectionTitle => $sectionSettings)
                    <h5 class="ui top attached header">
                        {!! csrf_field() !!}
                        <i class="settings icon"></i>
                        <div class="content">
                            {{ trans($sectionTitle) }}
                        </div>
                    </h5>
                    <div class="ui attached setting segment">
                        @include('setting::backend.partials.fields', ['currentModuleSettings' => $sectionSettings])
                    </div>
                    <div class="ui bottom attached segment">

                        <button type="submit"  class="ui primary button">
                            {{ trans('core::elements.button.update') }}
                        </button>
                        <a class="ui button" href="{{ URL::route('backend::setting.settings.index')}}">
                            {{ trans('core::elements.button.cancel') }}
                        </a>
                    </div>
                @endforeach

            </form>
        </div>
    </div>
@endsection

@section('javascript')

@endsection
