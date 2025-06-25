<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }}</title>
    <!-- Fav -->
    <link rel="icon" href="{{ asset('') }}assets/images/fav.png" />
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <!-- Icons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Custom Styles -->
    <link href="{{ asset('') }}assets/css/app.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- Page Init Js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('') }}assets/js/app.js"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"> 
    @livewireStyles
    @stack('styles')
  </head>
  <body>

    @php
    $user = Auth::user();
    @endphp


    <header class="header-main">
      <div class="header-main-wrap">
        <div class="header-main-left">
          <div class="logo">
            <span>
              <img src="{{ asset('') }}assets/images/logo-icon.png" alt="Kaykewalk Logo" />
            </span>
            <span>
              @if(auth()->user()->organization->image)
                <img src="{{ asset('storage/'.auth()->user()->organization->image) }}" alt="Organization Logo" />
              @else
                <img src="{{ asset('') }}assets/images/webeesocial-logo.png" alt="Webeesocial Logo" />
              @endif
            </span>
          </div>
        </div>
        <div class="header-main-right">
          <!-- Header Menu -->
          <nav class="navbar navbar-expand-lg">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#responsive-navbar-nav" aria-controls="responsive-navbar-nav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="responsive-navbar-nav">
              <ul class="navbar-nav align-items-center">                
                <!-- Message -->
                <!-- <livewire:messages.message /> -->
                
                <!-- Notifications -->
                <livewire:notifications.notification-drop-down />

                <!-- Roles & Permission -->
                <li class="nav-item">
                  <a class="nav-link" wire:navigate href="{{ route('role.index') }}" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="Roles & Permission"><i class='bx bx-slider-alt'></i></a>
                </li>
                
                <!-- Login -->
                <li class="nav-item navbar-dropdown dropdown">
                  <a href="#" class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="true">
                    <span class="avatar avatar-sm avatar-{{$user->color}}">{{$user->initials}}</span>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-account" data-bs-popper="static">
                   <li>
                    <div class="d-flex p-3">
                      <div class="avatar avatar-{{$user->color}}">{{$user->initials}}</div>
                      <div class="flex-grow-1 ps-2">
                          <span class="fw-medium d-block">{{$user->name}}</span>
                          <span class="text-muted">
                            @foreach($user->roles as $role)
                              {{ $role->name }}
                            @endforeach
                          </span>
                      </div>
                    </div>
                   </li>
                    <li>
                      <a class="dropdown-item align-items-center" wire:navigate href="{{ route('user.profile',$user->id) }}">
                        <i class="bx bx-user me-2"></i>
                        <span class="align-middle">My Profile</span>
                      </a>
                    </li>
                    <!-- <li>
                      <a class="dropdown-item align-items-center" href="#">
                        <i class="bx bx-cog me-2"></i>
                        <span class="align-middle">Settings</span>
                      </a>
                    </li> -->
                    <li>
                      <a class="dropdown-item align-items-center text-danger" href="{{route('logout')}}" >
                        <i class="bx bx-power-off me-2"></i>
                        <span class="align-middle">Log Out</span>
                      </a>
                    </li>
                  </ul>
                </li>
              </ul>
            </div>
          </nav>
        </div>
      </div>
    </header>
    <div class="main-body">
        <aside class="sidebar-l">
            <div class="sidebar-l-menu">
              @php
                $tour = session()->get('tour');
              @endphp
                <ul class="list-none menu-sidebar">
                    <li>
                      <a wire:navigate href="{{ route('dashboard') }}" class="@if (request()->routeIs('dashboard')) active @endif"><i class='bx bx-line-chart' ></i> Dashboard</a>
                    </li>
                    <li>
                      <li>
                        <a wire:navigate href="{{ route('client.profile',Auth::user()->organization->mainClient->id) }}"  class="@if ( request()->segment(4) == Auth::user()->organization->mainClient->id && (request()->segment(2) == 'clients' || request()->segment(2) == 'client' )) active @endif"><i class='bx bx-body' ></i> 
                          {{ ucfirst(Auth::user()->organization->name) }}
                        </a>
                      </li>
                    </li>
                        @can('View Client')
                            <li 
                            @if(isset($tour) && $tour != null)
                              data-step="1" 
                              data-intro='Create your first client' 
                              data-position='right'
                            @endif
                            >
                            <a wire:navigate href="{{ route('client.index') }}" 
                            class="@if ( request()->segment(4) != Auth::user()->organization->mainClient->id && (request()->segment(2) == 'clients' || request()->segment(2) == 'client' )) active @endif"><i class='bx bx-briefcase-alt-2'></i> Clients</a></li>
                        @endcan
                        @can('View Project')
                            <li @if(isset($tour['main_tour'])) data-step="2" data-position='right' data-intro='Create your first project' @endif><a wire:navigate href="{{ route('project.index') }}" class="@if (request()->segment(2) == 'projects' || request()->segment(2) == 'project' ) active @endif"><i class='bx bx-objects-horizontal-left'></i> Projects</a></li>
                            
                        @endcan
                        @can('View User')
                            <li @if(isset($tour['main_tour'])) data-step="3" data-position='right' data-intro='Create your first user' @endif><a wire:navigate href="{{ route('user.index') }}" class="@if (request()->segment(2) == 'users' || request()->segment(2) == 'user' ) active @endif"><i class='bx bx-user'></i> Users</a></li>
                           
                        @endcan
                        @can('View Team')
                            <li  @if(isset($tour['main_tour'])) data-step="4" data-position='right' data-intro='Create your first team' @endif><a wire:navigate href="{{ route('team.index') }}" class="@if (request()->segment(2) == 'teams' || request()->segment(2) == 'team' ) active @endif"><i class='bx bx-sitemap'></i> Teams</a></li>
                           
                        @endcan                      
                        @can('View Task')
                          <li @if(isset($tour['main_tour'])) data-step="5" data-position='right' data-intro='Create your first task' @endif><a wire:navigate href="{{ route('task.index') }}" class="@if (request()->segment(2)== 'tasks' || request()->segment(2) == 'task') active @endif"><i class='bx bx-task' ></i> Tasks</a></li>
                        @endcan
                </ul>
            </div>
            <div class="sidebar-l-btm">
                <img src="{{ asset('') }}assets/images/logo.png" width="150" alt="Kaykewalk Profile" />
                <p class="mb-0 mt-3">Copyright © 2024, Kaykewalk, All Rights Reserved.</p>
            </div>
        </aside>
      <div class="main-body-content scrollbar scrollbar-primary">
        <div class="text-center">
          <dotlottie-player src="https://lottie.host/644d7824-72cd-4a03-8cfb-e64e010591f8/2SN3cXzzHl.lottie" background="transparent" speed="1" style="width: 300px; margin: auto;" loop autoplay></dotlottie-player>
            <h2>Error 4O3 | Forbidden</h2>
            <p>You Don't have permission to access this</p>
        </div>
        <p class="alert alert-warning" wire:offline> Whoops, your device has lost connection. The web page you are viewing is offline. </p>
      </div>
    </div>
    {{-- <livewire:components.voice-control /> --}}
    
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/annyang/2.6.0/annyang.min.js"></script>
    <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>

    @livewireScripts 
    <script src="https://unpkg.com/@nextapps-be/livewire-sortablejs@0.4.0/dist/livewire-sortable.js"></script>
    @stack('scripts') 
    @if (session()->has('success')) 
    <script>
      toastr.success("{{ session('success') }}")
    </script> 
    @endif 
    @if (session()->has('error')) <script>
      toastr.error("{{ session('error') }}")
    </script> 
    @endif 
    <script>
      document.addEventListener('error', event => {
        toastr.remove()
        toastr.error(event.detail)
      })
      document.addEventListener('success', event => {
        toastr.remove();
        toastr.success(event.detail)
      })
      $('.cus_dropdown-icon').click(function() {
        $(this).parent('.cus_dropdown').toggleClass('open');
      });
      $('.cus_dropdown-icon, .filterSort').click(function() {
        event.stopPropagation();
      }); 
      $('html').click(function() {
        $('.cus_dropdown').removeClass('open');
      });
      document.addEventListener('livewire:navigated', () => {
        setTimeout(() => {
          $(function() {
            $('[data-toggle="tooltip"]').tooltip();
            $('[data-bs-toggle="dropdown"]').dropdown();
          })
        }, 1000);
      });

      $(".image_upload_input").change(function() {
          if (this.files && this.files[0]) {
              var reader = new FileReader();
              reader.onload = function(e) {
                  $('.image-preview-section').removeClass('d-none');
                  $('.image-preview-section').html('<img src="'+e.target.result+'" alt="Image Preview" class="img-fluid" style="max-width: 200px;">');
              }
              reader.readAsDataURL(this.files[0]);
          }
      });

      document.addEventListener('livewire-upload-progress', event => {
          $(".upload-progress").removeClass("d-none");
          let progress = event.detail.progress;
          $(".progress-bar").width(progress + "%");
          $(".progress-bar-text").text(progress + "%");
      });

      document.addEventListener('livewire-upload-finish', event => {
          $(".upload-progress").addClass("d-none");
      });

    </script>
  </body>
</html>