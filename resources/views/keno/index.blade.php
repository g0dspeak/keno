@extends('layouts.main')


@section('header_styles')
    @foreach($cssFiles as $cssFile)
        <link href="{{ $cssFile }}" rel="stylesheet" type="text/css">
    @endforeach
@endsection

@section('header_scripts')
    @foreach($jsFilesHeader as $jsFileHeader)
        <script type="text/javascript" src="{{ $jsFileHeader }}"></script>
    @endforeach
@endsection

@section('content')
    <div class="row">

        <div class="col-md-4">
            @include('keno.wins_table')
        </div>

        <div class="col-md-4">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                @include('keno.keno_form')
                @include('keno.progress')
            </div>
            <div class="col-md-1"></div>
        </div>

        <div class="col-md-4">
            @include('keno.wins_results')
        </div>

    </div>
@endsection

@section('footer_section')
    @foreach($jsFilesFooter as $jsFileFooter)
        <script type="text/javascript" src="{{ $jsFileFooter }}"></script>
    @endforeach
@endsection
