<div>
    <div wire:ignore class="modal fade modal-lg" id="add-role-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center justify-content-between gap-20">
                    <h3 class="modal-title"><span class="btn-icon btn-icon-primary me-1"><i class='bx bx-user'></i></span> <span class="role-form-text">Add Role</span></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit="addRole" method="POST" enctype="multipart/form-data">
                        <div class="modal-form-body">
                            <div class="row">
                                <div class="col-md-5 mb-4">
                                    <label for="">Role Name<sup class="text-primary">*</sup></label>
                                </div>
                                <div class="col-md-7 mb-4">
                                    <input wire:model="role_name" type="text" class="form-style role-name-input" placeholder="Role Name">
                                </div>
                                <span class="text-danger">@error('role_name') {{ $message }} @enderror</span>
                            </div>
                            <div class="row">
                                <div class="table-responsive">
                                    <table class="table table-flush-spacing">
                                      <tbody>
                                        <tr>
                                          <td class="text-nowrap fw-medium">Administrator Access <i class="bx bx-info-circle bx-xs" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Allows a full access to the system" data-bs-original-title="Allows a full access to the system"></i></td>
                                          <td>
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" id="selectAll">
                                              <label class="form-check-label" for="selectAll">
                                                Select All
                                              </label>
                                            </div>
                                          </td>
                                        </tr>
                                        {{-- Client --}}
                                        <tr>
                                            <td class="text-nowrap fw-medium">Client Management</td>
                                            <td>
                                              <div class="d-flex justify-content-between">
                                                  @foreach($client_permissions as $permission)
                                                  <div class="form-check">
                                                      <input class="form-check-input" wire:model="client_selected_permissions" value="{{ $permission->id }}" type="checkbox" id="ClientManagementRead">
                                                      <label class="form-check-label" for="ClientManagementRead">
                                                          {{ str_replace('Client', ' ', $permission->name)}}
                                                      </label>
                                                  </div>
                                                  @endforeach
                                              </div>
                                            </td>
                                        </tr>
                                        {{-- Project --}}
                                        <tr>
                                            <td class="text-nowrap fw-medium">Project Management</td>
                                            <td>
                                              <div class="d-flex justify-content-between">
                                                    @foreach($project_permissions as $permission)
                                                        <div class="form-check">
                                                            <input class="form-check-input" wire:model="project_selected_permissions" value="{{ $permission->id }}" type="checkbox" id="ProjectManagementRead">
                                                            <label class="form-check-label" for="ProjectManagementRead">
                                                                {{ str_replace('Project', ' ', $permission->name)}}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>
                                        {{-- User --}}
                                        <tr>
                                            <td class="text-nowrap fw-medium">User Management</td>
                                            <td>
                                              <div class="d-flex justify-content-between">
                                                  @foreach($user_permissions as $permission)
                                                  <div class="form-check">
                                                      <input class="form-check-input" wire:model="user_selected_permissions" value="{{ $permission->id }}" type="checkbox" id="userManagementRead">
                                                      <label class="form-check-label" for="userManagementRead">
                                                          {{ str_replace('User', ' ', $permission->name)}}
                                                      </label>
                                                  </div>
                                                  @endforeach
                                              </div>
                                            </td>
                                          </tr>
                                        {{-- Team --}}
                                        <tr>
                                            <td class="text-nowrap fw-medium">Team Management</td>
                                            <td>
                                              <div class="d-flex justify-content-between">
                                                    @foreach($team_permissions as $permission)
                                                        <div class="form-check">
                                                            <input class="form-check-input" wire:model="team_selected_permissions" value="{{ $permission->id }}" type="checkbox" id="TeamManagementRead">
                                                            <label class="form-check-label" for="TeamManagementRead">
                                                                {{ str_replace('Team', ' ', $permission->name)}}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>
                                        {{-- Task --}}
                                        <tr>
                                            <td class="text-nowrap fw-medium">Task Management</td>
                                            <td>
                                              <div class="d-flex justify-content-between">
                                                    @foreach($task_permissions as $permission)
                                                        <div class="form-check">
                                                            <input class="form-check-input" wire:model="task_selected_permissions" value="{{ $permission->id }}" type="checkbox" id="TaskManagementRead">
                                                            <label class="form-check-label" for="TaskManagementRead">
                                                                {{ str_replace('Task', ' ', $permission->name)}}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>
                                        {{-- Role --}}
                                        <tr>
                                          <td class="text-nowrap fw-medium">Role Management</td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    @foreach($role_permissions as $permission)
                                                        <div class="form-check">
                                                            <input class="form-check-input" wire:model="role_selected_permissions" value="{{ $permission->id }}" type="checkbox" id="roleManagementRead">
                                                            <label class="form-check-label" for="roleManagementRead">
                                                                {{ str_replace('Role', ' ', $permission->name)}}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>
                            </div>
                        </div>
                        <div class="modal-form-btm">
                            <div class="row">
                                <div class="col-md-6 ms-auto text-end">
                                    <button type="submit" class="btn btn-primary role-form-btn">Add Role</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    $(document).ready(function() {

        $('#selectAll').click(function(event) {
            if(this.checked) {
                $(':checkbox').each(function() {
                    this.checked = true;
                });
                @this.set('client_selected_permissions', @json($client_permissions->pluck('id')));
                @this.set('project_selected_permissions', @json($project_permissions->pluck('id')));
                @this.set('user_selected_permissions', @json($user_permissions->pluck('id')));
                @this.set('team_selected_permissions', @json($team_permissions->pluck('id')));
                @this.set('task_selected_permissions', @json($task_permissions->pluck('id')));
                @this.set('role_selected_permissions', @json($role_permissions->pluck('id')));
            } else {
                $(':checkbox').each(function() {
                    this.checked = false;
                });
                @this.set('client_selected_permissions', []);
                @this.set('project_selected_permissions', []);
                @this.set('user_selected_permissions', []);
                @this.set('team_selected_permissions', []);
                @this.set('task_selected_permissions', []);
                @this.set('role_selected_permissions', []);
            }
        });

        function initPlugins(){
            $('.permissions').select2({
                placeholder: 'Select Permissions',
                allowClear: true,
                closeOnSelect: false
            });
        }

        setTimeout(() => {
            initPlugins();
        }, 1500);

        $(document).on('change', '.permissions', function(){
            @this.set('selected_permissions', $(this).val());
        });
    });
</script>
@endscript