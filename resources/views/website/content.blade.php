@extends('layouts.website')

@section('head')
    @if($meta != null)
        <title>{{$meta->title}}</title>
        <meta name="description" content="{{$meta->description}}">
        <meta name="keywords" content="{{$meta->keywords}}">
        <!-- Twitter Card data -->
        <meta name="twitter:title" content="{{$meta->title}}">
        <meta name="twitter:description" content="{{$meta->description}}">
        <meta name="twitter:image" content="{{$meta->media}}">
        <meta name="twitter:card" content="summary">
        <!-- Open Graph data -->
        <meta property="og:title" content="{{$meta->title}}" />
        <meta property="og:description" content="{{$meta->description}}" />
        <meta property="og:image" content="{{$meta->media}}" />
        <meta property="og:type" content="article" />
        <meta property="og:site_name" content="{{$meta->title}}" />
    @endif
@endsection

@section('content')
    <div class="container py-5">
        @auth
            @if(Auth::user()->isAdministrator() || Auth::user()->isWebAdmin())
                <div class="alert alert-info border-0 shadow-sm rounded-4 p-3 mb-4 d-flex align-items-center justify-content-between flex-wrap g-3" style="background: linear-gradient(135deg, #e0e7ff 0%, #e8f0fe 100%); border-left: 4px solid #4f46e5 !important;">
                    <div class="d-flex align-items-center">
                        <div class="p-2 bg-white rounded-circle text-primary me-3 mr-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 font-weight-bold text-dark" style="font-family: 'Inter', sans-serif;">Administrator Mode</h6>
                            <span class="text-muted small">You are viewing this page with editing privileges. You can modify its content.</span>
                        </div>
                    </div>
                    <div>
                        <a href="/administrator/website_manager/pages?edit_page={{ urlencode($page->page) }}" class="btn btn-primary px-3 py-2 font-weight-bold rounded-3 shadow-sm d-inline-flex align-items-center gap-2" style="background: #4f46e5; border: none; font-size: 0.8rem; font-family: 'Inter', sans-serif;">
                            <i class="fas fa-edit"></i> Edit Page Content
                        </a>
                    </div>
                </div>
            @endif
        @endauth

        @if($page && $page->content)
            {!! $page->content !!}
        @else
            <div class="text-center py-5">
                <p class="text-muted">No content available for this page yet.</p>
            </div>
        @endif
    </div>
@endsection