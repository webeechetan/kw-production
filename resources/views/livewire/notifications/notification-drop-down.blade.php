<li class="nav-item navbar-dropdown dropdown" wire:click="markAllAsRead" wire:ignore>
  <a href="#" class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0)" data-bs-toggle="dropdown"
    data-bs-auto-close="outside" aria-expanded="true">
    <i class='bx bx-bell'></i>
  </a>
  
    <span class="badge bg-danger rounded-pill badge-notifications @if(!$unreadNotifications->count()) d-none @endif">
      {{ $unreadNotifications->count() }} 
    </span>
  <ul class="dropdown-menu dropdown-menu-end py-0" data-bs-popper="static">
    <li class="dropdown-menu-header">
      <div class="dropdown-header">
        <h5 class="fs-5 mb-0 me-auto">Notification</h5>
        @if(count($notifications) > 0)
          <a href="javascript:void(0);" wire:click="clearAll" class="text-decoration-underline text-danger">Clear All</a>        
        @endif
      </div>
    </li>
    <li class="activity-recent-scroll custom_scrollbar pe-0">
      <ul class="ps-0">
        @forelse ($notifications as $notification)
        <li class="border-bottom d-grid dropdown-item">
          <div class="d-flex justify-content-between cursor-pointer">
            <div class="col-auto pe-2">
              @if($notification->actionBy)
                <x-avatar :user="$notification->actionBy" />
              @else
                <x-avatar :user="$notification->user" />
              @endif
            </div>
            <div class="team-text col">
              <div class="mb-1 text-sm">
                <a href="{{ $notification->url }}">
                  {{ Str::limit($notification->title, 50, '...') }}
                </a>
              </div>
              <span class="text-sm text-muted">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</span>
            </div>
            {{-- <div class="col-auto">
              <div>
                <a href="javascript:void(0);void(0)" class="dropdown-notifications-archive">
                  <span class="bx bx-x"></span>
                </a>
              </div>
            </div> --}}
          </div>
        </li>
        @empty
        <div class="p-4 text-center">
          <img src="{{ asset('assets/images/'.'signup_welcome.png') }}" width="80" alt="">
          <h3 class="mt-2">Woo!</h3>
          <h6 class="text-danger mb-2">No Notification</h6>
          <p>You don't have notification</p>
        </div>
        @endforelse
      </ul>
    </li>
  </ul>
</li>

@assets
  <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
@endassets

@script
  <script>
    $(document).ready(function() {

      var pusher = new Pusher('d7930b1b0598bf366431', {
          cluster: 'ap2'
        });
        
      var channel = pusher.subscribe('notification-channel-'+{{ auth()->user()->id }});
      channel.bind('notification-event-'+{{ auth()->user()->id }}, function(data) {
        console.log(data.sender_data)
        $('.badge-notifications').removeClass('d-none');
        $('.badge-notifications').text($('.badge-notifications').text() == '' ? 1 : parseInt($('.badge-notifications').text()) + 1);
        let created_Date = new Date(data.data.created_at);
        let user_image = data.sender_data.image;
        if(data.sender_data.image == null) {
          user_image = `<a href="#" class="avatarGroup-avatar">
                        <span title="${data.sender_data.name}" class="avatar  avatar-pink" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="${data.sender_data.name}">
                          ${data.sender_data.name.charAt(0)}
                        </span>
                        </a>`;
        }else{
          user_image = `<img src="{{ env('APP_URL') }}/storage/${data.sender_data.image}" class="rounded-circle" width="40" alt="">`;
        }

        let notification_html = `
          <li class="border-bottom d-grid dropdown-item">
            <div class="d-flex justify-content-between cursor-pointer">
              <div class="col-auto pe-2">
                ${user_image}
              </div>
              <div class="team-text col">
                <div class="mb-1 text-sm">
                  <a href="${data.data.url}">
                    ${data.data.message}
                  </a>
                </div>
                <span class="text-sm text-muted">
                  ${created_Date.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true })}
                  </span>
              </div>
            </div>
          </li>
        `;
        $('.activity-recent-scroll ul').prepend(notification_html);
      });

      $(".navbar-dropdown dropdown").on('click', function() {
        $('.badge-notifications').addClass('d-none');
        $('.badge-notifications').text('');
      });
    });
    
  </script>
@endscript
