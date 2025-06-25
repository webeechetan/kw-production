<div>
    <div class="client-tab @if($client->trashed()) archived_content @endif">
        <div class="dashboard-head pb-0 mb-4">
            <div class="row align-items-center">
                <div class="col">
                    <div class="dashboard-head-title-wrap">
                        @if(!$client->image || $client->image == 'default.png')
                            <div class="client-logo">
                                <span class="avatar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Ac">{{$client->initials}}</span>
                            </div>
                        @else
                            <div class="team-avtar">
                                <span>
                                    <img src="{{ env('APP_URL') }}/storage/{{ $client->image }}" alt="">
                                </span>
                            </div>
                        @endif
                        <div>
                            <h3 class="main-body-header-title mb-2">{{ $client->visible_name }}</h3>
                            <div class="text-sm">Created by <span class="text-secondary font-500">{{ $client->createdBy?->name }}</span></div>
                        </div>
                    </div>
                </div>
                <div class="text-end col">
                    <div class="main-body-header-right">
                        <!-- Edit -->
                        @can('Edit Client')
                            @if(!$client->is_main)
                            <div class="cus_dropdown">
                                <!-- For Active Class = btn-border-success | For Archived Class = btn-border-archived -->
                                @if(!$client->trashed())
                                    <div class="cus_dropdown-icon btn-border btn-border-success">Active <i class='bx bx-chevron-down' ></i></div>
                                @else
                                    <div class="cus_dropdown-icon btn-border btn-border-archived">Archived <i class='bx bx-chevron-down' ></i></div>
                                @endif

                                <div class="cus_dropdown-body cus_dropdown-body-widh_s">
                                    <div class="cus_dropdown-body-wrap">
                                        <ul class="cus_dropdown-list">
                                            <li><a href="javascript:;" wire:click="changeClientStatus('active')" @if(!$client->trashed()) class="active" @endif>Active</a></li>
                                            <li><a href="javascript:;" wire:click="changeClientStatus('archived')" @if($client->trashed()) class="active" @endif>Archive</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            @if(!$client->trashed())
                                <button wire:click="emitEditClient({{$client->id}})" class="btn-sm btn-border btn-border-secondary ">
                                    <span wire:loading wire:target="emitEditClient">
                                        <i class='bx bx-loader-alt bx-spin'></i>
                                    </span> 
                                    <i class='bx bx-pencil'></i> 
                                    Edit
                                </button>
                            @endif
                        @endcan
                        <!-- Delete -->
                        @can('Delete Client')
                        @if(!$client->is_main)
                            {{-- <a href="#" class="btn-sm btn-border btn-border-danger" wire:click="forceDeleteClient({{$client->id}})"><i class='bx bx-trash'></i> Delete</a>--}}
                             <a href="#" class="btn-sm btn-border btn-border-danger" wire:click="forceDeleteClient({{$client->id}})" wire:confirm="Are you sure you want to delete?"><i class='bx bx-trash'></i> Delete</a> 
                        @endif
                        @endcan
                    </div>
                </div>
            </div>
            <hr class="mb-0">
            <div class="tabNavigationBar-tab border_style">
                <a wire:navigate class="tabNavigationBar-item @if($currentRoute == 'client.profile') active @endif" href="{{ route('client.profile', $client->id) }}"><i class='bx bx-line-chart'></i> Overview</a>
                @if(!$client->trashed())
                    <a wire:navigate class="tabNavigationBar-item @if($currentRoute == 'client.projects')) active @endif" href="{{ route('client.projects', $client->id) }}"><i class='bx bx-layer' ></i> Projects</a>
                    <a class="tabNavigationBar-item @if($currentRoute == 'client.file-manager')) active @endif" href="{{ route('client.file-manager', $client->id ) }}"><i class='bx bx-objects-horizontal-left' ></i> File Manager</a>
                @endif
            </div>
        </div>
    </div>
    <livewire:components.add-client @saved="$refresh" />

</div>